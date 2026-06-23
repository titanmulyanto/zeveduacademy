<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-8">
    <div class="flex items-center gap-4">
        <a href="<?= base_url('admin/materi/detail/' . $ujian['id_produk']) ?>" class="btn btn-circle btn-ghost">
            <i class="ph ph-arrow-left text-2xl"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold"><?= $title ?></h1>
            <div class="flex gap-3 mt-1 text-sm">
                <span class="badge badge-primary">
                    <i class="ph ph-clock"></i> <?= $ujian['durasi_menit'] ?? 60 ?> menit
                </span>
                <span class="badge badge-secondary">
                    <i class="ph ph-star"></i> Min. Nilai: <?= $ujian['nilai_minimal_lulus'] ?? 70 ?>
                </span>
            </div>
        </div>
    </div>

    <button class="btn btn-primary" onclick="add_question_modal.showModal()">
        <i class="ph ph-plus"></i> Tambah Soal
    </button>
</div>

<!-- Info Alert -->
<div class="alert alert-info mb-6">
    <i class="ph ph-info text-xl"></i>
    <div>
        <p class="font-semibold"><?= count($questions) ?> Soal</p>
        <p class="text-sm opacity-70">Setiap soal memiliki 3 opsi jawaban (A, B, C)</p>
    </div>
</div>

<div class="space-y-4">
    <?php if (empty($questions)): ?>
        <div class="text-center py-12 bg-base-200 rounded-xl border-2 border-dashed border-base-300">
            <i class="ph ph-exam text-4xl opacity-20 mb-2"></i>
            <p class="opacity-50">Belum ada soal. Klik "Tambah Soal" untuk membuat.</p>
        </div>
    <?php else: ?>
        <?php foreach($questions as $index => $q): ?>
        <div class="card bg-base-100 shadow border border-base-200">
            <div class="card-body">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex items-center gap-3">
                        <span class="badge badge-primary badge-lg"><?= $index + 1 ?></span>
                        <span class="font-semibold">ID: <?= $q['id_soal'] ?></span>
                    </div>
                    <div class="flex gap-2">
                        <button class="btn btn-ghost btn-sm" onclick="openEditQuestionModal(<?= $q['id_soal'] ?>, '<?= esc(addslashes($q['pertanyaan']), 'js') ?>', '<?= esc(addslashes($q['opsi_a'] ?? ''), 'js') ?>', '<?= esc(addslashes($q['opsi_b'] ?? ''), 'js') ?>', '<?= esc(addslashes($q['opsi_c'] ?? ''), 'js') ?>', '<?= $q['jawaban_benar'] ?>')">
                            <i class="ph ph-pencil-simple text-primary"></i> Edit
                        </button>
                        <a href="<?= base_url('admin/ujian/delete-question/' . $q['id_soal']) ?>" class="btn btn-ghost btn-sm text-error" onclick="return confirm('Hapus soal ini?')">
                            <i class="ph ph-trash"></i> Hapus
                        </a>
                    </div>
                </div>

                <div class="bg-base-200 p-4 rounded-lg mb-4">
                    <p class="text-lg font-medium"><?= nl2br(esc($q['pertanyaan'])) ?></p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="p-4 rounded-lg border-2 <?= $q['jawaban_benar'] == 'A' ? 'bg-success/10 border-success' : 'bg-base-200 border-base-300' ?> relative">
                        <?php if ($q['jawaban_benar'] == 'A'): ?>
                        <span class="absolute -top-3 right-4 badge badge-success">
                            <i class="ph ph-check-circle"></i> BENAR
                        </span>
                        <?php endif; ?>
                        <div class="flex items-start gap-3">
                            <span class="bg-primary text-primary-content w-8 h-8 flex items-center justify-center rounded-full font-bold text-sm">A</span>
                            <span class="mt-1"><?= nl2br(esc($q['opsi_a'])) ?></span>
                        </div>
                    </div>
                    <div class="p-4 rounded-lg border-2 <?= $q['jawaban_benar'] == 'B' ? 'bg-success/10 border-success' : 'bg-base-200 border-base-300' ?> relative">
                        <?php if ($q['jawaban_benar'] == 'B'): ?>
                        <span class="absolute -top-3 right-4 badge badge-success">
                            <i class="ph ph-check-circle"></i> BENAR
                        </span>
                        <?php endif; ?>
                        <div class="flex items-start gap-3">
                            <span class="bg-secondary text-secondary-content w-8 h-8 flex items-center justify-center rounded-full font-bold text-sm">B</span>
                            <span class="mt-1"><?= nl2br(esc($q['opsi_b'])) ?></span>
                        </div>
                    </div>
                    <div class="p-4 rounded-lg border-2 <?= $q['jawaban_benar'] == 'C' ? 'bg-success/10 border-success' : 'bg-base-200 border-base-300' ?> relative">
                        <?php if ($q['jawaban_benar'] == 'C'): ?>
                        <span class="absolute -top-3 right-4 badge badge-success">
                            <i class="ph ph-check-circle"></i> BENAR
                        </span>
                        <?php endif; ?>
                        <div class="flex items-start gap-3">
                            <span class="bg-accent text-accent-content w-8 h-8 flex items-center justify-center rounded-full font-bold text-sm">C</span>
                            <span class="mt-1"><?= nl2br(esc($q['opsi_c'])) ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- ==================== MODALS ==================== -->

<!-- Add Question Modal -->
<dialog id="add_question_modal" class="modal">
    <div class="modal-box max-w-2xl">
        <h3 class="font-bold text-lg mb-4">➕ Tambah Soal Baru</h3>
        <form action="<?= base_url('admin/ujian/store-question') ?>" method="POST">
            <input type="hidden" name="id_ujian" value="<?= $ujian['id_ujian'] ?>">

            <div class="form-control mb-4">
                <label class="label"><span class="label-text font-semibold">Pertanyaan</span></label>
                <textarea name="pertanyaan" class="textarea textarea-bordered" rows="3" placeholder="Ketik pertanyaan..." required></textarea>
            </div>

            <div class="divider">Opsi Jawaban</div>

            <div class="space-y-4">
                <div class="form-control">
                    <label class="label"><span class="label-text font-semibold text-primary">Opsi A</span></label>
                    <input type="text" name="opsi_a" class="input input-bordered" placeholder="Jawaban A" required>
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text font-semibold text-secondary">Opsi B</span></label>
                    <input type="text" name="opsi_b" class="input input-bordered" placeholder="Jawaban B" required>
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text font-semibold text-accent">Opsi C</span></label>
                    <input type="text" name="opsi_c" class="input input-bordered" placeholder="Jawaban C" required>
                </div>
            </div>

            <div class="form-control mt-4">
                <label class="label"><span class="label-text font-semibold">Jawaban Benar</span></label>
                <div class="flex gap-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="jawaban_benar" value="A" class="radio radio-primary" required>
                        <span class="font-bold text-primary">A</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="jawaban_benar" value="B" class="radio radio-secondary">
                        <span class="font-bold text-secondary">B</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="jawaban_benar" value="C" class="radio radio-accent">
                        <span class="font-bold text-accent">C</span>
                    </label>
                </div>
            </div>

            <div class="modal-action">
                <button type="button" class="btn btn-ghost" onclick="add_question_modal.close()">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Soal</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop"><button>close</button></form>
</dialog>

<!-- Edit Question Modal -->
<dialog id="edit_question_modal" class="modal">
    <div class="modal-box max-w-2xl">
        <h3 class="font-bold text-lg mb-4">✏️ Edit Soal</h3>
        <form action="" method="POST" id="edit_question_form">
            <input type="hidden" name="id_soal" id="edit_question_id">

            <div class="form-control mb-4">
                <label class="label"><span class="label-text font-semibold">Pertanyaan</span></label>
                <textarea name="pertanyaan" id="edit_question_text" class="textarea textarea-bordered" rows="3" required></textarea>
            </div>

            <div class="divider">Opsi Jawaban</div>

            <div class="space-y-4">
                <div class="form-control">
                    <label class="label"><span class="label-text font-semibold text-primary">Opsi A</span></label>
                    <input type="text" name="opsi_a" id="edit_question_a" class="input input-bordered" required>
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text font-semibold text-secondary">Opsi B</span></label>
                    <input type="text" name="opsi_b" id="edit_question_b" class="input input-bordered" required>
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text font-semibold text-accent">Opsi C</span></label>
                    <input type="text" name="opsi_c" id="edit_question_c" class="input input-bordered" required>
                </div>
            </div>

            <div class="form-control mt-4">
                <label class="label"><span class="label-text font-semibold">Jawaban Benar</span></label>
                <div class="flex gap-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="jawaban_benar" value="A" id="edit_jawaban_a" class="radio radio-primary">
                        <span class="font-bold text-primary">A</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="jawaban_benar" value="B" id="edit_jawaban_b" class="radio radio-secondary">
                        <span class="font-bold text-secondary">B</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="jawaban_benar" value="C" id="edit_jawaban_c" class="radio radio-accent">
                        <span class="font-bold text-accent">C</span>
                    </label>
                </div>
            </div>

            <div class="modal-action">
                <button type="button" class="btn btn-ghost" onclick="edit_question_modal.close()">Batal</button>
                <button type="submit" class="btn btn-primary">Update Soal</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop"><button>close</button></form>
</dialog>

<script>
    function openEditQuestionModal(id, pertanyaan, opsiA, opsiB, opsiC, jawabanBenar) {
        document.getElementById('edit_question_id').value = id;
        document.getElementById('edit_question_text').value = pertanyaan;
        document.getElementById('edit_question_a').value = opsiA;
        document.getElementById('edit_question_b').value = opsiB;
        document.getElementById('edit_question_c').value = opsiC;

        document.getElementById('edit_jawaban_a').checked = (jawabanBenar === 'A');
        document.getElementById('edit_jawaban_b').checked = (jawabanBenar === 'B');
        document.getElementById('edit_jawaban_c').checked = (jawabanBenar === 'C');

        document.getElementById('edit_question_form').action = '<?= base_url('admin/ujian/update-question') ?>/' + id;
        edit_question_modal.showModal();
    }
</script>

<?= $this->endSection() ?>