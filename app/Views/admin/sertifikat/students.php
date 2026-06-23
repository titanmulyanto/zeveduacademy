<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<div class="breadcrumbs mb-4">
    <a href="<?= base_url('admin/sertifikat') ?>" class="text-primary">Kelas & Sertifikat</a>
    <span class="text-base-content/30">/</span>
    <span>Student: <?= esc($produk['judul']) ?></span>
</div>

<!-- Header with Actions -->
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
    <div>
        <!-- Info Box -->
        <div class="alert <?= ($produk['harga_promo'] ?? 0) > 0 ? 'alert-warning' : 'alert-info' ?> mb-0">
            <i class="ph ph-<?= ($produk['harga_promo'] ?? 0) > 0 ? 'currency-dollar' : 'gift' ?>"></i>
            <div>
                <div class="font-bold"><?= esc($produk['judul']) ?></div>
                <div class="text-sm">
                    <?= ($produk['harga_promo'] ?? 0) > 0 ? 'Kelas Berbayar - Rp ' . number_format($produk['harga_promo'], 0, ',', '.') : 'Kelas Gratis - Akses langsung' ?>
                    • <?= count($students) ?> student enrolled
                </div>
            </div>
        </div>
    </div>
    <div class="flex gap-2">
        <button onclick="addStudentModal.showModal()" class="btn btn-outline">
            <i class="ph ph-user-plus"></i> Tambah Student
        </button>
        <button onclick="newStudentModal.showModal()" class="btn btn-primary">
            <i class="ph ph-user-circle-plus"></i> Student Baru
        </button>
    </div>
</div>

<!-- Student List with Cards -->
<?php if (empty($students)): ?>
<div class="card bg-base-200 shadow border border-base-300">
    <div class="card-body text-center py-16">
        <i class="ph ph-users text-6xl text-base-content/20 mb-4"></i>
        <h3 class="text-xl font-bold text-base-content/60">Belum Ada Student</h3>
        <p class="text-base-content/40 mt-2">Student akan muncul setelah mereka mendaftar di kelas ini.</p>
        <button onclick="addStudentModal.showModal()" class="btn btn-primary mt-4 mx-auto">
            <i class="ph ph-user-plus"></i> Tambah Student Manual
        </button>
    </div>
</div>
<?php else: ?>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    <?php foreach ($students as $student): ?>
    <div class="card bg-base-100 shadow border border-base-200 hover:shadow-lg transition-shadow">
        <div class="card-body p-4">
            <!-- Header with Avatar and Name -->
            <div class="flex items-center gap-3 mb-4">
                <div class="avatar">
                    <div class="w-12 h-12 rounded-full ring ring-primary ring-offset-base-100 ring-offset-2">
                        <img src="<?= $student['foto_profil'] ?? 'https://ui-avatars.com/api/?name=' . urlencode($student['nama_lengkap']) . '&background=random' ?>" />
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <h4 class="font-bold truncate"><?= esc($student['nama_lengkap']) ?></h4>
                    <p class="text-xs text-base-content/50 truncate"><?= esc($student['email']) ?></p>
                </div>
                <!-- Dropdown Menu (DaisyUI style) -->
                <div class="dropdown dropdown-end">
                    <div tabindex="0" role="button" class="btn btn-ghost btn-xs btn-square">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="inline-block w-5 h-5 stroke-current">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </div>
                    <ul tabindex="0" class="z-10 dropdown-content menu p-2 shadow-lg bg-base-100 rounded-box w-52 border border-base-200">
                        <?php if (!empty($student['no_whatsapp'])): ?>
                        <li>
                            <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $student['no_whatsapp']) ?>" target="_blank" class="text-success">
                                <i class="ph ph-whatsapp-logo"></i> Chat WhatsApp
                            </a>
                        </li>
                        <?php endif; ?>
                        <li>
                            <a href="<?= base_url('admin/users/view/' . $student['id_user']) ?>">
                                <i class="ph ph-user"></i> Lihat Profil
                            </a>
                        </li>
                        <li class="border-t border-base-200 mt-1 pt-1">
                            <a href="#" class="text-error">
                                <i class="ph ph-trash"></i> Hapus Student
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Status Badges -->
            <div class="flex flex-wrap gap-2 mb-4">
                <?php if (($student['status_akses'] ?? '') === 'aktif'): ?>
                <span class="badge badge-success badge-sm">
                    <i class="ph ph-check-circle"></i> Aktif
                </span>
                <?php else: ?>
                <span class="badge badge-ghost badge-sm">Nonaktif</span>
                <?php endif; ?>

                <?php if (($student['harga_promo'] ?? 0) > 0): ?>
                    <span class="badge badge-warning badge-sm">
                        <i class="ph ph-credit-card"></i> Sudah Bayar
                    </span>
                <?php else: ?>
                    <span class="badge badge-info badge-sm">
                        <i class="ph ph-gift"></i> Bonus
                    </span>
                <?php endif; ?>
            </div>

            <!-- Progress Section -->
            <div class="space-y-3 border-t border-base-200 pt-3">
                <!-- Ujian -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <i class="ph ph-exam text-lg text-base-content/50"></i>
                        <span class="text-sm">Ujian</span>
                    </div>
                    <?php if (!empty($student['nilai_ujian'])): ?>
                        <div class="flex items-center gap-2">
                            <div class="radial-progress text-xs" style="--value:<?= $student['nilai_ujian'] ?>; --size:2rem; --thickness:3px;" role="progressbar">
                                <?= $student['nilai_ujian'] ?>%
                            </div>
                            <?php if ($student['status_lulus'] === 'lulus'): ?>
                            <span class="badge badge-success badge-xs">Lulus</span>
                            <?php else: ?>
                            <span class="badge badge-error badge-xs">Gagal</span>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <span class="badge badge-ghost badge-xs">Belum</span>
                    <?php endif; ?>
                </div>

                <!-- Sertifikat -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <i class="ph ph-certificate text-lg text-base-content/50"></i>
                        <span class="text-sm">Sertifikat</span>
                    </div>
                    <?php if (!empty($student['nomor_sertifikat'])): ?>
                        <div class="text-right">
                            <span class="badge badge-success badge-xs mb-1">
                                <i class="ph ph-check"></i> Terbit
                            </span>
                            <p class="text-xs font-mono text-base-content/40"><?= esc($student['nomor_sertifikat']) ?></p>
                        </div>
                    <?php else: ?>
                        <span class="badge badge-ghost badge-xs">Belum</span>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Footer with Date -->
            <div class="mt-4 pt-3 border-t border-base-200">
                <div class="flex items-center justify-between text-xs text-base-content/50">
                    <span><i class="ph ph-calendar"></i> Bergabung</span>
                    <span><?= date('d M Y', strtotime($student['tanggal_aktif'] ?? 'now')) ?></span>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<!-- Modal Tambah Student -->
<dialog id="addStudentModal" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg mb-4">
            <i class="ph ph-user-plus text-primary"></i> Tambah Student ke Kelas
        </h3>
        <form action="<?= base_url('admin/sertifikat/add-student/' . $produk['id_produk']) ?>" method="POST">
            <?= csrf_field() ?>
            <div class="form-control mb-4">
                <label class="label"><span class="label-text font-semibold">Pilih Student</span></label>
                <select name="id_user" class="select select-bordered" required>
                    <option value="">-- Pilih Student --</option>
                    <?php
                    // Get users who are students and not yet enrolled in this class
                    $db = \Config\Database::connect();
                    $enrolledIds = array_column($students, 'id_user');
                    $enrolledCondition = !empty($enrolledIds) ? 'AND id_user NOT IN (' . implode(',', array_map('intval', $enrolledIds)) . ')' : '';
                    $availableStudents = $db->query("SELECT id_user, nama_lengkap, email FROM users WHERE role = 'student' {$enrolledCondition} ORDER BY nama_lengkap ASC")->getResultArray();
                    ?>
                    <?php foreach ($availableStudents as $s): ?>
                    <option value="<?= $s['id_user'] ?>"><?= esc($s['nama_lengkap']) ?> (<?= esc($s['email']) ?>)</option>
                    <?php endforeach; ?>
                </select>
                <label class="label">
                    <span class="label-text-alt text-base-content/50">Pilih student yang belum terdaftar di kelas ini</span>
                </label>
            </div>

            <div class="modal-action">
                <button type="button" class="btn" onclick="addStudentModal.close()">Batal</button>
                <button type="submit" class="btn btn-outline">
                    <i class="ph ph-check"></i> Tambahkan
                </button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop"><button>close</button></form>
</dialog>

<!-- Modal Student Baru (Create New Student + Auto Enroll) -->
<dialog id="newStudentModal" class="modal">
    <div class="modal-box w-11/12 max-w-2xl">
        <h3 class="font-bold text-lg mb-4">
            <i class="ph ph-user-circle-plus text-primary"></i> Tambah Student Baru
        </h3>
        <form action="<?= base_url('admin/sertifikat/create-student/' . $produk['id_produk']) ?>" method="POST">
            <?= csrf_field() ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="form-control">
                    <label class="label"><span class="label-text font-semibold">Nama Lengkap</span></label>
                    <input type="text" name="nama_lengkap" class="input input-bordered" placeholder="Nama student" required />
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text font-semibold">Email</span></label>
                    <input type="email" name="email" class="input input-bordered" placeholder="email@example.com" required />
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text font-semibold">Password</span></label>
                    <input type="password" name="password" class="input input-bordered" placeholder="********" required />
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">No. WhatsApp</span></label>
                    <input type="text" name="no_whatsapp" class="input input-bordered" placeholder="08xxxxxxxxxx" />
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Instansi</span></label>
                    <input type="text" name="instansi" class="input input-bordered" placeholder="Sekolah/Universitas" />
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Jenis Kelamin</span></label>
                    <select name="jenis_kelamin" class="select select-bordered">
                        <option value="">-- Pilih --</option>
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>
                <div class="form-control col-span-full">
                    <label class="label"><span class="label-text">Alamat</span></label>
                    <textarea name="alamat" class="textarea textarea-bordered h-20" placeholder="Alamat lengkap"></textarea>
                </div>
            </div>

            <div class="modal-action">
                <button type="button" class="btn" onclick="newStudentModal.close()">Batal</button>
                <button type="submit" class="btn btn-primary">
                    <i class="ph ph-check"></i> Simpan & Enroll
                </button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop"><button>close</button></form>
</dialog>

<!-- Modal Enroll ke Multiple Classes -->
<dialog id="multiClassModal" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg mb-4">
            <i class="ph ph-books text-primary"></i> Pilih Kelas untuk Student
        </h3>
        <p class="text-sm text-base-content/70 mb-4">Pilih kelas-kelas yang ingin ditambahkan untuk student ini:</p>
        <form action="<?= base_url('admin/sertifikat/enroll-multiple') ?>" method="POST" id="multiClassForm">
            <?= csrf_field() ?>
            <input type="hidden" name="id_user" id="multiClassUserId">
            <div class="form-control">
                <?php
                // Get all classes for multi-enrollment
                $db = \Config\Database::connect();
                $allClasses = $db->query("SELECT id_produk, judul FROM produk_pelatihan ORDER BY judul ASC")->getResultArray();
                ?>
                <div class="space-y-2 max-h-60 overflow-y-auto">
                    <?php foreach ($allClasses as $cls): ?>
                    <label class="flex items-center gap-3 p-3 bg-base-200 rounded-lg cursor-pointer hover:bg-base-300 transition-colors">
                        <input type="checkbox" name="id_produk[]" value="<?= $cls['id_produk'] ?>" class="checkbox checkbox-primary" />
                        <span><?= esc($cls['judul']) ?></span>
                    </label>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="modal-action">
                <button type="button" class="btn" onclick="multiClassModal.close()">Batal</button>
                <button type="submit" class="btn btn-primary">
                    <i class="ph ph-check"></i> Enroll
                </button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop"><button>close</button></form>
</dialog>

<script>
    // Open multi-class enrollment modal
    function openMultiClassModal(userId, userName) {
        document.getElementById('multiClassUserId').value = userId;
        multiClassModal.showModal();
    }
</script>

<?= $this->endSection() ?>