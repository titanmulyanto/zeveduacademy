<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="page-header">
    <div>
        <h1 class="page-title">Manajemen Kurikulum</h1>
        <p class="page-subtitle">Kelola kelas dan materi pelatihan</p>
    </div>
    <?php if(isset($userRole) && $userRole === 'super_admin'): ?>
    <div class="page-actions">
        <a href="<?= base_url('admin/materi/create') ?>" class="btn btn-primary">
            <i class="ph ph-plus"></i>
            Tambah Kelas Baru
        </a>
    </div>
    <?php endif; ?>
</div>

<!-- Alert Messages -->
<?php if (session()->getFlashdata('success')): ?>
<div class="alert alert-success mb-6 animate-slide-up">
    <div class="alert-icon">
        <i class="ph-fill ph-check-circle"></i>
    </div>
    <span><?= session()->getFlashdata('success') ?></span>
</div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
<div class="alert alert-error mb-6 animate-slide-up">
    <div class="alert-icon">
        <i class="ph-fill ph-warning-circle"></i>
    </div>
    <span><?= session()->getFlashdata('error') ?></span>
</div>
<?php endif; ?>

<!-- Classes Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <?php if (empty($classes)): ?>
        <div class="col-span-full">
            <div class="card">
                <div class="card-body">
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="ph ph-books"></i>
                        </div>
                        <h3 class="empty-state-title">Belum Ada Kelas</h3>
                        <p class="empty-state-description">
                            Kelas belum ditugaskan kepada Anda. Hubungi Super Admin untuk mendapatkan akses.
</p>
                        <?php if(isset($userRole) && $userRole === 'super_admin'): ?>
                        <a href="<?= base_url('admin/materi/create') ?>" class="btn btn-primary mt-4">
                            <i class="ph ph-plus"></i>
                            Buat Kelas Baru
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <?php foreach($classes as $item): ?>
        <div class="card hover-lift animate-scale-in">
            <!-- Thumbnail -->
            <figure class="relative h-48 overflow-hidden">
                <img src="<?= $item['gambar'] ?? 'https://placehold.co/600x400/0C5CAB/FFFFFF?text=Kelas' ?>"
                     alt="<?= esc($item['judul'] ?? 'Kelas') ?>"
                     class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" />
                <div class="absolute top-3 right-3">
                    <span class="badge badge-primary"><?= esc($item['nama_kategori'] ?? 'Tanpa Kategori') ?></span>
                </div>
            </figure>

            <!-- Content -->
            <div class="card-body">
                <h3 class="font-bold text-lg text-slate-900 line-clamp-2 mb-2">
                    <?= esc($item['judul'] ?? '-') ?>
                </h3>
                <p class="text-sm text-slate-500 line-clamp-2 mb-4">
                    <?= strip_tags($item['deskripsi'] ?? 'Tidak ada deskripsi') ?>
                </p>

                <!-- Stats -->
                <div class="flex items-center gap-4 text-xs text-slate-500 mb-4">
                    <span class="flex items-center gap-1">
                        <i class="ph ph-video-camera"></i>
                        <?= $item['total_video'] ?? 0 ?> Video
                    </span>
                    <span class="flex items-center gap-1">
                        <i class="ph ph-file-text"></i>
                        <?= $item['total_materi'] ?? 0 ?> Materi
                    </span>
                    <span class="flex items-center gap-1">
                        <i class="ph ph-users"></i>
                        <?= $item['total_student'] ?? 0 ?> Student
                    </span>
                </div>

                <!-- Actions -->
                <div class="card-actions">
                    <a href="<?= base_url('admin/materi/detail/' . ($item['id_produk'] ?? '')) ?>"
                       class="btn btn-primary flex-1">
                        <i class="ph ph-books"></i>
                        Kelola Kurikulum
                    </a>
                    <?php if(isset($userRole) && $userRole === 'super_admin'): ?>
                    <a href="<?= base_url('admin/materi/edit/' . ($item['id_produk'] ?? '')) ?>"
                       class="btn btn-ghost"
                       title="Edit">
                        <i class="ph ph-pencil text-lg"></i>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>