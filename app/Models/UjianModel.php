<?php

namespace App\Models;

use CodeIgniter\Model;

class UjianModel extends Model
{
    protected $table            = 'ujian_sertifikasi';
    protected $primaryKey       = 'id_ujian';
    protected $useAutoIncrement = true;
    protected $allowedFields    = ['id_produk', 'durasi_menit', 'nilai_minimal_lulus'];
}
