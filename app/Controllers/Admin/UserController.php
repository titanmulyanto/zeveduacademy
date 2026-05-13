<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\TransaksiModel;

class UserController extends BaseController
{
    protected $userModel;
    protected $transaksiModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->transaksiModel = new TransaksiModel();
    }

    public function index()
    {
        $roleFilter = $this->request->getGet('role');
        
        $query = $this->userModel;
        if ($roleFilter) {
            $query = $query->where('role', $roleFilter);
        }

        $data = [
            'title' => 'Manajemen User',
            'users' => $query->findAll(),
            'roleFilter' => $roleFilter
        ];

        return view('admin/users/index', $data);
    }

    public function transactions()
    {
        $data = [
            'title' => 'Daftar Transaksi',
            'transactions' => $this->transaksiModel->getDetailedTransactions()
        ];

        return view('admin/users/transactions', $data);
    }

    // Add methods for CRUD (store, update, delete) here...
}
