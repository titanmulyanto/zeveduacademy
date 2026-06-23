<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\ProdukModel;

class UserController extends BaseController
{
    protected $userModel;
    protected $produkModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->produkModel = new ProdukModel();
        helper(['form', 'url']);
    }

    /**
     * List all users with filtering
     */
    public function index()
    {
        $roleFilter = $this->request->getGet('role');
        $search = $this->request->getGet('search');

        $query = $this->userModel;

        if ($roleFilter) {
            $query = $query->where('role', $roleFilter);
        }

        if ($search) {
            $query = $query->groupStart()
                ->like('nama_lengkap', $search)
                ->orLike('email', $search)
                ->groupEnd();
        }

        // Get all produk for dropdown in modal
        $kelas_list = $this->produkModel->orderBy('judul', 'ASC')->findAll();

        $data = [
            'title' => 'Manajemen User',
            'users' => $query->orderBy('id_user', 'DESC')->paginate(20),
            'pager' => $this->userModel->pager,
            'roleFilter' => $roleFilter,
            'search' => $search,
            'roles' => ['student', 'admin', 'super_admin'],
            'kelas_list' => $kelas_list
        ];

        return view('admin/users/index', $data);
    }

    /**
     * Create new user (store)
     */
    public function store()
    {
        $role = $this->request->getPost('role') ?? 'student';
        $id_produk = $this->request->getPost('id_produk');

        $data = [
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'email'        => $this->request->getPost('email'),
            'password'     => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'         => $role,
            'instansi'     => $this->request->getPost('instansi'),
            'no_whatsapp'  => $this->request->getPost('no_whatsapp'),
            'alamat'       => $this->request->getPost('alamat'),
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

        // Check if email already exists
        if ($this->userModel->where('email', $data['email'])->first()) {
            return redirect()->back()->withInput()->with('error', 'Email sudah terdaftar.');
        }

        $this->userModel->insert($data);
        $userId = $this->userModel->getInsertID();

        // If student and produk selected, give access directly (bonus/beasiswa)
        if ($role === 'student' && !empty($id_produk)) {
            $kelasUserModel = new \App\Models\KelasUserModel();
            $transaksiModel = new \App\Models\TransaksiModel();

            // Check if user already has access
            if (!$kelasUserModel->hasAccess($userId, $id_produk)) {
                // Create transaction record (marked as paid for bonus)
                $transaksiModel->insert([
                    'id_user' => $userId,
                    'id_produk' => $id_produk,
                    'kode_invoice' => 'BONUS-' . date('Ymd') . '-' . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT),
                    'total_bayar' => 0,
                    'status_pembayaran' => 'paid',
                    'tanggal_transaksi' => date('Y-m-d H:i:s')
                ]);

                // Grant access
                $kelasUserModel->insert([
                    'id_user' => $userId,
                    'id_produk' => $id_produk,
                    'status_akses' => 'aktif',
                    'tanggal_aktif' => date('Y-m-d H:i:s'),
                    'sumber_akses' => 'bonus'
                ]);
            }
        }

        return redirect()->to('/admin/users')->with('success', 'User berhasil ditambahkan.');
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $user = $this->userModel->find($id);

        if (!$user) {
            return redirect()->to('/admin/users')->with('error', 'User tidak ditemukan.');
        }

        $data = [
            'title' => 'Edit User',
            'user' => $user,
            'roles' => ['student', 'admin', 'super_admin']
        ];

        return view('admin/users/edit', $data);
    }

    /**
     * Update user
     */
    public function update($id)
    {
        $user = $this->userModel->find($id);

        if (!$user) {
            return redirect()->to('/admin/users')->with('error', 'User tidak ditemukan.');
        }

        $data = [
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'role'         => $this->request->getPost('role') ?? 'student',
            'instansi'     => $this->request->getPost('instansi'),
            'no_whatsapp'  => $this->request->getPost('no_whatsapp'),
            'alamat'       => $this->request->getPost('alamat'),
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

        // Update password only if provided
        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $this->userModel->update($id, $data);

        return redirect()->to('/admin/users')->with('success', 'User berhasil diupdate.');
    }

    /**
     * Delete user
     */
    public function delete($id)
    {
        // Prevent deleting own account
        if ($id == session()->get('id_user')) {
            return redirect()->to('/admin/users')->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        $user = $this->userModel->find($id);

        if (!$user) {
            return redirect()->to('/admin/users')->with('error', 'User tidak ditemukan.');
        }

        // Prevent deleting super_admin
        if ($user['role'] === 'super_admin') {
            return redirect()->to('/admin/users')->with('error', 'Tidak dapat menghapus Super Admin.');
        }

        $this->userModel->delete($id);

        return redirect()->to('/admin/users')->with('success', 'User berhasil dihapus.');
    }

    /**
     * View user details
     */
    public function view($id)
    {
        $user = $this->userModel->find($id);

        if (!$user) {
            return redirect()->to('/admin/users')->with('error', 'User tidak ditemukan.');
        }

        $data = [
            'title' => 'Detail User',
            'user' => $user
        ];

        return view('admin/users/view', $data);
    }

    /**
     * List all transactions
     */
    public function transactions()
    {
        $statusFilter = $this->request->getGet('status');

        $transaksiModel = new \App\Models\TransaksiModel();
        $query = $transaksiModel->getDetailedTransactions();

        if ($statusFilter) {
            $query = array_filter($query, function($trx) use ($statusFilter) {
                return $trx['status_pembayaran'] === $statusFilter;
            });
        }

        $data = [
            'title' => 'Daftar Transaksi',
            'transactions' => array_values($query),
            'statusFilter' => $statusFilter,
            'statuses' => ['pending', 'paid', 'failed', 'expired']
        ];

        return view('admin/users/transactions', $data);
    }

    /**
     * Add bonus/beasiswa student (manual without payment)
     */
    public function addBonusStudent()
    {
        // This endpoint is for super_admin to add students manually (bonus/beasiswa)
        // They get immediate access without payment

        $kelasUserModel = new \App\Models\KelasUserModel();
        $transaksiModel = new \App\Models\TransaksiModel();

        $userId = $this->request->getPost('id_user');
        $produkId = $this->request->getPost('id_produk');

        // Check if user exists
        $user = $this->userModel->find($userId);
        if (!$user) {
            return redirect()->back()->with('error', 'User tidak ditemukan.');
        }

        // Check if already has access
        if ($kelasUserModel->hasAccess($userId, $produkId)) {
            return redirect()->back()->with('error', 'User sudah memiliki akses ke kelas ini.');
        }

        // Create transaction record (marked as paid for bonus)
        $transaksiModel->insert([
            'id_user' => $userId,
            'id_produk' => $produkId,
            'kode_invoice' => 'BONUS-' . date('Ymd') . '-' . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT),
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

        return redirect()->back()->with('success', 'Siswa bonus berhasil ditambahkan.');
    }
}