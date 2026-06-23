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
        <span class="breadcrumbs-modern-current">Kategori Materi</span>
    </div>
</div>

<!-- Page Header -->
<div class="page-header">
    <div>
        <h1 class="page-title">Kategori Materi</h1>
        <p class="page-subtitle">Kelola kategori/sub-bab dalam kelas pelatihan</p>
    </div>
    <div class="page-actions">
        <button onclick="openAddModal()" class="btn btn-primary">
            <i class="ph ph-plus"></i>
            Tambah Kategori
        </button>
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

<?php if (session()->getFlashdata('error')): ?>
<div class="alert alert-error mb-6 animate-slide-up">
    <div class="alert-icon">
        <i class="ph-fill ph-warning-circle"></i>
    </div>
    <span><?= session()->getFlashdata('error') ?></span>
</div>
<?php endif; ?>

<!-- Info Box -->
<div class="alert alert-info mb-6 animate-slide-up">
    <div class="alert-icon">
        <i class="ph-fill ph-info"></i>
    </div>
    <div>
        <p class="font-semibold">Kategori Materi</p>
        <ul class="text-sm mt-1 space-y-1">
            <li>• Kategori digunakan untuk mengelompokkan video dan dokumen dalam satu kelas</li>
            <li>• Urutan menentukan tampilan di halaman student</li>
            <li>• Hapus video dalam kategori terlebih dahulu sebelum menghapus kategori</li>
        </ul>
    </div>
</div>

<!-- Categories Grid -->
<?php if (empty($kategori)): ?>
    <div class="card">
        <div class="card-body">
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="ph ph-folder-dashed"></i>
                </div>
                <h3 class="empty-state-title">Belum Ada Kategori</h3>
                <p class="empty-state-description">Klik tombol "Tambah Kategori" untuk membuat kategori baru.</p>
                <button onclick="openAddModal()" class="btn btn-primary mt-4">
                    <i class="ph ph-plus"></i>
                    Tambah Kategori
                </button>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($kategori as $kat): ?>
        <div class="card hover-lift animate-scale-in">
            <div class="card-body">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-xl bg-primary-light flex items-center justify-center flex-shrink-0">
                        <i class="ph ph-folder text-2xl text-primary"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-bold text-lg text-slate-900 mb-1"><?= esc($kat['judul_kategori'] ?? '-') ?></h3>
                        <p class="text-sm text-slate-500 line-clamp-2">
                            <span class="badge badge-info"><?= esc($kat['nama_kelas'] ?? '-') ?></span>
                        </p>
                    </div>
                </div>

                <!-- Stats -->
                <div class="flex items-center gap-4 mt-4 pt-4 border-t border-slate-100">
                    <div class="flex items-center gap-2">
                        <i class="ph ph-video-camera text-slate-400"></i>
                        <span class="text-sm text-slate-600"><?= $kat['total_video'] ?? 0 ?> Video</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="ph ph-list-ordered text-slate-400"></i>
                        <span class="text-sm text-slate-600">Urutan: <?= $kat['urutan_kategori'] ?? 1 ?></span>
                    </div>
                </div>

                <!-- Actions -->
                <div class="card-actions mt-4">
                    <button class="btn btn-sm btn-ghost text-warning hover:bg-warning-light flex-1"
                            onclick="openEditModal(<?= $kat['id_kategori_materi'] ?? 0 ?>, '<?= esc(addslashes($kat['judul_kategori'] ?? ''), 'js') ?>', <?= $kat['id_produk'] ?? 0 ?>, <?= $kat['urutan_kategori'] ?? 1 ?>)">
                        <i class="ph ph-pencil"></i>
                        Edit
                    </button>
                    <a href="<?= base_url('admin/kategori-materi/delete/' . ($kat['id_kategori_materi'] ?? '')) ?>"
                       class="btn btn-sm btn-ghost text-error hover:bg-error-light"
                       onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini?<?= ($kat['total_video'] ?? 0) > 0 ? ' Kategori ini memiliki ' . $kat['total_video'] . ' video.' : '' ?>')">
                        <i class="ph ph-trash"></i>
                        Hapus
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<!-- Add Kategori Modal -->
<dialog id="add_modal" class="modal">
    <div class="modal-box">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-12 h-12 rounded-xl bg-primary-light flex items-center justify-center">
                <i class="ph ph-folder-plus text-primary text-xl"></i>
            </div>
            <div>
                <h3 class="modal-title mb-1">Tambah Kategori Baru</h3>
                <p class="text-sm text-slate-500">Buat kategori untuk mengelompokkan materi</p>
            </div>
        </div>

        <form action="<?= base_url('admin/kategori-materi/store') ?>" method="POST" class="space-y-4">
            <?= csrf_field() ?>

            <div class="form-control">
                <label class="label">
                    <span class="label-text font-medium">Kelas</span>
                </label>
                <select name="id_produk" id="add_id_produk" class="select" required onchange="updateEditProdukOptions()">
                    <option value="">-- Pilih Kelas --</option>
                    <?php if (!empty($classes)): ?>
                        <?php foreach($classes as $cls): ?>
                            <option value="<?= $cls['id_produk'] ?? '' ?>"><?= esc($cls['judul'] ?? '') ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div class="form-control">
                <label class="label">
                    <span class="label-text font-medium">Nama Kategori</span>
                </label>
                <input type="text" name="judul_kategori" id="add_judul_kategori"
                       placeholder="Contoh: Modul 1 - Pengenalan, Bab 1 - Dasar"
                       class="input" required />
            </div>

            <div class="form-control">
                <label class="label">
                    <span class="label-text font-medium">Urutan</span>
                </label>
                <input type="number" name="urutan_kategori" id="add_urutan_kategori"
                       class="input" value="1" min="1" />
                <label class="label">
                    <span class="label-text-alt text-slate-400">Angka kecil tampil di atas</span>
                </label>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn btn-ghost" onclick="add_modal.close()">Batal</button>
                <button type="submit" class="btn btn-primary">
                    <i class="ph ph-check mr-2"></i>
                    Simpan
                </button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop"></form>
</dialog>

<!-- Edit Kategori Modal -->
<dialog id="edit_modal" class="modal">
    <div class="modal-box">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-12 h-12 rounded-xl bg-warning-light flex items-center justify-center">
                <i class="ph ph-folder-edit text-warning text-xl"></i>
            </div>
            <div>
                <h3 class="modal-title mb-1">Edit Kategori</h3>
                <p class="text-sm text-slate-500">Perbarui informasi kategori</p>
            </div>
        </div>

        <form action="" method="POST" id="edit_form" class="space-y-4">
            <?= csrf_field() ?>

            <div class="form-control">
                <label class="label">
                    <span class="label-text font-medium">Kelas</span>
                </label>
                <select name="id_produk" id="edit_id_produk" class="select" required>
                    <?php if (!empty($classes)): ?>
                        <?php foreach($classes as $cls): ?>
                            <option value="<?= $cls['id_produk'] ?? '' ?>"><?= esc($cls['judul'] ?? '') ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div class="form-control">
                <label class="label">
                    <span class="label-text font-medium">Nama Kategori</span>
                </label>
                <input type="text" name="judul_kategori" id="edit_judul_kategori"
                       class="input" required />
            </div>

            <div class="form-control">
                <label class="label">
                    <span class="label-text font-medium">Urutan</span>
                </label>
                <input type="number" name="urutan_kategori" id="edit_urutan_kategori"
                       class="input" min="1" />
                <label class="label">
                    <span class="label-text-alt text-slate-400">Angka kecil tampil di atas</span>
                </label>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn btn-ghost" onclick="edit_modal.close()">Batal</button>
                <button type="submit" class="btn btn-warning">
                    <i class="ph ph-check mr-2"></i>
                    Update
                </button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop"></form>
</dialog>

<script>
function openAddModal() {
    document.getElementById('add_id_produk').value = '';
    document.getElementById('add_judul_kategori').value = '';
    document.getElementById('add_urutan_kategori').value = '1';
    add_modal.showModal();
}

function openEditModal(id, judul, idProduk, urutan) {
    document.getElementById('edit_id_produk').value = idProduk;
    document.getElementById('edit_judul_kategori').value = judul;
    document.getElementById('edit_urutan_kategori').value = urutan;
    document.getElementById('edit_form').action = '<?= base_url('admin/kategori-materi/update/') ?>' + id;
    edit_modal.showModal();
}
</script>

<?= $this->endSection() ?>