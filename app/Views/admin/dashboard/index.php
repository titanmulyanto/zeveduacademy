<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="page-header">
    <div>
        <h1 class="page-title">Dashboard Overview</h1>
        <p class="page-subtitle">Selamat datang di panel administrasi ZevedU Academy</p>
    </div>
</div>

<!-- Stats Cards Grid -->
<div class="stats-grid mb-8">
    <!-- Total Students -->
    <div class="stat-card animate-scale-in stagger-item">
        <div class="stat-icon text-primary">
            <i class="ph ph-users"></i>
        </div>
        <div class="stat-content">
            <p class="stat-label">Total Students</p>
            <p class="stat-value text-primary"><?= number_format($stats['total_student'] ?? 0) ?></p>
            <p class="stat-desc">Student aktif: <?= number_format($stats['siswa_aktif'] ?? 0) ?></p>
        </div>
    </div>

    <!-- Total Transaksi -->
    <div class="stat-card animate-scale-in stagger-item">
        <div class="stat-icon text-secondary">
            <i class="ph ph-hand-coins"></i>
        </div>
        <div class="stat-content">
            <p class="stat-label">Total Transaksi</p>
            <p class="stat-value text-secondary"><?= number_format($stats['total_transaksi'] ?? 0) ?></p>
            <p class="stat-desc text-secondary">Berhasil: <?= number_format($stats['transaksi_berhasil'] ?? 0) ?></p>
        </div>
    </div>

    <!-- Sertifikat Terbit -->
    <div class="stat-card animate-scale-in stagger-item">
        <div class="stat-icon" style="background: #EDE9FE; color: #7C3AED;">
            <i class="ph ph-certificate"></i>
        </div>
        <div class="stat-content">
            <p class="stat-label">Sertifikat Terbit</p>
            <p class="stat-value" style="color: #7C3AED;"><?= number_format($stats['jumlah_sertifikat'] ?? 0) ?></p>
            <p class="stat-desc">Alumni: <?= number_format($stats['jumlah_alumni'] ?? 0) ?></p>
        </div>
    </div>

    <!-- File Diunggah - NOW CLICKABLE -->
    <button onclick="showFilesModal()" class="stat-card animate-scale-in stagger-item text-left w-full cursor-pointer hover:shadow-lg transition-shadow">
        <div class="stat-icon text-warning">
            <i class="ph ph-file-text"></i>
        </div>
        <div class="stat-content">
            <p class="stat-label">File Diunggah</p>
            <p class="stat-value text-warning"><?= number_format($stats['total_file_unggah'] ?? 0) ?></p>
            <p class="stat-desc">Klik untuk lihat detail <i class="ph ph-arrow-right text-xs"></i></p>
        </div>
    </button>
</div>

<!-- Second Row Stats -->
<div class="stats-grid mb-8">
    <!-- Storage Usage -->
    <div class="stat-card animate-scale-in stagger-item">
        <div class="stat-icon text-info">
            <i class="ph ph-hard-drive"></i>
        </div>
        <div class="stat-content">
            <p class="stat-label">Storage Usage</p>
            <p class="stat-value text-lg"><?= $storage['used'] ?> <span class="text-slate-400">/ <?= $storage['limit'] ?></span></p>
            <div class="mt-3">
                <div class="progress-bar">
                    <div class="progress-bar-fill bg-primary" style="width: <?= $storage['percent'] ?>%"></div>
                </div>
                <p class="text-xs text-slate-500 mt-2 text-right"><?= $storage['percent'] ?>% used</p>
            </div>
        </div>
    </div>

    <!-- Total Admin -->
    <div class="stat-card animate-scale-in stagger-item">
        <div class="stat-icon text-success">
            <i class="ph ph-shield-check"></i>
        </div>
        <div class="stat-content">
            <p class="stat-label">Total Admin</p>
            <p class="stat-value text-success"><?= number_format($stats['total_admin'] ?? 0) ?></p>
            <p class="stat-desc">Admin & Super Admin</p>
        </div>
    </div>

    <!-- Total Revenue -->
    <div class="stat-card animate-scale-in stagger-item">
        <div class="stat-icon" style="background: #ECFDF5; color: #059669;">
            <i class="ph ph-currency-circle-dollar"></i>
        </div>
        <div class="stat-content">
            <p class="stat-label">Total Revenue</p>
            <p class="stat-value text-lg" style="color: #059669;">Rp <?= number_format($stats['total_revenue'] ?? 0, 0, ',', '.') ?></p>
            <p class="stat-desc">Transaksi berhasil</p>
        </div>
    </div>

    <!-- Quick Actions Placeholder -->
    <div class="stat-card animate-scale-in stagger-item">
        <div class="stat-icon" style="background: var(--color-primary-light); color: var(--color-primary);">
            <i class="ph ph-lightning"></i>
        </div>
        <div class="stat-content">
            <p class="stat-label">Quick Actions</p>
            <div class="flex gap-2 mt-3 flex-wrap">
                <a href="<?= base_url('admin/materi') ?>" class="btn btn-sm btn-primary">Kelola Kelas</a>
                <a href="<?= base_url('admin/users') ?>" class="btn btn-sm btn-ghost">Users</a>
            </div>
        </div>
    </div>
</div>

<!-- Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Recent Transactions -->
    <div class="card animate-slide-up">
        <div class="card-header">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-primary-light flex items-center justify-center">
                        <i class="ph ph-receipt text-primary text-lg"></i>
                    </div>
                    <div>
                        <h3 class="card-title">Transaksi Terbaru</h3>
                        <p class="text-xs text-slate-500">Aktivitas transaksi terbaru</p>
                    </div>
                </div>
                <a href="<?= base_url('admin/transactions') ?>" class="btn btn-sm btn-ghost">
                    Lihat Semua
                    <i class="ph ph-arrow-right"></i>
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            <?php if (!empty($recent_transactions)): ?>
                <div class="table-wrapper !border-0 !rounded-none">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Produk</th>
                                <th class="text-right">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recent_transactions as $trx): ?>
                            <tr>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="avatar avatar-sm">
                                            <?php if (!empty($trx['foto_profil'])): ?>
                                                <img src="<?= base_url($trx['foto_profil']) ?>" alt="" class="w-full h-full object-cover" />
                                            <?php else: ?>
                                                <div class="avatar-content bg-primary text-white">
                                                    <?= strtoupper(substr($trx['nama_lengkap'] ?? 'U', 0, 1)) ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div>
                                            <p class="font-medium text-slate-800"><?= esc($trx['nama_lengkap'] ?? '-') ?></p>
                                            <p class="text-xs text-slate-500"><?= date('d/m/Y H:i', strtotime($trx['tanggal_transaksi'] ?? 'now')) ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-slate-600"><?= esc($trx['nama_produk'] ?? '-') ?></td>
                                <td class="text-right">
                                    <span class="font-semibold text-success">Rp <?= number_format($trx['total_bayar'] ?? 0, 0, ',', '.') ?></span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="empty-state py-8">
                    <div class="empty-state-icon">
                        <i class="ph ph-receipt"></i>
                    </div>
                    <p class="empty-state-title">Belum ada transaksi</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Recent Students -->
    <div class="card animate-slide-up" style="animation-delay: 100ms;">
        <div class="card-header">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-info-light flex items-center justify-center">
                        <i class="ph ph-users text-info text-lg"></i>
                    </div>
                    <div>
                        <h3 class="card-title">Siswa Terbaru</h3>
                        <p class="text-xs text-slate-500">Pengguna baru terdaftar</p>
                    </div>
                </div>
                <a href="<?= base_url('admin/users') ?>" class="btn btn-sm btn-ghost">
                    Lihat Semua
                    <i class="ph ph-arrow-right"></i>
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            <?php if (!empty($recent_students)): ?>
                <div class="table-wrapper !border-0 !rounded-none">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Siswa</th>
                                <th>Email</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recent_students as $student): ?>
                            <tr>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="avatar avatar-sm">
                                            <?php if (!empty($student['foto_profil'])): ?>
                                                <img src="<?= base_url($student['foto_profil']) ?>" alt="" class="w-full h-full object-cover" />
                                            <?php else: ?>
                                                <div class="avatar-content bg-info text-white">
                                                    <?= strtoupper(substr($student['nama_lengkap'] ?? 'S', 0, 1)) ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div>
                                            <p class="font-medium text-slate-800"><?= esc($student['nama_lengkap'] ?? '-') ?></p>
                                            <p class="text-xs text-slate-500"><?= esc($student['instansi'] ?? '-') ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-slate-600"><?= esc($student['email'] ?? '-') ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="empty-state py-8">
                    <div class="empty-state-icon">
                        <i class="ph ph-users"></i>
                    </div>
                    <p class="empty-state-title">Belum ada siswa</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Quick Actions Banner -->
<div class="quick-actions-card mt-8 animate-slide-up" style="animation-delay: 200ms;">
    <div class="relative z-10">
        <h2 class="card-title">Quick Actions</h2>
        <p class="opacity-90">Ready to manage your academy today? Choose an action below.</p>
        <div class="flex flex-wrap gap-3 mt-4">
            <a href="<?= base_url('admin/materi/create') ?>" class="btn btn-lg bg-white text-primary hover:bg-slate-50">
                <i class="ph ph-plus-circle"></i>
                Tambah Kelas Baru
            </a>
            <a href="<?= base_url('admin/users') ?>" class="btn btn-lg bg-white/20 text-white border border-white/30 hover:bg-white/30">
                <i class="ph ph-users-three"></i>
                Kelola Users
            </a>
            <a href="<?= base_url('admin/sertifikat') ?>" class="btn btn-lg bg-white/20 text-white border border-white/30 hover:bg-white/30">
                <i class="ph ph-certificate"></i>
                Kelola Sertifikat
            </a>
        </div>
    </div>
</div>

<!-- Modal File Diunggah -->
<dialog id="files_modal" class="modal">
    <div class="modal-box max-w-4xl max-h-[90vh] overflow-hidden flex flex-col">
        <div class="flex items-center gap-4 mb-4">
            <div class="w-12 h-12 rounded-xl bg-warning-light flex items-center justify-center">
                <i class="ph ph-file-text text-warning text-xl"></i>
            </div>
            <div>
                <h3 class="modal-title mb-1">File Diunggah</h3>
                <p class="text-sm text-slate-500">Kelola file materi dan video</p>
            </div>
        </div>

        <!-- Filter -->
        <div class="flex gap-2 mb-4">
            <select id="file_filter_type" class="select select-sm" onchange="filterFiles()">
                <option value="">Semua Tipe</option>
                <option value="dokumen">Dokumen</option>
                <option value="video">Video</option>
            </select>
            <select id="file_filter_kelas" class="select select-sm" onchange="filterFiles()">
                <option value="">Semua Kelas</option>
            </select>
        </div>

        <!-- Files Table -->
        <div class="flex-1 overflow-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Tipe</th>
                        <th>Nama File</th>
                        <th>Kelas</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="files_table_body">
                    <tr>
                        <td colspan="4" class="text-center py-8">
                            <i class="ph ph-spinner text-2xl animate-spin text-slate-400"></i>
                            <p class="text-slate-500 mt-2">Memuat data...</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="modal-actions mt-4">
            <button type="button" class="btn btn-ghost" onclick="files_modal.close()">Tutup</button>
        </div>
    </div>
    <form method="dialog" class="modal-backdrop"></form>
</dialog>

<script>
// Global variables for files data
let allFiles = [];

function showFilesModal() {
    files_modal.showModal();
    loadFiles();
}

function loadFiles() {
    fetch('<?= base_url('admin/dashboard/uploaded-files') ?>')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                allFiles = data.data;
                populateKelasFilter();
                renderFilesTable(allFiles);
            }
        })
        .catch(error => {
            console.error('Error loading files:', error);
            document.getElementById('files_table_body').innerHTML = `
                <tr>
                    <td colspan="4" class="text-center py-8 text-error">
                        <i class="ph ph-warning text-2xl"></i>
                        <p class="mt-2">Gagal memuat data</p>
                    </td>
                </tr>
            `;
        });
}

function populateKelasFilter() {
    const kelasSet = new Set(allFiles.map(f => f.kelas).filter(k => k && k !== '-'));
    const kelasSelect = document.getElementById('file_filter_kelas');
    kelasSelect.innerHTML = '<option value="">Semua Kelas</option>';
    kelasSet.forEach(kelas => {
        kelasSelect.innerHTML += `<option value="${kelas}">${kelas}</option>`;
    });
}

function filterFiles() {
    const typeFilter = document.getElementById('file_filter_type').value;
    const kelasFilter = document.getElementById('file_filter_kelas').value;

    let filtered = allFiles;
    if (typeFilter) {
        filtered = filtered.filter(f => f.type === typeFilter);
    }
    if (kelasFilter) {
        filtered = filtered.filter(f => f.kelas === kelasFilter);
    }

    renderFilesTable(filtered);
}

function renderFilesTable(files) {
    const tbody = document.getElementById('files_table_body');

    if (files.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="4" class="text-center py-8">
                    <div class="empty-state-icon">
                        <i class="ph ph-file-x"></i>
                    </div>
                    <p class="empty-state-title">Tidak ada file</p>
                </td>
            </tr>
        `;
        return;
    }

    tbody.innerHTML = files.map(file => {
        const typeIcon = file.type === 'dokumen'
            ? '<i class="ph ph-file-pdf text-error"></i>'
            : '<i class="ph ph-video-camera text-info"></i>';
        const typeLabel = file.type === 'dokumen' ? 'Dokumen' : 'Video';
        const typeBadge = file.type === 'dokumen'
            ? 'badge-error'
            : 'badge-info';

        const editUrl = file.type === 'dokumen'
            ? '<?= base_url('admin/materi/edit-materi/') ?>' + file.id
            : '<?= base_url('admin/materi/edit-video/') ?>' + file.id;

        return `
            <tr>
                <td>
                    <span class="badge ${typeBadge} gap-1">
                        ${typeIcon}
                        ${typeLabel}
                    </span>
                </td>
                <td>
                    <div class="flex items-center gap-2">
                        <i class="ph ph-file-text text-slate-400"></i>
                        <span class="font-medium">${file.judul || '-'}</span>
                    </div>
                    <p class="text-xs text-slate-400">${file.file || '-'}</p>
                </td>
                <td>
                    <span class="badge badge-neutral">${file.kelas || '-'}</span>
                </td>
                <td>
                    <div class="flex gap-1">
                        <a href="${editUrl}" class="btn btn-sm btn-ghost text-warning hover:bg-warning-light" title="Edit">
                            <i class="ph ph-pencil"></i>
                        </a>
                        ${file.file ? `
                            <a href="<?= base_url() ?>${file.file}" target="_blank" class="btn btn-sm btn-ghost text-info hover:bg-info-light" title="Download">
                                <i class="ph ph-download"></i>
                            </a>
                        ` : ''}
                    </div>
                </td>
            </tr>
        `;
    }).join('');
}
</script>

<?= $this->endSection() ?>
