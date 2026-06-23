-- =====================================================
-- FIX SQL: Fix kelas_admin table structure
-- =====================================================

-- Step 1: Drop existing kelas_admin table (if exists with wrong structure)
DROP TABLE IF EXISTS kelas_admin;

-- Step 2: Create new table with correct structure
CREATE TABLE kelas_admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_produk INT NOT NULL,
    id_user INT NOT NULL,
    role VARCHAR(50) DEFAULT 'pemateri',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Step 3: Add unique constraint (separate statement for MariaDB)
ALTER TABLE kelas_admin ADD UNIQUE INDEX idx_kelas_admin (id_produk, id_user);

-- Step 4: Drop and recreate template_sertifikat table
DROP TABLE IF EXISTS template_sertifikat;

CREATE TABLE template_sertifikat (
    id_template INT AUTO_INCREMENT PRIMARY KEY,
    id_produk INT NOT NULL,
    background_image VARCHAR(255)
);

-- Step 5: Verify tables
SHOW TABLES;

-- Step 6: Check table structures
DESCRIBE kelas_admin;
DESCRIBE template_sertifikat;