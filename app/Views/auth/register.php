<!DOCTYPE html>
<html lang="id" data-theme="zevedu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - ZevedU Academy</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Compiled Tailwind + DaisyUI -->
    <link rel="stylesheet" href="<?= base_url('assets/css/output.css?v=' . time()) ?>">

    <!-- Custom Design System -->
    <link rel="stylesheet" href="<?= base_url('assets/css/design-system.css?v=' . time()) ?>">

    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0C5CAB 0%, #1E40AF 50%, #0C5CAB 100%);
            min-height: 100vh;
        }
        h1, h2, h3, h4, h5, h6 {
            font-family: 'IBM Plex Sans', sans-serif;
        }
        .login-bg-pattern {
            background-image:
                radial-gradient(circle at 20% 50%, rgba(255,255,255,0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255,255,255,0.05) 0%, transparent 40%);
        }
        .input-field:focus {
            border-color: #0C5CAB;
            box-shadow: 0 0 0 3px rgba(12, 92, 171, 0.1);
        }
        .btn-register {
            background: linear-gradient(135deg, #0C5CAB 0%, #1E40AF 100%);
            transition: all 0.3s ease;
        }
        .btn-register:hover {
            background: linear-gradient(135deg, #084A8A 0%, #0C5CAB 100%);
            transform: translateY(-1px);
            box-shadow: 0 8px 24px rgba(12, 92, 171, 0.35);
        }
    </style>
</head>
<body class="login-bg-pattern flex items-center justify-center min-h-screen p-4">

    <!-- Background Decoration -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-white/5 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-white/5 rounded-full blur-3xl"></div>
    </div>

    <!-- Register Container -->
    <div class="relative w-full max-w-md">

        <!-- Logo & Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-white to-slate-100 rounded-2xl shadow-xl mb-6">
                <i class="ph-fill ph-graduation-cap text-4xl text-[#0C5CAB]"></i>
            </div>
            <h1 class="text-3xl font-bold text-white mb-2">ZevedU Academy</h1>
            <p class="text-blue-200 text-sm">Daftar untuk mulai belajar bersama kami</p>
        </div>

        <!-- Register Card -->
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">

            <!-- Card Header -->
            <div class="px-8 pt-8 pb-6 bg-gradient-to-r from-slate-50 to-white border-b border-slate-100">
                <h2 class="text-xl font-bold text-slate-800 mb-1">Buat Akun Baru</h2>
                <p class="text-sm text-slate-500">Isi data di bawah untuk mendaftar</p>
            </div>

            <!-- Card Body -->
            <div class="px-8 py-6">
                <?php if(session()->getFlashdata('error')): ?>
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl flex items-center gap-3 animate-fade-in">
                    <div class="w-10 h-10 rounded-xl bg-red-500 flex items-center justify-center flex-shrink-0">
                        <i class="ph-fill ph-warning-circle text-white text-lg"></i>
                    </div>
                    <p class="text-red-700 font-medium text-sm"><?= session()->getFlashdata('error') ?></p>
                </div>
                <?php endif; ?>

                <form action="<?= base_url('auth/register') ?>" method="POST" class="space-y-5">

                    <!-- Nama Lengkap Field -->
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-700 flex items-center gap-2">
                            <i class="ph ph-user text-slate-400"></i>
                            Nama Lengkap
                        </label>
                        <div class="relative">
                            <input type="text"
                                   name="nama_lengkap"
                                   placeholder="Masukkan nama lengkap"
                                   class="input-field w-full px-4 py-3.5 pl-12 bg-white border-2 border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none transition-all"
                                   required />
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                                <i class="ph ph-user text-lg"></i>
                            </span>
                        </div>
                    </div>

                    <!-- Email Field -->
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-700 flex items-center gap-2">
                            <i class="ph ph-envelope-simple text-slate-400"></i>
                            Email Address
                        </label>
                        <div class="relative">
                            <input type="email"
                                   name="email"
                                   placeholder="nama@email.com"
                                   class="input-field w-full px-4 py-3.5 pl-12 bg-white border-2 border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none transition-all"
                                   required />
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                                <i class="ph ph-envelope-simple text-lg"></i>
                            </span>
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-700 flex items-center gap-2">
                            <i class="ph ph-lock-simple text-slate-400"></i>
                            Password
                        </label>
                        <div class="relative">
                            <input type="password"
                                   name="password"
                                   id="password"
                                   placeholder="Minimal 6 karakter"
                                   class="input-field w-full px-4 py-3.5 pl-12 pr-12 bg-white border-2 border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none transition-all"
                                   required minlength="6" />
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                                <i class="ph ph-lock-simple text-lg"></i>
                            </span>
                            <button type="button"
                                    onclick="togglePassword()"
                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors">
                                <i class="ph ph-eye text-lg" id="toggleIcon"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Terms Agreement -->
                    <div class="flex items-start gap-3">
                        <input type="checkbox"
                               id="terms"
                               class="w-5 h-5 mt-0.5 rounded-lg border-2 border-slate-200 text-[#0C5CAB] focus:ring-[#0C5CAB] focus:ring-offset-0 cursor-pointer"
                               required />
                        <label for="terms" class="text-sm text-slate-600 cursor-pointer">
                            Saya setuju dengan
                            <a href="#" class="font-medium text-[#0C5CAB] hover:underline">Syarat & Ketentuan</a>
                            dan
                            <a href="#" class="font-medium text-[#0C5CAB] hover:underline">Kebijakan Privasi</a>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                            class="btn-register w-full py-4 rounded-xl text-white font-semibold text-base flex items-center justify-center gap-2 shadow-lg">
                        <span>Daftar Sekarang</span>
                        <i class="ph-fill ph-arrow-right text-lg"></i>
                    </button>
                </form>
            </div>

            <!-- Footer -->
            <div class="px-8 py-6 bg-slate-50 border-t border-slate-100 text-center">
                <p class="text-sm text-slate-600">
                    Sudah punya akun?
                    <a href="<?= base_url('/login') ?>" class="font-semibold text-[#0C5CAB] hover:text-[#084A8A] hover:underline transition-colors">
                        Masuk di sini
                    </a>
                </p>
            </div>
        </div>

        <!-- Back to Website -->
        <div class="text-center mt-8">
            <a href="<?= base_url('/') ?>"
               class="inline-flex items-center gap-2 text-blue-200 hover:text-white transition-colors text-sm font-medium">
                <i class="ph ph-arrow-left"></i>
                <span>Kembali ke Beranda</span>
            </a>
        </div>

        <!-- Copyright -->
        <div class="text-center mt-6 text-blue-300/60 text-xs">
            <p>&copy; <?= date('Y') ?> ZevedU Academy. All rights reserved.</p>
        </div>
    </div>

    <!-- Toggle Password Script -->
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.className = 'ph ph-eye-slash text-lg';
            } else {
                passwordInput.type = 'password';
                toggleIcon.className = 'ph ph-eye text-lg';
            }
        }
    </script>

</body>
</html>