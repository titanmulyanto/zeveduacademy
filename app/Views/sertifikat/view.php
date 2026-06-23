<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Sertifikat' ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Georgia', serif;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .container { width: 100%; max-width: 900px; }

        /* ====== LOCKED MESSAGE ====== */
        .locked-alert {
            background: linear-gradient(135deg, #ff6b6b 0%, #ff8e53 100%);
            color: white;
            padding: 30px;
            border-radius: 20px;
            text-align: center;
            margin-bottom: 30px;
            box-shadow: 0 10px 40px rgba(255,107,107,0.3);
        }
        .locked-alert h2 { font-size: 28px; margin-bottom: 10px; }
        .locked-alert p { opacity: 0.9; margin-bottom: 20px; }
        .btn-unlock {
            display: inline-block;
            background: white;
            color: #ff6b6b;
            padding: 15px 40px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: bold;
            font-size: 16px;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .btn-unlock:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }

        /* ====== PASS/FAIL STATUS ====== */
        .exam-status {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            text-align: center;
        }
        .exam-status.pass { border: 2px solid #22c55e; }
        .exam-status.fail { border: 2px solid #ef4444; }

        /* ====== CERTIFICATE ====== */
        .certificate {
            background: white;
            border-radius: 10px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            position: relative;
            overflow: hidden;
        }
        .certificate-bg {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background-size: cover;
            background-position: center;
            opacity: 0.15;
            z-index: 0;
        }
        .certificate-content {
            position: relative;
            z-index: 1;
            text-align: center;
        }

        /* Border decorations */
        .border-outer {
            position: absolute;
            top: 15px; left: 15px; right: 15px; bottom: 15px;
            border: 3px solid #c9a227;
            border-radius: 5px;
            pointer-events: none;
        }
        .border-inner {
            position: absolute;
            top: 25px; left: 25px; right: 25px; bottom: 25px;
            border: 1px solid #c9a227;
            border-radius: 3px;
            pointer-events: none;
        }

        /* Certificate elements - positioned */
        .cert-number { /* Pojok kanan atas */
            position: absolute;
            top: 35px; right: 45px;
            font-size: 11px;
            color: #666;
        }
        .cert-date { /* Pojok kiri atas */
            position: absolute;
            top: 35px; left: 45px;
            font-size: 11px;
            color: #666;
        }
        .cert-header { /* CERTIFICATE */
            color: #c9a227;
            font-size: 32px;
            font-weight: bold;
            letter-spacing: 8px;
            margin-bottom: 15px;
        }
        .cert-subheader { /* This certifies that */
            font-size: 16px;
            color: #555;
            margin-bottom: 25px;
        }
        .cert-name { /* Nama student */
            font-size: 28px;
            font-weight: bold;
            color: #333;
            margin-bottom: 15px;
            text-transform: uppercase;
        }
        .cert-training-text { /* has successfully attended... */
            font-size: 14px;
            color: #555;
            margin-bottom: 5px;
        }
        .cert-minutes { /* XX minutes */
            font-size: 16px;
            color: #333;
            font-weight: bold;
        }
        .cert-course { /* Judul kelas */
            font-size: 20px;
            font-weight: bold;
            color: #333;
            margin-top: 10px;
            margin-bottom: 30px;
        }
        .cert-instructor { /* Nama admin pemateri - TENGAH */
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            width: 100%;
        }
        .cert-instructor-name {
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 5px;
        }
        .cert-instructor-title {
            font-size: 13px;
            color: #666;
        }
        .cert-seal { /* Seal/logo */
            margin-top: 40px;
        }
        .cert-seal-inner {
            width: 70px;
            height: 70px;
            background: #c9a227;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            color: white;
            font-size: 10px;
            font-weight: bold;
        }

        /* Download button */
        .btn-download {
            display: block;
            width: 100%;
            max-width: 400px;
            margin: 30px auto;
            padding: 15px;
            background: linear-gradient(135deg, #c9a227 0%, #d4af37 100%);
            color: white;
            border: none;
            border-radius: 50px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
            transition: transform 0.2s;
        }
        .btn-download:hover { transform: translateY(-2px); }

        @media print {
            body { background: white; }
            .locked-alert, .btn-download, .btn-unlock, .exam-status { display: none; }
        }
    </style>
</head>
<body>
<div class="container">

    <?php if (isset($isLocked) && $isLocked): ?>
    <!-- ====== LOCKED SERTIFIKAT MESSAGE ====== -->
    <div class="locked-alert">
        <h2>🔒 Sertifikat Terkunci</h2>
        <p>
            <?php if (isset($hasPassed) && $hasPassed): ?>
                Selamat! Anda telah lulus ujian dengan nilai <strong><?= $nilai ?? 0 ?></strong>.<br>
                Namun sertifikat masih terkunci karena belum menyelesaikan pembayaran.
            <?php else: ?>
                Selesaikan pembayaran untuk membuka sertifikat.
            <?php endif; ?>
        </p>
        <?php if (isset($isFreeClass) && $isFreeClass): ?>
            <a href="<?= base_url('/payment/start/' . $produk['id_produk']) ?>" class="btn-unlock">
                💳 Bayar untuk Unlock Sertifikat - Rp <?= number_format($produk['harga_promo'] ?? 0, 0, ',', '.') ?>
            </a>
        <?php else: ?>
            <a href="<?= base_url('/kelas/' . $produk['id_produk']) ?>" class="btn-unlock">
                📚 Ikuti Kelas untuk Mendapat Sertifikat
            </a>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <?php if (isset($hasPassed)): ?>
    <!-- ====== EXAM STATUS ====== -->
    <div class="exam-status <?= $hasPassed ? 'pass' : 'fail' ?>">
        <?php if ($hasPassed): ?>
            <div style="color: #22c55e; font-size: 20px;">✓ Anda Lulus Ujian Sertifikasi</div>
            <div>Nilai: <strong><?= $nilai ?? 0 ?></strong></div>
        <?php else: ?>
            <div style="color: #ef4444; font-size: 20px;">✗ Anda Belum Lulus Ujian</div>
            <div>Nilai Anda: <strong><?= $nilai ?? 0 ?></strong> - Nilai Minimal: <strong><?= $ujian['nilai_minimal_lulus'] ?? 70 ?></strong></div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <!-- ====== CERTIFICATE DISPLAY ====== -->
    <div class="certificate">
        <!-- Background image from template -->
        <?php if (!empty($backgroundPath)): ?>
        <div class="certificate-bg" style="background-image: url('<?= $backgroundPath ?>');"></div>
        <?php endif; ?>

        <div class="border-outer"></div>
        <div class="border-inner"></div>

        <div class="certificate-content">
            <span class="cert-number"><?= $certNumber ?? '' ?></span>
            <span class="cert-date"><?= date('d F Y') ?></span>

            <div class="cert-header">CERTIFICATE</div>
            <div class="cert-subheader">This certifies that</div>

            <div class="cert-name">
                <?= esc($user['nama_lengkap'] ?? 'Nama Student') ?>
            </div>

            <div class="cert-training-text">
                has successfully attended and completed
            </div>

            <div class="cert-minutes">
                <strong><?= $totalMinutes ?? 120 ?></strong> minutes online training program for
            </div>

            <div class="cert-course">
                <?= esc($produk['judul'] ?? 'Judul Kelas') ?>
            </div>

            <!-- Nama Admin Pemateri - TENGAH SECARA VERTIKAL & HORIZONTAL -->
            <div class="cert-instructor">
                <div class="cert-instructor-name">
                    <?php
                    // Tampilkan semua pemateri jika ada
                    if (!empty($allAdmins) && count($allAdmins) > 1) {
                        echo implode(' & ', $allAdmins);
                    } else {
                        echo esc($pemateri ?? 'Nama Admin Pemateri');
                    }
                    ?>
                </div>
                <div class="cert-instructor-title">
                    <u>Instructor / Pemateri</u>
                </div>
            </div>

            <div class="cert-seal">
                <div class="cert-seal-inner">
                    ZVD<br>CERTIFIED
                </div>
            </div>
        </div>
    </div>

    <?php if (isset($isUnlocked) && $isUnlocked && $hasPassed): ?>
    <!-- ====== DOWNLOAD BUTTON ====== -->
    <a href="<?= base_url('/sertifikat/download/' . $user['id_user'] . '/' . $produk['id_produk']) ?>" class="btn-download">
        ⬇️ Download Sertifikat (PDF)
    </a>
    <?php endif; ?>

</div>
</body>
</html>