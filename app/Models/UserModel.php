<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id_user';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nama_lengkap', 'instansi', 'no_whatsapp', 'email', 'password', 
        'login_google', 'google_id', 'role', 'alamat', 'tanggal_lahir', 
        'jenis_kelamin', 'pendidikan_terakhir', 'foto_profil'
    ];

    protected $useTimestamps = false; // Based on provided SQL schema
}
