-- =====================================================
-- SQL FIX: ZevEdu Academy - Fix Database Structure
-- Access: http://localhost/phpmyadmin/index.php?db=db_zevedu
-- =====================================================

-- Step 1: Create template_sertifikat table (for certificate backgrounds per class)
CREATE TABLE IF NOT EXISTS template_sertifikat (
    id_template INT AUTO_INCREMENT PRIMARY KEY,
    id_produk INT NOT NULL,
    background_image VARCHAR(255),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_produk) REFERENCES produk_pelatihan(id_produk) ON DELETE CASCADE
);

-- Step 2: Add id_admin column to produk_pelatihan if not exists
-- First check if column exists
SET @column_exists = (
    SELECT COUNT(*)
    FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = 'db_zevedu'
    AND TABLE_NAME = 'produk_pelatihan'
    AND COLUMN_NAME = 'id_admin'
);

-- If column doesn't exist, add it
SET @sql = IF(@column_exists = 0,
    'ALTER TABLE produk_pelatihan ADD COLUMN id_admin INT AFTER id_kategori',
    'SELECT "Column id_admin already exists" as status'
);

PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Step 3: Verify tables
SELECT 'Tables in db_zevedu:' as info;
SHOW TABLES;

-- Step 4: Verify produk_pelatihan columns
SELECT 'Columns in produk_pelatihan:' as info;
DESCRIBE produk_pelatihan;

-- Step 5: Verify template_sertifikat columns
SELECT 'Columns in template_sertifikat:' as info;
DESCRIBE template_sertifikat;

-- Step 6: Verify sertifikat_kelas columns
SELECT 'Columns in sertifikat_kelas:' as info;
DESCRIBE sertifikat_kelas;