<!DOCTYPE html>
<html lang="id" data-theme="zevedu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - ZevedU Academy</title>

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
        .btn-login {
            background: linear-gradient(135deg, #0C5CAB 0%, #1E40AF 100%);
            transition: all 0.3s ease;
        }
        .btn-login:hover {
            background: linear-gradient(135deg, #084A8A 0%, #0C5CAB 100%);
            transform: translateY(-1px);
            box-shadow: 0 8px 24px rgba(12, 92, 171, 0.35);
        }
        .btn-google {
            transition: all 0.3s ease;
        }
        .btn-google:hover {
            background: #F8FAFC;
            border-color: #CBD5E1;
            transform: translateY(-1px);
        }
    </style>
</head>
<body class="login-bg-pattern flex items-center justify-center min-h-screen p-4">

    <!-- Background Decoration -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-white/5 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-white/5 rounded-full blur-3xl"></div>
    </div>

    <!-- Login Container -->
    <div class="relative w-full max-w-md">

        <!-- Logo & Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-white to-slate-100 rounded-2xl shadow-xl mb-6">
                <i class="ph-fill ph-graduation-cap text-4xl text-[#0C5CAB]"></i>
            </div>
            <h1 class="text-3xl font-bold text-white mb-2">ZevedU Academy</h1>
            <p class="text-blue-200 text-sm">Masuk untuk mengakses platform belajar</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">

            <!-- Card Header -->
            <div class="px-8 pt-8 pb-6 bg-gradient-to-r from-slate-50 to-white border-b border-slate-100">
                <h2 class="text-xl font-bold text-slate-800 mb-1">Selamat Datang</h2>
                <p class="text-sm text-slate-500">Masuk ke akun Anda untuk melanjutkan</p>
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

                <form action="<?= base_url('auth/login') ?>" method="POST" class="space-y-5">

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
                                <i class="ph ph-user text-lg"></i>
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
                                   placeholder="Masukkan password"
                                   class="input-field w-full px-4 py-3.5 pl-12 pr-12 bg-white border-2 border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none transition-all"
                                   required />
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

                    <!-- Remember & Forgot -->
                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2 cursor-pointer group">
                            <input type="checkbox"
                                   class="w-5 h-5 rounded-lg border-2 border-slate-200 text-[#0C5CAB] focus:ring-[#0C5CAB] focus:ring-offset-0 cursor-pointer" />
                            <span class="text-sm text-slate-600 group-hover:text-slate-800 transition-colors">Ingat saya</span>
                        </label>
                        <a href="#"
                           class="text-sm font-medium text-[#0C5CAB] hover:text-[#084A8A] hover:underline transition-colors">
                            Lupa Password?
                        </a>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                            class="btn-login w-full py-4 rounded-xl text-white font-semibold text-base flex items-center justify-center gap-2 shadow-lg">
                        <span>Masuk Sekarang</span>
                        <i class="ph-fill ph-arrow-right text-lg"></i>
                    </button>
                </form>
            </div>

            <!-- Divider -->
            <div class="px-8">
                <div class="flex items-center gap-4">
                    <div class="flex-1 h-px bg-slate-200"></div>
                    <span class="text-xs text-slate-400 font-medium">ATAU</span>
                    <div class="flex-1 h-px bg-slate-200"></div>
                </div>
            </div>

            <!-- Social Login -->
            <div class="px-8 py-6">
                <button class="btn-google w-full py-3.5 bg-white border-2 border-slate-200 rounded-xl text-slate-700 font-medium flex items-center justify-center gap-3 shadow-sm">
                    <svg class="w-5 h-5" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                    <span>Masuk dengan Google</span>
                </button>
            </div>

            <!-- Footer -->
            <div class="px-8 py-6 bg-slate-50 border-t border-slate-100 text-center">
                <p class="text-sm text-slate-600">
                    Belum punya akun?
                    <a href="<?= base_url('/register') ?>" class="font-semibold text-[#0C5CAB] hover:text-[#084A8A] hover:underline transition-colors">
                        Daftar sebagai Siswa
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