<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class PesertaMigration extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_peserta' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'username' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'unique'     => true,
            ],
            'password' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'nama_lengkap' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'unique'     => true,
            ],
            'no_hp' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
            ],
            'token' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'unique'     => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['AKTIF', 'NONAKTIF'],
                'default'    => 'AKTIF',
            ],
            'jenjang' => [
                'type'       => 'ENUM',
                'constraint' => ['ELEMENTARY', 'HIGH_SCHOOL'],
                'default'    => 'ELEMENTARY',
            ],
            'waktu_dibuat' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'waktu_diubah' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id_peserta', true);
        $this->forge->createTable('peserta');
    }

    public function down()
    {
        $this->forge->dropTable('peserta');
    }
}
