<?php

namespace App\Models;

use CodeIgniter\Model;

class SliderBannerModel extends Model
{
    protected $table            = 'slider_banner';
    protected $primaryKey       = 'id_slider';
    protected $useAutoIncrement = true;
    protected $allowedFields    = ['gambar', 'urutan'];
}
