<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class DashboardController extends BaseController
{
    public function index()
    {
        $storageInfo = $this->getStorageUsage(FCPATH . 'uploads');
        
        $data = [
            'title' => 'Dashboard Utama',
            'storage' => $storageInfo
        ];
        return view('admin/dashboard/index', $data);
    }

    private function getStorageUsage($path)
    {
        $totalSize = 0;
        if (is_dir($path)) {
            $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path));
            foreach ($files as $file) {
                if ($file->isFile()) {
                    $totalSize += $file->getSize();
                }
            }
        }

        $limit = 10 * 1024 * 1024 * 1024; // 10 GB Limit
        $percentage = ($totalSize / $limit) * 100;

        return [
            'used' => $this->formatSize($totalSize),
            'limit' => $this->formatSize($limit),
            'percent' => round($percentage, 2)
        ];
    }

    private function formatSize($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }
        return round($bytes, 2) . ' ' . $units[$i];
    }
}
