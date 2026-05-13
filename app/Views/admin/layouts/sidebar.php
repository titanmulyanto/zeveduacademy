<div class="drawer-side z-40">
    <label for="admin-drawer" class="drawer-overlay"></label>
    <div class="menu p-4 w-72 min-h-full bg-neutral text-neutral-content flex flex-col">
        <!-- Logo Area -->
        <div class="flex items-center gap-3 px-4 py-6 mb-4">
            <div class="bg-primary p-2 rounded-lg">
                <i class="ph ph-graduation-cap text-2xl text-white"></i>
            </div>
            <div>
                <h1 class="text-xl font-bold tracking-tight text-white">ZevedU <span class="font-normal text-sky-400">Academy</span></h1>
                <p class="text-xs opacity-60">Admin Dashboard</p>
            </div>
        </div>

        <!-- Navigation Menu -->
        <ul class="flex-1 space-y-1">
            <li class="menu-title text-sky-400 opacity-60 mt-4">Main Menu</li>
            <li>
                <a href="<?= base_url('admin/dashboard') ?>" class="<?= url_is('admin/dashboard') ? 'active' : '' ?>">
                    <i class="ph ph-gauge text-xl"></i>
                    Dashboard
                </a>
            </li>
            
            <li>
                <a href="<?= base_url('admin/users') ?>" class="<?= url_is('admin/users*') ? 'active' : '' ?>">
                    <i class="ph ph-users text-xl"></i>
                    Manajemen User
                </a>
            </li>

            <li>
                <a href="<?= base_url('admin/transactions') ?>" class="<?= url_is('admin/transactions*') ? 'active' : '' ?>">
                    <i class="ph ph-hand-coins text-xl"></i>
                    Daftar Transaksi
                </a>
            </li>

            <li>
                <a href="<?= base_url('admin/materi') ?>" class="<?= url_is('admin/materi*') ? 'active' : '' ?>">
                    <i class="ph ph-books text-xl"></i>
                    Materi & Sertifikasi
                </a>
            </li>

            <li class="menu-title text-sky-400 opacity-60 mt-4">Platform Settings</li>
            <li>
                <details <?= url_is('admin/cms*') ? 'open' : '' ?>>
                    <summary><i class="ph ph-browser text-xl"></i> CMS Landing Page</summary>
                    <ul>
                        <li><a href="<?= base_url('admin/cms/slider') ?>" class="<?= url_is('admin/cms/slider') ? 'active' : '' ?>">Slider Banner</a></li>
                        <li><a href="<?= base_url('admin/cms/features') ?>" class="<?= url_is('admin/cms/features') ? 'active' : '' ?>">Fitur Unggulan</a></li>
                        <li><a href="<?= base_url('admin/cms/faq') ?>" class="<?= url_is('admin/cms/faq') ? 'active' : '' ?>">FAQ</a></li>
                        <li><a href="<?= base_url('admin/cms/testimoni') ?>" class="<?= url_is('admin/cms/testimoni') ? 'active' : '' ?>">Testimoni</a></li>
                    </ul>
                </details>
            </li>
            <li>
                <a href="#">
                    <i class="ph ph-certificate text-xl"></i>
                    Kelas & Sertifikat
                </a>
            </li>
        </ul>

        <!-- User Info / Role Badge -->
        <div class="mt-auto p-4 bg-black/20 rounded-xl">
            <div class="flex items-center gap-3">
                <div class="avatar online">
                    <div class="w-10 rounded-full ring ring-primary ring-offset-base-100 ring-offset-2">
                        <img src="https://ui-avatars.com/api/?name=Admin&background=0369A1&color=fff" />
                    </div>
                </div>
                <div>
                    <p class="font-bold text-sm text-white">Super Admin</p>
                    <p class="text-xs opacity-60">system_owner</p>
                </div>
            </div>
        </div>
    </div>
</div>
