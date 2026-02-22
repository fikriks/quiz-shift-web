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
                'waktu_dibuat'  => date('Y-m-d H:i:s'),
                'waktu_diubah'  => date('Y-m-d H:i:s'),
            ],
            [
                'nama_pengguna' => 'instruktur123',
                'kata_sandi'    => password_hash('123456789', PASSWORD_DEFAULT),
                'nama_lengkap'  => 'Instruktur',
                'hak_akses'     => 'INSTRUKTUR',
                'foto_profil'   => null,
                'status'        => 'AKTIF',
                'waktu_dibuat'  => date('Y-m-d H:i:s'),
                'waktu_diubah'  => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('pengguna')->insertBatch($data);
    }
}