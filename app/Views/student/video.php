<?= $this->extend('student/layouts/main') ?>

<?= $this->section('content') ?>

<!-- Flash Messages -->
<?php if (session()->getFlashdata('success')): ?>
<div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-2xl flex items-center gap-3 animate-fade-in">
    <div class="w-10 h-10 rounded-xl bg-emerald-500 flex items-center justify-center flex-shrink-0">
        <i class="ph-fill ph-check-circle text-white text-lg"></i>
    </div>
    <p class="text-emerald-700 font-medium"><?= session()->getFlashdata('success') ?></p>
</div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
<div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-2xl flex items-center gap-3 animate-fade-in">
    <div class="w-10 h-10 rounded-xl bg-red-500 flex items-center justify-center flex-shrink-0">
        <i class="ph-fill ph-warning-circle text-white text-lg"></i>
    </div>
    <p class="text-red-700 font-medium"><?= session()->getFlashdata('error') ?></p>
</div>
<?php endif; ?>

<!-- ==================== VIDEO HEADER ==================== -->
<div class="mb-6">
    <div class="flex items-center gap-4">
        <a href="<?= base_url('student/kelas/' . ($idProduk ?? '')) ?>"
           class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-600 hover:bg-slate-200 hover:text-slate-800 transition-colors">
            <i class="ph-fill ph-arrow-left text-xl"></i>
        </a>
        <div class="flex-1">
            <h1 class="text-lg lg:text-xl font-bold text-slate-800 line-clamp-1"><?= esc($video['judul_video']) ?></h1>
            <p class="text-sm text-slate-500 flex items-center gap-2 mt-1">
                <i class="ph ph-clock"></i>
                <span><?= $video['durasi_menit'] ?? 0 ?> menit</span>
                <?php if (!empty($video['youtube_url'])): ?>
                <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                <span class="text-red-500 flex items-center gap-1">
                    <i class="ph-fill ph-youtube-logo"></i>
                    YouTube
                </span>
                <?php endif; ?>
            </p>
        </div>
    </div>
</div>

<!-- ==================== VIDEO PLAYER ==================== -->
<div class="max-w-5xl mx-auto">
    <div class="bg-slate-900 rounded-2xl overflow-hidden shadow-2xl">
        <?php if (!empty($video['youtube_url'])): ?>
        <!-- YouTube Embed -->
        <?php
        $youtubeId = '';
        $url = trim($video['youtube_url']);

        // Pattern 1: youtube.com/watch?v=ID
        if (preg_match('/youtube\.com\/watch\?.*v=([a-zA-Z0-9_-]{11})/', $url, $match)) {
            $youtubeId = $match[1];
        }
        // Pattern 2: youtu.be/ID
        elseif (preg_match('/youtu\.be\/([a-zA-Z0-9_-]{11})/', $url, $match)) {
            $youtubeId = $match[1];
        }
        // Pattern 3: youtube.com/embed/ID
        elseif (preg_match('/youtube\.com\/embed\/([a-zA-Z0-9_-]{11})/', $url, $match)) {
            $youtubeId = $match[1];
        }
        // Pattern 4: youtube.com/v/ID
        elseif (preg_match('/youtube\.com\/v\/([a-zA-Z0-9_-]{11})/', $url, $match)) {
            $youtubeId = $match[1];
        }
        // Pattern 5: Short format youtube.com/?v=ID
        elseif (preg_match('/youtube\.com\/\?v=([a-zA-Z0-9_-]{11})/', $url, $match)) {
            $youtubeId = $match[1];
        }
        ?>
        <?php if ($youtubeId): ?>
        <div class="aspect-video">
            <iframe class="w-full h-full"
                    src="https://www.youtube.com/embed/<?= $youtubeId ?>?rel=0&modestbranding=1&enablejsapi=1"
                    frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen>
            </iframe>
        </div>
        <?php else: ?>
        <div class="aspect-video flex items-center justify-center bg-slate-800">
            <div class="text-center">
                <div class="w-16 h-16 rounded-full bg-slate-700 flex items-center justify-center mx-auto mb-4">
                    <i class="ph-fill ph-video-camera-slash text-3xl text-slate-500"></i>
                </div>
                <p class="text-slate-400">Link YouTube tidak valid</p>
                <p class="text-slate-500 text-sm mt-2">URL: <?= esc($url) ?></p>
            </div>
        </div>
        <?php endif; ?>

        <?php elseif (!empty($video['file_materi'])): ?>
        <!-- Video File -->
        <video class="w-full" controls playsinline>
            <source src="<?= base_url($video['file_materi']) ?>" type="video/mp4">
            Browser tidak mendukung video.
        </video>

        <?php else: ?>
        <!-- No Video -->
        <div class="aspect-video flex items-center justify-center bg-slate-800">
            <div class="text-center">
                <div class="w-16 h-16 rounded-full bg-slate-700 flex items-center justify-center mx-auto mb-4">
                    <i class="ph-fill ph-video-camera-slash text-3xl text-slate-500"></i>
                </div>
                <p class="text-slate-400">Tidak ada video yang tersedia</p>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- ==================== VIDEO INFO & ACTIONS ==================== -->
    <div class="mt-6 bg-white rounded-2xl border border-slate-200 p-5 lg:p-6">
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center flex-shrink-0">
                <i class="ph-fill ph-video-camera text-xl text-blue-600"></i>
            </div>
            <div class="flex-1">
                <h2 class="font-bold text-slate-800 text-lg"><?= esc($video['judul_video']) ?></h2>
                <p class="text-sm text-slate-500 mt-1">
                    Durasi: <?= $video['durasi_menit'] ?? 0 ?> menit
                </p>
            </div>
        </div>

        <!-- Mark as Complete Button -->
        <div class="mt-6 pt-6 border-t border-slate-100">
            <?php
            // Check completion status from videoProgress passed by controller
            $isCompleted = isset($videoProgress) && $videoProgress['status_selesai'] === 'selesai';
            ?>
            <?php if ($isCompleted): ?>
            <div class="flex items-center justify-center gap-3 px-5 py-4 bg-emerald-50 border border-emerald-200 rounded-xl">
                <div class="w-10 h-10 rounded-full bg-emerald-500 flex items-center justify-center">
                    <i class="ph-fill ph-check text-white text-lg"></i>
                </div>
                <div>
                    <p class="font-semibold text-emerald-700">✓ Video Selesai Ditonton</p>
                    <p class="text-sm text-emerald-600">Great job! Lanjutkan ke video berikutnya.</p>
                </div>
            </div>
            <?php else: ?>
            <form action="<?= base_url('student/mark-complete') ?>" method="POST">
                <?= csrf_field() ?>
                <input type="hidden" name="id_video" value="<?= $video['id_video'] ?>">
                <input type="hidden" name="id_produk" value="<?= $idProduk ?? '' ?>">
                <button type="submit"
                        class="w-full flex items-center justify-center gap-3 px-5 py-4 bg-blue-600 hover:bg-blue-700 border border-blue-700 rounded-xl text-white transition-colors shadow-lg shadow-blue-600/25">
                    <i class="ph-fill ph-check-circle text-xl"></i>
                    <span class="font-semibold">Tandai Selesai</span>
                </button>
                <p class="text-xs text-slate-500 text-center mt-2">Klik tombol ini setelah menyelesaikan video</p>
            </form>
            <?php endif; ?>
        </div>
    </div>

    <!-- ==================== NAVIGATION ==================== -->
    <div class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-4">
        <a href="<?= base_url('student/kelas/' . ($idProduk ?? '')) ?>"
           class="flex items-center gap-2 px-5 py-3 bg-white border border-slate-200 rounded-xl text-slate-600 hover:bg-slate-50 hover:border-slate-300 transition-all w-full sm:w-auto justify-center">
            <i class="ph-fill ph-arrow-left"></i>
            <span class="text-sm font-medium">Kembali ke Kelas</span>
        </a>

        <?php if (isset($nextVideo) && $nextVideo): ?>
        <a href="<?= base_url('student/video/' . $nextVideo['id_video']) ?>"
           class="flex items-center gap-2 px-5 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-all w-full sm:w-auto justify-center shadow-lg shadow-blue-600/25">
            <span class="text-sm font-medium">Video Berikutnya</span>
            <i class="ph-fill ph-arrow-right"></i>
        </a>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>