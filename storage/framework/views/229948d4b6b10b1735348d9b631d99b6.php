<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StockMaster - Sistem Manajemen Gudang Modern</title>
    <link rel="icon" type="image/svg+xml" href="<?php echo e(asset('favicon.svg')); ?>">
    <link rel="alternate icon" href="<?php echo e(asset('favicon.ico')); ?>">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        .hero-bg {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            position: relative;
            overflow: hidden;
        }
        .hero-bg::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="50" cy="10" r="0.5" fill="rgba(255,255,255,0.1)"/><circle cx="10" cy="50" r="0.5" fill="rgba(255,255,255,0.1)"/><circle cx="90" cy="50" r="0.5" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            animation: float 20s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            33% { transform: translateY(-10px) rotate(1deg); }
            66% { transform: translateY(10px) rotate(-1deg); }
        }
        .floating-card {
            animation: floating 6s ease-in-out infinite;
        }
        .floating-card:nth-child(2) { animation-delay: -2s; }
        .floating-card:nth-child(3) { animation-delay: -4s; }
        @keyframes floating {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        .pulse-glow {
            animation: pulse-glow 2s ease-in-out infinite alternate;
        }
        @keyframes pulse-glow {
            from { box-shadow: 0 0 20px rgba(16, 185, 129, 0.5); }
            to { box-shadow: 0 0 40px rgba(16, 185, 129, 0.8); }
        }
        .gradient-text {
            background: linear-gradient(45deg, #10b981, #059669, #34d399, #6ee7b7);
            background-size: 400% 400%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: gradient-shift 3s ease infinite;
        }
        @keyframes gradient-shift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }
        .bounce-in {
            animation: bounce-in 0.8s ease-out;
        }
        @keyframes bounce-in {
            0% { transform: scale(0.3); opacity: 0; }
            50% { transform: scale(1.05); }
            70% { transform: scale(0.9); }
            100% { transform: scale(1); opacity: 1; }
        }
        .slide-up {
            animation: slide-up 0.6s ease-out;
        }
        @keyframes slide-up {
            from { transform: translateY(50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .rotate-in {
            animation: rotate-in 0.8s ease-out;
        }
        @keyframes rotate-in {
            from { transform: rotate(-10deg) scale(0.8); opacity: 0; }
            to { transform: rotate(0deg) scale(1); opacity: 1; }
        }
    </style>
</head>
<body class="bg-white">
    <!-- Demo Mode Modal -->
    <div id="demoModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-[100] flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-2xl font-bold text-gray-900">Pilih Role Demo</h3>
                    <button onclick="closeDemoModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <p class="text-gray-600 mb-6">Pilih role untuk mencoba fitur sistem gudang dalam mode demo. Data hanya tersimpan di browser Anda.</p>

                <div class="space-y-4">
                    <button onclick="startDemo('admin')" class="w-full bg-gradient-to-r from-[#1F8F6A] to-[#166B50] text-white px-6 py-4 rounded-xl hover:from-[#166B50] hover:to-[#0F4A37] transition-all duration-300 transform hover:scale-105 shadow-lg">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="text-left">
                                    <p class="font-semibold text-lg">Admin</p>
                                    <p class="text-sm text-white/80">Akses penuh ke semua fitur</p>
                                </div>
                            </div>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </button>

                    <button onclick="startDemo('staf')" class="w-full bg-gradient-to-r from-green-500 to-teal-500 text-white px-6 py-4 rounded-xl hover:from-green-600 hover:to-teal-600 transition-all duration-300 transform hover:scale-105 shadow-lg">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                                    </svg>
                                </div>
                                <div class="text-left">
                                    <p class="font-semibold text-lg">Staf</p>
                                    <p class="text-sm text-white/80">Akses terbatas untuk operasional</p>
                                </div>
                            </div>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </button>
                </div>

                <div class="mt-6 p-4 bg-[#E9F6F1] rounded-lg">
                    <p class="text-sm text-[#1F8F6A]">
                        <strong>Mode Demo:</strong> Semua data hanya disimpan di browser Anda dan tidak akan tersimpan secara permanen.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="fixed top-0 w-full bg-white/90 backdrop-blur-md z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center select-none" style="gap: 12px;">
                    <svg viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" style="filter: drop-shadow(0 4px 6px rgba(0,0,0,0.15));">
                        <circle cx="32" cy="32" r="30" fill="#1F8F6A"/>
                        <circle cx="32" cy="32" r="30" fill="url(#grad1)" opacity="0.3"/>

                        <rect x="18" y="18" width="28" height="28" rx="4" fill="white" opacity="0.9"/>
                        <rect x="18" y="18" width="28" height="28" rx="4" fill="url(#grad2)" opacity="0.2"/>
                        <rect x="23" y="24" width="18" height="3" rx="1.5" fill="#1F8F6A" opacity="0.5"/>
                        <rect x="23" y="30" width="12" height="3" rx="1.5" fill="#1F8F6A" opacity="0.5"/>
                        <path d="M26 38 L30 42 L38 34" stroke="#1F8F6A" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>

                        <defs>
                            <linearGradient id="grad1" x1="0" y1="0" x2="64" y2="64">
                                <stop offset="0%" stop-color="white" stop-opacity="0.3"/>
                                <stop offset="100%" stop-color="black" stop-opacity="0.1"/>
                            </linearGradient>
                            <linearGradient id="grad2" x1="18" y1="18" x2="46" y2="46">
                                <stop offset="0%" stop-color="black" stop-opacity="0.1"/>
                                <stop offset="100%" stop-color="transparent"/>
                            </linearGradient>
                        </defs>
                    </svg>
                    <div class="flex flex-col justify-center" style="line-height: 1.1;">
                        <span class="font-extrabold" style="font-size: 22px; letter-spacing: -0.5px; color: #2c3e50;">StockMaster</span>
                        <span class="font-bold uppercase" style="font-size: 9px; color: #1F8F6A; letter-spacing: 1px; margin-top: 1px;">Inventory System</span>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="#features" class="text-slate-600 hover:text-[#1F8F6A] transition-colors duration-300">Fitur</a>
                    <a href="#pricing" class="text-slate-600 hover:text-[#1F8F6A] transition-colors duration-300">Harga</a>
                    <a href="#contact" class="text-slate-600 hover:text-[#1F8F6A] transition-colors duration-300">Kontak</a>
                    <a href="<?php echo e(route('login')); ?>" class="bg-[#1F8F6A] text-white px-4 py-2 rounded-lg hover:bg-[#166B50] transition-all duration-300 transform hover:scale-105">
                        Masuk
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-bg min-h-screen flex items-center justify-center relative">
        <div class="absolute inset-0 bg-black/20"></div>
        <div class="relative z-10 text-center text-white px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto">
            <div class="bounce-in">
                <h1 class="text-5xl md:text-7xl font-bold mb-6 gradient-text">
                    Kelola Gudang Anda
                    <span class="block text-3xl md:text-5xl font-light">dengan Mudah & Efisien</span>
                </h1>
                <p class="text-xl md:text-2xl mb-8 text-gray-100 slide-up" style="animation-delay: 0.3s;">
                    Sistem manajemen gudang modern dengan fitur lengkap untuk bisnis Anda.
                    Multi-tenant, real-time dashboard, dan laporan otomatis.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center slide-up" style="animation-delay: 0.6s;">
                    <a href="<?php echo e(route('subscription.subscribe')); ?>" class="bg-white text-[#1F8F6A] px-8 py-4 rounded-full text-lg font-semibold hover:bg-[#E9F6F1] transition-all duration-300 transform hover:scale-105 pulse-glow shadow-lg">
                        Langganan Sekarang
                    </a>
                    <button onclick="openDemoModal()" class="border-2 border-white text-white px-8 py-4 rounded-full text-lg font-semibold hover:bg-white hover:text-[#1F8F6A] transition-all duration-300">
                        Coba Demo
                    </button>
                </div>
            </div>
        </div>

        <!-- Floating Elements -->
        <div class="absolute top-20 left-10 floating-card">
            <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 shadow-lg">
                <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center mb-2">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <p class="text-white text-sm">Stok Aman</p>
            </div>
        </div>

        <div class="absolute bottom-20 right-10 floating-card">
            <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 shadow-lg">
                <div class="w-12 h-12 bg-[#E9F6F1]0 rounded-full flex items-center justify-center mb-2">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <p class="text-white text-sm">Real-time Update</p>
            </div>
        </div>

        <div class="absolute top-1/2 left-1/4 floating-card">
            <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 shadow-lg">
                <div class="w-12 h-12 bg-[#E9F6F1]0 rounded-full flex items-center justify-center mb-2">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"/>
                    </svg>
                </div>
                <p class="text-white text-sm">Multi-User</p>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Fitur Lengkap untuk Bisnis Anda</h2>
                <p class="text-xl text-gray-600">Semua yang Anda butuhkan untuk mengelola gudang dengan profesional</p>
            </div>

            <div class="flex flex-wrap gap-8 justify-center">
                <div class="w-full md:w-[calc(50%-1rem)] lg:w-[calc(33.333%-1.25rem)] max-w-md bg-gradient-to-br from-[#E9F6F1] to-[#D1EDE5] p-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 h-full" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-12 h-12 bg-[#1F8F6A] rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Manajemen Produk</h3>
                    <p class="text-gray-600">Kelola produk dengan kategori dan supplier lengkap. Import/export data dengan mudah.</p>
                </div>

                <div class="w-full md:w-[calc(50%-1rem)] lg:w-[calc(33.333%-1.25rem)] max-w-md bg-gradient-to-br from-[#E9F6F1] to-[#D1EDE5] p-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 h-full" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-12 h-12 bg-[#1F8F6A] rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Stok</h3>
                    <p class="text-gray-600">Catat transaksi stok dengan sistem FIFO/LIFO. Tracking perubahan stok real-time.</p>
                </div>

                <div class="w-full md:w-[calc(50%-1rem)] lg:w-[calc(33.333%-1.25rem)] max-w-md bg-gradient-to-br from-[#E9F6F1] to-[#D1EDE5] p-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 h-full" data-aos="fade-up" data-aos-delay="300">
                    <div class="w-12 h-12 bg-[#1F8F6A] rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M9.401 1.518a1 1 0 011.198 0l6 4.5a1 1 0 01.401.8V12a7 7 0 01-3.11 5.81l-2.89 1.926a1 1 0 01-1.1 0l-2.89-1.926A7 7 0 013 12V6.818a1 1 0 01.401-.8l6-4.5zM10 11.586L7.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l5-5a1 1 0 10-1.414-1.414L10 11.586z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Aktivitas</h3>
                    <p class="text-gray-600">Lacak perubahan data beserta pengguna, aksi, dan waktu untuk jejak aktivitas lengkap.</p>
                </div>

                <div class="w-full md:w-[calc(50%-1rem)] lg:w-[calc(33.333%-1.25rem)] max-w-md bg-gradient-to-br from-[#E9F6F1] to-[#D1EDE5] p-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 h-full" data-aos="fade-up" data-aos-delay="400">
                    <div class="w-12 h-12 bg-[#1F8F6A] rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 2a2 2 0 00-2 2v11a3 3 0 106 0V4a2 2 0 00-2-2H4zm1 14a1 1 0 100-2 1 1 0 000 2zm5-1.757l4.9-4.9a2 2 0 000-2.828L13.485 5.1a2 2 0 00-2.828 0L10 5.757v8.486zM16 18H9.071l6-6H16a2 2 0 012 2v2a2 2 0 01-2 2z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Multi-Tenant</h3>
                    <p class="text-gray-600">Sistem terisolasi untuk setiap perusahaan. Data aman dan terpisah dengan sempurna.</p>
                </div>

                <div class="w-full md:w-[calc(50%-1rem)] lg:w-[calc(33.333%-1.25rem)] max-w-md bg-gradient-to-br from-[#E9F6F1] to-[#D1EDE5] p-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 h-full" data-aos="fade-up" data-aos-delay="500">
                    <div class="w-12 h-12 bg-[#1F8F6A] rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">User Management</h3>
                    <p class="text-gray-600">Kelola admin dan staf dengan role-based access control. Multi-level permissions.</p>
                </div>

                <div class="w-full md:w-[calc(50%-1rem)] lg:w-[calc(33.333%-1.25rem)] max-w-md bg-gradient-to-br from-[#E9F6F1] to-[#D1EDE5] p-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 h-full" data-aos="fade-up" data-aos-delay="600">
                    <div class="w-12 h-12 bg-[#1F8F6A] rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Dashboard Real-time</h3>
                    <p class="text-gray-600">Monitor stok, aktivitas, dan performa bisnis secara real-time dengan chart interaktif.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="pricing" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Harga Terjangkau</h2>
                <p class="text-xl text-gray-600">Pilih paket yang sesuai dengan kebutuhan bisnis Anda</p>
            </div>

            <div class="max-w-md mx-auto">
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden transform hover:scale-105 transition-all duration-300" data-aos="zoom-in">
                    <div class="bg-gradient-to-r from-[#1F8F6A] to-[#166B50] p-8 text-white text-center">
                        <h3 class="text-2xl font-bold mb-2">Paket Premium</h3>
                        <div class="text-4xl font-bold mb-2">Rp 1.000.000</div>
                        <div class="text-[#E9F6F1]">per tahun</div>
                    </div>
                    <div class="p-8">
                        <ul class="space-y-4 mb-8">
                            <li class="flex items-center">
                                <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                Semua fitur lengkap
                            </li>
                            <li class="flex items-center">
                                <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                Multi-tenant system
                            </li>
                            <li class="flex items-center">
                                <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                Support 24/7
                            </li>
                            <li class="flex items-center">
                                <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                Update gratis
                            </li>
                        </ul>
                        <a href="<?php echo e(route('subscription.subscribe')); ?>" class="block w-full bg-gradient-to-r from-[#1F8F6A] to-[#166B50] text-white text-center py-3 rounded-lg font-semibold hover:from-[#166B50] hover:to-[#0F4C37] transition-all duration-300 transform hover:scale-105">
                            Langganan Sekarang
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-20 bg-gradient-to-r from-[#1F8F6A] to-[#166B50] text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 text-center">
                <div class="rotate-in" data-aos="zoom-in">
                    <div class="text-4xl font-bold mb-2">500+</div>
                    <div class="text-[#E9F6F1]">Perusahaan Aktif</div>
                </div>
                <div class="rotate-in" data-aos="zoom-in" data-aos-delay="100">
                    <div class="text-4xl font-bold mb-2">10K+</div>
                    <div class="text-[#E9F6F1]">Produk Dikelola</div>
                </div>
                <div class="rotate-in" data-aos="zoom-in" data-aos-delay="200">
                    <div class="text-4xl font-bold mb-2">99.9%</div>
                    <div class="text-[#E9F6F1]">Uptime Sistem</div>
                </div>
                <div class="rotate-in" data-aos="zoom-in" data-aos-delay="300">
                    <div class="text-4xl font-bold mb-2">24/7</div>
                    <div class="text-[#E9F6F1]">Support Teknis</div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-gray-900 text-white">
        <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8" data-aos="fade-up">
            <h2 class="text-4xl font-bold mb-6">Siap Tingkatkan Efisiensi Gudang Anda?</h2>
            <p class="text-xl text-gray-300 mb-8">Bergabunglah dengan ratusan perusahaan yang telah mempercayai sistem kami</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="<?php echo e(route('subscription.subscribe')); ?>" class="bg-gradient-to-r from-[#1F8F6A] to-[#166B50] text-white px-8 py-4 rounded-full text-lg font-semibold hover:from-[#166B50] hover:to-[#0F4C37] transition-all duration-300 transform hover:scale-105 shadow-lg pulse-glow">
                    Mulai Langganan Sekarang
                </a>
                <a href="<?php echo e(route('login')); ?>" class="border-2 border-white text-white px-8 py-4 rounded-full text-lg font-semibold hover:bg-white hover:text-gray-900 transition-all duration-300">
                    Masuk ke Akun
                </a>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Hubungi Kami</h2>
                <p class="text-xl text-gray-600">Kami siap membantu Anda dengan pertanyaan atau dukungan</p>
            </div>

            <div class="max-w-2xl mx-auto">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="text-center" data-aos="fade-up" data-aos-delay="100">
                        <div class="w-16 h-16 bg-[#F0FAF7] rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-[#1F8F6A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Telepon</h3>
                        <p class="text-gray-600">085748437132</p>
                    </div>

                    <div class="text-center" data-aos="fade-up" data-aos-delay="200">
                        <div class="w-16 h-16 bg-[#F0FAF7] rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-[#1F8F6A]" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Instagram</h3>
                        <p class="text-gray-600">@StockMasterCompany</p>
                    </div>

                    <div class="text-center" data-aos="fade-up" data-aos-delay="300">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Email</h3>
                        <p class="text-gray-600">stockmastercomp@gmail.com</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gradient-to-br from-[#2c3e50] to-[#34495e] py-12 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center space-x-2 mb-4">
                        <div class="w-8 h-8 bg-gradient-to-r from-[#1F8F6A] to-[#166B50] rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                            </svg>
                        </div>
                        <span class="text-xl font-bold text-white">StockMaster</span>
                    </div>
                    <p class="text-gray-300">Solusi manajemen gudang modern untuk bisnis Anda.</p>
                </div>
                <div>
                    <h3 class="font-semibold text-white mb-4">Produk</h3>
                    <ul class="space-y-2 text-gray-300">
                        <li><a href="#" class="hover:text-[#1F8F6A] transition-colors">Fitur</a></li>
                        <li><a href="#" class="hover:text-[#1F8F6A] transition-colors">Harga</a></li>
                        <li><a href="#" class="hover:text-[#1F8F6A] transition-colors">Demo</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="font-semibold text-white mb-4">Support</h3>
                    <ul class="space-y-2 text-gray-300">
                        <li><a href="#" class="hover:text-[#1F8F6A] transition-colors">Dokumentasi</a></li>
                        <li><a href="#" class="hover:text-[#1F8F6A] transition-colors">FAQ</a></li>
                        <li><a href="#" class="hover:text-[#1F8F6A] transition-colors">Kontak</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="font-semibold text-white mb-4">Perusahaan</h3>
                    <ul class="space-y-2 text-gray-300">
                        <li><a href="#" class="hover:text-[#1F8F6A] transition-colors">Tentang Kami</a></li>
                        <li><a href="#" class="hover:text-[#1F8F6A] transition-colors">Karir</a></li>
                        <li><a href="#" class="hover:text-[#1F8F6A] transition-colors">Blog</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-600 mt-8 pt-8 text-center text-gray-300">
                <p>&copy; 2025 StockMaster. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Initialize AOS
        AOS.init({
            duration: 800,
            once: true,
            offset: 100
        });

        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Navbar background change on scroll
        window.addEventListener('scroll', function() {
            const nav = document.querySelector('nav');
            if (window.scrollY > 50) {
                nav.classList.add('shadow-lg');
            } else {
                nav.classList.remove('shadow-lg');
            }
        });

        // Interactive hover effects
        document.querySelectorAll('.floating-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'scale(1.1) translateY(-10px)';
                this.style.transition = 'all 0.3s ease';
            });
            card.addEventListener('mouseleave', function() {
                this.style.transform = '';
            });
        });

        // Typing effect for hero text
        const heroText = document.querySelector('.gradient-text');
        if (heroText) {
            const text = heroText.textContent;
            heroText.textContent = '';
            let i = 0;
            const typeWriter = () => {
                if (i < text.length) {
                    heroText.textContent += text.charAt(i);
                    i++;
                    setTimeout(typeWriter, 50);
                }
            };
            setTimeout(typeWriter, 1000);
        }

        // Parallax effect for hero background
        window.addEventListener('scroll', function() {
            const scrolled = window.pageYOffset;
            const heroBg = document.querySelector('.hero-bg::before');
            if (heroBg) {
                heroBg.style.transform = `translateY(${scrolled * 0.5}px)`;
            }
        });

        // Demo Mode Functions
        function openDemoModal() {
            console.log('openDemoModal called');
            const modal = document.getElementById('demoModal');
            if (modal) {
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
                console.log('Modal opened successfully');
            } else {
                console.error('Modal element not found!');
            }
        }

        function closeDemoModal() {
            document.getElementById('demoModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function startDemo(role) {
            console.log('Starting demo mode as:', role);
            // Redirect to new demo route
            window.location.href = `/demo/${role}`;
        }

        // Demo Mode: Data is now managed by server-side session
        // No localStorage initialization needed

        // Close modal on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeDemoModal();
            }
        });

        // Close modal on backdrop click
        document.getElementById('demoModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeDemoModal();
            }
        });
    </script>
</body>
</html>






<?php /**PATH D:\Semester 3\PAW\TUBES\tubes-gudang\resources\views/subscription/landing.blade.php ENDPATH**/ ?>