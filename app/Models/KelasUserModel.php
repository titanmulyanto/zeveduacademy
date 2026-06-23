<?php

namespace App\Models;

use CodeIgniter\Model;

class KelasUserModel extends Model
{
    protected $table            = 'kelas_user';
    protected $primaryKey       = 'id_kelas_user';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'id_user', 'id_produk', 'status_akses', 'tanggal_aktif', 'sumber_akses'
    ];

    /**
     * Get active classes for a user
     *
     * @param int $userId
     * @return array
     */
    public function getActiveClasses(int $userId): array
    {
        return $this->select('kelas_user.*, produk_pelatihan.judul as nama_produk, produk_pelatihan.gambar')
                    ->join('produk_pelatihan', 'produk_pelatihan.id_produk = kelas_user.id_produk')
                    ->where('kelas_user.id_user', $userId)
                    ->where('kelas_user.status_akses', 'aktif')
                    ->findAll();
    }

    /**
     * Check if user has access to a class
     *
     * @param int $userId
     * @param int $produkId
     * @return bool
     */
    public function hasAccess(int $userId, int $produkId): bool
    {
        $result = $this->where('id_user', $userId)
                       ->where('id_produk', $produkId)
                       ->where('status_akses', 'aktif')
                       ->first();

        return $result !== null;
    }

    /**
     * Get students count for a product/class
     *
     * @param int $produkId
     * @return int
     */
    public function getStudentsCount(int $produkId): int
    {
        return $this->where('id_produk', $produkId)
                     ->where('status_akses', 'aktif')
                     ->countAllResults();
    }

    /**
     * Get all students for admin view
     *
     * @param int|null $produkId Filter by product
     * @param string|null $status Filter by status
     * @return array
     */
    public function getAllStudents(?int $produkId = null, ?string $status = null): array
    {
        $builder = $this->select('kelas_user.*, users.nama_lengkap, users.email, users.no_whatsapp, produk_pelatihan.judul as nama_produk')
                         ->join('users', 'users.id_user = kelas_user.id_user')
                         ->join('produk_pelatihan', 'produk_pelatihan.id_produk = kelas_user.id_produk');

        if ($produkId !== null) {
            $builder->where('kelas_user.id_produk', $produkId);
        }

        if ($status !== null) {
            $builder->where('kelas_user.status_akses', $status);
        }

        return $builder->orderBy('kelas_user.tanggal_aktif', 'DESC')->findAll();
    }
}