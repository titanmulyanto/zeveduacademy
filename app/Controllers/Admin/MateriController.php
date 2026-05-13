<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ProdukModel;
use App\Models\KategoriMateriModel;
use App\Models\SubMateriVideoModel;

class MateriController extends BaseController
{
    protected $produkModel;
    protected $kategoriModel;
    protected $videoModel;

    public function __construct()
    {
        $this->produkModel = new ProdukModel();
        $this->kategoriModel = new KategoriMateriModel();
        $this->videoModel = new SubMateriVideoModel();
    }

    public function index()
    {
        // For now, list all classes. Later filter by admin if role is 'admin'
        $data = [
            'title' => 'Manajemen Kurikulum',
            'classes' => $this->produkModel->getClassesByAdmin()
        ];

        return view('admin/materi/index', $data);
    }

    public function detail($id)
    {
        $produk = $this->produkModel->find($id);
        if (!$produk) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();

        $kategori = $this->kategoriModel->where('id_produk', $id)->orderBy('urutan_kategori', 'ASC')->findAll();
        
        // Get videos for each category
        foreach($kategori as &$kat) {
            $kat['videos'] = $this->videoModel->where('id_kategori_materi', $kat['id_kategori_materi'])
                                              ->orderBy('urutan_video', 'ASC')
                                              ->findAll();
        }

        $data = [
            'title' => 'Kurikulum: ' . $produk['judul'],
            'produk' => $produk,
            'modules' => $kategori
        ];

        return view('admin/materi/detail', $data);
    }
}
