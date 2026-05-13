<?php

namespace App\Models;

use CodeIgniter\Model;

class HasilUjianModel extends Model
{
    protected $table            = 'hasil_ujian';
    protected $primaryKey       = 'id_hasil';
    protected $useAutoIncrement = true;
    protected $allowedFields    = [
        'id_user', 'id_ujian', 'nilai', 'status_lulus', 'tanggal_ujian'
    ];

    public function getDetailedResults($ujianId = null)
    {
        $builder = $this->select('hasil_ujian.*, users.nama_lengkap, produk_pelatihan.judul as nama_kelas')
                        ->join('users', 'users.id_user = hasil_ujian.id_user')
                        ->join('ujian_sertifikasi', 'ujian_sertifikasi.id_ujian = hasil_ujian.id_ujian')
                        ->join('produk_pelatihan', 'produk_pelatihan.id_produk = ujian_sertifikasi.id_produk');
        
        if ($ujianId) {
            $builder->where('hasil_ujian.id_ujian', $ujianId);
        }

        return $builder->orderBy('tanggal_ujian', 'DESC')->findAll();
    }
}
