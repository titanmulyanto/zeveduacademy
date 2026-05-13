<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAdminToProdukPelatihan extends Migration
{
    public function up()
    {
        $fields = [
            'id_admin' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
                'after'      => 'id_kategori',
            ],
        ];
        $this->forge->addColumn('produk_pelatihan', $fields);
        $this->forge->addForeignKey('id_admin', 'users', 'id_user', 'CASCADE', 'SET NULL');
    }

    public function down()
    {
        $this->forge->dropForeignKey('produk_pelatihan', 'produk_pelatihan_id_admin_foreign');
        $this->forge->dropColumn('produk_pelatihan', 'id_admin');
    }
}
