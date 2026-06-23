<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- ==================== BACKDROP FOR MOBILE MENU ==================== -->
<div id="backdrop" class="fixed inset-0 bg-black/50 z-[998] hidden" onclick="toggleMenu()"></div>

<!-- ==================== MOBILE MENU ==================== -->
<div id="mobile-menu" class="fixed top-0 right-0 h-full w-80 bg-white z-[999] shadow-2xl p-8 overflow-y-auto flex flex-col">
    <button onclick="toggleMenu()" class="mb-8 p-2 hover:bg-gray-100 rounded-full transition-colors self-end">
        <i class="ph ph-x text-3xl"></i>
    </button>

    <ul class="space-y-6 flex-grow">
        <li><a href="#fitur" class="block text-lg font-semibold hover:text-primary transition-colors">Fitur Zevedu</a></li>
        <li><a href="#program" class="block text-lg font-semibold hover:text-primary transition-colors">Program Pilihan</a></li>
        <li><a href="#faq" class="block text-lg font-semibold hover:text-primary transition-colors">Pusat Bantuan/FAQ</a></li>
    </ul>

    <div class="mt-10 pt-6 border-t border-gray-100">
        <img src="<?= base_url('assets/images/zevedulogo.png') ?>" alt="Zevedu Logo" class="h-12 w-auto opacity-80">
    </div>
</div>

<!-- ==================== MAIN CONTENT ==================== -->
<div class="relative flex h-auto min-h-screen w-full flex-col overflow-x-hidden">

    <!-- ==================== HEADER ==================== -->
    <header class="flex items-center justify-between whitespace-nowrap border-b border-solid border-[#e7ebf3] px-6 md:px-20 lg:px-40 py-4 bg-white">
        <div class="flex items-center gap-8 lg:gap-12">
            <!-- LOGO with 7:4 aspect ratio -->
            <a href="<?= base_url('/') ?>">
                <img src="<?= base_url('assets/images/zevedulogo.png') ?>"
                     alt="Logo Zevedu"
                     class="h-12 w-auto object-contain"
                     style="aspect-ratio: 7/4;">
            </a>

            <nav class="hidden md:flex items-center gap-6 lg:gap-10 text-sm font-bold text-[#0d121b]">
                <!-- Dropdown Temukan Kelas - Dynamic from kategori_produk -->
                <div class="relative group cursor-pointer">
                    <div class="flex items-center gap-1 hover:text-primary transition-colors">
                        <span>Temukan Kelas</span>
                        <i class="ph ph-caret-down text-[18px]"></i>
                    </div>
                    <div class="absolute left-0 top-full mt-2 w-56 bg-white border border-gray-100 rounded-xl shadow-xl hidden group-hover:block z-50 p-2">
                        <?php if (!empty($kategori)): ?>
                            <?php foreach ($kategori as $kat): ?>
                                <a href="<?= base_url('/#program?kategori=' . $kat['id_kategori']) ?>"
                                   class="block px-4 py-3 hover:bg-primary/5 rounded-lg text-sm font-medium transition-colors">
                                    <?= esc($kat['nama_kategori']) ?>
                                </a>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <span class="block px-4 py-3 text-sm text-gray-400">Belum ada kategori</span>
                        <?php endif; ?>
                    </div>
                </div>
                <a href="#program" class="hover:text-primary transition-colors">Temukan Sertifikat</a>
            </nav>
        </div>

        <div class="flex items-center gap-2">
            <a href="<?= base_url('/register') ?>"
               class="hidden sm:flex min-w-[100px] cursor-pointer items-center justify-center rounded-xl h-10 px-4 bg-primary text-white text-sm font-bold transition-all hover:bg-primary/90">
                Daftar
            </a>
            <a href="<?= base_url('/login') ?>"
               class="hidden sm:flex min-w-[84px] cursor-pointer items-center justify-center rounded-xl h-10 px-4 bg-[#e7ebf3] text-[#0d121b] text-sm font-bold transition-all hover:bg-gray-200">
                Masuk
            </a>

            <button onclick="toggleMenu()" class="ml-2 p-2 rounded-xl border border-slate-200 hover:border-primary hover:text-primary transition-all flex items-center justify-center">
                <i class="ph ph-list text-2xl"></i>
            </button>
        </div>
    </header>

    <main class="flex-1">

        <!-- ==================== HERO SECTION ==================== -->
        <section class="flex justify-center py-10 lg:py-20 px-6 md:px-20 lg:px-40 bg-white">
            <div class="max-w-[1200px] w-full">
                <div class="flex flex-col gap-12 lg:flex-row lg:items-center">
                    <div class="flex flex-col gap-8 flex-1">
                        <div class="flex flex-col gap-4 text-left">
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary/10 text-primary text-xs font-bold uppercase tracking-wider w-fit">
                                <i class="ph ph-sparkle !text-[14px]"></i> MARI BERSAMA ZEVEDU ACADEMY !!
                            </div>
                            <h1 class="text-[#0d121b] text-5xl font-black leading-tight tracking-[-0.033em] md:text-7xl">
                                Belajar, <span class="text-primary">Berkembang</span>, Berkarier
                            </h1>
                            <p class="text-[#4c669a] text-lg font-normal leading-relaxed max-w-[540px]">
                                Akses pendidikan berkualitas tinggi untuk mengakselerasi karier masa depanmu bersama para mentor ahli dari industri terkemuka.
                            </p>
                        </div>

                        <div class="flex flex-wrap gap-4">
                            <a href="<?= base_url('/#program') ?>"
                               class="flex min-w-[200px] cursor-pointer items-center justify-center rounded-xl h-14 px-8 bg-primary text-white text-base font-bold shadow-lg shadow-primary/30 hover:scale-105 transition-transform">
                                Coba kelas gratis
                            </a>
                            <a href="<?= base_url('/#faq') ?>"
                               class="flex min-w-[200px] cursor-pointer items-center justify-center rounded-xl h-14 px-8 border-2 border-primary/20 bg-transparent text-primary text-base font-bold hover:bg-primary/5 transition-colors">
                                Kontak Kami
                            </a>
                        </div>

                        <!-- Alumni Profile Pictures - Updated with local alumni images -->
                        <div class="flex items-center gap-4 pt-2">
                            <div class="flex -space-x-3">
                                <div class="w-10 h-10 rounded-full border-2 border-white bg-cover bg-center bg-[#cbd5e1]" style='background-image: url("<?= base_url('uploads/cms/alumni/alumni1.png') ?>");'></div>
                                <div class="w-10 h-10 rounded-full border-2 border-white bg-cover bg-center bg-[#cbd5e1]" style='background-image: url("<?= base_url('uploads/cms/alumni/alumni2.png') ?>");'></div>
                                <div class="w-10 h-10 rounded-full border-2 border-white bg-cover bg-center bg-[#cbd5e1]" style='background-image: url("<?= base_url('uploads/cms/alumni/alumni3.png') ?>");'></div>
                                <div class="w-10 h-10 rounded-full border-2 border-white bg-cover bg-center bg-[#cbd5e1]" style='background-image: url("<?= base_url('uploads/cms/alumni/alumni4.png') ?>");'></div>
                                <div class="w-10 h-10 rounded-full border-2 border-white bg-primary flex items-center justify-center text-white text-[10px] font-bold shadow-sm shadow-primary/30">+500k</div>
                            </div>
                            <div class="text-sm text-[#4c669a] font-medium">
                                Telah dipercaya oleh 500+ alumni zevedu
                            </div>
                        </div>
                    </div>

                    <!-- Hero Banner Slider (1:1 aspect ratio) - All images will match this size -->
                    <?php if (!empty($sliders)): ?>
                    <div class="flex-1">
                        <div class="relative w-full max-w-lg mx-auto">
                            <!-- Arrow Left - Selalu Visible -->
                            <button onclick="moveSlider(-1)"
                                    class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-1/2 w-12 h-12 rounded-full bg-white border-2 border-primary shadow-xl flex items-center justify-center hover:bg-primary hover:text-white hover:border-primary transition-all z-20">
                                <i class="ph ph-caret-left !text-2xl text-primary"></i>
                            </button>

                            <!-- Arrow Right - Selalu Visible -->
                            <button onclick="moveSlider(1)"
                                    class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-1/2 w-12 h-12 rounded-full bg-white border-2 border-primary shadow-xl flex items-center justify-center hover:bg-primary hover:text-white hover:border-primary transition-all z-20">
                                <i class="ph ph-caret-right !text-2xl text-primary"></i>
                            </button>

                            <!-- Slider Container with fixed aspect ratio 1:1 -->
                            <div class="relative overflow-hidden rounded-3xl shadow-2xl bg-white border border-slate-100" style="aspect-ratio: 1/1;">
                                <div id="hero-slider" class="flex transition-transform duration-500 ease-out h-full">
                                    <?php foreach ($sliders as $index => $slider): ?>
                                    <div class="w-full flex-shrink-0 h-full flex items-center justify-center p-6 md:p-8">
                                        <img src="<?= base_url('uploads/' . $slider['gambar']) ?>"
                                             alt="Banner <?= $index + 1 ?>"
                                             class="w-full h-full object-cover rounded-2xl"
                                             style="aspect-ratio: 1/1;"
                                             onerror="this.onerror=null; this.src='<?= base_url('assets/images/placeholder.png') ?>';">
                                    </div>
                                    <?php endforeach; ?>
                                </div>

                                <!-- Pagination Dots - Inside Image Bottom -->
                                <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2 z-20">
                                    <?php foreach ($sliders as $index => $slider): ?>
                                    <button onclick="goToSlide(<?= $index ?>)"
                                            class="slider-dot w-3 h-3 rounded-full transition-all <?= $index === 0 ? 'bg-primary w-6 shadow-md' : 'bg-slate-300 hover:bg-primary' ?>">
                                    </button>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="flex-1">
                        <div class="w-full aspect-square bg-gradient-to-br from-primary/10 to-primary/5 rounded-3xl relative overflow-hidden flex items-center justify-center p-8">
                            <div class="w-full h-full bg-center bg-no-repeat bg-contain rounded-xl relative z-10" style='background-image: url("https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=600&h=600&fit=crop");'></div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <!-- ==================== WHY CHOOSE US ==================== -->
        <section id="fitur" class="py-24 px-6 md:px-20 lg:px-10 bg-[#f8fafc] border-t border-slate-100 font-inter">
            <div class="max-w-[1200px] mx-auto">
                <div class="text-center mb-16 max-w-2xl mx-auto">
                    <h2 class="text-3xl md:text-4xl font-extrabold text-[#0d121b] mb-4 tracking-tight">
                        Mengapa Memilih <span class="text-primary">Zevedu Academy?</span>
                    </h2>
                    <p class="text-lg text-[#4c669a]">Ayo bersama kami belajar langsung dari ahlinya yang sudah berpengalaman di bidangnya</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="bg-white p-5 pt-14 rounded-[1.5rem] shadow-lg shadow-slate-200/50 border border-slate-50 transition-all duration-300 hover:-translate-y-2 text-center relative flex flex-col">
                        <div class="absolute -top-8 left-1/2 -translate-x-1/2 w-14 h-14 bg-accent-purple rounded-full flex items-center justify-center shadow-lg shadow-accent-purple/20">
                            <i class="ph ph-devices text-white text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800 mb-2 leading-tight">Belajar Kapan Saja</h3>
                        <p class="text-xs text-[#4c669a] leading-relaxed">Akses semua materi melalui perangkat apa pun, desktop maupun mobile.</p>
                    </div>
                    <div class="bg-white p-5 pt-14 rounded-[1.5rem] shadow-lg shadow-slate-200/50 border border-slate-50 transition-all duration-300 hover:-translate-y-2 text-center relative flex flex-col">
                        <div class="absolute -top-8 left-1/2 -translate-x-1/2 w-14 h-14 bg-accent-cyan rounded-full flex items-center justify-center shadow-lg shadow-accent-cyan/20">
                            <i class="ph ph-chart-line-up text-white text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800 mb-2 leading-tight">Kurikulum Terstruktur</h3>
                        <p class="text-xs text-[#4c669a] leading-relaxed">Materi disusun secara logis dari dasar hingga tingkat lanjut untuk Anda.</p>
                    </div>
                    <div class="bg-white p-5 pt-14 rounded-[1.5rem] shadow-lg shadow-slate-200/50 border border-slate-50 transition-all duration-300 hover:-translate-y-2 text-center relative flex flex-col">
                        <div class="absolute -top-8 left-1/2 -translate-x-1/2 w-14 h-14 bg-emerald-500 rounded-full flex items-center justify-center shadow-lg shadow-emerald-500/20">
                            <i class="ph ph-exam text-white text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800 mb-2 leading-tight">Validasi Skill</h3>
                        <p class="text-xs text-[#4c669a] leading-relaxed">Uji pemahaman Anda dengan tes berkala yang mengukur perkembangan.</p>
                    </div>
                    <div class="bg-white p-5 pt-14 rounded-[1.5rem] shadow-lg shadow-slate-200/50 border border-slate-50 transition-all duration-300 hover:-translate-y-2 text-center relative flex flex-col">
                        <div class="absolute -top-8 left-1/2 -translate-x-1/2 w-14 h-14 bg-orange-500 rounded-full flex items-center justify-center shadow-lg shadow-orange-500/20">
                            <i class="ph ph-arrows-clockwise text-white text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800 mb-2 leading-tight">Kurikulum Terkini</h3>
                        <p class="text-xs text-[#4c669a] leading-relaxed">Materi selalu diperbarui sesuai tren teknologi dan industri terbaru.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- ==================== PROGRAM PILIHAN (DYNAMIC PRODUK) ==================== -->
        <section id="program" class="py-20 px-6 md:px-20 lg:px-40 bg-white overflow-hidden">
            <div class="max-w-[1200px] mx-auto relative">

                <div class="mb-6">
                    <h2 class="text-2xl md:text-3xl font-extrabold text-[#0d121b] tracking-tight">
                        Program Pilihan <span class="text-primary">Terpopuler</span>
                    </h2>
                </div>

                <!-- Kategori Navigation Pills - Dynamic from kategori_produk -->
                <div class="relative flex items-center justify-between mb-10">
                    <div id="category-scroll" class="flex items-center gap-8 overflow-x-auto no-scrollbar w-full scroll-smooth">
                        <button onclick="filterProduk(null)" class="nav-category-b active" data-kategori="all">
                            Kelas Unggulan
                        </button>
                        <?php if (!empty($kategori)): ?>
                            <?php foreach ($kategori as $kat): ?>
                                <button onclick="filterProduk(<?= $kat['id_kategori'] ?>)"
                                        class="nav-category-b"
                                        data-kategori="<?= $kat['id_kategori'] ?>">
                                    <?= esc($kat['nama_kategori']) ?>
                                </button>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Kategori Deskripsi with Inline Expansion - Clean In-Text Style -->
                <div id="kategori-deskripsi" class="mb-6 hidden">
                    <div class="font-sans text-sm text-slate-600">
                        <p>
                            <span id="kategori-text-visible" class="inline kategori-text-visible"></span>
                            <span id="kategori-text-more" class="inline text-slate-400 overflow-hidden max-h-0 opacity-0 transition-all duration-300 ease-in-out kategori-text-more"></span>
                            <button type="button"
                                    id="kategori-deskripsi-toggle"
                                    class="text-[#1152d4] font-bold ml-1 inline-block hover:underline focus:outline-none kategori-toggle hidden"
                                    onclick="toggleKategoriDeskripsi(this)">
                                Baca Selengkapnya
                            </button>
                        </p>
                    </div>
                </div>

                <!-- Promo Cards Carousel - Dynamic from produk_pelatihan -->
                <div class="relative group/carousel px-2">

                    <button onclick="scrollCards('left')" class="absolute -left-4 top-1/2 -translate-y-1/2 z-20 w-12 h-12 rounded-full bg-white/90 backdrop-blur-sm text-slate-800 border border-slate-200 shadow-xl flex items-center justify-center opacity-0 group-hover/carousel:opacity-100 transition-all duration-300 hover:bg-primary hover:text-white">
                        <i class="ph ph-caret-left !text-[24px]"></i>
                    </button>

                    <button onclick="scrollCards('right')" class="absolute -right-4 top-1/2 -translate-y-1/2 z-20 w-12 h-12 rounded-full bg-white/90 backdrop-blur-sm text-slate-800 border border-slate-200 shadow-xl flex items-center justify-center opacity-100 lg:opacity-70 group-hover/carousel:opacity-100 transition-all duration-300 hover:bg-primary hover:text-white">
                        <i class="ph ph-caret-right !text-[24px]"></i>
                    </button>

                    <div id="card-carousel" class="flex gap-8 overflow-x-auto no-scrollbar pb-6 scroll-smooth snap-x snap-mandatory">
                        <?php if (!empty($produk)): ?>
                            <?php foreach ($produk as $p): ?>
                                <div class="promo-card snap-start flex-shrink-0" data-kategori="<?= $p['id_kategori'] ?>" style="min-width: 320px; max-width: 320px;">
                                    <!-- Discount Badge -->
                                    <?php if ($p['diskon_persen'] > 0): ?>
                                        <span class="discount-badge">-<?= $p['diskon_persen'] ?>%</span>
                                    <?php endif; ?>

                                    <!-- Product Image with 16:9 aspect ratio -->
                                    <div class="promo-img-container" style="aspect-ratio: 16/9; overflow: hidden;">
                                        <?php if (!empty($p['gambar'])): ?>
                                            <img src="<?= base_url('uploads/' . $p['gambar']) ?>"
                                                 alt="<?= esc($p['judul']) ?>"
                                                 class="promo-img-16-9"/>
                                        <?php else: ?>
                                            <img src="https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=800&auto=format&fit=crop"
                                                 alt="<?= esc($p['judul']) ?>"
                                                 class="promo-img-16-9"/>
                                        <?php endif; ?>
                                    </div>

                                    <div class="promo-content flex flex-col flex-grow">
                                        <!-- Product Title -->
                                        <h3 class="font-bold text-lg text-[#0d121b] mb-3 leading-tight"><?= esc($p['judul']) ?></h3>

                                        <!-- Description with Inline Expansion -->
                                        <div class="deskripsi-container flex-grow" data-full-text="<?= esc($p['deskripsi'] ?? '') ?>">
                                            <p class="text-[#4c669a] text-sm leading-relaxed mb-4 deskripsi-text">
                                                <?= esc(character_limiter($p['deskripsi'] ?? 'Deskripsi tidak tersedia', 80)) ?>
                                            </p>
                                            <?php if (strlen($p['deskripsi'] ?? '') > 80): ?>
                                                <button type="button"
                                                        class="toggle-deskripsi text-primary text-sm font-semibold hover:underline mb-4"
                                                        onclick="toggleDeskripsi(this)">
                                                    Baca Selengkapnya
                                                </button>
                                            <?php endif; ?>
                                        </div>

                                        <!-- Price & CTA -->
                                        <div class="mt-auto">
                                            <div class="price mb-4">
                                                <?php if ($p['harga_awal'] > $p['harga_promo']): ?>
                                                    <span class="price-old">Rp <?= number_format($p['harga_awal'], 0, ',', '.') ?></span>
                                                <?php endif; ?>
                                                <?php if ($p['harga_promo'] == 0): ?>
                                                    <span class="price-new text-emerald-600 font-bold">GRATIS</span>
                                                <?php else: ?>
                                                    <span class="price-new">Rp <?= number_format($p['harga_promo'], 0, ',', '.') ?></span>
                                                <?php endif; ?>
                                            </div>

                                            <!-- CTA Button - Green for free, Blue for paid -->
                                            <?php if ($p['harga_promo'] == 0): ?>
                                                <a href="<?= base_url('/produk/' . $p['id_produk']) ?>" class="btn-buy btn-buy-free w-full">
                                                    <i class="ph ph-shopping-cart !text-[18px]"></i>
                                                    Daftar Sekarang
                                                </a>
                                            <?php else: ?>
                                                <a href="<?= base_url('/produk/' . $p['id_produk']) ?>" class="btn-buy w-full">
                                                    <i class="ph ph-shopping-cart !text-[18px]"></i>
                                                    Daftar Sekarang
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="flex-1 text-center py-12">
                                <i class="ph ph-books text-6xl text-gray-300 mb-4"></i>
                                <p class="text-gray-400">Belum ada program yang tersedia</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>

        <!-- ==================== FEATURES & TESTIMONIALS SECTION (Dynamic) ==================== -->
        <?= view('landing/components/features_testimonials', [
            'features_list' => $features_list ?? [],
            'testimoni_list' => $testimoni_list ?? []
        ]) ?>

        <!-- ==================== FAQ SECTION ==================== -->
        <?php if (!empty($faq)): ?>
        <section id="faq" class="py-20 px-6 md:px-20 lg:px-40 bg-white">
            <div class="max-w-[800px] mx-auto">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-extrabold text-[#0d121b] mb-4">
                        Pertanyaan yang Sering <span class="text-primary">Diajukan</span>
                    </h2>
                </div>

                <div class="space-y-4">
                    <?php foreach ($faq as $f): ?>
                        <div class="faq-item border border-gray-200 rounded-xl overflow-hidden">
                            <button type="button"
                                    class="faq-toggle w-full flex items-center justify-between p-5 text-left font-semibold text-[#0d121b] hover:bg-gray-50 transition-colors"
                                    onclick="toggleFaq(this)">
                                <span><?= esc($f['pertanyaan']) ?></span>
                                <i class="ph ph-caret-down transition-transform"></i>
                            </button>
                            <div class="faq-answer hidden px-5 pb-5 text-[#4c669a]">
                                <?= esc($f['jawaban']) ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <!-- ==================== FOOTER ==================== -->
        <footer class="bg-[#0d121b] text-white py-12 px-6 md:px-20 lg:px-40">
            <div class="max-w-[1200px] mx-auto">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                    <div>
                        <img src="<?= base_url('assets/images/zevedulogo.png') ?>" alt="Zevedu" class="h-10 mb-4 brightness-0 invert">
                        <p class="text-gray-400 text-sm">Platform pembelajaran online berkualitas untuk masa depan yang lebih cerah.</p>
                    </div>
                    <div>
                        <h4 class="font-bold mb-4">Program</h4>
                        <ul class="space-y-2 text-gray-400 text-sm">
                            <?php if (!empty($kategori)): ?>
                                <?php foreach (array_slice($kategori, 0, 5) as $kat): ?>
                                    <li><a href="<?= base_url('/#program?kategori=' . $kat['id_kategori']) ?>" class="hover:text-white transition-colors"><?= esc($kat['nama_kategori']) ?></a></li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-bold mb-4">Bantuan</h4>
                        <ul class="space-y-2 text-gray-400 text-sm">
                            <li><a href="#faq" class="hover:text-white transition-colors">FAQ</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">Syarat & Ketentuan</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">Kebijakan Privasi</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-bold mb-4">Kontak</h4>
                        <ul class="space-y-2 text-gray-400 text-sm">
                            <li class="flex items-center gap-2"><i class="ph ph-envelope"></i> info@zevedu.com</li>
                            <li class="flex items-center gap-2"><i class="ph ph-whatsapp-logo"></i> +62 812 3456 7890</li>
                        </ul>
                    </div>
                </div>
                <div class="border-t border-gray-700 pt-8 text-center text-gray-400 text-sm">
                    <p>&copy; <?= date('Y') ?> Zevedu Academy. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </main>
</div>

<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    /* Card Grid - Standardized sizing */
    .promo-card {
        display: flex;
        flex-direction: column;
        height: 100%;
    }
    .promo-content {
        display: flex;
        flex-direction: column;
        flex: 1;
    }

    /* 16:9 Image Container */
    .promo-img-container {
        width: 100%;
        overflow: hidden;
        background-color: #f3f4f6;
        flex-shrink: 0;
    }
    .promo-img-16-9 {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    /* Green Button for Free Products */
    .btn-buy-free {
        background: #10b981 !important;
    }
    .btn-buy-free:hover {
        background: #059669 !important;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // ==================== MOBILE MENU ====================
    function toggleMenu() {
        const menu = document.getElementById('mobile-menu');
        const backdrop = document.getElementById('backdrop');
        menu.classList.toggle('open');
        backdrop.classList.toggle('hidden');
    }

    // ==================== HERO SLIDER ====================
    let currentSlide = 0;
    const totalSlides = <?= !empty($sliders) ? count($sliders) : 0 ?>;

    function moveSlider(direction) {
        if (totalSlides <= 1) return;
        currentSlide = (currentSlide + direction + totalSlides) % totalSlides;
        updateSlider();
    }

    function goToSlide(index) {
        currentSlide = index;
        updateSlider();
    }

    function updateSlider() {
        const slider = document.getElementById('hero-slider');
        const dots = document.querySelectorAll('.slider-dot');
        if (slider) {
            slider.style.transform = `translateX(-${currentSlide * 100}%)`;
        }
        dots.forEach((dot, index) => {
            if (index === currentSlide) {
                dot.classList.add('bg-primary', 'w-6', 'shadow-md');
                dot.classList.remove('bg-slate-300');
            } else {
                dot.classList.remove('bg-primary', 'w-6', 'shadow-md');
                dot.classList.add('bg-slate-300');
            }
        });
    }

    // Auto-play slider
    if (totalSlides > 1) {
        setInterval(() => moveSlider(1), 5000);
    }

    // ==================== CARD CAROUSEL ====================
    function scrollCards(direction) {
        const carousel = document.getElementById('card-carousel');
        const cards = carousel.querySelectorAll('.promo-card');
        if (cards.length === 0) return;
        const cardWidth = 320 + 32; // Fixed card width + gap
        if (direction === 'left') {
            carousel.scrollLeft -= cardWidth;
        } else {
            carousel.scrollLeft += cardWidth;
        }
    }

    // ==================== FILTER PRODUK BY KATEGORI ====================
    // Store kategori deskripsi data
    const kategoriDeskripsiData = <?= json_encode(array_column($kategori ?? [], 'deskripsi', 'id_kategori')) ?>;

    function filterProduk(idKategori) {
        const cards = document.querySelectorAll('#card-carousel .promo-card');
        const navButtons = document.querySelectorAll('.nav-category-b');
        const kategoriDeskripsi = document.getElementById('kategori-deskripsi');
        const textVisible = document.getElementById('kategori-text-visible');
        const textMore = document.getElementById('kategori-text-more');
        const kategoriToggle = document.getElementById('kategori-deskripsi-toggle');

        // Update active state
        navButtons.forEach(btn => {
            btn.classList.remove('active');
            if ((idKategori === null && btn.dataset.kategori === 'all') ||
                (btn.dataset.kategori == idKategori)) {
                btn.classList.add('active');
            }
        });

        // Show/hide cards
        cards.forEach(card => {
            if (idKategori === null || card.dataset.kategori == idKategori) {
                card.style.display = 'flex';
                card.style.visibility = 'visible';
            } else {
                card.style.display = 'none';
                card.style.visibility = 'hidden';
            }
        });

        // Show/hide kategori deskripsi
        if (idKategori !== null && kategoriDeskripsiData[idKategori]) {
            const deskripsi = kategoriDeskripsiData[idKategori];

            if (deskripsi && deskripsi.length > 100) {
                textVisible.textContent = deskripsi.substring(0, 100);
                textMore.textContent = deskripsi.substring(100);
                textMore.classList.remove('max-h-0', 'opacity-0');
                textMore.classList.add('max-h-full', 'opacity-100');
                kategoriToggle.classList.remove('hidden');
                kategoriToggle.textContent = 'Sembunyikan';
            } else {
                textVisible.textContent = deskripsi || '';
                textMore.textContent = '';
                textMore.classList.add('max-h-0', 'opacity-0');
                textMore.classList.remove('max-h-full', 'opacity-100');
                kategoriToggle.classList.add('hidden');
            }

            kategoriDeskripsi.classList.remove('hidden');
        } else {
            kategoriDeskripsi.classList.add('hidden');
        }
    }

    // Toggle Kategori Deskripsi (In-Text Inline Expansion)
    function toggleKategoriDeskripsi(button) {
        const textMore = document.getElementById('kategori-text-more');
        const isExpanded = button.textContent === 'Sembunyikan';

        if (isExpanded) {
            textMore.classList.remove('max-h-full', 'opacity-100');
            textMore.classList.add('max-h-0', 'opacity-0');
            button.textContent = 'Baca Selengkapnya';
        } else {
            textMore.classList.remove('max-h-0', 'opacity-0');
            textMore.classList.add('max-h-full', 'opacity-100');
            button.textContent = 'Sembunyikan';
        }
    }

    // ==================== TOGGLE DESKRIPSI (INLINE EXPANSION) ====================
    function toggleDeskripsi(button) {
        const container = button.closest('.deskripsi-container');
        const textEl = container.querySelector('.deskripsi-text');
        const fullText = container.dataset.fullText;
        const isExpanded = button.textContent === 'Sembunyikan';

        if (isExpanded) {
            textEl.textContent = fullText.substring(0, 80) + '...';
            button.textContent = 'Baca Selengkapnya';
        } else {
            textEl.textContent = fullText;
            button.textContent = 'Sembunyikan';
        }
    }

    // ==================== TOGGLE FAQ ====================
    function toggleFaq(button) {
        const item = button.closest('.faq-item');
        const answer = item.querySelector('.faq-answer');
        const icon = button.querySelector('i');

        answer.classList.toggle('hidden');
        icon.style.transform = answer.classList.contains('hidden') ? '' : 'rotate(180deg)';
    }

    // ==================== INITIALIZE FROM URL PARAMS ====================
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const kategoriId = urlParams.get('kategori');
        if (kategoriId) {
            filterProduk(parseInt(kategoriId));
        }
        // Initialize slider
        updateSlider();
    });
</script>
<?= $this->endSection() ?>