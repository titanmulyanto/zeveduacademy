<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<!-- Breadcrumb -->
<div class="text-sm breadcrumbs mb-4">
    <ul>
        <li><a href="<?= base_url('admin/madmin') ?>">Materi & Tes</a></li>
        <li><?= $produk['judul'] ?></li>
    </ul>
</div>

<!-- Header -->
<div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6">
    <div class="flex items-center gap-4">
        <a href="<?= base_url('admin/materi') ?>" class="btn btn-circle btn-ghost">
            <i class="ph ph-arrow-left text-2xl"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold"><?= $produk['judul'] ?></h1>
            <p class="text-sm opacity-60">Kelola modul, video, dokumen, dan ujian sertifikasi</p>
        </div>
    </div>
    <div class="badge badge-outline">ID: <?= $produk['id_produk'] ?></div>
</div>

<!-- Flash Messages -->
<?php if (session()->getFlashdata('success')): ?>
<div class="alert alert-success mb-4">
    <i class="ph ph-check-circle"></i>
    <span><?= session()->getFlashdata('success') ?></span>
</div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
<div class="alert alert-error mb-4">
    <i class="ph ph-warning-circle"></i>
    <span><?= session()->getFlashdata('error') ?></span>
</div>
<?php endif; ?>

<!-- Tab Navigation -->
<div role="tablist" class="tabs tabs-lifted">
    <!-- Tab 1: Modul & Video -->
    <input type="radio" name="kurikulum_tabs" role="tab" class="tab" aria-label="📚 Modul & Video" checked />
    <div role="tabpanel" class="tab-content bg-base-100 border-base-300 rounded-box p-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-lg font-bold">Modul & Video</h2>
                <p class="text-sm opacity-60">Kelola bab/modul dan video YouTube</p>
            </div>
            <button class="btn btn-primary btn-sm" onclick="openAddModuleModal()">
                <i class="ph ph-plus"></i> Tambah Modul
            </button>
        </div>

        <?php if (empty($modules)): ?>
        <div class="text-center py-12 bg-base-200 rounded-xl border-2 border-dashed border-base-300">
            <i class="ph ph-folder-dashed text-5xl opacity-20 mb-3"></i>
            <p class="opacity-60">Belum ada modul. Klik tombol "Tambah Modul" untuk membuat.</p>
        </div>
        <?php else: ?>
        <div class="space-y-4">
            <?php foreach ($modules as $mod): ?>
            <div class="card bg-base-200 shadow-sm">
                <div class="card-body p-0">
                    <div class="flex items-center justify-between p-4 cursor-pointer hover:bg-base-300 transition-colors" onclick="toggleModul(<?= $mod['id_kategori_materi'] ?>)">
                        <div class="flex items-center gap-3">
                            <div class="bg-primary/10 p-2 rounded-lg">
                                <i class="ph ph-folder text-xl text-primary"></i>
                            </div>
                            <div>
                                <span class="badge badge-primary badge-sm mr-2"><?= $mod['urutan_kategori'] ?></span>
                                <span class="font-bold"><?= esc($mod['judul_kategori']) ?></span>
                                <span class="text-sm opacity-60 ml-2">(<?= count($mod['videos'] ?? []) ?> video)</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <button class="btn btn-ghost btn-sm" onclick="event.stopPropagation(); openEditModuleModal(<?= $mod['id_kategori_materi'] ?>, '<?= esc($mod['judul_kategori'], 'js') ?>', <?= $mod['urutan_kategori'] ?>)">
                                <i class="ph ph-pencil text-warning"></i>
                            </button>
                            <a href="<?= base_url('admin/materi/delete-kategori/' . $mod['id_kategori_materi']) ?>" class="btn btn-ghost btn-sm" onclick="return confirm('Hapus modul beserta video di dalamnya?')">
                                <i class="ph ph-trash text-error"></i>
                            </a>
                            <i class="ph ph-caret-down transition-transform" id="arrow-<?= $mod['id_kategori_materi'] ?>"></i>
                        </div>
                    </div>

                    <!-- Video List (Collapsible) -->
                    <div class="border-t border-base-300 hidden" id="modul-content-<?= $mod['id_kategori_materi'] ?>">
                        <div class="p-4">
                            <button class="btn btn-outline btn-primary btn-sm w-full mb-4" onclick="openAddVideoModal(<?= $mod['id_kategori_materi'] ?>)">
                                <i class="ph ph-plus-circle"></i> Tambah Video
                            </button>

                            <?php if (empty($mod['videos'])): ?>
                            <div class="text-center py-6 bg-base-100 rounded-lg">
                                <i class="ph ph-video-camera text-3xl opacity-20"></i>
                                <p class="text-sm opacity-50 mt-2">Belum ada video di modul ini</p>
                            </div>
                            <?php else: ?>
                            <div class="space-y-2">
                                <?php foreach ($mod['videos'] as $vid): ?>
                                <div class="flex items-center justify-between p-3 bg-base-100 rounded-lg hover:bg-base-300 transition-colors">
                                    <div class="flex items-center gap-3 flex-1">
                                        <div class="bg-base-200 p-2 rounded">
                                            <i class="ph ph-youtube-logo text-xl text-error"></i>
                                        </div>
                                        <div class="flex-1">
                                            <p class="font-medium text-sm"><?= esc($vid['judul_video']) ?></p>
                                            <div class="flex gap-3 text-xs opacity-60">
                                                <span><i class="ph ph-clock"></i> <?= $vid['durasi_menit'] ?? 0 ?> menit</span>
                                                <?php if (($vid['minimal_progress_unlock'] ?? 0) > 0): ?>
                                                <span class="text-warning"><i class="ph ph-lock"></i> Unlock <?= $vid['minimal_progress_unlock'] ?>%</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex gap-1">
                                        <?php if (!empty($vid['youtube_url'])): ?>
                                        <a href="<?= esc($vid['youtube_url']) ?>" target="_blank" class="btn btn-ghost btn-sm">
                                            <i class="ph ph-youtube-logo text-error"></i>
                                        </a>
                                        <?php endif; ?>
                                        <button class="btn btn-ghost btn-sm" onclick="openEditVideoModal(<?= $vid['id_video'] ?>, '<?= esc($vid['judul_video'], 'js') ?>', '<?= esc($vid['youtube_url'] ?? '', 'js') ?>', <?= $vid['durasi_menit'] ?? 0 ?>, <?= $vid['urutan_video'] ?? 1 ?>, <?= $vid['minimal_progress_unlock'] ?? 0 ?>)">
                                            <i class="ph ph-pencil text-warning"></i>
                                        </button>
                                        <a href="<?= base_url('admin/materi/delete-video/' . $vid['id_video']) ?>" class="btn btn-ghost btn-sm" onclick="return confirm('Hapus video ini?')">
                                            <i class="ph ph-trash text-error"></i>
                                        </a>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>

    <!-- Tab 2: Dokumen -->
    <input type="radio" name="kurikulum_tabs" role="tab" class="tab" aria-label="📄 Dokumen" />
    <div role="tabpanel" class="tab-content bg-base-100 border-base-300 rounded-box p-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-lg font-bold">Dokumen</h2>
                <p class="text-sm opacity-60">Upload PDF, DOC, DOCX</p>
            </div>
            <button class="btn btn-primary btn-sm" onclick="openAddDokumenModal()">
                <i class="ph ph-plus"></i> Tambah Dokumen
            </button>
        </div>

        <?php if (empty($dokumen)): ?>
        <div class="text-center py-12 bg-base-200 rounded-xl">
            <i class="ph ph-file-dashed text-5xl opacity-20"></i>
            <p class="opacity-60 mt-2">Belum ada dokumen. Klik tombol di atas untuk menambah.</p>
        </div>
        <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <?php foreach ($dokumen as $doc):
                $ext = strtolower(pathinfo($doc['file_materi'] ?? '', PATHINFO_EXTENSION));
                $icon = ($ext === 'pdf') ? 'ph-file-pdf text-error' : 'ph-file-doc text-primary';
            ?>
            <div class="card bg-base-200">
                <div class="card-body">
                    <div class="flex items-start justify-between">
                        <div class="flex items-center gap-3">
                            <i class="ph <?= $icon ?> text-2xl"></i>
                            <div>
                                <h4 class="font-semibold text-sm"><?= esc($doc['judul_materi']) ?></h4>
                                <p class="text-xs opacity-60"><?= strtoupper($ext) ?> • Urutan: <?= $doc['urutan_materi'] ?? 1 ?></p>
                            </div>
                        </div>
                    </div>
                    <?php if (!empty($doc['deskripsi'])): ?>
                    <p class="text-xs opacity-70 mt-2"><?= esc($doc['deskripsi']) ?></p>
                    <?php endif; ?>
                    <div class="card-actions justify-end mt-4">
                        <?php if (!empty($doc['file_materi'])): ?>
                        <a href="<?= base_url($doc['file_materi']) ?>" target="_blank" class="btn btn-ghost btn-xs">
                            <i class="ph ph-eye"></i> Lihat
                        </a>
                        <?php endif; ?>
                        <button class="btn btn-ghost btn-xs" onclick="openEditDokumenModal(<?= $doc['id_materi'] ?>, '<?= esc($doc['judul_materi'], 'js') ?>', '<?= esc($doc['deskripsi'] ?? '', 'js') ?>', <?= $doc['urutan_materi'] ?? 1 ?>)">
                            <i class="ph ph-pencil text-warning"></i>
                        </button>
                        <a href="<?= base_url('admin/materi/delete-materi/' . $doc['id_materi']) ?>" class="btn btn-ghost btn-xs" onclick="return confirm('Hapus dokumen ini?')">
                            <i class="ph ph-trash text-error"></i>
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>

    <!-- Tab 3: Ujian -->
    <input type="radio" name="kurikulum_tabs" role="tab" class="tab" aria-label="📝 Ujian" />
    <div role="tabpanel" class="tab-content bg-base-100 border-base-300 rounded-box p-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-lg font-bold">Ujian Sertifikasi</h2>
                <p class="text-sm opacity-60">Atur soal dan konfigurasi ujian</p>
            </div>
            <?php if (!isset($ujian)): ?>
            <a href="<?= base_url('admin/ujian/create/' . $produk['id_produk']) ?>" class="btn btn-primary btn-sm">
                <i class="ph ph-plus"></i> Buat Ujian
            </a>
            <?php endif; ?>
        </div>

        <?php if (isset($ujian) && $ujian): ?>
        <div class="card bg-base-200">
            <div class="card-body">
                <div class="flex flex-col md:flex-row justify-between items-start gap-4">
                    <div>
                        <h3 class="font-bold text-lg"><?= esc($ujian['judul_ujian'] ?? 'Ujian Sertifikasi') ?></h3>
                        <div class="flex flex-wrap gap-2 mt-2">
                            <span class="badge badge-primary"><i class="ph ph-clock"></i> <?= $ujian['durasi_menit'] ?? 60 ?> menit</span>
                            <span class="badge badge-secondary"><i class="ph ph-star"></i> Min. <?= $ujian['nilai_minimal_lulus'] ?? 70 ?></span>
                            <span class="badge badge-accent"><i class="ph ph-list-checks"></i> <?= $ujian['total_soal'] ?? 0 ?> soal</span>
                            <?php if (isset($ujian['minimal_progress_persen'])): ?>
                            <span class="badge badge-warning"><i class="ph ph-chart-pie"></i> Min. Progress <?= $ujian['minimal_progress_persen'] ?>%</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <a href="<?= base_url('admin/ujian/questions/' . $ujian['id_ujian']) ?>" class="btn btn-primary btn-sm">
                            <i class="ph ph-list-bullets"></i> Kelola Soal
                        </a>
                        <a href="<?= base_url('admin/ujian/edit/' . $ujian['id_ujian']) ?>" class="btn btn-outline btn-sm">
                            <i class="ph ph-pencil"></i> Edit
                        </a>
                        <a href="<?= base_url('admin/ujian/delete/' . $ujian['id_ujian']) ?>" class="btn btn-ghost btn-sm text-error" onclick="return confirm('Hapus ujian beserta semua soal?')">
                            <i class="ph ph-trash"></i>
                        </a>
                    </div>
                </div>

                <?php if (!empty($soal_preview)): ?>
                <div class="divider">Preview Soal</div>
                <div class="space-y-2">
                    <?php foreach (array_slice($soal_preview, 0, 3) as $idx => $soal): ?>
                    <div class="bg-base-100 p-3 rounded-lg">
                        <div class="flex justify-between items-center">
                            <span class="badge badge-ghost">Soal <?= $idx + 1 ?></span>
                            <span class="badge badge-success">Jawaban: <?= $soal['jawaban_benar'] ?></span>
                        </div>
                        <p class="text-sm mt-1"><?= esc(substr($soal['pertanyaan'], 0, 100)) ?>...</p>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                <div class="divider"></div>
                <p class="text-center opacity-60 py-4">
                    <i class="ph ph-exam text-2xl opacity-20"></i><br>
                    Belum ada soal. <a href="<?= base_url('admin/ujian/questions/' . $ujian['id_ujian']) ?>" class="text-primary">Tambah soal</a>
                </p>
                <?php endif; ?>
            </div>
        </div>
        <?php else: ?>
        <div class="text-center py-12 bg-base-200 rounded-xl">
            <i class="ph ph-exam text-5xl opacity-20"></i>
            <p class="opacity-60 mt-2">Belum ada ujian untuk kelas ini.</p>
        </div>
        <?php endif; ?>
    </div>

    <!-- Tab 4: Chat -->
    <input type="radio" name="kurikulum_tabs" role="tab" class="tab" aria-label="💬 Chat" />
    <div role="tabpanel" class="tab-content bg-base-100 border-base-300 rounded-box p-6">
        <div class="mb-4">
            <h2 class="text-lg font-bold">Chat dengan Student</h2>
            <p class="text-sm opacity-60">Kirim pesan broadcast ke student</p>
        </div>

        <div class="card bg-base-200">
            <div class="card-body max-h-80 overflow-y-auto">
                <?php if (!empty($chats)): ?>
                <div class="space-y-3">
                    <?php foreach ($chats as $chat): ?>
                    <div class="chat <?= $chat['pengirim'] === 'student' ? 'chat-start' : 'chat-end' ?> relative group">
                        <div class="chat-bubble <?= $chat['pengirim'] === 'student' ? 'bg-base-100' : 'bg-primary text-primary-content' ?>">
                            <p class="text-sm"><?= esc($chat['pesan']) ?></p>
                        </div>
                        <div class="chat-footer opacity-60 text-xs mt-1 flex items-center justify-between">
                            <div>
                                <?= esc($chat['nama_pengirim'] ?? $chat['pengirim']) ?> •
                                <?= date('d/m H:i', strtotime($chat['waktu_kirim'] ?? 'now')) ?>
                                <?php if ($chat['pengirim'] === 'student'): ?>
                                <span class="badge badge-ghost badge-xs ml-1">Student</span>
                                <?php else: ?>
                                <span class="badge badge-primary badge-xs ml-1"><?= ucfirst($chat['pengirim']) ?></span>
                                <?php endif; ?>
                            </div>
                            <button onclick="confirmDeleteChat(<?= $chat['id_chat'] ?>)" class="btn btn-ghost btn-xs text-error opacity-0 group-hover:opacity-100 transition-opacity">
                                <i class="ph ph-trash"></i> Hapus
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                <div class="text-center py-8">
                    <i class="ph ph-chat-dots text-4xl opacity-20"></i>
                    <p class="opacity-50 mt-2">Belum ada chat.</p>
                </div>
                <?php endif; ?>
            </div>
            <form action="<?= base_url('admin/materi/send-chat') ?>" method="POST" class="p-4 border-t border-base-300">
                <input type="hidden" name="id_produk" value="<?= $produk['id_produk'] ?>">
                <div class="flex gap-2">
                    <input type="text" name="pesan" placeholder="Ketik pesan..." class="input input-bordered flex-1" required>
                    <button type="submit" class="btn btn-primary">
                        <i class="ph ph-paper-plane-tilt"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tab 5: Progress Student -->
    <input type="radio" name="kurikulum_tabs" role="tab" class="tab" aria-label="📊 Progress" />
    <div role="tabpanel" class="tab-content bg-base-100 border-base-300 rounded-box p-6">
        <div class="mb-4">
            <h2 class="text-lg font-bold">Progress Student</h2>
            <p class="text-sm opacity-60">Lacak progress belajar student di kelas ini</p>
        </div>

        <?php if (!empty($student_progress)): ?>
        <div class="overflow-x-auto">
            <table class="table table-zebra">
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Materi</th>
                        <th>Progress</th>
                        <th>Status</th>
                        <th>Terakhir</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($student_progress as $sp): ?>
                    <tr>
                        <td>
                            <div class="flex items-center gap-2">
                                <div class="avatar placeholder">
                                    <div class="bg-neutral text-neutral-content rounded-full w-8">
                                        <span class="text-xs"><?= strtoupper(substr($sp['nama_lengkap'] ?? 'S', 0, 1)) ?></span>
                                    </div>
                                </div>
                                <span class="font-medium text-sm"><?= esc($sp['nama_lengkap'] ?? 'Unknown') ?></span>
                            </div>
                        </td>
                        <td class="text-sm"><?= esc($sp['judul_materi'] ?? $sp['judul_video'] ?? 'N/A') ?></td>
                        <td>
                            <div class="flex items-center gap-2">
                                <progress class="progress progress-primary w-20" value="<?= $sp['progress_persen'] ?? 0 ?>" max="100"></progress>
                                <span class="text-sm font-bold"><?= $sp['progress_persen'] ?? 0 ?>%</span>
                            </div>
                        </td>
                        <td>
                            <?php if (($sp['status_selesai'] ?? '') === 'selesai'): ?>
                            <span class="badge badge-success">Selesai</span>
                            <?php else: ?>
                            <span class="badge badge-warning">Belum</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-xs opacity-60">
                            <?= $sp['terakhir_ditonton'] ? date('d/m/Y H:i', strtotime($sp['terakhir_ditonton'])) : '-' ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="text-center py-12 bg-base-200 rounded-xl">
            <i class="ph ph-chart-line text-5xl opacity-20"></i>
            <p class="opacity-60 mt-2">Belum ada data progress.</p>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- ==================== MODALS ==================== -->

<!-- Add Module Modal -->
<dialog id="add_module_modal" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg mb-4"><i class="ph ph-folder-plus text-primary"></i> Tambah Modul</h3>
        <form action="<?= base_url('admin/materi/store-kategori') ?>" method="POST">
            <input type="hidden" name="id_produk" value="<?= $produk['id_produk'] ?>">
            <div class="form-control mb-4">
                <label class="label"><span class="label-text font-semibold">Nama Modul</span></label>
                <input type="text" name="judul_kategori" class="input input-bordered" placeholder="Contoh: Modul 1 - Pengenalan" required>
            </div>
            <div class="form-control mb-4">
                <label class="label"><span class="label-text font-semibold">Urutan</span></label>
                <input type="number" name="urutan_kategori" class="input input-bordered" value="1" min="1" required>
            </div>
            <div class="modal-action">
                <button type="button" class="btn btn-ghost" onclick="add_module_modal.close()">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="ph ph-check"></i> Simpan</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop"><button>close</button></form>
</dialog>

<!-- Edit Module Modal -->
<dialog id="edit_module_modal" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg mb-4"><i class="ph ph-folder-edit text-warning"></i> Edit Modul</h3>
        <form action="" method="POST" id="edit_module_form">
            <div class="form-control mb-4">
                <label class="label"><span class="label-text font-semibold">Nama Modul</span></label>
                <input type="text" name="judul_kategori" id="edit_modul_nama" class="input input-bordered" required>
            </div>
            <div class="form-control mb-4">
                <label class="label"><span class="label-text font-semibold">Urutan</span></label>
                <input type="number" name="urutan_kategori" id="edit_modul_urutan" class="input input-bordered" min="1" required>
            </div>
            <div class="modal-action">
                <button type="button" class="btn btn-ghost" onclick="edit_module_modal.close()">Batal</button>
                <button type="submit" class="btn btn-warning"><i class="ph ph-check"></i> Update</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop"><button>close</button></form>
</dialog>

<!-- Add Video Modal -->
<dialog id="add_video_modal" class="modal">
    <div class="modal-box max-w-xl">
        <h3 class="font-bold text-lg mb-4"><i class="ph ph-video-camera text-primary"></i> Tambah Video</h3>
        <form action="<?= base_url('admin/materi/store-video') ?>" method="POST">
            <input type="hidden" name="id_kategori_materi" id="add_video_kategori_id">
            <div class="form-control mb-4">
                <label class="label"><span class="label-text font-semibold">Judul Video</span></label>
                <input type="text" name="judul_video" class="input input-bordered" placeholder="Contoh: Video 1 - Pendahuluan" required>
            </div>
            <div class="form-control mb-4">
                <label class="label"><span class="label-text font-semibold">YouTube URL</span></label>
                <input type="url" name="youtube_url" class="input input-bordered" placeholder="https://youtube.com/watch?v=...">
            </div>
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div class="form-control">
                    <label class="label"><span class="label-text">Durasi (menit)</span></label>
                    <input type="number" name="durasi_menit" class="input input-bordered" value="0" min="0">
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Urutan</span></label>
                    <input type="number" name="urutan_video" class="input input-bordered" value="1" min="1">
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Unlock (%)</span></label>
                    <input type="number" name="minimal_progress_unlock" class="input input-bordered" value="0" min="0" max="100">
                    <label class="label-text-alt text-error">Min. progress untuk unlock</label>
                </div>
            </div>
            <div class="modal-action">
                <button type="button" class="btn btn-ghost" onclick="add_video_modal.close()">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="ph ph-check"></i> Simpan</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop"><button>close</button></form>
</dialog>

<!-- Edit Video Modal -->
<dialog id="edit_video_modal" class="modal">
    <div class="modal-box max-w-xl">
        <h3 class="font-bold text-lg mb-4"><i class="ph ph-video-edit text-warning"></i> Edit Video</h3>
        <form action="" method="POST" id="edit_video_form">
            <div class="form-control mb-4">
                <label class="label"><span class="label-text font-semibold">Judul Video</span></label>
                <input type="text" name="judul_video" id="edit_video_judul" class="input input-bordered" required>
            </div>
            <div class="form-control mb-4">
                <label class="label"><span class="label-text">YouTube URL</span></label>
                <input type="url" name="youtube_url" id="edit_video_url" class="input input-bordered">
            </div>
            <div class="grid grid-cols-3 gap-4 mb-4">
                <div class="form-control">
                    <label class="label"><span class="label-text">Durasi (menit)</span></label>
                    <input type="number" name="durasi_menit" id="edit_video_durasi" class="input input-bordered" min="0">
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Urutan</span></label>
                    <input type="number" name="urutan_video" id="edit_video_urutan" class="input input-bordered" min="1">
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Unlock (%)</span></label>
                    <input type="number" name="minimal_progress_unlock" id="edit_video_unlock" class="input input-bordered" min="0" max="100">
                </div>
            </div>
            <div class="modal-action">
                <button type="button" class="btn btn-ghost" onclick="edit_video_modal.close()">Batal</button>
                <button type="submit" class="btn btn-warning"><i class="ph ph-check"></i> Update</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop"><button>close</button></form>
</dialog>

<!-- Add Dokumen Modal -->
<dialog id="add_dokumen_modal" class="modal">
    <div class="modal-box max-w-xl">
        <h3 class="font-bold text-lg mb-4"><i class="ph ph-file-plus text-primary"></i> Tambah Dokumen</h3>
        <form action="<?= base_url('admin/materi/store-materi') ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id_produk" value="<?= $produk['id_produk'] ?>">
            <div class="form-control mb-4">
                <label class="label"><span class="label-text font-semibold">Judul Dokumen</span></label>
                <input type="text" name="judul_materi" class="input input-bordered" placeholder="Contoh: Modul 1 - Pengenalan" required>
            </div>
            <div class="form-control mb-4">
                <label class="label"><span class="label-text">Deskripsi</span></label>
                <textarea name="deskripsi" class="textarea textarea-bordered" rows="2"></textarea>
            </div>
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div class="form-control">
                    <label class="label"><span class="label-text">Upload File PDF/DOC</span></label>
                    <input type="file" name="file_materi" class="file-input file-input-bordered w-full" accept=".pdf,.doc,.docx">
                    <label class="label-text-alt">Format: PDF, DOC, DOCX</label>
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Urutan</span></label>
                    <input type="number" name="urutan_materi" class="input input-bordered" value="1" min="1">
                </div>
            </div>
            <div class="modal-action">
                <button type="button" class="btn btn-ghost" onclick="add_dokumen_modal.close()">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="ph ph-check"></i> Simpan</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop"><button>close</button></form>
</dialog>

<!-- Edit Dokumen Modal -->
<dialog id="edit_dokumen_modal" class="modal">
    <div class="modal-box max-w-xl">
        <h3 class="font-bold text-lg mb-4"><i class="ph ph-file-edit text-warning"></i> Edit Dokumen</h3>
        <form action="" method="POST" enctype="multipart/form-data" id="edit_dokumen_form">
            <div class="form-control mb-4">
                <label class="label"><span class="label-text font-semibold">Judul</span></label>
                <input type="text" name="judul_materi" id="edit_dokumen_judul" class="input input-bordered" required>
            </div>
            <div class="form-control mb-4">
                <label class="label"><span class="label-text">Deskripsi</span></label>
                <textarea name="deskripsi" id="edit_dokumen_desc" class="textarea textarea-bordered" rows="2"></textarea>
            </div>
            <div class="form-control mb-4">
                <label class="label"><span class="label-text">Ganti File</span></label>
                <input type="file" name="file_materi" class="file-input file-input-bordered w-full" accept=".pdf,.doc,.docx">
            </div>
            <div class="form-control mb-4">
                <label class="label"><span class="label-text">Urutan</span></label>
                <input type="number" name="urutan_materi" id="edit_dokumen_urutan" class="input input-bordered" min="1">
            </div>
            <div class="modal-action">
                <button type="button" class="btn btn-ghost" onclick="edit_dokumen_modal.close()">Batal</button>
                <button type="submit" class="btn btn-warning"><i class="ph ph-check"></i> Update</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop"><button>close</button></form>
</dialog>

<script>
    // Toggle modul collapse
    function toggleModul(id) {
        const content = document.getElementById('modul-content-' + id);
        const arrow = document.getElementById('arrow-' + id);
        content.classList.toggle('hidden');
        arrow.classList.toggle('rotate-180');
    }

    // Open modals
    function openAddModuleModal() { add_module_modal.showModal(); }
    function openAddVideoModal(kategoriId) {
        document.getElementById('add_video_kategori_id').value = kategoriId;
        add_video_modal.showModal();
    }
    function openAddDokumenModal() { add_dokumen_modal.showModal(); }

    // Edit module
    function openEditModuleModal(id, nama, urutan) {
        document.getElementById('edit_modul_nama').value = nama;
        document.getElementById('edit_modul_urutan').value = urutan;
        document.getElementById('edit_module_form').action = '<?= base_url('admin/materi/update-kategori') ?>/' + id;
        edit_module_modal.showModal();
    }

    // Edit video
    function openEditVideoModal(id, judul, youtube, durasi, urutan, unlock) {
        document.getElementById('edit_video_judul').value = judul;
        document.getElementById('edit_video_url').value = youtube || '';
        document.getElementById('edit_video_durasi').value = durasi || 0;
        document.getElementById('edit_video_urutan').value = urutan || 1;
        document.getElementById('edit_video_unlock').value = unlock || 0;
        document.getElementById('edit_video_form').action = '<?= base_url('admin/materi/update-video') ?>/' + id;
        edit_video_modal.showModal();
    }

    // Edit dokumen
    function openEditDokumenModal(id, judul, desc, urutan) {
        document.getElementById('edit_dokumen_judul').value = judul;
        document.getElementById('edit_dokumen_desc').value = desc || '';
        document.getElementById('edit_dokumen_urutan').value = urutan || 1;
        document.getElementById('edit_dokumen_form').action = '<?= base_url('admin/materi/update-materi') ?>/' + id;
        edit_dokumen_modal.showModal();
    }

    // Delete chat with confirmation
    function confirmDeleteChat(chatId) {
        if (confirm('Hapus chat ini?')) {
            window.location.href = '<?= base_url('admin/materi/delete-chat/') ?>' + chatId;
        }
    }
</script>

<?= $this->endSection() ?>