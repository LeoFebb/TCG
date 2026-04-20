<?php

namespace App\Http\Controllers;

use App\Http\Controllers\EscrowController;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * ValidatorController - Gestisce le azioni del validatore terzo.
 *
 * Il validatore è un soggetto indipendente che:
 * 1. Riceve fisicamente la carta dal venditore
 * 2. Ne verifica l'autenticità e le condizioni
 * 3. Approva o rifiuta la transazione
 * 4. In caso di approvazione, rispedisce la carta all'acquirente
 *
 * Per le PERMUTE, il processo è più complesso:
 * - Riceve carte da ENTRAMBI gli utenti
 * - Verifica entrambe le carte
 * - Solo quando approva entrambe, la transazione procede
 */
class ValidatorController extends Controller
{
    public function __construct(private readonly EscrowController $escrowController) {}

    /**
     * Dashboard del validatore: mostra tutti i task pendenti assegnati.
     */
    public function dashboard()
    {
        // Carica le transazioni assegnate a questo validatore
        // con eager loading per evitare N+1 query
        $pendingTasks = Transaction::with(['card', 'buyer', 'seller', 'offeredCard'])
            ->where(function ($q) {
                $q->where('validator_id', Auth::id())->orWhere('buyer_validator_id', Auth::id());
            })
            ->whereIn('status', ['in_validation', 'paid_escrow', 'accepted', 'pending'])
            ->latest()
            ->paginate(15);

        $completedTasks = Transaction::with(['card', 'buyer', 'seller'])
            ->where(function ($q) {
                $q->where('validator_id', Auth::id())->orWhere('buyer_validator_id', Auth::id());
            })
            ->whereIn('status', ['validated', 'shipping', 'completed', 'disputed'])
            ->latest()
            ->take(10)
            ->get();

        $stats = [
            'pending' => $pendingTasks->total(),
            'validated' => Transaction::where(function ($q) {
                $q->where('validator_id', Auth::id())->orWhere('buyer_validator_id', Auth::id());
            })
                ->whereIn('status', ['validated', 'shipping'])
                ->count(),
            'completed' => Transaction::where(function ($q) {
                $q->where('validator_id', Auth::id())->orWhere('buyer_validator_id', Auth::id());
            })
                ->whereIn('status', ['completed', 'disputed'])
                ->count(),
        ];

        return view('validator.dashboard', compact('pendingTasks', 'completedTasks', 'stats'));
    }

    public function list()
    {
        $validators = \App\Models\User::where('role', 'validator')->where('is_verified_validator', true)->get();

        return view('validators.list', compact('validators'));
    }

    /**
     * Mostra i dettagli di una singola transazione da validare.
     * Include le immagini della carta e la storia della transazione.
     */
    public function show(Transaction $transaction)
    {
        abort_if($transaction->validator_id !== Auth::id() && $transaction->buyer_validator_id !== Auth::id(), 403);
        $transaction->load(['card', 'buyer', 'seller', 'offeredCard.owner']);
        return view('validator.transaction-detail', compact('transaction'));
    }

    /**
     * Approva una transazione dopo aver verificato fisicamente la carta.
     *
     * FLUSSO VENDITA:
     *   in_validation → validated → [EscrowController::releaseFunds()] → shipping
     *
     * FLUSSO PERMUTA:
     *   in_validation → validated → shipping (nessun trasferimento monetario)
     */
    public function approve(Request $request, Transaction $transaction)
    {
        // Validazione input
        $validated = $request->validate([
            'validator_notes' => 'nullable|string|max:1000',
        ]);

        abort_if($transaction->validator_id !== Auth::id() && $transaction->buyer_validator_id !== Auth::id(), 403);
        abort_if(!in_array($transaction->status, ['in_validation', 'accepted', 'pending']), 422, 'Questa transazione non è in attesa di validazione.');
        DB::transaction(function () use ($transaction, $validated) {
            if ($transaction->type === 'trade') {
                // Per le permute ogni validatore approva solo la sua carta
                if ($transaction->buyer_validator_id === Auth::id()) {
                    $transaction->update(['buyer_validated' => true]);
                } else {
                    $transaction->update(['seller_validated' => true]);
                }
                $transaction->refresh();
                // Solo quando entrambi approvano → shipping
                if ($transaction->seller_validated && $transaction->buyer_validated) {
                    $transaction->update([
                        'status' => 'shipping',
                        'validated_at' => now(),
                    ]);
                    $transaction->card->update(['is_validated' => true]);
                }
                Log::info('Transaction validated', [
                    'transaction_id' => $transaction->id,
                    'validator_id' => Auth::id(),
                    'type' => $transaction->type,
                ]);
            } else {
                // Per le VENDITE
                $transaction->update([
                    'status' => 'validated',
                    'validated_at' => now(),
                    'validator_notes' => $validated['validator_notes'] ?? null,
                ]);
                Log::info('Transaction validated', [
                    'transaction_id' => $transaction->id,
                    'validator_id' => Auth::id(),
                    'type' => $transaction->type,
                ]);
                $transaction->card->update(['is_validated' => true]);
                $this->escrowController->releaseFunds($transaction);
                $transaction->refresh();
            }
        });

        $message = $transaction->type === 'sale' ? 'Transazione approvata con successo. I fondi sono stati rilasciati al venditore.' : 'Transazione approvata con successo.';

        return redirect()->route('validator.dashboard')->with('success', $message);
    }

    /**
     * Rifiuta una transazione (carta non autentica o condizioni non corrispondenti).
     *
     * In caso di rifiuto:
     * - La transazione va in stato 'disputed' per revisione dell'admin
     * - I fondi rimangono bloccati in escrow fino a decisione dell'admin
     * - Entrambe le parti vengono notificate (TODO: implementare notifiche email)
     */
    public function reject(Request $request, Transaction $transaction)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string|min:20|max:2000',
        ]);

        abort_if($transaction->validator_id !== Auth::id() && $transaction->buyer_validator_id !== Auth::id(), 403);
        abort_if(!in_array($transaction->status, ['in_validation', 'paid_escrow', 'accepted', 'pending', 'shipping']), 422);

        DB::transaction(function () use ($transaction, $validated) {
            $transaction->update([
                'status' => 'disputed',
                'validator_notes' => "RIFIUTO: {$validated['rejection_reason']}",
                'validated_at' => now(),
            ]);

            // Per le vendite: rimborsa l'acquirente su Stripe
            if ($transaction->type === 'sale' && $transaction->stripe_intent_id) {
                try {
                    \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
                    $paymentIntent = \Stripe\PaymentIntent::retrieve($transaction->stripe_intent_id);
                    if ($paymentIntent->status === 'succeeded') {
                        \Stripe\Refund::create([
                            'payment_intent' => $transaction->stripe_intent_id,
                        ]);
                        Log::info('Rimborso effettuato per transazione #' . $transaction->id);
                    }
                } catch (\Exception $e) {
                    Log::error('Errore rimborso Stripe: ' . $e->getMessage());
                }
            }

            // Per le vendite: il venditore deve pagare le spese di spedizione
            if ($transaction->type === 'sale') {
                $transaction->seller->update([
                    'has_shipping_debt' => true,
                    'shipping_debt_amount' => $transaction->seller->shipping_debt_amount + 5.9,
                ]);
            }

            // La carta torna disponibile
            // Togli la carta dal marketplace
            // Per le vendite: togli la carta dal marketplace
// Per le permute: le carte tornano disponibili per entrambi
if ($transaction->type === 'sale') {
    $transaction->card->update(['status' => 'unavailable']);
} else {
    $transaction->card->update(['status' => 'available']);
    // Cerca la carta dell'altro utente nella permuta e rendila disponibile
    $otherCard = \App\Models\Card::where('user_id', $transaction->buyer_id)
        ->where('status', 'in_negotiation')
        ->latest()
        ->first();
    if ($otherCard) {
        $otherCard->update(['status' => 'available']);
    }
}

            Log::warning('Transaction rejected by validator', [
                'transaction_id' => $transaction->id,
                'validator_id' => Auth::id(),
                'reason' => $validated['rejection_reason'],
            ]);
        });

        return redirect()->route('validator.dashboard')->with('warning', 'Transazione rifiutata. L\'admin esaminerà la controversia.');
    }

    /**
     * Conferma la ricezione fisica di una carta (per le permute).
     * Il validatore deve confermare ricezione di ENTRAMBE le carte
     * prima che la validazione possa procedere.
     */
    public function confirmReceived(Request $request, Transaction $transaction)
    {
        abort_if($transaction->validator_id !== Auth::id() && $transaction->buyer_validator_id !== Auth::id(), 403);

        if ($transaction->type === 'trade') {
            // Per le permute: ogni validatore conferma la propria ricezione
            if ($transaction->buyer_validator_id === Auth::id()) {
                $transaction->update(['buyer_validator_received' => true]);
            } else {
                $transaction->update(['validator_received' => true]);
            }
            // Se entrambi hanno ricevuto, aggiorna lo stato
            $transaction->refresh();
            if ($transaction->validator_received && $transaction->buyer_validator_received) {
                $transaction->update(['status' => 'in_validation']);
            }
        } else {
            // Per le vendite: solo il validatore del venditore
            abort_if($transaction->validator_id !== Auth::id(), 403);
            $transaction->update(['validator_received' => true, 'status' => 'in_validation']);
        }

        return back()->with('success', 'Ricezione carta confermata.');
    }

    public function markShipped(Request $request, Transaction $transaction)
    {
        abort_if($transaction->validator_id !== Auth::id() && $transaction->buyer_validator_id !== Auth::id(), 403);
        abort_if(!in_array($transaction->status, ['validated', 'shipping']), 422);

        if ($transaction->buyer_validator_id === Auth::id()) {
            $transaction->update([
                'buyer_validator_shipped' => true,
                'return_tracking_number' => $request->return_tracking_number,
            ]);
        }

        if ($transaction->type === 'trade') {
            if ($transaction->buyer_validator_id === Auth::id()) {
                $transaction->update([
                    'buyer_validator_shipped' => true,
                    'return_tracking_number' => $request->return_tracking_number,
                    'status' => 'completed',
                ]);
            } else {
                $transaction->update([
                    'validator_shipped' => true,
                    'return_tracking_number' => $request->return_tracking_number,
                    'status' => 'completed',
                ]);
            }
        } else {
            $transaction->update([
                'return_tracking_number' => $request->return_tracking_number,
                'status' => 'shipping',
            ]);
        }

        return redirect()->back()->with('success', 'Spedizione confermata!');
    }

    public function markReceived(Transaction $transaction)
    {
        abort_if($transaction->validator_id !== Auth::id() && $transaction->buyer_validator_id !== Auth::id(), 403);

        if ($transaction->type === 'trade') {
            if ($transaction->buyer_validator_id === Auth::id()) {
                $transaction->update([
                    'buyer_validator_received' => true,
                    'buyer_shipped' => true,
                ]);
            } else {
                $transaction->update([
                    'validator_received' => true,
                    'seller_shipped' => true,
                ]);
            }
            $transaction->refresh();
            if ($transaction->validator_received && $transaction->buyer_validator_received) {
                $transaction->update(['status' => 'in_validation']);
                if ($transaction->validator_id !== $transaction->buyer_validator_id) {
                    \App\Models\ChatRoom::firstOrCreate([
                        'transaction_id' => $transaction->id,
                        'user_1_id' => $transaction->validator_id,
                        'user_2_id' => $transaction->buyer_validator_id,
                    ]);
                }
            }
        } else {
            abort_if($transaction->validator_id !== Auth::id(), 403);
            $transaction->update([
                'validator_received' => true,
                'seller_shipped' => true,
                'status' => 'in_validation',
            ]);
        }

        return redirect()->back()->with('success', 'Carta ricevuta confermata!');
    }
}
