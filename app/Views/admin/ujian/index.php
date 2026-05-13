<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6">
    <h1 class="text-2xl font-bold">Manajemen Ujian Sertifikasi</h1>
    <a href="<?= base_url('admin/ujian/results') ?>" class="btn btn-outline btn-secondary">
        <i class="ph ph-list-numbers"></i> Lihat Semua Hasil Ujian
    </a>
</div>

<div class="card bg-base-100 shadow border border-base-200">
    <div class="card-body p-0">
        <div class="overflow-x-auto">
            <table class="table table-zebra w-full">
                <thead>
                    <tr>
                        <th>Kelas / Produk</th>
                        <th>Durasi</th>
                        <th>Status Ujian</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($exams as $exam): ?>
                    <tr>
                        <td>
                            <div class="font-bold"><?= $exam['judul'] ?></div>
                            <div class="text-xs opacity-50">ID: <?= $exam['id_produk'] ?></div>
                        </td>
                        <td><?= $exam['durasi_menit'] ? $exam['durasi_menit'].' Menit' : '-' ?></td>
                        <td>
                            <?php if($exam['id_ujian']): ?>
                                <div class="badge badge-success gap-2">Aktif</div>
                            <?php else: ?>
                                <div class="badge badge-ghost gap-2">Belum Dibuat</div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="flex justify-center gap-2">
                                <?php if($exam['id_ujian']): ?>
                                    <a href="<?= base_url('admin/ujian/questions/' . $exam['id_ujian']) ?>" class="btn btn-sm btn-primary">
                                        Kelola Soal
                                    </a>
                                    <button class="btn btn-square btn-ghost btn-sm">
                                        <i class="ph ph-gear text-xl"></i>
                                    </button>
                                <?php else: ?>
                                    <button class="btn btn-sm btn-outline">Buat Ujian</button>
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
