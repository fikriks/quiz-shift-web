<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PenggunaSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nama_pengguna' => 'admin123',
                'kata_sandi'    => password_hash('123456789', PASSWORD_DEFAULT),
                'nama_lengkap'  => 'Administrator',
                'hak_akses'     => 'ADMIN',
                'foto_profil'   => null,
                'status'        => 'AKTIF',
                'jenjang'       => null,
                'waktu_dibuat'  => date('Y-m-d H:i:s'),
                'waktu_diubah'  => date('Y-m-d H:i:s'),
            ],
            [
                'nama_pengguna' => 'instruktur123',
                'kata_sandi'    => password_hash('123456789', PASSWORD_DEFAULT),
                'nama_lengkap'  => 'Instruktur Elementary',
                'hak_akses'     => 'INSTRUKTUR',
                'foto_profil'   => null,
                'status'        => 'AKTIF',
                'jenjang'       => 'ELEMENTARY',
                'waktu_dibuat'  => date('Y-m-d H:i:s'),
                'waktu_diubah'  => date('Y-m-d H:i:s'),
            ],
            [
                'nama_pengguna' => 'instruktur456',
                'kata_sandi'    => password_hash('123456789', PASSWORD_DEFAULT),
                'nama_lengkap'  => 'Instruktur High School',
                'hak_akses'     => 'INSTRUKTUR',
                'foto_profil'   => null,
                'status'        => 'AKTIF',
                'jenjang'       => 'HIGH_SCHOOL',
                'waktu_dibuat'  => date('Y-m-d H:i:s'),
                'waktu_diubah'  => date('Y-m-d H:i:s'),
            ],
        ];

        $insertCount = 0;
        foreach ($data as $user) {
            // Check if user already exists
            $exists = $this->db->table('pengguna')
                              ->where('nama_pengguna', $user['nama_pengguna'])
                              ->countAllResults() > 0;

            if (!$exists) {
                $this->db->table('pengguna')->insert($user);
                $insertCount++;
            }
        }

        if ($insertCount > 0) {
            echo "✅ PenggunaSeeder completed! {$insertCount} users created.\n";
        } else {
            echo "⏭️  PenggunaSeeder skipped - users already exist.\n";
        }
    }
}