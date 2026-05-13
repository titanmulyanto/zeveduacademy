<?php

namespace App\Models;

use CodeIgniter\Model;

class TransaksiModel extends Model
{
    protected $table            = 'transaksi';
    protected $primaryKey       = 'id_transaksi';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'id_user', 'id_produk', 'kode_invoice', 'total_bayar', 
        'status_pembayaran', 'midtrans_order_id', 'snap_token', 'tanggal_transaksi'
    ];

    protected $useTimestamps = false;

    public function getDetailedTransactions()
    {
        return $this->select('transaksi.*, users.nama_lengkap, produk_pelatihan.judul as nama_produk')
                    ->join('users', 'users.id_user = transaksi.id_user')
                    ->join('produk_pelatihan', 'produk_pelatihan.id_produk = transaksi.id_produk')
                    ->orderBy('tanggal_transaksi', 'DESC')
                    ->findAll();
    }
}
