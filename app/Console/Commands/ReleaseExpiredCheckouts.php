<?php

namespace App\Console\Commands;

use App\Models\Transaction;
use Illuminate\Console\Command;

class ReleaseExpiredCheckouts extends Command
{
    protected $signature   = 'checkouts:release-expired';
    protected $description = 'Libera le carte con checkout scaduto';

    public function handle(): void
    {
        $expired = Transaction::where('status', 'pending_payment')
    ->where(function($q) {
        $q->where('checkout_expires_at', '<', now())
          ->orWhere(function($q2) {
              $q2->whereNull('checkout_expires_at')
                 ->where('created_at', '<', now()->subMinutes(1));
          });
    })
    ->with('card')
    ->get();

        foreach ($expired as $transaction) {
    $transaction->update(['status' => 'rejected']);
    if ($transaction->card) {
        $transaction->card->update(['status' => 'available']);
        $this->info("Carta liberata: {$transaction->card->name}");
            }
        }
    }
}