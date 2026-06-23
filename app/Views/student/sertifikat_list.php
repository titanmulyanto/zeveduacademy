<?= $this->extend('student/layouts/main') ?>

<?= $this->section('content') ?>

<!-- Flash Messages -->
<?php if (session()->getFlashdata('success')): ?>
<div class="alert alert-success mb-4">
    <i class="ph ph-check-circle"></i>
    <span><?= session()->getFlashdata('success') ?></span>
</div>
<?php endif; ?>

<h1 class="text-2xl font-bold mb-6 flex items-center gap-2">
    <i class="ph ph-certificate text-primary"></i> Sertifikat Saya
</h1>

<?php if (empty($certificates)): ?>
<div class="card bg-base-200 shadow border border-base-300">
    <div class="card-body text-center py-16">
        <div class="bg-primary/10 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="ph ph-certificate text-4xl text-primary"></i>
        </div>
        <h2 class="text-xl font-bold text-base-content/70">Belum Ada Sertifikat</h2>
        <p class="text-base-content/50 mt-2 max-w-sm mx-auto">Selesaikan kelas dan lulus ujian sertifikasi untuk mendapatkan sertifikat.</p>
        <a href="<?= base_url('student') ?>" class="btn btn-primary mt-6 mx-auto">
            <i class="ph ph-book-open"></i> Mulai Belajar
        </a>
    </div>
</div>
<?php else: ?>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    <?php foreach ($certificates as $cert): ?>
    <div class="card bg-base-100 shadow border border-base-200">
        <div class="card-body">
            <!-- Certificate Status -->
            <?php if (!empty($cert['nomor_sertifikat'])): ?>
            <!-- Has Certificate -->
            <div class="flex items-start justify-between mb-4">
                <div class="bg-success/10 p-3 rounded-lg">
                    <i class="ph ph-certificate text-2xl text-success"></i>
                </div>
                <span class="badge badge-success badge-sm">Terbit</span>
            </div>
            <h3 class="font-bold truncate"><?= esc($cert['judul']) ?></h3>
            <p class="text-xs text-base-content/60 mt-1"><?= esc($cert['nomor_sertifikat']) ?></p>
            <p class="text-xs text-base-content/50 mt-1">
                <i class="ph ph-calendar"></i> <?= date('d M Y', strtotime($cert['tanggal_terbit'])) ?>
            </p>

            <!-- Actions -->
            <div class="card-actions justify-end mt-4">
                <a href="<?= base_url('student/sertifikat/' . $cert['id_produk']) ?>" class="btn btn-success btn-sm">
                    <i class="ph ph-eye"></i> Lihat
                </a>
            </div>
            <?php elseif (!empty($cert['id_ujian'])): ?>
            <!-- No Certificate but has exam -->
            <?php if ($cert['status_lulus'] === 'lulus'): ?>
            <!-- Passed but no cert yet -->
            <div class="flex items-start justify-between mb-4">
                <div class="bg-success/10 p-3 rounded-lg">
                    <i class="ph ph-certificate text-2xl text-success"></i>
                </div>
                <span class="badge badge-success badge-sm">
                    <i class="ph ph-check-circle"></i> Lulus
                </span>
            </div>
            <h3 class="font-bold truncate"><?= esc($cert['judul']) ?></h3>
            <p class="text-xs text-success mt-2">
                <i class="ph ph-trophy"></i> Nilai: <?= $cert['nilai'] ?>%
            </p>
            <p class="text-xs text-base-content/50 mt-1">
                <i class="ph ph-calendar"></i> <?= date('d M Y', strtotime($cert['tanggal_ujian'] ?? 'now')) ?>
            </p>
            <div class="card-actions justify-end mt-4">
                <a href="<?= base_url('student/sertifikat/' . $cert['id_produk']) ?>" class="btn btn-success btn-sm">
                    <i class="ph ph-certificate"></i> Ambil Sertifikat
                </a>
            </div>
            <?php elseif ($cert['status_lulus'] === 'tidak'): ?>
            <!-- Failed -->
            <div class="flex items-start justify-between mb-4">
                <div class="bg-error/10 p-3 rounded-lg">
                    <i class="ph ph-certificate text-2xl text-error"></i>
                </div>
                <span class="badge badge-error badge-sm">Belum Lulus</span>
            </div>
            <h3 class="font-bold truncate"><?= esc($cert['judul']) ?></h3>
            <p class="text-xs text-error mt-2">
                <i class="ph ph-warning"></i> Nilai: <?= $cert['nilai'] ?>% (Min: 70%)
            </p>
            <div class="card-actions justify-end mt-4">
                <a href="<?= base_url('student/retry-exam/' . $cert['id_ujian']) ?>" class="btn btn-error btn-sm">
                    <i class="ph ph-arrow-clockwise"></i> Remedial
                </a>
            </div>
            <?php else: ?>
            <!-- Has exam but not taken -->
            <div class="flex items-start justify-between mb-4">
                <div class="bg-warning/10 p-3 rounded-lg">
                    <i class="ph ph-certificate text-2xl text-warning"></i>
                </div>
                <span class="badge badge-warning badge-sm">Menunggu</span>
            </div>
            <h3 class="font-bold truncate"><?= esc($cert['judul']) ?></h3>
            <p class="text-xs text-base-content/50 mt-2">Ujian belum diambil</p>
            <div class="card-actions justify-end mt-4">
                <a href="<?= base_url('student/kelas/' . $cert['id_produk']) ?>" class="btn btn-primary btn-sm">
                    <i class="ph ph-book-open"></i> Mulai Ujian
                </a>
            </div>
            <?php endif; ?>
            <?php else: ?>
            <!-- No exam for this class -->
            <div class="flex items-start justify-between mb-4">
                <div class="bg-base-300 p-3 rounded-lg">
                    <i class="ph ph-certificate text-2xl text-base-content/40"></i>
                </div>
                <span class="badge badge-ghost badge-sm">Tanpa Ujian</span>
            </div>
            <h3 class="font-bold truncate"><?= esc($cert['judul']) ?></h3>
            <p class="text-xs text-base-content/50 mt-2">Tidak ada ujian untuk kelas ini</p>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<?= $this->endSection() ?>