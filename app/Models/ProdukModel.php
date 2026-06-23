<?php

namespace App\Models;

use CodeIgniter\Model;

class ProdukModel extends Model
{
    protected $table            = 'produk_pelatihan';
    protected $primaryKey       = 'id_produk';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'id_kategori', 'id_admin', 'judul', 'deskripsi',
        'harga_awal', 'harga_promo', 'diskon_persen', 'gambar'
    ];

    /**
     * Get classes optionally filtered by admin
     *
     * @param int|null $adminId
     * @return array
     */
    public function getClassesByAdmin(?int $adminId = null): array
    {
        $builder = $this->select('produk_pelatihan.*, kategori_produk.nama_kategori, users.nama_lengkap as nama_admin')
                        ->join('kategori_produk', 'kategori_produk.id_kategori = produk_pelatihan.id_kategori', 'left')
                        ->join('users', 'users.id_user = produk_pelatihan.id_admin', 'left')
                        ->orderBy('produk_pelatihan.id_produk', 'DESC');

        if ($adminId !== null) {
            $builder->where('produk_pelatihan.id_admin', $adminId);
        }

        return $builder->findAll();
    }

    /**
     * Get all classes with details
     *
     * @return array
     */
    public function getAllWithDetails(): array
    {
        return $this->select('produk_pelatihan.*, kategori_produk.nama_kategori, users.nama_lengkap as nama_admin')
                ->join('kategori_produk', 'kategori_produk.id_kategori = produk_pelatihan.id_kategori', 'left')
                ->join('users', 'users.id_user = produk_pelatihan.id_admin', 'left')
                ->orderBy('produk_pelatihan.id_produk', 'DESC')
                ->findAll();
    }

    /**
     * Get class by ID with full details
     *
     * @param int $id
     * @return array|null
     */
    public function getWithDetails(int $id): ?array
    {
        return $this->select('produk_pelatihan.*, kategori_produk.nama_kategori, users.nama_lengkap as nama_admin')
                ->join('kategori_produk', 'kategori_produk.id_kategori = produk_pelatihan.id_kategori', 'left')
                ->join('users', 'users.id_user = produk_pelatihan.id_admin', 'left')
                ->where('produk_pelatihan.id_produk', $id)
                ->first();
    }
}
