<!DOCTYPE html>
<html lang="id" data-theme="zevedu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Dashboard' ?> | ZevedU Academy</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Compiled Tailwind + DaisyUI with Cache Busting -->
    <link rel="stylesheet" href="<?= base_url('assets/css/output.css?v=' . time()) ?>">

    <!-- Custom Design System with Cache Busting -->
    <link rel="stylesheet" href="<?= base_url('assets/css/design-system.css?v=' . time()) ?>">

    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--color-slate-50);
        }
        h1, h2, h3, h4, h5, h6 {
            font-family: 'IBM Plex Sans', sans-serif;
        }
    </style>
</head>
<body class="min-h-screen">

    <!-- ==================== MOBILE HEADER ==================== -->
    <header class="lg:hidden sticky top-0 z-40 bg-white border-b border-slate-200 shadow-sm">
        <div class="flex items-center justify-between px-4 h-16">
            <!-- Logo -->
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-600 to-blue-700 flex items-center justify-center shadow-md shadow-blue-600/20">
                    <i class="ph-fill ph-graduation-cap text-white text-lg"></i>
                </div>
                <div>
                    <h1 class="text-base font-bold text-slate-800">ZevedU</h1>
                    <p class="text-[10px] text-blue-600 font-medium tracking-wide">ACADEMY</p>
                </div>
            </div>

            <!-- User Avatar -->
            <div class="flex items-center gap-3">
                <div class="dropdown dropdown-end">
                    <label tabindex="0" class="btn btn-ghost btn-circle">
                        <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-blue-600 to-emerald-500 flex items-center justify-center text-white font-bold text-sm shadow-md">
                            <?= strtoupper(substr(session()->get('nama_lengkap') ?? 'S', 0, 1)) ?>
                        </div>
                    </label>
                    <ul tabindex="0" class="mt-3 z-[100] p-2 shadow-xl border border-slate-100 rounded-2xl w-56 bg-white">
                        <li class="px-4 py-3 border-b border-slate-100">
                            <p class="font-semibold text-slate-800"><?= esc(session()->get('nama_lengkap') ?? 'Student') ?></p>
                            <p class="text-xs text-slate-500"><?= esc(session()->get('email') ?? '') ?></p>
                        </li>
                        <li><a href="<?= base_url('student/profile') ?>" class="flex items-center gap-3 px-4 py-2.5 rounded-xl hover:bg-slate-50 text-slate-600"><i class="ph ph-user text-lg"></i> Profil Saya</a></li>
                        <li><a href="<?= base_url('auth/logout') ?>" class="flex items-center gap-3 px-4 py-2.5 rounded-xl hover:bg-red-50 text-red-600"><i class="ph ph-sign-out text-lg"></i> Keluar</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </header>

    <!-- ==================== DESKTOP LAYOUT ==================== -->
    <div class="flex min-h-screen">

        <!-- ==================== SIDEBAR (Desktop Only) ==================== -->
        <aside class="hidden lg:flex flex-col w-72 bg-white border-r border-slate-200 min-h-screen fixed left-0 top-0 bottom-0 shadow-sm z-30">

            <!-- Logo Section -->
            <div class="p-6 border-b border-slate-100">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-600 to-blue-700 flex items-center justify-center shadow-lg shadow-blue-600/20">
                        <i class="ph-fill ph-graduation-cap text-white text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-slate-800">ZevedU</h1>
                        <p class="text-xs text-blue-600 font-semibold tracking-wider">ACADEMY</p>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 p-4 overflow-y-auto custom-scrollbar">
                <p class="px-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-4">Menu Utama</p>

                <div class="space-y-1">
                    <!-- Dashboard -->
                    <a href="<?= base_url('student') ?>"
                       class="sidebar-nav-item <?= (current_url() == base_url('student')) ? 'active' : '' ?>">
                        <i class="ph-fill ph-house sidebar-nav-icon"></i>
                        <span>Dashboard</span>
                    </a>

                    <!-- My Classes -->
                    <a href="<?= base_url('student/kelas') ?>"
                       class="sidebar-nav-item <?= (strpos(current_url(), 'student/kelas') !== false && strpos(current_url(), 'student/kelas/') === false) ? 'active' : '' ?>">
                        <i class="ph-fill ph-books sidebar-nav-icon"></i>
                        <span>Kelas Saya</span>
                    </a>

                    <!-- Profile -->
                    <a href="<?= base_url('student/profile') ?>"
                       class="sidebar-nav-item <?= (strpos(current_url(), 'profile') !== false) ? 'active' : '' ?>">
                        <i class="ph-fill ph-user sidebar-nav-icon"></i>
                        <span>Profil Saya</span>
                    </a>

                    <!-- Sertifikat -->
                    <a href="<?= base_url('student/sertifikat') ?>"
                       class="sidebar-nav-item <?= (strpos(current_url(), 'sertifikat') !== false) ? 'active' : '' ?>">
                        <i class="ph-fill ph-certificate sidebar-nav-icon"></i>
                        <span>Sertifikat</span>
                    </a>
                </div>
            </nav>

            <!-- User Profile Card -->
            <div class="p-4 border-t border-slate-100">
                <div class="sidebar-user-card">
                    <div class="sidebar-user-avatar">
                        <?= strtoupper(substr(session()->get('nama_lengkap') ?? 'S', 0, 1)) ?>
                    </div>
                    <div class="sidebar-user-info">
                        <p class="sidebar-user-name"><?= esc(session()->get('nama_lengkap') ?? 'Student') ?></p>
                        <p class="sidebar-user-role">Student</p>
                    </div>
                    <a href="<?= base_url('auth/logout') ?>"
                       class="sidebar-user-logout"
                       title="Keluar">
                        <i class="ph ph-sign-out text-lg"></i>
                    </a>
                </div>
            </div>
        </aside>

        <!-- ==================== MAIN CONTENT ==================== -->
        <main class="flex-1 lg:ml-72">
            <!-- Desktop Header -->
            <header class="hidden lg:flex items-center justify-between px-8 py-5 bg-white border-b border-slate-200">
                <div>
                    <h2 class="text-lg font-semibold text-slate-800">
                        <?php
                        $uri = service('uri')->getSegment(2);
                        $pageTitle = match($uri) {
                            '' => 'Dashboard',
                            'profile' => 'Profil Saya',
                            'sertifikat' => 'Sertifikat',
                            'kelas' => 'Kelas Saya',
                            default => 'Dashboard'
                        };
                        echo $pageTitle;
                        ?>
                    </h2>
                    <p class="text-sm text-slate-500"><?= date('l, d F Y') ?></p>
                </div>
                <div class="flex items-center gap-4">
                    <div class="text-right">
                        <p class="text-sm font-medium text-slate-700"><?= esc(session()->get('nama_lengkap') ?? 'Student') ?></p>
                        <p class="text-xs text-slate-500">Student</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-600 to-emerald-500 flex items-center justify-center text-white font-bold shadow-md">
                        <?= strtoupper(substr(session()->get('nama_lengkap') ?? 'S', 0, 1)) ?>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <div class="p-4 lg:p-8 pb-24 lg:pb-8">
                <?= $this->renderSection('content') ?>
            </div>
        </main>
    </div>

    <!-- ==================== MOBILE BOTTOM NAVIGATION ==================== -->
    <nav class="mobile-bottom-nav lg:hidden">
        <div class="flex items-center justify-around py-2">
            <a href="<?= base_url('student') ?>"
               class="flex flex-col items-center gap-1 px-4 py-2 rounded-xl transition-colors <?= (current_url() == base_url('student')) ? 'text-blue-600' : 'text-slate-400' ?>">
                <i class="ph-fill ph-house text-xl"></i>
                <span class="text-[10px] font-medium">Home</span>
            </a>

            <a href="<?= base_url('student/kelas') ?>"
               class="flex flex-col items-center gap-1 px-4 py-2 rounded-xl transition-colors <?= (strpos(current_url(), 'student/kelas') !== false) ? 'text-blue-600' : 'text-slate-400' ?>">
                <i class="ph-fill ph-books text-xl"></i>
                <span class="text-[10px] font-medium">Kelas</span>
            </a>

            <a href="<?= base_url('student/profile') ?>"
               class="flex flex-col items-center gap-1 px-4 py-2 rounded-xl transition-colors <?= (strpos(current_url(), 'profile') !== false) ? 'text-blue-600' : 'text-slate-400' ?>">
                <i class="ph-fill ph-user text-xl"></i>
                <span class="text-[10px] font-medium">Profil</span>
            </a>

            <a href="<?= base_url('student/sertifikat') ?>"
               class="flex flex-col items-center gap-1 px-4 py-2 rounded-xl transition-colors <?= (strpos(current_url(), 'sertifikat') !== false) ? 'text-blue-600' : 'text-slate-400' ?>">
                <i class="ph-fill ph-certificate text-xl"></i>
                <span class="text-[10px] font-medium">Sertifikat</span>
            </a>
        </div>
    </nav>

    <!-- Scripts with Cache Busting -->
    <script src="<?= base_url('assets/js/app.js?v=' . time()) ?>"></script>
    <?= $this->renderSection('scripts') ?>
</body>
</html>