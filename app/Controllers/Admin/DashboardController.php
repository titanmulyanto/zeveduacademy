<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\TransaksiModel;
use App\Models\SertifikatModel;
use App\Models\KelasUserModel;
use App\Models\MateriKelasModel;
use App\Models\SubMateriVideoModel;

class DashboardController extends BaseController
{
    protected $userModel;
    protected $transaksiModel;
    protected $sertifikatModel;
    protected $kelasUserModel;
    protected $materiKelasModel;
    protected $subMateriVideoModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->transaksiModel = new TransaksiModel();
        $this->sertifikatModel = new SertifikatModel();
        $this->kelasUserModel = new KelasUserModel();
        $this->materiKelasModel = new MateriKelasModel();
        $this->subMateriVideoModel = new SubMateriVideoModel();
    }

    public function index()
    {
        // Redirect admin users to materi page (they shouldn't see super_admin dashboard)
        if (session()->get('role') === 'admin') {
            return redirect()->to('/admin/materi');
        }

        // Only super_admin can see the full dashboard statistics
        $data = [
            'title' => 'Dashboard Utama',
            'stats' => $this->getDashboardStatistics(),
            'recent_transactions' => $this->getRecentTransactions(),
            'recent_students' => $this->getRecentStudents(),
            'storage' => $this->getStorageUsage(FCPATH . 'uploads')
        ];

        return view('admin/dashboard/index', $data);
    }

    /**
     * Get all dashboard statistics from database
     *
     * @return array
     */
    private function getDashboardStatistics(): array
    {
        $db = \Config\Database::connect();

        // 1. Total Student (role = 'student')
        $totalStudent = $this->userModel->where('role', 'student')->countAllResults();

        // 2. Total Admin (role = 'admin' + 'super_admin')
        $totalAdmin = $this->userModel
            ->groupStart()
                ->where('role', 'admin')
                ->orWhere('role', 'super_admin')
            ->groupEnd()
            ->countAllResults();

        // 3. Total Transaksi
        $totalTransaksi = $this->transaksiModel->countAllResults();

        // 4. Total Transaksi Berhasil (status = 'paid')
        $totalTransaksiBerhasil = $this->transaksiModel->where('status_pembayaran', 'paid')->countAllResults();

        // 5. Jumlah Sertifikat Terbit
        $jumlahSertifikat = $this->sertifikatModel->countAllResults();

        // 6. Jumlah Siswa Aktif (status_akses = 'aktif' di kelas_user)
        $jumlahSiswaAktif = $this->kelasUserModel->where('status_akses', 'aktif')->countAllResults();

        // 7. Jumlah Alumni (student yang punya sertifikat)
        $jumlahAlumni = $db->table('sertifikat_kelas')
            ->distinct('id_user')
            ->countAllResults();

        // 8. Total File Diunggah (materi + video files)
        $totalMateriFiles = $this->materiKelasModel
            ->whereNotIn('file_materi', ['', null])
            ->countAllResults();

        $totalVideoFiles = $this->subMateriVideoModel
            ->whereNotIn('file_materi', ['', null])
            ->countAllResults();

        $totalFileUnggah = $totalMateriFiles + $totalVideoFiles;

        // Total Revenue dari transaksi paid
        $totalRevenue = $db->table('transaksi')
            ->selectSum('total_bayar')
            ->where('status_pembayaran', 'paid')
            ->get()
            ->getRow()
            ->total_bayar ?? 0;

        return [
            'total_student' => $totalStudent,
            'total_admin' => $totalAdmin,
            'total_transaksi' => $totalTransaksi,
            'transaksi_berhasil' => $totalTransaksiBerhasil,
            'jumlah_sertifikat' => $jumlahSertifikat,
            'siswa_aktif' => $jumlahSiswaAktif,
            'jumlah_alumni' => $jumlahAlumni,
            'total_file_unggah' => $totalFileUnggah,
            'total_revenue' => $totalRevenue
        ];
    }

    /**
     * Get recent successful transactions
     *
     * @param int $limit
     * @return array
     */
    private function getRecentTransactions(int $limit = 5): array
    {
        return $this->transaksiModel
            ->select('transaksi.*, users.nama_lengkap, produk_pelatihan.judul as nama_produk')
            ->join('users', 'users.id_user = transaksi.id_user')
            ->join('produk_pelatihan', 'produk_pelatihan.id_produk = transaksi.id_produk')
            ->where('transaksi.status_pembayaran', 'paid')
            ->orderBy('transaksi.tanggal_transaksi', 'DESC')
            ->limit($limit)
            ->findAll();
    }

    /**
     * Get recently registered students
     *
     * @param int $limit
     * @return array
     */
    private function getRecentStudents(int $limit = 5): array
    {
        return $this->userModel->where('role', 'student')
            ->orderBy('id_user', 'DESC')
            ->limit($limit)
            ->findAll();
    }

    /**
     * Calculate storage usage
     *
     * @param string $path
     * @return array
     */
    private function getStorageUsage($path): array
    {
        $totalSize = 0;
        if (is_dir($path)) {
            $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path));
            foreach ($files as $file) {
                if ($file->isFile()) {
                    $totalSize += $file->getSize();
                }
            }
        }

        $limit = 5 * 1024 * 1024 * 1024; // 5 GB Limit
        $percentage = ($totalSize / $limit) * 100;

        return [
            'used' => $this->formatSize($totalSize),
            'limit' => $this->formatSize($limit),
            'percent' => round($percentage, 2)
        ];
    }

    /**
     * Format bytes to human readable size
     *
     * @param int $bytes
     * @return string
     */
    private function formatSize($bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * API endpoint for dashboard statistics (for AJAX refresh)
     *
     * @return \CodeIgniter\HTTP\Response
     */
    public function statistics()
    {
        $stats = $this->getDashboardStatistics();
        return $this->response->setJSON([
            'status' => 'success',
            'data' => $stats
        ]);
    }

    /**
     * Get all uploaded files with class information
     * Used for File Diunggah modal/table
     *
     * @return array
     */
    public function getUploadedFiles()
    {
        $db = \Config\Database::connect();

        // Get materi files (documents)
        $materiFiles = $db->table('materi_kelas')
            ->select('materi_kelas.*, produk_pelatihan.judul as nama_kelas, produk_pelatihan.id_produk')
            ->join('produk_pelatihan', 'produk_pelatihan.id_produk = materi_kelas.id_produk', 'left')
            ->where('materi_kelas.file_materi !=', '')
            ->where('materi_kelas.file_materi IS NOT NULL', null, false)
            ->get()
            ->getResultArray();

        // Get video files
        $videoFiles = $db->table('sub_materi_video')
            ->select('sub_materi_video.*, produk_pelatihan.judul as nama_kelas, produk_pelatihan.id_produk, kategori_materi.judul_kategori')
            ->join('kategori_materi', 'kategori_materi.id_kategori_materi = sub_materi_video.id_kategori_materi', 'left')
            ->join('produk_pelatihan', 'produk_pelatihan.id_produk = kategori_materi.id_produk', 'left')
            ->where('sub_materi_video.file_materi !=', '')
            ->where('sub_materi_video.file_materi IS NOT NULL', null, false)
            ->get()
            ->getResultArray();

        // Format materi files
        $result = [];
        foreach ($materiFiles as $file) {
            $result[] = [
                'id' => $file['id_materi'],
                'type' => 'dokumen',
                'judul' => $file['judul_materi'],
                'file' => $file['file_materi'],
                'kelas' => $file['nama_kelas'] ?? '-',
                'id_produk' => $file['id_produk'],
                'created_at' => $file['created_at'] ?? null
            ];
        }

        // Format video files
        foreach ($videoFiles as $file) {
            $result[] = [
                'id' => $file['id_video'],
                'type' => 'video',
                'judul' => $file['judul_video'],
                'file' => $file['file_materi'],
                'kelas' => $file['nama_kelas'] ?? '-',
                'kategori' => $file['judul_kategori'] ?? '-',
                'id_produk' => $file['id_produk'],
                'created_at' => $file['created_at'] ?? null
            ];
        }

        return $result;
    }

    /**
     * API endpoint for uploaded files list (AJAX)
     *
     * @return \CodeIgniter\HTTP\Response
     */
    public function uploadedFiles()
    {
        $files = $this->getUploadedFiles();
        return $this->response->setJSON([
            'status' => 'success',
            'data' => $files,
            'total' => count($files)
        ]);
    }
}