<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<!-- Breadcrumbs -->
<div class="breadcrumbs-modern mb-4">
    <div class="breadcrumbs-modern-item">
        <a href="<?= base_url('admin/users') ?>">
            <i class="ph ph-users"></i>
            <span>Manajemen User</span>
        </a>
    </div>
    <span class="breadcrumbs-modern-separator">
        <i class="ph ph-caret-right"></i>
    </span>
    <div class="breadcrumbs-modern-item">
        <span class="breadcrumbs-modern-current">Detail User</span>
    </div>
</div>

<!-- Page Header -->
<div class="page-header">
    <div>
        <h1 class="page-title">Detail User</h1>
        <p class="page-subtitle">Informasi lengkap pengguna</p>
    </div>
    <div class="page-actions">
        <a href="<?= base_url('admin/users/edit/' . ($user['id_user'] ?? '')) ?>" class="btn btn-primary">
            <i class="ph ph-pencil"></i>
            Edit User
        </a>
        <a href="<?= base_url('admin/users') ?>" class="btn btn-ghost">
            <i class="ph ph-arrow-left"></i>
            Kembali
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Profile Card -->
    <div class="card animate-slide-up">
        <div class="card-body items-center text-center">
            <div class="avatar avatar-lg">
                <div class="avatar-content text-2xl">
                    <?= strtoupper(substr($user['nama_lengkap'] ?? 'U', 0, 1)) ?>
                </div>
            </div>

            <h2 class="card-title mt-4 text-xl"><?= esc($user['nama_lengkap'] ?? '-') ?></h2>

            <?php
            $role = $user['role'] ?? 'student';
            $badgeClass = match($role) {
                'super_admin' => 'badge-error',
                'admin' => 'badge-warning',
                default => 'badge-info'
            };
            $roleLabel = match($role) {
                'super_admin' => 'Super Admin',
                'admin' => 'Admin',
                default => 'Student'
            };
            ?>
            <span class="badge <?= $badgeClass ?> text-sm px-4 py-2"><?= $roleLabel ?></span>

            <div class="mt-4 space-y-2 text-sm text-slate-600">
                <p class="flex items-center justify-center gap-2">
                    <i class="ph ph-envelope text-slate-400"></i>
                    <?= esc($user['email'] ?? '-') ?>
                </p>
                <?php if (!empty($user['no_whatsapp'])): ?>
                <p class="flex items-center justify-center gap-2">
                    <i class="ph ph-whatsapp-logo text-slate-400"></i>
                    <?= esc($user['no_whatsapp']) ?>
                </p>
                <?php endif; ?>
                <?php if (!empty($user['instansi'])): ?>
                <p class="flex items-center justify-center gap-2">
                    <i class="ph ph-buildings text-slate-400"></i>
                    <?= esc($user['instansi']) ?>
                </p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Info Card -->
    <div class="card lg:col-span-2 animate-slide-up" style="animation-delay: 100ms;">
        <div class="card-header">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-primary-light flex items-center justify-center">
                    <i class="ph ph-user text-primary text-lg"></i>
                </div>
                <div>
                    <h3 class="card-title">Informasi Lengkap</h3>
                    <p class="text-xs text-slate-500">Data profil pengguna</p>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="text-xs font-medium text-slate-500 uppercase tracking-wider">Instansi</label>
                    <p class="text-slate-800 font-medium mt-1"><?= esc($user['instansi'] ?? '-') ?></p>
                </div>

                <div>
                    <label class="text-xs font-medium text-slate-500 uppercase tracking-wider">Alamat</label>
                    <p class="text-slate-800 font-medium mt-1"><?= esc($user['alamat'] ?? '-') ?></p>
                </div>

                <div>
                    <label class="text-xs font-medium text-slate-500 uppercase tracking-wider">Tanggal Lahir</label>
                    <p class="text-slate-800 font-medium mt-1">
                        <?= !empty($user['tanggal_lahir']) ? date('d F Y', strtotime($user['tanggal_lahir'])) : '-' ?>
                    </p>
                </div>

                <div>
                    <label class="text-xs font-medium text-slate-500 uppercase tracking-wider">Jenis Kelamin</label>
                    <p class="text-slate-800 font-medium mt-1">
                        <?php
                        $jk = $user['jenis_kelamin'] ?? '';
                        echo match($jk) {
                            'L' => 'Laki-laki',
                            'P' => 'Perempuan',
                            default => '-'
                        };
                        ?>
                    </p>
                </div>

                <div>
                    <label class="text-xs font-medium text-slate-500 uppercase tracking-wider">Pendidikan Terakhir</label>
                    <p class="text-slate-800 font-medium mt-1"><?= esc($user['pendidikan_terakhir'] ?? '-') ?></p>
                </div>

                <div>
                    <label class="text-xs font-medium text-slate-500 uppercase tracking-wider">Login Google</label>
                    <p class="text-slate-800 font-medium mt-1">
                        <?php if (($user['login_google'] ?? 'tidak') == 'ya'): ?>
                            <span class="badge badge-success">
                                <i class="ph ph-check mr-1"></i>
                                Ya
                            </span>
                        <?php else: ?>
                            <span class="badge badge-neutral">
                                <i class="ph ph-x mr-1"></i>
                                Tidak
                            </span>
                        <?php endif; ?>
                    </p>
                </div>

                <?php if (!empty($user['google_id'])): ?>
                <div>
                    <label class="text-xs font-medium text-slate-500 uppercase tracking-wider">Google ID</label>
                    <p class="text-slate-800 font-medium mt-1 text-xs break-all"><?= esc($user['google_id']) ?></p>
                </div>
                <?php endif; ?>

                <div>
                    <label class="text-xs font-medium text-slate-500 uppercase tracking-wider">Email</label>
                    <p class="text-slate-800 font-medium mt-1"><?= esc($user['email'] ?? '-') ?></p>
                </div>
            </div>

            <!-- Stats Preview -->
            <?php if (isset($userStats) && !empty($userStats)): ?>
            <div class="mt-6 pt-6 border-t border-slate-100">
                <h4 class="text-sm font-semibold text-slate-700 mb-4">Statistik</h4>
                <div class="grid grid-cols-3 gap-4">
                    <div class="text-center p-4 bg-slate-50 rounded-xl">
                        <p class="text-2xl font-bold text-primary"><?= $userStats['total_kelas'] ?? 0 ?></p>
                        <p class="text-xs text-slate-500">Kelas Diikuti</p>
                    </div>
                    <div class="text-center p-4 bg-slate-50 rounded-xl">
                        <p class="text-2xl font-bold text-success"><?= $userStats['total_sertifikat'] ?? 0 ?></p>
                        <p class="text-xs text-slate-500">Sertifikat</p>
                    </div>
                    <div class="text-center p-4 bg-slate-50 rounded-xl">
                        <p class="text-2xl font-bold text-info"><?= $userStats['total_transaksi'] ?? 0 ?></p>
                        <p class="text-xs text-slate-500">Transaksi</p>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>