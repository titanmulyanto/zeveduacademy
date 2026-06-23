<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ProdukModel;
use App\Models\KategoriMateriModel;
use App\Models\SubMateriVideoModel;
use App\Models\MateriKelasModel;
use App\Models\UserModel;

class MateriController extends BaseController
{
    protected $produkModel;
    protected $kategoriModel;
    protected $videoModel;
    protected $materiKelasModel;
    protected $userModel;

    public function __construct()
    {
        $this->produkModel = new ProdukModel();
        $this->kategoriModel = new KategoriMateriModel();
        $this->videoModel = new SubMateriVideoModel();
        $this->materiKelasModel = new MateriKelasModel();
        $this->userModel = new UserModel();
        helper(['form', 'url']);
    }

    /**
     * List all classes (produk/pelatihan)
     */
    public function index()
    {
        $role = session()->get('role');
        $adminId = ($role === 'admin') ? session()->get('id_user') : null;

        // Super Admin sees all classes, Admin sees only assigned classes
        $classes = ($role === 'super_admin')
            ? $this->produkModel->getAllWithDetails()
            : $this->produkModel->getClassesByAdmin($adminId);

        $data = [
            'title' => 'Manajemen Kurikulum',
            'classes' => $classes,
            'admins' => $this->userModel->whereIn('role', ['admin', 'super_admin'])->findAll(),
            'userRole' => $role
        ];

        return view('admin/materi/index', $data);
    }

    /**
     * View class detail with categories and videos
     */
    public function detail($id)
    {
        $produk = $this->produkModel->find($id);
        if (!$produk) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Check permission: Super Admin can view all, Admin can only view their own classes
        $role = session()->get('role');
        $userId = session()->get('id_user');
        if ($role === 'admin' && $produk['id_admin'] != $userId) {
            return redirect()->to('/admin/materi')->with('error', 'Anda tidak memiliki akses ke kelas ini.');
        }

        $kategori = $this->kategoriModel->where('id_produk', $id)->orderBy('urutan_kategori', 'ASC')->findAll();

        // Get videos for each category
        foreach ($kategori as &$kat) {
            $kat['videos'] = $this->videoModel->where('id_kategori_materi', $kat['id_kategori_materi'])
                                              ->orderBy('urutan_video', 'ASC')
                                              ->findAll();
        }

        // Get dokumen/materi_kelas for this produk
        $materiKelas = $this->materiKelasModel->where('id_produk', $id)->orderBy('urutan_materi', 'ASC')->findAll();

        // Get pemateri info
        $pemateri = null;
        if (!empty($produk['id_admin'])) {
            $pemateri = $this->userModel->find($produk['id_admin']);
        }

        // Get Ujian data
        $ujianModel = new \App\Models\UjianModel();
        $ujian = $ujianModel->where('id_produk', $id)->first();

        // Get soal preview if ujian exists
        $soal_preview = [];
        if ($ujian) {
            $soalModel = new \App\Models\SoalUjianModel();
            $soal_preview = $soalModel->where('id_ujian', $ujian['id_ujian'])
                                      ->orderBy('id_soal', 'ASC')
                                      ->limit(5)
                                      ->findAll();
            // Count total soal
            $ujian['total_soal'] = $soalModel->where('id_ujian', $ujian['id_ujian'])->countAllResults();
        }

        // Get chat data
        $chatModel = new \App\Models\ChatKelasModel();
        $chats = $chatModel->select('chat_kelas.*, users.nama_lengkap as nama_pengirim')
                          ->join('users', 'users.id_user = chat_kelas.id_user', 'left')
                          ->where('chat_kelas.id_produk', $id)
                          ->orderBy('waktu_kirim', 'ASC')
                          ->findAll();

        // Get student progress - simplified query
        $progressModel = new \App\Models\ProgressBelajarModel();
        // Get all progress for users who have access to this produk (via transaksi paid)
        $student_progress = $progressModel->select('progress_belajar.*, users.nama_lengkap, users.email')
                                          ->join('users', 'users.id_user = progress_belajar.id_user', 'left')
                                          ->join('transaksi', 'transaksi.id_user = progress_belajar.id_user AND transaksi.id_produk = ' . $id . ' AND transaksi.status_pembayaran = "paid"', 'left')
                                          ->where('transaksi.id_transaksi IS NOT NULL')
                                          ->groupBy('progress_belajar.id_user')
                                          ->findAll();

        // Get materi titles for each progress entry
        $db = \Config\Database::connect();
        foreach ($student_progress as &$sp) {
            // Check if it's dokumen (materi_kelas)
            $materi = $db->table('materi_kelas')->where('id_materi', $sp['id_materi'])->get()->getRowArray();
            if ($materi) {
                $sp['judul_materi'] = $materi['judul_materi'];
                $sp['judul_video'] = null;
            } else {
                // Check if it's video (sub_materi_video)
                $video = $db->table('sub_materi_video')->where('id_video', $sp['id_materi'])->get()->getRowArray();
                $sp['judul_video'] = $video['judul_video'] ?? null;
                $sp['judul_materi'] = null;
            }
        }

        $data = [
            'title' => 'Kurikulum: ' . $produk['judul'],
            'produk' => $produk,
            'modules' => $kategori,
            'dokumen' => $materiKelas,
            'pemateri' => $pemateri,
            'ujian' => $ujian ?? null,
            'soal_preview' => $soal_preview,
            'chats' => $chats,
            'student_progress' => $student_progress
        ];

        return view('admin/materi/detail', $data);
    }

    /**
     * Create new class (produk)
     */
    public function create()
    {
        $role = session()->get('role');

        // Only Super Admin can create classes
        if ($role !== 'super_admin') {
            return redirect()->to('/admin/materi')->with('error', 'Anda tidak memiliki akses untuk membuat kelas baru.');
        }

        try {
            $kategoriProdukModel = new \App\Models\KategoriProdukModel();
            $kategoriProduk = $kategoriProdukModel->findAll();
        } catch (\Exception $e) {
            $kategoriProduk = [];
        }

        // Determine class type from URL parameter
        $classType = $this->request->getGet('type');
        $defaultHarga = ($classType === 'paid') ? 0 : 0; // Will be set by user

        $data = [
            'title' => 'Tambah Kelas Baru',
            'kategoriProduk' => $kategoriProduk,
            'admins' => $this->userModel->whereIn('role', ['admin', 'super_admin'])->findAll(),
            'classType' => $classType // 'free' or 'paid'
        ];

        return view('admin/materi/create', $data);
    }

    /**
     * Store new class
     */
    public function store()
    {
        $file = $this->request->getFile('gambar');
        $gambarPath = '';
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/materi/', $newName);
            $gambarPath = 'uploads/materi/' . $newName;
        }

        $hargaAwal = (float) $this->request->getPost('harga_awal');
        $hargaPromo = (float) $this->request->getPost('harga_promo');

        // Calculate discount percentage
        $diskon = 0;
        if ($hargaAwal > 0 && $hargaPromo < $hargaAwal) {
            $diskon = round((($hargaAwal - $hargaPromo) / $hargaAwal) * 100);
        }

        $this->produkModel->insert([
            'id_kategori'   => $this->request->getPost('id_kategori'),
            'id_admin'      => $this->request->getPost('id_admin'),
            'judul'         => $this->request->getPost('judul'),
            'deskripsi'     => $this->request->getPost('deskripsi'),
            'harga_awal'    => $hargaAwal,
            'harga_promo'   => $hargaPromo,
            'diskon_persen' => $diskon,
            'gambar'        => $gambarPath
        ]);

        return redirect()->to('/admin/materi')->with('success', 'Kelas berhasil ditambahkan.');
    }

    /**
     * Edit class
     */
    public function edit($id)
    {
        $produk = $this->produkModel->find($id);
        if (!$produk) {
            return redirect()->to('/admin/materi')->with('error', 'Kelas tidak ditemukan.');
        }

        $role = session()->get('role');
        $userId = session()->get('id_user');

        // Check permission: Super Admin can edit all, Admin can only edit their own classes
        if ($role === 'admin' && $produk['id_admin'] != $userId) {
            return redirect()->to('/admin/materi')->with('error', 'Anda tidak memiliki akses untuk mengedit kelas ini.');
        }

        try {
            $kategoriProdukModel = new \App\Models\KategoriProdukModel();
            $kategoriProduk = $kategoriProdukModel->findAll();
        } catch (\Exception $e) {
            $kategoriProduk = [];
        }

        $data = [
            'title' => 'Edit Kelas',
            'produk' => $produk,
            'kategoriProduk' => $kategoriProduk,
            'admins' => $this->userModel->whereIn('role', ['admin', 'super_admin'])->findAll()
        ];

        return view('admin/materi/edit', $data);
    }

    /**
     * Update class
     */
    public function update($id)
    {
        $produk = $this->produkModel->find($id);
        if (!$produk) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Check permission: Super Admin can update all, Admin can only update their own classes
        $role = session()->get('role');
        $userId = session()->get('id_user');
        if ($role === 'admin' && $produk['id_admin'] != $userId) {
            return redirect()->to('/admin/materi')->with('error', 'Anda tidak memiliki akses untuk mengupdate kelas ini.');
        }

        $data = [
            'id_kategori' => $this->request->getPost('id_kategori'),
            'id_admin'    => $this->request->getPost('id_admin'),
            'judul'       => $this->request->getPost('judul'),
            'deskripsi'   => $this->request->getPost('deskripsi'),
            'harga_awal'  => (float) $this->request->getPost('harga_awal'),
            'harga_promo' => (float) $this->request->getPost('harga_promo')
        ];

        // Calculate discount
        if ($data['harga_awal'] > 0 && $data['harga_promo'] < $data['harga_awal']) {
            $data['diskon_persen'] = round((($data['harga_awal'] - $data['harga_promo']) / $data['harga_awal']) * 100);
        } else {
            $data['diskon_persen'] = 0;
        }

        // Handle image upload
        $file = $this->request->getFile('gambar');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/materi/', $newName);
            $data['gambar'] = 'uploads/materi/' . $newName;
        }

        $this->produkModel->update($id, $data);

        return redirect()->to('/admin/materi')->with('success', 'Kelas berhasil diupdate.');
    }

    /**
     * Delete class
     */
    public function delete($id)
    {
        $produk = $this->produkModel->find($id);
        if (!$produk) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Check permission: Only Super Admin can delete classes
        $role = session()->get('role');
        if ($role !== 'super_admin') {
            return redirect()->to('/admin/materi')->with('error', 'Anda tidak memiliki akses untuk menghapus kelas ini.');
        }

        $this->produkModel->delete($id);

        return redirect()->to('/admin/materi')->with('success', 'Kelas berhasil dihapus.');
    }

    // ========================
    // KATEGORI MATERI CRUD
    // ========================

    /**
     * Store new kategori materi
     */
    public function storeKategori()
    {
        $id_produk = $this->request->getPost('id_produk');

        $this->kategoriModel->insert([
            'id_produk' => $id_produk,
            'judul_kategori' => $this->request->getPost('judul_kategori'),
            'urutan_kategori' => $this->request->getPost('urutan_kategori') ?? 1
        ]);

        return redirect()->back()->with('success', 'Kategori berhasil ditambahkan.');
    }

    /**
     * Update kategori materi
     */
    public function updateKategori($id)
    {
        $this->kategoriModel->update($id, [
            'judul_kategori' => $this->request->getPost('judul_kategori'),
            'urutan_kategori' => $this->request->getPost('urutan_kategori') ?? 1
        ]);

        return redirect()->back()->with('success', 'Kategori berhasil diupdate.');
    }

    /**
     * Delete kategori materi
     */
    public function deleteKategori($id)
    {
        $this->kategoriModel->delete($id);
        return redirect()->back()->with('success', 'Kategori berhasil dihapus.');
    }

    // ========================
    // VIDEO/SUB MATERI CRUD
    // ========================

    /**
     * Store new video/sub materi - YouTube URL only, no file upload
     */
    public function storeVideo()
    {
        $this->videoModel->insert([
            'id_kategori_materi' => $this->request->getPost('id_kategori_materi'),
            'judul_video' => $this->request->getPost('judul_video'),
            'youtube_url' => $this->request->getPost('youtube_url'),
            'durasi_menit' => $this->request->getPost('durasi_menit') ?? 0,
            'urutan_video' => $this->request->getPost('urutan_video') ?? 1,
            'minimal_progress_unlock' => $this->request->getPost('minimal_progress_unlock') ?? 0
            // file_materi tidak digunakan - hanya YouTube URL
        ]);

        return redirect()->back()->with('success', 'Video berhasil ditambahkan.');
    }

    /**
     * Update video/sub materi - YouTube URL only, no file upload
     */
    public function updateVideo($id)
    {
        $data = [
            'judul_video' => $this->request->getPost('judul_video'),
            'youtube_url' => $this->request->getPost('youtube_url'),
            'durasi_menit' => $this->request->getPost('durasi_menit') ?? 0,
            'urutan_video' => $this->request->getPost('urutan_video') ?? 1,
            'minimal_progress_unlock' => $this->request->getPost('minimal_progress_unlock') ?? 0
            // file_materi tidak diupdate - hanya YouTube URL
        ];

        $this->videoModel->update($id, $data);

        return redirect()->back()->with('success', 'Video berhasil diupdate.');
    }

    /**
     * Delete video/sub materi
     */
    public function deleteVideo($id)
    {
        $this->videoModel->delete($id);
        return redirect()->back()->with('success', 'Video berhasil dihapus.');
    }

    // ========================
    // MATERI KELAS (DOKUMEN) CRUD
    // ========================

    /**
     * Store new materi/dokumen
     */
    /**
     * Store new materi/dokumen - no video_url field
     */
    public function storeMateri()
    {
        $file = $this->request->getFile('file_materi');
        $filePath = '';

        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/materi/', $newName);
            $filePath = 'uploads/materi/' . $newName;
        }

        $this->materiKelasModel->insert([
            'id_produk' => $this->request->getPost('id_produk'),
            'judul_materi' => $this->request->getPost('judul_materi'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            // video_url dihapus - tidak diperlukan untuk dokumen
            'file_materi' => $filePath,
            'urutan_materi' => $this->request->getPost('urutan_materi') ?? 1
        ]);

        return redirect()->back()->with('success', 'Materi berhasil ditambahkan.');
    }

    /**
     * Update materi/dokumen - no video_url field
     */
    public function updateMateri($id)
    {
        $data = [
            'judul_materi' => $this->request->getPost('judul_materi'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            // video_url dihapus - tidak diperlukan untuk dokumen
            'urutan_materi' => $this->request->getPost('urutan_materi') ?? 1
        ];

        $file = $this->request->getFile('file_materi');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/materi/', $newName);
            $data['file_materi'] = 'uploads/materi/' . $newName;
        }

        $this->materiKelasModel->update($id, $data);

        return redirect()->back()->with('success', 'Materi berhasil diupdate.');
    }

    /**
     * Delete materi/dokumen
     */
    public function deleteMateri($id)
    {
        $this->materiKelasModel->delete($id);
        return redirect()->back()->with('success', 'Materi berhasil dihapus.');
    }

    // ========================
    // CHAT WITH STUDENT
    // ========================

    /**
     * Send chat message to students
     */
    public function sendChat()
    {
        $chatModel = new \App\Models\ChatKelasModel();
        $role = session()->get('role');

        $pengirim = ($role === 'super_admin') ? 'super_admin' : 'admin';

        $chatModel->insert([
            'id_user' => session()->get('id_user'),
            'id_produk' => $this->request->getPost('id_produk'),
            'pesan' => $this->request->getPost('pesan'),
            'pengirim' => $pengirim,
            'waktu_kirim' => date('Y-m-d H:i:s')
        ]);

        return redirect()->back()->with('success', 'Pesan berhasil dikirim.');
    }

    /**
     * Delete chat message
     */
    public function deleteChat($id)
    {
        $role = session()->get('role');

        // Only admin/super_admin can delete chat
        if ($role !== 'super_admin' && $role !== 'admin') {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk hapus chat ini.');
        }

        $chatModel = new \App\Models\ChatKelasModel();
        $chatModel->delete($id);

        return redirect()->back()->with('success', 'Chat berhasil dihapus.');
    }
}