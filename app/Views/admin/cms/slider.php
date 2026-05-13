<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6">
    <h1 class="text-2xl font-bold">Kelola Slider Banner</h1>
    <button class="btn btn-primary" onclick="add_slider_modal.showModal()">
        <i class="ph ph-plus"></i> Tambah Banner
    </button>
</div>

<div class="alert alert-info shadow-sm mb-6">
    <i class="ph ph-info text-xl"></i>
    <span>Note untuk admin: Mohon upload gambar dengan rasio ukuran **1:1** untuk hasil terbaik.</span>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <?php foreach($sliders as $s): ?>
    <div class="card bg-base-100 shadow border border-base-200 overflow-hidden">
        <figure class="aspect-square relative">
            <img src="<?= $s['gambar'] ?>" class="w-full h-full object-cover" />
            <div class="absolute top-2 left-2 badge badge-neutral">Urutan: <?= $s['urutan'] ?></div>
        </figure>
        <div class="card-body p-4 flex-row justify-between items-center">
            <div class="flex gap-2">
                <button class="btn btn-square btn-ghost btn-sm text-primary"><i class="ph ph-pencil-simple"></i></button>
                <button class="btn btn-square btn-ghost btn-sm text-error"><i class="ph ph-trash"></i></button>
            </div>
            <div class="flex gap-1">
                <button class="btn btn-square btn-ghost btn-xs"><i class="ph ph-caret-up"></i></button>
                <button class="btn btn-square btn-ghost btn-xs"><i class="ph ph-caret-down"></i></button>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?= $this->endSection() ?>
