<?= $this->extend('student/layouts/main') ?>

<?= $this->section('content') ?>

<!-- Flash Messages -->
<?php if (session()->getFlashdata('success')): ?>
<div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-2xl flex items-center gap-3 animate-fade-in">
    <div class="w-10 h-10 rounded-xl bg-emerald-500 flex items-center justify-center flex-shrink-0">
        <i class="ph-fill ph-check-circle text-white text-lg"></i>
    </div>
    <p class="text-emerald-700 font-medium"><?= session()->getFlashdata('success') ?></p>
</div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
<div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-2xl flex items-center gap-3 animate-fade-in">
    <div class="w-10 h-10 rounded-xl bg-red-500 flex items-center justify-center flex-shrink-0">
        <i class="ph-fill ph-warning-circle text-white text-lg"></i>
    </div>
    <p class="text-red-700 font-medium"><?= session()->getFlashdata('error') ?></p>
</div>
<?php endif; ?>

<!-- ==================== WELCOME SECTION ==================== -->
<div class="mb-6 lg:mb-8 animate-slide-up">
    <h1 class="text-2xl lg:text-3xl font-bold text-slate-800">
        Halo, <?= esc(session()->get('nama_lengkap')) ?>! 👋
    </h1>
    <p class="text-slate-500 mt-1">Lanjutkan perjalanan belajarmu dan raih kesuksesan.</p>
</div>

<!-- ==================== CONTINUE LEARNING BANNER ==================== -->
<?php
$continueClass = null;
foreach ($enrolledClasses as $kelas) {
    if (isset($kelas['progress']) && $kelas['progress'] > 0 && $kelas['progress'] < 100) {
        $continueClass = $kelas;
        break;
    }
}
?>

<?php if ($continueClass): ?>
<div class="mb-6 lg:mb-8 animate-slide-up" style="animation-delay: 50ms;">
    <div class="quick-actions-card">
        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center gap-4 lg:gap-6">
            <!-- Icon -->
            <div class="flex-shrink-0">
                <div class="w-14 h-14 rounded-2xl bg-white/20 flex items-center justify-center backdrop-blur-sm">
                    <i class="ph-fill ph-book-open-text text-2xl"></i>
                </div>
            </div>

            <!-- Content -->
            <div class="flex-1">
                <p class="text-blue-200 text-sm font-medium mb-1">Lanjutkan Belajar</p>
                <h2 class="text-lg lg:text-xl font-bold mb-2"><?= esc($continueClass['judul'] ?? 'Kelas') ?></h2>

                <!-- Progress Bar -->
                <div class="flex items-center gap-3">
                    <div class="flex-1 h-2 bg-white/20 rounded-full overflow-hidden">
                        <div class="h-full bg-white rounded-full transition-all duration-500" style="width: <?= $continueClass['progress'] ?? 0 ?>%"></div>
                    </div>
                    <span class="text-sm font-bold"><?= $continueClass['progress'] ?? 0 ?>%</span>
                </div>
            </div>

            <!-- CTA Button -->
            <a href="<?= base_url('student/kelas/' . ($continueClass['id_produk'] ?? '')) ?>"
               class="flex-shrink-0 flex items-center justify-center gap-2 px-5 py-3 bg-white text-blue-600 rounded-xl font-semibold hover:bg-blue-50 transition-colors shadow-lg">
                <span>Lanjutkan</span>
                <i class="ph-fill ph-arrow-right"></i>
            </a>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- ==================== MY CLASSES SECTION ==================== -->
<div class="animate-slide-up" style="animation-delay: 100ms;">
    <div class="flex items-center justify-between mb-4 lg:mb-6">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-primary-light flex items-center justify-center">
                <i class="ph-fill ph-books text-primary text-lg"></i>
            </div>
            <div>
                <h2 class="text-lg font-bold text-slate-800">Kelas Saya</h2>
                <p class="text-sm text-slate-500">
                    <?= count($enrolledClasses) ?> kelas terdaftar
                </p>
            </div>
        </div>
    </div>

    <?php if (empty($enrolledClasses)): ?>
    <!-- ==================== EMPTY STATE ==================== -->
    <div class="card">
        <div class="card-body">
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="ph-fill ph-graduation-cap"></i>
                </div>
                <h3 class="empty-state-title">Belum Ada Kelas</h3>
                <p class="empty-state-description">
                    Anda belum terdaftar di kelas manapun. Yuk mulai perjalanan belajar Anda dengan memilih kelas yang tersedia.
                </p>
                <a href="<?= base_url('/') ?>"
                   class="btn btn-primary mt-4">
                    <i class="ph-fill ph-storefront"></i>
                    <span>Lihat Katalog Kelas</span>
                </a>
            </div>
        </div>
    </div>

    <?php else: ?>
    <!-- ==================== COURSE GRID ==================== -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 lg:gap-6">
        <?php foreach($enrolledClasses as $kelas): ?>
        <?php
        $gambarPath = $kelas['gambar'] ?? '';
        $gambarUrl = base_url($gambarPath);
        $gambarExists = !empty($gambarPath) && file_exists(FCPATH . $gambarPath);
        $progress = $kelas['progress'] ?? 0;
        $isCompleted = $progress >= 100;
        $isStarted = $progress > 0;
        ?>

        <div class="card hover-lift animate-scale-in">
            <!-- Thumbnail -->
            <figure class="relative h-40 lg:h-48 overflow-hidden">
                <img src="<?= $gambarExists ? $gambarUrl : 'https://placehold.co/600x400/0C5CAB/FFFFFF?text=Kelas' ?>"
                     alt="<?= esc($kelas['judul'] ?? 'Kelas') ?>"
                     class="w-full h-full object-cover">

                <!-- Price Badge -->
                <?php if (($kelas['harga_promo'] ?? 0) == 0): ?>
                <div class="absolute top-3 right-3 px-3 py-1.5 bg-emerald-500 text-white text-xs font-bold rounded-lg shadow-lg">
                    GRATIS
                </div>
                <?php else: ?>
                <div class="absolute top-3 right-3 px-3 py-1.5 bg-white text-slate-800 text-xs font-bold rounded-lg shadow-lg">
                    Rp <?= number_format($kelas['harga_promo'], 0, ',', '.') ?>
                </div>
                <?php endif; ?>

                <!-- Completed Badge -->
                <?php if ($isCompleted): ?>
                <div class="absolute top-3 left-3 px-3 py-1.5 bg-emerald-500 text-white text-xs font-bold rounded-lg flex items-center gap-1 shadow-lg">
                    <i class="ph-fill ph-check-circle"></i>
                    <span>Selesai</span>
                </div>
                <?php endif; ?>
            </figure>

            <!-- Content -->
            <div class="card-body">
                <!-- Title -->
                <h3 class="font-bold text-slate-800 line-clamp-2 mb-2">
                    <?= esc($kelas['judul'] ?? '-') ?>
                </h3>

                <!-- Description -->
                <p class="text-sm text-slate-500 line-clamp-2 mb-4">
                    <?= strip_tags($kelas['deskripsi'] ?? 'Tidak ada deskripsi') ?>
                </p>

                <!-- Progress Section -->
                <div class="mb-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-medium text-slate-500">Progress</span>
                        <span class="text-sm font-bold <?= $isCompleted ? 'text-emerald-600' : 'text-primary' ?>">
                            <?= $progress ?>%
                        </span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-bar-fill <?= $isCompleted ? 'bg-success' : 'bg-primary' ?>"
                             style="width: <?= $progress ?>%"></div>
                    </div>
                </div>

                <!-- Action Button -->
                <a href="<?= base_url('student/kelas/' . ($kelas['id_produk'] ?? '')) ?>"
                   class="btn w-full <?= $isCompleted ? 'btn-success' : ($isStarted ? 'btn-primary' : 'btn-secondary') ?>">
                    <?php if ($isCompleted): ?>
                    <i class="ph-fill ph-arrow-clockwise"></i>
                    <span>Pelajari Kembali</span>
                    <?php elseif ($isStarted): ?>
                    <i class="ph-fill ph-play"></i>
                    <span>Lanjutkan</span>
                    <?php else: ?>
                    <i class="ph-fill ph-book-open"></i>
                    <span>Mulai Belajar</span>
                    <?php endif; ?>
                </a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<!-- ==================== QUICK STATS ==================== -->
<?php if (!empty($enrolledClasses)): ?>
<div class="stats-grid mt-8 animate-slide-up" style="animation-delay: 200ms;">
    <?php
    $completedCount = 0;
    $inProgressCount = 0;
    foreach ($enrolledClasses as $kelas) {
        $progress = $kelas['progress'] ?? 0;
        if ($progress >= 100) $completedCount++;
        elseif ($progress > 0) $inProgressCount++;
    }
    ?>

    <!-- Total Classes -->
    <div class="stat-card">
        <div class="stat-icon text-primary">
            <i class="ph ph-books"></i>
        </div>
        <div class="stat-content">
            <p class="stat-label">Total Kelas</p>
            <p class="stat-value text-primary"><?= count($enrolledClasses) ?></p>
        </div>
    </div>

    <!-- Completed Classes -->
    <div class="stat-card">
        <div class="stat-icon text-success">
            <i class="ph ph-check-circle"></i>
        </div>
        <div class="stat-content">
            <p class="stat-label">Kelas Selesai</p>
            <p class="stat-value text-success"><?= $completedCount ?></p>
        </div>
    </div>

    <!-- In Progress -->
    <div class="stat-card">
        <div class="stat-icon text-warning">
            <i class="ph ph-hourglass"></i>
        </div>
        <div class="stat-content">
            <p class="stat-label">Sedang Dipelajari</p>
            <p class="stat-value text-warning"><?= $inProgressCount ?></p>
        </div>
    </div>

    <!-- Certificates -->
    <div class="stat-card">
        <div class="stat-icon" style="background: #EDE9FE; color: #7C3AED;">
            <i class="ph ph-certificate"></i>
        </div>
        <div class="stat-content">
            <p class="stat-label">Sertifikat</p>
            <p class="stat-value" style="color: #7C3AED;"><?= $completedCount ?></p>
        </div>
    </div>
</div>
<?php endif; ?>

<?= $this->endSection() ?>