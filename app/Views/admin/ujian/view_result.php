<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<div class="breadcrumbs mb-6">
    <a href="<?= base_url('admin/ujian/results') ?>" class="text-primary">Hasil Ujian</a>
    <span class="text-base-content/30">/</span>
    <span>Detail Hasil</span>
</div>

<h1 class="text-2xl font-bold mb-6">Detail Hasil Ujian</h1>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <!-- Student Info -->
    <div class="card bg-base-100 shadow border border-base-200">
        <div class="card-body">
            <h3 class="font-bold">Informasi Student</h3>
            <div class="avatar mt-2">
                <div class="w-16 rounded-full">
                    <img src="https://ui-avatars.com/api/?name=<?= urlencode($result['nama_lengkap'] ?? 'S') ?>" />
                </div>
            </div>
            <p class="font-bold mt-2"><?= esc($result['nama_lengkap'] ?? '-') ?></p>
            <p class="text-sm text-base-content/60"><?= esc($result['email'] ?? '-') ?></p>
        </div>
    </div>

    <!-- Exam Result -->
    <div class="card bg-base-100 shadow border border-base-200">
        <div class="card-body">
            <h3 class="font-bold">Hasil Ujian</h3>
            <div class="mt-2">
                <div class="text-4xl font-bold <?= ($result['status_lulus'] ?? '') == 'lulus' ? 'text-success' : 'text-error' ?>">
                    <?= $result['nilai'] ?? 0 ?>
                </div>
                <div class="badge badge-<?= ($result['status_lulus'] ?? '') == 'lulus' ? 'success' : 'error' ?> mt-2">
                    <?= ($result['status_lulus'] ?? '') == 'lulus' ? 'LULUS' : 'TIDAK LULUS' ?>
                </div>
            </div>
            <p class="text-sm mt-2">Nilai Minimal: <?= $result['nilai_minimal_lulus'] ?? 70 ?></p>
            <p class="text-sm">Tanggal Ujian: <?= date('d F Y H:i', strtotime($result['tanggal_ujian'] ?? 'now')) ?></p>
        </div>
    </div>

    <!-- Class Info -->
    <div class="card bg-base-100 shadow border border-base-200">
        <div class="card-body">
            <h3 class="font-bold">Informasi Kelas</h3>
            <p class="font-semibold mt-2"><?= esc($result['nama_produk'] ?? '-') ?></p>
            <p class="text-sm text-base-content/60">Durasi: <?= $result['durasi_menit'] ?? 60 ?> menit</p>
        </div>
    </div>
</div>

<div class="card bg-base-100 shadow border border-base-200">
    <div class="card-body">
        <h3 class="font-bold mb-4">Daftar Soal</h3>
        <div class="overflow-x-auto">
            <table class="table table-zebra">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Pertanyaan</th>
                        <th>Opsi A</th>
                        <th>Opsi B</th>
                        <th>Opsi C</th>
                        <th>Jawaban Benar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($questions as $q): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= esc($q['pertanyaan']) ?></td>
                            <td><?= esc($q['opsi_a']) ?></td>
                            <td><?= esc($q['opsi_b']) ?></td>
                            <td><?= esc($q['opsi_c']) ?></td>
                            <td>
                                <span class="badge badge-success"><?= $q['jawaban_benar'] ?></span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>