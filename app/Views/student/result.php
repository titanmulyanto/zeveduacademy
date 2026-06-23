<?= $this->extend('student/layouts/main') ?>

<?= $this->section('content') ?>

<!-- Result Header -->
<div class="bg-base-200 py-8 mb-6">
    <div class="text-center">
        <?php if ($result['status_lulus'] === 'lulus'): ?>
        <div class="bg-success/10 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="ph ph-trophy text-5xl text-success"></i>
        </div>
        <h1 class="text-3xl font-bold text-success mb-2">SELAMAT!</h1>
        <p class="text-lg text-base-content/70">Anda LULUS ujian sertifikasi</p>
        <?php else: ?>
        <div class="bg-error/10 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="ph ph-exam text-5xl text-error"></i>
        </div>
        <h1 class="text-3xl font-bold text-error mb-2">BELUM LULUS</h1>
        <p class="text-lg text-base-content/70">Anda perlu remedial ujian</p>
        <?php endif; ?>
    </div>
</div>

<div class="max-w-3xl mx-auto px-4">
    <!-- Score Card -->
    <div class="card bg-base-100 shadow-xl border border-base-200 mb-6">
        <div class="card-body">
            <div class="grid grid-cols-3 gap-4 text-center">
                <!-- Score -->
                <div class="p-4">
                    <div class="text-4xl font-bold <?= $result['status_lulus'] === 'lulus' ? 'text-success' : 'text-error' ?>">
                        <?= $result['nilai'] ?>%
                    </div>
                    <p class="text-sm text-base-content/60 mt-1">Nilai Akhir</p>
                </div>
                <!-- Correct -->
                <div class="p-4 border-l border-base-200">
                    <div class="text-4xl font-bold text-success">
                        <?php
                        $correct = 0;
                        $total = count($questions);
                        foreach ($questions as $q) {
                            if ($q['jawaban_benar']) $correct++;
                        }
                        echo $correct;
                        ?>
                    </div>
                    <p class="text-sm text-base-content/60 mt-1">Jawaban Benar</p>
                </div>
                <!-- Wrong -->
                <div class="p-4 border-l border-base-200">
                    <div class="text-4xl font-bold text-error"><?= $total - $correct ?></div>
                    <p class="text-sm text-base-content/60 mt-1">Jawaban Salah</p>
                </div>
            </div>

            <!-- Pass/Fail Status -->
            <div class="mt-4 pt-4 border-t border-base-200 text-center">
                <?php if ($result['status_lulus'] === 'lulus'): ?>
                <span class="badge badge-success badge-lg">
                    <i class="ph ph-check-circle"></i> LULUS
                </span>
                <span class="ml-2 text-base-content/60">Nilai Minimal: <?= $ujian['nilai_minimal_lulus'] ?? 70 ?>%</span>
                <?php else: ?>
                <span class="badge badge-error badge-lg">
                    <i class="ph ph-x-circle"></i> TIDAK LULUS
                </span>
                <span class="ml-2 text-base-content/60">Nilai Minimal: <?= $ujian['nilai_minimal_lulus'] ?? 70 ?>%</span>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Rating (only for passed exam) -->
    <?php if ($result['status_lulus'] === 'lulus'): ?>
    <?php if (!isset($result['rating'])): ?>
    <div class="card bg-base-100 shadow border border-base-200 mb-6">
        <div class="card-body">
            <h3 class="font-bold text-lg mb-4 flex items-center gap-2">
                <i class="ph ph-star text-warning"></i> Berikan Rating
            </h3>
            <p class="text-sm text-base-content/60 mb-4">Bagaimana pengalaman Anda mengikuti ujian ini?</p>

            <form action="<?= base_url('student/rate-exam') ?>" method="POST">
                <?= csrf_field() ?>
                <input type="hidden" name="id_hasil" value="<?= $result['id_hasil'] ?>">
                <div class="rating rating-lg justify-center mb-4">
                    <?php for ($i = 1; $i <= 10; $i++): ?>
                    <input type="radio" name="rating" value="<?= $i ?>" class="mask mask-star-2 bg-warning" />
                    <?php endfor; ?>
                </div>
                <div class="text-center mb-4">
                    <span id="ratingValue" class="text-2xl font-bold text-warning">-</span>
                    <span class="text-base-content/60">/ 10</span>
                </div>
                <button type="submit" class="btn btn-primary w-full">
                    <i class="ph ph-check"></i> Kirim Rating
                </button>
            </form>
        </div>
    </div>
    <?php else: ?>
    <div class="alert alert-success mb-6">
        <i class="ph ph-star text-warning"></i>
        <span>Terima kasih! Anda sudah memberikan rating: <?= $result['rating'] ?>/10</span>
    </div>
    <?php endif; ?>
    <?php endif; ?>

    <!-- Actions -->
    <div class="flex flex-wrap justify-center gap-4 mb-6">
        <?php if ($result['status_lulus'] !== 'lulus'): ?>
        <a href="<?= base_url('student/retry-exam/' . $ujian['id_ujian']) ?>" class="btn btn-error btn-lg">
            <i class="ph ph-arrow-clockwise"></i> Remedial Ujian
        </a>
        <?php endif; ?>
        <a href="<?= base_url('student/kelas/' . $produk['id_produk']) ?>" class="btn btn-ghost">
            <i class="ph ph-arrow-left"></i> Kembali ke Kelas
        </a>
        <?php if ($result['status_lulus'] === 'lulus'): ?>
        <a href="<?= base_url('student/sertifikat/' . $produk['id_produk']) ?>" class="btn btn-success btn-lg">
            <i class="ph ph-certificate"></i> Lihat Sertifikat
        </a>
        <?php endif; ?>
    </div>

    <!-- Answer Review -->
    <h3 class="text-lg font-bold mb-4 flex items-center gap-2">
        <i class="ph ph-exam text-primary"></i> Review Jawaban
    </h3>

    <div class="space-y-4 mb-8">
        <?php foreach($questions as $index => $soal): ?>
        <div class="card bg-base-100 shadow border border-base-200">
            <div class="card-body">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2">
                        <span class="badge badge-lg <?= $soal['jawaban_benar'] ? 'badge-success' : 'badge-ghost' ?>"><?= $index + 1 ?></span>
                        <span class="text-sm font-medium">
                            <?= $soal['jawaban_benar'] ? 'Benar' : 'Salah' ?>
                        </span>
                    </div>
                    <span class="badge badge-success">
                        <i class="ph ph-check"></i> <?= $soal['jawaban_benar'] ?>
                    </span>
                </div>

                <p class="font-medium mb-4"><?= nl2br(esc($soal['pertanyaan'])) ?></p>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-2 text-sm">
                    <div class="p-3 rounded-lg <?= $soal['jawaban_benar'] === 'A' ? 'bg-success/20 border-2 border-success' : 'bg-base-200' ?>">
                        <span class="font-bold">A.</span> <?= esc($soal['opsi_a'] ?? '-') ?>
                        <?php if ($soal['jawaban_benar'] === 'A'): ?>
                        <i class="ph ph-check-circle text-success ml-2"></i>
                        <?php endif; ?>
                    </div>
                    <div class="p-3 rounded-lg <?= $soal['jawaban_benar'] === 'B' ? 'bg-success/20 border-2 border-success' : 'bg-base-200' ?>">
                        <span class="font-bold">B.</span> <?= esc($soal['opsi_b'] ?? '-') ?>
                        <?php if ($soal['jawaban_benar'] === 'B'): ?>
                        <i class="ph ph-check-circle text-success ml-2"></i>
                        <?php endif; ?>
                    </div>
                    <div class="p-3 rounded-lg <?= $soal['jawaban_benar'] === 'C' ? 'bg-success/20 border-2 border-success' : 'bg-base-200' ?>">
                        <span class="font-bold">C.</span> <?= esc($soal['opsi_c'] ?? '-') ?>
                        <?php if ($soal['jawaban_benar'] === 'C'): ?>
                        <i class="ph ph-check-circle text-success ml-2"></i>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
    // Rating display
    const ratingInputs = document.querySelectorAll('input[name="rating"]');
    const ratingValue = document.getElementById('ratingValue');

    ratingInputs.forEach(input => {
        input.addEventListener('change', function() {
            ratingValue.textContent = this.value;
        });
    });
</script>

<?= $this->endSection() ?>