<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6">
    <h1 class="text-2xl font-bold">Hasil Ujian Siswa</h1>
</div>

<div class="card bg-base-100 shadow border border-base-200">
    <div class="card-body p-0">
        <div class="overflow-x-auto">
            <table class="table table-zebra w-full">
                <thead>
                    <tr>
                        <th>Siswa</th>
                        <th>Kelas / Produk</th>
                        <th>Nilai</th>
                        <th>Status</th>
                        <th>Tanggal Ujian</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($results)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-8 opacity-50">Belum ada siswa yang menempuh ujian.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($results as $res): ?>
                        <tr>
                            <td>
                                <div class="font-bold"><?= $res['nama_lengkap'] ?></div>
                            </td>
                            <td><?= $res['nama_kelas'] ?></td>
                            <td>
                                <div class="font-bold text-lg"><?= $res['nilai'] ?></div>
                            </td>
                            <td>
                                <?php if($res['status_lulus'] == 'lulus'): ?>
                                    <div class="badge badge-success gap-2">LULUS</div>
                                <?php else: ?>
                                    <div class="badge badge-error gap-2">TIDAK LULUS</div>
                                <?php endif; ?>
                            </td>
                            <td class="text-xs"><?= date('d M Y, H:i', strtotime($res['tanggal_ujian'])) ?></td>
                            <td>
                                <div class="flex justify-center">
                                    <button class="btn btn-sm btn-ghost text-primary">Detail Soal</button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
