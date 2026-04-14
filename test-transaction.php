<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$card = \App\Models\Card::first();

if($card) {
    $transaction = \App\Models\Transaction::create([
        'buyer_id'         => 2,
        'seller_id'        => 1,
        'card_id'          => $card->id,
        'type'             => 'sale',
        'status'           => 'paid_escrow',
        'amount'           => $card->price ?? 29.99,
        'platform_fee'     => 2.40,
        'shipping_cost'    => 5.90,
        'shipping_name'    => 'Mario Rossi',
        'shipping_address' => 'Via Roma 1',
        'shipping_city'    => 'Milano',
        'shipping_zip'     => '20100',
        'shipping_country' => 'Italia',
        'shipping_phone'   => '+39 333 1234567',
    ]);
    echo "Transazione creata: ID " . $transaction->id . "\n";
} else {
    echo "Nessuna carta trovata — crea prima una carta!\n";
}