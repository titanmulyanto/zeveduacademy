<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6">
    <h1 class="text-2xl font-bold">Fitur Unggulan</h1>
    <button class="btn btn-primary">
        <i class="ph ph-plus"></i> Tambah Fitur
    </button>
</div>

<div class="alert alert-info shadow-sm mb-6">
    <i class="ph ph-info text-xl"></i>
    <span>Note untuk admin: Mohon upload gambar dengan rasio ukuran **4:3**.</span>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <?php foreach($features as $f): ?>
    <div class="card bg-base-100 shadow border border-base-200">
        <figure class="aspect-[4/3]">
            <img src="<?= $f['gambar'] ?>" class="w-full h-full object-cover" />
        </figure>
        <div class="card-body">
            <h2 class="card-title"><?= $f['judul'] ?></h2>
            <p class="text-sm opacity-70"><?= $f['deskripsi'] ?></p>
            <div class="card-actions justify-end mt-4">
                <button class="btn btn-ghost btn-sm text-primary">Edit</button>
                <button class="btn btn-ghost btn-sm text-error">Hapus</button>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?= $this->endSection() ?>
