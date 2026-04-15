<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Transaction;
use App\Models\User;
use App\Services\EscrowService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\ApiErrorException;

class EscrowController extends Controller
{
    private const SHIPPING_COST = 590; // €5.90 = una spedizione

    public function __construct(private readonly EscrowService $escrowService) {}

    /**
     * Mostra la pagina di checkout per l'acquisto di una carta.
     */
    public function checkout(Request $request, Card $card)
    {
        // Controlla se c'è una transazione scaduta per questa carta
        $expiredTransaction = Transaction::where('card_id', $card->id)->where('status', 'pending_payment')->where('checkout_expires_at', '<', now())->first();

        if ($expiredTransaction) {
            $expiredTransaction->delete();
            $card->update(['status' => 'available']);
        }

        abort_if($card->status !== 'available', 422, 'Questa carta non è più disponibile.');
        abort_if($card->user_id === Auth::id(), 403, 'Non puoi acquistare la tua stessa carta.');
        abort_if($card->price === null, 422, 'Questa carta non è in vendita diretta.');
        abort_if(Auth::user()->role === 'validator', 403, 'I validatori non possono acquistare carte.');

        // Assegna un validatore disponibile
        // Recupera i validatori disponibili per la categoria
        $validators = \App\Models\User::where('role', 'validator')
            ->where('is_verified_validator', true)
            ->whereJsonContains('tcg_categories', $card->tcg_category)
            ->where('id', '!=', $card->user_id) // Escludi il venditore
            ->get();
        // Crea subito la transazione nel database
        $transaction = Transaction::create([
            'buyer_id' => Auth::id(),
            'seller_id' => $card->user_id,
            'card_id' => $card->id,
            'validator_id' => null,
            'type' => 'sale',
            'status' => 'pending_payment',
            'amount' => $card->price,
            'platform_fee' => $this->escrowService->calculatePlatformFee($card->price),
            'shipping_cost' => self::SHIPPING_COST / 100,
            'shipping_country' => 'Italia',
            'checkout_expires_at' => now()->addMinutes(5),
        ]);

        // Blocca la carta subito
        $card->update(['status' => 'in_negotiation']);

        $clientSecret = null;
        $stripePublicKey = config('services.stripe.key');
        try {
            $totalAmount = (int) ($card->price * 100) + self::SHIPPING_COST;
            $paymentIntent = $this->escrowService->createPaymentIntent(amount: $totalAmount, sellerId: $card->user_id, cardId: $card->id);
            $clientSecret = $paymentIntent->client_secret;
            $transaction->update(['stripe_intent_id' => $paymentIntent->id]);
        } catch (\Exception $e) {
        } catch (\Exception $e) {
            Log::error('Stripe checkout error: ' . $e->getMessage() . ' - ' . $e->getFile() . ':' . $e->getLine());
        }

        return view('marketplace.checkout', [
            'card' => $card,
            'seller' => $card->owner,
            'clientSecret' => $clientSecret,
            'stripePublicKey' => $stripePublicKey,
            'platformFee' => $this->escrowService->calculatePlatformFee($card->price),
            'sellerReceives' => $card->price - $this->escrowService->calculatePlatformFee($card->price),
            'shippingCost' => self::SHIPPING_COST / 100,
            'transaction' => $transaction,
            'validators' => $validators,
        ]);
    }

    public function updateShipping(Request $request, Transaction $transaction)
    {
        abort_if($transaction->buyer_id !== Auth::id(), 403);

        $transaction->update([
            'shipping_name' => $request->shipping_name,
            'shipping_address' => $request->shipping_address,
            'shipping_city' => $request->shipping_city,
            'shipping_zip' => $request->shipping_zip,
            'shipping_phone' => $request->shipping_phone,
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Webhook Stripe: riceve notifica del pagamento avvenuto.
     */
    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');

        try {
            $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, config('services.stripe.webhook_secret'));
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            Log::warning('Stripe webhook signature verification failed');
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        match ($event->type) {
            'payment_intent.succeeded' => $this->onPaymentSucceeded($event->data->object),
            'payment_intent.payment_failed' => $this->onPaymentFailed($event->data->object),
            default => null,
        };

        return response()->json(['status' => 'ok']);
    }

    private function onPaymentSucceeded(\Stripe\PaymentIntent $paymentIntent): void
    {
        DB::transaction(function () use ($paymentIntent) {
            $transaction = Transaction::where('stripe_intent_id', $paymentIntent->id)->first();

            if ($transaction) {
                $transaction->update([
                    'status' => 'paid_escrow',
                    'escrow_paid_at' => now(),
                ]);

                // Blocca la carta SOLO dopo pagamento confermato
                $transaction->card->update(['status' => 'in_negotiation']);
            }
        });
    }

    private function onPaymentFailed(\Stripe\PaymentIntent $paymentIntent): void
    {
        $transaction = Transaction::where('stripe_intent_id', $paymentIntent->id)->first();
        if ($transaction) {
            $transaction->update(['status' => 'pending']);
        }
    }

    /**
     * Rilascia i fondi al venditore dopo la validazione.
     */
    public function releaseFunds(Transaction $transaction): void
    {
        if ($transaction->status !== 'validated') {
            throw new \LogicException('Impossibile rilasciare fondi: la transazione non è validata.');
        }

        $seller = $transaction->seller;

        if (!$seller->stripe_connect_id) {
            // In modalità test saltiamo il trasferimento Stripe
            // In produzione il venditore deve configurare il suo account
            Log::info('Test mode: skipping Stripe transfer for seller without connect account', [
                'transaction_id' => $transaction->id,
                'seller_id' => $seller->id,
            ]);
            $transaction->update([
                'status' => 'validated',
                'funds_released_at' => now(),
            ]);
            return;
        }

        try {
            $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
            $totalAmount = (int) ($transaction->amount * 100);
            $shippingCost = 590;
            $sellerAmount = $totalAmount + $shippingCost; // Prezzo carta + spedizione

            $transfer = $stripe->transfers->create([
                'amount' => $sellerAmount,
                'currency' => 'eur',
                'destination' => $seller->stripe_connect_id,
                'metadata' => ['transaction_id' => $transaction->id],
            ]);

            $transaction->update([
                'status' => 'validated',
                'stripe_transfer_id' => $transfer->id,
                'funds_released_at' => now(),
            ]);
        } catch (ApiErrorException $e) {
            Log::error('Stripe Transfer failed', [
                'transaction_id' => $transaction->id,
                'stripe_error' => $e->getMessage(),
            ]);
            $transaction->update(['status' => 'disputed']);
            throw $e;
        }
    }

    public function chooseValidator(Request $request, Transaction $transaction)
    {
        abort_if($transaction->buyer_id !== Auth::id(), 403);

        $request->validate([
            'validator_id' => 'required|exists:users,id',
        ]);

        $transaction->update(['validator_id' => $request->validator_id]);

        return redirect()->route('transactions.index')->with('success', 'Validatore scelto con successo!');
    }
}
