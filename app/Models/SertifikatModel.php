<?php

namespace App\Models;

use CodeIgniter\Model;

class SertifikatModel extends Model
{
    protected $table            = 'sertifikat_kelas';
    protected $primaryKey       = 'id_sertifikat';
    protected $useAutoIncrement = true;
    protected $allowedFields    = ['id_produk', 'background_template'];
}
