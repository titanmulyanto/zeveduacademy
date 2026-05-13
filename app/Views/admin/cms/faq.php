<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6">
    <h1 class="text-2xl font-bold">Kelola FAQ</h1>
    <button class="btn btn-primary">
        <i class="ph ph-plus"></i> Tambah FAQ
    </button>
</div>

<div class="card bg-base-100 shadow border border-base-200">
    <div class="card-body p-0">
        <div class="overflow-x-auto">
            <table class="table table-zebra">
                <thead>
                    <tr>
                        <th>Pertanyaan</th>
                        <th>Jawaban</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($faqs as $fq): ?>
                    <tr>
                        <td class="font-bold"><?= $fq['pertanyaan'] ?></td>
                        <td class="text-sm opacity-70"><?= $fq['jawaban'] ?></td>
                        <td>
                            <div class="flex justify-center gap-2">
                                <button class="btn btn-square btn-ghost btn-sm text-primary"><i class="ph ph-pencil-simple"></i></button>
                                <button class="btn btn-square btn-ghost btn-sm text-error"><i class="ph ph-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
