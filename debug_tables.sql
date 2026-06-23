-- ============================================
-- ZEVEDU ACADEMY - DEBUG QUERY
-- Jalankan di phpMyAdmin untuk cek data
-- ============================================

-- 1. Check apakah ada produk/kelas
SELECT '=== PRODUK (KELAS) ===' as info;
SELECT * FROM produk_pelatihan;

-- 2. Check apakah ada kategori_materi (modul)
SELECT '=== KATEGORI MATERI (MODUL) ===' as info;
SELECT km.*, pp.judul as nama_produk
FROM kategori_materi km
JOIN produk_pelatihan pp ON pp.id_produk = km.id_produk;

-- 3. Check apakah ada video
SELECT '=== SUB MATERI VIDEO ===' as info;
SELECT smv.*, km.judul_kategori, pp.judul as nama_produk
FROM sub_materi_video smv
JOIN kategori_materi km ON km.id_kategori_materi = smv.id_kategori_materi
JOIN produk_pelatihan pp ON pp.id_produk = km.id_produk;

-- 4. Check apakah ada dokumen/materi_kelas
SELECT '=== MATERI KELAS (DOKUMEN) ===' as info;
SELECT mk.*, pp.judul as nama_produk
FROM materi_kelas mk
JOIN produk_pelatihan pp ON pp.id_produk = mk.id_produk;

-- 5. Check apakah ada chat
SELECT '=== CHAT KELAS ===' as info;
SELECT ck.*, u.nama_lengkap, pp.judul as nama_produk
FROM chat_kelas ck
JOIN users u ON u.id_user = ck.id_user
JOIN produk_pelatihan pp ON pp.id_produk = ck.id_produk;

-- 6. Check user dengan role student
SELECT '=== USERS (STUDENT) ===' as info;
SELECT * FROM users WHERE role = 'student';

-- 7. Check transaksi siswa
SELECT '=== TRANSAKSI ===' as info;
SELECT t.*, u.nama_lengkap, pp.judul, t.status_pembayaran
FROM transaksi t
JOIN users u ON u.id_user = t.id_user
JOIN produk_pelatihan pp ON pp.id_produk = t.id_produk;

-- 8. Check kelas_user (manual access)
SELECT '=== KELAS USER (MANUAL ACCESS) ===' as info;
SELECT ku.*, u.nama_lengkap, pp.judul
FROM kelas_user ku
JOIN users u ON u.id_user = ku.id_user
JOIN produk_pelatihan pp ON pp.id_produk = ku.id_produk;

-- 9. Check progress_belajar
SELECT '=== PROGRESS BELAJAR ===' as info;
SELECT pb.*, u.nama_lengkap, mk.judul_materi, smv.judul_video
FROM progress_belajar pb
JOIN users u ON u.id_user = pb.id_user
LEFT JOIN materi_kelas mk ON mk.id_materi = pb.id_materi
LEFT JOIN sub_materi_video smv ON smv.id_video = pb.id_video;