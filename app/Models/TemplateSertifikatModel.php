<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Model for template_sertifikat table
 * Stores background images for certificates per class
 */
class TemplateSertifikatModel extends Model
{
    protected $table            = 'template_sertifikat';
    protected $primaryKey       = 'id_template';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['id_produk', 'background_image'];

    /**
     * Get template by product ID
     *
     * @param int $produkId
     * @return array|null
     */
    public function getByProduk(int $produkId): ?array
    {
        return $this->where('id_produk', $produkId)->first();
    }

    /**
     * Get all templates with product details
     *
     * @return array
     */
    public function getAllWithProduk(): array
    {
        return $this->select('template_sertifikat.*, produk_pelatihan.judul as nama_produk')
                ->join('produk_pelatihan', 'produk_pelatihan.id_produk = template_sertifikat.id_produk')
                ->findAll();
    }

    /**
     * Check if template exists for product
     *
     * @param int $produkId
     * @return bool
     */
    public function exists(int $produkId): bool
    {
        return $this->where('id_produk', $produkId)->countAllResults() > 0;
    }
}