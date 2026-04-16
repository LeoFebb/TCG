<?php

namespace Database\Seeders;

use App\Models\Card;
use App\Models\User;
use Illuminate\Database\Seeder;

class DuplicateCardsSeeder extends Seeder
{
    public function run(): void
    {
        $card = Card::first();
        $users = User::where('role', 'user')->where('id', '!=', $card->user_id)->take(3)->get();
        
        foreach ($users as $u) {
            Card::create([
                'user_id' => $u->id,
                'name' => $card->name,
                'set_name' => $card->set_name,
                'card_number' => $card->card_number,
                'tcg_category' => $card->tcg_category,
                'condition' => $card->condition,
                'rarity' => $card->rarity,
                'price' => rand(10, 50),
                'status' => 'available',
                'images' => $card->images,
                'available_for_trade' => false,
            ]);
        }
        
        echo 'Done!';
    }
}
