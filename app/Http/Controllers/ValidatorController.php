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
            ->where('validator_id', Auth::id())
            ->whereIn('status', ['in_validation', 'paid_escrow'])
            ->latest()
            ->paginate(15);

        $completedTasks = Transaction::with(['card', 'buyer', 'seller'])
            ->where('validator_id', Auth::id())
            ->whereIn('status', ['validated', 'shipping', 'completed'])
            ->latest()
            ->take(10)
            ->get();

        $stats = [
            'pending' => $pendingTasks->total(),
            'validated' => Transaction::where('validator_id', Auth::id())
                ->whereIn('status', ['validated', 'shipping'])
                ->count(),
            'completed' => Transaction::where('validator_id', Auth::id())->where('status', 'completed')->count(),
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
        // Il validatore può vedere solo le transazioni assegnate a lui
        abort_if($transaction->validator_id !== Auth::id(), 403);

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

        abort_if($transaction->validator_id !== Auth::id(), 403);
        abort_if($transaction->status !== 'in_validation', 422, 'Questa transazione non è in attesa di validazione.');

        DB::transaction(function () use ($transaction, $validated) {
            // Per le PERMUTE: verifica che entrambi abbiano spedito le carte
            if ($transaction->type === 'trade') {
                if (!$transaction->buyer_shipped || !$transaction->seller_shipped) {
                    abort(422, 'Non tutte le carte sono state ricevute dal validatore.');
                }
            }

            // Aggiorna la transazione come validata
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

            // Marca la carta come validata
            $transaction->card->update(['is_validated' => true]);

            // Per le VENDITE: rilascia i fondi al venditore
            // Per le PERMUTE: non ci sono fondi da rilasciare
            if ($transaction->type === 'sale') {
                $this->escrowController->releaseFunds($transaction);
                $transaction->refresh();
            } else {
                // Per le permute, passa direttamente a shipping
                $transaction->update(['status' => 'shipping']);

                // Aggiorna i proprietari delle carte scambiate
                $card = $transaction->card;
                $offeredCard = $transaction->offeredCard;

                // Scambia i proprietari delle carte
                $card->update(['user_id' => $transaction->buyer_id]);
                $offeredCard->update(['user_id' => $transaction->seller_id]);
            }
        });

        return redirect()->route('validator.dashboard')->with('success', 'Transazione approvata con successo. I fondi sono stati rilasciati al venditore.');
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

        abort_if($transaction->validator_id !== Auth::id(), 403);
        abort_if(!in_array($transaction->status, ['in_validation', 'paid_escrow']), 422);

        DB::transaction(function () use ($transaction, $validated) {
            $transaction->update([
                'status' => 'disputed',
                'validator_notes' => "RIFIUTO: {$validated['rejection_reason']}",
                'validated_at' => now(),
            ]);

            // La carta rimane bloccata - non torna in vendita
            // L'admin deciderà il destino finale della carta
            $transaction->card->update(['status' => 'in_negotiation']);

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
        $validated = $request->validate([
            'party' => 'required|in:buyer,seller',
        ]);

        abort_if($transaction->validator_id !== Auth::id(), 403);
        abort_if($transaction->type !== 'trade', 422);

        $field = $validated['party'] === 'buyer' ? 'buyer_shipped' : 'seller_shipped';
        $transaction->update([$field => true]);

        // Se entrambe le carte sono arrivate, aggiorna lo stato
        $transaction->refresh();
        if ($transaction->buyer_shipped && $transaction->seller_shipped) {
            $transaction->update(['status' => 'in_validation']);
        }

        return back()->with('success', 'Ricezione carta confermata.');
    }

    public function markShipped(Request $request, Transaction $transaction)
    {
        \Illuminate\Support\Facades\Log::info('markShipped called', [
            'transaction_id' => $transaction->id,
            'transaction_status' => $transaction->status,
            'validator_id' => $transaction->validator_id,
            'auth_id' => Auth::id(),
        ]);

        abort_if($transaction->validator_id !== Auth::id(), 403);
        abort_if($transaction->status !== 'validated', 422);

        $transaction->update([
            'return_tracking_number' => $request->return_tracking_number,
            'status' => 'shipping',
        ]);

        return redirect()->back()->with('success', 'Spedizione confermata!');
    }
}
