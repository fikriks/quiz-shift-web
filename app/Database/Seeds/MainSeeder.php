<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MainSeeder extends Seeder
{
    public function run()
    {
        // Seed pengguna (admin & guru) - must be first as other tables reference it
        $this->call('PenggunaSeeder');

        // Seed level - must be before soal as soal references level
        $this->call('LevelSeeder');

        // Seed soal - depends on pengguna and level
        $this->call('SoalSeeder');

        // Seed peserta - independent table
        $this->call('PesertaSeeder');

        echo "✅ All seeders completed successfully!\n";
        echo "📊 Database seeded with:\n";
        echo "   - Pengguna (admin & guru)\n";
        echo "   - Level (3 levels)\n";
        echo "   - Soal (24 sample questions)\n";
        echo "   - Peserta (5 sample participants)\n";
    }
}