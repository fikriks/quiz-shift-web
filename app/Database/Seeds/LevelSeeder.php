<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class LevelSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nama_level'   => 'BEGINNER',
                'deskripsi'    => 'Basic English - Fundamental grammar concepts and simple sentence structures',
                'nilai_min'    => 0,
                'nilai_max'    => 59,
                'waktu_dibuat' => date('Y-m-d H:i:s'),
            ],
            [
                'nama_level'   => 'INTERMEDIATE',
                'deskripsi'    => 'Intermediate English - More complex grammar including conditionals, modals, and passive voice',
                'nilai_min'    => 60,
                'nilai_max'    => 79,
                'waktu_dibuat' => date('Y-m-d H:i:s'),
            ],
            [
                'nama_level'   => 'ADVANCED',
                'deskripsi'    => 'Advanced English - Complex sentences, inversion, subjunctive mood, and nuanced expressions',
                'nilai_min'    => 80,
                'nilai_max'    => 100,
                'waktu_dibuat' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('level')->insertBatch($data);

        echo "✅ LevelSeeder completed! 3 levels created.\n";
    }
}
