<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    protected $fillable = [
        'buyer_id', 'seller_id', 'card_id', 'offered_card_id',
        'validator_id', 'type', 'status', 'amount', 'platform_fee',
        'shipping_cost', 'shipping_name', 'shipping_address',
        'shipping_city', 'shipping_zip', 'shipping_country',
        'shipping_phone', 'label_generated_at',
        'stripe_intent_id', 'stripe_transfer_id', 'escrow_paid_at',
        'funds_released_at', 'buyer_shipped', 'seller_shipped',
        'validator_notes', 'validated_at', 'tracking_number',
        'return_tracking_number',
    ];

    protected $casts = [
        'amount'            => 'decimal:2',
        'platform_fee'      => 'decimal:2',
        'shipping_cost'     => 'decimal:2',
        'buyer_shipped'     => 'boolean',
        'seller_shipped'    => 'boolean',
        'escrow_paid_at'    => 'datetime',
        'funds_released_at' => 'datetime',
        'validated_at'      => 'datetime',
        'label_generated_at'=> 'datetime',
    ];

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function card(): BelongsTo
    {
        return $this->belongsTo(Card::class, 'card_id');
    }

    public function offeredCard(): BelongsTo
    {
        return $this->belongsTo(Card::class, 'offered_card_id');
    }

    public function validator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'validator_id');
    }

    public function canBeValidated(): bool
    {
        return $this->status === 'in_validation';
    }

    public function isValidated(): bool
    {
        return in_array($this->status, ['validated', 'shipping', 'completed']);
    }
}