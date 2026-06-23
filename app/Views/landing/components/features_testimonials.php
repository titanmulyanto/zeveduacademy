<?php
/**
 * ============================================================================
 * FEATURES & TESTIMONIALS SECTION
 * ============================================================================
 * Data dari database db_zevedu
 * Referensi:
 * - Features: zigzaglayout.html (zig-zag position dengan nth-child)
 * - Testimonials: button.html (Dual Center Focus Carousel)
 *
 * @project Zevedu Academy
 * ============================================================================
 */
?>

<!-- ========================================================================== -->
<!-- FEATURES SECTION - Zig-Zag Layout dengan nth-child                             -->
<!-- ========================================================================== -->

<!-- Judul Section (di luar nth-child) -->
<div class="text-center py-16 md:py-20 bg-white">
    <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold text-[#1152d4] mb-4">
        Fitur yang Anda Dapatkan
    </h2>
    <p class="text-base md:text-lg text-[#64748b] max-w-2xl mx-auto">
        Temukan berbagai keunggulan yang akan membantu perjalanan belajar Anda
    </p>
</div>

<!-- Wrapper untuk feature sections (nth-child mulai dari 1) -->
<div class="feature-wrapper">

    <?php if (!empty($features_list)): ?>
        <?php foreach ($features_list as $index => $feature): ?>
            <?php
            // Image path dari database
            $imageSrc = !empty($feature['gambar'])
                ? base_url('uploads/' . $feature['gambar'])
                : null;

            // Fallback images
            $fallbackImages = [
                'https://images.unsplash.com/photo-1498050108023-c5249f4df085?w=800&q=80',
                'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=800&q=80',
                'https://images.unsplash.com/photo-1552664730-d307ca884978?w=800&q=80',
            ];
            $fallbackSrc = $fallbackImages[$index % count($fallbackImages)];
            ?>
            <!-- Feature Section - nth-child odd/even untuk zig-zag -->
            <section class="feature-section">
                <div class="container">
                    <div class="feature-content">
                        <h2><?= esc($feature['judul']) ?></h2>
                        <p><?= esc($feature['deskripsi']) ?></p>
                    </div>
                    <div class="feature-image">
                        <div class="image-wrapper">
                            <img
                                src="<?= $imageSrc ?: $fallbackSrc ?>"
                                alt="<?= esc($feature['judul']) ?>"
                                loading="lazy"
                                onerror="this.onerror=null; this.src='<?= $fallbackSrc ?>';"
                            >
                        </div>
                    </div>
                </div>
            </section>
        <?php endforeach; ?>

        <?php else: ?>
        <section class="feature-section">
            <div class="container">
                <div class="feature-content text-center">
                    <h2>Belum Ada Fitur</h2>
                    <p>Tambahkan data di tabel feature_section pada database</p>
                </div>
            </div>
        </section>
        <?php endif; ?>

</div><!-- end .feature-wrapper -->

</section>

<!-- ========================================================================== -->
<!-- TESTIMONIALS SECTION - Dual Center Focus Carousel (dari button.html)          -->
<!-- ========================================================================== -->
<section id="testimoni" class="bg-[#f8fafc] py-16 md:py-20 lg:py-24 overflow-hidden">
    <div class="max-w-7xl mx-auto px-4">

        <!-- Section Header -->
        <div class="text-center mb-12">
            <span class="inline-block px-4 py-1.5 rounded-full bg-[#1152d4]/10 text-[#1152d4] text-sm font-semibold mb-4">
                TESTIMONI
            </span>
            <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold text-[#0d121b] mb-4">
                Kata <span class="text-[#1152d4]">Mereka</span> Tentang Kami
            </h2>
            <p class="text-base md:text-lg text-[#64748b] max-w-2xl mx-auto">
                Dengarkan langsung dari para alumni yang telah merasakan manfaat belajar di Zevedu Academy
            </p>
        </div>

        <?php if (!empty($testimoni_list)): ?>
        <!-- Dual Center Focus Carousel -->
        <div class="relative">
            <div id="carousel-container" class="relative flex justify-center items-center h-[520px] md:h-[380px] w-full">

                <?php foreach ($testimoni_list as $index => $testi): ?>
                    <?php
                    $nama = esc($testi['nama'] ?? 'Alumni Zevedu');
                    $deskripsi = esc($testi['deskripsi'] ?? '');
                    $rating = max(1, min(10, (int)($testi['rating'] ?? 5)));
                    $fotoProfil = !empty($testi['foto_profil'])
                        ? base_url('uploads/' . $testi['foto_profil'])
                        : null;
                    $fallbackAvatar = 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=200';
                    ?>
                    <div class="carousel-card absolute w-full max-w-md bg-white p-6 rounded-3xl border border-slate-200/80 flex flex-col md:flex-row items-center md:items-start gap-5 overflow-hidden transition-all duration-500 ease-out"
                         data-index="<?= $index ?>">
                        <div class="relative shrink-0">
                            <div class="absolute -inset-1 bg-gradient-to-tr from-[#1152d4] to-indigo-400 rounded-full opacity-20 blur-[1px]"></div>
                            <img
                                src="<?= $fotoProfil ?: $fallbackAvatar ?>"
                                alt="<?= $nama ?>"
                                class="w-20 h-20 rounded-full aspect-square object-cover border-4 border-white shadow-md relative z-10"
                                onerror="this.src='<?= $fallbackAvatar ?>';"
                            >
                        </div>
                        <div class="flex-1 flex flex-col text-center md:text-left w-full">
                            <h4 class="font-extrabold text-slate-950 text-xl tracking-tight"><?= $nama ?></h4>

                            <!-- Rating 10 bintang -->
                            <div class="flex items-center justify-center md:justify-start gap-0.5 mt-1 mb-3 text-amber-400">
                                <?php for ($i = 1; $i <= 10; $i++): ?>
                                    <svg class="w-3.5 h-3.5 <?= $i <= $rating ? 'fill-current' : 'fill-slate-200' ?>" viewBox="0 0 20 20">
                                        <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                    </svg>
                                <?php endfor; ?>
                                <span class="text-xs font-bold text-slate-400 ml-1 font-inter"><?= $rating ?>/10</span>
                            </div>

                            <div class="relative min-h-[80px] flex items-center">
                                <svg class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 fill-[#f1f5f9] w-28 h-28 select-none pointer-events-none z-0" viewBox="0 0 24 24">
                                    <path d="M14 17h3l2-4V7h-6v6h3zM5 17h3l2-4V7H4v6h3z"/>
                                </svg>
                                <p class="text-xs md:text-sm text-slate-600 font-medium font-inter leading-relaxed italic relative z-10 w-full">
                                    "<?= $deskripsi ?>"
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>

            <!-- Navigation Buttons -->
            <div class="flex justify-center items-center gap-6 mt-10">
                <button onclick="prevSlide()" class="w-12 h-12 bg-white rounded-full border border-slate-200 shadow-md flex items-center justify-center text-slate-600 hover:text-[#1152d4] hover:border-[#1152d4] hover:shadow-lg transition-all duration-300 focus:outline-none">
                    <svg class="w-5 h-5 fill-none stroke-current stroke-2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
                <button onclick="nextSlide()" class="w-12 h-12 bg-white rounded-full border border-slate-200 shadow-md flex items-center justify-center text-slate-600 hover:text-[#1152d4] hover:border-[#1152d4] hover:shadow-lg transition-all duration-300 focus:outline-none">
                    <svg class="w-5 h-5 fill-none stroke-current stroke-2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>
        </div>

        <?php else: ?>
        <div class="text-center py-16">
            <div class="w-20 h-20 mx-auto mb-6 rounded-full bg-[#1152d4]/10 flex items-center justify-center">
                <svg class="w-10 h-10 text-[#1152d4]/50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-[#0d121b] mb-2">Belum Ada Testimoni</h3>
            <p class="text-[#64748b] max-w-md mx-auto">
                Jadilah yang pertama memberikan testimoni tentang pengalaman belajar Anda di Zevedu Academy
            </p>
        </div>
        <?php endif; ?>

    </div>
</section>

<!-- ========================================================================== -->
<!-- INTERNAL CSS - Zig-Zag dengan nth-child (PUTIH dulu, lalu BIRU)                 -->
<!-- ========================================================================== -->
<style>
    /* ==========================================================================
       FEATURES SECTION - Zig-Zag Layout dengan nth-child
       ========================================================================== */

    #fitur-unggulan {
        position: relative;
    }

    .feature-section {
        position: relative;
        padding: 130px 0;
        width: 100%;
    }

    /* Section 1 (nth-child 1 = odd) - PUTIH dulu */
    .feature-wrapper .feature-section:nth-child(odd) {
        background-color: #ffffff;
        color: #1e293b;
    }

    /* Section 2 (nth-child 2 = even) - BIRU */
    .feature-wrapper .feature-section:nth-child(even) {
        background-color: #1152d4;
        color: #ffffff;
    }

    .container {
        max-width: 1100px;
        margin: 0 auto;
        padding: 0 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 70px;
    }

    /* Section 1 (odd): flex-direction row (normal) - teks kiri, gambar kanan */
    .feature-wrapper .feature-section:nth-child(odd) .container {
        flex-direction: row;
    }

    /* Section 2 (even): flex-direction row-reverse - gambar kiri, teks kanan */
    .feature-wrapper .feature-section:nth-child(even) .container {
        flex-direction: row-reverse;
    }

    .feature-content {
        flex: 1;
    }

    .feature-content h2 {
        font-size: 2.8rem;
        font-weight: 800;
        margin: 0 0 20px 0;
        line-height: 1.15;
        letter-spacing: -0.02em;
    }

    /* Section 1 (PUTIH): judul biru, deskripsi slate */
    .feature-wrapper .feature-section:nth-child(odd) h2 {
        color: #1152d4;
    }
    .feature-wrapper .feature-section:nth-child(odd) p {
        color: #475569;
    }

    /* Section 2 (BIRU): judul putih, deskripsi kebiruan */
    .feature-wrapper .feature-section:nth-child(even) h2 {
        color: #ffffff;
    }
    .feature-wrapper .feature-section:nth-child(even) p {
        color: #e0e7ff;
    }

    .feature-content p {
        font-size: 1.1rem;
        line-height: 1.7;
    }

    .feature-image {
        flex: 1.2;
        display: flex;
        justify-content: center;
    }

    /* Image Wrapper - RASIO 4:3 */
    .image-wrapper {
        width: 100%;
        max-width: 600px;
        aspect-ratio: 4 / 3;
        overflow: hidden;
        border-radius: 24px;
    }

    .image-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }

    /* Shadow */
    .feature-wrapper .feature-section:nth-child(odd) .image-wrapper {
        box-shadow: 0 30px 60px -15px rgba(17, 82, 212, 0.15);
    }
    .feature-wrapper .feature-section:nth-child(even) .image-wrapper {
        box-shadow: 0 30px 60px -15px rgba(0, 0, 0, 0.3);
    }

    .feature-image:hover img {
        transform: translateY(-8px) scale(1.02);
    }

    /* Responsive Mobile */
    @media (max-width: 768px) {
        .feature-section {
            padding: 80px 0;
        }
        .container,
        .feature-wrapper .feature-section:nth-child(odd) .container,
        .feature-wrapper .feature-section:nth-child(even) .container {
            flex-direction: column;
            text-align: center;
            gap: 40px;
        }
        .feature-content h2 {
            font-size: 2.1rem;
        }
        .image-wrapper {
            max-width: 100%;
        }
    }

    /* ==========================================================================
       TESTIMONIALS SECTION
       ========================================================================== */

    .carousel-card {
        will-change: transform, opacity;
    }

    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;700;800&family=Inter:wght@400;500;600&display=swap');

    body, .font-sans {
        font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
    }

    .font-inter {
        font-family: 'Inter', system-ui, sans-serif;
    }
</style>

<!-- ========================================================================== -->
<!-- JAVASCRIPT - Dual Center Focus Carousel                                       -->
<!-- ========================================================================== -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.carousel-card');
        let activeStartIndex = 0;

        function updateCarousel() {
            if (cards.length === 0) return;

            const isMobile = window.innerWidth < 768;
            const active1 = activeStartIndex;
            const active2 = (activeStartIndex + 1) % cards.length;
            const leftOuter = (activeStartIndex - 1 + cards.length) % cards.length;
            const rightOuter = (active2 + 1) % cards.length;

            cards.forEach((card, index) => {
                card.classList.remove('z-20', 'z-10', 'z-0', 'opacity-100', 'opacity-30', 'opacity-0', 'scale-105', 'scale-90', 'shadow-2xl', 'shadow-slate-200');

                if (index === active1) {
                    card.classList.add('z-20', 'opacity-100', 'scale-105', 'shadow-2xl', 'shadow-slate-200');
                    card.style.transform = isMobile ? "translateY(-60px) scale(1)" : "translateX(-52%) scale(1.04)";
                }
                else if (index === active2) {
                    card.classList.add('z-20', 'opacity-100', 'scale-105', 'shadow-2xl', 'shadow-slate-200');
                    card.style.transform = isMobile ? "translateY(60px) scale(1)" : "translateX(52%) scale(1.04)";
                }
                else if (index === leftOuter) {
                    card.classList.add('z-10', 'opacity-30', 'scale-90');
                    card.style.transform = isMobile ? "translateY(-170px) scale(0.85)" : "translateX(-138%) scale(0.88)";
                }
                else if (index === rightOuter) {
                    card.classList.add('z-10', 'opacity-30', 'scale-90');
                    card.style.transform = isMobile ? "translateY(170px) scale(0.85)" : "translateX(138%) scale(0.88)";
                }
                else {
                    card.classList.add('z-0', 'opacity-0');
                    card.style.transform = "scale(0.5)";
                }
            });
        }

        window.nextSlide = function() {
            if (cards.length > 0) {
                activeStartIndex = (activeStartIndex + 1) % cards.length;
                updateCarousel();
            }
        };

        window.prevSlide = function() {
            if (cards.length > 0) {
                activeStartIndex = (activeStartIndex - 1 + cards.length) % cards.length;
                updateCarousel();
            }
        };

        window.addEventListener('resize', updateCarousel);
        updateCarousel();
    });
</script>
