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
            ->where('checkout_expires_at', '<', now())
            ->with('card')
            ->get();

        foreach ($expired as $transaction) {
            $transaction->card->update(['status' => 'available']);
            $transaction->delete();
            $this->info("Carta liberata: {$transaction->card->name}");
        }

        $this->info("✅ {$expired->count()} carte liberate.");
    }
}