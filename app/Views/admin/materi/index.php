<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6">
    <h1 class="text-2xl font-bold">Manajemen Kurikulum</h1>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <?php if (empty($classes)): ?>
        <div class="col-span-full text-center py-12 bg-base-200 rounded-xl opacity-50">
            Belum ada kelas yang ditugaskan kepada Anda.
        </div>
    <?php else: ?>
        <?php foreach($classes as $item): ?>
        <div class="card bg-base-100 shadow-xl border border-base-200 overflow-hidden group">
            <figure class="relative h-48">
                <img src="<?= $item['gambar'] ?? 'https://placehold.co/600x400?text=No+Image' ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                <div class="absolute top-2 right-2">
                    <div class="badge badge-primary"><?= $item['nama_kategori'] ?></div>
                </div>
            </figure>
            <div class="card-body">
                <h2 class="card-title text-lg leading-tight"><?= $item['judul'] ?></h2>
                <p class="text-sm opacity-70 line-clamp-2"><?= strip_tags($item['deskripsi']) ?></p>
                <div class="card-actions justify-end mt-4">
                    <a href="<?= base_url('admin/materi/detail/' . $item['id_produk']) ?>" class="btn btn-primary btn-block">
                        Kelola Kurikulum <i class="ph ph-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
