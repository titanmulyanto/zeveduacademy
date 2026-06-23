<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="page-header">
    <div>
        <h1 class="page-title">Kelola Slider Banner</h1>
        <p class="page-subtitle">Banner utama di halaman landing</p>
    </div>
    <div class="page-actions">
        <button class="btn btn-primary" onclick="add_slider_modal.showModal()">
            <i class="ph ph-plus"></i>
            Tambah Slider
        </button>
    </div>
</div>

<!-- Info Box -->
<div class="alert alert-warning mb-6 animate-slide-up">
    <div class="alert-icon">
        <i class="ph-fill ph-warning"></i>
    </div>
    <div>
        <p class="font-semibold">📐 Ukuran Gambar: Rasio 1:1</p>
        <p class="text-sm mt-1">Contoh: 1080x1080px, 500x500px, 800x800px</p>
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

<!-- Sliders Table -->
<div class="card animate-slide-up">
    <div class="card-body p-0">
        <?php if (empty($sliders)): ?>
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="ph ph-image"></i>
                </div>
                <h3 class="empty-state-title">Belum Ada Slider</h3>
                <p class="empty-state-description">Tambahkan slider pertama untuk menampilkan di halaman utama.</p>
                <button class="btn btn-primary mt-4" onclick="add_slider_modal.showModal()">
                    <i class="ph ph-plus"></i>
                    Tambah Slider
                </button>
            </div>
        <?php else: ?>
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Urutan</th>
                            <th>Gambar</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($sliders as $slider): ?>
                        <tr>
                            <td>
                                <span class="badge badge-primary badge-lg"><?= $slider['urutan'] ?? 1 ?></span>
                            </td>
                            <td>
                                <div class="flex items-center gap-4">
                                    <div class="w-20 h-20 rounded-xl overflow-hidden border-2 border-slate-200">
                                        <img src="<?= base_url($slider['gambar'] ?? '') ?>" alt="Slider"
                                             class="w-full h-full object-cover">
                                    </div>
                                    <div>
                                        <p class="font-medium text-slate-800">Slider #<?= $slider['urutan'] ?? 1 ?></p>
                                        <p class="text-xs text-slate-500"><?= esc($slider['gambar'] ?? '') ?></p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="flex justify-center gap-2">
                                    <button onclick="editSlider(<?= $slider['id_slider'] ?? 0 ?>, '<?= $slider['urutan'] ?? 1 ?>')"
                                            class="btn btn-sm btn-ghost text-primary hover:bg-primary-light"
                                            title="Edit">
                                        <i class="ph ph-pencil text-lg"></i>
                                    </button>
                                    <a href="<?= base_url('admin/cms/slider/delete/' . ($slider['id_slider'] ?? '')) ?>"
                                       class="btn btn-sm btn-ghost text-error hover:bg-error-light"
                                       title="Hapus"
                                       onclick="return confirm('Hapus slider ini?')">
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

<!-- Add Slider Modal -->
<dialog id="add_slider_modal" class="modal">
    <div class="modal-box">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-12 h-12 rounded-xl bg-primary-light flex items-center justify-center">
                <i class="ph ph-plus-circle text-primary text-xl"></i>
            </div>
            <div>
                <h3 class="modal-title mb-1">Tambah Slider Baru</h3>
                <p class="text-sm text-slate-500">Upload gambar slider baru</p>
            </div>
        </div>

        <form action="<?= base_url('admin/cms/slider') ?>" method="POST" enctype="multipart/form-data">
            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text font-medium">Gambar Slider</span>
                    <span class="label-text-alt text-warning">📐 Rasio 1:1</span>
                </label>
                <input type="file" name="gambar" class="file-input file-input-bordered w-full"
                       accept="image/*" required />
            </div>
            <div class="form-control mb-6">
                <label class="label">
                    <span class="label-text font-medium">Urutan Tampilan</span>
                </label>
                <input type="number" name="urutan" class="input" value="1" min="1" required />
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-ghost" onclick="add_slider_modal.close()">Batal</button>
                <button type="submit" class="btn btn-primary">
                    <i class="ph ph-upload mr-2"></i>
                    Upload
                </button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop"></form>
</dialog>

<!-- Edit Slider Modal -->
<dialog id="edit_slider_modal" class="modal">
    <div class="modal-box">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-12 h-12 rounded-xl bg-warning-light flex items-center justify-center">
                <i class="ph ph-pencil text-warning text-xl"></i>
            </div>
            <div>
                <h3 class="modal-title mb-1">Edit Slider</h3>
                <p class="text-sm text-slate-500">Perbarui informasi slider</p>
            </div>
        </div>

        <form id="editSliderForm" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" id="edit_slider_id" />
            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text font-medium">Gambar Baru</span>
                    <span class="label-text-alt text-slate-400">(Kosongkan jika tidak diubah)</span>
                </label>
                <input type="file" name="gambar" class="file-input file-input-bordered w-full"
                       accept="image/*" />
            </div>
            <div class="form-control mb-6">
                <label class="label">
                    <span class="label-text font-medium">Urutan</span>
                </label>
                <input type="number" name="urutan" id="edit_slider_urutan" class="input" min="1" required />
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-ghost" onclick="edit_slider_modal.close()">Batal</button>
                <button type="submit" class="btn btn-warning">
                    <i class="ph ph-check mr-2"></i>
                    Simpan
                </button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop"></form>
</dialog>

<script>
function editSlider(id, urutan) {
    document.getElementById('edit_slider_id').value = id;
    document.getElementById('edit_slider_urutan').value = urutan;
    document.getElementById('editSliderForm').action = '<?= base_url('admin/cms/slider/update/') ?>' + id;
    edit_slider_modal.showModal();
}
</script>

<?= $this->endSection() ?>