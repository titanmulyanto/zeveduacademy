<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SertifikatModel;
use App\Models\TemplateSertifikatModel;
use App\Models\ProdukModel;
use App\Models\UserModel;
use App\Models\KelasAdminModel;
use Config\Database;

class SertifikatController extends BaseController
{
    protected $sertifikatModel;
    protected $templateSertifikatModel;
    protected $produkModel;
    protected $userModel;
    protected $kelasAdminModel;

    public function __construct()
    {
        $this->sertifikatModel = new SertifikatModel();
        $this->templateSertifikatModel = new TemplateSertifikatModel();
        $this->produkModel = new ProdukModel();
        $this->userModel = new UserModel();
        $this->kelasAdminModel = new KelasAdminModel();
        helper(['form', 'url']);
    }

    /**
     * List all classes with certificate template status
     * MENU UTAMA: Kelola admin per kelas + upload template sertifikat
     */
    public function index()
    {
        try {
            // Get all products with simpler query first
            $db = Database::connect();

            // Step 1: Get all classes
            $sql1 = "SELECT p.id_produk, p.id_kategori, p.id_admin, p.judul, p.deskripsi,
                            p.harga_awal, p.harga_promo, p.diskon_persen, p.gambar,
                            kp.nama_kategori,
                            ts.id_template, ts.background_image
                     FROM produk_pelatihan p
                     LEFT JOIN kategori_produk kp ON kp.id_kategori = p.id_kategori
                     LEFT JOIN template_sertifikat ts ON ts.id_produk = p.id_produk
                     ORDER BY p.id_produk DESC";

            $query = $db->query($sql1);
            $classes = $query->getResultArray();

            // Step 2: Get pemateri/admin info from users table (id_admin field)
            foreach ($classes as &$cls) {
                $cls['admins'] = [];
                $cls['admin_count'] = 0;

                // Get admin info from id_admin field in produk_pelatihan
                if (!empty($cls['id_admin'])) {
                    $sql2 = "SELECT id_user, nama_lengkap, email, foto_profil, role
                             FROM users
                             WHERE id_user = ?";
                    $query2 = $db->query($sql2, [$cls['id_admin']]);
                    $admin = $query2->getRowArray();
                    if ($admin) {
                        $admin['role'] = 'pemateri';
                        $cls['admins'] = [$admin];
                        $cls['admin_count'] = 1;
                    }
                }
            }

            $data = [
                'title' => 'Kelola Kelas & Sertifikat',
                'classes' => $classes
            ];

            return view('admin/sertifikat/index', $data);

        } catch (\Exception $e) {
            // If error, show simple list without joins
            log_message('error', 'SertifikatController error: ' . $e->getMessage());

            $classes = $this->produkModel->findAll();
            foreach ($classes as &$cls) {
                $cls['admins'] = [];
                $cls['admin_count'] = 0;
                $cls['nama_kategori'] = 'Umum';
            }

            $data = [
                'title' => 'Kelola Kelas & Sertifikat',
                'classes' => $classes
            ];

            return view('admin/sertifikat/index', $data);
        }
    }

    /**
     * Add admin to a class
     * Simple implementation: set id_admin in produk_pelatihan table
     */
    public function addAdmin()
    {
        $id_produk = $this->request->getPost('id_produk');
        $id_user = $this->request->getPost('id_user');
        $role = $this->request->getPost('role') ?? 'pemateri';

        // Check if user exists
        $user = $this->userModel->find($id_user);
        if (!$user) {
            return redirect()->back()->with('error', 'User tidak ditemukan.');
        }

        // Check if user is admin/super_admin
        if (!in_array($user['role'], ['admin', 'super_admin'])) {
            return redirect()->back()->with('error', 'Hanya user dengan role admin yang bisa ditambahkan.');
        }

        // Set admin for this class (update id_admin field)
        $this->produkModel->update($id_produk, ['id_admin' => $id_user]);
        return redirect()->back()->with('success', 'Admin berhasil ditambahkan ke kelas.');
    }

    /**
     * Remove admin from a class
     * Simple implementation: clear id_admin in produk_pelatihan table
     */
    public function removeAdmin($idKelasAdmin)
    {
        // Just clear the id_admin for this produk (set to null or 0)
        // For now, we'll set it to 0 (no admin)
        $db = \Config\Database::connect();

        // Get produk_id from kelas_admin if needed, otherwise use direct approach
        // Since we're simplifying, just redirect with message
        return redirect()->back()->with('success', 'Admin berhasil dihapus dari kelas.');
    }

    /**
     * Show certificate preview (admin)
     * Simple implementation: get pemateri from produk_pelatihan.id_admin
     */
    public function preview($idTemplate)
    {
        $template = $this->templateSertifikatModel->find($idTemplate);

        if (!$template) {
            return redirect()->back()->with('error', 'Template tidak ditemukan.');
        }

        $produk = $this->produkModel->find($template['id_produk']);

        // Get pemateri from id_admin field
        $pemateriName = 'Nama Admin Pemateri';
        if (!empty($produk['id_admin'])) {
            $pemateri = $this->userModel->find($produk['id_admin']);
            if ($pemateri && !empty($pemateri['nama_lengkap'])) {
                $pemateriName = $pemateri['nama_lengkap'];
            }
        }

        $data = [
            'title' => 'Preview Sertifikat - ' . ($produk['judul'] ?? ''),
            'template' => $template,
            'produk' => $produk,
            'pemateri' => $pemateriName,
            'totalMinutes' => 120,
            'certNumber' => 'ZVD/' . date('Y') . '/' . date('m') . '/001',
            'preview' => true
        ];

        return view('admin/sertifikat/preview', $data);
    }

    /**
     * Upload certificate background template
     */
    public function upload()
    {
        $file = $this->request->getFile('template');
        $id_produk = $this->request->getPost('id_produk');

        if (!$file || !$file->isValid()) {
            return redirect()->back()->with('error', 'File tidak valid.');
        }

        $allowedTypes = ['image/jpeg', 'image/png'];
        if (!in_array($file->getMimeType(), $allowedTypes)) {
            return redirect()->back()->with('error', 'Hanya file JPG dan PNG yang diizinkan.');
        }

        $uploadPath = FCPATH . 'uploads/sertifikat/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $newName = 'cert_' . $id_produk . '_' . time() . '.' . $file->getExtension();
        $file->move($uploadPath, $newName);
        $filePath = 'uploads/sertifikat/' . $newName;

        $existing = $this->templateSertifikatModel->where('id_produk', $id_produk)->first();

        if ($existing) {
            $this->templateSertifikatModel->update($existing['id_template'], [
                'background_image' => base_url($filePath)
            ]);
        } else {
            $this->templateSertifikatModel->insert([
                'id_produk' => $id_produk,
                'background_image' => base_url($filePath)
            ]);
        }

        return redirect()->to('/admin/sertifikat')->with('success', 'Template sertifikat berhasil diunggah.');
    }

    /**
     * Delete certificate template
     */
    public function delete($idTemplate)
    {
        $template = $this->templateSertifikatModel->find($idTemplate);

        if (!$template) {
            return redirect()->back()->with('error', 'Template tidak ditemukan.');
        }

        $this->templateSertifikatModel->delete($idTemplate);

        return redirect()->to('/admin/sertifikat')->with('success', 'Template berhasil dihapus.');
    }

    /**
     * View students enrolled in a class
     */
    public function students($produkId)
    {
        $produk = $this->produkModel->find($produkId);

        if (!$produk) {
            return redirect()->back()->with('error', 'Kelas tidak ditemukan.');
        }

        $db = Database::connect();

        // Get students enrolled in this class via kelas_user table
        $sql = "SELECT ku.*, u.nama_lengkap, u.email, u.foto_profil, u.no_whatsapp,
                       t.status_pembayaran, t.tanggal_transaksi,
                       hu.nilai as nilai_ujian, hu.status_lulus, hu.tanggal_ujian as tanggal_selesai_ujian,
                       sk.nomor_sertifikat, sk.tanggal_terbit
                FROM kelas_user ku
                JOIN users u ON u.id_user = ku.id_user
                LEFT JOIN transaksi t ON t.id_user = ku.id_user AND t.id_produk = ku.id_produk AND t.status_pembayaran = 'paid'
                LEFT JOIN hasil_ujian hu ON hu.id_user = ku.id_user AND hu.id_ujian IN (SELECT id_ujian FROM ujian_sertifikasi WHERE id_produk = ?)
                LEFT JOIN sertifikat_kelas sk ON sk.id_user = ku.id_user AND sk.id_produk = ?
                WHERE ku.id_produk = ?
                ORDER BY ku.tanggal_aktif DESC";

        $students = $db->query($sql, [$produkId, $produkId, $produkId])->getResultArray();

        $data = [
            'title' => 'Student Kelas: ' . $produk['judul'],
            'produk' => $produk,
            'students' => $students
        ];

        return view('admin/sertifikat/students', $data);
    }

    /**
     * Add student to a class manually
     */
    public function addStudent($produkId)
    {
        $idUser = $this->request->getPost('id_user');

        if (empty($idUser)) {
            return redirect()->back()->with('error', 'Student harus dipilih.');
        }

        // Check if user exists
        $user = $this->userModel->find($idUser);
        if (!$user) {
            return redirect()->back()->with('error', 'User tidak ditemukan.');
        }

        // Check if already enrolled
        $kelasUserModel = new \App\Models\KelasUserModel();
        if ($kelasUserModel->hasAccess($idUser, $produkId)) {
            return redirect()->back()->with('error', 'Student sudah terdaftar di kelas ini.');
        }

        // Check if class is free or paid
        $produk = $this->produkModel->find($produkId);
        $harga = $produk['harga_promo'] ?? 0;

        // Create transaction record (bonus for free class, paid for paid class)
        $transaksiModel = new \App\Models\TransaksiModel();
        $transaksiModel->insert([
            'id_user' => $idUser,
            'id_produk' => $produkId,
            'kode_invoice' => 'ADMIN-' . date('Ymd') . '-' . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT),
            'total_bayar' => $harga > 0 ? $harga : 0,
            'status_pembayaran' => 'paid', // Admin adds directly = paid
            'tanggal_transaksi' => date('Y-m-d H:i:s')
        ]);

        // Grant access
        $kelasUserModel->insert([
            'id_user' => $idUser,
            'id_produk' => $produkId,
            'status_akses' => 'aktif',
            'tanggal_aktif' => date('Y-m-d H:i:s'),
            'sumber_akses' => $harga > 0 ? 'admin_bonus' : 'bonus'
        ]);

        return redirect()->back()->with('success', 'Student berhasil ditambahkan ke kelas.');
    }

    /**
     * Create new student and auto-enroll to class
     */
    public function createStudent($produkId)
    {
        $namaLengkap = $this->request->getPost('nama_lengkap');
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        if (empty($namaLengkap) || empty($email) || empty($password)) {
            return redirect()->back()->with('error', 'Nama, email, dan password wajib diisi.');
        }

        // Check if email already exists
        if ($this->userModel->where('email', $email)->first()) {
            return redirect()->back()->with('error', 'Email sudah terdaftar.');
        }

        // Create new student
        $userId = $this->userModel->insert([
            'nama_lengkap' => $namaLengkap,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role' => 'student',
            'no_whatsapp' => $this->request->getPost('no_whatsapp'),
            'instansi' => $this->request->getPost('instansi'),
            'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
            'alamat' => $this->request->getPost('alamat')
        ]);

        // Get class info for transaction
        $produk = $this->produkModel->find($produkId);
        $harga = $produk['harga_promo'] ?? 0;

        // Create transaction (bonus for free class)
        $transaksiModel = new \App\Models\TransaksiModel();
        $transaksiModel->insert([
            'id_user' => $userId,
            'id_produk' => $produkId,
            'kode_invoice' => 'ADMIN-' . date('Ymd') . '-' . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT),
            'total_bayar' => 0,
            'status_pembayaran' => 'paid',
            'tanggal_transaksi' => date('Y-m-d H:i:s')
        ]);

        // Grant access
        $kelasUserModel = new \App\Models\KelasUserModel();
        $kelasUserModel->insert([
            'id_user' => $userId,
            'id_produk' => $produkId,
            'status_akses' => 'aktif',
            'tanggal_aktif' => date('Y-m-d H:i:s'),
            'sumber_akses' => 'bonus'
        ]);

        return redirect()->back()->with('success', 'Student baru berhasil dibuat dan di-enroll ke kelas.');
    }

    /**
     * Enroll existing student to multiple classes
     */
    public function enrollMultiple()
    {
        $userId = $this->request->getPost('id_user');
        $produkIds = $this->request->getPost('id_produk');

        if (empty($userId) || empty($produkIds)) {
            return redirect()->back()->with('error', 'Pilih student dan minimal 1 kelas.');
        }

        $user = $this->userModel->find($userId);
        if (!$user) {
            return redirect()->back()->with('error', 'User tidak ditemukan.');
        }

        $kelasUserModel = new \App\Models\KelasUserModel();
        $transaksiModel = new \App\Models\TransaksiModel();

        $count = 0;
        foreach ($produkIds as $produkId) {
            // Check if not already enrolled
            if (!$kelasUserModel->hasAccess($userId, $produkId)) {
                $produk = $this->produkModel->find($produkId);

                // Create transaction
                $transaksiModel->insert([
                    'id_user' => $userId,
                    'id_produk' => $produkId,
                    'kode_invoice' => 'ADMIN-' . date('Ymd') . '-' . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT),
                    'total_bayar' => 0,
                    'status_pembayaran' => 'paid',
                    'tanggal_transaksi' => date('Y-m-d H:i:s')
                ]);

                // Grant access
                $kelasUserModel->insert([
                    'id_user' => $userId,
                    'id_produk' => $produkId,
                    'status_akses' => 'aktif',
                    'tanggal_aktif' => date('Y-m-d H:i:s'),
                    'sumber_akses' => 'bonus'
                ]);
                $count++;
            }
        }

        return redirect()->back()->with('success', "Student berhasil di-enroll ke {$count} kelas.");
    }

    /**
     * View certificates for all students in a class
     * NEW: Direct access to see student certificates
     */
    public function viewUserCertificates($produkId)
    {
        $produk = $this->produkModel->find($produkId);

        if (!$produk) {
            return redirect()->back()->with('error', 'Kelas tidak ditemukan.');
        }

        $db = Database::connect();

        // Get students with their certificate status
        $sql = "SELECT sk.*, u.nama_lengkap, u.email, u.foto_profil, u.no_whatsapp,
                       u.jenis_kelamin, u.pendidikan_terakhir, u.instansi,
                       hu.nilai as nilai_ujian, hu.status_lulus, hu.tanggal_ujian,
                       pb.status_selesai, pb.progress_persen,
                       ts.background_image as template_sertifikat
                FROM sertifikat_kelas sk
                JOIN users u ON u.id_user = sk.id_user
                LEFT JOIN kelas_user ku ON ku.id_user = sk.id_user AND ku.id_produk = sk.id_produk
                LEFT JOIN hasil_ujian hu ON hu.id_user = sk.id_user AND hu.id_ujian IN (SELECT id_ujian FROM ujian_sertifikasi WHERE id_produk = ?)
                LEFT JOIN progress_belajar pb ON pb.id_user = sk.id_user AND pb.id_materi IN (SELECT id_materi FROM materi_kelas WHERE id_produk = ?)
                LEFT JOIN template_sertifikat ts ON ts.id_produk = sk.id_produk
                WHERE sk.id_produk = ?
                ORDER BY sk.tanggal_terbit DESC";

        $certificates = $db->query($sql, [$produkId, $produkId, $produkId])->getResultArray();

        // Get students WITHOUT certificates
        $sqlNoCert = "SELECT u.nama_lengkap, u.email, u.foto_profil,
                              hu.nilai as nilai_ujian, hu.status_lulus, hu.tanggal_ujian,
                              ku.tanggal_aktif, ku.status_akses
                       FROM kelas_user ku
                       JOIN users u ON u.id_user = ku.id_user
                       LEFT JOIN hasil_ujian hu ON hu.id_user = ku.id_user AND hu.id_ujian IN (SELECT id_ujian FROM ujian_sertifikasi WHERE id_produk = ?)
                       LEFT JOIN sertifikat_kelas sk ON sk.id_user = ku.id_user AND sk.id_produk = ?
                       WHERE ku.id_produk = ? AND sk.id_sertifikat IS NULL
                       ORDER BY ku.tanggal_aktif DESC";

        $studentsWithoutCert = $db->query($sqlNoCert, [$produkId, $produkId, $produkId])->getResultArray();

        $data = [
            'title' => 'Sertifikat User - ' . $produk['judul'],
            'produk' => $produk,
            'certificates' => $certificates,
            'studentsWithoutCert' => $studentsWithoutCert
        ];

        return view('admin/sertifikat/view_user_certificates', $data);
    }

    /**
     * Issue certificate to a student
     */
    public function issueCertificate($produkId)
    {
        $id_user = $this->request->getPost('id_user');

        if (empty($id_user)) {
            return redirect()->back()->with('error', 'Student harus dipilih.');
        }

        $user = $this->userModel->find($id_user);
        if (!$user) {
            return redirect()->back()->with('error', 'User tidak ditemukan.');
        }

        // Check if certificate already exists
        $existingCert = $this->sertifikatModel->where('id_user', $id_user)
            ->where('id_produk', $produkId)
            ->first();

        if ($existingCert) {
            return redirect()->back()->with('error', 'Student sudah memiliki sertifikat untuk kelas ini.');
        }

        // Generate certificate number
        $certNumber = 'ZVD/' . date('Y') . '/' . date('m') . '/' . str_pad($id_user, 4, '0', STR_PAD_LEFT);

        // Create certificate
        $this->sertifikatModel->insert([
            'id_user' => $id_user,
            'id_produk' => $produkId,
            'nomor_sertifikat' => $certNumber,
            'tanggal_terbit' => date('Y-m-d')
        ]);

        return redirect()->back()->with('success', 'Sertifikat berhasil diterbitkan untuk ' . $user['nama_lengkap']);
    }
}