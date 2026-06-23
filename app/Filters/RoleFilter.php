<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleFilter implements FilterInterface
{
    /**
     * Role-based access control
     * Usage in routes: ['filter' => 'role:admin,super_admin']
     */

    public function before(RequestInterface $request, $arguments = null)
    {
        $role = session()->get('role');

        // If not logged in, redirect to login
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        // If no role restrictions, allow access
        if (empty($arguments)) {
            return;
        }

        // Check if user's role is in allowed roles
        $allowedRoles = is_array($arguments) ? $arguments : [$arguments];

        if (!in_array($role, $allowedRoles)) {
            // Role not allowed - redirect based on role
            if ($role === 'super_admin') {
                // Super Admin trying to access Admin-only pages → go to dashboard
                return redirect()->to('/admin/dashboard')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
            } else {
                // Admin trying to access Super Admin-only pages → go to materi
                return redirect()->to('/admin/materi')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No action needed after
    }
}