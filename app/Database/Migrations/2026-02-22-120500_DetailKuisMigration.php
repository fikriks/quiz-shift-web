<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DetailKuisMigration extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_detail' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_kuis' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'id_soal' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'jawaban_siswa' => [
                'type'       => 'ENUM',
                'constraint' => ['A', 'B', 'C', 'D'],
            ],
            'is_benar' => [
                'type'       => 'BOOLEAN',
                'default'    => false,
            ],
            'urutan_soal' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'waktu_dibuat' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
        ]);

        $this->forge->addKey('id_detail', true);
        $this->forge->addForeignKey('id_kuis', 'kuis', 'id_kuis', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_soal', 'soal', 'id_soal', 'CASCADE', 'CASCADE');
        $this->forge->createTable('detail_kuis');
    }

    public function down()
    {
        $this->forge->dropTable('detail_kuis');
    }
}
