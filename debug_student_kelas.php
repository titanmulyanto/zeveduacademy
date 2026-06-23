<?php
$mysqli = new mysqli('localhost', 'root', '', 'db_zevedu');
$userId = 12; // sasaranku
$id = 8; // dev

echo "=== EXACT CONTROLLER OUTPUT ===\n\n";

// Access check
$transaksi = $mysqli->query("SELECT * FROM transaksi WHERE id_user = $userId AND id_produk = $id AND status_pembayaran = 'paid'")->fetch_assoc();
$hasAccessViaKelasUser = $mysqli->query("SELECT * FROM kelas_user WHERE id_user = $userId AND id_produk = $id AND status_akses = 'aktif'")->num_rows > 0;

if (!$transaksi && !$hasAccessViaKelasUser) {
    echo "NO ACCESS - Redirecting\n";
    exit;
}

echo "ACCESS: GRANTED\n\n";

// Get produk
$result = $mysqli->query("SELECT * FROM produk_pelatihan WHERE id_produk = $id");
$produk = $result->fetch_assoc();
echo "produk['id_produk'] = " . $produk['id_produk'] . "\n";
echo "produk['judul'] = " . $produk['judul'] . "\n\n";

// Get modules with videos - EXACTLY like controller
$result = $mysqli->query("SELECT * FROM kategori_materi WHERE id_produk = $id ORDER BY urutan_kategori ASC");
$kategori = [];
while ($kat = $result->fetch_assoc()) {
    // Get videos for this kategori
    $videoResult = $mysqli->query("SELECT * FROM sub_materi_video WHERE id_kategori_materi = " . $kat['id_kategori_materi'] . " ORDER BY urutan_video ASC");
    $videos = [];
    while ($vid = $videoResult->fetch_assoc()) {
        $videos[] = $vid;
    }
    $kat['videos'] = $videos;
    $kategori[] = $kat;
}

echo "\$modules (kategori) count: " . count($kategori) . "\n";
foreach ($kategori as $i => $mod) {
    echo "  [" . $i . "] \$mod['judul_kategori'] = " . $mod['judul_kategori'] . "\n";
    echo "      \$mod['videos'] count = " . count($mod['videos']) . "\n";
    foreach ($mod['videos'] as $j => $vid) {
        echo "        [" . $j . "] \$vid['judul_video'] = " . $vid['judul_video'] . "\n";
        echo "            youtube_url = " . ($vid['youtube_url'] ? $vid['youtube_url'] : 'NULL') . "\n";
    }
}

echo "\n";

// Get documents
$result = $mysqli->query("SELECT * FROM materi_kelas WHERE id_produk = $id ORDER BY urutan_materi ASC");
$dokumen = $result->fetch_all(MYSQLI_ASSOC);
echo "\$dokumen count: " . count($dokumen) . "\n";
foreach ($dokumen as $doc) {
    echo "  - " . $doc['judul_materi'] . " | file: " . ($doc['file_materi'] ? 'ADA' : 'NULL') . "\n";
}

echo "\n";

// Get chats
$result = $mysqli->query("SELECT * FROM chat_kelas WHERE id_produk = $id ORDER BY waktu_kirim ASC");
$chats = $result->fetch_all(MYSQLI_ASSOC);
echo "\$chats count: " . count($chats) . "\n";
foreach ($chats as $chat) {
    echo "  - User " . $chat['id_user'] . ": " . substr($chat['pesan'], 0, 30) . "...\n";
}

$mysqli->close();