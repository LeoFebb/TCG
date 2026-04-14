<?php

namespace App\Services;

use Stripe\PaymentIntent;
use Stripe\StripeClient;

class EscrowService
{
    private StripeClient $stripe;
    private const PLATFORM_FEE_PERCENTAGE = 8.0;

    public function __construct()
    {
        $this->stripe = new StripeClient(config('services.stripe.secret'));
    }

    public function createPaymentIntent(int $amount, int $sellerId, int $cardId): PaymentIntent
    {
        return $this->stripe->paymentIntents->create([
            'amount' => $amount,
            'currency' => 'eur',
            'capture_method' => 'automatic',
            'payment_method_types' => ['card', 'paypal'],
            'metadata' => [
                'card_id' => $cardId,
                'seller_id' => $sellerId,
            ],
        ]);
    }

    public function calculatePlatformFee(float $amount): float
    {
        return round($amount * (self::PLATFORM_FEE_PERCENTAGE / 100), 2);
    }

    public function calculateSellerPayout(float $amount): float
    {
        return $amount - $this->calculatePlatformFee($amount) + 5.9;
    }
}
