<?php

namespace App\Models;

use CodeIgniter\Model;

class KategoriMateriModel extends Model
{
    protected $table            = 'kategori_materi';
    protected $primaryKey       = 'id_kategori_materi';
    protected $useAutoIncrement = true;
    protected $allowedFields    = ['id_produk', 'judul_kategori', 'urutan_kategori'];
}
