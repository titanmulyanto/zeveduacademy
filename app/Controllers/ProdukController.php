<?php

namespace App\Controllers;

use App\Models\ProdukModel;
use App\Models\KategoriProdukModel;
use App\Models\TestimoniModel;

class ProdukController extends BaseController
{
    public function detail(int $id): string
    {
        $produkModel = new ProdukModel();
        $kategoriModel = new KategoriProdukModel();
        $testimoniModel = new TestimoniModel();

        $produk = $produkModel->getWithDetails($id);

        if (!$produk) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Produk tidak ditemukan');
        }

        // Get testimoni for this produk
        $testimoni = $testimoniModel->select('testimoni.*, users.nama_lengkap, users.foto_profil')
            ->join('users', 'users.id_user = testimoni.id_user', 'left')
            ->where('testimoni.id_produk', $id)
            ->findAll();

        // Get all kategori for navigation
        $kategori = $kategoriModel->findAll();

        $data = [
            'title' => $produk['judul'] . ' - Zevedu Academy',
            'produk' => $produk,
            'testimoni' => $testimoni,
            'kategori' => $kategori,
        ];

        return view('landing/produk_detail', $data);
    }
}