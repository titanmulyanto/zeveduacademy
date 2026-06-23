<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<!-- Breadcrumbs -->
<div class="breadcrumbs-modern mb-4">
    <div class="breadcrumbs-modern-item">
        <a href="<?= base_url('admin/materi') ?>">
            <i class="ph ph-books"></i>
            <span>Materi & Tes</span>
        </a>
    </div>
    <span class="breadcrumbs-modern-separator">
        <i class="ph ph-caret-right"></i>
    </span>
    <div class="breadcrumbs-modern-item">
        <span class="breadcrumbs-modern-current">Buat Ujian Baru</span>
    </div>
</div>

<!-- Page Header -->
<div class="page-header">
    <div>
        <h1 class="page-title">Buat Ujian Sertifikasi</h1>
        <p class="page-subtitle">Buat ujian sertifikasi untuk kelas</p>
    </div>
</div>

<!-- Alert Messages -->
<?php if (session()->getFlashdata('error')): ?>
<div class="alert alert-error mb-6 animate-slide-up">
    <div class="alert-icon">
        <i class="ph-fill ph-warning-circle"></i>
    </div>
    <span><?= session()->getFlashdata('error') ?></span>
</div>
<?php endif; ?>

<div class="card animate-slide-up">
    <div class="card-body">
        <form action="<?= base_url('admin/ujian') ?>" method="POST">
            <?= csrf_field() ?>

            <!-- Header Icon -->
            <div class="flex items-center gap-4 mb-6 pb-6 border-b border-slate-200">
                <div class="w-14 h-14 rounded-xl bg-primary-light flex items-center justify-center">
                    <i class="ph ph-exam text-primary text-2xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-slate-900">Form Buat Ujian</h3>
                    <p class="text-sm text-slate-500">Lengkapi informasi di bawah untuk membuat ujian baru</p>
                </div>
            </div>

            <div class="space-y-6">
                <!-- Select Class -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-medium">Pilih Kelas</span>
                    </label>
                    <select name="id_produk" class="select" required>
                        <option value="">-- Pilih Kelas --</option>
                        <?php if (!empty($classes)): ?>
                            <?php foreach ($classes as $cls): ?>
                                <option value="<?= $cls['id_produk'] ?? '' ?>" <?= (isset($produkId) && $produkId == ($cls['id_produk'] ?? '')) ? 'selected' : '' ?>>
                                    <?= esc($cls['judul'] ?? '') ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <label class="label">
                        <span class="label-text-alt text-warning flex items-center gap-1">
                            <i class="ph ph-info"></i>
                            Pilih kelas yang akan diberikan ujian sertifikasi
                        </span>
                    </label>
                </div>

                <!-- Exam Name -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-medium">Nama Ujian</span>
                    </label>
                    <input type="text" name="judul_ujian" value="Ujian Sertifikasi"
                           class="input"
                           placeholder="Contoh: Ujian Akhir Semester"
                           required />
                </div>

                <!-- Settings Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Durasi (menit)</span>
                        </label>
                        <div class="relative">
                            <input type="number" name="durasi_menit" value="60"
                                   class="input pr-12" min="1" required />
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm">min</span>
                        </div>
                        <label class="label">
                            <span class="label-text-alt text-slate-400">Waktu pengerjaan ujian</span>
                        </label>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Nilai Minimal Lulus</span>
                        </label>
                        <div class="relative">
                            <input type="number" name="nilai_minimal_lulus" value="70"
                                   class="input pr-8" min="0" max="100" required />
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm">%</span>
                        </div>
                        <label class="label">
                            <span class="label-text-alt text-slate-400">Nilai minimum untuk lulus</span>
                        </label>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Min. Progress (%)</span>
                        </label>
                        <div class="relative">
                            <input type="number" name="minimal_progress_persen" value="100"
                                   class="input pr-8" min="0" max="100" required />
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm">%</span>
                        </div>
                        <label class="label">
                            <span class="label-text-alt text-warning flex items-center gap-1">
                                <i class="ph ph-warning"></i>
                                Progress materi harus selesai
                            </span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-slate-200">
                <a href="<?= base_url('admin/materi') ?>" class="btn btn-ghost">
                    <i class="ph ph-arrow-left mr-2"></i>
                    Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="ph ph-plus mr-2"></i>
                    Buat Ujian
                </button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>