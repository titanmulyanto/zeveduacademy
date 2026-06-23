<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="page-header">
    <div>
        <h1 class="page-title">Fitur Unggulan</h1>
        <p class="page-subtitle">Kelola fitur yang ditampilkan di landing page</p>
    </div>
    <div class="page-actions">
        <button class="btn btn-primary" onclick="add_feature_modal.showModal()">
            <i class="ph ph-plus"></i>
            Tambah Fitur
        </button>
    </div>
</div>

<!-- Info Box -->
<div class="alert alert-warning mb-6 animate-slide-up">
    <div class="alert-icon">
        <i class="ph-fill ph-warning"></i>
    </div>
    <div>
        <p class="font-semibold">📐 Ukuran Gambar: Rasio 4:3</p>
        <p class="text-sm mt-1">Contoh: 800x600px, 1200x900px, 400x300px</p>
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

<!-- Features Table -->
<div class="card animate-slide-up">
    <div class="card-body p-0">
        <?php if (empty($features)): ?>
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="ph ph-star"></i>
                </div>
                <h3 class="empty-state-title">Belum Ada Fitur</h3>
                <p class="empty-state-description">Tambahkan fitur unggulan pertama untuk ditampilkan di landing page.</p>
                <button class="btn btn-primary mt-4" onclick="add_feature_modal.showModal()">
                    <i class="ph ph-plus"></i>
                    Tambah Fitur
                </button>
            </div>
        <?php else: ?>
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Gambar</th>
                            <th>Judul</th>
                            <th>Deskripsi</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($features as $feature): ?>
                        <tr>
                            <td>
                                <div class="w-16 h-12 rounded-lg overflow-hidden border border-slate-200">
                                    <img src="<?= base_url($feature['gambar'] ?? '') ?>"
                                         alt="<?= esc($feature['judul'] ?? '') ?>"
                                         class="w-full h-full object-cover">
                                </div>
                            </td>
                            <td>
                                <p class="font-semibold text-slate-800"><?= esc($feature['judul'] ?? '-') ?></p>
                            </td>
                            <td>
                                <p class="text-sm text-slate-500 line-clamp-2 max-w-md"><?= esc($feature['deskripsi'] ?? '-') ?></p>
                            </td>
                            <td>
                                <div class="flex justify-center gap-2">
                                    <button onclick="editFeature(<?= $feature['id_feature'] ?? 0 ?>, '<?= esc(addslashes($feature['judul'] ?? ''), 'js') ?>', '<?= esc(addslashes($feature['deskripsi'] ?? ''), 'js') ?>')"
                                            class="btn btn-sm btn-ghost text-primary hover:bg-primary-light"
                                            title="Edit">
                                        <i class="ph ph-pencil text-lg"></i>
                                    </button>
                                    <a href="<?= base_url('admin/cms/features/delete/' . ($feature['id_feature'] ?? '')) ?>"
                                       class="btn btn-sm btn-ghost text-error hover:bg-error-light"
                                       title="Hapus"
                                       onclick="return confirm('Hapus fitur ini?')">
                                        <i class="ph ph-trash text-lg"></i>
                                    </a>
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

<!-- Add Feature Modal -->
<dialog id="add_feature_modal" class="modal">
    <div class="modal-box">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-12 h-12 rounded-xl bg-primary-light flex items-center justify-center">
                <i class="ph ph-plus-circle text-primary text-xl"></i>
            </div>
            <div>
                <h3 class="modal-title mb-1">Tambah Fitur Unggulan</h3>
                <p class="text-sm text-slate-500">Buat fitur baru untuk landing page</p>
            </div>
        </div>

        <form action="<?= base_url('admin/cms/features') ?>" method="POST" enctype="multipart/form-data">
            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text font-medium">Gambar</span>
                    <span class="label-text-alt text-warning">📐 Rasio 4:3</span>
                </label>
                <input type="file" name="gambar" class="file-input file-input-bordered w-full"
                       accept="image/*" required />
            </div>
            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text font-medium">Judul</span>
                </label>
                <input type="text" name="judul" class="input" required />
            </div>
            <div class="form-control mb-6">
                <label class="label">
                    <span class="label-text font-medium">Deskripsi</span>
                </label>
                <textarea name="deskripsi" class="textarea" rows="3" required></textarea>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-ghost" onclick="add_feature_modal.close()">Batal</button>
                <button type="submit" class="btn btn-primary">
                    <i class="ph ph-check mr-2"></i>
                    Simpan
                </button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop"></form>
</dialog>

<!-- Edit Feature Modal -->
<dialog id="edit_feature_modal" class="modal">
    <div class="modal-box">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-12 h-12 rounded-xl bg-warning-light flex items-center justify-center">
                <i class="ph ph-pencil text-warning text-xl"></i>
            </div>
            <div>
                <h3 class="modal-title mb-1">Edit Fitur Unggulan</h3>
                <p class="text-sm text-slate-500">Perbarui informasi fitur</p>
            </div>
        </div>

        <form id="editFeatureForm" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" id="edit_feature_id" />
            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text font-medium">Gambar Baru</span>
                    <span class="label-text-alt text-slate-400">(Kosongkan jika tidak diubah)</span>
                </label>
                <input type="file" name="gambar" class="file-input file-input-bordered w-full"
                       accept="image/*" />
            </div>
            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text font-medium">Judul</span>
                </label>
                <input type="text" name="judul" id="edit_feature_judul" class="input" required />
            </div>
            <div class="form-control mb-6">
                <label class="label">
                    <span class="label-text font-medium">Deskripsi</span>
                </label>
                <textarea name="deskripsi" id="edit_feature_desc" class="textarea" rows="3" required></textarea>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-ghost" onclick="edit_feature_modal.close()">Batal</button>
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
function editFeature(id, judul, deskripsi) {
    document.getElementById('edit_feature_id').value = id;
    document.getElementById('edit_feature_judul').value = judul;
    document.getElementById('edit_feature_desc').value = deskripsi;
    document.getElementById('editFeatureForm').action = '<?= base_url('admin/cms/features/update/') ?>' + id;
    edit_feature_modal.showModal();
}
</script>

<?= $this->endSection() ?>