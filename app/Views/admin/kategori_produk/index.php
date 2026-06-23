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
        <span class="breadcrumbs-modern-current">Kategori Produk</span>
    </div>
</div>

<!-- Page Header -->
<div class="page-header">
    <div>
        <h1 class="page-title">Kategori Produk</h1>
        <p class="page-subtitle">Kelola kategori untuk kelas pelatihan</p>
    </div>
    <div class="page-actions">
        <button onclick="openAddKategoriModal()" class="btn btn-primary">
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
                <button onclick="openAddKategoriModal()" class="btn btn-primary mt-4">
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
                        <h3 class="font-bold text-lg text-slate-900 mb-1"><?= esc($kat['nama_kategori'] ?? '-') ?></h3>
                        <?php if (!empty($kat['deskripsi'])): ?>
                        <p class="text-sm text-slate-500 line-clamp-2"><?= esc($kat['deskripsi']) ?></p>
                        <?php else: ?>
                        <p class="text-sm text-slate-400 italic">Tidak ada deskripsi</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Product Count -->
                <div class="flex items-center gap-2 mt-4 pt-4 border-t border-slate-100">
                    <span class="badge badge-neutral">
                        <i class="ph ph-books mr-1"></i>
                        <?= $kat['total_produk'] ?? 0 ?> Produk
                    </span>
                </div>

                <!-- Actions -->
                <div class="card-actions mt-4">
                    <button class="btn btn-sm btn-ghost text-warning hover:bg-warning-light"
                            onclick="openEditKategoriModal(<?= $kat['id_kategori'] ?? 0 ?>, '<?= esc(addslashes($kat['nama_kategori'] ?? ''), 'js') ?>', '<?= esc(addslashes($kat['deskripsi'] ?? ''), 'js') ?>')">
                        <i class="ph ph-pencil"></i>
                        Edit
                    </button>
                    <a href="<?= base_url('admin/kategori/delete/' . ($kat['id_kategori'] ?? '')) ?>"
                       class="btn btn-sm btn-ghost text-error hover:bg-error-light"
                       onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">
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
<dialog id="add_kategori_modal" class="modal">
    <div class="modal-box">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-12 h-12 rounded-xl bg-primary-light flex items-center justify-center">
                <i class="ph ph-folder-plus text-primary text-xl"></i>
            </div>
            <div>
                <h3 class="modal-title mb-1">Tambah Kategori Baru</h3>
                <p class="text-sm text-slate-500">Buat kategori untuk mengelompokkan kelas</p>
            </div>
        </div>

        <form action="<?= base_url('admin/kategori/store') ?>" method="POST" class="space-y-4">
            <div class="form-control">
                <label class="label">
                    <span class="label-text font-medium">Nama Kategori</span>
                </label>
                <input type="text" name="nama_kategori"
                       placeholder="Contoh: Programming, Design, Marketing"
                       class="input" required />
            </div>

            <div class="form-control">
                <label class="label">
                    <span class="label-text font-medium">Deskripsi</span>
                    <span class="label-text-alt text-slate-400">(Opsional)</span>
                </label>
                <textarea name="deskripsi" class="textarea" rows="2"
                          placeholder="Deskripsi singkat kategori"></textarea>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn btn-ghost" onclick="add_kategori_modal.close()">Batal</button>
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
<dialog id="edit_kategori_modal" class="modal">
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

        <form action="" method="POST" id="edit_kategori_form" class="space-y-4">
            <div class="form-control">
                <label class="label">
                    <span class="label-text font-medium">Nama Kategori</span>
                </label>
                <input type="text" name="nama_kategori" id="edit_kategori_nama"
                       class="input" required />
            </div>

            <div class="form-control">
                <label class="label">
                    <span class="label-text font-medium">Deskripsi</span>
                    <span class="label-text-alt text-slate-400">(Opsional)</span>
                </label>
                <textarea name="deskripsi" id="edit_kategori_desc"
                          class="textarea" rows="2"></textarea>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn btn-ghost" onclick="edit_kategori_modal.close()">Batal</button>
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
    function openAddKategoriModal() {
        add_kategori_modal.showModal();
    }

    function openEditKategoriModal(id, nama, deskripsi) {
        document.getElementById('edit_kategori_nama').value = nama;
        document.getElementById('edit_kategori_desc').value = deskripsi || '';
        document.getElementById('edit_kategori_form').action = '<?= base_url('admin/kategori/update/') ?>' + id;
        edit_kategori_modal.showModal();
    }
</script>

<?= $this->endSection() ?>