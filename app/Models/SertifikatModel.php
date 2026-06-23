<?php

namespace App\Models;

use CodeIgniter\Model;

class SertifikatModel extends Model
{
    protected $table            = 'sertifikat_kelas';
    protected $primaryKey       = 'id_sertifikat';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'id_user', 'id_produk', 'nomor_sertifikat', 'tanggal_terbit', 'file_pdf'
    ];

    /**
     * Get certificate by user and product
     *
     * @param int $userId
     * @param int $produkId
     * @return array|null
     */
    public function getCertificate(int $userId, int $produkId): ?array
    {
        return $this->where('id_user', $userId)
                    ->where('id_produk', $produkId)
                    ->first();
    }

    /**
     * Check if certificate exists for user and product
     *
     * @param int $userId
     * @param int $produkId
     * @return bool
     */
    public function exists(int $userId, int $produkId): bool
    {
        return $this->where('id_user', $userId)
                    ->where('id_produk', $produkId)
                    ->countAllResults() > 0;
    }

    /**
     * Get all certificates issued this month
     *
     * @return int
     */
    public function countThisMonth(): int
    {
        $startOfMonth = date('Y-m-01');
        $endOfMonth = date('Y-m-t');

        return $this->where('tanggal_terbit >=', $startOfMonth)
                    ->where('tanggal_terbit <=', $endOfMonth)
                    ->countAllResults();
    }

    /**
     * Get certificates with user and product details
     *
     * @param int|null $limit
     * @return array
     */
    public function getWithDetails(?int $limit = null): array
    {
        $builder = $this->select('sertifikat_kelas.*, users.nama_lengkap, users.email, produk_pelatihan.judul as nama_produk')
                        ->join('users', 'users.id_user = sertifikat_kelas.id_user')
                        ->join('produk_pelatihan', 'produk_pelatihan.id_produk = sertifikat_kelas.id_produk')
                        ->orderBy('sertifikat_kelas.tanggal_terbit', 'DESC');

        if ($limit) {
            $builder->limit($limit);
        }

        return $builder->findAll();
    }

    /**
     * Get latest certificate number this month
     *
     * @return string
     */
    public function getLatestCertNumber(): string
    {
        $year = date('Y');
        $month = str_pad(date('m'), 2, '0', STR_PAD_LEFT);

        $result = $this->select('nomor_sertifikat')
            ->like('nomor_sertifikat', "ZVD/{$year}/{$month}/", 'after')
            ->orderBy('id_sertifikat', 'DESC')
            ->first();

        if ($result) {
            // Extract number from format ZVD/YYYY/MM/XXX
            preg_match('/ZVD\/\d{4}\/\d{2}\/(\d+)/', $result['nomor_sertifikat'], $matches);
            if (isset($matches[1])) {
                return sprintf('ZVD/%s/%s/%03d', $year, $month, ((int)$matches[1]) + 1);
            }
        }

        return sprintf('ZVD/%s/%s/%03d', $year, $month, 1);
    }
}