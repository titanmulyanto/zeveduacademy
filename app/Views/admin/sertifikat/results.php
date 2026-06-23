<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<div class="breadcrumbs mb-4">
    <a href="<?= base_url('admin/sertifikat') ?>" class="text-primary">Kelas & Sertifikat</a>
    <span class="text-base-content/30">/</span>
    <span>Hasil Sertifikat</span>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="stat bg-base-100 shadow border border-base-200 rounded-lg">
        <div class="stat-figure text-primary">
            <i class="ph ph-certificate text-4xl"></i>
        </div>
        <div class="stat-title">Total Sertifikat</div>
        <div class="stat-value text-primary"><?= $stats['total'] ?></div>
        <div class="stat-desc">Sertifikat yang pernah diterbitkan</div>
    </div>
    <div class="stat bg-base-100 shadow border border-base-200 rounded-lg">
        <div class="stat-figure text-success">
            <i class="ph ph-calendar-check text-4xl"></i>
        </div>
        <div class="stat-title">Bulan Ini</div>
        <div class="stat-value text-success"><?= $stats['this_month'] ?></div>
        <div class="stat-desc">Sertifikat diterbitkan <?= date('F Y') ?></div>
    </div>
    <div class="stat bg-base-100 shadow border border-base-200 rounded-lg">
        <div class="stat-figure text-info">
            <a href="<?= base_url('admin/sertifikat/earnings') ?>" class="btn btn-sm btn-ghost">
                <i class="ph ph-arrow-right"></i>
            </a>
        </div>
        <div class="stat-title">Pendapatan</div>
        <div class="stat-value text-info">Lihat</div>
        <div class="stat-desc">
            <a href="<?= base_url('admin/sertifikat/earnings') ?>" class="text-primary hover:underline">Rincian Pendapatan →</a>
        </div>
    </div>
</div>

<!-- Filter & Search -->
<div class="flex justify-between items-center mb-4">
    <h1 class="text-2xl font-bold">Hasil Sertifikat Siswa</h1>
    <div class="flex gap-2">
        <a href="<?= base_url('admin/ujian/results') ?>" class="btn btn-outline btn-sm">
            <i class="ph ph-exam"></i> Hasil Ujian
        </a>
    </div>
</div>

<div class="card bg-base-100 shadow border border-base-200">
    <div class="card-body p-0">
        <div class="overflow-x-auto">
            <table class="table table-zebra w-full">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Student</th>
                        <th>Kelas</th>
                        <th>No. Sertifikat</th>
                        <th>Tanggal Terbit</th>
                        <th>File PDF</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($certificates)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-12">
                                <i class="ph ph-certificate text-5xl text-base-content/30"></i>
                                <p class="mt-4 text-base-content/60">Belum ada sertifikat yang diterbitkan.</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; foreach ($certificates as $cert): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="avatar">
                                        <div class="w-10 h-10 rounded-full">
                                            <img src="<?= $cert['foto_profil'] ?? 'https://ui-avatars.com/api/?name=' . urlencode($cert['nama_lengkap']) ?>" />
                                        </div>
                                    </div>
                                    <div>
                                        <div class="font-bold"><?= esc($cert['nama_lengkap']) ?></div>
                                        <div class="text-xs opacity-60"><?= esc($cert['email']) ?></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="font-medium"><?= esc($cert['nama_produk']) ?></div>
                                <?php if (($cert['harga_kelas'] ?? 0) > 0): ?>
                                    <span class="badge badge-warning badge-sm">BERBAYAR</span>
                                <?php else: ?>
                                    <span class="badge badge-success badge-sm">GRATIS</span>
                                <?php endif; ?>
                            </td>
                            <td class="font-mono text-sm"><?= esc($cert['nomor_sertifikat']) ?></td>
                            <td><?= date('d/m/Y', strtotime($cert['tanggal_terbit'])) ?></td>
                            <td>
                                <?php if (!empty($cert['file_pdf'])): ?>
                                    <span class="badge badge-success badge-sm">
                                        <i class="ph ph-file-pdf"></i> Ada
                                    </span>
                                <?php else: ?>
                                    <span class="badge badge-ghost badge-sm">Belum</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="flex justify-center gap-1">
                                    <?php if (!empty($cert['background_image'])): ?>
                                        <a href="<?= base_url('admin/sertifikat/preview/' . $cert['id_produk'] . '?student_id=' . $cert['id_user']) ?>"
                                           class="btn btn-ghost btn-sm" title="Preview">
                                            <i class="ph ph-eye"></i>
                                        </a>
                                    <?php endif; ?>
                                    <?php if (!empty($cert['file_pdf'])): ?>
                                        <a href="<?= base_url($cert['file_pdf']) ?>" target="_blank" class="btn btn-ghost btn-sm" title="Download PDF">
                                            <i class="ph ph-download"></i>
                                        </a>
                                    <?php endif; ?>
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