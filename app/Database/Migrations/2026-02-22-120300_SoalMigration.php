<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class SoalMigration extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_soal' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'pertanyaan' => [
                'type' => 'TEXT',
            ],
            'opsi_a' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'opsi_b' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'opsi_c' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'opsi_d' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'jawaban_benar' => [
                'type'       => 'ENUM',
                'constraint' => ['A', 'B', 'C', 'D'],
            ],
            'id_level' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'dibuat_oleh' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
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

        $this->forge->addKey('id_soal', true);
        $this->forge->addForeignKey('id_level', 'level', 'id_level', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('dibuat_oleh', 'pengguna', 'id_pengguna', 'CASCADE', 'CASCADE');
        $this->forge->createTable('soal');
    }

    public function down()
    {
        $this->forge->dropTable('soal');
    }
}
