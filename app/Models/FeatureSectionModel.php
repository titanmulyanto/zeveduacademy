<?php

namespace App\Models;

use CodeIgniter\Model;

class FeatureSectionModel extends Model
{
    protected $table            = 'feature_section';
    protected $primaryKey       = 'id_feature';
    protected $useAutoIncrement = true;
    protected $allowedFields    = ['judul', 'deskripsi', 'gambar'];
}
