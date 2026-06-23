<?php

namespace App\Models;

use CodeIgniter\Model;

class ChatKelasModel extends Model
{
    protected $table            = 'chat_kelas';
    protected $primaryKey       = 'id_chat';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['id_user', 'id_produk', 'pesan', 'pengirim', 'waktu_kirim'];

    /**
     * Get chats for a produk
     */
    public function getChatsByProduk(int $id_produk): array
    {
        return $this->select('chat_kelas.*, users.nama_lengkap as nama_pengirim')
                    ->join('users', 'users.id_user = chat_kelas.id_user', 'left')
                    ->where('chat_kelas.id_produk', $id_produk)
                    ->orderBy('waktu_kirim', 'ASC')
                    ->findAll();
    }

    /**
     * Send a chat message
     */
    public function sendMessage(int $id_user, int $id_produk, string $pesan, string $pengirim): bool
    {
        return $this->insert([
            'id_user' => $id_user,
            'id_produk' => $id_produk,
            'pesan' => $pesan,
            'pengirim' => $pengirim,
            'waktu_kirim' => date('Y-m-d H:i:s')
        ]);
    }
}