<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\KategoriMateriModel;
use App\Models\ProdukModel;
use Config\Database;

class KategoriMateriController extends BaseController
{
    protected $kategoriMateriModel;
    protected $produkModel;

    public function __construct()
    {
        $this->kategoriMateriModel = new KategoriMateriModel();
        $this->produkModel = new ProdukModel();
        helper(['form', 'url']);
    }

    /**
     * List all kategori materi across all classes
     */
    public function index()
    {
        $db = Database::connect();

        // Get all kategori materi with class info
        $sql = "SELECT km.*, pp.judul as nama_kelas, pp.id_produk,
                       (SELECT COUNT(*) FROM sub_materi_video WHERE id_kategori_materi = km.id_kategori_materi) as total_video
                FROM kategori_materi km
                JOIN produk_pelatihan pp ON pp.id_produk = km.id_produk
                ORDER BY km.id_produk DESC, km.urutan_kategori ASC";

        $kategori = $db->query($sql)->getResultArray();

        // Get all classes for dropdown
        $classes = $this->produkModel->orderBy('judul', 'ASC')->findAll();

        $data = [
            'title' => 'Kategori Materi',
            'kategori' => $kategori,
            'classes' => $classes
        ];

        return view('admin/kategori_materi/index', $data);
    }

    /**
     * Store new kategori materi
     */
    public function store()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'id_produk' => 'required',
            'judul_kategori' => 'required|max_length[150]',
            'urutan_kategori' => 'permit_empty|integer'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('error', $validation->listErrors());
        }

        $data = [
            'id_produk' => $this->request->getPost('id_produk'),
            'judul_kategori' => $this->request->getPost('judul_kategori'),
            'urutan_kategori' => $this->request->getPost('urutan_kategori') ?? 1
        ];

        $this->kategoriMateriModel->insert($data);

        return redirect()->to('/admin/kategori-materi')->with('success', 'Kategori materi berhasil ditambahkan.');
    }

    /**
     * Update kategori materi
     */
    public function update($id)
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'id_produk' => 'required',
            'judul_kategori' => 'required|max_length[150]',
            'urutan_kategori' => 'permit_empty|integer'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('error', $validation->listErrors());
        }

        $data = [
            'id_produk' => $this->request->getPost('id_produk'),
            'judul_kategori' => $this->request->getPost('judul_kategori'),
            'urutan_kategori' => $this->request->getPost('urutan_kategori') ?? 1
        ];

        $this->kategoriMateriModel->update($id, $data);

        return redirect()->to('/admin/kategori-materi')->with('success', 'Kategori materi berhasil diupdate.');
    }

    /**
     * Delete kategori materi
     */
    public function delete($id)
    {
        // Check if kategori has videos
        $db = Database::connect();
        $videoCount = $db->table('sub_materi_video')
            ->where('id_kategori_materi', $id)
            ->countAllResults();

        if ($videoCount > 0) {
            return redirect()->to('/admin/kategori-materi')->with('error', 'Tidak dapat menghapus kategori yang masih memiliki video. Hapus video terlebih dahulu.');
        }

        $this->kategoriMateriModel->delete($id);

        return redirect()->to('/admin/kategori-materi')->with('success', 'Kategori materi berhasil dihapus.');
    }

    /**
     * Get kategori by produk (for AJAX)
     */
    public function getByProduk($produkId)
    {
        $kategori = $this->kategoriMateriModel
            ->where('id_produk', $produkId)
            ->orderBy('urutan_kategori', 'ASC')
            ->findAll();

        return $this->response->setJSON([
            'status' => 'success',
            'data' => $kategori
        ]);
    }
}
