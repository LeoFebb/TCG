<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role', 'is_verified_validator', 'stripe_connect_id', 'identity_document_path', 'tcg_categories', 'validation_notes', 'address', 'city', 'zip', 'country', 'phone', 'address', 'city', 'zip', 'country', 'phone', 'profile_photo', 'address', 'city', 'zip', 'country', 'phone', 'vat_number', 'profile_photo'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_verified_validator' => 'boolean',
        'tcg_categories' => 'array',
        'password' => 'hashed',
    ];

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
    public function isValidator(): bool
    {
        return $this->role === 'validator';
    }
    public function isVerifiedValidator(): bool
    {
        return $this->role === 'validator' && $this->is_verified_validator;
    }

    public function cards(): HasMany
    {
        return $this->hasMany(Card::class);
    }
    public function purchasedTransactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'buyer_id');
    }
    public function sellingTransactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'seller_id');
    }
    public function validationTasks(): HasMany
    {
        return $this->hasMany(Transaction::class, 'validator_id');
    }
}
