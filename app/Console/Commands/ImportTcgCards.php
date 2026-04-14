<?php

namespace App\Console\Commands;

use App\Models\TcgCardCatalog;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class ImportTcgCards extends Command
{
    protected $signature = 'tcg:import {category}';
    protected $description = 'Importa carte dal catalogo TCG';

    public function handle(): void
    {
        $category = $this->argument('category');
        match ($category) {
            'pokemon' => $this->importPokemon(),
            'yugioh' => $this->importYugioh(),
            'dragonball' => $this->importDragonBall(),
            'onepiece' => $this->importOnePiece(),
            'naruto' => $this->importNaruto(),
            'mtg' => $this->importMtg(),
            default => $this->error("Categoria non supportata: $category"),
        };
    }

    private function importPokemon(): void
    {
        $this->info('Importazione carte Pokemon...');
        $page = 1;
        $imported = 0;

        do {
            $response = Http::get('https://api.pokemontcg.io/v2/cards', [
                'page' => $page,
                'pageSize' => 250,
                'select' => 'id,name,set,number,rarity,images',
            ]);

            if (!$response->successful()) {
                break;
            }

            $data = $response->json();
            $cards = $data['data'] ?? [];

            foreach ($cards as $card) {
                TcgCardCatalog::updateOrCreate(
                    [
                        'card_number' => $card['id'],
                        'tcg_category' => 'pokemon',
                    ],
                    [
                        'name' => $card['name'],
                        'set_name' => $card['set']['name'] ?? 'Unknown',
                        'rarity' => $card['rarity'] ?? null,
                        'image_url' => $card['images']['small'] ?? null,
                    ],
                );
                $imported++;
            }

            $this->info("Pagina $page importata — $imported carte totali");
            $page++;
        } while (count($cards) === 250);

        $this->info("✅ Pokemon: $imported carte importate!");
    }

    private function importYugioh(): void
    {
        $this->info('Importazione carte Yu-Gi-Oh!...');

        $response = Http::get('https://db.ygoprodeck.com/api/v7/cardinfo.php');

        if (!$response->successful()) {
            $this->error('Errore API Yu-Gi-Oh!');
            return;
        }

        $cards = $response->json()['data'] ?? [];
        $imported = 0;

        foreach ($cards as $card) {
            TcgCardCatalog::updateOrCreate(
                [
                    'card_number' => (string) $card['id'],
                    'tcg_category' => 'yugioh',
                ],
                [
                    'name' => $card['name'],
                    'set_name' => $card['card_sets'][0]['set_name'] ?? 'Unknown',
                    'rarity' => $card['card_sets'][0]['set_rarity'] ?? null,
                    'image_url' => $card['card_images'][0]['image_url_small'] ?? null,
                ],
            );
            $imported++;
        }

        $this->info("✅ Yu-Gi-Oh!: $imported carte importate!");
    }

    private function importDragonBall(): void
    {
        $this->info('Importazione carte Dragon Ball Super...');

        $cards = [
            // Dawn of the Z-Legends (BT1)
            ['name' => 'Son Goku', 'set' => 'Dawn of the Z-Legends', 'number' => 'BT1-001', 'rarity' => 'Super Rare'],
            ['name' => 'Vegeta', 'set' => 'Dawn of the Z-Legends', 'number' => 'BT1-077', 'rarity' => 'Super Rare'],
            ['name' => 'Piccolo', 'set' => 'Dawn of the Z-Legends', 'number' => 'BT1-042', 'rarity' => 'Uncommon'],
            ['name' => 'Krillin', 'set' => 'Dawn of the Z-Legends', 'number' => 'BT1-016', 'rarity' => 'Common'],
            ['name' => 'Frieza', 'set' => 'Dawn of the Z-Legends', 'number' => 'BT1-062', 'rarity' => 'Rare'],
            ['name' => 'Nappa', 'set' => 'Dawn of the Z-Legends', 'number' => 'BT1-080', 'rarity' => 'Rare'],
            ['name' => 'Raditz', 'set' => 'Dawn of the Z-Legends', 'number' => 'BT1-085', 'rarity' => 'Uncommon'],
            ['name' => 'Super Saiyan God Son Goku', 'set' => 'Dawn of the Z-Legends', 'number' => 'BT1-031', 'rarity' => 'Ultra Rare'],
            ['name' => 'Super Saiyan Vegeta', 'set' => 'Dawn of the Z-Legends', 'number' => 'BT1-093', 'rarity' => 'Super Rare'],
            ['name' => 'Android 18', 'set' => 'Dawn of the Z-Legends', 'number' => 'BT1-021', 'rarity' => 'Rare'],
            ['name' => 'Android 17', 'set' => 'Dawn of the Z-Legends', 'number' => 'BT1-020', 'rarity' => 'Rare'],
            // Union Force (BT2)
            ['name' => 'Trunks', 'set' => 'Union Force', 'number' => 'BT2-022', 'rarity' => 'Super Rare'],
            ['name' => 'Golden Frieza', 'set' => 'Union Force', 'number' => 'BT2-061', 'rarity' => 'Super Rare'],
            ['name' => 'Gotenks', 'set' => 'Union Force', 'number' => 'BT2-008', 'rarity' => 'Super Rare'],
            ['name' => 'Cabba', 'set' => 'Union Force', 'number' => 'BT2-040', 'rarity' => 'Rare'],
            ['name' => 'Champa', 'set' => 'Union Force', 'number' => 'BT2-044', 'rarity' => 'Rare'],
            ['name' => 'Vados', 'set' => 'Union Force', 'number' => 'BT2-045', 'rarity' => 'Rare'],
            // Cross Worlds (BT3)
            ['name' => 'Gohan', 'set' => 'Cross Worlds', 'number' => 'BT3-072', 'rarity' => 'Super Rare'],
            ['name' => 'Zamasu', 'set' => 'Cross Worlds', 'number' => 'BT3-113', 'rarity' => 'Super Rare'],
            ['name' => 'Goku Black', 'set' => 'Cross Worlds', 'number' => 'BT3-112', 'rarity' => 'Super Rare'],
            ['name' => 'Vegito', 'set' => 'Cross Worlds', 'number' => 'BT3-127', 'rarity' => 'Secret Rare'],
            ['name' => 'Future Trunks', 'set' => 'Cross Worlds', 'number' => 'BT3-033', 'rarity' => 'Super Rare'],
            // Colossal Warfare (BT4)
            ['name' => 'Cell', 'set' => 'Colossal Warfare', 'number' => 'BT4-068', 'rarity' => 'Super Rare'],
            ['name' => 'Android 16', 'set' => 'Colossal Warfare', 'number' => 'BT4-019', 'rarity' => 'Rare'],
            ['name' => 'Perfect Cell', 'set' => 'Colossal Warfare', 'number' => 'BT4-069', 'rarity' => 'Ultra Rare'],
            // Miraculous Revival (BT5)
            ['name' => 'Gogeta', 'set' => 'Miraculous Revival', 'number' => 'BT5-112', 'rarity' => 'Secret Rare'],
            ['name' => 'Majin Buu', 'set' => 'Miraculous Revival', 'number' => 'BT5-107', 'rarity' => 'Super Rare'],
            ['name' => 'Kid Buu', 'set' => 'Miraculous Revival', 'number' => 'BT5-110', 'rarity' => 'Super Rare'],
            ['name' => 'Super Buu', 'set' => 'Miraculous Revival', 'number' => 'BT5-108', 'rarity' => 'Rare'],
            // Realm of the Gods (BT6)
            ['name' => 'Beerus', 'set' => 'Realm of the Gods', 'number' => 'BT6-113', 'rarity' => 'Secret Rare'],
            ['name' => 'Whis', 'set' => 'Realm of the Gods', 'number' => 'BT6-109', 'rarity' => 'Rare'],
            ['name' => 'Broly', 'set' => 'Realm of the Gods', 'number' => 'BT6-017', 'rarity' => 'Super Rare'],
            ['name' => 'Hit', 'set' => 'Realm of the Gods', 'number' => 'BT6-088', 'rarity' => 'Rare'],
            // Assault of the Saiyans (BT7)
            ['name' => 'Jiren', 'set' => 'Assault of the Saiyans', 'number' => 'BT7-109', 'rarity' => 'Special Rare'],
            ['name' => 'Kefla', 'set' => 'Assault of the Saiyans', 'number' => 'BT7-031', 'rarity' => 'Super Rare'],
            ['name' => 'Caulifla', 'set' => 'Assault of the Saiyans', 'number' => 'BT7-028', 'rarity' => 'Super Rare'],
            ['name' => 'Kale', 'set' => 'Assault of the Saiyans', 'number' => 'BT7-029', 'rarity' => 'Rare'],
            // Malicious Machinations (BT8)
            ['name' => 'Ultra Instinct Son Goku', 'set' => 'Malicious Machinations', 'number' => 'BT8-031', 'rarity' => 'Special Rare'],
            ['name' => 'Toppo', 'set' => 'Malicious Machinations', 'number' => 'BT8-107', 'rarity' => 'Rare'],
            ['name' => 'Dyspo', 'set' => 'Malicious Machinations', 'number' => 'BT8-106', 'rarity' => 'Rare'],
            // Universal Onslaught (BT9)
            ['name' => 'Super Saiyan 4 Son Goku', 'set' => 'Universal Onslaught', 'number' => 'BT9-001', 'rarity' => 'Special Rare'],
            ['name' => 'Super Saiyan 4 Vegeta', 'set' => 'Universal Onslaught', 'number' => 'BT9-073', 'rarity' => 'Super Rare'],
            ['name' => 'Gogeta SS4', 'set' => 'Universal Onslaught', 'number' => 'BT9-112', 'rarity' => 'Secret Rare'],
            // Rise of the Unison Warrior (BT10)
            ['name' => 'Vegito Blue', 'set' => 'Rise of the Unison Warrior', 'number' => 'BT10-127', 'rarity' => 'Special Rare'],
            ['name' => 'Fused Zamasu', 'set' => 'Rise of the Unison Warrior', 'number' => 'BT10-110', 'rarity' => 'Super Rare'],
        ];

        foreach ($cards as $card) {
            \App\Models\TcgCardCatalog::updateOrCreate(['card_number' => $card['number'], 'tcg_category' => 'dragon_ball_super'], ['name' => $card['name'], 'set_name' => $card['set'], 'rarity' => $card['rarity']]);
        }

        $this->info('✅ Dragon Ball Super: ' . count($cards) . ' carte importate!');
    }

    private function importOnePiece(): void
    {
        $this->info('Importazione carte One Piece...');

        $cards = [
            // Romance Dawn (OP01)
            ['name' => 'Monkey D. Luffy', 'set' => 'Romance Dawn', 'number' => 'OP01-001', 'rarity' => 'Secret Rare'],
            ['name' => 'Roronoa Zoro', 'set' => 'Romance Dawn', 'number' => 'OP01-001', 'rarity' => 'Super Rare'],
            ['name' => 'Nami', 'set' => 'Romance Dawn', 'number' => 'OP01-016', 'rarity' => 'Rare'],
            ['name' => 'Usopp', 'set' => 'Romance Dawn', 'number' => 'OP01-017', 'rarity' => 'Common'],
            ['name' => 'Sanji', 'set' => 'Romance Dawn', 'number' => 'OP01-013', 'rarity' => 'Rare'],
            ['name' => 'Tony Tony Chopper', 'set' => 'Romance Dawn', 'number' => 'OP01-020', 'rarity' => 'Common'],
            ['name' => 'Nico Robin', 'set' => 'Romance Dawn', 'number' => 'OP01-011', 'rarity' => 'Rare'],
            ['name' => 'Franky', 'set' => 'Romance Dawn', 'number' => 'OP01-018', 'rarity' => 'Uncommon'],
            ['name' => 'Brook', 'set' => 'Romance Dawn', 'number' => 'OP01-021', 'rarity' => 'Uncommon'],
            ['name' => 'Shanks', 'set' => 'Romance Dawn', 'number' => 'OP01-118', 'rarity' => 'Secret Rare'],
            ['name' => 'Buggy', 'set' => 'Romance Dawn', 'number' => 'OP01-060', 'rarity' => 'Rare'],
            ['name' => 'Coby', 'set' => 'Romance Dawn', 'number' => 'OP01-007', 'rarity' => 'Common'],
            ['name' => 'Alvida', 'set' => 'Romance Dawn', 'number' => 'OP01-061', 'rarity' => 'Common'],
            ['name' => 'Helmeppo', 'set' => 'Romance Dawn', 'number' => 'OP01-008', 'rarity' => 'Common'],
            ['name' => 'Arlong', 'set' => 'Romance Dawn', 'number' => 'OP01-047', 'rarity' => 'Rare'],
            ['name' => 'Smoker', 'set' => 'Romance Dawn', 'number' => 'OP01-102', 'rarity' => 'Rare'],
            ['name' => 'Tashigi', 'set' => 'Romance Dawn', 'number' => 'OP01-103', 'rarity' => 'Uncommon'],
            ['name' => 'Crocodile', 'set' => 'Romance Dawn', 'number' => 'OP01-092', 'rarity' => 'Super Rare'],
            ['name' => 'Dracule Mihawk', 'set' => 'Romance Dawn', 'number' => 'OP01-111', 'rarity' => 'Super Rare'],
            // Paramount War (OP02)
            ['name' => 'Portgas D. Ace', 'set' => 'Paramount War', 'number' => 'OP02-013', 'rarity' => 'Super Rare'],
            ['name' => 'Whitebeard', 'set' => 'Paramount War', 'number' => 'OP02-004', 'rarity' => 'Super Rare'],
            ['name' => 'Jinbe', 'set' => 'Paramount War', 'number' => 'OP02-082', 'rarity' => 'Rare'],
            ['name' => 'Akainu', 'set' => 'Paramount War', 'number' => 'OP02-093', 'rarity' => 'Rare'],
            ['name' => 'Aokiji', 'set' => 'Paramount War', 'number' => 'OP02-091', 'rarity' => 'Rare'],
            ['name' => 'Kizaru', 'set' => 'Paramount War', 'number' => 'OP02-092', 'rarity' => 'Rare'],
            ['name' => 'Marco', 'set' => 'Paramount War', 'number' => 'OP02-007', 'rarity' => 'Super Rare'],
            ['name' => 'Vista', 'set' => 'Paramount War', 'number' => 'OP02-010', 'rarity' => 'Uncommon'],
            ['name' => 'Sengoku', 'set' => 'Paramount War', 'number' => 'OP02-096', 'rarity' => 'Super Rare'],
            ['name' => 'Garp', 'set' => 'Paramount War', 'number' => 'OP02-086', 'rarity' => 'Super Rare'],
            // Pillars of Strength (OP03)
            ['name' => 'Trafalgar Law', 'set' => 'Pillars of Strength', 'number' => 'OP03-051', 'rarity' => 'Super Rare'],
            ['name' => 'Boa Hancock', 'set' => 'Pillars of Strength', 'number' => 'OP03-040', 'rarity' => 'Super Rare'],
            ['name' => 'Enel', 'set' => 'Pillars of Strength', 'number' => 'OP03-021', 'rarity' => 'Super Rare'],
            ['name' => 'Donquixote Doflamingo', 'set' => 'Pillars of Strength', 'number' => 'OP03-060', 'rarity' => 'Super Rare'],
            ['name' => 'Caesar Clown', 'set' => 'Pillars of Strength', 'number' => 'OP03-058', 'rarity' => 'Rare'],
            // Kingdoms of Intrigue (OP04)
            ['name' => 'Eustass Kid', 'set' => 'Kingdoms of Intrigue', 'number' => 'OP04-013', 'rarity' => 'Super Rare'],
            ['name' => 'Kaido', 'set' => 'Kingdoms of Intrigue', 'number' => 'OP04-057', 'rarity' => 'Secret Rare'],
            ['name' => 'Big Mom', 'set' => 'Kingdoms of Intrigue', 'number' => 'OP04-020', 'rarity' => 'Super Rare'],
            ['name' => 'Yamato', 'set' => 'Kingdoms of Intrigue', 'number' => 'OP04-047', 'rarity' => 'Super Rare'],
            ['name' => 'Ulti', 'set' => 'Kingdoms of Intrigue', 'number' => 'OP04-044', 'rarity' => 'Rare'],
            // Awakening of the New Era (OP05)
            ['name' => 'Blackbeard', 'set' => 'Awakening of the New Era', 'number' => 'OP05-041', 'rarity' => 'Super Rare'],
            ['name' => 'Jewelry Bonney', 'set' => 'Awakening of the New Era', 'number' => 'OP05-029', 'rarity' => 'Super Rare'],
            ['name' => 'Basil Hawkins', 'set' => 'Awakening of the New Era', 'number' => 'OP05-005', 'rarity' => 'Rare'],
            ['name' => 'X Drake', 'set' => 'Awakening of the New Era', 'number' => 'OP05-014', 'rarity' => 'Rare'],
            // Wings of the Captain (OP06)
            ['name' => 'Sanji Vinsmoke', 'set' => 'Wings of the Captain', 'number' => 'OP06-029', 'rarity' => 'Super Rare'],
            ['name' => 'Zoro Roronoa', 'set' => 'Wings of the Captain', 'number' => 'OP06-020', 'rarity' => 'Super Rare'],
            ['name' => 'Lucci', 'set' => 'Wings of the Captain', 'number' => 'OP06-043', 'rarity' => 'Super Rare'],
            ['name' => 'Kaku', 'set' => 'Wings of the Captain', 'number' => 'OP06-044', 'rarity' => 'Rare'],
            ['name' => 'Stussy', 'set' => 'Wings of the Captain', 'number' => 'OP06-049', 'rarity' => 'Rare'],
            ['name' => 'Luffy Gear 5', 'set' => 'Wings of the Captain', 'number' => 'OP06-013', 'rarity' => 'Secret Rare'],
        ];

        foreach ($cards as $card) {
            \App\Models\TcgCardCatalog::updateOrCreate(['card_number' => $card['number'], 'tcg_category' => 'onepiece'], ['name' => $card['name'], 'set_name' => $card['set'], 'rarity' => $card['rarity']]);
        }

        $this->info('✅ One Piece: ' . count($cards) . ' carte importate!');
    }

    private function importNaruto(): void
    {
        $this->info('Importazione carte Naruto...');

        $cards = [
            // Path to Hokage
            ['name' => 'Naruto Uzumaki', 'set' => 'Path to Hokage', 'number' => 'N-001', 'rarity' => 'Secret Rare'],
            ['name' => 'Sasuke Uchiha', 'set' => 'Path to Hokage', 'number' => 'N-002', 'rarity' => 'Super Rare'],
            ['name' => 'Sakura Haruno', 'set' => 'Path to Hokage', 'number' => 'N-003', 'rarity' => 'Rare'],
            ['name' => 'Kakashi Hatake', 'set' => 'Path to Hokage', 'number' => 'N-004', 'rarity' => 'Super Rare'],
            ['name' => 'Rock Lee', 'set' => 'Path to Hokage', 'number' => 'N-005', 'rarity' => 'Rare'],
            ['name' => 'Neji Hyuga', 'set' => 'Path to Hokage', 'number' => 'N-006', 'rarity' => 'Rare'],
            ['name' => 'Tenten', 'set' => 'Path to Hokage', 'number' => 'N-007', 'rarity' => 'Common'],
            ['name' => 'Hinata Hyuga', 'set' => 'Path to Hokage', 'number' => 'N-008', 'rarity' => 'Rare'],
            ['name' => 'Shikamaru Nara', 'set' => 'Path to Hokage', 'number' => 'N-009', 'rarity' => 'Rare'],
            ['name' => 'Ino Yamanaka', 'set' => 'Path to Hokage', 'number' => 'N-010', 'rarity' => 'Common'],
            ['name' => 'Choji Akimichi', 'set' => 'Path to Hokage', 'number' => 'N-011', 'rarity' => 'Common'],
            ['name' => 'Kiba Inuzuka', 'set' => 'Path to Hokage', 'number' => 'N-012', 'rarity' => 'Common'],
            ['name' => 'Shino Aburame', 'set' => 'Path to Hokage', 'number' => 'N-013', 'rarity' => 'Common'],
            ['name' => 'Kurenai Yuhi', 'set' => 'Path to Hokage', 'number' => 'N-014', 'rarity' => 'Rare'],
            ['name' => 'Asuma Sarutobi', 'set' => 'Path to Hokage', 'number' => 'N-015', 'rarity' => 'Rare'],
            // Quest for Power
            ['name' => 'Gaara', 'set' => 'Quest for Power', 'number' => 'N-016', 'rarity' => 'Super Rare'],
            ['name' => 'Temari', 'set' => 'Quest for Power', 'number' => 'N-017', 'rarity' => 'Rare'],
            ['name' => 'Kankuro', 'set' => 'Quest for Power', 'number' => 'N-018', 'rarity' => 'Uncommon'],
            ['name' => 'Itachi Uchiha', 'set' => 'Quest for Power', 'number' => 'N-019', 'rarity' => 'Secret Rare'],
            ['name' => 'Kisame Hoshigaki', 'set' => 'Quest for Power', 'number' => 'N-020', 'rarity' => 'Super Rare'],
            ['name' => 'Jiraiya', 'set' => 'Quest for Power', 'number' => 'N-021', 'rarity' => 'Super Rare'],
            ['name' => 'Tsunade', 'set' => 'Quest for Power', 'number' => 'N-022', 'rarity' => 'Super Rare'],
            ['name' => 'Orochimaru', 'set' => 'Quest for Power', 'number' => 'N-023', 'rarity' => 'Super Rare'],
            ['name' => 'Kabuto Yakushi', 'set' => 'Quest for Power', 'number' => 'N-024', 'rarity' => 'Rare'],
            ['name' => 'Sarutobi Hiruzen', 'set' => 'Quest for Power', 'number' => 'N-025', 'rarity' => 'Rare'],
            // Will of Fire
            ['name' => 'Minato Namikaze', 'set' => 'Will of Fire', 'number' => 'N-026', 'rarity' => 'Secret Rare'],
            ['name' => 'Pain', 'set' => 'Will of Fire', 'number' => 'N-027', 'rarity' => 'Secret Rare'],
            ['name' => 'Konan', 'set' => 'Will of Fire', 'number' => 'N-028', 'rarity' => 'Super Rare'],
            ['name' => 'Nagato', 'set' => 'Will of Fire', 'number' => 'N-029', 'rarity' => 'Super Rare'],
            ['name' => 'Killer Bee', 'set' => 'Will of Fire', 'number' => 'N-030', 'rarity' => 'Super Rare'],
            ['name' => 'Ay (Fourth Raikage)', 'set' => 'Will of Fire', 'number' => 'N-031', 'rarity' => 'Rare'],
            ['name' => 'Mei Terumi', 'set' => 'Will of Fire', 'number' => 'N-032', 'rarity' => 'Rare'],
            ['name' => 'Danzo Shimura', 'set' => 'Will of Fire', 'number' => 'N-033', 'rarity' => 'Rare'],
            // Eternal Rivals
            ['name' => 'Obito Uchiha', 'set' => 'Eternal Rivals', 'number' => 'N-034', 'rarity' => 'Secret Rare'],
            ['name' => 'Madara Uchiha', 'set' => 'Eternal Rivals', 'number' => 'N-035', 'rarity' => 'Secret Rare'],
            ['name' => 'Hashirama Senju', 'set' => 'Eternal Rivals', 'number' => 'N-036', 'rarity' => 'Super Rare'],
            ['name' => 'Tobirama Senju', 'set' => 'Eternal Rivals', 'number' => 'N-037', 'rarity' => 'Super Rare'],
            ['name' => 'Might Guy', 'set' => 'Eternal Rivals', 'number' => 'N-038', 'rarity' => 'Rare'],
            ['name' => 'Hiruzen Sarutobi', 'set' => 'Eternal Rivals', 'number' => 'N-039', 'rarity' => 'Rare'],
            ['name' => 'Naruto Uzumaki (Sage Mode)', 'set' => 'Eternal Rivals', 'number' => 'N-040', 'rarity' => 'Secret Rare'],
            ['name' => 'Sasuke Uchiha (Rinnegan)', 'set' => 'Eternal Rivals', 'number' => 'N-041', 'rarity' => 'Secret Rare'],
            ['name' => 'Naruto Uzumaki (Six Paths)', 'set' => 'Eternal Rivals', 'number' => 'N-042', 'rarity' => 'Secret Rare'],
            ['name' => 'Kaguya Otsutsuki', 'set' => 'Eternal Rivals', 'number' => 'N-043', 'rarity' => 'Secret Rare'],
            ['name' => 'Hagoromo Otsutsuki', 'set' => 'Eternal Rivals', 'number' => 'N-044', 'rarity' => 'Super Rare'],
            ['name' => 'Black Zetsu', 'set' => 'Eternal Rivals', 'number' => 'N-045', 'rarity' => 'Rare'],
            ['name' => 'White Zetsu', 'set' => 'Eternal Rivals', 'number' => 'N-046', 'rarity' => 'Common'],
            ['name' => 'Sai', 'set' => 'Eternal Rivals', 'number' => 'N-047', 'rarity' => 'Uncommon'],
            ['name' => 'Yamato', 'set' => 'Eternal Rivals', 'number' => 'N-048', 'rarity' => 'Uncommon'],
            ['name' => 'Boruto Uzumaki', 'set' => 'Eternal Rivals', 'number' => 'N-049', 'rarity' => 'Super Rare'],
            ['name' => 'Sarada Uchiha', 'set' => 'Eternal Rivals', 'number' => 'N-050', 'rarity' => 'Rare'],
        ];

        foreach ($cards as $card) {
            \App\Models\TcgCardCatalog::updateOrCreate(['card_number' => $card['number'], 'tcg_category' => 'naruto'], ['name' => $card['name'], 'set_name' => $card['set'], 'rarity' => $card['rarity']]);
        }

        $this->info('✅ Naruto: ' . count($cards) . ' carte importate!');
    }

    private function importMtg(): void
    {
        $this->info('Importazione carte Magic: The Gathering...');
        $imported = 0;
        $page = 1;

        do {
            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'User-Agent' => 'TCGVault/1.0 contact@tcgvault.it',
                'Accept' => 'application/json',
            ])->get('https://api.scryfall.com/cards/search', [
                'q' => 'game:paper',
                'page' => $page,
            ]);

            if (!$response->successful()) {
                $this->error('Errore API: ' . $response->body());
                break;
            }

            $data = $response->json();
            $cards = $data['data'] ?? [];

            foreach ($cards as $card) {
                \App\Models\TcgCardCatalog::updateOrCreate(
                    ['card_number' => $card['collector_number'] . '-' . $card['set'], 'tcg_category' => 'mtg'],
                    [
                        'name' => $card['name'],
                        'set_name' => $card['set_name'],
                        'rarity' => $card['rarity'],
                        'image_url' => $card['image_uris']['small'] ?? ($card['card_faces'][0]['image_uris']['small'] ?? null),
                    ],
                );
                $imported++;
            }

            $this->info("Pagina $page importata — $imported carte totali");
            $page++;
            sleep(1);
        } while ($data['has_more'] ?? false);

        $this->info("✅ Magic: $imported carte importate!");
    }
}
