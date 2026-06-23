<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SertifikatModel;
use App\Models\TemplateSertifikatModel;
use App\Models\TransaksiModel;
use App\Models\UserModel;
use App\Models\ProdukModel;
use App\Models\KelasUserModel;
use App\Models\HasilUjianModel;
use App\Models\UjianModel;
use App\Models\KelasAdminModel;

/**
 * SertifikatController
 * Handle certificate generation with Locked/Unlocked logic
 *
 * CRITICAL BUSINESS LOGIC:
 * - Sertifikat LOCKED: transaksi.status_pembayaran != 'paid' atau tidak ada transaksi
 *   → Tampilkan: sertifikat_locked_general.jpg (1 file global di assets/)
 * - Sertifikat UNLOCKED: transaksi.status_pembayaran = 'paid'
 *   → Generate PDF dengan background dari template_sertifikat per kelas
 */
class SertifikatController extends BaseController
{
    protected $sertifikatModel;
    protected $templateSertifikatModel;
    protected $transaksiModel;
    protected $userModel;
    protected $produkModel;
    protected $kelasUserModel;
    protected $hasilUjianModel;
    protected $ujianModel;
    protected $kelasAdminModel;

    // Path ke file locked certificate
    private const LOCKED_CERT_PATH = 'assets/images/sertifikat_locked_general.jpg';

    public function __construct()
    {
        $this->sertifikatModel = new SertifikatModel();
        $this->templateSertifikatModel = new TemplateSertifikatModel();
        $this->transaksiModel = new TransaksiModel();
        $this->userModel = new UserModel();
        $this->produkModel = new ProdukModel();
        $this->kelasUserModel = new KelasUserModel();
        $this->hasilUjianModel = new HasilUjianModel();
        $this->ujianModel = new UjianModel();
        $this->kelasAdminModel = new KelasAdminModel();
    }

    /**
     * Show certificate for a user and product
     *
     * CRITICAL BUSINESS LOGIC:
     * 1. Cek tabel transaksi.status_pembayaran
     * 2. Jika 'paid' → Sertifikat UNLOCKED (background dari template_sertifikat per kelas)
     * 3. Jika NULL/belum paid → Sertifikat LOCKED (pakai sertifikat_locked_general.jpg)
     *
     * @param int $userId
     * @param int $produkId
     */
    public function show($userId, $produkId)
    {
        // Get user data
        $user = $this->userModel->find($userId);
        if (!$user) {
            return redirect()->back()->with('error', 'User tidak ditemukan.');
        }

        // Get product/class data
        $produk = $this->produkModel->find($produkId);
        if (!$produk) {
            return redirect()->back()->with('error', 'Produk tidak ditemukan.');
        }

        // Get pemateri info
        $pemateri = $this->getPemateri($produkId);

        // CRITICAL LOGIC: Check transaction status
        // ========================================
        // If status 'paid' → UNLOCKED (background from template_sertifikat table)
        // If status NULL/belum paid → LOCKED (sertifikat_locked_general.jpg)
        // ========================================

        $transaksi = $this->transaksiModel
            ->where('id_user', $userId)
            ->where('id_produk', $produkId)
            ->where('status_pembayaran', 'paid')
            ->first();

        // Check if user passed the exam
        $ujian = $this->ujianModel->where('id_produk', $produkId)->first();
        $hasPassed = false;
        $nilai = 0;

        if ($ujian) {
            $hasilUjian = $this->hasilUjianModel
                ->where('id_user', $userId)
                ->where('id_ujian', $ujian['id_ujian'])
                ->first();
            $hasPassed = ($hasilUjian && $hasilUjian['status_lulus'] === 'lulus');
            $nilai = $hasilUjian['nilai'] ?? 0;
        }

        // ========================================
        // LOGIKA SERTIFIKAT LOCKED/UNLOCKED
        // ========================================
        // FREE CLASS (harga = 0):
        //   Student ikut kelas gratis → ikut ujian → dapat sertifikat LOCKED
        //   Jika bayar → unlock sertifikat
        //
        // PAID CLASS (harga > 0):
        //   Student harus bayar dulu untuk masuk kelas
        //   Jika sudah bayar (transaksi.status='paid') → Sertifikat UNLOCKED
        // ========================================

        $isUnlocked = ($transaksi !== null);
        $isLocked = !$isUnlocked;

        // Get certificate template if unlocked (from template_sertifikat per class)
        $template = null;
        $backgroundPath = null;

        if ($isUnlocked) {
            // Sertifikat UNLOCKED - Ambil background dari template_sertifikat per kelas
            $template = $this->templateSertifikatModel->getByProduk($produkId);
            $backgroundPath = $template['background_image'] ?? null;
        } else {
            // Sertifikat LOCKED - Pakai file global (1 file untuk semua kelas)
            $backgroundPath = base_url('assets/images/sertifikat_locked_general.jpg');
        }

        // Generate certificate number: ZVD/YYYY/MM/XXX
        $certNumber = $this->sertifikatModel->getLatestCertNumber();

        // Calculate total training minutes
        $totalMinutes = $this->getTotalMinutes($produkId);

        // Get issued certificate if exists
        $issuedCert = $this->sertifikatModel->getCertificate($userId, $produkId);

        // Determine class type
        $isFreeClass = ($produk['harga_promo'] == 0 || $produk['harga_awal'] == 0);

        $data = [
            'title' => 'Sertifikat - ' . $produk['judul'],
            'user' => $user,
            'produk' => $produk,
            'isLocked' => $isLocked,
            'isUnlocked' => $isUnlocked,
            'hasPassed' => $hasPassed,
            'nilai' => $nilai,
            'template' => $template,
            'backgroundPath' => $backgroundPath,
            'certNumber' => $certNumber,
            'totalMinutes' => $totalMinutes,
            'pemateri' => $pemateri,
            'allAdmins' => $this->getAllPemateri($produkId),
            'issuedCert' => $issuedCert,
            'transaksi' => $transaksi,
            'isFreeClass' => $isFreeClass,
            'ujian' => $ujian
        ];

        return view('sertifikat/view', $data);
    }

    /**
     * Preview certificate (admin)
     *
     * @param int $produkId
     */
    public function preview($produkId)
    {
        // Mock data for admin preview
        $data = [
            'title' => 'Preview Sertifikat',
            'name' => 'Nama Student',
            'course' => 'Judul Kelas',
            'date' => date('d F Y'),
            'certNumber' => 'ZVD/' . date('Y') . '/' . date('m') . '/001',
            'totalMinutes' => 120,
            'pemateri' => 'Nama Admin Pemateri',
            'isLocked' => false,
            'template' => null
        ];

        return view('sertifikat/preview', $data);
    }

    /**
     * Download certificate as PDF
     *
     * @param int $userId
     * @param int $produkId
     */
    public function download($userId, $produkId)
    {
        // Check if certificate is unlocked
        $transaksi = $this->transaksiModel
            ->where('id_user', $userId)
            ->where('id_produk', $produkId)
            ->where('status_pembayaran', 'paid')
            ->first();

        if (!$transaksi) {
            return redirect()->back()->with('error', 'Sertifikat masih terkunci. Selesaikan pembayaran untuk membuka.');
        }

        // Check if passed exam
        $ujian = $this->ujianModel->where('id_produk', $produkId)->first();
        if ($ujian) {
            $hasilUjian = $this->hasilUjianModel
                ->where('id_user', $userId)
                ->where('id_ujian', $ujian['id_ujian'])
                ->where('status_lulus', 'lulus')
                ->first();

            if (!$hasilUjian) {
                return redirect()->back()->with('error', 'Anda belum lulus ujian sertifikasi.');
            }
        }

        $user = $this->userModel->find($userId);
        $produk = $this->produkModel->find($produkId);
        $template = $this->sertifikatModel->where('id_produk', $produkId)->first();

        // Generate PDF (placeholder - needs TCPDF/DOMPDF library)
        // For now, return view that can be printed as PDF
        $data = [
            'title' => 'Download Sertifikat',
            'user' => $user,
            'produk' => $produk,
            'certNumber' => $this->generateCertNumber($userId, $produkId),
            'totalMinutes' => $this->getTotalMinutes($produkId),
            'pemateri' => $this->getPemateri($produkId),
            'template' => $template,
            'trainingDate' => date('d F Y'),
            'downloadMode' => true
        ];

        // Return as PDF-ready view
        return view('sertifikat/download', $data);
    }

    /**
     * Generate certificate number
     * Format: ZVD/YYYY/MM/XXX
     *
     * @return string
     */
    private function generateCertNumber(): string
    {
        $year = date('Y');
        $month = str_pad(date('m'), 2, '0', STR_PAD_LEFT);

        // Count existing certificates this month
        $count = $this->sertifikatModel->countAllResults();
        $nextNumber = $count + 1;

        return sprintf('ZVD/%s/%s/%03d', $year, $month, $nextNumber);
    }

    /**
     * Get total training minutes from materials
     *
     * @param int $produkId
     * @return int
     */
    private function getTotalMinutes(int $produkId): int
    {
        try {
            // Get from sub_materi_video
            $db = \Config\Database::connect();
            $builder = $db->table('sub_materi_video');
            $builder->selectSum('durasi_menit', 'total');
            $builder->join('kategori_materi', 'kategori_materi.id_kategori_materi = sub_materi_video.id_kategori_materi');
            $builder->where('kategori_materi.id_produk', $produkId);

            $result = $builder->get()->getRow();

            if ($result && isset($result->total) && $result->total !== null) {
                return (int) $result->total;
            }
        } catch (\Exception $e) {
            log_message('error', 'Error getting total minutes: ' . $e->getMessage());
        }

        return 120; // Default 120 minutes
    }

    /**
     * Get pemateri (instructor) for a class
     * Mengambil admin dengan role 'pemateri' dari tabel kelas_admin
     *
     * @param int $produkId
     * @return string|null
     */
    private function getPemateri(int $produkId): ?string
    {
        // Ambil admin dengan role 'pemateri' dari tabel kelas_admin
        $pemateri = $this->kelasAdminModel->where('id_produk', $produkId)
                                           ->where('role', 'pemateri')
                                           ->first();

        if ($pemateri) {
            $user = $this->userModel->find($pemateri['id_user']);
            return $user ? $user['nama_lengkap'] : null;
        }

        // Fallback: cek id_admin lama di produk_pelatihan
        $produk = $this->produkModel->find($produkId);
        if ($produk && !empty($produk['id_admin'])) {
            $user = $this->userModel->find($produk['id_admin']);
            return $user ? $user['nama_lengkap'] : null;
        }

        return null;
    }

    /**
     * Get all pemateris for a class (from kelas_admin table)
     * Returns array of all admin names with role 'pemateri'
     *
     * @param int $produkId
     * @return array
     */
    private function getAllPemateri(int $produkId): array
    {
        $pemateris = [];

        // Get all admins with pemateri role
        $admins = $this->kelasAdminModel->where('id_produk', $produkId)
                                          ->where('role', 'pemateri')
                                          ->findAll();

        foreach ($admins as $admin) {
            $user = $this->userModel->find($admin['id_user']);
            if ($user) {
                $pemateris[] = $user['nama_lengkap'];
            }
        }

        return $pemateris;
    }

    /**
     * Issue certificate to a user (auto called after passing exam)
     *
     * @param int $userId
     * @param int $produkId
     * @return int|false
     */
    public function issueCertificate(int $userId, int $produkId)
    {
        // Check if already issued
        $existing = $this->sertifikatModel
            ->where('id_user', $userId)
            ->where('id_produk', $produkId)
            ->first();

        if ($existing) {
            return $existing['id_sertifikat'];
        }

        // Generate certificate number
        $certNumber = $this->generateCertNumber($userId, $produkId);

        // Issue new certificate
        $data = [
            'id_user' => $userId,
            'id_produk' => $produkId,
            'nomor_sertifikat' => $certNumber,
            'tanggal_terbit' => date('Y-m-d')
        ];

        return $this->sertifikatModel->insert($data);
    }

    /**
     * Check certificate status (API)
     *
     * @param int $userId
     * @param int $produkId
     * @return \CodeIgniter\HTTP\Response
     */
    public function checkStatus($userId, $produkId)
    {
        $transaksi = $this->transaksiModel
            ->where('id_user', $userId)
            ->where('id_produk', $produkId)
            ->where('status_pembayaran', 'paid')
            ->first();

        $ujian = $this->ujianModel->where('id_produk', $produkId)->first();
        $hasPassed = false;

        if ($ujian) {
            $hasilUjian = $this->hasilUjianModel
                ->where('id_user', $userId)
                ->where('id_ujian', $ujian['id_ujian'])
                ->where('status_lulus', 'lulus')
                ->first();
            $hasPassed = $hasilUjian !== null;
        }

        $isUnlocked = $transaksi !== null && $hasPassed;

        return $this->response->setJSON([
            'status' => 'success',
            'data' => [
                'is_unlocked' => $isUnlocked,
                'payment_status' => $transaksi ? 'paid' : 'unpaid',
                'exam_passed' => $hasPassed
            ]
        ]);
    }
}