<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class PenggunaMigration extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_pengguna' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nama_pengguna' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'unique'     => true,
            ],
            'kata_sandi' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'nama_lengkap' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'hak_akses' => [
                'type'       => "ENUM('ADMIN', 'INSTRUKTUR')",
                'default'    => 'INSTRUKTUR',
                'null'       => false,
            ],
            'foto_profil' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'jenjang' => [
                'type'       => 'ENUM',
                'constraint' => ['ELEMENTARY', 'HIGH_SCHOOL'],
                'null'       => true,
                'default'    => null,
            ],
            'status' => [
                'type'       => "ENUM('AKTIF', 'NONAKTIF')",
                'default'    => 'AKTIF',
                'null'       => false,
            ],
            'waktu_dibuat' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'waktu_diubah' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
        ]);

        $this->forge->addKey('id_pengguna', true);
        $this->forge->createTable('pengguna');
    }

    public function down()
    {
        $this->forge->dropTable('pengguna');
    }
}
