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
        }
        h1, h2, h3, h4, h5, h6 {
            font-family: 'IBM Plex Sans', sans-serif;
        }
    </style>
</head>
<body class="bg-slate-50 min-h-screen">

    <div class="drawer lg:drawer-open">
        <input id="admin-drawer" type="checkbox" class="drawer-toggle" />

        <div class="drawer-content flex flex-col min-h-screen">
            <!-- Navbar -->
            <?= $this->include('admin/layouts/navbar') ?>

            <!-- Main Content -->
            <main class="flex-1 p-4 md:p-6 lg:p-8">
                <!-- Breadcrumbs -->
                <div class="breadcrumbs-modern mb-6">
                    <div class="breadcrumbs-modern-item">
                        <a href="<?= base_url('admin/dashboard') ?>">
                            <i class="ph ph-house"></i>
                            <span>Admin</span>
                        </a>
                    </div>
                    <span class="breadcrumbs-modern-separator">
                        <i class="ph ph-caret-right"></i>
                    </span>
                    <div class="breadcrumbs-modern-item">
                        <span class="breadcrumbs-modern-current"><?= $title ?? 'Dashboard' ?></span>
                    </div>
                </div>

                <?= $this->renderSection('content') ?>
            </main>

            <!-- Footer -->
            <footer class="footer footer-center p-4 bg-white border-t border-slate-200 text-slate-500 text-sm">
                <aside>
                    <p>Copyright © <?= date('Y') ?> - All right reserved by ZevedU Academy</p>
                </aside>
            </footer>
        </div>

        <!-- Sidebar -->
        <?= $this->include('admin/layouts/sidebar') ?>
    </div>

    <!-- Scripts with Cache Busting -->
    <script src="<?= base_url('assets/js/app.js?v=' . time()) ?>"></script>
    <?= $this->renderSection('scripts') ?>
</body>
</html>