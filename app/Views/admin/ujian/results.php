<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="page-header">
    <div>
        <h1 class="page-title">Hasil Ujian Siswa</h1>
        <p class="page-subtitle">Riwayat dan hasil ujian seluruh siswa</p>
    </div>
    <div class="page-actions">
        <a href="<?= base_url('admin/ujian') ?>" class="btn btn-ghost">
            <i class="ph ph-arrow-left"></i>
            Kembali
        </a>
    </div>
</div>

<!-- Stats Cards -->
<div class="stats-grid mb-8">
    <div class="stat-card animate-scale-in">
        <div class="stat-icon text-primary">
            <i class="ph ph-users"></i>
        </div>
        <div class="stat-content">
            <p class="stat-label">Total Peserta</p>
            <p class="stat-value text-primary"><?= count($results ?? []) ?></p>
            <p class="stat-desc">Siswa yang mengerjakan</p>
        </div>
    </div>

    <div class="stat-card animate-scale-in">
        <div class="stat-icon text-success">
            <i class="ph ph-check-circle"></i>
        </div>
        <div class="stat-content">
            <p class="stat-label">Lulus</p>
            <p class="stat-value text-success">
                <?php
                $lulus = 0;
                if (!empty($results)) {
                    foreach ($results as $res) {
                        if (($res['status_lulus'] ?? '') == 'lulus') $lulus++;
                    }
                }
                echo $lulus;
                ?>
            </p>
            <p class="stat-desc">Memenuhi standar</p>
        </div>
    </div>

    <div class="stat-card animate-scale-in">
        <div class="stat-icon text-error">
            <i class="ph ph-x-circle"></i>
        </div>
        <div class="stat-content">
            <p class="stat-label">Tidak Lulus</p>
            <p class="stat-value text-error"><?= count($results ?? []) - $lulus ?></p>
            <p class="stat-desc">Perlu remedial</p>
        </div>
    </div>

    <div class="stat-card animate-scale-in">
        <div class="stat-icon text-info">
            <i class="ph ph-chart-line"></i>
        </div>
        <div class="stat-content">
            <p class="stat-label">Rata-rata Nilai</p>
            <p class="stat-value text-info">
                <?php
                $avg = 0;
                if (!empty($results)) {
                    $total = 0;
                    foreach ($results as $res) {
                        $total += ($res['nilai'] ?? 0);
                    }
                    $avg = round($total / count($results));
                }
                echo $avg;
                ?>
            </p>
            <p class="stat-desc">Dari semua ujian</p>
        </div>
    </div>
</div>

<!-- Results Table -->
<div class="card animate-slide-up">
    <div class="card-body p-0">
        <?php if (empty($results)): ?>
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="ph ph-exam"></i>
                </div>
                <h3 class="empty-state-title">Belum Ada Hasil Ujian</h3>
                <p class="empty-state-description">Belum ada siswa yang menempuh ujian.</p>
            </div>
        <?php else: ?>
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Siswa</th>
                            <th>Kelas</th>
                            <th class="text-center">Nilai</th>
                            <th>Status</th>
                            <th>Tanggal Ujian</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($results as $res): ?>
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="avatar avatar-sm">
                                        <div class="avatar-content">
                                            <?= strtoupper(substr($res['nama_lengkap'] ?? 'S', 0, 1)) ?>
                                        </div>
                                    </div>
                                    <span class="font-medium text-slate-800"><?= esc($res['nama_lengkap'] ?? '-') ?></span>
                                </div>
                            </td>
                            <td class="text-slate-600"><?= esc($res['nama_kelas'] ?? '-') ?></td>
                            <td class="text-center">
                                <span class="text-2xl font-bold <?= ($res['status_lulus'] ?? '') == 'lulus' ? 'text-success' : 'text-error' ?>">
                                    <?= $res['nilai'] ?? 0 ?>
                                </span>
                            </td>
                            <td>
                                <?php if(($res['status_lulus'] ?? '') == 'lulus'): ?>
                                    <span class="badge badge-success">
                                        <i class="ph ph-check-circle mr-1"></i>
                                        LULUS
                                    </span>
                                <?php else: ?>
                                    <span class="badge badge-error">
                                        <i class="ph ph-x-circle mr-1"></i>
                                        TIDAK LULUS
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="text-slate-500 text-sm">
                                <?= date('d M Y, H:i', strtotime($res['tanggal_ujian'] ?? 'now')) ?>
                            </td>
                            <td>
                                <div class="flex justify-center gap-2">
                                    <a href="<?= base_url('admin/ujian/view_result/' . ($res['id_hasil'] ?? '')) ?>"
                                       class="btn btn-sm btn-ghost text-primary hover:bg-primary-light"
                                       title="Detail">
                                        <i class="ph ph-eye text-lg"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>