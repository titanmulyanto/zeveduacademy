<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6">
    <h1 class="text-2xl font-bold">Kelola Sertifikat</h1>
</div>

<div class="card bg-base-100 shadow border border-base-200">
    <div class="card-body p-0">
        <div class="overflow-x-auto">
            <table class="table table-zebra w-full">
                <thead>
                    <tr>
                        <th>Kelas / Produk</th>
                        <th>Template Background</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($templates as $tpl): ?>
                    <tr>
                        <td>
                            <div class="font-bold"><?= $tpl['judul'] ?></div>
                        </td>
                        <td>
                            <?php if($tpl['background_template']): ?>
                                <div class="avatar">
                                    <div class="w-24 rounded">
                                        <img src="<?= $tpl['background_template'] ?>" />
                                    </div>
                                </div>
                            <?php else: ?>
                                <span class="badge badge-ghost">Belum ada template</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="flex justify-center gap-2">
                                <button class="btn btn-sm btn-primary">Upload Template</button>
                                <?php if($tpl['background_template']): ?>
                                    <a href="<?= base_url('admin/sertifikat/preview/'.$tpl['id_sertifikat']) ?>" class="btn btn-sm btn-outline">Preview</a>
                                <?php endif; ?>
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
