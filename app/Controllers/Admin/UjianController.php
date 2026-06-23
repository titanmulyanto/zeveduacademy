<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UjianModel;
use App\Models\SoalUjianModel;
use App\Models\HasilUjianModel;
use App\Models\ProdukModel;
use App\Models\UserModel;

class UjianController extends BaseController
{
    protected $ujianModel;
    protected $soalModel;
    protected $hasilModel;
    protected $produkModel;
    protected $userModel;

    public function __construct()
    {
        $this->ujianModel = new UjianModel();
        $this->soalModel = new SoalUjianModel();
        $this->hasilModel = new HasilUjianModel();
        $this->produkModel = new ProdukModel();
        $this->userModel = new UserModel();
        helper(['form', 'url']);
    }

    /**
     * List all exams (ujian_sertifikasi)
     */
    public function index()
    {
        $data = [
            'title' => 'Manajemen Ujian Sertifikasi',
            'exams' => $this->produkModel->select('produk_pelatihan.*, ujian_sertifikasi.id_ujian, ujian_sertifikasi.durasi_menit, ujian_sertifikasi.nilai_minimal_lulus')
                                         ->join('ujian_sertifikasi', 'ujian_sertifikasi.id_produk = produk_pelatihan.id_produk', 'left')
                                         ->findAll()
        ];

        return view('admin/ujian/index', $data);
    }

    /**
     * Create exam configuration for a class
     * If produkId is provided (from detail page), pre-select that class
     */
    public function create($produkId = null)
    {
        $data = [
            'title' => 'Buat Ujian Baru',
            'produkId' => $produkId,
            'classes' => $this->produkModel->findAll()
        ];

        return view('admin/ujian/create', $data);
    }

    /**
     * Store exam configuration
     */
    public function store()
    {
        $id_produk = $this->request->getPost('id_produk');

        // Check if exam already exists for this product
        $existing = $this->ujianModel->where('id_produk', $id_produk)->first();
        if ($existing) {
            return redirect()->back()->with('error', 'Ujian sudah ada untuk produk ini.');
        }

        $this->ujianModel->insert([
            'id_produk' => $id_produk,
            'judul_ujian' => $this->request->getPost('judul_ujian') ?? 'Ujian Sertifikasi',
            'durasi_menit' => $this->request->getPost('durasi_menit') ?? 60,
            'nilai_minimal_lulus' => $this->request->getPost('nilai_minimal_lulus') ?? 70,
            'minimal_progress_persen' => $this->request->getPost('minimal_progress_persen') ?? 100
        ]);

        return redirect()->to('/admin/materi/detail/' . $id_produk)->with('success', 'Konfigurasi ujian berhasil disimpan.');
    }

    /**
     * Edit exam configuration
     */
    public function edit($id)
    {
        $ujian = $this->ujianModel->find($id);
        if (!$ujian) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'title' => 'Edit Ujian',
            'ujian' => $ujian,
            'classes' => $this->produkModel->findAll()
        ];

        return view('admin/ujian/edit', $data);
    }

    /**
     * Update exam configuration
     */
    public function update($id)
    {
        $ujian = $this->ujianModel->find($id);
        if (!$ujian) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $this->ujianModel->update($id, [
            'judul_ujian' => $this->request->getPost('judul_ujian') ?? 'Ujian Sertifikasi',
            'durasi_menit' => $this->request->getPost('durasi_menit') ?? 60,
            'nilai_minimal_lulus' => $this->request->getPost('nilai_minimal_lulus') ?? 70,
            'minimal_progress_persen' => $this->request->getPost('minimal_progress_persen') ?? 100
        ]);

        return redirect()->to('/admin/materi/detail/' . $ujian['id_produk'])->with('success', 'Konfigurasi ujian berhasil diupdate.');
    }

    /**
     * Delete exam configuration
     */
    public function delete($id)
    {
        $ujian = $this->ujianModel->find($id);
        if (!$ujian) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Delete all questions first
        $this->soalModel->where('id_ujian', $id)->delete();
        $this->ujianModel->delete($id);

        return redirect()->to('/admin/ujian')->with('success', 'Ujian berhasil dihapus.');
    }

    /**
     * View/manage questions for an exam
     */
    public function questions($id)
    {
        $ujian = $this->ujianModel->find($id);
        if (!$ujian) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $produk = $this->produkModel->find($ujian['id_produk']);

        $data = [
            'title' => 'Kelola Soal: ' . $produk['judul'],
            'ujian' => $ujian,
            'produk' => $produk,
            'questions' => $this->soalModel->where('id_ujian', $id)->orderBy('id_soal', 'ASC')->findAll()
        ];

        return view('admin/ujian/questions', $data);
    }

    /**
     * Store new question
     */
    public function storeQuestion()
    {
        $id_ujian = $this->request->getPost('id_ujian');

        $this->soalModel->insert([
            'id_ujian' => $id_ujian,
            'pertanyaan' => $this->request->getPost('pertanyaan'),
            'opsi_a' => $this->request->getPost('opsi_a'),
            'opsi_b' => $this->request->getPost('opsi_b'),
            'opsi_c' => $this->request->getPost('opsi_c'),
            'jawaban_benar' => $this->request->getPost('jawaban_benar')
        ]);

        return redirect()->back()->with('success', 'Soal berhasil ditambahkan.');
    }

    /**
     * Update question
     */
    public function updateQuestion($id)
    {
        $this->soalModel->update($id, [
            'pertanyaan' => $this->request->getPost('pertanyaan'),
            'opsi_a' => $this->request->getPost('opsi_a'),
            'opsi_b' => $this->request->getPost('opsi_b'),
            'opsi_c' => $this->request->getPost('opsi_c'),
            'jawaban_benar' => $this->request->getPost('jawaban_benar')
        ]);

        return redirect()->back()->with('success', 'Soal berhasil diupdate.');
    }

    /**
     * Delete question
     */
    public function deleteQuestion($id)
    {
        $this->soalModel->delete($id);
        return redirect()->back()->with('success', 'Soal berhasil dihapus.');
    }

    /**
     * View all exam results
     */
    public function results()
    {
        $statusFilter = $this->request->getGet('status');

        $query = $this->hasilModel->getDetailedResults();

        if ($statusFilter) {
            $query = array_filter($query, function($result) use ($statusFilter) {
                return $result['status_lulus'] === $statusFilter;
            });
        }

        $data = [
            'title' => 'Hasil Ujian Siswa',
            'results' => array_values($query),
            'statusFilter' => $statusFilter
        ];

        return view('admin/ujian/results', $data);
    }

    /**
     * View student exam result detail
     */
    public function viewResult($id_hasil)
    {
        $db = \Config\Database::connect();

        $result = $db->table('hasil_ujian')
            ->select('hasil_ujian.*, users.nama_lengkap, users.email, produk_pelatihan.judul as nama_produk, ujian_sertifikasi.durasi_menit, ujian_sertifikasi.nilai_minimal_lulus')
            ->join('users', 'users.id_user = hasil_ujian.id_user')
            ->join('ujian_sertifikasi', 'ujian_sertifikasi.id_ujian = hasil_ujian.id_ujian')
            ->join('produk_pelatihan', 'produk_pelatihan.id_produk = ujian_sertifikasi.id_produk')
            ->where('hasil_ujian.id_hasil', $id_hasil)
            ->get()
            ->getRowArray();

        if (!$result) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Get questions and user's answers
        $questions = $this->soalModel->where('id_ujian', $result['id_ujian'])->findAll();

        $data = [
            'title' => 'Detail Hasil Ujian',
            'result' => $result,
            'questions' => $questions,
            'totalQuestions' => count($questions)
        ];

        return view('admin/ujian/view_result', $data);
    }
}