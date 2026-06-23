<?php

namespace App\Models;

use CodeIgniter\Model;

class MateriKelasModel extends Model
{
    protected $table            = 'materi_kelas';
    protected $primaryKey       = 'id_materi';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'id_produk', 'judul_materi', 'deskripsi', 'video_url', 'file_materi', 'urutan_materi'
    ];

    /**
     * Get materi by product with ordering
     *
     * @param int $produkId
     * @return array
     */
    public function getByProduk(int $produkId): array
    {
        return $this->where('id_produk', $produkId)
                    ->orderBy('urutan_materi', 'ASC')
                    ->findAll();
    }
}