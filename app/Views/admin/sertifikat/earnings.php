<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<div class="breadcrumbs mb-4">
    <a href="<?= base_url('admin/sertifikat') ?>" class="text-primary">Kelas & Sertifikat</a>
    <span class="text-base-content/30">/</span>
    <span>Pendapatan</span>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="stat bg-base-100 shadow border border-base-200 rounded-lg">
        <div class="stat-figure text-success">
            <i class="ph ph-wallet text-4xl"></i>
        </div>
        <div class="stat-title">Total Pendapatan</div>
        <div class="stat-value text-success">Rp <?= number_format($earnings['total'], 0, ',', '.') ?></div>
        <div class="stat-desc">Dari semua transaksi</div>
    </div>
    <div class="stat bg-base-100 shadow border border-base-200 rounded-lg">
        <div class="stat-figure text-warning">
            <i class="ph ph-certificate text-4xl"></i>
        </div>
        <div class="stat-title">Pendapatan Unlock</div>
        <div class="stat-value text-warning">Rp <?= number_format($earnings['unlock'], 0, ',', '.') ?></div>
        <div class="stat-desc">Dari unlock sertifikat gratis</div>
    </div>
    <div class="stat bg-base-100 shadow border border-base-200 rounded-lg">
        <div class="stat-figure text-primary">
            <i class="ph ph-books text-4xl"></i>
        </div>
        <div class="stat-title">Pendapatan Kelas</div>
        <div class="stat-value text-primary">Rp <?= number_format($earnings['kelas'], 0, ',', '.') ?></div>
        <div class="stat-desc">Dari pembelian kelas berbayar</div>
    </div>
</div>

<div class="flex justify-between items-center mb-4">
    <h1 class="text-2xl font-bold">Riwayat Pendapatan</h1>
    <div class="flex gap-2">
        <a href="<?= base_url('admin/users/transactions') ?>" class="btn btn-outline btn-sm">
            <i class="ph ph-receipt"></i> Semua Transaksi
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
                        <th>Jenis</th>
                        <th>Kode Invoice</th>
                        <th>Jumlah</th>
                        <th>Tanggal</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($transactions)): ?>
                        <tr>
                            <td colspan="8" class="text-center py-12">
                                <i class="ph ph-receipt text-5xl text-base-content/30"></i>
                                <p class="mt-4 text-base-content/60">Belum ada transaksi.</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; foreach ($transactions as $trx): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td>
                                <div class="font-medium"><?= esc($trx['nama_lengkap']) ?></div>
                                <div class="text-xs opacity-60"><?= esc($trx['email']) ?></div>
                            </td>
                            <td>
                                <div class="font-medium"><?= esc($trx['nama_produk']) ?></div>
                                <?php if ($trx['payment_type'] === 'unlock_sertifikat'): ?>
                                    <span class="badge badge-warning badge-sm">Unlock Sertifikat</span>
                                <?php else: ?>
                                    <span class="badge badge-primary badge-sm">Pembelian Kelas</span>
                                <?php endif; ?>
                            </td>
                            <td class="font-mono text-xs"><?= esc($trx['kode_invoice']) ?></td>
                            <td class="font-semibold text-success">
                                Rp <?= number_format($trx['total_bayar'] ?? 0, 0, ',', '.') ?>
                            </td>
                            <td class="text-sm"><?= date('d/m/Y H:i', strtotime($trx['tanggal_transaksi'])) ?></td>
                            <td>
                                <div class="flex justify-center gap-1">
                                    <?php if ($trx['payment_type'] === 'unlock_sertifikat'): ?>
                                        <span class="badge badge-warning badge-sm">
                                            <i class="ph ph-lock-open"></i> Unlock
                                        </span>
                                    <?php else: ?>
                                        <span class="badge badge-success badge-sm">
                                            <i class="ph ph-check-circle"></i> Paid
                                        </span>
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