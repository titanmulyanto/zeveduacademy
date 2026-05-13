<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6">
    <h1 class="text-2xl font-bold">Daftar Transaksi</h1>
    
    <div class="flex gap-2">
        <div class="join">
            <input class="input input-bordered join-item" placeholder="Cari Kode Invoice..."/>
            <button class="btn join-item"><i class="ph ph-magnifying-glass"></i></button>
        </div>
    </div>
</div>

<div class="card bg-base-100 shadow border border-base-200">
    <div class="card-body p-0">
        <div class="overflow-x-auto">
            <table class="table table-zebra w-full">
                <thead>
                    <tr>
                        <th>Invoice</th>
                        <th>User</th>
                        <th>Produk / Kelas</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($transactions)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-8 opacity-50">Belum ada transaksi terekam.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($transactions as $trx): ?>
                        <tr>
                            <td class="font-mono text-xs font-bold"><?= $trx['kode_invoice'] ?></td>
                            <td>
                                <div class="font-bold text-sm"><?= $trx['nama_lengkap'] ?></div>
                            </td>
                            <td><?= $trx['nama_produk'] ?></td>
                            <td class="font-bold">Rp <?= number_format($trx['total_bayar'], 0, ',', '.') ?></td>
                            <td>
                                <?php if($trx['status_pembayaran'] == 'paid'): ?>
                                    <div class="badge badge-success gap-2">
                                        <i class="ph ph-check-circle"></i> Paid
                                    </div>
                                <?php elseif($trx['status_pembayaran'] == 'pending'): ?>
                                    <div class="badge badge-warning gap-2">
                                        <i class="ph ph-clock"></i> Pending
                                    </div>
                                <?php else: ?>
                                    <div class="badge badge-error gap-2">
                                        <i class="ph ph-x-circle"></i> Failed
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td class="text-xs"><?= date('d M Y, H:i', strtotime($trx['tanggal_transaksi'])) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
