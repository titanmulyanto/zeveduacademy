<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="min-h-screen bg-base-100">
    <!-- Header -->
    <div class="bg-primary text-primary-content py-8">
        <div class="container mx-auto px-4">
            <div class="flex items-center gap-4">
                <a href="<?= base_url('student/kelas/' . $produk['id_produk']) ?>" class="btn btn-circle btn-ghost">
                    <i class="ph ph-arrow-left text-2xl"></i>
                </a>
                <div>
                    <h1 class="text-xl font-bold">📜 Sertifikat</h1>
                    <p class="opacity-80"><?= esc($produk['judul']) ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto">
            <?php if ($passed): ?>
            <div class="card bg-gradient-to-br from-primary/10 to-success/10 shadow-xl border-2 border-primary">
                <div class="card-body text-center">
                    <div class="text-6xl mb-4">🏆</div>
                    <h2 class="text-2xl font-bold mb-2">SELAMAT!</h2>
                    <p class="text-lg opacity-70 mb-6">Anda telah menyelesaikan seluruh materi dan lulus ujian sertifikasi.</p>

                    <div class="bg-base-100 rounded-xl p-6 mb-6">
                        <h3 class="font-bold text-xl mb-2"><?= esc($produk['judul']) ?></h3>
                        <p class="opacity-60">Sertifikat ini membuktikan Anda telah menyelesaikan kelas ini dengan baik.</p>
                    </div>

                    <div class="flex justify-center gap-4">
                        <button class="btn btn-primary" onclick="window.print()">
                            <i class="ph ph-printer"></i> Cetak Sertifikat
                        </button>
                    </div>

                    <!-- Certificate Preview (for printing) -->
                    <div class="hidden print:block mt-8 p-8 border-4 border-amber-400 text-center">
                        <h1 class="text-3xl font-bold mb-4">SERTIFIKAT PENYELESAIAN</h1>
                        <p class="text-lg mb-2">Diberikan kepada:</p>
                        <h2 class="text-2xl font-bold text-primary mb-4"><?= session()->get('nama_lengkap') ?></h2>
                        <p class="mb-4">Atas partisipasi dan penyelesaian program:</p>
                        <h3 class="text-xl font-bold mb-8"><?= esc($produk['judul']) ?></h3>
                        <div class="flex justify-center gap-16 mt-8">
                            <div class="text-center">
                                <div class="border-t border-black w-32 mx-auto mb-2"></div>
                                <p class="text-sm">Tanggal</p>
                                <p class="text-sm"><?= date('d F Y') ?></p>
                            </div>
                            <div class="text-center">
                                <div class="border-t border-black w-32 mx-auto mb-2"></div>
                                <p class="text-sm">Tanda Tangan</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php else: ?>
            <div class="card bg-base-200 shadow-xl">
                <div class="card-body text-center py-12">
                    <div class="text-6xl mb-4 opacity-50">🔒</div>
                    <h2 class="text-2xl font-bold mb-2">SertifikatTerkunci</h2>
                    <p class="text-lg opacity-70 mb-6">
                        Anda harus <strong>lulus ujian sertifikasi</strong> terlebih dahulu untuk dapat mengakses sertifikat.
                    </p>

                    <?php if ($ujian): ?>
                    <a href="<?= base_url('student/kelas/' . $produk['id_produk']) ?>" class="btn btn-primary">
                        <i class="ph ph-book-open"></i> Lanjut Belajar
                    </a>
                    <?php else: ?>
                    <p class="opacity-60">Ujian belum tersedia. Hubungi admin untuk informasi lebih lanjut.</p>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>