<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Card extends Model
{
    protected $fillable = ['user_id', 'name', 'set_name', 'card_number', 'tcg_category', 'rarity', 'condition', 'price', 'available_for_trade', 'type', 'status', 'is_validated', 'images', 'description'];

    protected $casts = [
        
        'price' => 'decimal:2',
        'available_for_trade' => 'boolean',
        'is_validated' => 'boolean',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function isAvailable(): bool
    {
        return $this->status === 'available';
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available')->where('is_validated', true);
    }

    public function scopeForSale($query)
    {
        return $query->available()->whereNotNull('price');
    }

    public function getImagesAttribute($value)
    {
        if (is_array($value)) {
            return $value;
        }
        return json_decode($value, true) ?? [];
    }

    public function scopeForTrade($query)
    {
        return $query->available()->where('available_for_trade', true);
    }

    public $sellers_count = 1;
    public $min_price = null;
}
