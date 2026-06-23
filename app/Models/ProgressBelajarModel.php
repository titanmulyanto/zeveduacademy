<?php

namespace App\Models;

use CodeIgniter\Model;

class ProgressBelajarModel extends Model
{
    protected $table            = 'progress_belajar';
    protected $primaryKey       = 'id_progress';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['id_user', 'id_materi', 'id_video', 'status_selesai', 'progress_persen', 'terakhir_ditonton', 'tanggal_selesai'];

    /**
     * Get progress by user and produk (documents + videos)
     *
     * Now supports both:
     * - materi_kelas (documents) via id_materi
     * - sub_materi_video (videos) via id_video
     */
    public function getProgressByUserAndProduk(int $id_user, int $id_produk): array
    {
        // Get document progress
        $docProgress = $this->select('progress_belajar.*, materi_kelas.judul_materi')
                    ->join('materi_kelas', 'materi_kelas.id_materi = progress_belajar.id_materi', 'left')
                    ->where('progress_belajar.id_user', $id_user)
                    ->where('materi_kelas.id_produk', $id_produk)
                    ->where('progress_belajar.id_materi IS NOT NULL')
                    ->orderBy('progress_belajar.id_progress', 'ASC')
                    ->findAll();

        // Get video progress
        $videoProgress = $this->select('progress_belajar.*, sub_materi_video.judul_video')
                    ->join('sub_materi_video', 'sub_materi_video.id_video = progress_belajar.id_video', 'left')
                    ->join('kategori_materi', 'kategori_materi.id_kategori_materi = sub_materi_video.id_kategori_materi', 'left')
                    ->where('progress_belajar.id_user', $id_user)
                    ->where('kategori_materi.id_produk', $id_produk)
                    ->where('progress_belajar.id_video IS NOT NULL')
                    ->orderBy('progress_belajar.id_progress', 'ASC')
                    ->findAll();

        return array_merge($docProgress, $videoProgress);
    }

    /**
     * Get progress map by user for a produk (for quick lookup)
     * Returns array keyed by id_materi OR id_video
     */
    public function getProgressMap(int $id_user, int $id_produk): array
    {
        $progress = [];

        // Document progress
        $docs = $this->select('progress_belajar.*, materi_kelas.id_materi')
                    ->join('materi_kelas', 'materi_kelas.id_materi = progress_belajar.id_materi', 'left')
                    ->where('progress_belajar.id_user', $id_user)
                    ->where('materi_kelas.id_produk', $id_produk)
                    ->where('progress_belajar.id_materi IS NOT NULL')
                    ->findAll();

        foreach ($docs as $p) {
            $progress[$p['id_materi']] = $p;
        }

        // Video progress
        $videos = $this->select('progress_belajar.*, sub_materi_video.id_video')
                    ->join('sub_materi_video', 'sub_materi_video.id_video = progress_belajar.id_video', 'left')
                    ->join('kategori_materi', 'kategori_materi.id_kategori_materi = sub_materi_video.id_kategori_materi', 'left')
                    ->where('progress_belajar.id_user', $id_user)
                    ->where('kategori_materi.id_produk', $id_produk)
                    ->where('progress_belajar.id_video IS NOT NULL')
                    ->findAll();

        foreach ($videos as $p) {
            $progress[$p['id_video']] = $p;
        }

        return $progress;
    }

    /**
     * Update or create progress for document (materi_kelas)
     */
    public function updateProgress(int $id_user, int $id_materi, int $progress_persen): bool
    {
        $existing = $this->where('id_user', $id_user)
                            ->where('id_materi', $id_materi)
                            ->first();

        $data = [
            'progress_persen' => $progress_persen,
            'terakhir_ditonton' => date('Y-m-d H:i:s')
        ];

        if ($progress_persen >= 100) {
            $data['status_selesai'] = 'selesai';
            $data['tanggal_selesai'] = date('Y-m-d H:i:s');
        }

        if ($existing) {
            return $this->update($existing['id_progress'], $data);
        } else {
            $data['id_user'] = $id_user;
            $data['id_materi'] = $id_materi;
            return $this->insert($data);
        }
    }

    /**
     * Update or create progress for video (sub_materi_video)
     */
    public function updateVideoProgress(int $id_user, int $id_video, int $progress_persen = 100): bool
    {
        $existing = $this->where('id_user', $id_user)
                            ->where('id_video', $id_video)
                            ->first();

        $data = [
            'progress_persen' => $progress_persen,
            'terakhir_ditonton' => date('Y-m-d H:i:s')
        ];

        if ($progress_persen >= 100) {
            $data['status_selesai'] = 'selesai';
            $data['tanggal_selesai'] = date('Y-m-d H:i:s');
        }

        if ($existing) {
            return $this->update($existing['id_progress'], $data);
        } else {
            $data['id_user'] = $id_user;
            $data['id_video'] = $id_video;
            return $this->insert($data);
        }
    }

    /**
     * Get total progress for a user in a produk
     * Counts BOTH documents AND videos
     */
    public function getTotalProgress(int $id_user, int $id_produk): float
    {
        $db = \Config\Database::connect();

        // Count total documents (materi_kelas) for this produk
        $totalDocs = $db->table('materi_kelas')
                           ->where('id_produk', $id_produk)
                           ->countAllResults();

        // Count total videos for this produk
        $totalVideos = $db->table('sub_materi_video sm')
                           ->join('kategori_materi km', 'km.id_kategori_materi = sm.id_kategori_materi')
                           ->where('km.id_produk', $id_produk)
                           ->countAllResults();

        $totalItems = $totalDocs + $totalVideos;

        if ($totalItems == 0) return 0;

        // Get completed document count
        $completedDocs = $db->table('progress_belajar pb')
                        ->join('materi_kelas mk', 'mk.id_materi = pb.id_materi')
                        ->where('pb.id_user', $id_user)
                        ->where('pb.status_selesai', 'selesai')
                        ->where('mk.id_produk', $id_produk)
                        ->where('pb.id_materi IS NOT NULL')
                        ->countAllResults();

        // Get completed video count
        $completedVideos = $db->table('progress_belajar pb')
                        ->join('sub_materi_video sm', 'sm.id_video = pb.id_video')
                        ->join('kategori_materi km', 'km.id_kategori_materi = sm.id_kategori_materi')
                        ->where('pb.id_user', $id_user)
                        ->where('pb.status_selesai', 'selesai')
                        ->where('km.id_produk', $id_produk)
                        ->where('pb.id_video IS NOT NULL')
                        ->countAllResults();

        $totalCompleted = $completedDocs + $completedVideos;

        return round(($totalCompleted / $totalItems) * 100, 2);
    }
}