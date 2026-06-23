<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\KategoriProdukModel;

class KategoriProdukController extends BaseController
{
    protected $kategoriModel;

    public function __construct()
    {
        $this->kategoriModel = new KategoriProdukModel();
        helper(['form', 'url']);
    }

    /**
     * List all categories
     */
    public function index()
    {
        $kategori = $this->kategoriModel->orderBy('id_kategori', 'ASC')->findAll();

        $data = [
            'title' => 'Kategori Produk',
            'kategori' => $kategori
        ];

        return view('admin/kategori_produk/index', $data);
    }

    /**
     * Store new category
     */
    public function store()
    {
        $validation = \Config\Services::validation();

        $rules = [
            'nama_kategori' => 'required|min_length[2]|max_length[100]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $this->kategoriModel->insert([
            'nama_kategori' => $this->request->getPost('nama_kategori'),
            'deskripsi' => $this->request->getPost('deskripsi') ?? ''
        ]);

        return redirect()->to('/admin/kategori')->with('success', 'Kategori berhasil ditambahkan.');
    }

    /**
     * Update category
     */
    public function update($id)
    {
        $validation = \Config\Services::validation();

        $rules = [
            'nama_kategori' => 'required|min_length[2]|max_length[100]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $this->kategoriModel->update($id, [
            'nama_kategori' => $this->request->getPost('nama_kategori'),
            'deskripsi' => $this->request->getPost('deskripsi') ?? ''
        ]);

        return redirect()->to('/admin/kategori')->with('success', 'Kategori berhasil diupdate.');
    }

    /**
     * Delete category
     */
    public function delete($id)
    {
        // Check if there are products using this category
        $produkModel = new \App\Models\ProdukModel();
        $produkCount = $produkModel->where('id_kategori', $id)->countAllResults();

        if ($produkCount > 0) {
            return redirect()->to('/admin/kategori')->with('error', 'Kategori tidak bisa dihapus karena masih digunakan oleh ' . $produkCount . ' produk.');
        }

        $this->kategoriModel->delete($id);

        return redirect()->to('/admin/kategori')->with('success', 'Kategori berhasil dihapus.');
    }
}