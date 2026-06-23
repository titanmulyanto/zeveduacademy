<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="page-header">
    <div>
        <h1 class="page-title">Manajemen User</h1>
        <p class="page-subtitle">Kelola semua pengguna platform ZevedU Academy</p>
    </div>
    <div class="page-actions">
        <select class="select" onchange="window.location.href='?role='+this.value" style="min-width: 160px;">
            <option value="">Semua Peran</option>
            <option value="student" <?= ($roleFilter ?? '') == 'student' ? 'selected' : '' ?>>Student</option>
            <option value="admin" <?= ($roleFilter ?? '') == 'admin' ? 'selected' : '' ?>>Admin</option>
            <option value="super_admin" <?= ($roleFilter ?? '') == 'super_admin' ? 'selected' : '' ?>>Super Admin</option>
        </select>
        <button class="btn btn-primary" onclick="add_user_modal.showModal()">
            <i class="ph ph-user-plus"></i>
            Tambah User
        </button>
    </div>
</div>

<!-- Alert Messages -->
<?php if (session()->getFlashdata('success')): ?>
<div class="alert alert-success mb-6 animate-slide-up">
    <div class="alert-icon">
        <i class="ph-fill ph-check-circle"></i>
    </div>
    <div>
        <p class="font-medium">Berhasil!</p>
        <p class="text-sm opacity-80"><?= session()->getFlashdata('success') ?></p>
    </div>
</div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
<div class="alert alert-error mb-6 animate-slide-up">
    <div class="alert-icon">
        <i class="ph-fill ph-warning-circle"></i>
    </div>
    <div>
        <p class="font-medium">Error!</p>
        <p class="text-sm opacity-80"><?= session()->getFlashdata('error') ?></p>
    </div>
</div>
<?php endif; ?>

<!-- Users Table Card -->
<div class="card animate-slide-up">
    <div class="card-body p-0">
        <?php if (empty($users)): ?>
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="ph ph-users"></i>
                </div>
                <h3 class="empty-state-title">Tidak ada data user</h3>
                <p class="empty-state-description">Belum ada pengguna yang sesuai dengan filter yang dipilih.</p>
                <button class="btn btn-primary" onclick="add_user_modal.showModal()">
                    <i class="ph ph-user-plus"></i>
                    Tambah User Baru
                </button>
            </div>
        <?php else: ?>
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Peran</th>
                            <th>Instansi</th>
                            <th>WhatsApp</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($users as $user): ?>
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="avatar avatar-md">
                                        <?php if (!empty($user['foto_profil'])): ?>
                                            <div class="avatar-content">
                                                <img src="<?= base_url($user['foto_profil']) ?>" alt="" class="w-full h-full object-cover" />
                                            </div>
                                        <?php else: ?>
                                            <div class="avatar-content bg-primary text-white">
                                                <?= strtoupper(substr($user['nama_lengkap'] ?? 'U', 0, 1)) ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-slate-800"><?= esc($user['nama_lengkap'] ?? '-') ?></p>
                                        <p class="text-xs text-slate-500"><?= esc($user['email'] ?? '-') ?></p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <?php
                                $role = $user['role'] ?? 'student';
                                $badgeClass = match($role) {
                                    'super_admin' => 'badge-error',
                                    'admin' => 'badge-warning',
                                    default => 'badge-info'
                                };
                                $roleLabel = match($role) {
                                    'super_admin' => 'Super Admin',
                                    'admin' => 'Admin',
                                    default => 'Student'
                                };
                                ?>
                                <span class="badge <?= $badgeClass ?>"><?= $roleLabel ?></span>
                            </td>
                            <td class="text-slate-600"><?= esc($user['instansi'] ?? '-') ?></td>
                            <td class="text-slate-600">
                                <?php if (!empty($user['no_whatsapp'])): ?>
                                    <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $user['no_whatsapp']) ?>"
                                       target="_blank"
                                       class="flex items-center gap-2 text-success hover:underline">
                                        <i class="ph ph-whatsapp-logo"></i>
                                        <?= esc($user['no_whatsapp']) ?>
                                    </a>
                                <?php else: ?>
                                    <span class="text-slate-400">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="flex justify-center gap-2">
                                    <a href="<?= base_url('admin/users/edit/' . ($user['id_user'] ?? '')) ?>"
                                       class="btn btn-sm btn-ghost text-primary hover:bg-primary-light"
                                       title="Edit">
                                        <i class="ph ph-note-pencil text-lg"></i>
                                    </a>
                                    <a href="<?= base_url('admin/users/view/' . ($user['id_user'] ?? '')) ?>"
                                       class="btn btn-sm btn-ghost text-info hover:bg-info-light"
                                       title="Lihat Detail">
                                        <i class="ph ph-eye text-lg"></i>
                                    </a>
                                    <?php if(($user['role'] ?? '') != 'super_admin'): ?>
                                    <a href="<?= base_url('admin/users/delete/' . ($user['id_user'] ?? '')) ?>"
                                       class="btn btn-sm btn-ghost text-error hover:bg-error-light"
                                       title="Hapus"
                                       onclick="return confirm('Apakah Anda yakin ingin menghapus user ini?')">
                                        <i class="ph ph-trash text-lg"></i>
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal Tambah User - UPDATED WITH MORE FIELDS -->
<dialog id="add_user_modal" class="modal">
    <div class="modal-box max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-12 h-12 rounded-xl bg-primary-light flex items-center justify-center">
                <i class="ph ph-user-plus text-primary text-xl"></i>
            </div>
            <div>
                <h3 class="modal-title mb-1">Tambah User Baru</h3>
                <p class="text-sm text-slate-500">Lengkapi form di bawah untuk menambahkan user baru</p>
            </div>
        </div>

        <form action="<?= base_url('admin/users') ?>" method="POST" enctype="multipart/form-data" class="space-y-4">
            <?= csrf_field() ?>

            <!-- Basic Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-medium">Nama Lengkap</span>
                    </label>
                    <input type="text" name="nama_lengkap" placeholder="Masukkan nama lengkap"
                           class="input" required />
                </div>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-medium">Email</span>
                    </label>
                    <input type="email" name="email" placeholder="email@contoh.com"
                           class="input" required />
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-medium">Password</span>
                    </label>
                    <input type="password" name="password" placeholder="Min. 8 karakter"
                           class="input" required minlength="8" />
                </div>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-medium">Role</span>
                    </label>
                    <select name="role" id="user_role_select" class="select" onchange="toggleKelasSelection()">
                        <option value="student" selected>Student</option>
                        <option value="admin">Admin (Pemateri)</option>
                        <option value="super_admin">Super Admin</option>
                    </select>
                </div>
            </div>

            <!-- NEW: Jenis Kelamin -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-medium">Jenis Kelamin</span>
                    </label>
                    <select name="jenis_kelamin" class="select">
                        <option value="">-- Pilih --</option>
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>
                <!-- NEW: Pendidikan Terakhir -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-medium">Pendidikan Terakhir</span>
                    </label>
                    <select name="pendidikan_terakhir" class="select">
                        <option value="">-- Pilih --</option>
                        <option value="SMA/SMK">SMA/SMK</option>
                        <option value="D1">D1</option>
                        <option value="D2">D2</option>
                        <option value="D3">D3</option>
                        <option value="D4">D4</option>
                        <option value="S1">S1</option>
                        <option value="S2">S2</option>
                        <option value="S3">S3</option>
                    </select>
                </div>
            </div>

            <!-- NEW: Tanggal Lahir -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-medium">Tanggal Lahir</span>
                    </label>
                    <input type="date" name="tanggal_lahir" class="input" />
                </div>
                <!-- NEW: Foto Profil -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-medium">Foto Profil</span>
                    </label>
                    <input type="file" name="foto_profil" class="file-input file-input-bordered w-full" accept="image/*" />
                    <label class="label">
                        <span class="label-text-alt text-slate-400">Format: JPG, PNG. Max: 2MB</span>
                    </label>
                </div>
            </div>

            <!-- Kelas Selection - Only for Student -->
            <div class="form-control" id="kelas_selection">
                <label class="label">
                    <span class="label-text font-medium text-primary">Kelas / Produk</span>
                    <span class="label-text-alt text-slate-400">(Opsional)</span>
                </label>
                <select name="id_produk" class="select">
                    <option value="">-- Pilih Kelas --</option>
                    <?php if (!empty($kelas_list)): ?>
                        <?php foreach($kelas_list as $kelas): ?>
                            <option value="<?= $kelas['id_produk'] ?? '' ?>"><?= esc($kelas['judul'] ?? '') ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
                <label class="label">
                    <span class="label-text-alt text-slate-400">Pilih kelas untuk memberikan akses langsung ke student</span>
                </label>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-medium">No. WhatsApp</span>
                    </label>
                    <input type="text" name="no_whatsapp" placeholder="08xxxxxxxxxx"
                           class="input" />
                </div>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-medium">Instansi</span>
                    </label>
                    <input type="text" name="instansi" placeholder="Sekolah/Universitas/Perusahaan"
                           class="input" />
                </div>
            </div>

            <div class="form-control">
                <label class="label">
                    <span class="label-text font-medium">Alamat</span>
                </label>
                <textarea name="alamat" class="textarea" rows="2"
                          placeholder="Alamat lengkap (opsional)"></textarea>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn btn-ghost" onclick="add_user_modal.close()">Batal</button>
                <button type="submit" class="btn btn-primary">
                    <i class="ph ph-check mr-2"></i>
                    Simpan User
                </button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop"></form>
</dialog>

<script>
    function toggleKelasSelection() {
        var role = document.getElementById('user_role_select').value;
        var kelasSection = document.getElementById('kelas_selection');

        if (role === 'student') {
            kelasSection.style.display = 'block';
        } else {
            kelasSection.style.display = 'none';
        }
    }
</script>

<?= $this->endSection() ?>
