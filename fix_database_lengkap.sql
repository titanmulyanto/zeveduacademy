-- =====================================================
-- SQL FIX LENGKAP: ZevEdu Academy Database
-- Jalankan SEMUA baris ini di phpMyAdmin
-- =====================================================

-- 1. Buat tabel kelas_admin (tanpa foreign key untuk menghindari error)
CREATE TABLE IF NOT EXISTS kelas_admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_produk INT NOT NULL,
    id_user INT NOT NULL,
    role ENUM('pemateri', 'asisten', 'admin') DEFAULT 'pemateri',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_kelas_admin (id_produk, id_user)
);

-- 2. Buat tabel template_sertifikat
CREATE TABLE IF NOT EXISTS template_sertifikat (
    id_template INT AUTO_INCREMENT PRIMARY KEY,
    id_produk INT NOT NULL,
    background_image VARCHAR(255)
);

-- 3. Tambah kolom id_admin ke produk_pelatihan (kalau belum ada)
-- Pertama cek apakah kolom sudah ada:
-- SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'produk_pelatihan' AND COLUMN_NAME = 'id_admin';

-- Kalau belum ada, jalankan ini:
ALTER TABLE produk_pelatihan ADD COLUMN id_admin INT AFTER id_kategori;

-- 4. Insert default kategori (kalau tabel kosong)
INSERT IGNORE INTO kategori_produk (id_kategori, nama_kategori, deskripsi) VALUES
(1, 'Programming', 'Kelas tentang programming'),
(2, 'Desain', 'Kelas tentang desain'),
(3, 'Bisnis', 'Kelas tentang bisnis'),
(4, 'Umum', 'Kelas umum');

-- 5. Verifikasi - cek semua tabel
SHOW TABLES;