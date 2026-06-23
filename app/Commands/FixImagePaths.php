<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class FixImagePaths extends BaseCommand
{
    protected $group = 'Custom';
    protected $name = 'fix:imagepaths';
    protected $description = 'Fix duplicate image paths in database';

    public function run(array $params)
    {
        $db = db_connect();

        CLI::write("🔧 Memperbaiki image paths...", 'yellow');

        // Fix slider_banner
        $db->query("UPDATE slider_banner SET gambar = REPLACE(gambar, 'http://localhost/zeveduacademy/public/uploads/', '') WHERE gambar LIKE 'http://localhost%'");
        $sliderCount = $db->affectedRows();
        CLI::write("✅ Slider: $sliderCount record difix", 'green');

        // Fix feature_section
        $db->query("UPDATE feature_section SET gambar = REPLACE(gambar, 'http://localhost/zeveduacademy/public/uploads/', '') WHERE gambar LIKE 'http://localhost%'");
        $featureCount = $db->affectedRows();
        CLI::write("✅ Feature: $featureCount record difix", 'green');

        // Fix testimoni
        $db->query("UPDATE testimoni SET foto_profil = REPLACE(foto_profil, 'http://localhost/zeveduacademy/public/uploads/', '') WHERE foto_profil LIKE 'http://localhost%'");
        $testiCount = $db->affectedRows();
        CLI::write("✅ Testimoni: $testiCount record difix", 'green');

        // Fix produk_pelatihan
        $db->query("UPDATE produk_pelatihan SET gambar = REPLACE(gambar, 'http://localhost/zeveduacademy/public/uploads/', '') WHERE gambar LIKE 'http://localhost%'");
        $produkCount = $db->affectedRows();
        CLI::write("✅ Produk: $produkCount record difix", 'green');

        CLI::write("\n✅ Selesai!", 'green');
    }
}