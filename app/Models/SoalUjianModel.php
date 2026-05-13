<?php

namespace App\Models;

use CodeIgniter\Model;

class SoalUjianModel extends Model
{
    protected $table            = 'soal_ujian';
    protected $primaryKey       = 'id_soal';
    protected $useAutoIncrement = true;
    protected $allowedFields    = [
        'id_ujian', 'pertanyaan', 'opsi_a', 'opsi_b', 'opsi_c', 'jawaban_benar'
    ];
}
