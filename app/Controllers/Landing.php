<?php

namespace App\Controllers;

use App\Models\KategoriProdukModel;
use App\Models\ProdukModel;
use App\Models\FeatureSectionModel;
use App\Models\TestimoniModel;

class Landing extends BaseController
{
    protected $kategoriModel;
    protected $produkModel;
    protected $featureModel;
    protected $testimoniModel;

    public function __construct()
    {
        $this->kategoriModel = new KategoriProdukModel();
        $this->produkModel = new ProdukModel();
        $this->featureModel = new FeatureSectionModel();
        $this->testimoniModel = new TestimoniModel();
        helper(['text']); // Load text helper for character_limiter
    }

    public function index(): string
    {
        // Get all kategori for dropdown and nav pills
        $kategori = $this->kategoriModel->findAll();

        // Get all produk with kategori details
        $produk = $this->produkModel->getAllWithDetails();

        // Get FAQ for help section
        $faqModel = new \App\Models\FaqModel();
        $faq = $faqModel->findAll();

        // Get slider banners (1:1 aspect ratio images)
        $sliderModel = new \App\Models\SliderBannerModel();
        $sliders = $sliderModel->orderBy('urutan', 'ASC')->findAll();

        // Get Features Section Data untuk Zig-Zag Layout
        $features_list = $this->featureModel->orderBy('id_feature', 'ASC')->findAll();

        // Get Testimoni Data dengan JOIN ke users table untuk foto_profil yang benar
        $testimoni_list = $this->testimoniModel
            ->select('testimoni.*, users.foto_profil as user_foto, users.nama_lengkap, users.instansi')
            ->join('users', 'users.id_user = testimoni.id_user', 'left')
            ->orderBy('testimoni.rating', 'DESC')
            ->findAll();

        $data = [
            'title' => 'Zevedu Academy - Belajar, Berkembang, Berkarier',
            'kategori' => $kategori,
            'produk' => $produk,
            'faq' => $faq,
            'sliders' => $sliders,
            // Features & Testimonials Data
            'features_list' => $features_list,
            'testimoni_list' => $testimoni_list,
        ];

        return view('landing/index', $data);
    }

    /**
     * Get produk by kategori (for AJAX filtering)
     */
    public function getProdukByKategori(int $idKategori = null)
    {
        if ($idKategori === null) {
            $produk = $this->produkModel->getAllWithDetails();
        } else {
            $produk = $this->produkModel->select('produk_pelatihan.*, kategori_produk.nama_kategori, kategori_produk.deskripsi as kategori_deskripsi')
                ->join('kategori_produk', 'kategori_produk.id_kategori = produk_pelatihan.id_kategori', 'left')
                ->where('produk_pelatihan.id_kategori', $idKategori)
                ->findAll();
        }

        return $this->response->setJSON($produk);
    }
}