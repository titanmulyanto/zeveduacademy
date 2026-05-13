<?php

namespace App\Models;

use CodeIgniter\Model;

class SubMateriVideoModel extends Model
{
    protected $table            = 'sub_materi_video';
    protected $primaryKey       = 'id_video';
    protected $useAutoIncrement = true;
    protected $allowedFields    = [
        'id_kategori_materi', 'judul_video', 'youtube_url', 
        'file_materi', 'durasi_menit', 'urutan_video', 'minimal_progress_unlock'
    ];
}
