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

<!-- ==================== CLASS HEADER ==================== -->
<div class="mb-6 lg:mb-8">
    <div class="flex items-center gap-4 mb-4">
        <a href="<?= base_url('student') ?>"
           class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-600 hover:bg-slate-200 hover:text-slate-800 transition-colors lg:hidden">
            <i class="ph-fill ph-arrow-left text-xl"></i>
        </a>
        <div class="flex-1">
            <div class="flex items-center gap-2 text-sm text-slate-500 mb-1">
                <a href="<?= base_url('student') ?>" class="hover:text-blue-600">Dashboard</a>
                <i class="ph ph-caret-right text-xs"></i>
                <span class="text-slate-700"><?= esc($produk['judul']) ?></span>
            </div>
            <h1 class="text-xl lg:text-2xl font-bold text-slate-800"><?= esc($produk['judul']) ?></h1>
            <p class="text-sm text-slate-500 mt-1"><?= count($modules) ?> modul • <?= count($dokumen) ?> dokumen</p>
        </div>
    </div>

    <!-- Exam Button -->
    <?php if ($ujian): ?>
    <div class="flex flex-wrap items-center gap-3">
        <?php if ($canTakeExam): ?>
        <a href="<?= base_url('student/exam/' . $ujian['id_ujian']) ?>"
           class="flex items-center gap-2 px-5 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl font-semibold hover:from-blue-700 hover:to-blue-800 transition-all shadow-lg shadow-blue-600/25">
            <i class="ph-fill ph-exam text-lg"></i>
            <span>Ikuti Ujian Sekarang</span>
        </a>
        <?php else: ?>
        <div class="flex items-center gap-2 px-4 py-2.5 bg-amber-50 border border-amber-200 rounded-xl text-amber-700">
            <i class="ph-fill ph-lock text-lg"></i>
            <span class="text-sm font-medium">Selesaikan <?= $ujian['minimal_progress_persen'] ?? 100 ?>% untuk ujian</span>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>

<!-- ==================== TAB NAVIGATION ==================== -->
<div class="mb-6">
    <div class="flex gap-1 bg-slate-100 p-1 rounded-xl overflow-x-auto">
        <button onclick="switchTab('video')" id="tab-video"
                class="flex-1 lg:flex-none flex items-center justify-center gap-2 px-4 py-3 rounded-lg text-sm font-medium transition-all tab-btn active">
            <i class="ph-fill ph-video-camera text-lg"></i>
            <span>Video</span>
        </button>
        <button onclick="switchTab('dokumen')" id="tab-dokumen"
                class="flex-1 lg:flex-none flex items-center justify-center gap-2 px-4 py-3 rounded-lg text-sm font-medium transition-all tab-btn">
            <i class="ph-fill ph-file-text text-lg"></i>
            <span>Dokumen</span>
            <?php if (!empty($dokumen)): ?>
            <span class="px-2 py-0.5 bg-blue-600 text-white text-xs rounded-full"><?= count($dokumen) ?></span>
            <?php endif; ?>
        </button>
        <button onclick="switchTab('chat')" id="tab-chat"
                class="flex-1 lg:flex-none flex items-center justify-center gap-2 px-4 py-3 rounded-lg text-sm font-medium transition-all tab-btn">
            <i class="ph-fill ph-chat-circle-dots text-lg"></i>
            <span>Diskusi</span>
        </button>
    </div>
</div>

<!-- ==================== TAB CONTENT: VIDEO ==================== -->
<div id="content-video" class="tab-content">
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
        <?php if (empty($modules)): ?>
        <!-- Empty State -->
        <div class="p-8 lg:p-12 text-center">
            <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-4">
                <i class="ph-fill ph-video-camera text-3xl text-slate-400"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-700 mb-2">Belum Ada Modul Video</h3>
            <p class="text-slate-500">Modul video akan segera ditambahkan oleh pengajar.</p>
        </div>
        <?php else: ?>
        <!-- Module Accordion -->
        <div class="divide-y divide-slate-100">
            <?php foreach($modules as $modIndex => $mod): ?>
            <div class="module-item" data-module="<?= $modIndex ?>">
                <!-- Module Header -->
                <button onclick="toggleModule(<?= $modIndex ?>)"
                        class="w-full flex items-center gap-4 p-4 lg:p-5 hover:bg-slate-50 transition-colors text-left">
                    <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center flex-shrink-0">
                        <span class="font-bold text-blue-600"><?= $mod['urutan_kategori'] ?></span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-semibold text-slate-800"><?= esc($mod['judul_kategori']) ?></h3>
                        <p class="text-sm text-slate-500 mt-0.5">
                            <?= count($mod['videos'] ?? []) ?> video
                        </p>
                    </div>
                    <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center transition-transform module-arrow" id="arrow-<?= $modIndex ?>">
                        <i class="ph-fill ph-caret-down text-slate-600"></i>
                    </div>
                </button>

                <!-- Module Content -->
                <div class="hidden bg-slate-50/50" id="module-content-<?= $modIndex ?>">
                    <div class="p-4 lg:p-5 space-y-3">
                        <?php if (empty($mod['videos'])): ?>
                        <div class="text-center py-6 text-slate-500">
                            <i class="ph ph-video-camera-slash text-2xl mb-2"></i>
                            <p>Belum ada video di modul ini.</p>
                        </div>
                        <?php else: ?>
                        <?php foreach($mod['videos'] as $vidIndex => $vid): ?>
                        <?php
                        $isCompleted = isset($progressMap[$vid['id_video']]) && $progressMap[$vid['id_video']]['status_selesai'] === 'selesai';
                        ?>
                        <a href="<?= base_url('student/video/' . $vid['id_video']) ?>"
                           class="flex items-center gap-4 p-3 bg-white rounded-xl border border-slate-200 hover:border-blue-300 hover:shadow-md transition-all group">
                            <!-- Play Button / Check -->
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0 <?= $isCompleted ? 'bg-emerald-100' : 'bg-blue-100 group-hover:bg-blue-600' ?> transition-colors">
                                <?php if ($isCompleted): ?>
                                <i class="ph-fill ph-check-circle text-xl text-emerald-600"></i>
                                <?php else: ?>
                                <i class="ph-fill ph-play text-xl text-blue-600 group-hover:text-white"></i>
                                <?php endif; ?>
                            </div>

                            <!-- Video Info -->
                            <div class="flex-1 min-w-0">
                                <h4 class="font-medium text-slate-800 group-hover:text-blue-600 transition-colors line-clamp-1">
                                    <?= esc($vid['judul_video']) ?>
                                </h4>
                                <div class="flex items-center gap-3 mt-1">
                                    <span class="text-xs text-slate-500 flex items-center gap-1">
                                        <i class="ph ph-clock"></i>
                                        <?= $vid['durasi_menit'] ?? 0 ?> menit
                                    </span>
                                    <?php if (!empty($vid['youtube_url'])): ?>
                                    <span class="text-xs text-red-500 flex items-center gap-1">
                                        <i class="ph-fill ph-youtube-logo"></i>
                                        YouTube
                                    </span>
                                    <?php endif; ?>
                                    <?php if ($isCompleted): ?>
                                    <span class="text-xs text-emerald-600 flex items-center gap-1">
                                        <i class="ph-fill ph-check"></i>
                                        Selesai
                                    </span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Arrow -->
                            <i class="ph ph-arrow-right text-slate-400 group-hover:text-blue-600"></i>
                        </a>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- ==================== TAB CONTENT: DOKUMEN ==================== -->
<div id="content-dokumen" class="tab-content hidden">
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
        <?php if (empty($dokumen)): ?>
        <!-- Empty State -->
        <div class="p-8 lg:p-12 text-center">
            <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-4">
                <i class="ph-fill ph-file-text text-3xl text-slate-400"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-700 mb-2">Belum Ada Dokumen</h3>
            <p class="text-slate-500">Dokumen dan materi akan segera ditambahkan.</p>
        </div>
        <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 lg:p-6">
            <?php foreach($dokumen as $doc):
                $ext = strtolower(pathinfo($doc['file_materi'] ?? '', PATHINFO_EXTENSION));
                $isCompleted = isset($progressMap[$doc['id_materi']]) && $progressMap[$doc['id_materi']]['status_selesai'] === 'selesai';

                // Icon based on extension
                $iconClass = match($ext) {
                    'pdf' => 'ph-file-pdf text-red-500 bg-red-50',
                    'doc', 'docx' => 'ph-file-doc text-blue-500 bg-blue-50',
                    'xls', 'xlsx' => 'ph-file-xls text-emerald-500 bg-emerald-50',
                    'ppt', 'pptx' => 'ph-file-ppt text-orange-500 bg-orange-50',
                    'zip', 'rar' => 'ph-file-zip text-slate-500 bg-slate-100',
                    default => 'ph-file text-slate-500 bg-slate-100'
                };
            ?>
            <div class="p-4 lg:p-5 rounded-2xl border border-slate-200 hover:border-blue-300 hover:shadow-md transition-all">
                <div class="flex items-start gap-4">
                    <!-- Icon -->
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0 <?= $iconClass ?>">
                        <i class="ph-fill ph-file-text text-2xl"></i>
                    </div>

                    <!-- Content -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <h4 class="font-semibold text-slate-800 line-clamp-1"><?= esc($doc['judul_materi']) ?></h4>
                                <p class="text-xs text-slate-500 mt-1 uppercase"><?= $ext ?: 'Tanpa file' ?></p>
                            </div>
                            <?php if ($isCompleted): ?>
                            <div class="w-6 h-6 rounded-full bg-emerald-500 flex items-center justify-center flex-shrink-0">
                                <i class="ph-fill ph-check text-white text-xs"></i>
                            </div>
                            <?php endif; ?>
                        </div>

                        <?php if (!empty($doc['deskripsi'])): ?>
                        <p class="text-sm text-slate-500 mt-2 line-clamp-2"><?= esc($doc['deskripsi']) ?></p>
                        <?php endif; ?>

                        <!-- Action -->
                        <div class="mt-4">
                            <?php if (!empty($doc['file_materi'])): ?>
                            <a href="<?= base_url($doc['file_materi']) ?>" target="_blank"
                               class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                                <i class="ph-fill ph-download"></i>
                                <span>Unduh</span>
                            </a>
                            <?php else: ?>
                            <span class="inline-flex items-center gap-2 px-4 py-2 bg-slate-100 text-slate-500 rounded-lg text-sm">
                                <i class="ph ph-x-circle"></i>
                                <span>Tidak tersedia</span>
                            </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- ==================== TAB CONTENT: CHAT ==================== -->
<div id="content-chat" class="tab-content hidden">
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
        <!-- Chat Header -->
        <div class="p-4 lg:p-5 border-b border-slate-200">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center">
                    <i class="ph-fill ph-chats text-blue-600 text-lg"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-slate-800">Diskusi Kelas</h3>
                    <p class="text-sm text-slate-500">Tanyakan apapun ke pengajar</p>
                </div>
            </div>
        </div>

        <!-- Chat Messages -->
        <div class="h-80 lg:h-96 overflow-y-auto p-4 lg:p-5 space-y-4" id="chatContainer">
            <?php if (empty($chats)): ?>
            <div class="flex flex-col items-center justify-center h-full text-center py-8">
                <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center mb-4">
                    <i class="ph-fill ph-chat-dots text-3xl text-slate-400"></i>
                </div>
                <h4 class="font-semibold text-slate-700 mb-1">Belum Ada Pesan</h4>
                <p class="text-sm text-slate-500">Mulai percakapan dengan mengirim pesan</p>
            </div>
            <?php else: ?>
            <?php foreach($chats as $chat): ?>
            <?php $isSent = $chat['pengirim'] === 'student'; ?>
            <div class="flex <?= $isSent ? 'justify-end' : 'justify-start' ?>">
                <div class="max-w-[80%] <?= $isSent ? 'order-2' : 'order-1' ?>">
                    <div class="flex items-end gap-2 <?= $isSent ? 'flex-row-reverse' : 'flex-row' ?>">
                        <div class="w-8 h-8 rounded-xl bg-slate-200 flex items-center justify-center flex-shrink-0">
                            <?php if ($isSent): ?>
                            <span class="text-xs font-bold text-slate-600"><?= strtoupper(substr(session()->get('nama_lengkap') ?? 'S', 0, 1)) ?></span>
                            <?php else: ?>
                            <i class="ph-fill ph-user text-sm text-slate-600"></i>
                            <?php endif; ?>
                        </div>
                        <div>
                            <div class="px-4 py-3 rounded-2xl <?= $isSent ? 'bg-blue-600 text-white rounded-br-md' : 'bg-slate-100 text-slate-700 rounded-bl-md' ?>">
                                <p class="text-sm"><?= esc($chat['pesan']) ?></p>
                            </div>
                            <p class="text-[10px] text-slate-400 mt-1 <?= $isSent ? 'text-right' : '' ?>">
                                <?= date('d M, H:i', strtotime($chat['waktu_kirim'])) ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Chat Input -->
        <form action="<?= base_url('student/send-chat') ?>" method="POST"
              class="p-4 lg:p-5 border-t border-slate-200 bg-slate-50/50">
            <?= csrf_field() ?>
            <input type="hidden" name="id_produk" value="<?= $produk['id_produk'] ?>">
            <div class="flex items-center gap-3">
                <input type="text" name="pesan" placeholder="Ketik pesan untuk pengajar..."
                       class="flex-1 px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100 transition-all"
                       required>
                <button type="submit"
                        class="w-12 h-12 rounded-xl bg-blue-600 text-white flex items-center justify-center hover:bg-blue-700 transition-colors shadow-lg shadow-blue-600/25">
                    <i class="ph-fill ph-paper-plane-tilt text-lg"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ==================== FLOATING BACK BUTTON (Desktop) ==================== -->
<a href="<?= base_url('student') ?>"
   class="hidden lg:flex fixed bottom-8 left-8 items-center gap-2 px-4 py-3 bg-white border border-slate-200 rounded-xl shadow-lg hover:shadow-xl transition-all text-slate-600 hover:text-slate-800">
    <i class="ph-fill ph-arrow-left"></i>
    <span class="text-sm font-medium">Kembali</span>
</a>

<!-- ==================== SCRIPTS ==================== -->
<script>
    // Tab Switching
    function switchTab(tabName) {
        // Update tab buttons
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('active', 'bg-white', 'shadow-sm', 'text-blue-600');
            btn.classList.add('text-slate-600');
        });
        document.getElementById('tab-' + tabName).classList.add('active', 'bg-white', 'shadow-sm', 'text-blue-600');
        document.getElementById('tab-' + tabName).classList.remove('text-slate-600');

        // Update content
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.add('hidden');
        });
        document.getElementById('content-' + tabName).classList.remove('hidden');
    }

    // Module Accordion
    function toggleModule(moduleIndex) {
        const content = document.getElementById('module-content-' + moduleIndex);
        const arrow = document.getElementById('arrow-' + moduleIndex);

        if (content.classList.contains('hidden')) {
            // Close all other modules
            document.querySelectorAll('.module-content').forEach(c => c.classList.add('hidden'));
            document.querySelectorAll('.module-arrow').forEach(a => {
                a.style.transform = 'rotate(0deg)';
            });

            // Open this module
            content.classList.remove('hidden');
            arrow.style.transform = 'rotate(180deg)';
        } else {
            // Close this module
            content.classList.add('hidden');
            arrow.style.transform = 'rotate(0deg)';
        }
    }

    // Scroll to bottom of chat
    const chatContainer = document.getElementById('chatContainer');
    if (chatContainer) {
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }
</script>

<style>
    .tab-btn.active {
        background: white;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        color: #2563EB;
    }
</style>

<?= $this->endSection() ?>