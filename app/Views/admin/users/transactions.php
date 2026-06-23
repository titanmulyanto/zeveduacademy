<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="page-header">
    <div>
        <h1 class="page-title">Daftar Transaksi</h1>
        <p class="page-subtitle">Semua aktivitas pembayaran di platform</p>
    </div>
    <div class="page-actions">
        <div class="join">
            <input class="input join-item" placeholder="Cari Invoice..." style="min-width: 200px;"/>
            <button class="btn btn-primary join-item">
                <i class="ph ph-magnifying-glass"></i>
            </button>
        </div>
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

<!-- Transactions Table Card -->
<div class="card animate-slide-up">
    <div class="card-body p-0">
        <?php if (empty($transactions)): ?>
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="ph ph-receipt"></i>
                </div>
                <h3 class="empty-state-title">Belum Ada Transaksi</h3>
                <p class="empty-state-description">Belum ada transaksi yang terekam dalam sistem.</p>
            </div>
        <?php else: ?>
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Invoice</th>
                            <th>User</th>
                            <th>Produk / Kelas</th>
                            <th class="text-right">Total</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($transactions as $trx): ?>
                        <tr>
                            <td>
                                <code class="font-mono text-xs bg-slate-100 px-2 py-1 rounded"><?= esc($trx['kode_invoice'] ?? '-') ?></code>
                            </td>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="avatar avatar-sm">
                                        <div class="avatar-content">
                                            <?= strtoupper(substr($trx['nama_lengkap'] ?? 'U', 0, 1)) ?>
                                        </div>
                                    </div>
                                    <span class="font-medium text-slate-800"><?= esc($trx['nama_lengkap'] ?? '-') ?></span>
                                </div>
                            </td>
                            <td class="text-slate-600"><?= esc($trx['nama_produk'] ?? '-') ?></td>
                            <td class="text-right">
                                <span class="font-bold text-slate-900">Rp <?= number_format($trx['total_bayar'] ?? 0, 0, ',', '.') ?></span>
                            </td>
                            <td>
                                <?php
                                $status = $trx['status_pembayaran'] ?? 'pending';
                                $statusConfig = match($status) {
                                    'paid' => ['class' => 'badge-success', 'icon' => 'check-circle', 'label' => 'Berhasil'],
                                    'pending' => ['class' => 'badge-warning', 'icon' => 'clock', 'label' => 'Pending'],
                                    'failed' => ['class' => 'badge-error', 'icon' => 'x-circle', 'label' => 'Gagal'],
                                    'expired' => ['class' => 'badge-neutral', 'icon' => 'hourglass-medium', 'label' => 'Kedaluwarsa'],
                                    default => ['class' => 'badge-neutral', 'icon' => 'question', 'label' => 'Unknown']
                                };
                                ?>
                                <span class="badge <?= $statusConfig['class'] ?>">
                                    <i class="ph ph-<?= $statusConfig['icon'] ?> mr-1"></i>
                                    <?= $statusConfig['label'] ?>
                                </span>
                            </td>
                            <td class="text-slate-500 text-sm">
                                <?= date('d M Y, H:i', strtotime($trx['tanggal_transaksi'] ?? 'now')) ?>
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