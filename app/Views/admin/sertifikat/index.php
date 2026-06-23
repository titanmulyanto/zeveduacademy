<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="page-header">
    <div>
        <h1 class="page-title">Kelola Kelas & Sertifikat</h1>
        <p class="page-subtitle">Pengaturan kelas dan template sertifikat</p>
    </div>
    <div class="page-actions">
        <a href="<?= base_url('admin/materi/create?type=free') ?>" class="btn btn-success">
            <i class="ph ph-gift"></i>
            Kelas Gratis
        </a>
        <a href="<?= base_url('admin/materi/create?type=paid') ?>" class="btn btn-primary">
            <i class="ph ph-plus"></i>
            Kelas Berbayar
        </a>
    </div>
</div>

<!-- Info Box -->
<div class="alert alert-info mb-6 animate-slide-up">
    <div class="alert-icon">
        <i class="ph-fill ph-info"></i>
    </div>
    <div>
        <p class="font-semibold">Pengaturan Kelas & Sertifikat</p>
        <ul class="text-sm mt-1 space-y-1">
            <li>• Satu kelas bisa dikelola 2+ admin/pemateri</li>
            <li>• Upload background template sertifikat (rasio 16:10)</li>
            <li>• Sertifikat LOCKED: <code>assets/images/sertifikat_locked_general.jpg</code></li>
        </ul>
    </div>
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

<!-- Classes Table -->
<div class="card animate-slide-up">
    <div class="card-header">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-primary-light flex items-center justify-center">
                <i class="ph ph-books text-primary text-lg"></i>
            </div>
            <div>
                <h3 class="card-title">Daftar Kelas</h3>
                <p class="text-xs text-slate-500"><?= count($classes ?? []) ?> kelas ditemukan</p>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <?php if (empty($classes)): ?>
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="ph ph-books"></i>
                </div>
                <h3 class="empty-state-title">Belum Ada Kelas</h3>
                <p class="empty-state-description">Mulai dengan membuat kelas baru.</p>
                <a href="<?= base_url('admin/materi/create') ?>" class="btn btn-primary mt-4">
                    <i class="ph ph-plus"></i>
                    Tambah Kelas
                </a>
            </div>
        <?php else: ?>
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Kelas / Produk</th>
                            <th>Harga</th>
                            <th>Tipe</th>
                            <th>Admin/Pemateri</th>
                            <th>Template</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach($classes as $cls): ?>
                        <tr>
                            <td class="text-slate-500"><?= $no++ ?></td>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center overflow-hidden">
                                        <?php if (!empty($cls['gambar'])): ?>
                                            <img src="<?= base_url($cls['gambar']) ?>" alt="" class="w-full h-full object-cover">
                                        <?php else: ?>
                                            <i class="ph ph-books text-slate-400"></i>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-slate-800"><?= esc($cls['judul'] ?? '-') ?></p>
                                        <p class="text-xs text-slate-500"><?= esc($cls['nama_kategori'] ?? '-') ?></p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <?php if(($cls['harga_promo'] ?? 0) == 0): ?>
                                    <span class="badge badge-success font-semibold">GRATIS</span>
                                <?php else: ?>
                                    <span class="font-semibold text-success">Rp <?= number_format($cls['harga_promo'] ?? 0, 0, ',', '.') ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if(($cls['harga_promo'] ?? 0) == 0): ?>
                                    <span class="badge badge-info">FREE</span>
                                <?php else: ?>
                                    <span class="badge badge-warning">PAID</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="flex flex-wrap gap-1 mb-2">
                                    <?php if(!empty($cls['admins'])): ?>
                                        <?php foreach($cls['admins'] as $adm): ?>
                                            <span class="badge <?= ($adm['role'] ?? '') == 'pemateri' ? 'badge-primary' : 'badge-secondary' ?> gap-1">
                                                <?= esc(substr($adm['nama_lengkap'] ?? 'A', 0, 12)) ?>
                                                <?php if(($cls['admin_count'] ?? 0) > 1): ?>
                                                    <a href="<?= base_url('admin/sertifikat/remove-admin/' . ($adm['id'] ?? '')) ?>"
                                                       class="hover:text-error font-bold"
                                                       onclick="return confirm('Hapus admin ini?')">×</a>
                                                <?php endif; ?>
                                            </span>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <span class="text-slate-400 text-sm italic">Belum ada admin</span>
                                    <?php endif; ?>
                                </div>
                                <button onclick="openAddAdminModal(<?= $cls['id_produk'] ?? 0 ?>)"
                                        class="btn btn-xs btn-ghost text-primary">
                                    <i class="ph ph-plus mr-1"></i> Tambah
                                </button>
                            </td>
                            <td>
                                <?php if(!empty($cls['background_image'])): ?>
                                    <div class="avatar">
                                        <div class="w-12 h-12 rounded-lg border-2 border-success">
                                            <img src="<?= $cls['background_image'] ?>" alt="Template" class="object-cover">
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <span class="badge badge-ghost">Belum ada</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="dropdown dropdown-end">
                                    <button tabindex="0" class="btn btn-ghost btn-sm btn-square">
                                        <i class="ph ph-dots-three-vertical text-lg"></i>
                                    </button>
                                    <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow-xl bg-white border border-slate-200 rounded-xl w-52 mt-2">
                                        <li>
                                            <a href="<?= base_url('admin/materi/edit/' . ($cls['id_produk'] ?? '')) ?>"
                                               class="flex items-center gap-2 text-warning hover:bg-warning-light">
                                                <i class="ph ph-pencil"></i> Edit Kelas
                                            </a>
                                        </li>
                                        <?php if(!empty($cls['id_template'])): ?>
                                        <li>
                                            <a href="<?= base_url('admin/sertifikat/preview/' . ($cls['id_template'] ?? '')) ?>"
                                               target="_blank"
                                               class="flex items-center gap-2 text-info hover:bg-info-light">
                                                <i class="ph ph-eye"></i> Preview Sertifikat
                                            </a>
                                        </li>
                                        <?php endif; ?>
                                        <li>
                                            <a href="<?= base_url('admin/sertifikat/students/' . ($cls['id_produk'] ?? '')) ?>"
                                               class="flex items-center gap-2 text-info hover:bg-info-light">
                                                <i class="ph ph-users"></i> Lihat Student
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= base_url('admin/sertifikat/view-user-certificates/' . ($cls['id_produk'] ?? '')) ?>"
                                               class="flex items-center gap-2 text-success hover:bg-success-light">
                                                <i class="ph ph-certificate"></i> View Sertifikat User
                                            </a>
                                        </li>
                                        <li class="border-t border-slate-100 mt-1 pt-1">
                                            <button onclick="openUploadModal(<?= $cls['id_produk'] ?? 0 ?>)"
                                                    class="flex items-center gap-2 w-full text-left text-slate-600 hover:bg-slate-50 rounded-lg px-3 py-2">
                                                <i class="ph ph-upload"></i> Upload Template
                                            </button>
                                        </li>
                                    </ul>
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

<!-- Add Admin Modal -->
<dialog id="add_admin_modal" class="modal">
    <div class="modal-box">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-12 h-12 rounded-xl bg-primary-light flex items-center justify-center">
                <i class="ph ph-users text-primary text-xl"></i>
            </div>
            <div>
                <h3 class="modal-title mb-1">Tambah Admin ke Kelas</h3>
                <p class="text-sm text-slate-500">Pilih admin yang akan mengelola kelas</p>
            </div>
        </div>

        <form action="<?= base_url('admin/sertifikat/add-admin') ?>" method="POST">
            <?= csrf_field() ?>
            <input type="hidden" name="id_produk" id="modal_id_produk" value="" />

            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text font-medium">Pilih Admin</span>
                </label>
                <select name="id_user" class="select" required>
                    <option value="">-- Pilih Admin --</option>
                    <?php if (!empty($allAdmins)): ?>
                        <?php foreach($allAdmins as $adm): ?>
                            <option value="<?= $adm['id_user'] ?? '' ?>">
                                <?= esc($adm['nama_lengkap'] ?? '') ?> (<?= $adm['role'] ?? '' ?>)
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div class="form-control mb-6">
                <label class="label">
                    <span class="label-text font-medium">Role</span>
                </label>
                <select name="role" class="select">
                    <option value="pemateri">Pemateri Utama</option>
                    <option value="asisten">Asisten</option>
                    <option value="admin">Admin Pendamping</option>
                </select>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn btn-ghost" onclick="add_admin_modal.close()">Batal</button>
                <button type="submit" class="btn btn-primary">
                    <i class="ph ph-user-plus mr-2"></i>
                    Tambahkan
                </button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop"></form>
</dialog>

<!-- Upload Template Modal -->
<dialog id="upload_modal" class="modal">
    <div class="modal-box">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-12 h-12 rounded-xl bg-warning-light flex items-center justify-center">
                <i class="ph ph-certificate text-warning text-xl"></i>
            </div>
            <div>
                <h3 class="modal-title mb-1">Upload Template Sertifikat</h3>
                <p class="text-sm text-slate-500">Background untuk sertifikat kelas</p>
            </div>
        </div>

        <form action="<?= base_url('admin/sertifikat/upload') ?>" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <input type="hidden" name="id_produk" id="upload_id_produk" value="" />

            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text font-medium">File Background</span>
                </label>
                <input type="file" name="template" class="file-input file-input-bordered w-full"
                       accept="image/jpeg,image/png" required />
                <label class="label">
                    <span class="label-text-alt text-warning flex items-center gap-1">
                        <i class="ph ph-warning"></i>
                        Rasio 16:10 (1920x1200px) - Untuk cetak A4
                    </span>
                </label>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn btn-ghost" onclick="upload_modal.close()">Batal</button>
                <button type="submit" class="btn btn-warning">
                    <i class="ph ph-upload mr-2"></i>
                    Upload
                </button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop"></form>
</dialog>

<script>
function openAddAdminModal(idProduk) {
    document.getElementById('modal_id_produk').value = idProduk;
    add_admin_modal.showModal();
}

function openUploadModal(idProduk) {
    document.getElementById('upload_id_produk').value = idProduk;
    upload_modal.showModal();
}
</script>

<?= $this->endSection() ?>