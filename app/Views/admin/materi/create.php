<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<!-- Breadcrumbs -->
<div class="breadcrumbs-modern mb-4">
    <div class="breadcrumbs-modern-item">
        <a href="<?= base_url('admin/materi') ?>">
            <i class="ph ph-books"></i>
            <span>Manajemen Kurikulum</span>
        </a>
    </div>
    <span class="breadcrumbs-modern-separator">
        <i class="ph ph-caret-right"></i>
    </span>
    <div class="breadcrumbs-modern-item">
        <span class="breadcrumbs-modern-current">Tambah Kelas</span>
    </div>
</div>

<!-- Page Header -->
<div class="page-header">
    <div>
        <h1 class="page-title">
            <?php if (($classType ?? '') === 'paid'): ?>
                <span class="text-primary"><i class="ph ph-currency-dollar"></i></span>
                Tambah Kelas Berbayar
            <?php elseif (($classType ?? '') === 'free'): ?>
                <span class="text-success"><i class="ph ph-gift"></i></span>
                Tambah Kelas Gratis
            <?php else: ?>
                Tambah Kelas Baru
            <?php endif; ?>
        </h1>
        <p class="page-subtitle">Buat kelas pelatihan baru di platform</p>
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
        <form action="<?= base_url('admin/materi/store') ?>" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Left Column -->
                <div class="space-y-6">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Nama Kelas</span>
                        </label>
                        <input type="text" name="judul" class="input"
                               placeholder="Contoh: Web Programming Fundamentals"
                               required />
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Kategori</span>
                        </label>
                        <select name="id_kategori" class="select" required>
                            <option value="">-- Pilih Kategori --</option>
                            <?php if (!empty($kategoriProduk)): ?>
                                <?php foreach ($kategoriProduk as $kat): ?>
                                    <option value="<?= $kat['id_kategori'] ?? '' ?>"><?= esc($kat['nama_kategori'] ?? '') ?></option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="1">Umum</option>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Pemateri / Guru</span>
                        </label>
                        <select name="id_admin" class="select">
                            <option value="">-- Pilih Pemateri --</option>
                            <?php if (!empty($admins)): ?>
                                <?php foreach ($admins as $admin): ?>
                                    <option value="<?= $admin['id_user'] ?? '' ?>"><?= esc($admin['nama_lengkap'] ?? '') ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <label class="label">
                            <span class="label-text-alt text-slate-400">Pilih admin yang akan mengelola kelas ini</span>
                        </label>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Harga Awal (Rp)</span>
                        </label>
                        <input type="number" name="harga_awal" class="input"
                               placeholder="500000" min="0" />
                        <label class="label">
                            <span class="label-text-alt text-slate-400">Harga sebelum diskon</span>
                        </label>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Harga Promo (Rp)</span>
                        </label>
                        <input type="number" name="harga_promo" class="input"
                               placeholder="350000" min="0"
                               value="<?= ($classType ?? '') === 'free' ? '0' : '' ?>" />
                        <?php if (($classType ?? '') === 'free'): ?>
                            <label class="label">
                                <span class="label-text-alt text-success flex items-center gap-1">
                                    <i class="ph ph-check-circle"></i>
                                    Kelas ini akan GRATIS
                                </span>
                            </label>
                        <?php else: ?>
                            <label class="label">
                                <span class="label-text-alt text-info flex items-center gap-1">
                                    <i class="ph ph-info"></i>
                                    Isi 0 untuk kelas gratis
                                </span>
                            </label>
                        <?php endif; ?>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Gambar Kelas</span>
                        </label>
                        <input type="file" name="gambar" class="file-input file-input-bordered w-full"
                               accept="image/*" />
                        <label class="label">
                            <span class="label-text-alt text-slate-400">Rasio 16:9, format JPG/PNG</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="divider"></div>

            <div class="form-control">
                <label class="label">
                    <span class="label-text font-medium">Deskripsi</span>
                </label>
                <textarea name="deskripsi" class="textarea" rows="4"
                          placeholder="Deskripsi lengkap tentang kelas ini..."></textarea>
            </div>

            <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-slate-200">
                <a href="<?= base_url('admin/materi') ?>" class="btn btn-ghost">
                    <i class="ph ph-arrow-left mr-2"></i>
                    Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="ph ph-check mr-2"></i>
                    Simpan Kelas
                </button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>