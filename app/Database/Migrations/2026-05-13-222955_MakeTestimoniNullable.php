<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MakeTestimoniNullable extends Migration
{
    public function up()
    {
        $fields = [
            'id_user' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'id_produk' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
        ];
        $this->forge->modifyColumn('testimoni', $fields);
    }

    public function down()
    {
        // No need to reverse for now
    }
}
