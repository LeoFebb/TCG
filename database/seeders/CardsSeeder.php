<?php
namespace Database\Seeders;

use App\Models\Card;
use App\Models\User;
use Illuminate\Database\Seeder;

class CardsSeeder extends Seeder
{
    public function run(): void
    {
        $sellers = User::where('role', 'user')->get();
        echo 'Sellers: ' . $sellers->count() . PHP_EOL;

        $cards = [
            ['name'=>'Pikachu','set'=>'Base Set','num'=>'058/102','cat'=>'pokemon','rarity'=>'Common','price'=>50,'trade'=>false,'img'=>'cards/Pikachu.jpg'],
            ['name'=>'Rayquaza','set'=>'EX Deoxys','num'=>'100/107','cat'=>'pokemon','rarity'=>'Rare Holo','price'=>120,'trade'=>false,'img'=>'cards/Rayquaza.jpg'],
            ['name'=>'Ajani','set'=>'Magic 2013','num'=>'1/249','cat'=>'mtg','rarity'=>'Mythic Rare','price'=>45,'trade'=>true,'img'=>'cards/Ajani.jpg'],
            ['name'=>'Drago Nero Occhi Rossi','set'=>'Legend of Blue Eyes','num'=>'RDS-EN004','cat'=>'yugioh','rarity'=>'Ultra Rare','price'=>80,'trade'=>false,'img'=>'cards/Drago Nero Occhi Rossi.jpg'],
            ['name'=>'Monkey D. Luffy','set'=>'OP01','num'=>'001/Secret','cat'=>'onepiece','rarity'=>'Secret Rare','price'=>95,'trade'=>true,'img'=>'cards/Monkey D.Luffy.jpg'],
            ['name'=>'Son Goku','set'=>'BT1','num'=>'001/200','cat'=>'dragon_ball_super','rarity'=>'Super Rare','price'=>60,'trade'=>false,'img'=>'cards/Son Goku.jpg'],
            ['name'=>'Naruto Uzumaki','set'=>'Serie 1','num'=>'001/100','cat'=>'naruto','rarity'=>'Rare','price'=>35,'trade'=>true,'img'=>'cards/Naruto Uzumaki.jpg'],
            ['name'=>'Shanks','set'=>'OP02','num'=>'001/Secret','cat'=>'onepiece','rarity'=>'Secret Rare','price'=>150,'trade'=>false,'img'=>'cards/Shanks.jpg'],
            ['name'=>'Gogeta','set'=>'BT7','num'=>'001/200','cat'=>'dragon_ball_super','rarity'=>'Legendary Rare','price'=>75,'trade'=>true,'img'=>'cards/Gogeta.jpg'],
            ['name'=>'Super Saiyan God Son Goku','set'=>'BT4','num'=>'001/200','cat'=>'dragon_ball_super','rarity'=>'Special Rare','price'=>55,'trade'=>false,'img'=>'cards/Super Saiyan God Son Goku.jpg'],
            ['name'=>'Portgas D. Ace','set'=>'OP02','num'=>'002/Secret','cat'=>'onepiece','rarity'=>'Secret Rare','price'=>110,'trade'=>true,'img'=>'cards/Portgas.D.Ace.jpg'],
            ['name'=>'Elecsprint','set'=>'Sword & Shield','num'=>'050/202','cat'=>'pokemon','rarity'=>'Uncommon','price'=>15,'trade'=>false,'img'=>'cards/Elecsprint.jpg'],
        ];

        foreach ($cards as $i => $c) {
            try {
                $seller = $sellers->get($i % $sellers->count());
                Card::create([
                    'user_id' => $seller->id,
                    'name' => $c['name'],
                    'set_name' => $c['set'],
                    'card_number' => $c['num'],
                    'tcg_category' => $c['cat'],
                    'rarity' => $c['rarity'],
                    'condition' => 'NM',
                    'price' => $c['price'],
                    'available_for_trade' => $c['trade'],
                    'type' => $c['trade'] ? 'both' : 'sale',
                    'status' => 'available',
                    'is_validated' => true,
                    'images' => json_encode([$c['img']]),
                ]);
                echo 'Creata: ' . $c['name'] . PHP_EOL;
            } catch (\Exception $e) {
                echo 'Errore su ' . $c['name'] . ': ' . $e->getMessage() . PHP_EOL;
            }
        }

        echo 'Done!';
    }
}