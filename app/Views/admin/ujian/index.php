<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="page-header">
    <div>
        <h1 class="page-title">Manajemen Ujian Sertifikasi</h1>
        <p class="page-subtitle">Kelola soal dan jadwal ujian untuk setiap kelas</p>
    </div>
    <div class="page-actions">
        <a href="<?= base_url('admin/ujian/results') ?>" class="btn btn-secondary">
            <i class="ph ph-list-numbers"></i>
            Lihat Semua Hasil
        </a>
    </div>
</div>

<!-- Alert Messages -->
<?php if (session()->getFlashdata('success')): ?>
<div class="alert alert-success mb-6 animate-slide-up">
    <div class="alert-icon">
        <i class="ph-fill ph-check-circle"></i>
    </div>
    <span><?= session()->getFlashdata('success') ?></span>
</div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
<div class="alert alert-error mb-6 animate-slide-up">
    <div class="alert-icon">
        <i class="ph-fill ph-warning-circle"></i>
    </div>
    <span><?= session()->getFlashdata('error') ?></span>
</div>
<?php endif; ?>

<!-- Exams Table Card -->
<div class="card animate-slide-up">
    <div class="card-header">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-primary-light flex items-center justify-center">
                <i class="ph ph-exam text-primary text-lg"></i>
            </div>
            <div>
                <h3 class="card-title">Daftar Ujian</h3>
                <p class="text-xs text-slate-500"><?= count($exams ?? []) ?> kelas ditemukan</p>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <?php if (empty($exams)): ?>
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="ph ph-exam"></i>
                </div>
                <h3 class="empty-state-title">Belum Ada Data</h3>
                <p class="empty-state-description">Tidak ada data ujian yang ditemukan.</p>
            </div>
        <?php else: ?>
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Kelas / Produk</th>
                            <th>Durasi</th>
                            <th>Nilai Minimal</th>
                            <th>Jumlah Soal</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($exams as $exam): ?>
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center overflow-hidden">
                                        <?php if (!empty($exam['gambar'])): ?>
                                            <img src="<?= base_url($exam['gambar']) ?>" alt="" class="w-full h-full object-cover">
                                        <?php else: ?>
                                            <i class="ph ph-books text-slate-400"></i>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-slate-800"><?= esc($exam['judul'] ?? '-') ?></p>
                                        <p class="text-xs text-slate-500">ID: <?= $exam['id_produk'] ?? '-' ?></p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <?php if (!empty($exam['durasi_menit'])): ?>
                                    <span class="badge badge-neutral">
                                        <i class="ph ph-clock mr-1"></i>
                                        <?= $exam['durasi_menit'] ?> Menit
                                    </span>
                                <?php else: ?>
                                    <span class="text-slate-400">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!empty($exam['nilai_minimal_lulus'])): ?>
                                    <span class="font-semibold text-slate-700"><?= $exam['nilai_minimal_lulus'] ?></span>
                                <?php else: ?>
                                    <span class="text-slate-400">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!empty($exam['jumlah_soal'])): ?>
                                    <span class="font-semibold text-slate-700"><?= $exam['jumlah_soal'] ?> Soal</span>
                                <?php else: ?>
                                    <span class="badge badge-ghost">Belum ada</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if(!empty($exam['id_ujian'])): ?>
                                    <span class="badge badge-success">
                                        <i class="ph ph-check-circle mr-1"></i>
                                        Aktif
                                    </span>
                                <?php else: ?>
                                    <span class="badge badge-neutral">
                                        <i class="ph ph-hourglass mr-1"></i>
                                        Belum Dibuat
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="flex justify-center gap-2">
                                    <?php if(!empty($exam['id_ujian'])): ?>
                                        <a href="<?= base_url('admin/ujian/edit/' . $exam['id_ujian']) ?>"
                                           class="btn btn-sm btn-ghost text-warning hover:bg-warning-light"
                                           title="Edit Ujian">
                                            <i class="ph ph-pencil text-lg"></i>
                                        </a>
                                        <a href="<?= base_url('admin/ujian/questions/' . $exam['id_ujian']) ?>"
                                           class="btn btn-sm btn-primary"
                                           title="Kelola Soal">
                                            <i class="ph ph-list-numbers mr-1"></i>
                                            Soal
                                        </a>
                                        <a href="<?= base_url('admin/ujian/view_result/' . $exam['id_ujian']) ?>"
                                           class="btn btn-sm btn-ghost text-info hover:bg-info-light"
                                           title="Lihat Hasil">
                                            <i class="ph ph-chart-bar text-lg"></i>
                                        </a>
                                    <?php else: ?>
                                        <a href="<?= base_url('admin/ujian/create/' . $exam['id_produk']) ?>"
                                           class="btn btn-sm btn-primary">
                                            <i class="ph ph-plus mr-1"></i>
                                            Buat Ujian
                                        </a>
                                    <?php endif; ?>
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