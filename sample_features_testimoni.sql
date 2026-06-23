-- =====================================================
-- SAMPLE DATA - Features Section
-- =====================================================
INSERT INTO feature_section (id_feature, judul, deskripsi, gambar) VALUES
(1, 'Belajar dengan Kurikulum Terstruktur', 'Dapatkan akses ke materi pembelajaran yang disusun secara sistematis dari tingkat dasar hingga mahir. Setiap modul dirancang oleh para ahli industri untuk memastikan kamu mendapatkan pengetahuan yang relevan dan praktis.', 'feature-1.jpg'),
(2, 'Konsultasi Langsung dengan Mentor Profesional', 'Jangan bingung sendirian! Sesi konsultasi satu-satu dengan mentor berpengalaman memungkinkan kamu mendapatkan panduan personal dan feedback langsung untuk accelerate pembelajaran.', 'feature-2.jpg'),
(3, 'Uji Pemahaman dengan Quiz Interaktif', 'Validasi skill yang kamu pelajari dengan quiz berkala yang dirancang untuk mengukur pemahamanmu secara menyeluruh. Dapatkan sertifikat sebagai bukti kompetensimu.', 'feature-3.jpg'),
(4, 'Dashboard Progress & Analytics', 'Pantau perkembangan belajarmu secara real-time dengan dashboard analytics yang menampilkan progress, waktu belajar, dan area yang perlu ditingkatkan.', 'feature-4.jpg');

-- =====================================================
-- SAMPLE DATA - Testimoni
-- =====================================================
INSERT INTO testimoni (id_testimoni, id_user, id_produk, nama, foto_profil, deskripsi, rating) VALUES
(1, 1, 1, 'Rizky Pratama', 'profile-1.jpg', 'Pelayanan sangat memuaskan! Mentor的专业讲解让复杂的概念变得易于理解。100% recommended untuk yang ingin belajar coding dari nol.', 9),
(2, 2, 1, 'Sarah Amanda', 'profile-2.jpg', '材料的质量超出我的预期。Sistem belajar yang terstruktur membuat saya bisa mengikuti dengan mudah meski sebagai pemula total.', 10),
(3, 3, 1, 'Budi Santoso', 'profile-3.jpg', '久闻大名，终于体验到了！Saya sudah coba beberapa platform lain dan ini yang terbaik. Quiz challenge-nya seru banget!', 8),
(4, 4, 1, 'Diana Putri', 'profile-4.jpg', 'La calidad del contenido es excelente. Dashboard analytics membantu saya melihat progress dengan jelas. Worth every penny!', 9),
(5, 5, 1, 'Ahmad Fauzi', 'profile-5.jpg', 'Il materiale è molto completo e ben strutturato. Konsultasi mentor sangat helpful, bisa tanya langsung kapan saja.', 10),
(6, 6, 1, 'Maya Indah', 'profile-6.jpg', 'Les quiz sont vraiment bien conçus. Setiap fitur di platform ini terasa продуманный dan user-friendly. Great job!', 8);