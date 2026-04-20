<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class ValidatorsSeeder extends Seeder
{
    public function run(): void
    {
        $validators = [
            ['name'=>'Roberto Marino','email'=>'roberto@tcgvault.it','cats'=>['pokemon','yugioh'],'city'=>'Roma'],
            ['name'=>'Chiara Fontana','email'=>'chiara@tcgvault.it','cats'=>['mtg','dragon_ball_super'],'city'=>'Napoli'],
            ['name'=>'Davide Ricci','email'=>'davide@tcgvault.it','cats'=>['onepiece','naruto'],'city'=>'Torino'],
            ['name'=>'Elena Greco','email'=>'elena@tcgvault.it','cats'=>['pokemon','mtg'],'city'=>'Bologna'],
            ['name'=>'Francesco Lombardi','email'=>'francesco@tcgvault.it','cats'=>['yugioh','onepiece'],'city'=>'Firenze'],
            ['name'=>'Valentina Serra','email'=>'valentina@tcgvault.it','cats'=>['dragon_ball_super','naruto'],'city'=>'Palermo'],
            ['name'=>'Andrea Costa','email'=>'andrea@tcgvault.it','cats'=>['pokemon','onepiece'],'city'=>'Venezia'],
            ['name'=>'Monica Bruno','email'=>'monica@tcgvault.it','cats'=>['mtg','yugioh'],'city'=>'Genova'],
        ];

        foreach ($validators as $v) {
            User::create([
                'name' => $v['name'],
                'email' => $v['email'],
                'password' => bcrypt('password123'),
                'role' => 'validator',
                'is_verified_validator' => true,
                'tcg_categories' => $v['cats'],
                'country' => 'Italia',
                'city' => $v['city'],
                'address' => 'Via Roma 1',
                'zip' => '00100',
                'phone' => '3331234567',
                'vat_number' => 'IT12345678901',
            ]);
        }

        echo 'Done! ' . count($validators) . ' validatori creati.';
    }
}