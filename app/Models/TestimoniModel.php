<?php

namespace App\Models;

use CodeIgniter\Model;

class TestimoniModel extends Model
{
    protected $table            = 'testimoni';
    protected $primaryKey       = 'id_testimoni';
    protected $useAutoIncrement = true;
    protected $allowedFields    = [
        'id_user', 'id_produk', 'nama', 'foto_profil', 'deskripsi', 'rating'
    ];
}
