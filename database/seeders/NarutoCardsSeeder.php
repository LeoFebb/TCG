<?php

namespace Database\Seeders;

use App\Models\TcgCardCatalog;
use Illuminate\Database\Seeder;

class NarutoCardsSeeder extends Seeder
{
    public function run(): void
    {
        $sets = [
            // Path to Hokage
            'Path to Hokage' => [
                ['N-001', 'Naruto Uzumaki', 'Ninja', 'Secret Rare'],
                ['N-002', 'Sasuke Uchiha', 'Ninja', 'Super Rare'],
                ['N-003', 'Sakura Haruno', 'Ninja', 'Rare'],
                ['N-004', 'Kakashi Hatake', 'Ninja', 'Super Rare'],
                ['N-005', 'Rock Lee', 'Ninja', 'Rare'],
                ['N-006', 'Neji Hyuga', 'Ninja', 'Rare'],
                ['N-007', 'Tenten', 'Ninja', 'Common'],
                ['N-008', 'Hinata Hyuga', 'Ninja', 'Rare'],
                ['N-009', 'Shikamaru Nara', 'Ninja', 'Rare'],
                ['N-010', 'Ino Yamanaka', 'Ninja', 'Common'],
                ['N-011', 'Choji Akimichi', 'Ninja', 'Common'],
                ['N-012', 'Kiba Inuzuka', 'Ninja', 'Common'],
                ['N-013', 'Shino Aburame', 'Ninja', 'Common'],
                ['N-014', 'Kurenai Yuhi', 'Ninja', 'Rare'],
                ['N-015', 'Asuma Sarutobi', 'Ninja', 'Rare'],
                ['N-016', 'Might Guy', 'Ninja', 'Rare'],
                ['N-017', 'Iruka Umino', 'Ninja', 'Common'],
                ['N-018', 'Konohamaru', 'Ninja', 'Common'],
                ['N-019', 'Hiruzen Sarutobi', 'Ninja', 'Super Rare'],
                ['N-020', 'Minato Namikaze', 'Ninja', 'Secret Rare'],
                ['N-021', 'Kushina Uzumaki', 'Ninja', 'Rare'],
                ['N-022', 'Naruto Uzumaki (Sage Mode)', 'Ninja', 'Super Rare'],
                ['N-023', 'Naruto Uzumaki (Nine-Tails Mode)', 'Ninja', 'Super Rare'],
                ['N-024', 'Sasuke Uchiha (Sharingan)', 'Ninja', 'Super Rare'],
                ['N-025', 'Kakashi Hatake (Sharingan)', 'Ninja', 'Super Rare'],
                ['N-026', 'Shadow Clone Jutsu', 'Jutsu', 'Common'],
                ['N-027', 'Rasengan', 'Jutsu', 'Rare'],
                ['N-028', 'Chidori', 'Jutsu', 'Rare'],
                ['N-029', 'Eight Gates', 'Jutsu', 'Super Rare'],
                ['N-030', 'Byakugan', 'Jutsu', 'Uncommon'],
            ],
            // Quest for Power
            'Quest for Power' => [
                ['N-031', 'Gaara', 'Ninja', 'Super Rare'],
                ['N-032', 'Temari', 'Ninja', 'Rare'],
                ['N-033', 'Kankuro', 'Ninja', 'Uncommon'],
                ['N-034', 'Itachi Uchiha', 'Ninja', 'Secret Rare'],
                ['N-035', 'Kisame Hoshigaki', 'Ninja', 'Super Rare'],
                ['N-036', 'Jiraiya', 'Ninja', 'Super Rare'],
                ['N-037', 'Tsunade', 'Ninja', 'Super Rare'],
                ['N-038', 'Orochimaru', 'Ninja', 'Super Rare'],
                ['N-039', 'Kabuto Yakushi', 'Ninja', 'Rare'],
                ['N-040', 'Sarutobi Hiruzen', 'Ninja', 'Rare'],
                ['N-041', 'Zabuza Momochi', 'Ninja', 'Rare'],
                ['N-042', 'Haku', 'Ninja', 'Rare'],
                ['N-043', 'Deidara', 'Ninja', 'Super Rare'],
                ['N-044', 'Sasori', 'Ninja', 'Super Rare'],
                ['N-045', 'Hidan', 'Ninja', 'Rare'],
                ['N-046', 'Kakuzu', 'Ninja', 'Rare'],
                ['N-047', 'Zetsu', 'Ninja', 'Uncommon'],
                ['N-048', 'Konan', 'Ninja', 'Super Rare'],
                ['N-049', 'Pain (Nagato)', 'Ninja', 'Secret Rare'],
                ['N-050', 'Yahiko', 'Ninja', 'Rare'],
                ['N-051', 'Gaara (Shukaku)', 'Ninja', 'Super Rare'],
                ['N-052', 'Itachi Uchiha (Tsukuyomi)', 'Ninja', 'Secret Rare'],
                ['N-053', 'Sand Coffin', 'Jutsu', 'Rare'],
                ['N-054', 'Amaterasu', 'Jutsu', 'Super Rare'],
                ['N-055', 'Summoning Jutsu', 'Jutsu', 'Uncommon'],
                ['N-056', 'Sharingan', 'Jutsu', 'Rare'],
                ['N-057', 'Sage Mode', 'Jutsu', 'Super Rare'],
                ['N-058', 'Reanimation Jutsu', 'Jutsu', 'Super Rare'],
                ['N-059', 'Hidden Sand Village', 'Mission', 'Common'],
                ['N-060', 'Akatsuki Hideout', 'Mission', 'Rare'],
            ],
            // Will of Fire
            'Will of Fire' => [
                ['N-061', 'Killer Bee', 'Ninja', 'Super Rare'],
                ['N-062', 'Fourth Raikage', 'Ninja', 'Rare'],
                ['N-063', 'Mei Terumi', 'Ninja', 'Rare'],
                ['N-064', 'Danzo Shimura', 'Ninja', 'Rare'],
                ['N-065', 'Sai', 'Ninja', 'Uncommon'],
                ['N-066', 'Yamato', 'Ninja', 'Uncommon'],
                ['N-067', 'Naruto Uzumaki (Bijuu Mode)', 'Ninja', 'Secret Rare'],
                ['N-068', 'Minato Namikaze (Fourth Hokage)', 'Ninja', 'Secret Rare'],
                ['N-069', 'Hiruzen Sarutobi (Third Hokage)', 'Ninja', 'Super Rare'],
                ['N-070', 'Tsunade (Fifth Hokage)', 'Ninja', 'Super Rare'],
                ['N-071', 'Kakashi Hatake (Sixth Hokage)', 'Ninja', 'Super Rare'],
                ['N-072', 'Pain (Six Paths)', 'Ninja', 'Secret Rare'],
                ['N-073', 'Konan (Paper Angel)', 'Ninja', 'Super Rare'],
                ['N-074', 'Nagato', 'Ninja', 'Super Rare'],
                ['N-075', 'Killer Bee (Eight Tails)', 'Ninja', 'Super Rare'],
                ['N-076', 'Rasenshuriken', 'Jutsu', 'Super Rare'],
                ['N-077', 'Bijuudama', 'Jutsu', 'Super Rare'],
                ['N-078', 'Six Paths of Pain', 'Jutsu', 'Secret Rare'],
                ['N-079', 'Hidden Cloud Village', 'Mission', 'Common'],
                ['N-080', 'Hidden Mist Village', 'Mission', 'Common'],
                ['N-081', 'Konohagakure', 'Mission', 'Rare'],
                ['N-082', 'Root (ANBU)', 'Mission', 'Uncommon'],
                ['N-083', 'Five Kage Summit', 'Mission', 'Rare'],
                ['N-084', 'Chunin Exams', 'Mission', 'Common'],
                ['N-085', 'Fourth Shinobi War', 'Mission', 'Super Rare'],
            ],
            // Eternal Rivals
            'Eternal Rivals' => [
                ['N-086', 'Obito Uchiha', 'Ninja', 'Secret Rare'],
                ['N-087', 'Madara Uchiha', 'Ninja', 'Secret Rare'],
                ['N-088', 'Hashirama Senju', 'Ninja', 'Super Rare'],
                ['N-089', 'Tobirama Senju', 'Ninja', 'Super Rare'],
                ['N-090', 'Hiruzen Sarutobi (Reanimated)', 'Ninja', 'Rare'],
                ['N-091', 'Minato Namikaze (Reanimated)', 'Ninja', 'Super Rare'],
                ['N-092', 'Naruto Uzumaki (Six Paths)', 'Ninja', 'Secret Rare'],
                ['N-093', 'Sasuke Uchiha (Rinnegan)', 'Ninja', 'Secret Rare'],
                ['N-094', 'Kakashi Hatake (Twin Mangekyo)', 'Ninja', 'Super Rare'],
                ['N-095', 'Kaguya Otsutsuki', 'Ninja', 'Secret Rare'],
                ['N-096', 'Hagoromo Otsutsuki', 'Ninja', 'Super Rare'],
                ['N-097', 'Hamura Otsutsuki', 'Ninja', 'Rare'],
                ['N-098', 'Black Zetsu', 'Ninja', 'Rare'],
                ['N-099', 'Spiral Zetsu', 'Ninja', 'Common'],
                ['N-100', 'Madara Uchiha (Ten-Tails)', 'Ninja', 'Secret Rare'],
                ['N-101', 'Obito Uchiha (Ten-Tails)', 'Ninja', 'Super Rare'],
                ['N-102', 'Infinite Tsukuyomi', 'Jutsu', 'Secret Rare'],
                ['N-103', 'Susanoo', 'Jutsu', 'Super Rare'],
                ['N-104', 'Limbo', 'Jutsu', 'Super Rare'],
                ['N-105', 'Truth-Seeking Balls', 'Jutsu', 'Super Rare'],
                ['N-106', 'Boruto Uzumaki', 'Ninja', 'Super Rare'],
                ['N-107', 'Sarada Uchiha', 'Ninja', 'Rare'],
                ['N-108', 'Mitsuki', 'Ninja', 'Uncommon'],
                ['N-109', 'Himawari Uzumaki', 'Ninja', 'Common'],
                ['N-110', 'Naruto Uzumaki (Seventh Hokage)', 'Ninja', 'Secret Rare'],
                ['N-111', 'Sasuke Uchiha (Adult)', 'Ninja', 'Super Rare'],
                ['N-112', 'Sakura Haruno (Adult)', 'Ninja', 'Rare'],
                ['N-113', 'Kakashi Hatake (Hokage)', 'Ninja', 'Super Rare'],
                ['N-114', 'Rock Lee (Adult)', 'Ninja', 'Uncommon'],
                ['N-115', 'Hinata Uzumaki', 'Ninja', 'Rare'],
            ],
        ];

        $imported = 0;
        foreach ($sets as $setName => $cards) {
            foreach ($cards as $card) {
                TcgCardCatalog::updateOrCreate(
                    ['card_number' => $card[0], 'tcg_category' => 'naruto'],
                    [
                        'name'     => $card[1],
                        'set_name' => $setName,
                        'rarity'   => $card[3],
                    ]
                );
                $imported++;
            }
        }

        $this->command->info("✅ Naruto: $imported carte importate!");
    }
}