-- ============================================
-- ZEVEDU ACADEMY - SAMPLE DATA
-- Jalankan di phpMyAdmin untuk testing
-- Import SEQUENCE: 1. INSERT SAMPLE PRODUK, 2. INSERT KATEGORI & VIDEO, 3. INSERT DOKUMEN, 4. INSERT USER & ACCESS
-- ============================================

-- ============================================
-- SAMPLE 1: PRODUK/KELAS
-- ============================================
-- Cek dulu apakah produk sudah ada
-- Jika belum, jalankan ini:
/*
INSERT INTO produk_pelatihan (id_kategori, judul, deskripsi, harga_awal, harga_promo, diskon_persen, gambar) VALUES
(1, 'Belajar React JS dari Nol', 'Kursus lengkap React JS untuk pemula. Pelajari konsep fundamental React, hooks, state management, dan membangun aplikasi web modern.', 500000, 350000, 30, 'uploads/products/react-course.jpg'),
(1, 'Mastering PHP & MySQL', 'Kursus komprehensif PHP dan MySQL untuk membangun aplikasi web dinamis dengan database.', 450000, 299000, 33, 'uploads/products/php-mysql.jpg');
*/

-- ============================================
-- SAMPLE 2: KATEGORI MATERI (MODUL) & VIDEO
-- ============================================
-- Contoh untuk produk dengan id_produk = 1 (sesuaikan dengan id yang ada di database kamu)
-- Jalankan jika belum ada data di kategori_materi

/*
-- MODUL 1: Pengenalan React
INSERT INTO kategori_materi (id_produk, judul_kategori, urutan_kategori) VALUES
(1, 'Pengenalan React JS', 1);

-- Video untuk Modul 1
INSERT INTO sub_materi_video (id_kategori_materi, judul_video, youtube_url, durasi_menit, urutan_video) VALUES
(1, 'apa itu React JS?', 'https://www.youtube.com/watch?v=Ke90Tje7VS0', 10, 1),
(1, 'Persiapan Environment', 'https://www.youtube.com/watch?v=Ke90Tje7VS0', 15, 2),
(1, 'Create React App vs Vite', 'https://www.youtube.com/watch?v=Ke90Tje7VS0', 12, 3);

-- MODUL 2: Component & Props
INSERT INTO kategori_materi (id_produk, judul_kategori, urutan_kategori) VALUES
(1, 'Component & Props', 2);

-- Video untuk Modul 2
INSERT INTO sub_materi_video (id_kategori_materi, judul_video, youtube_url, durasi_menit, urutan_video) VALUES
(2, 'Membuat Komponen Pertama', 'https://www.youtube.com/watch?v=Ke90Tje7VS0', 20, 1),
(2, 'Memahami Props', 'https://www.youtube.com/watch?v=Ke90Tje7VS0', 18, 2),
(2, 'Props Children', 'https://www.youtube.com/watch?v=Ke90Tje7VS0', 15, 3),
(2, 'Best Practice Props', 'https://www.youtube.com/watch?v=Ke90Tje7VS0', 12, 4);

-- MODUL 3: State & Hooks
INSERT INTO kategori_materi (id_produk, judul_kategori, urutan_kategori) VALUES
(1, 'State & Hooks', 3);

-- Video untuk Modul 3
INSERT INTO sub_materi_video (id_kategori_materi, judul_video, youtube_url, durasi_menit, urutan_video) VALUES
(3, 'Memahami useState', 'https://www.youtube.com/watch?v=Ke90Tje7VS0', 25, 1),
(3, 'useEffect Hook', 'https://www.youtube.com/watch?v=Ke90Tje7VS0', 22, 2),
(3, 'Custom Hooks', 'https://www.youtube.com/watch?v=Ke90Tje7VS0', 20, 3);
*/

-- ============================================
-- SAMPLE 3: DOKUMEN/MATERI KELAS
-- ============================================
-- Contoh untuk produk dengan id_produk = 1
/*
INSERT INTO materi_kelas (id_produk, judul_materi, deskripsi, file_materi, urutan_materi) VALUES
(1, 'E-Book: Dasar-Dasar React', 'E-book panduan lengkap React untuk pemula', 'uploads/materi/ebook-react-dasar.pdf', 1),
(1, 'Cheat Sheet: React Hooks', 'Cheat sheet referensi cepat React Hooks', 'uploads/materi/cheatsheet-react-hooks.pdf', 2),
(1, 'Source Code Latihan Modul 1', 'Kode sumber untuk latihan Modul 1', 'uploads/materi/latihan-modul1.zip', 3),
(1, 'Slide Presentasi: Component', 'Slide presentasi tentang Component', 'uploads/materi/slide-component.pptx', 4);
*/

-- ============================================
-- SAMPLE 4: USER (STUDENT) & ACCESS
-- ============================================
-- Contoh membuat student dan memberi akses ke kelas
-- Ganti :id_produk dengan id produk yang ada di database kamu

/*
-- Buat user student (jika belum ada)
INSERT INTO users (nama_lengkap, email, password, role, no_whatsapp) VALUES
('Test Student', 'student@test.com', 'test123', 'student', '081234567890');

-- Beri akses via transaksi (pembayaran)
INSERT INTO transaksi (id_user, id_produk, kode_invoice, total_bayar, status_pembayaran, tanggal_transaksi) VALUES
(2, 1, 'INV-2024-001', 350000, 'paid', NOW());

-- ATAU beri akses via kelas_user (manual/bonus - tanpa bayar)
INSERT INTO kelas_user (id_user, id_produk, status_akses, tanggal_aktif, sumber_akses) VALUES
(2, 1, 'aktif', NOW(), 'manual');
*/

-- ============================================
-- SAMPLE 5: CHAT
-- ============================================
-- Contoh chat dari student
/*
INSERT INTO chat_kelas (id_user, id_produk, pesan, pengirim, waktu_kirim) VALUES
(2, 1, 'Halo, saya sudah menyelesaikan Modul 1. Apakah bisa lanjut ke Modul 2?', 'student', NOW()),
(1, 1, 'Tentu! Silakan lanjutkan. Modul 2 akan membahas Component lebih dalam.', 'admin', NOW()),
(2, 1, 'Terima kasih! Saya akan langsung coba.', 'student', NOW());
*/

-- ============================================
-- QUICK TEST: Jika ingin langsung test
-- ============================================
-- Jalankan semua query di bawah ini SEQUENTIAL untuk membuat data test lengkap

-- Step 1: Insert sample produk (jika belum ada)
/*
INSERT IGNORE INTO produk_pelatihan (id_produk, id_kategori, judul, deskripsi, harga_awal, harga_promo, diskon_persen) VALUES
(99, 1, 'Kelas Test Sample', 'Kelas untuk testing sistem Zevedu Academy', 100000, 0, 0);
*/

-- Step 2: Insert sample user student (jika belum ada)
/*
INSERT IGNORE INTO users (id_user, nama_lengkap, email, password, role) VALUES
(99, 'Test Student', 'student@test.com', 'password123', 'student');
*/

-- Step 3: Insert kategori dan video
/*
INSERT IGNORE INTO kategori_materi (id_kategori_materi, id_produk, judul_kategori, urutan_kategori) VALUES
(1, 99, 'Modul 1: Perkenalan', 1),
(2, 99, 'Modul 2: Dasar-Dasar', 2);

INSERT IGNORE INTO sub_materi_video (id_video, id_kategori_materi, judul_video, youtube_url, durasi_menit, urutan_video) VALUES
(1, 1, 'Video 1: Selamat Datang', 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', 5, 1),
(2, 1, 'Video 2: Persiapan Environment', 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', 10, 2),
(3, 2, 'Video 3: Konsep Dasar', 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', 15, 1);
*/

-- Step 4: Insert dokumen
/*
INSERT IGNORE INTO materi_kelas (id_materi, id_produk, judul_materi, deskripsi, urutan_materi) VALUES
(1, 99, 'E-Book Panduan', 'Dokumentasi lengkap kelas test', 1),
(2, 99, 'Slide Presentasi', 'Slide untuk presentasi', 2);
*/

-- Step 5: Beri akses student ke kelas
/*
INSERT IGNORE INTO kelas_user (id_user, id_produk, status_akses, tanggal_aktif, sumber_akses) VALUES
(99, 99, 'aktif', NOW(), 'manual');
*/

-- Step 6: Insert chat sample
/*
INSERT INTO chat_kelas (id_user, id_produk, pesan, pengirim, waktu_kirim) VALUES
(99, 99, 'Halo! Saya ingin bertanya tentang kelas ini.', 'student', NOW());
*/

-- ============================================
-- VERIFIKASI: Jalankan query ini setelah insert
-- ============================================
SELECT 'Verifikasi Data:' as info;
SELECT COUNT(*) as total_produk FROM produk_pelatihan;
SELECT COUNT(*) as total_kategori FROM kategori_materi;
SELECT COUNT(*) as total_video FROM sub_materi_video;
SELECT COUNT(*) as total_dokumen FROM materi_kelas;
SELECT COUNT(*) as total_student FROM users WHERE role = 'student';