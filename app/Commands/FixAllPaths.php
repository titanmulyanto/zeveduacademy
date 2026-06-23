<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class FixAllPaths extends BaseCommand
{
    protected $group = 'Custom';
    protected $name = 'fix:allpaths';
    protected $description = 'Fix all duplicate and malformed image paths in database';

    public function run(array $params)
    {
        $db = db_connect();

        CLI::write("Fixing all image paths...", 'yellow');

        // Fix slider_banner
        $sliders = $db->query("SELECT * FROM slider_banner")->getResult();
        foreach ($sliders as $s) {
            $original = $s->gambar;
            $fixed = $this->fixPath($original, 'cms/slider/');
            if ($fixed !== $original) {
                $db->query("UPDATE slider_banner SET gambar = ? WHERE id_slider = ?", [$fixed, $s->id_slider]);
                CLI::write("Fixed Slider {$s->id_slider}: $original -> $fixed", 'green');
            }
        }

        // Fix feature_section
        $features = $db->query("SELECT * FROM feature_section")->getResult();
        foreach ($features as $f) {
            if (empty($f->gambar)) continue;
            $original = $f->gambar;
            $fixed = $this->fixPath($original, 'cms/features/');
            if ($fixed !== $original) {
                $db->query("UPDATE feature_section SET gambar = ? WHERE id_feature = ?", [$fixed, $f->id_feature]);
                CLI::write("Fixed Feature {$f->id_feature}: $original -> $fixed", 'green');
            }
        }

        // Fix testimoni
        $testimonis = $db->query("SELECT * FROM testimoni")->getResult();
        foreach ($testimonis as $t) {
            if (empty($t->foto_profil)) continue;
            $original = $t->foto_profil;
            $fixed = $this->fixPath($original, 'cms/testimoni/');
            if ($fixed !== $original) {
                $db->query("UPDATE testimoni SET foto_profil = ? WHERE id_testimoni = ?", [$fixed, $t->id_testimoni]);
                CLI::write("Fixed Testimoni {$t->id_testimoni}: $original -> $fixed", 'green');
            }
        }

        CLI::write("Done!", 'green');
    }

    private function fixPath(string $path, string $folder): string
    {
        if (preg_match('/^cms\//', $path)) return $path;
        if (preg_match('/((?:testi|slider|feature)_[a-z0-9_]+\.(?:jpg|png|jpeg|webp))/i', $path, $matches)) {
            return $folder . strtolower($matches[1]);
        }
        if (strpos($path, 'localhost') !== false && preg_match('/uploads\/(cms\/[^\/]+\/[^\/]+)/', $path, $matches)) {
            return $matches[1];
        }
        return $path;
    }
}