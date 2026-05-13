<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UjianModel;
use App\Models\SoalUjianModel;
use App\Models\HasilUjianModel;
use App\Models\ProdukModel;

class UjianController extends BaseController
{
    protected $ujianModel;
    protected $soalModel;
    protected $hasilModel;
    protected $produkModel;

    public function __construct()
    {
        $this->ujianModel = new UjianModel();
        $this->soalModel = new SoalUjianModel();
        $this->hasilModel = new HasilUjianModel();
        $this->produkModel = new ProdukModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Manajemen Ujian Sertifikasi',
            'exams' => $this->produkModel->select('produk_pelatihan.*, ujian_sertifikasi.id_ujian, ujian_sertifikasi.durasi_menit')
                                         ->join('ujian_sertifikasi', 'ujian_sertifikasi.id_produk = produk_pelatihan.id_produk', 'left')
                                         ->findAll()
        ];

        return view('admin/ujian/index', $data);
    }

    public function questions($id)
    {
        $ujian = $this->ujianModel->find($id);
        if (!$ujian) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();

        $produk = $this->produkModel->find($ujian['id_produk']);

        $data = [
            'title' => 'Kelola Soal: ' . $produk['judul'],
            'ujian' => $ujian,
            'questions' => $this->soalModel->where('id_ujian', $id)->findAll()
        ];

        return view('admin/ujian/questions', $data);
    }

    public function results()
    {
        $data = [
            'title' => 'Hasil Ujian Siswa',
            'results' => $this->hasilModel->getDetailedResults()
        ];

        return view('admin/ujian/results', $data);
    }
}
