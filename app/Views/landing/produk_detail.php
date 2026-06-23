<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- ==================== HEADER ==================== -->
<header class="flex items-center justify-between whitespace-nowrap border-b border-solid border-[#e7ebf3] px-6 md:px-20 lg:px-40 py-4 bg-white">
    <div class="flex items-center gap-8 lg:gap-12">
        <a href="<?= base_url('/') ?>">
            <img src="<?= base_url('assets/images/zevedulogo.png') ?>"
                 alt="Logo Zevedu"
                 class="h-12 w-auto object-contain"
                 style="aspect-ratio: 7/4;">
        </a>
    </div>

    <div class="flex items-center gap-2">
        <a href="<?= base_url('/login') ?>"
           class="hidden sm:flex min-w-[84px] cursor-pointer items-center justify-center rounded-xl h-10 px-4 bg-[#e7ebf3] text-[#0d121b] text-sm font-bold transition-all hover:bg-gray-200">
            Masuk
        </a>
    </div>
</header>

<!-- ==================== PRODUCT DETAIL ==================== -->
<main class="bg-gradient-to-b from-[#f8fafc] to-white min-h-screen">
    <div class="max-w-[1200px] mx-auto px-6 md:px-20 lg:px-40 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Product Image -->
            <div class="relative">
                <?php if (!empty($produk['gambar'])): ?>
                    <img src="<?= base_url('uploads/' . $produk['gambar']) ?>"
                         alt="<?= esc($produk['judul']) ?>"
                         class="w-full aspect-square object-cover rounded-3xl shadow-xl">
                <?php else: ?>
                    <img src="https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=800&auto=format&fit=crop"
                         alt="<?= esc($produk['judul']) ?>"
                         class="w-full aspect-square object-cover rounded-3xl shadow-xl">
                <?php endif; ?>

                <?php if ($produk['diskon_persen'] > 0): ?>
                    <span class="absolute top-6 left-6 bg-primary text-white text-sm font-bold px-4 py-2 rounded-full shadow-lg">
                        -<?= $produk['diskon_persen'] ?>% OFF
                    </span>
                <?php endif; ?>
            </div>

            <!-- Product Info -->
            <div class="flex flex-col">
                <!-- Kategori Badge -->
                <span class="inline-block px-3 py-1 text-xs font-semibold text-primary bg-primary/10 rounded-full w-fit mb-4">
                    <?= esc($produk['nama_kategori'] ?? 'Umum') ?>
                </span>

                <!-- Title -->
                <h1 class="text-3xl md:text-4xl font-black text-[#0d121b] mb-4">
                    <?= esc($produk['judul']) ?>
                </h1>

                <!-- Description -->
                <div class="mb-6">
                    <h3 class="font-bold text-[#0d121b] mb-2">Deskripsi Program</h3>
                    <p class="text-[#4c669a] leading-relaxed">
                        <?= nl2br(esc($produk['deskripsi'] ?? 'Deskripsi tidak tersedia')) ?>
                    </p>
                </div>

                <!-- Mentor Info -->
                <?php if (!empty($produk['nama_admin'])): ?>
                    <div class="mb-6 p-4 bg-white rounded-xl border border-gray-100">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-full bg-primary/20 flex items-center justify-center">
                                <i class="ph ph-user text-primary text-xl"></i>
                            </div>
                            <div>
                                <p class="text-sm text-[#4c669a]">Dipandu oleh</p>
                                <p class="font-bold text-[#0d121b]"><?= esc($produk['nama_admin']) ?></p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Price -->
                <div class="mb-6 p-6 bg-white rounded-2xl border border-gray-100">
                    <div class="flex items-end gap-4 mb-4">
                        <?php if ($produk['harga_awal'] > $produk['harga_promo']): ?>
                            <span class="text-2xl text-gray-400 line-through">Rp <?= number_format($produk['harga_awal'], 0, ',', '.') ?></span>
                        <?php endif; ?>
                        <span class="text-4xl font-black text-primary">Rp <?= number_format($produk['harga_promo'], 0, ',', '.') ?></span>
                    </div>
                    <?php if ($produk['diskon_persen'] > 0): ?>
                        <p class="text-sm text-[#4c669a]">Hemat Rp <?= number_format($produk['harga_awal'] - $produk['harga_promo'], 0, ',', '.') ?> (<?= $produk['diskon_persen'] ?>% discount)</p>
                    <?php endif; ?>
                </div>

                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 mt-auto">
                    <a href="<?= base_url('/login') ?>" class="flex-1 btn-buy justify-center text-lg py-4">
                        <i class="ph ph-shopping-cart !text-[20px]"></i>
                        Daftar Sekarang
                    </a>
                    <a href="<?= base_url('/#program') ?>" class="flex items-center justify-center gap-2 px-6 py-4 border-2 border-gray-200 rounded-xl text-[#0d121b] font-bold hover:bg-gray-50 transition-colors">
                        <i class="ph ph-arrow-left"></i>
                        Lihat Program Lain
                    </a>
                </div>
            </div>
        </div>

        <!-- Testimoni Section -->
        <?php if (!empty($testimoni)): ?>
        <div class="mt-16">
            <h2 class="text-2xl font-bold text-[#0d121b] mb-6">Testimoni Peserta</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <?php foreach ($testimoni as $testi): ?>
                    <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100">
                        <div class="flex items-center gap-3 mb-4">
                            <?php if (!empty($testi['foto_profil'])): ?>
                                <img src="<?= base_url('uploads/profil/' . $testi['foto_profil']) ?>"
                                     alt="<?= esc($testi['nama_lengkap'] ?? 'User') ?>"
                                     class="w-12 h-12 rounded-full object-cover">
                            <?php else: ?>
                                <div class="w-12 h-12 rounded-full bg-primary/20 flex items-center justify-center">
                                    <i class="ph ph-user text-primary text-xl"></i>
                                </div>
                            <?php endif; ?>
                            <div>
                                <h4 class="font-bold text-[#0d121b]"><?= esc($testi['nama_lengkap'] ?? 'Anonymous') ?></h4>
                            </div>
                        </div>
                        <div class="flex gap-1 mb-3">
                            <?php for ($i = 0; $i < ($testi['rating'] ?? 5); $i++): ?>
                                <i class="ph-fill ph-star text-yellow-400"></i>
                            <?php endfor; ?>
                        </div>
                        <p class="text-[#4c669a] text-sm leading-relaxed"><?= esc($testi['deskripsi'] ?? '') ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</main>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Any additional scripts for product detail page
</script>
<?= $this->endSection() ?>