<?php

namespace App\Controllers;

use App\Models\UserModel;

class AuthController extends BaseController
{
    public function index()
    {
        if (session()->get('isLoggedIn')) {
            // Redirect based on role
            if (session()->get('role') === 'super_admin') {
                return redirect()->to('/admin/dashboard');
            } elseif (session()->get('role') === 'admin') {
                return redirect()->to('/admin/materi');
            } else {
                // Student → ke halaman student dashboard
                return redirect()->to('/student');
            }
        }
        return view('auth/login');
    }

    public function register()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/student');
        }
        return view('auth/register');
    }

    public function registerProcess()
    {
        $session = session();
        $model = new UserModel();

        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password');
        $namaLengkap = $this->request->getVar('nama_lengkap');

        // Check if email already exists
        $existingUser = $model->where('email', $email)->first();
        if ($existingUser) {
            $session->setFlashdata('error', 'Email sudah terdaftar.');
            return redirect()->to('/register');
        }

        // Create new user
        $data = [
            'nama_lengkap' => $namaLengkap,
            'email' => $email,
            'password' => $password, // In production, hash this!
            'role' => 'student',
            'login_google' => 'tidak',
        ];

        $model->insert($data);

        // Auto login after registration
        $user = $model->where('email', $email)->first();
        $ses_data = [
            'id_user' => $user['id_user'],
            'nama_lengkap' => $user['nama_lengkap'],
            'email' => $user['email'],
            'role' => $user['role'],
            'isLoggedIn' => TRUE
        ];
        $session->set($ses_data);

        return redirect()->to('/student');
    }

    public function login()
    {
        $session = session();
        $model = new UserModel();
        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password');

        $user = $model->where('email', $email)->first();

        if ($user) {
            if ($password === $user['password']) {
                $ses_data = [
                    'id_user' => $user['id_user'],
                    'nama_lengkap' => $user['nama_lengkap'],
                    'email' => $user['email'],
                    'role' => $user['role'],
                    'isLoggedIn' => TRUE
                ];
                $session->set($ses_data);

                // Redirect based on role
                if ($user['role'] === 'super_admin') {
                    // Super Admin → ke Dashboard Super Admin
                    return redirect()->to('/admin/dashboard');
                } elseif ($user['role'] === 'admin') {
                    // Admin → ke halaman Materi & Tes
                    return redirect()->to('/admin/materi');
                } else {
                    // Student → ke halaman student dashboard
                    return redirect()->to('/student');
                }
            } else {
                $session->setFlashdata('error', 'Password salah.');
                return redirect()->to('/login');
            }
        } else {
            $session->setFlashdata('error', 'Email tidak ditemukan.');
            return redirect()->to('/login');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
