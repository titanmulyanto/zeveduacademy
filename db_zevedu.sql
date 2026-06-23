CREATE DATABASE IF NOT EXISTS db_zevedu;
USE db_zevedu;

CREATE TABLE slider_banner (
    id_slider INT AUTO_INCREMENT PRIMARY KEY,
    gambar VARCHAR(255) NOT NULL,
    urutan INT NOT NULL
);

CREATE TABLE kategori_produk (
    id_kategori INT AUTO_INCREMENT PRIMARY KEY,
    nama_kategori VARCHAR(150) NOT NULL,
    deskripsi TEXT
);

CREATE TABLE produk_pelatihan (
    id_produk INT AUTO_INCREMENT PRIMARY KEY,
    id_kategori INT NOT NULL,
    judul VARCHAR(150) NOT NULL,
    deskripsi TEXT,
    harga_awal DECIMAL(10,2) NOT NULL,
    harga_promo DECIMAL(10,2) NOT NULL,
    diskon_persen INT NOT NULL,
    gambar VARCHAR(255),
    FOREIGN KEY (id_kategori) REFERENCES kategori_produk(id_kategori)
);

CREATE TABLE feature_section (
    id_feature INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(150) NOT NULL,
    deskripsi TEXT,
    gambar VARCHAR(255)
);

CREATE TABLE users (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    nama_lengkap VARCHAR(150) NOT NULL,
    instansi VARCHAR(150),
    no_whatsapp VARCHAR(20),
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255),
    login_google ENUM('ya','tidak') DEFAULT 'tidak',
    google_id VARCHAR(255),
    role ENUM('student','admin','super_admin') DEFAULT 'student',
    alamat TEXT,
    tanggal_lahir DATE,
    jenis_kelamin ENUM('L','P'),
    pendidikan_terakhir VARCHAR(150),
    foto_profil VARCHAR(255)
);

CREATE TABLE testimoni (
    id_testimoni INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    id_produk INT NOT NULL,
    nama VARCHAR(150) NOT NULL,
    foto_profil VARCHAR(255),
    deskripsi TEXT,
    rating INT,
    FOREIGN KEY (id_user) REFERENCES users(id_user),
    FOREIGN KEY (id_produk) REFERENCES produk_pelatihan(id_produk)
);

CREATE TABLE faq (
    id_faq INT AUTO_INCREMENT PRIMARY KEY,
    pertanyaan VARCHAR(255) NOT NULL,
    jawaban TEXT
);

CREATE TABLE transaksi (
    id_transaksi INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    id_produk INT NOT NULL,
    kode_invoice VARCHAR(100) NOT NULL,
    total_bayar DECIMAL(10,2) NOT NULL,
    status_pembayaran ENUM('pending','paid','failed','expired') DEFAULT 'pending',
    midtrans_order_id VARCHAR(255),
    snap_token TEXT,
    tanggal_transaksi DATETIME,
    FOREIGN KEY (id_user) REFERENCES users(id_user),
    FOREIGN KEY (id_produk) REFERENCES produk_pelatihan(id_produk)
);

CREATE TABLE kelas_user (
    id_kelas_user INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    id_produk INT NOT NULL,
    status_akses ENUM('aktif','nonaktif') DEFAULT 'aktif',
    tanggal_aktif DATETIME,
    sumber_akses ENUM('payment','manual') DEFAULT 'payment',
    FOREIGN KEY (id_user) REFERENCES users(id_user),
    FOREIGN KEY (id_produk) REFERENCES produk_pelatihan(id_produk)
);

CREATE TABLE kategori_materi (
    id_kategori_materi INT AUTO_INCREMENT PRIMARY KEY,
    id_produk INT NOT NULL,
    judul_kategori VARCHAR(150) NOT NULL,
    urutan_kategori INT NOT NULL,
    FOREIGN KEY (id_produk) REFERENCES produk_pelatihan(id_produk)
);

CREATE TABLE sub_materi_video (
    id_video INT AUTO_INCREMENT PRIMARY KEY,
    id_kategori_materi INT NOT NULL,
    judul_video VARCHAR(150) NOT NULL,
    youtube_url TEXT,
    file_materi VARCHAR(255),
    durasi_menit INT,
    urutan_video INT,
    minimal_progress_unlock INT,
    FOREIGN KEY (id_kategori_materi) REFERENCES kategori_materi(id_kategori_materi)
);

CREATE TABLE materi_kelas (
    id_materi INT AUTO_INCREMENT PRIMARY KEY,
    id_produk INT NOT NULL,
    judul_materi VARCHAR(150) NOT NULL,
    deskripsi TEXT,
    video_url TEXT,
    file_materi VARCHAR(255),
    urutan_materi INT,
    FOREIGN KEY (id_produk) REFERENCES produk_pelatihan(id_produk)
);

CREATE TABLE progress_belajar (
    id_progress INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    id_materi INT NOT NULL,
    status_selesai ENUM('belum','selesai') DEFAULT 'belum',
    progress_persen INT DEFAULT 0,
    terakhir_ditonton DATETIME,
    tanggal_selesai DATETIME,
    FOREIGN KEY (id_user) REFERENCES users(id_user),
    FOREIGN KEY (id_materi) REFERENCES materi_kelas(id_materi)
);

CREATE TABLE chat_kelas (
    id_chat INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    id_produk INT NOT NULL,
    pesan TEXT NOT NULL,
    pengirim ENUM('student','admin','super_admin') NOT NULL,
    waktu_kirim DATETIME,
    FOREIGN KEY (id_user) REFERENCES users(id_user),
    FOREIGN KEY (id_produk) REFERENCES produk_pelatihan(id_produk)
);

CREATE TABLE ujian_sertifikasi (
    id_ujian INT AUTO_INCREMENT PRIMARY KEY,
    id_produk INT NOT NULL,
    durasi_menit INT NOT NULL,
    nilai_minimal_lulus INT NOT NULL,
    FOREIGN KEY (id_produk) REFERENCES produk_pelatihan(id_produk)
);

CREATE TABLE soal_ujian (
    id_soal INT AUTO_INCREMENT PRIMARY KEY,
    id_ujian INT NOT NULL,
    pertanyaan TEXT NOT NULL,
    opsi_a TEXT,
    opsi_b TEXT,
    opsi_c TEXT,
    jawaban_benar ENUM('A','B','C') NOT NULL,
    FOREIGN KEY (id_ujian) REFERENCES ujian_sertifikasi(id_ujian)
);

CREATE TABLE hasil_ujian (
    id_hasil INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    id_ujian INT NOT NULL,
    nilai INT,
    status_lulus ENUM('lulus','tidak'),
    tanggal_ujian DATETIME,
    FOREIGN KEY (id_user) REFERENCES users(id_user),
    FOREIGN KEY (id_ujian) REFERENCES ujian_sertifikasi(id_ujian)
);

CREATE TABLE template_sertifikat (
    id_template INT AUTO_INCREMENT PRIMARY KEY,
    id_produk INT NOT NULL,
    background_image VARCHAR(255),
    FOREIGN KEY (id_produk) REFERENCES produk_pelatihan(id_produk)
);

CREATE TABLE sertifikat_kelas (
    id_sertifikat INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    id_produk INT NOT NULL,
    nomor_sertifikat VARCHAR(150) NOT NULL,
    tanggal_terbit DATE,
    file_pdf VARCHAR(255),
    FOREIGN KEY (id_user) REFERENCES users(id_user),
    FOREIGN KEY (id_produk) REFERENCES produk_pelatihan(id_produk)
);

-- ============================================
-- Tabel kelas_admin (relasi admin/pemateri ke kelas)
-- ============================================
CREATE TABLE kelas_admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_produk INT NOT NULL,
    id_user INT NOT NULL,
    role VARCHAR(50) DEFAULT 'pemateri',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_kelas_admin (id_produk, id_user),
    FOREIGN KEY (id_produk) REFERENCES produk_pelatihan(id_produk) ON DELETE CASCADE,
    FOREIGN KEY (id_user) REFERENCES users(id_user) ON DELETE CASCADE
);
