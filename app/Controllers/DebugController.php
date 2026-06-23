<?php
/**
 * Debug script untuk cek video data
 * Akses via: http://localhost/zeveduacademy/debug-video
 */

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SubMateriVideoModel;
use App\Models\KategoriMateriModel;

class DebugController extends BaseController
{
    public function video()
    {
        $videoModel = new SubMateriVideoModel();
        $kategoriModel = new KategoriMateriModel();

        // Get all videos
        $videos = $videoModel->findAll();

        echo "<h1>Debug Video Data</h1>";
        echo "<h2>All Videos in Database:</h2>";
        echo "<pre>";
        print_r($videos);
        echo "</pre>";

        echo "<h2>YouTube URL Test:</h2>";
        foreach ($videos as $video) {
            if (!empty($video['youtube_url'])) {
                $youtubeId = '';
                $url = trim($video['youtube_url']);

                if (preg_match('/youtube\.com\/watch\?.*v=([a-zA-Z0-9_-]{11})/', $url, $match)) {
                    $youtubeId = $match[1];
                } elseif (preg_match('/youtu\.be\/([a-zA-Z0-9_-]{11})/', $url, $match)) {
                    $youtubeId = $match[1];
                } elseif (preg_match('/youtube\.com\/embed\/([a-zA-Z0-9_-]{11})/', $url, $match)) {
                    $youtubeId = $match[1];
                }

                echo "<p><strong>Video ID {$video['id_video']}:</strong> {$video['judul_video']}</p>";
                echo "<p>URL: {$video['youtube_url']}</p>";
                echo "<p>YouTube ID: $youtubeId</p>";
                echo "<p>Embed URL: https://www.youtube.com/embed/$youtubeId</p>";
                echo "<iframe width='320' height='180' src='https://www.youtube.com/embed/$youtubeId' frameborder='0' allowfullscreen></iframe>";
                echo "<hr>";
            }
        }

        echo "<h2>Kategori& Video Relation:</h2>";
        $kategori = $kategoriModel->findAll();
        foreach ($kategori as $kat) {
            $vids = $videoModel->where('id_kategori_materi', $kat['id_kategori_materi'])->findAll();
            echo "<p><strong>Kategori {$kat['id_kategori_materi']}:</strong> {$kat['judul_kategori']} (Produk ID: {$kat['id_produk']})</p>";
            echo "<p>Videos: " . count($vids) . "</p>";
            echo "<hr>";
        }
    }

    public function chat()
    {
        $db = \Config\Database::connect();

        echo "<h1>Debug Chat Data</h1>";

        // Check chat table
        $chats = $db->table('chat_kelas')->get()->getResultArray();
        echo "<h2>All Chats:</h2>";
        echo "<pre>";
        print_r($chats);
        echo "</pre>";

        // Check table structure
        echo "<h2>Chat Table Structure:</h2>";
        $structure = $db->query("DESCRIBE chat_kelas")->getResultArray();
        echo "<pre>";
        print_r($structure);
        echo "</pre>";
    }
}