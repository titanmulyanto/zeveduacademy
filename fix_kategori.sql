-- =====================================================
-- SQL FIX: Add Default Category for produk_pelatihan
-- Run this in phpMyAdmin: http://localhost/phpmyadmin/index.php?db=db_zevedu
-- =====================================================

-- Step 1: Check if kategori_produk table has data
SELECT * FROM kategori_produk;

-- Step 2: If empty, insert default categories
INSERT INTO kategori_produk (nama_kategori, deskripsi) VALUES
('Programming', 'Kelas tentang programming dan development'),
('Desain', 'Kelas tentang desain grafis dan UI/UX'),
('Bisnis', 'Kelas tentang bisnis dan entrepreneurship'),
('Umum', 'Kelas umum / Uncategorized');

-- Step 3: Now you can add products with valid id_kategori
-- id_kategori values:
-- 1 = Programming
-- 2 = Desain
-- 3 = Bisnis
-- 4 = Umum