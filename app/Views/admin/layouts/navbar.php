<nav class="navbar bg-white border-b border-slate-200 sticky top-0 z-30">
    <div class="flex-none lg:hidden">
        <label for="admin-drawer" class="btn btn-ghost btn-square hover:bg-slate-100">
            <i class="ph ph-list text-2xl text-slate-600"></i>
        </label>
    </div>

    <div class="flex-1 px-4">
        <!-- Page Title (Mobile) -->
        <span class="text-xl font-bold lg:hidden" style="font-family: 'IBM Plex Sans', sans-serif;">ZevedU</span>
    </div>

    <div class="flex-none flex items-center gap-3 pr-4">
        <!-- Quick Search -->
        <button class="btn btn-ghost hover:bg-slate-100 text-slate-600 hidden md:flex items-center gap-2">
            <i class="ph ph-magnifying-glass text-lg"></i>
            <span class="text-sm">Search...</span>
            <kbd class="kbd kbd-sm hidden lg:inline">⌘K</kbd>
        </button>

        <!-- Notifications -->
        <div class="dropdown dropdown-end">
            <button class="btn btn-ghost btn-square hover:bg-slate-100 relative">
                <i class="ph ph-bell text-xl text-slate-600"></i>
                <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
            </button>
            <div tabindex="0" class="dropdown-content z-[1] card card-compact w-72 bg-white shadow-lg border border-slate-200 rounded-2xl mt-2">
                <div class="card-body p-4">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-semibold text-slate-900">Notifikasi</h3>
                        <button class="text-xs text-primary hover:underline">Tandai semua dibaca</button>
                    </div>
                    <div class="space-y-3">
<div class="flex items-start gap-3 p-2 rounded-xl hover:bg-slate-50 transition-colors cursor-pointer">
                            <div class="w-10 h-10 rounded-xl bg-success-light flex items-center justify-center flex-shrink-0">
                                <i class="ph-fill ph-check-circle text-success"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-slate-800">Transaksi baru berhasil</p>
                                <p class="text-xs text-slate-500">John Doe membeli kelas PHP</p>
                                <p class="text-xs text-slate-400 mt-1">2 menit yang lalu</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3 p-2 rounded-xl hover:bg-slate-50 transition-colors cursor-pointer">
                            <div class="w-10 h-10 rounded-xl bg-info-light flex items-center justify-center flex-shrink-0">
                                <i class="ph-fill ph-user-plus text-info"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-slate-800">User baru terdaftar</p>
                                <p class="text-xs text-slate-500">Jane Smith bergabung</p>
                                <p class="text-xs text-slate-400 mt-1">15 menit yang lalu</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-actions mt-4 pt-4 border-t border-slate-100">
                        <a href="#" class="btn btn-primary btn-sm w-full">Lihat Semua</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Profile -->
        <div class="dropdown dropdown-end">
            <label tabindex="0" class="flex items-center gap-3 cursor-pointer hover:bg-slate-50 rounded-xl p-2 transition-colors">
                <div class="avatar online">
                    <div class="w-10 h-10 rounded-xl">
                        <img src="https://ui-avatars.com/api/?name=<?= urlencode((string)session()->get('nama_lengkap')) ?>&background=0C5CAB&color=fff"
                             alt="<?= esc((string)session()->get('nama_lengkap')) ?>" />
                    </div>
                </div>
                <div class="hidden md:block text-right">
                    <p class="text-sm font-semibold text-slate-800"><?= esc((string)session()->get('nama_lengkap')) ?></p>
                    <p class="text-xs text-slate-500">
                        <?php
                        $role = session()->get('role');
                        $roleLabels = [
                            'super_admin' => 'Super Admin',
                            'admin' => 'Admin',
                            'student' => 'Student'
                        ];
                        echo $roleLabels[$role] ?? ucfirst((string)$role);
                        ?>
                    </p>
                </div>
                <i class="ph ph-caret-down text-slate-400 hidden md:block"></i>
            </label>
            <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow-lg bg-white border border-slate-200 rounded-2xl w-56 mt-2">
                <li class="px-4 py-3 border-b border-slate-100">
                    <p class="font-semibold text-slate-800"><?= esc((string)session()->get('nama_lengkap')) ?></p>
                    <p class="text-xs text-slate-500"><?= esc((string)session()->get('email')) ?></p>
                </li>
                <li>
                    <a href="<?= base_url('admin/profile') ?>" class="flex items-center gap-3 px-4 py-2.5 rounded-xl hover:bg-slate-50 text-slate-600">
                        <i class="ph ph-user text-lg"></i>
                        Profil Saya
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('auth/logout') ?>" class="flex items-center gap-3 px-4 py-2.5 rounded-xl hover:bg-red-50 text-red-600">
                        <i class="ph ph-sign-out text-lg"></i>
                        Keluar
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>