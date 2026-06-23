<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUnlockSertifikatPriceToProduk extends Migration
{
    public function up()
    {
        // Add unlock_sertifikat_price column to produk_pelatihan table
        $this->forge->addColumn('produk_pelatihan', [
            'unlock_sertifikat_price' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0,
                'null'      => true,
                'after'     => 'harga_promo'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('produk_pelatihan', 'unlock_sertifikat_price');
    }
}