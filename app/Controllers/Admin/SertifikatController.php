<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SertifikatModel;
use App\Models\ProdukModel;

class SertifikatController extends BaseController
{
    protected $sertifikatModel;
    protected $produkModel;

    public function __construct()
    {
        $this->sertifikatModel = new SertifikatModel();
        $this->produkModel = new ProdukModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Kelola Sertifikat',
            'templates' => $this->produkModel->select('produk_pelatihan.*, sertifikat_kelas.background_template, sertifikat_kelas.id_sertifikat')
                                            ->join('sertifikat_kelas', 'sertifikat_kelas.id_produk = produk_pelatihan.id_produk', 'left')
                                            ->findAll()
        ];
        return view('admin/sertifikat/index', $data);
    }

    public function preview($id)
    {
        // Mock preview logic
        $data = [
            'title' => 'Preview Sertifikat',
            'name' => 'John Doe, S.Kom',
            'course' => 'Fullstack Web Development with CodeIgniter 4',
            'date' => date('d F Y')
        ];
        return view('admin/sertifikat/preview', $data);
    }
}
