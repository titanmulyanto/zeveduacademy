<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<!-- Breadcrumbs -->
<div class="breadcrumbs-modern mb-4">
    <div class="breadcrumbs-modern-item">
        <a href="<?= base_url('admin/sertifikat') ?>">
            <i class="ph ph-certificate"></i>
            <span>Kelola Sertifikat</span>
        </a>
    </div>
    <span class="breadcrumbs-modern-separator">
        <i class="ph ph-caret-right"></i>
    </span>
    <div class="breadcrumbs-modern-item">
        <span class="breadcrumbs-modern-current"><?= esc($produk['judul'] ?? 'Kelas') ?></span>
    </div>
</div>

<!-- Page Header -->
<div class="page-header">
    <div>
        <h1 class="page-title">Sertifikat User</h1>
        <p class="page-subtitle"><?= esc($produk['judul'] ?? '') ?></p>
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

<!-- Students with Certificates -->
<div class="card mb-6 animate-slide-up">
    <div class="card-header">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-success-light flex items-center justify-center">
                <i class="ph ph-certificate text-success text-lg"></i>
            </div>
            <div>
                <h3 class="card-title">Sertifikat Terbit</h3>
                <p class="text-xs text-slate-500"><?= count($certificates ?? []) ?> siswa memiliki sertifikat</p>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <?php if (empty($certificates)): ?>
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="ph ph-certificate"></i>
                </div>
                <h3 class="empty-state-title">Belum Ada Sertifikat</h3>
                <p class="empty-state-description">Belum ada siswa yang mendapatkan sertifikat untuk kelas ini.</p>
            </div>
        <?php else: ?>
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Siswa</th>
                            <th>No. Sertifikat</th>
                            <th>Tanggal Terbit</th>
                            <th>Status Ujian</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($certificates as $cert): ?>
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="avatar avatar-md">
                                        <?php if (!empty($cert['foto_profil'])): ?>
                                            <img src="<?= base_url($cert['foto_profil']) ?>" alt="" class="w-full h-full object-cover" />
                                        <?php else: ?>
                                            <div class="avatar-content bg-success text-white">
                                                <?= strtoupper(substr($cert['nama_lengkap'] ?? 'S', 0, 1)) ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-slate-800"><?= esc($cert['nama_lengkap'] ?? '-') ?></p>
                                        <p class="text-xs text-slate-500"><?= esc($cert['email'] ?? '-') ?></p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <code class="bg-slate-100 px-2 py-1 rounded text-sm font-mono"><?= esc($cert['nomor_sertifikat'] ?? '-') ?></code>
                            </td>
                            <td>
                                <span class="text-slate-600"><?= date('d/m/Y', strtotime($cert['tanggal_terbit'] ?? 'now')) ?></span>
                            </td>
                            <td>
                                <?php if (!empty($cert['nilai_ujian'])): ?>
                                    <span class="badge <?= ($cert['status_lulus'] ?? '') === 'lulus' ? 'badge-success' : 'badge-error' ?>">
                                        <?= ($cert['status_lulus'] ?? '') === 'lulus' ? 'Lulus' : 'Tidak Lulus' ?>
                                        (<?= $cert['nilai_ujian'] ?? 0 ?>)
                                    </span>
                                <?php else: ?>
                                    <span class="badge badge-ghost">Belum Ujian</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="flex justify-center gap-2">
                                    <a href="<?= base_url('admin/sertifikat/preview/' . $cert['id_sertifikat']) ?>"
                                       class="btn btn-sm btn-ghost text-info hover:bg-info-light"
                                       title="Preview">
                                        <i class="ph ph-eye"></i>
                                    </a>
                                    <?php if (!empty($cert['file_pdf'])): ?>
                                        <a href="<?= base_url($cert['file_pdf']) ?>"
                                           target="_blank"
                                           class="btn btn-sm btn-ghost text-success hover:bg-success-light"
                                           title="Download PDF">
                                            <i class="ph ph-download"></i>
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

<!-- Students Without Certificates -->
<?php if (!empty($studentsWithoutCert)): ?>
<div class="card animate-slide-up">
    <div class="card-header">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-warning-light flex items-center justify-center">
                <i class="ph ph-clock text-warning text-lg"></i>
            </div>
            <div>
                <h3 class="card-title">Belum Memiliki Sertifikat</h3>
                <p class="text-xs text-slate-500"><?= count($studentsWithoutCert) ?> siswa belum mendapatkan sertifikat</p>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Siswa</th>
                        <th>Status Akses</th>
                        <th>Tanggal Daftar</th>
                        <th>Status Ujian</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($studentsWithoutCert as $student): ?>
                    <tr>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="avatar avatar-md">
                                    <?php if (!empty($student['foto_profil'])): ?>
                                        <img src="<?= base_url($student['foto_profil']) ?>" alt="" class="w-full h-full object-cover" />
                                    <?php else: ?>
                                        <div class="avatar-content bg-warning text-white">
                                            <?= strtoupper(substr($student['nama_lengkap'] ?? 'S', 0, 1)) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <p class="font-semibold text-slate-800"><?= esc($student['nama_lengkap'] ?? '-') ?></p>
                                    <p class="text-xs text-slate-500"><?= esc($student['email'] ?? '-') ?></p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge <?= ($student['status_akses'] ?? '') === 'aktif' ? 'badge-success' : 'badge-error' ?>">
                                <?= ($student['status_akses'] ?? '') === 'aktif' ? 'Aktif' : 'Nonaktif' ?>
                            </span>
                        </td>
                        <td>
                            <span class="text-slate-600"><?= date('d/m/Y', strtotime($student['tanggal_aktif'] ?? 'now')) ?></span>
                        </td>
                        <td>
                            <?php if (!empty($student['nilai_ujian'])): ?>
                                <span class="badge <?= ($student['status_lulus'] ?? '') === 'lulus' ? 'badge-success' : 'badge-error' ?>">
                                    <?= ($student['status_lulus'] ?? '') === 'lulus' ? 'Lulus' : 'Tidak Lulus' ?>
                                    (<?= $student['nilai_ujian'] ?? 0 ?>)
                                </span>
                            <?php else: ?>
                                <span class="badge badge-ghost">Belum Ujian</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <form action="<?= base_url('admin/sertifikat/issue-certificate/' . ($produk['id_produk'] ?? '')) ?>" method="POST" class="inline">
                                <?= csrf_field() ?>
                                <input type="hidden" name="id_user" value="<?= $student['id_user'] ?? '' ?>" />
                                <button type="submit" class="btn btn-sm btn-primary"
                                        onclick="return confirm('Terbitkan sertifikat untuk <?= esc($student['nama_lengkap'] ?? '') ?>?')">
                                    <i class="ph ph-certificate mr-1"></i>
                                    Terbitkan
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php endif; ?>

<?= $this->endSection() ?>
