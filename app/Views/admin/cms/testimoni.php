<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6">
    <h1 class="text-2xl font-bold">Kelola Testimoni</h1>
    <button class="btn btn-primary" onclick="add_testi_modal.showModal()">
        <i class="ph ph-plus"></i> Tambah Testimoni
    </button>
</div>

<div class="alert alert-info shadow-sm mb-6">
    <i class="ph ph-info text-xl"></i>
    <span>Note untuk admin: Mohon upload foto profil dengan rasio **1:1**. Rating menggunakan angka **1-10**.</span>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <?php foreach($testimonials as $t): ?>
    <div class="card bg-base-100 shadow border border-base-200 p-6">
        <div class="flex gap-4 items-start">
            <div class="avatar">
                <div class="w-16 rounded-full ring ring-primary ring-offset-base-100 ring-offset-2">
                    <img src="<?= $t['foto_profil'] ?>" />
                </div>
            </div>
            <div class="flex-1">
                <div class="flex justify-between items-center">
                    <h3 class="font-bold text-lg"><?= $t['nama'] ?></h3>
                    <div class="badge badge-warning font-bold">⭐ <?= $t['rating'] ?>/10</div>
                </div>
                <p class="text-sm italic opacity-70 mt-2">"<?= $t['deskripsi'] ?>"</p>
                <div class="flex justify-end mt-4 gap-2">
                    <button class="btn btn-ghost btn-sm text-primary">Edit</button>
                    <button class="btn btn-ghost btn-sm text-error">Hapus</button>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?= $this->endSection() ?>
