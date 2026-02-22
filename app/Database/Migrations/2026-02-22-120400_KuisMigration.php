<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class KuisMigration extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_kuis' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nama_kuis' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'id_peserta' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'waktu_mulai' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'waktu_selesai' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['BERLANGSUNG', 'SELESAI', 'DIBATALKAN'],
                'default'    => 'BERLANGSUNG',
            ],
            'total_nilai' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'level_ditetapkan' => [
                'type'       => 'ENUM',
                'constraint' => ['BEGINNER', 'INTERMEDIATE', 'ADVANCED'],
                'null'       => true,
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

        $this->forge->addKey('id_kuis', true);
        $this->forge->addForeignKey('id_peserta', 'peserta', 'id_peserta', 'CASCADE', 'CASCADE');
        $this->forge->createTable('kuis');
    }

    public function down()
    {
        $this->forge->dropTable('kuis');
    }
}
