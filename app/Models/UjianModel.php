<?php

namespace App\Models;

use CodeIgniter\Model;

class UjianModel extends Model
{
    protected $table            = 'ujian_sertifikasi';
    protected $primaryKey       = 'id_ujian';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['id_produk', 'judul_ujian', 'durasi_menit', 'nilai_minimal_lulus', 'minimal_progress_persen'];

    /**
     * Get ujian with produk details
     */
    public function getUjianWithProduk(int $id_produk): ?array
    {
        $db = \Config\Database::connect();
        return $db->table($this->table)
                  ->where('id_produk', $id_produk)
                  ->get()
                  ->getRowArray();
    }

    /**
     * Check if student can take exam based on progress
     */
    public function canTakeExam(int $id_user, int $id_produk): bool
    {
        $ujian = $this->getUjianWithProduk($id_produk);
        if (!$ujian) return false;

        // Check minimum progress requirement
        $progressModel = new \App\Models\ProgressBelajarModel();
        $progress = $progressModel->getTotalProgress($id_user, $id_produk);

        return $progress >= ($ujian['minimal_progress_persen'] ?? 100);
    }
}