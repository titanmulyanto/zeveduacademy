-- SQL FIX: Multi-Admin per Class (Fixed for MariaDB)
-- =====================================================

-- Step 1: Buat tabel
CREATE TABLE IF NOT EXISTS kelas_admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_produk INT NOT NULL,
    id_user INT NOT NULL,
    role ENUM('pemateri', 'asisten', 'admin') DEFAULT 'pemateri',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Step 2: Tambah unique index terpisah (kalau error, skip langkah ini)
-- ALTER TABLE kelas_admin ADD UNIQUE INDEX idx_kelas_admin (id_produk, id_user);

-- Step 3: Copy data dari id_admin yang sudah ada (optional)
-- INSERT INTO kelas_admin (id_produk, id_user, role)
-- SELECT id_produk, id_admin, 'pemateri'
-- FROM produk_pelatihan
-- WHERE id_admin IS NOT NULL AND id_admin > 0;