<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6">
    <h1 class="text-2xl font-bold">Kelola FAQ</h1>
    <button class="btn btn-primary" onclick="add_faq_modal.showModal()">
        <i class="ph ph-plus mr-2"></i> Tambah FAQ
    </button>
</div>

<div class="card bg-base-100 shadow border border-base-200">
    <div class="card-body p-0">
        <?php if (empty($faqs)): ?>
            <div class="text-center py-12">
                <i class="ph ph-question text-5xl text-base-content/30"></i>
                <p class="mt-4 text-base-content/60">Belum ada FAQ. Tambahkan FAQ pertama.</p>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Pertanyaan</th>
                            <th>Jawaban</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($faqs as $faq): ?>
                        <tr>
                            <td class="font-semibold"><?= esc($faq['pertanyaan']) ?></td>
                            <td class="text-sm opacity-70 max-w-lg"><?= esc($faq['jawaban']) ?></td>
                            <td>
                                <div class="flex justify-center gap-2">
                                    <button onclick="editFaq(<?= $faq['id_faq'] ?>, '<?= esc(addslashes($faq['pertanyaan'])) ?>', '<?= esc(addslashes($faq['jawaban'])) ?>')" class="btn btn-sm btn-ghost text-primary" title="Edit">
                                        <i class="ph ph-pencil"></i>
                                    </button>
                                    <a href="<?= base_url('admin/cms/faq/delete/' . $faq['id_faq']) ?>"
                                       class="btn btn-sm btn-ghost text-error"
                                       title="Hapus"
                                       onclick="return confirm('Hapus FAQ ini?')">
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

<!-- Modal: Add FAQ -->
<dialog id="add_faq_modal" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg mb-4">
            <i class="ph ph-plus-circle"></i> Tambah FAQ Baru
        </h3>
        <form action="<?= base_url('admin/cms/faq') ?>" method="POST">
            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text font-semibold">Pertanyaan</span>
                </label>
                <input type="text" name="pertanyaan" class="input input-bordered w-full" placeholder="Contoh: Bagaimana cara mendaftar?" required />
            </div>
            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text font-semibold">Jawaban</span>
                </label>
                <textarea name="jawaban" class="textarea textarea-bordered h-32" placeholder="Jawaban untuk pertanyaan di atas..." required></textarea>
            </div>
            <div class="modal-action">
                <button type="button" class="btn" onclick="add_faq_modal.close()">Batal</button>
                <button type="submit" class="btn btn-primary">
                    <i class="ph ph-save"></i> Simpan
                </button>
            </div>
        </form>
    </div>
    <label class="modal-backdrop" for="add_faq_modal"></label>
</dialog>

<!-- Modal: Edit FAQ -->
<dialog id="edit_faq_modal" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg mb-4">
            <i class="ph ph-pencil"></i> Edit FAQ
        </h3>
        <form id="editFaqForm" method="POST">
            <input type="hidden" name="id" id="edit_faq_id" />
            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text font-semibold">Pertanyaan</span>
                </label>
                <input type="text" name="pertanyaan" id="edit_faq_pertanyaan" class="input input-bordered w-full" required />
            </div>
            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text font-semibold">Jawaban</span>
                </label>
                <textarea name="jawaban" id="edit_faq_jawaban" class="textarea textarea-bordered h-32" required></textarea>
            </div>
            <div class="modal-action">
                <button type="button" class="btn" onclick="edit_faq_modal.close()">Batal</button>
                <button type="submit" class="btn btn-primary">
                    <i class="ph ph-save"></i> Simpan
                </button>
            </div>
        </form>
    </div>
    <label class="modal-backdrop" for="edit_faq_modal"></label>
</dialog>

<script>
function editFaq(id, pertanyaan, jawaban) {
    document.getElementById('edit_faq_id').value = id;
    document.getElementById('edit_faq_pertanyaan').value = pertanyaan;
    document.getElementById('edit_faq_jawaban').value = jawaban;
    document.getElementById('editFaqForm').action = '<?= base_url('admin/cms/faq/update/') ?>' + id;
    edit_faq_modal.showModal();
}
</script>

<?= $this->endSection() ?>
