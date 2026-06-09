<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddWaktuPengerjaanToLevel extends Migration
{
    public function up()
    {
        $this->forge->addColumn('level', [
            'waktu_pengerjaan' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'default'    => 15,
                'null'       => false,
                'after'      => 'nilai_max',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('level', 'waktu_pengerjaan');
    }
}
