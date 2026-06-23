<?php

namespace App\Models;

use CodeIgniter\Model;
use Config\Database;

class KelasAdminModel extends Model
{
    protected $table            = 'kelas_admin';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['id_produk', 'id_user', 'role'];

    /**
     * Get all admins for a class
     */
    public function getAdminsByKelas(int $produkId): array
    {
        try {
            $db = Database::connect();
            $sql = "SELECT ka.*, u.nama_lengkap, u.email, u.foto_profil
                    FROM kelas_admin ka
                    LEFT JOIN users u ON u.id_user = ka.id_user
                    WHERE ka.id_produk = ?
                    ORDER BY ka.role ASC";
            return $db->query($sql, [$produkId])->getResultArray();
        } catch (\Exception $e) {
            log_message('error', 'KelasAdminModel error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get all classes for an admin
     */
    public function getKelasByAdmin(int $userId): array
    {
        try {
            $db = Database::connect();
            $sql = "SELECT ka.*, p.judul, p.gambar
                    FROM kelas_admin ka
                    LEFT JOIN produk_pelatihan p ON p.id_produk = ka.id_produk
                    WHERE ka.id_user = ?";
            return $db->query($sql, [$userId])->getResultArray();
        } catch (\Exception $e) {
            log_message('error', 'KelasAdminModel error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Check if user is admin of a class
     */
    public function isAdminOfKelas(int $userId, int $produkId): bool
    {
        try {
            $db = Database::connect();
            $sql = "SELECT id FROM kelas_admin WHERE id_user = ? AND id_produk = ? LIMIT 1";
            $result = $db->query($sql, [$userId, $produkId])->getRow();
            return $result !== null;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Add admin to a class
     */
    public function addAdminToKelas(int $produkId, int $userId, string $role = 'pemateri'): bool
    {
        try {
            if ($this->isAdminOfKelas($userId, $produkId)) {
                return false;
            }

            return $this->insert([
                'id_produk' => $produkId,
                'id_user' => $userId,
                'role' => $role
            ]) !== false;
        } catch (\Exception $e) {
            log_message('error', 'KelasAdminModel addAdminToKelas error: ' . $e->getMessage());
            return false;
        }
    }
}