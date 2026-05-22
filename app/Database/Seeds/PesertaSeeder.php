<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PesertaSeeder extends Seeder
{
    public function run()
    {
        $defaultPassword = password_hash('password123', PASSWORD_DEFAULT);

        $data = [
            [
                'username'     => 'peserta1',
                'password'     => $defaultPassword,
                'nama_lengkap' => 'Ahmad Fauzi',
                'email'        => 'ahmad.fauzi@example.com',
                'no_hp'        => '081234567890',
                'token'        => $this->generateToken(),
                'status'       => 'AKTIF',
                'jenjang'      => 'ELEMENTARY',
                'waktu_dibuat' => date('Y-m-d H:i:s'),
            ],
            [
                'username'     => 'peserta2',
                'password'     => $defaultPassword,
                'nama_lengkap' => 'Siti Rahayu',
                'email'        => 'siti.rahayu@example.com',
                'no_hp'        => '081234567891',
                'token'        => $this->generateToken(),
                'status'       => 'AKTIF',
                'jenjang'      => 'ELEMENTARY',
                'waktu_dibuat' => date('Y-m-d H:i:s'),
            ],
            [
                'username'     => 'peserta3',
                'password'     => $defaultPassword,
                'nama_lengkap' => 'Budi Santoso',
                'email'        => 'budi.santoso@example.com',
                'no_hp'        => '081234567892',
                'token'        => $this->generateToken(),
                'status'       => 'AKTIF',
                'jenjang'      => 'ELEMENTARY',
                'waktu_dibuat' => date('Y-m-d H:i:s'),
            ],
            [
                'username'     => 'peserta4',
                'password'     => $defaultPassword,
                'nama_lengkap' => 'Dewi Lestari',
                'email'        => 'dewi.lestari@example.com',
                'no_hp'        => '081234567893',
                'token'        => $this->generateToken(),
                'status'       => 'AKTIF',
                'jenjang'      => 'HIGH_SCHOOL',
                'waktu_dibuat' => date('Y-m-d H:i:s'),
            ],
            [
                'username'     => 'peserta5',
                'password'     => $defaultPassword,
                'nama_lengkap' => 'Rizky Pratama',
                'email'        => 'rizky.pratama@example.com',
                'no_hp'        => '081234567894',
                'token'        => $this->generateToken(),
                'status'       => 'AKTIF',
                'jenjang'      => 'HIGH_SCHOOL',
                'waktu_dibuat' => date('Y-m-d H:i:s'),
            ],
        ];

        $insertCount = 0;
        foreach ($data as $peserta) {
            // Check if peserta already exists
            $exists = $this->db->table('peserta')
                              ->where('username', $peserta['username'])
                              ->countAllResults() > 0;

            if (!$exists) {
                $this->db->table('peserta')->insert($peserta);
                $insertCount++;
            }
        }

        if ($insertCount > 0) {
            echo "✅ PesertaSeeder completed! {$insertCount} participants created.\n";
            echo "   Username: peserta1-5\n";
            echo "   Password: password123\n";
        } else {
            echo "⏭️  PesertaSeeder skipped - participants already exist.\n";
        }
    }

    /**
     * Generate a unique API token
     */
    private function generateToken()
    {
        return bin2hex(random_bytes(32));
    }
}
