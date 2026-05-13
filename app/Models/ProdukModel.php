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

    public function getClassesByAdmin($adminId = null)
    {
        $builder = $this->select('produk_pelatihan.*, kategori_produk.nama_kategori')
                        ->join('kategori_produk', 'kategori_produk.id_kategori = produk_pelatihan.id_kategori');
        
        if ($adminId) {
            $builder->where('id_admin', $adminId);
        }

        return $builder->findAll();
    }
}
