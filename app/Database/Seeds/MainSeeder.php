<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MainSeeder extends Seeder
{
    public function run()
    {
        // Seed pengguna (admin & guru)
        $this->call('PenggunaSeeder');

        echo "✅ All seeders completed successfully!\n";
        echo "📊 Database seeded with:\n";
        echo "   - Pengguna\n";
    }
}