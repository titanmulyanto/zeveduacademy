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

<!-- ==================== PAGE HEADER ==================== -->
<div class="mb-6 lg:mb-8">
    <h1 class="text-2xl lg:text-3xl font-bold text-slate-800">Profil Saya</h1>
    <p class="text-slate-500 mt-1">Kelola data diri dan pengaturan akun Anda</p>
</div>

<!-- ==================== PROFILE HEADER CARD ==================== -->
<div class="bg-white rounded-2xl border border-slate-200 p-6 mb-6">
    <div class="flex flex-col sm:flex-row items-center gap-6">
        <!-- Avatar -->
        <div class="relative">
            <div class="w-24 h-24 rounded-2xl overflow-hidden ring-4 ring-blue-100">
                <?php $avatarUrl = !empty($user['foto_profil']) && file_exists(FCPATH . $user['foto_profil'])
                    ? base_url($user['foto_profil'])
                    : 'https://ui-avatars.com/api/?name=' . urlencode($user['nama_lengkap'] ?? 'S') . '&background=2563EB&color=fff&size=128'; ?>
                <img src="<?= $avatarUrl ?>" alt="Profile" class="w-full h-full object-cover">
            </div>
            <?php if ($profileComplete): ?>
            <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-emerald-500 rounded-full flex items-center justify-center border-4 border-white">
                <i class="ph-fill ph-check text-white text-sm"></i>
            </div>
            <?php endif; ?>
        </div>

        <!-- Info -->
        <div class="flex-1 text-center sm:text-left">
            <h2 class="text-xl font-bold text-slate-800"><?= esc($user['nama_lengkap'] ?? 'Student') ?></h2>
            <p class="text-slate-500 mt-1"><?= esc($user['email'] ?? '') ?></p>
            <div class="flex flex-wrap justify-center sm:justify-start gap-2 mt-3">
                <?php if ($profileComplete): ?>
                <span class="px-3 py-1.5 bg-emerald-50 text-emerald-600 text-xs font-semibold rounded-lg flex items-center gap-1">
                    <i class="ph-fill ph-check-circle"></i> Profil Lengkap
                </span>
                <?php else: ?>
                <span class="px-3 py-1.5 bg-amber-50 text-amber-600 text-xs font-semibold rounded-lg flex items-center gap-1">
                    <i class="ph-fill ph-warning"></i> Lengkapi Profil
                </span>
                <?php endif; ?>
                <span class="px-3 py-1.5 bg-slate-100 text-slate-600 text-xs font-medium rounded-lg flex items-center gap-1">
                    <i class="ph ph-user"></i> Student
                </span>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="flex gap-6">
            <div class="text-center">
                <p class="text-2xl font-bold text-blue-600"><?= count($transaksi) ?></p>
                <p class="text-xs text-slate-500">Kelas</p>
            </div>
            <div class="text-center">
                <p class="text-2xl font-bold text-emerald-600"><?= count(array_filter($transaksi, fn($t) => $t['status_pembayaran'] === 'paid')) ?></p>
                <p class="text-xs text-slate-500">Aktif</p>
            </div>
        </div>
    </div>
</div>

<!-- ==================== MAIN CONTENT ==================== -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- Left: Profile Form -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
            <div class="p-5 border-b border-slate-200 bg-slate-50/50">
                <h3 class="font-bold text-slate-800 flex items-center gap-2">
                    <i class="ph-fill ph-user text-blue-600"></i>
                    Data Diri
                </h3>
            </div>

            <form action="<?= base_url('student/update-profile') ?>" method="POST" id="profileForm" enctype="multipart/form-data" class="p-6">
                <?= csrf_field() ?>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Nama Lengkap -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama_lengkap" value="<?= esc($user['nama_lengkap'] ?? '') ?>"
                               class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all"
                               placeholder="Masukkan nama lengkap" required />
                    </div>

                    <!-- Email (Readonly) -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" value="<?= esc($user['email'] ?? '') ?>"
                               class="w-full px-4 py-3 bg-slate-100 border border-slate-200 rounded-xl text-slate-500 cursor-not-allowed"
                               readonly />
                        <p class="text-xs text-slate-400 mt-1">Email tidak dapat diubah</p>
                    </div>

                    <!-- No. WhatsApp -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            No. WhatsApp
                        </label>
                        <input type="text" name="no_whatsapp" value="<?= esc($user['no_whatsapp'] ?? '') ?>"
                               class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all"
                               placeholder="08xxxxxxxxxx" />
                    </div>

                    <!-- Instansi -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Instansi
                        </label>
                        <input type="text" name="instansi" value="<?= esc($user['instansi'] ?? '') ?>"
                               class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all"
                               placeholder="Sekolah/Universitas/Kantor" />
                    </div>

                    <!-- Tanggal Lahir -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Tanggal Lahir
                        </label>
                        <input type="date" name="tanggal_lahir" value="<?= esc($user['tanggal_lahir'] ?? '') ?>"
                               class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all" />
                    </div>

                    <!-- Jenis Kelamin -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Jenis Kelamin
                        </label>
                        <select name="jenis_kelamin"
                                class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all">
                            <option value="">-- Pilih --</option>
                            <option value="L" <?= ($user['jenis_kelamin'] ?? '') === 'L' ? 'selected' : '' ?>>Laki-laki</option>
                            <option value="P" <?= ($user['jenis_kelamin'] ?? '') === 'P' ? 'selected' : '' ?>>Perempuan</option>
                        </select>
                    </div>

                    <!-- Pendidikan -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Pendidikan Terakhir
                        </label>
                        <select name="pendidikan_terakhir"
                                class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all">
                            <option value="">-- Pilih --</option>
                            <option value="SMA" <?= ($user['pendidikan_terakhir'] ?? '') === 'SMA' ? 'selected' : '' ?>>SMA/SMK</option>
                            <option value="D1" <?= ($user['pendidikan_terakhir'] ?? '') === 'D1' ? 'selected' : '' ?>>D1</option>
                            <option value="D2" <?= ($user['pendidikan_terakhir'] ?? '') === 'D2' ? 'selected' : '' ?>>D2</option>
                            <option value="D3" <?= ($user['pendidikan_terakhir'] ?? '') === 'D3' ? 'selected' : '' ?>>D3</option>
                            <option value="S1" <?= ($user['pendidikan_terakhir'] ?? '') === 'S1' ? 'selected' : '' ?>>S1</option>
                            <option value="S2" <?= ($user['pendidikan_terakhir'] ?? '') === 'S2' ? 'selected' : '' ?>>S2</option>
                            <option value="S3" <?= ($user['pendidikan_terakhir'] ?? '') === 'S3' ? 'selected' : '' ?>>S3</option>
                        </select>
                    </div>

                    <!-- Alamat -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Alamat
                        </label>
                        <textarea name="alamat" rows="3"
                                  class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all resize-none"
                                  placeholder="Masukkan alamat lengkap"><?= esc($user['alamat'] ?? '') ?></textarea>
                    </div>

                    <!-- Foto Profil -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Foto Profil
                        </label>
                        <div class="flex items-center gap-4">
                            <div class="w-20 h-20 rounded-xl overflow-hidden bg-slate-100 border-2 border-dashed border-slate-300">
                                <img src="<?= $avatarUrl ?>" alt="Current" class="w-full h-full object-cover">
                            </div>
                            <div class="flex-1">
                                <input type="file" name="foto_profil" id="foto_profil"
                                       class="hidden" accept="image/*" onchange="previewImage(this)" />
                                <label for="foto_profil"
                                       class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm font-medium text-slate-700 hover:bg-slate-50 hover:border-slate-300 cursor-pointer transition-all">
                                    <i class="ph ph-upload"></i>
                                    Pilih Foto
                                </label>
                                <p class="text-xs text-slate-400 mt-2">Format: JPG, PNG. Maksimal 2MB</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 pt-6 border-t border-slate-200 flex justify-end">
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-xl font-semibold hover:bg-blue-700 transition-colors shadow-lg shadow-blue-600/25">
                        <i class="ph-fill ph-check"></i>
                        <span>Simpan Perubahan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Right Sidebar -->
    <div class="space-y-6">

        <!-- Change Password -->
        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
            <div class="p-5 border-b border-slate-200 bg-slate-50/50">
                <h3 class="font-bold text-slate-800 flex items-center gap-2">
                    <i class="ph-fill ph-lock text-amber-500"></i>
                    Ganti Password
                </h3>
            </div>

            <form action="<?= base_url('student/change-password') ?>" method="POST" class="p-6">
                <?= csrf_field() ?>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Password Lama</label>
                        <input type="password" name="password_lama"
                               class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all"
                               placeholder="••••••••" required />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Password Baru</label>
                        <input type="password" name="password_baru"
                               class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all"
                               placeholder="Minimal 6 karakter" required />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Konfirmasi Password</label>
                        <input type="password" name="password_konfirmasi"
                               class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all"
                               placeholder="Ulangi password baru" required />
                    </div>
                </div>
                <button type="submit"
                        class="w-full mt-4 inline-flex items-center justify-center gap-2 px-4 py-3 bg-amber-500 text-white rounded-xl font-semibold hover:bg-amber-600 transition-colors">
                    <i class="ph-fill ph-key"></i>
                    <span>Ubah Password</span>
                </button>
            </form>
        </div>

        <!-- Logout -->
        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
            <div class="p-5 border-b border-slate-200 bg-red-50/50">
                <h3 class="font-bold text-red-600 flex items-center gap-2">
                    <i class="ph-fill ph-sign-out"></i>
                    Keluar Akun
                </h3>
            </div>
            <div class="p-6">
                <p class="text-sm text-slate-500 mb-4">Anda yakin ingin keluar dari akun ini?</p>
                <a href="<?= base_url('auth/logout') ?>"
                   class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-red-500 text-white rounded-xl font-semibold hover:bg-red-600 transition-colors">
                    <i class="ph-fill ph-sign-out"></i>
                    <span>Keluar</span>
                </a>
            </div>
        </div>

        <!-- Transaction History -->
        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
            <div class="p-5 border-b border-slate-200 bg-slate-50/50">
                <h3 class="font-bold text-slate-800 flex items-center gap-2">
                    <i class="ph-fill ph-receipt text-blue-600"></i>
                    Riwayat Transaksi
                </h3>
            </div>
            <div class="p-6">
                <?php if (empty($transaksi)): ?>
                <div class="text-center py-6">
                    <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-3">
                        <i class="ph ph-receipt text-xl text-slate-400"></i>
                    </div>
                    <p class="text-sm text-slate-500">Belum ada transaksi</p>
                </div>
                <?php else: ?>
                <div class="space-y-3 max-h-64 overflow-y-auto">
                    <?php foreach (array_slice($transaksi, 0, 5) as $trx): ?>
                    <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl">
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-slate-800 text-sm truncate"><?= esc($trx['nama_produk'] ?? 'Kelas') ?></p>
                            <p class="text-xs text-slate-500"><?= date('d M Y', strtotime($trx['tanggal_transaksi'] ?? date('Y-m-d'))) ?></p>
                        </div>
                        <?php if ($trx['status_pembayaran'] === 'paid'): ?>
                        <span class="px-2 py-1 bg-emerald-100 text-emerald-600 text-xs font-medium rounded-lg">Lunas</span>
                        <?php else: ?>
                        <span class="px-2 py-1 bg-amber-100 text-amber-600 text-xs font-medium rounded-lg"><?= ucfirst($trx['status_pembayaran']) ?></span>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php if (count($transaksi) > 5): ?>
                <p class="text-xs text-slate-400 text-center mt-3">+<?= count($transaksi) - 5 ?> transaksi lainnya</p>
                <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            input.closest('.flex').querySelector('img').src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<?= $this->endSection() ?>