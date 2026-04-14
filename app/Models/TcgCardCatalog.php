<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TcgCardCatalog extends Model
{
    protected $table = 'tcg_cards_catalog';

    protected $fillable = ['name', 'set_name', 'card_number', 'tcg_category', 'rarity', 'image_url', 'type'];
}
