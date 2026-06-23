<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<!-- Breadcrumbs -->
<div class="breadcrumbs-modern mb-4">
    <div class="breadcrumbs-modern-item">
        <a href="<?= base_url('admin/users') ?>">
            <i class="ph ph-users"></i>
            <span>Manajemen User</span>
        </a>
    </div>
    <span class="breadcrumbs-modern-separator">
        <i class="ph ph-caret-right"></i>
    </span>
    <div class="breadcrumbs-modern-item">
        <span class="breadcrumbs-modern-current">Edit User</span>
    </div>
</div>

<!-- Page Header -->
<div class="page-header">
    <div>
        <h1 class="page-title">Edit User</h1>
        <p class="page-subtitle">Perbarui informasi pengguna</p>
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
        <form action="<?= base_url('admin/users/update/' . ($user['id_user'] ?? '')) ?>" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Left Column -->
                <div class="space-y-4">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Nama Lengkap</span>
                        </label>
                        <input type="text" name="nama_lengkap"
                               value="<?= esc($user['nama_lengkap'] ?? '') ?>"
                               class="input" required />
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Email</span>
                        </label>
                        <input type="email"
                               value="<?= esc($user['email'] ?? '') ?>"
                               class="input bg-slate-50" disabled />
                        <label class="label">
                            <span class="label-text-alt text-slate-400">Email tidak dapat diubah</span>
                        </label>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Role</span>
                        </label>
                        <select name="role" class="select" required>
                            <?php if (!empty($roles)): ?>
                                <?php foreach ($roles as $r): ?>
                                    <option value="<?= $r ?>" <?= ($user['role'] ?? '') == $r ? 'selected' : '' ?>>
                                        <?= ucfirst($r) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="student" <?= ($user['role'] ?? '') == 'student' ? 'selected' : '' ?>>Student</option>
                                <option value="admin" <?= ($user['role'] ?? '') == 'admin' ? 'selected' : '' ?>>Admin</option>
                                <option value="super_admin" <?= ($user['role'] ?? '') == 'super_admin' ? 'selected' : '' ?>>Super Admin</option>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Password Baru</span>
                            <span class="label-text-alt text-slate-400">(Opsional)</span>
                        </label>
                        <input type="password" name="password" class="input"
                               placeholder="Kosongkan jika tidak diubah" />
                        <label class="label">
                            <span class="label-text-alt text-slate-400">Minimal 8 karakter</span>
                        </label>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-4">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Instansi</span>
                        </label>
                        <input type="text" name="instansi"
                               value="<?= esc($user['instansi'] ?? '') ?>"
                               class="input"
                               placeholder="Sekolah/Universitas" />
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">No. WhatsApp</span>
                        </label>
                        <input type="text" name="no_whatsapp"
                               value="<?= esc($user['no_whatsapp'] ?? '') ?>"
                               class="input"
                               placeholder="08xxxxxxxxxx" />
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Alamat</span>
                        </label>
                        <textarea name="alamat" class="textarea" rows="2"
                                  placeholder="Alamat lengkap"><?= esc($user['alamat'] ?? '') ?></textarea>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Foto Profil</span>
                        </label>
                        <?php if (!empty($user['foto_profil'])): ?>
                            <div class="mb-3">
                                <div class="avatar">
                                    <div class="w-20 h-20 rounded-xl">
                                        <img src="<?= base_url($user['foto_profil']) ?>"
                                             alt="Current profile"
                                             class="object-cover" />
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <input type="file" name="foto_profil"
                               class="file-input file-input-bordered w-full"
                               accept="image/*" />
                        <label class="label">
                            <span class="label-text-alt text-slate-400">Format: JPG, PNG. Maks 2MB</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-slate-200">
                <a href="<?= base_url('admin/users') ?>" class="btn btn-ghost">
                    <i class="ph ph-arrow-left mr-2"></i>
                    Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="ph ph-check mr-2"></i>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>