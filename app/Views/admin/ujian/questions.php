<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-8">
    <div class="flex items-center gap-4">
        <a href="<?= base_url('admin/ujian') ?>" class="btn btn-circle btn-ghost">
            <i class="ph ph-arrow-left text-2xl"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold"><?= $title ?></h1>
            <p class="text-sm opacity-60">Kelola bank soal ujian sertifikasi</p>
        </div>
    </div>
    
    <button class="btn btn-primary" onclick="add_question_modal.showModal()">
        <i class="ph ph-plus"></i> Tambah Soal
    </button>
</div>

<div class="grid grid-cols-1 gap-6">
    <?php if (empty($questions)): ?>
        <div class="text-center py-12 bg-base-200 rounded-xl border-2 border-dashed border-base-300">
            <p class="opacity-50">Belum ada soal untuk ujian ini.</p>
        </div>
    <?php else: ?>
        <?php foreach($questions as $index => $q): ?>
        <div class="card bg-base-100 shadow border border-base-200">
            <div class="card-body">
                <div class="flex justify-between items-start">
                    <h3 class="font-bold">Pertanyaan <?= $index + 1 ?></h3>
                    <div class="flex gap-1">
                        <button class="btn btn-square btn-ghost btn-xs text-primary"><i class="ph ph-pencil-simple"></i></button>
                        <button class="btn btn-square btn-ghost btn-xs text-error"><i class="ph ph-trash"></i></button>
                    </div>
                </div>
                <p class="my-4 text-lg"><?= $q['pertanyaan'] ?></p>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="p-3 rounded-lg border <?= $q['jawaban_benar'] == 'A' ? 'bg-success/10 border-success' : 'bg-base-200 border-base-300' ?> relative">
                        <span class="absolute top-2 right-2 text-xs font-bold text-success <?= $q['jawaban_benar'] == 'A' ? '' : 'hidden' ?>">BENAR</span>
                        <div class="flex items-center gap-2">
                            <span class="bg-base-100 w-6 h-6 flex items-center justify-center rounded-full text-xs font-bold">A</span>
                            <span><?= $q['opsi_a'] ?></span>
                        </div>
                    </div>
                    <div class="p-3 rounded-lg border <?= $q['jawaban_benar'] == 'B' ? 'bg-success/10 border-success' : 'bg-base-200 border-base-300' ?> relative">
                        <span class="absolute top-2 right-2 text-xs font-bold text-success <?= $q['jawaban_benar'] == 'B' ? '' : 'hidden' ?>">BENAR</span>
                        <div class="flex items-center gap-2">
                            <span class="bg-base-100 w-6 h-6 flex items-center justify-center rounded-full text-xs font-bold">B</span>
                            <span><?= $q['opsi_b'] ?></span>
                        </div>
                    </div>
                    <div class="p-3 rounded-lg border <?= $q['jawaban_benar'] == 'C' ? 'bg-success/10 border-success' : 'bg-base-200 border-base-300' ?> relative">
                        <span class="absolute top-2 right-2 text-xs font-bold text-success <?= $q['jawaban_benar'] == 'C' ? '' : 'hidden' ?>">BENAR</span>
                        <div class="flex items-center gap-2">
                            <span class="bg-base-100 w-6 h-6 flex items-center justify-center rounded-full text-xs font-bold">C</span>
                            <span><?= $q['opsi_c'] ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Modal Add Question would go here -->

<?= $this->endSection() ?>
