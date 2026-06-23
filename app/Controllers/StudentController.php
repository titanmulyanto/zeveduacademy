<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProdukModel;
use App\Models\KategoriMateriModel;
use App\Models\SubMateriVideoModel;
use App\Models\MateriKelasModel;
use App\Models\ProgressBelajarModel;
use App\Models\UjianModel;
use App\Models\SoalUjianModel;
use App\Models\HasilUjianModel;
use App\Models\ChatKelasModel;
use App\Models\TransaksiModel;
use App\Models\KelasUserModel;

class StudentController extends BaseController
{
    protected $produkModel;
    protected $kategoriModel;
    protected $videoModel;
    protected $materiKelasModel;
    protected $progressModel;
    protected $ujianModel;
    protected $soalModel;
    protected $hasilModel;
    protected $chatModel;
    protected $transaksiModel;
    protected $kelasUserModel;

    public function __construct()
    {
        $this->produkModel = new ProdukModel();
        $this->kategoriModel = new KategoriMateriModel();
        $this->videoModel = new SubMateriVideoModel();
        $this->materiKelasModel = new MateriKelasModel();
        $this->progressModel = new ProgressBelajarModel();
        $this->ujianModel = new UjianModel();
        $this->soalModel = new SoalUjianModel();
        $this->hasilModel = new HasilUjianModel();
        $this->chatModel = new ChatKelasModel();
        $this->transaksiModel = new TransaksiModel();
        $this->kelasUserModel = new KelasUserModel();
        helper(['form', 'url']);
    }

    /**
     * Student Dashboard - List enrolled classes
     * Includes BOTH transaksi (pembayaran) AND kelas_user (manual access)
     */
    public function index()
    {
        $userId = session()->get('id_user');
        $db = \Config\Database::connect();

        // Get enrolled classes via transaksi (pembayaran)
        $kelasViaTransaksi = $this->transaksiModel->select('transaksi.*, produk_pelatihan.judul, produk_pelatihan.gambar, produk_pelatihan.deskripsi, produk_pelatihan.harga_promo')
            ->join('produk_pelatihan', 'produk_pelatihan.id_produk = transaksi.id_produk')
            ->where('transaksi.id_user', $userId)
            ->where('transaksi.status_pembayaran', 'paid')
            ->findAll();

        // Get enrolled classes via kelas_user (manual/bonus access)
        $kelasViaKelasUser = $db->table('kelas_user ku')
            ->select('ku.*, produk_pelatihan.judul, produk_pelatihan.gambar, produk_pelatihan.deskripsi, produk_pelatihan.harga_promo')
            ->join('produk_pelatihan', 'produk_pelatihan.id_produk = ku.id_produk')
            ->where('ku.id_user', $userId)
            ->where('ku.status_akses', 'aktif')
            ->get()
            ->getResultArray();

        // Merge both arrays, avoid duplicates by id_produk
        $allClasses = [];
        $seenProduk = [];

        foreach ($kelasViaTransaksi as $kelas) {
            $allClasses[$kelas['id_produk']] = $kelas;
            $seenProduk[$kelas['id_produk']] = true;
        }

        foreach ($kelasViaKelasUser as $kelas) {
            if (!isset($seenProduk[$kelas['id_produk']])) {
                $allClasses[$kelas['id_produk']] = $kelas;
            }
        }

        // Get progress for each class
        foreach ($allClasses as &$kelas) {
            $kelas['progress'] = $this->progressModel->getTotalProgress($userId, $kelas['id_produk']);
        }

        $data = [
            'title' => 'Dashboard Saya',
            'enrolledClasses' => array_values($allClasses)
        ];

        return view('student/dashboard', $data);
    }

    /**
     * View class detail / learning page
     */
    public function kelas($id)
    {
        $userId = session()->get('id_user');

        // Check if user has access via transaksi ATAU kelas_user (dual access system)
        $transaksi = $this->transaksiModel->where('id_user', $userId)
            ->where('id_produk', $id)
            ->where('status_pembayaran', 'paid')
            ->first();

        $hasAccessViaKelasUser = $this->kelasUserModel->hasAccess($userId, $id);

        if (!$transaksi && !$hasAccessViaKelasUser) {
            return redirect()->to('/student')->with('error', 'Anda belum memiliki akses ke kelas ini.');
        }

        $produk = $this->produkModel->find($id);
        if (!$produk) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Get modules with videos
        $kategori = $this->kategoriModel->where('id_produk', $id)->orderBy('urutan_kategori', 'ASC')->findAll();
        foreach ($kategori as &$kat) {
            $kat['videos'] = $this->videoModel->where('id_kategori_materi', $kat['id_kategori_materi'])
                ->orderBy('urutan_video', 'ASC')
                ->findAll();
        }

        // Get documents
        $dokumen = $this->materiKelasModel->where('id_produk', $id)->orderBy('urutan_materi', 'ASC')->findAll();

        // Get user's progress (both documents and videos)
        $progressMap = $this->progressModel->getProgressMap($userId, $id);

        // Check exam eligibility
        $ujian = $this->ujianModel->where('id_produk', $id)->first();
        $canTakeExam = false;
        if ($ujian) {
            $totalProgress = $this->progressModel->getTotalProgress($userId, $id);
            $canTakeExam = $totalProgress >= ($ujian['minimal_progress_persen'] ?? 100);
        }

        // Get chat messages
        $chats = $this->chatModel->where('id_produk', $id)->orderBy('waktu_kirim', 'ASC')->findAll();

        $data = [
            'title' => $produk['judul'],
            'produk' => $produk,
            'modules' => $kategori,
            'dokumen' => $dokumen,
            'ujian' => $ujian,
            'canTakeExam' => $canTakeExam,
            'progressMap' => $progressMap,
            'chats' => $chats
        ];

        return view('student/kelas', $data);
    }

    /**
     * Watch video - update progress
     */
    public function watchVideo($id)
    {
        $userId = session()->get('id_user');

        $video = $this->videoModel->find($id);
        if (!$video) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Get id_produk via kategori_materi
        $kategori = $this->kategoriModel->find($video['id_kategori_materi']);
        $idProduk = $kategori['id_produk'] ?? 0;

        // Check access via transaksi ATAU kelas_user (dual access system)
        $transaksi = $this->transaksiModel->where('id_user', $userId)
            ->where('id_produk', $idProduk)
            ->where('status_pembayaran', 'paid')
            ->first();

        $hasAccessViaKelasUser = $this->kelasUserModel->hasAccess($userId, $idProduk);

        if (!$transaksi && !$hasAccessViaKelasUser) {
            return redirect()->to('/student')->with('error', 'Anda belum memiliki akses.');
        }

        // Get user's progress for this video
        $videoProgress = $this->progressModel->where('id_user', $userId)
            ->where('id_video', $id)
            ->first();

        // Get next video in same kategori for navigation
        $nextVideo = $this->videoModel->where('id_kategori_materi', $video['id_kategori_materi'])
            ->where('urutan_video >', $video['urutan_video'])
            ->orderBy('urutan_video', 'ASC')
            ->first();

        // If no next video in same kategori, check next kategori
        if (!$nextVideo) {
            $nextKategori = $this->kategoriModel->where('id_produk', $idProduk)
                ->where('urutan_kategori >', $kategori['urutan_kategori'])
                ->orderBy('urutan_kategori', 'ASC')
                ->first();

            if ($nextKategori) {
                $nextVideo = $this->videoModel->where('id_kategori_materi', $nextKategori['id_kategori_materi'])
                    ->orderBy('urutan_video', 'ASC')
                    ->first();
            }
        }

        $data = [
            'title' => 'Tonton: ' . $video['judul_video'],
            'video' => $video,
            'videoProgress' => $videoProgress,
            'idProduk' => $idProduk,
            'nextVideo' => $nextVideo
        ];

        return view('student/video', $data);
    }

    /**
     * Update video progress (called via AJAX or form)
     */
    public function updateProgress()
    {
        $userId = session()->get('id_user');
        $idMateri = $this->request->getPost('id_materi');
        $progressPersen = (int) $this->request->getPost('progress_persen', 0);

        // Update or create progress
        $existing = $this->progressModel->where('id_user', $userId)
            ->where('id_materi', $idMateri)
            ->first();

        if ($existing) {
            $this->progressModel->update($existing['id_progress'], [
                'progress_persen' => $progressPersen,
                'terakhir_ditonton' => date('Y-m-d H:i:s'),
                'status_selesai' => ($progressPersen >= 100) ? 'selesai' : 'belum',
                'tanggal_selesai' => ($progressPersen >= 100) ? date('Y-m-d H:i:s') : null
            ]);
        } else {
            $this->progressModel->insert([
                'id_user' => $userId,
                'id_materi' => $idMateri,
                'progress_persen' => $progressPersen,
                'terakhir_ditonton' => date('Y-m-d H:i:s'),
                'status_selesai' => ($progressPersen >= 100) ? 'selesai' : 'belum',
                'tanggal_selesai' => ($progressPersen >= 100) ? date('Y-m-d H:i:s') : null
            ]);
        }

        return $this->response->setJSON(['success' => true]);
    }

    /**
     * Mark video or document as complete
     * Can handle both video (via id_video) and document (via id_materi)
     */
    public function markComplete()
    {
        $userId = session()->get('id_user');
        $idMateri = $this->request->getPost('id_materi');
        $idVideo = $this->request->getPost('id_video');
        $idProduk = $this->request->getPost('id_produk');

        if (!$idProduk) {
            return redirect()->back()->with('error', 'ID Produk tidak valid.');
        }

        // Check if user has access
        $transaksi = $this->transaksiModel->where('id_user', $userId)
            ->where('id_produk', $idProduk)
            ->where('status_pembayaran', 'paid')
            ->first();

        $hasAccessViaKelasUser = $this->kelasUserModel->hasAccess($userId, $idProduk);

        if (!$transaksi && !$hasAccessViaKelasUser) {
            return redirect()->to('/student')->with('error', 'Anda belum memiliki akses.');
        }

        try {
            if ($idVideo) {
                // Mark VIDEO as complete - use id_video field
                $existing = $this->progressModel->where('id_user', $userId)
                    ->where('id_video', $idVideo)
                    ->first();

                if ($existing) {
                    $this->progressModel->update($existing['id_progress'], [
                        'progress_persen' => 100,
                        'status_selesai' => 'selesai',
                        'terakhir_ditonton' => date('Y-m-d H:i:s'),
                        'tanggal_selesai' => date('Y-m-d H:i:s')
                    ]);
                } else {
                    $this->progressModel->insert([
                        'id_user' => $userId,
                        'id_video' => $idVideo,
                        'progress_persen' => 100,
                        'status_selesai' => 'selesai',
                        'terakhir_ditonton' => date('Y-m-d H:i:s'),
                        'tanggal_selesai' => date('Y-m-d H:i:s')
                    ]);
                }
                return redirect()->back()->with('success', 'Video ditandai selesai!');
            } elseif ($idMateri) {
                // Mark DOCUMENT as complete - use id_materi field
                $existing = $this->progressModel->where('id_user', $userId)
                    ->where('id_materi', $idMateri)
                    ->first();

                if ($existing) {
                    $this->progressModel->update($existing['id_progress'], [
                        'progress_persen' => 100,
                        'status_selesai' => 'selesai',
                        'terakhir_ditonton' => date('Y-m-d H:i:s'),
                        'tanggal_selesai' => date('Y-m-d H:i:s')
                    ]);
                } else {
                    $this->progressModel->insert([
                        'id_user' => $userId,
                        'id_materi' => $idMateri,
                        'progress_persen' => 100,
                        'status_selesai' => 'selesai',
                        'terakhir_ditonton' => date('Y-m-d H:i:s'),
                        'tanggal_selesai' => date('Y-m-d H:i:s')
                    ]);
                }
                return redirect()->back()->with('success', 'Dokumen ditandai selesai!');
            } else {
                return redirect()->back()->with('error', 'ID materi atau video tidak ditemukan.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Take exam
     */
    public function exam($idUjian)
    {
        $userId = session()->get('id_user');

        $ujian = $this->ujianModel->find($idUjian);
        if (!$ujian) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Check if user has access to this class (dual access system)
        $transaksi = $this->transaksiModel->where('id_user', $userId)
            ->where('id_produk', $ujian['id_produk'])
            ->where('status_pembayaran', 'paid')
            ->first();

        $hasAccessViaKelasUser = $this->kelasUserModel->hasAccess($userId, $ujian['id_produk']);

        if (!$transaksi && !$hasAccessViaKelasUser) {
            return redirect()->to('/student')->with('error', 'Anda belum memiliki akses ke ujian ini.');
        }

        // Check eligibility - progress requirement
        $totalProgress = $this->progressModel->getTotalProgress($userId, $ujian['id_produk']);
        if ($totalProgress < ($ujian['minimal_progress_persen'] ?? 100)) {
            return redirect()->to('/student/kelas/' . $ujian['id_produk'])
                ->with('error', 'Anda harus menyelesaikan minimal ' . ($ujian['minimal_progress_persen'] ?? 100) . '% materi untuk bisa mengikuti ujian.');
        }

        // Get questions
        $questions = $this->soalModel->where('id_ujian', $idUjian)->findAll();
        if (empty($questions)) {
            return redirect()->to('/student/kelas/' . $ujian['id_produk'])
                ->with('error', 'Ujian belum memiliki soal. Hubungi admin.');
        }

        $data = [
            'title' => 'Ujian: ' . ($ujian['judul_ujian'] ?? 'Sertifikasi'),
            'ujian' => $ujian,
            'questions' => $questions,
            'durasi_detik' => ($ujian['durasi_menit'] ?? 60) * 60
        ];

        return view('student/exam', $data);
    }

    /**
     * Submit exam answers
     */
    public function submitExam()
    {
        $userId = session()->get('id_user');
        $idUjian = $this->request->getPost('id_ujian');

        $ujian = $this->ujianModel->find($idUjian);
        if (!$ujian) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Verify user has access to this class (dual access system)
        $transaksi = $this->transaksiModel->where('id_user', $userId)
            ->where('id_produk', $ujian['id_produk'])
            ->where('status_pembayaran', 'paid')
            ->first();

        $hasAccessViaKelasUser = $this->kelasUserModel->hasAccess($userId, $ujian['id_produk']);

        if (!$transaksi && !$hasAccessViaKelasUser) {
            return redirect()->to('/student')->with('error', 'Anda tidak memiliki akses untuksubmit ujian ini.');
        }

        $questions = $this->soalModel->where('id_ujian', $idUjian)->findAll();

        // Calculate score
        $correct = 0;
        $total = count($questions);

        foreach ($questions as $q) {
            $userAnswer = $this->request->getPost('jawaban_' . $q['id_soal']);
            if ($userAnswer === $q['jawaban_benar']) {
                $correct++;
            }
        }

        $nilai = ($total > 0) ? round(($correct / $total) * 100) : 0;
        $statusLulus = ($nilai >= ($ujian['nilai_minimal_lulus'] ?? 70)) ? 'lulus' : 'tidak';

        // Save result
        $this->hasilModel->insert([
            'id_user' => $userId,
            'id_ujian' => $idUjian,
            'nilai' => $nilai,
            'status_lulus' => $statusLulus,
            'tanggal_ujian' => date('Y-m-d H:i:s')
        ]);

        // Save answers for review
        // (In production, you'd save each answer to a separate table)

        return redirect()->to('/student/exam-result/' . $this->hasilModel->getInsertID())
            ->with('nilai', $nilai)
            ->with('status_lulus', $statusLulus)
            ->with('total', $total)
            ->with('correct', $correct);
    }

    /**
     * View exam result
     */
    public function examResult($idHasil)
    {
        $userId = session()->get('id_user');

        $result = $this->hasilModel->find($idHasil);
        if (!$result || $result['id_user'] != $userId) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $ujian = $this->ujianModel->find($result['id_ujian']);
        $questions = $this->soalModel->where('id_ujian', $result['id_ujian'])->findAll();
        $produk = $this->produkModel->find($ujian['id_produk']);

        // Check if user can retry
        $canRetry = ($result['status_lulus'] === 'tidak');

        $data = [
            'title' => 'Hasil Ujian',
            'result' => $result,
            'ujian' => $ujian,
            'questions' => $questions,
            'produk' => $produk,
            'canRetry' => $canRetry
        ];

        return view('student/result', $data);
    }

    /**
     * Send chat message
     */
    public function sendChat()
    {
        $userId = session()->get('id_user');
        $idProduk = $this->request->getPost('id_produk');
        $pesan = trim($this->request->getPost('pesan'));

        // Validation
        if (empty($idProduk)) {
            return redirect()->back()->with('error', 'ID Produk tidak valid.');
        }

        if (empty($pesan)) {
            return redirect()->back()->with('error', 'Pesan tidak boleh kosong.');
        }

        // Sanitize pesan
        $pesan = strip_tags($pesan);

        // Insert chat message
        try {
            $this->chatModel->insert([
                'id_user' => $userId,
                'id_produk' => $idProduk,
                'pesan' => $pesan,
                'pengirim' => 'student',
                'waktu_kirim' => date('Y-m-d H:i:s')
            ]);

            return redirect()->back()->with('success', 'Pesan terkirim.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengirim pesan: ' . $e->getMessage());
        }
    }

    /**
     * View certificate (locked/unlocked)
     */
    public function sertifikat($idProduk)
    {
        $userId = session()->get('id_user');

        $produk = $this->produkModel->find($idProduk);
        if (!$produk) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Check if user has access to this class (dual access system)
        $transaksi = $this->transaksiModel->where('id_user', $userId)
            ->where('id_produk', $idProduk)
            ->where('status_pembayaran', 'paid')
            ->first();

        $hasAccessViaKelasUser = $this->kelasUserModel->hasAccess($userId, $idProduk);

        if (!$transaksi && !$hasAccessViaKelasUser) {
            return redirect()->to('/student')->with('error', 'Anda belum memiliki akses ke sertifikat ini.');
        }

        // Check if passed exam
        $ujian = $this->ujianModel->where('id_produk', $idProduk)->first();
        $passed = false;
        if ($ujian) {
            $hasil = $this->hasilModel->where('id_user', $userId)
                ->where('id_ujian', $ujian['id_ujian'])
                ->where('status_lulus', 'lulus')
                ->first();
            $passed = ($hasil !== null);
        }

        $data = [
            'title' => 'Sertifikat - ' . $produk['judul'],
            'produk' => $produk,
            'passed' => $passed,
            'ujian' => $ujian
        ];

        return view('student/sertifikat', $data);
    }

    /**
     * List all certificates for student
     * Includes BOTH transaksi (pembayaran) AND kelas_user (manual access)
     */
    public function sertifikatList()
    {
        $userId = session()->get('id_user');
        $db = \Config\Database::connect();

        // Get enrolled classes via transaksi (pembayaran)
        $sqlTransaksi = "SELECT DISTINCT pp.id_produk, pp.judul, pp.gambar,
                         us.id_user as pemateri_id, us.nama_lengkap as nama_pemateri,
                         u.id_ujian,
                         hu.nilai, hu.status_lulus, hu.tanggal_ujian,
                         sk.nomor_sertifikat, sk.tanggal_terbit
                         FROM produk_pelatihan pp
                         JOIN transaksi t ON t.id_produk = pp.id_produk AND t.status_pembayaran = 'paid'
                         LEFT JOIN users us ON us.id_user = pp.id_admin
                         LEFT JOIN ujian_sertifikasi u ON u.id_produk = pp.id_produk
                         LEFT JOIN hasil_ujian hu ON hu.id_user = ? AND hu.id_ujian = u.id_ujian
                         LEFT JOIN sertifikat_kelas sk ON sk.id_user = ? AND sk.id_produk = pp.id_produk
                         WHERE t.id_user = ?";

        $certTransaksi = $db->query($sqlTransaksi, [$userId, $userId, $userId])->getResultArray();

        // Get enrolled classes via kelas_user (manual/bonus access)
        $sqlKelasUser = "SELECT DISTINCT pp.id_produk, pp.judul, pp.gambar,
                         us.id_user as pemateri_id, us.nama_lengkap as nama_pemateri,
                         u.id_ujian,
                         hu.nilai, hu.status_lulus, hu.tanggal_ujian,
                         sk.nomor_sertifikat, sk.tanggal_terbit
                         FROM produk_pelatihan pp
                         JOIN kelas_user ku ON ku.id_produk = pp.id_produk AND ku.status_akses = 'aktif'
                         LEFT JOIN users us ON us.id_user = pp.id_admin
                         LEFT JOIN ujian_sertifikasi u ON u.id_produk = pp.id_produk
                         LEFT JOIN hasil_ujian hu ON hu.id_user = ? AND hu.id_ujian = u.id_ujian
                         LEFT JOIN sertifikat_kelas sk ON sk.id_user = ? AND sk.id_produk = pp.id_produk
                         WHERE ku.id_user = ?";

        $certKelasUser = $db->query($sqlKelasUser, [$userId, $userId, $userId])->getResultArray();

        // Merge both, avoid duplicates by id_produk
        $allCerts = [];
        $seen = [];

        foreach ($certTransaksi as $cert) {
            $allCerts[] = $cert;
            $seen[$cert['id_produk']] = true;
        }

        foreach ($certKelasUser as $cert) {
            if (!isset($seen[$cert['id_produk']])) {
                $allCerts[] = $cert;
            }
        }

        $data['certificates'] = $allCerts;
        $data['title'] = 'Sertifikat Saya';
        return view('student/sertifikat_list', $data);
    }

    /**
     * Student Profile Page
     */
    public function profile()
    {
        $userModel = new \App\Models\UserModel();
        $user = $userModel->find(session()->get('id_user'));

        // Get transaction history
        $transaksi = $this->transaksiModel->select('transaksi.*, produk_pelatihan.judul as nama_produk')
            ->join('produk_pelatihan', 'produk_pelatihan.id_produk = transaksi.id_produk')
            ->where('transaksi.id_user', session()->get('id_user'))
            ->orderBy('transaksi.tanggal_transaksi', 'DESC')
            ->findAll();

        // Check profile completeness
        $requiredFields = ['nama_lengkap', 'email'];
        $optionalFields = ['no_whatsapp', 'instansi', 'alamat', 'tanggal_lahir', 'jenis_kelamin'];
        $missingRequired = [];
        $missingOptional = [];

        foreach ($requiredFields as $field) {
            if (empty($user[$field])) {
                $missingRequired[] = $field;
            }
        }
        foreach ($optionalFields as $field) {
            if (empty($user[$field])) {
                $missingOptional[] = $field;
            }
        }

        $data = [
            'title' => 'Profil Saya',
            'user' => $user,
            'transaksi' => $transaksi,
            'missingRequired' => $missingRequired,
            'missingOptional' => $missingOptional,
            'profileComplete' => empty($missingRequired)
        ];

        return view('student/profile', $data);
    }

    /**
     * Update student profile
     */
    public function updateProfile()
    {
        $userModel = new \App\Models\UserModel();

        $data = [
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'no_whatsapp' => $this->request->getPost('no_whatsapp'),
            'instansi' => $this->request->getPost('instansi'),
            'alamat' => $this->request->getPost('alamat'),
            'tanggal_lahir' => $this->request->getPost('tanggal_lahir'),
            'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
            'pendidikan_terakhir' => $this->request->getPost('pendidikan_terakhir')
        ];

        // Handle photo upload
        $file = $this->request->getFile('foto_profil');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/profil/', $newName);
            $data['foto_profil'] = 'uploads/profil/' . $newName;
        }

        $userModel->update(session()->get('id_user'), $data);

        return redirect()->to('/student/profile')->with('success', 'Profil berhasil diupdate.');
    }

    /**
     * Change password
     */
    public function changePassword()
    {
        $userModel = new \App\Models\UserModel();
        $user = $userModel->find(session()->get('id_user'));

        $passwordLama = $this->request->getPost('password_lama');
        $passwordBaru = $this->request->getPost('password_baru');
        $passwordKonfirmasi = $this->request->getPost('password_konfirmasi');

        if (!password_verify($passwordLama, $user['password'])) {
            return redirect()->to('/student/profile')->with('error', 'Password lama salah.');
        }

        if ($passwordBaru !== $passwordKonfirmasi) {
            return redirect()->to('/student/profile')->with('error', 'Password baru dan konfirmasi tidak cocok.');
        }

        if (strlen($passwordBaru) < 6) {
            return redirect()->to('/student/profile')->with('error', 'Password minimal 6 karakter.');
        }

        $userModel->update(session()->get('id_user'), [
            'password' => password_hash($passwordBaru, PASSWORD_DEFAULT)
        ]);

        return redirect()->to('/student/profile')->with('success', 'Password berhasil diubah.');
    }

    /**
     * Rate exam after completion
     */
    public function rateExam()
    {
        $userId = session()->get('id_user');
        $idHasil = $this->request->getPost('id_hasil');
        $rating = (int) $this->request->getPost('rating', 0);

        if ($rating < 1 || $rating > 10) {
            return redirect()->back()->with('error', 'Rating harus antara 1-10.');
        }

        $result = $this->hasilModel->find($idHasil);
        if (!$result || $result['id_user'] != $userId) {
            return redirect()->to('/student');
        }

        $this->hasilModel->update($idHasil, ['rating' => $rating]);

        return redirect()->to('/student/exam-result/' . $idHasil)->with('success', 'Terima kasih atas rating Anda!');
    }

    /**
     * Retry exam (remedial)
     */
    public function retryExam($idUjian)
    {
        $userId = session()->get('id_user');

        $ujian = $this->ujianModel->find($idUjian);
        if (!$ujian) {
            return redirect()->to('/student');
        }

        // Check if user has access to this class (dual access system)
        $transaksi = $this->transaksiModel->where('id_user', $userId)
            ->where('id_produk', $ujian['id_produk'])
            ->where('status_pembayaran', 'paid')
            ->first();

        $hasAccessViaKelasUser = $this->kelasUserModel->hasAccess($userId, $ujian['id_produk']);

        if (!$transaksi && !$hasAccessViaKelasUser) {
            return redirect()->to('/student')->with('error', 'Anda belum memiliki akses ke remedial ini.');
        }

        // Re-check eligibility
        $totalProgress = $this->progressModel->getTotalProgress($userId, $ujian['id_produk']);
        if ($totalProgress < ($ujian['minimal_progress_persen'] ?? 100)) {
            return redirect()->to('/student/kelas/' . $ujian['id_produk'])
                ->with('error', 'Anda harus menyelesaikan minimal ' . ($ujian['minimal_progress_persen'] ?? 100) . '% materi untuk bisa mengikuti remedial.');
        }

        // Get questions
        $questions = $this->soalModel->where('id_ujian', $idUjian)->findAll();
        if (empty($questions)) {
            return redirect()->to('/student/kelas/' . $ujian['id_produk'])
                ->with('error', 'Ujian belum memiliki soal. Hubungi admin.');
        }

        $data = [
            'title' => 'Remedial: ' . ($ujian['judul_ujian'] ?? 'Sertifikasi'),
            'ujian' => $ujian,
            'questions' => $questions,
            'durasi_detik' => ($ujian['durasi_menit'] ?? 60) * 60,
            'isRetry' => true
        ];

        return view('student/exam', $data);
    }
}