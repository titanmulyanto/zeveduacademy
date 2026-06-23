<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6">
    <h1 class="text-2xl font-bold">Kelola Testimoni</h1>
    <button class="btn btn-primary" onclick="add_testi_modal.showModal()">
        <i class="ph ph-plus mr-2"></i> Tambah Testimoni
    </button>
</div>

<!-- Info Box -->
<div class="alert alert-warning mb-6">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-current shrink-0 w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
    <div>
        <div class="font-bold">📐 Ukuran Foto: Rasio 1:1</div>
        <div class="text-sm">Rating: Bintang 1-10 (bilangan bulat)</div>
    </div>
</div>

<div class="card bg-base-100 shadow border border-base-200">
    <div class="card-body p-0">
        <?php if (empty($testimonials)): ?>
            <div class="text-center py-12">
                <i class="ph ph-chat-circle-text text-5xl text-base-content/30"></i>
                <p class="mt-4 text-base-content/60">Belum ada testimoni. Tambahkan testimoni pertama.</p>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Profil</th>
                            <th>Nama</th>
                            <th>Rating</th>
                            <th>Deskripsi</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($testimonials as $testi): ?>
                        <tr>
                            <td>
                                <div class="avatar">
                                    <div class="w-12 rounded-full border-2 border-primary">
                                        <img src="<?= $testi['foto_profil'] ?>" alt="<?= esc($testi['nama']) ?>" />
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="font-bold"><?= esc($testi['nama']) ?></div>
                            </td>
                            <td>
                                <div class="flex items-center gap-1">
                                    <span class="text-warning font-bold"><?= $testi['rating'] ?></span>
                                    <i class="ph-fill ph-star text-warning"></i>
                                    <span class="text-base-content/50">/ 10</span>
                                </div>
                            </td>
                            <td>
                                <div class="text-sm opacity-70 max-w-xs truncate"><?= esc($testi['deskripsi']) ?></div>
                            </td>
                            <td>
                                <div class="flex justify-center gap-2">
                                    <button onclick="editTestimoni(<?= $testi['id_testimoni'] ?>, '<?= esc(addslashes($testi['nama'])) ?>', '<?= esc(addslashes($testi['deskripsi'])) ?>', <?= $testi['rating'] ?>)" class="btn btn-sm btn-ghost text-primary" title="Edit">
                                        <i class="ph ph-pencil"></i>
                                    </button>
                                    <a href="<?= base_url('admin/cms/testimoni/delete/' . $testi['id_testimoni']) ?>"
                                       class="btn btn-sm btn-ghost text-error"
                                       title="Hapus"
                                       onclick="return confirm('Hapus testimoni ini?')">
                                        <i class="ph ph-trash"></i>
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

<!-- Modal: Add Testimoni -->
<dialog id="add_testi_modal" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg mb-4">
            <i class="ph ph-plus-circle"></i> Tambah Testimoni Baru
        </h3>
        <form action="<?= base_url('admin/cms/testimoni') ?>" method="POST" enctype="multipart/form-data">
            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text font-semibold">Nama Student</span>
                </label>
                <input type="text" name="nama" class="input input-bordered w-full" placeholder="Nama lengkap student" required />
            </div>
            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text font-semibold">Rating (1-10)</span>
                    <span class="label-text-alt">Bintang</span>
                </label>
                <input type="number" name="rating" class="input input-bordered w-full" min="1" max="10" value="5" required />
                <div class="rating mt-2">
                    <?php for($i = 1; $i <= 10; $i++): ?>
                        <input type="radio" name="rating_display" class="mask mask-star-2 bg-warning rating-xs" value="<?= $i ?>" <?= $i == 5 ? 'checked' : '' ?> />
                    <?php endfor; ?>
                </div>
            </div>
            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text font-semibold">Deskripsi / Ulasan</span>
                </label>
                <textarea name="deskripsi" class="textarea textarea-bordered h-24" placeholder="Isi testimoni atau ulasan student..." required></textarea>
            </div>
            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text">Foto Profil</span>
                    <span class="label-text-alt text-warning">📐 Rasio 1:1 (Opsional)</span>
                </label>
                <input type="file" name="foto_profil" class="file-input file-input-bordered w-full" accept="image/*" />
            </div>
            <div class="modal-action">
                <button type="button" class="btn" onclick="add_testi_modal.close()">Batal</button>
                <button type="submit" class="btn btn-primary">
                    <i class="ph ph-save"></i> Simpan
                </button>
            </div>
        </form>
    </div>
    <label class="modal-backdrop" for="add_testi_modal"></label>
</dialog>

<!-- Modal: Edit Testimoni -->
<dialog id="edit_testi_modal" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg mb-4">
            <i class="ph ph-pencil"></i> Edit Testimoni
        </h3>
        <form id="editTestiForm" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" id="edit_testi_id" />
            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text font-semibold">Nama Student</span>
                </label>
                <input type="text" name="nama" id="edit_testi_nama" class="input input-bordered w-full" required />
            </div>
            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text font-semibold">Rating (1-10)</span>
                </label>
                <input type="number" name="rating" id="edit_testi_rating" class="input input-bordered w-full" min="1" max="10" required />
            </div>
            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text font-semibold">Deskripsi</span>
                </label>
                <textarea name="deskripsi" id="edit_testi_desc" class="textarea textarea-bordered h-24" required></textarea>
            </div>
            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text">Foto Baru (kosongkan jika tidak diubah)</span>
                    <span class="label-text-alt text-warning">📐 Rasio 1:1</span>
                </label>
                <input type="file" name="foto_profil" class="file-input file-input-bordered w-full" accept="image/*" />
            </div>
            <div class="modal-action">
                <button type="button" class="btn" onclick="edit_testi_modal.close()">Batal</button>
                <button type="submit" class="btn btn-primary">
                    <i class="ph ph-save"></i> Simpan
                </button>
            </div>
        </form>
    </div>
    <label class="modal-backdrop" for="edit_testi_modal"></label>
</dialog>

<script>
function editTestimoni(id, nama, deskripsi, rating) {
    document.getElementById('edit_testi_id').value = id;
    document.getElementById('edit_testi_nama').value = nama;
    document.getElementById('edit_testi_desc').value = deskripsi;
    document.getElementById('edit_testi_rating').value = rating;
    document.getElementById('editTestiForm').action = '<?= base_url('admin/cms/testimoni/update/') ?>' + id;
    edit_testi_modal.showModal();
}
</script>

<?= $this->endSection() ?>
