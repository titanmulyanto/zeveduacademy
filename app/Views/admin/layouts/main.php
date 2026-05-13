<!DOCTYPE html>
<html lang="en" data-theme="zevedu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Dashboard' ?> | ZevedU Academy</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Compiled Tailwind + DaisyUI -->
    <link rel="stylesheet" href="<?= base_url('assets/css/output.css') ?>">
    
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>
<body class="bg-base-100">

    <div class="drawer lg:drawer-open">
        <input id="admin-drawer" type="checkbox" class="drawer-toggle" />
        
        <div class="drawer-content flex flex-col min-h-screen">
            <!-- Navbar -->
            <?= $this->include('admin/layouts/navbar') ?>
            
            <!-- Main Content -->
            <main class="flex-1 p-4 md:p-8">
                <!-- Breadcrumbs -->
                <div class="text-sm breadcrumbs mb-6">
                    <ul>
                        <li><a>Admin</a></li> 
                        <li><?= $title ?? 'Dashboard' ?></li>
                    </ul>
                </div>
                
                <?= $this->renderSection('content') ?>
            </main>
            
            <!-- Footer -->
            <footer class="footer footer-center p-4 bg-base-200 text-base-content border-t border-base-300">
                <aside>
                    <p>Copyright © <?= date('Y') ?> - All right reserved by ZevedU Academy</p>
                </aside>
            </footer>
        </div> 
        
        <!-- Sidebar -->
        <?= $this->include('admin/layouts/sidebar') ?>
    </div>

    <!-- Scripts -->
    <script src="<?= base_url('assets/js/app.js') ?>"></script>
    <?= $this->renderSection('scripts') ?>
</body>
</html>
