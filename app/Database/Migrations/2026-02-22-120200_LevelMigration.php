<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class LevelMigration extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_level' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nama_level' => [
                'type'       => 'ENUM',
                'constraint' => ['BEGINNER', 'INTERMEDIATE', 'ADVANCED'],
            ],
            'deskripsi' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'nilai_min' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'nilai_max' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 100,
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

        $this->forge->addKey('id_level', true);
        $this->forge->createTable('level');
    }

    public function down()
    {
        $this->forge->dropTable('level');
    }
}
