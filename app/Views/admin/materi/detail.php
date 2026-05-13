<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-8">
    <div class="flex items-center gap-4">
        <a href="<?= base_url('admin/materi') ?>" class="btn btn-circle btn-ghost">
            <i class="ph ph-arrow-left text-2xl"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold"><?= $produk['judul'] ?></h1>
            <p class="text-sm opacity-60">Kelola modul dan sub-materi video</p>
        </div>
    </div>
    
    <button class="btn btn-primary" onclick="add_module_modal.showModal()">
        <i class="ph ph-plus"></i> Tambah Modul (Bab)
    </button>
</div>

<div class="space-y-6">
    <?php if (empty($modules)): ?>
        <div class="text-center py-12 bg-base-200 rounded-xl border-2 border-dashed border-base-300">
            <i class="ph ph-stack text-4xl opacity-20 mb-2"></i>
            <p class="opacity-50">Belum ada modul untuk kelas ini.</p>
        </div>
    <?php else: ?>
        <?php foreach($modules as $mod): ?>
        <div class="collapse collapse-plus bg-base-100 border border-base-200 shadow-sm">
            <input type="checkbox" checked /> 
            <div class="collapse-title text-xl font-bold flex items-center justify-between pr-12">
                <span>Modul <?= $mod['urutan_kategori'] ?>: <?= $mod['judul_kategori'] ?></span>
                <div class="flex gap-2" onclick="event.stopPropagation()">
                    <button class="btn btn-square btn-ghost btn-sm text-primary"><i class="ph ph-pencil-simple"></i></button>
                    <button class="btn btn-square btn-ghost btn-sm text-error"><i class="ph ph-trash"></i></button>
                </div>
            </div>
            <div class="collapse-content">
                <div class="divider mt-0"></div>
                
                <div class="space-y-3 mb-4">
                    <?php if (empty($mod['videos'])): ?>
                        <p class="text-sm italic opacity-50 px-4">Belum ada video di modul ini.</p>
                    <?php else: ?>
                        <?php foreach($mod['videos'] as $vid): ?>
                        <div class="flex items-center justify-between p-3 bg-base-200 rounded-lg group">
                            <div class="flex items-center gap-4">
                                <div class="bg-primary/10 p-2 rounded text-primary">
                                    <i class="ph ph-video-camera text-xl"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-sm"><?= $vid['judul_video'] ?></p>
                                    <div class="flex gap-4 text-xs opacity-60">
                                        <span><i class="ph ph-clock"></i> <?= $vid['durasi_menit'] ?>m</span>
                                        <span><i class="ph ph-lock-key"></i> Unlock: <?= $vid['minimal_progress_unlock'] ?>%</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button class="btn btn-square btn-ghost btn-xs"><i class="ph ph-pencil-simple"></i></button>
                                <button class="btn btn-square btn-ghost btn-xs text-error"><i class="ph ph-trash"></i></button>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <button class="btn btn-outline btn-primary btn-sm btn-block" onclick="add_video_modal.showModal()">
                    <i class="ph ph-plus-circle"></i> Tambah Sub-Materi Video
                </button>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Modal Modals would go here -->

<?= $this->endSection() ?>
