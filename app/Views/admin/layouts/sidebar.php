<div class="drawer-side z-40">
    <label for="admin-drawer" class="drawer-overlay"></label>

    <aside class="sidebar">
        <!-- Logo Area -->
        <div class="sidebar-header">
            <div class="sidebar-logo">
                <div class="sidebar-logo-icon">
                    <i class="ph-fill ph-graduation-cap"></i>
                </div>
                <div class="sidebar-logo-text">
                    <h1>ZevedU <span>Academy</span></h1>
                    <?php if(session()->get('role') == 'super_admin'): ?>
                        <p class="text-warning font-bold mt-1">Super Admin Panel</p>
                    <?php else: ?>
                        <p>Admin Panel</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Navigation Menu -->
        <nav class="sidebar-nav custom-scrollbar">
            <?php if(session()->get('role') == 'super_admin'): ?>
            <!-- SUPER ADMIN SECTION -->
            <div class="sidebar-section">
                <div class="sidebar-section-title">Super Admin</div>
                <ul class="sidebar-menu">
                    <li>
                        <a href="<?= base_url('admin/dashboard') ?>"
                           class="sidebar-nav-item <?= url_is('admin/dashboard') ? 'active' : '' ?>">
                            <i class="ph ph-gauge sidebar-nav-icon"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    <li>
                        <a href="<?= base_url('admin/users') ?>"
                           class="sidebar-nav-item <?= url_is('admin/users*') ? 'active' : '' ?>">
                            <i class="ph ph-users sidebar-nav-icon"></i>
                            <span>Manajemen User</span>
                        </a>
                    </li>

                    <li>
                        <a href="<?= base_url('admin/transactions') ?>"
                           class="sidebar-nav-item <?= url_is('admin/transactions*') ? 'active' : '' ?>">
                            <i class="ph ph-receipt sidebar-nav-icon"></i>
                            <span>Daftar Transaksi</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="sidebar-section">
                <div class="sidebar-section-title">Pengaturan</div>
                <ul class="sidebar-menu">
            <?php endif; ?>

            <!-- MATERI & TES - AKSES UNTUK ADMIN & SUPER ADMIN -->
            <li>
                <a href="<?= base_url('admin/materi') ?>"
                   class="sidebar-nav-item <?= url_is('admin/materi*') || url_is('admin/ujian*') ? 'active' : '' ?>">
                    <i class="ph ph-books sidebar-nav-icon"></i>
                    <span>Materi & Tes</span>
                </a>
            </li>

            <?php if(session()->get('role') == 'super_admin'): ?>
                </ul>
            </div>

            <!-- SUPER ADMIN ONLY -->
            <div class="sidebar-section">
                <div class="sidebar-section-title">Kelas & Sertifikat</div>
                <ul class="sidebar-menu">
                    <li>
                        <a href="<?= base_url('admin/sertifikat') ?>"
                           class="sidebar-nav-item <?= url_is('admin/sertifikat*') ? 'active' : '' ?>">
                            <i class="ph ph-certificate sidebar-nav-icon"></i>
                            <span>Kelola Sertifikat</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('admin/kategori-materi') ?>"
                           class="sidebar-nav-item <?= url_is('admin/kategori-materi*') ? 'active' : '' ?>">
                            <i class="ph ph-folder-open sidebar-nav-icon"></i>
                            <span>Kategori Materi</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- CMS SECTION -->
            <div class="sidebar-section">
                <div class="sidebar-section-title">Konten Landing</div>
                <ul class="sidebar-menu">
                    <li>
                        <details <?= url_is('admin/cms*') ? 'open' : '' ?>>
                            <summary class="sidebar-nav-item cursor-pointer">
                                <i class="ph ph-layout sidebar-nav-icon"></i>
                                <span>CMS Landing Page</span>
                                <i class="ph ph-caret-down ml-auto text-xs"></i>
                            </summary>
                            <ul class="sidebar-submenu">
                                <li>
                                    <a href="<?= base_url('admin/cms/slider') ?>"
                                       class="<?= url_is('admin/cms/slider') ? 'active' : '' ?>">
                                        <i class="ph ph-image text-sm"></i>
                                        Slider Banner
                                    </a>
                                </li>
                                <li>
                                    <a href="<?= base_url('admin/cms/features') ?>"
                                       class="<?= url_is('admin/cms/features') ? 'active' : '' ?>">
                                        <i class="ph ph-star text-sm"></i>
                                        Fitur Unggulan
                                    </a>
                                </li>
                                <li>
                                    <a href="<?= base_url('admin/cms/faq') ?>"
                                       class="<?= url_is('admin/cms/faq') ? 'active' : '' ?>">
                                        <i class="ph ph-question text-sm"></i>
                                        FAQ
                                    </a>
                                </li>
                                <li>
                                    <a href="<?= base_url('admin/cms/testimoni') ?>"
                                       class="<?= url_is('admin/cms/testimoni') ? 'active' : '' ?>">
                                        <i class="ph ph-chat-circle-text text-sm"></i>
                                        Testimoni
                                    </a>
                                </li>
                            </ul>
                        </details>
                    </li>
                </ul>
            </div>
            <?php endif; ?>
        </nav>

        <!-- User Info / Role Badge -->
        <div class="sidebar-footer">
            <div class="sidebar-user-card">
                <div class="sidebar-user-avatar">
                    <?= strtoupper(substr(session()->get('nama_lengkap') ?? 'A', 0, 1)) ?>
                </div>
                <div class="sidebar-user-info">
                    <p class="sidebar-user-name"><?= esc(session()->get('nama_lengkap')) ?></p>
                    <p class="sidebar-user-role">
                        <?php
                        $role = session()->get('role');
                        $roleLabels = [
                            'super_admin' => 'Super Admin',
                            'admin' => 'Admin',
                            'student' => 'Student'
                        ];
                        echo $roleLabels[$role] ?? ucfirst($role);
                        ?>
                    </p>
                </div>
                <a href="<?= base_url('auth/logout') ?>"
                   class="sidebar-user-logout"
                   title="Keluar"
                   onclick="return confirm('Apakah Anda yakin ingin keluar?')">
                    <i class="ph ph-sign-out text-lg"></i>
                </a>
            </div>
        </div>
    </aside>
</div>