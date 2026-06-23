<?= $this->extend('student/layouts/main') ?>

<?= $this->section('content') ?>

<!-- Exam Header with Timer -->
<div class="sticky top-0 z-30 bg-warning text-warning-content shadow-lg">
    <div class="flex items-center justify-between px-4 py-3">
        <div class="flex items-center gap-3">
            <i class="ph ph-exam text-2xl"></i>
            <div>
                <h1 class="font-bold"><?= esc($ujian['judul_ujian'] ?? 'Ujian Sertifikasi') ?></h1>
                <p class="text-sm opacity-80"><?= count($questions) ?> Soal • <?= $ujian['durasi_menit'] ?? 60 ?> Menit</p>
            </div>
        </div>
        <div class="flex items-center gap-4">
            <!-- Timer Display -->
            <div class="flex items-center gap-2 bg-warning-content/20 px-4 py-2 rounded-lg">
                <i class="ph ph-clock text-xl"></i>
                <span class="text-2xl font-bold font-mono" id="examTimer">--:--</span>
            </div>
            <!-- Submit Button -->
            <button onclick="confirmSubmit()" class="btn btn-warning-content btn-sm">
                <i class="ph ph-paper-plane-tilt"></i> Submit
            </button>
        </div>
    </div>
    <!-- Progress Bar -->
    <div class="h-1 bg-warning-content/20">
        <div class="h-1 bg-warning-content/50 transition-all duration-1000" id="progressBar" style="width: 0%"></div>
    </div>
</div>

<!-- Exam Content -->
<form action="<?= base_url('student/submit-exam') ?>" method="POST" id="examForm">
    <?= csrf_field() ?>
    <input type="hidden" name="id_ujian" value="<?= $ujian['id_ujian'] ?>">

    <div class="max-w-3xl mx-auto py-6 space-y-6 px-4">
        <?php foreach($questions as $index => $soal): ?>
        <div class="card bg-base-100 shadow border border-base-200" id="soal-<?= $soal['id_soal'] ?>">
            <div class="card-body">
                <!-- Question Header -->
                <div class="flex items-center gap-3 mb-4">
                    <div class="badge badge-primary badge-lg"><?= $index + 1 ?></div>
                    <span class="text-sm text-base-content/60">
                        Soal <?= $index + 1 ?> dari <?= count($questions) ?>
                    </span>
                    <?php if (isset($isRetry) && $isRetry): ?>
                    <span class="badge badge-warning badge-sm">
                        <i class="ph ph-arrow-clockwise"></i> Remedial
                    </span>
                    <?php endif; ?>
                </div>

                <!-- Question Text -->
                <p class="text-lg font-medium mb-6"><?= nl2br(esc($soal['pertanyaan'])) ?></p>

                <!-- Answer Options -->
                <div class="space-y-3" id="options-<?= $soal['id_soal'] ?>">
                    <label class="flex items-center gap-4 p-4 bg-base-200 rounded-lg cursor-pointer hover:bg-base-300 transition-colors border-2 border-transparent" data-option="A">
                        <input type="radio" name="jawaban_<?= $soal['id_soal'] ?>" value="A" class="radio radio-primary">
                        <span class="font-bold text-primary w-6">A.</span>
                        <span class="flex-1"><?= nl2br(esc($soal['opsi_a'] ?? '-')) ?></span>
                    </label>

                    <label class="flex items-center gap-4 p-4 bg-base-200 rounded-lg cursor-pointer hover:bg-base-300 transition-colors border-2 border-transparent" data-option="B">
                        <input type="radio" name="jawaban_<?= $soal['id_soal'] ?>" value="B" class="radio radio-secondary">
                        <span class="font-bold text-secondary w-6">B.</span>
                        <span class="flex-1"><?= nl2br(esc($soal['opsi_b'] ?? '-')) ?></span>
                    </label>

                    <label class="flex items-center gap-4 p-4 bg-base-200 rounded-lg cursor-pointer hover:bg-base-300 transition-colors border-2 border-transparent" data-option="C">
                        <input type="radio" name="jawaban_<?= $soal['id_soal'] ?>" value="C" class="radio radio-accent">
                        <span class="font-bold text-accent w-6">C.</span>
                        <span class="flex-1"><?= nl2br(esc($soal['opsi_c'] ?? '-')) ?></span>
                    </label>
                </div>
            </div>
        </div>
        <?php endforeach; ?>

        <!-- Submit Section -->
        <div class="card bg-base-100 shadow border border-base-200">
            <div class="card-body text-center">
                <p class="text-base-content/60 mb-4">Pastikan semua jawaban telah dipilih sebelum submit.</p>
                <button type="submit" class="btn btn-warning btn-lg">
                    <i class="ph ph-paper-plane-tilt"></i> Submit Jawaban
                </button>
            </div>
        </div>
    </div>
</form>

<!-- Confirmation Modal -->
<dialog id="submitModal" class="modal">
    <div class="modal-box">
        <div class="flex items-center gap-3 mb-4">
            <div class="bg-warning/20 p-3 rounded-full">
                <i class="ph ph-warning text-2xl text-warning"></i>
            </div>
            <div>
                <h3 class="font-bold text-lg">Konfirmasi Submit</h3>
            </div>
        </div>
        <p class="mb-4">Apakah Anda yakin ingin submit jawaban? Jawaban tidak bisa diubah setelah submit.</p>
        <div class="modal-action">
            <form method="dialog">
                <button class="btn">Batal</button>
            </form>
            <button onclick="doSubmit()" class="btn btn-warning">
                <i class="ph ph-paper-plane-tilt"></i> Ya, Submit
            </button>
        </div>
    </div>
    <form method="dialog" class="modal-backdrop"><button>close</button></form>
</dialog>

<script>
    // ===== TIMER =====
    let totalSeconds = <?= $durasi_detik ?>;
    let remainingSeconds = totalSeconds;
    const timerDisplay = document.getElementById('examTimer');
    const progressBar = document.getElementById('progressBar');

    function updateTimerDisplay() {
        const minutes = Math.floor(remainingSeconds / 60);
        const seconds = remainingSeconds % 60;
        timerDisplay.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;

        // Update progress bar
        const progress = ((totalSeconds - remainingSeconds) / totalSeconds) * 100;
        progressBar.style.width = `${progress}%`;

        if (remainingSeconds > 0) {
            remainingSeconds--;
            setTimeout(updateTimerDisplay, 1000);
        } else {
            // Auto submit when time expires
            timerDisplay.textContent = '00:00';
            timerDisplay.classList.add('text-error');
            document.getElementById('examForm').submit();
        }
    }

    updateTimerDisplay();

    // ===== ANSWER SELECTION =====
    document.querySelectorAll('[data-option]').forEach(label => {
        label.addEventListener('click', function() {
            const option = this.dataset.option;
            const containerId = this.closest('.space-y-3').id;
            const container = document.getElementById(containerId);

            // Remove selection from siblings
            container.querySelectorAll('[data-option]').forEach(l => {
                l.classList.remove('border-primary', 'ring-2', 'ring-primary');
            });

            // Add selection to this
            this.classList.add('border-primary', 'ring-2', 'ring-primary');
        });
    });

    // ===== CONFIRM SUBMIT =====
    function confirmSubmit() {
        submitModal.showModal();
    }

    function doSubmit() {
        document.getElementById('examForm').submit();
    }
</script>

<?= $this->endSection() ?>