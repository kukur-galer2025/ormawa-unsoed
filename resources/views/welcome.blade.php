<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Portal Rekrutmen Pengurus Organisasi Mahasiswa Universitas Jenderal Soedirman (UNSOED). Daftar dan kelola seleksi ormawa secara terpadu.">
    <title>Rekrutmen Ormawa UNSOED — Portal Organisasi Mahasiswa</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }
        @keyframes heroSlide {
            0%, 30% { opacity: 1; }
            33.33%, 96.66% { opacity: 0; }
            100% { opacity: 1; }
        }
        .hero-slide-1 { animation: heroSlide 15s infinite; }
        .hero-slide-2 { animation: heroSlide 15s infinite -5s; }
        .hero-slide-3 { animation: heroSlide 15s infinite -10s; }
    </style>
</head>
<body class="min-h-screen flex flex-col justify-between bg-slate-950 text-slate-100 overflow-x-hidden selection:bg-blue-200 selection:text-blue-900 relative">

    {{-- ============================================================ --}}
    {{-- BACKGROUND SLIDESHOW --}}
    {{-- ============================================================ --}}
    <div class="fixed inset-0 z-0 pointer-events-none">
        <div class="absolute inset-0 hero-slide-1">
            <img src="{{ asset('images/hero_bg_1.webp') }}" alt="" class="w-full h-full object-cover">
        </div>
        <div class="absolute inset-0 hero-slide-2">
            <img src="{{ asset('images/hero_bg_2.webp') }}" alt="" class="w-full h-full object-cover">
        </div>
        <div class="absolute inset-0 hero-slide-3">
            <img src="{{ asset('images/hero_bg_3.webp') }}" alt="" class="w-full h-full object-cover">
        </div>
        {{-- Dark gradient overlay for text readability --}}
        <div class="absolute inset-0 bg-gradient-to-b from-slate-950/85 via-slate-900/75 to-slate-950/90 backdrop-blur-[2px]"></div>
    </div>

    {{-- ============================================================ --}}
    {{-- NAVBAR --}}
    {{-- ============================================================ --}}
    <nav class="relative z-50 bg-slate-900/40 backdrop-blur-md border-b border-white/10 sticky top-0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 sm:h-20">
                {{-- Logo --}}
                <div class="flex items-center gap-2.5 sm:gap-3">
                    <div class="w-10 h-10 sm:w-11 sm:h-11 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/30 border border-white/10">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-white font-extrabold text-base sm:text-lg leading-tight tracking-tight">Ormawa UNSOED</p>
                        <p class="text-slate-400 text-[10px] sm:text-[11px] uppercase tracking-wider font-bold">Portal Rekrutmen</p>
                    </div>
                </div>

                {{-- CTA Navbar --}}
                <div class="flex items-center gap-2 sm:gap-3">
                    <a href="{{ route('login') }}" class="px-4 py-2 sm:px-5 sm:py-2.5 text-xs sm:text-sm font-semibold text-white/90 bg-white/10 hover:bg-white/20 border border-white/15 rounded-xl transition-all shadow-sm">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" class="px-4 py-2 sm:px-5 sm:py-2.5 text-xs sm:text-sm font-bold bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white rounded-xl transition-all shadow-lg shadow-blue-600/30">
                        Daftar Sekarang
                    </a>
                </div>
            </div>
        </div>
    </nav>

    {{-- ============================================================ --}}
    {{-- SINGLE SECTION HERO --}}
    {{-- ============================================================ --}}
    <main class="relative z-10 flex-1 flex items-center justify-center px-4 sm:px-6 lg:px-8 py-10 sm:py-16">
        <div class="max-w-4xl mx-auto text-center">
            
            {{-- Status Badge --}}
            <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-white/10 backdrop-blur-md border border-white/20 rounded-full mb-6 shadow-sm">
                <div class="flex h-2 w-2 relative">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-400"></span>
                </div>
                <span class="text-white/90 text-xs font-semibold tracking-wide">Pendaftaran Dibuka — {{ $openCount }} rekrutmen tersedia</span>
            </div>

            {{-- Main Headline --}}
            <h1 class="text-3xl sm:text-5xl lg:text-7xl font-black leading-[1.12] tracking-tight mb-4 sm:mb-6 text-white">
                Sistem Rekrutmen <br>
                <span class="relative inline-block mt-2">
                    <span class="relative z-10 bg-gradient-to-r from-blue-400 via-indigo-300 to-purple-400 bg-clip-text text-transparent">Ormawa UNSOED</span>
                    <div class="absolute -bottom-2 left-0 w-full h-3 sm:h-4 bg-blue-500/30 -rotate-1 skew-x-12 z-0 rounded-full"></div>
                </span>
            </h1>

            {{-- Subtitle --}}
            <p class="text-sm sm:text-lg text-slate-300 max-w-2xl mx-auto mb-8 sm:mb-10 leading-relaxed font-normal px-2">
                Satu portal terpusat untuk mendaftar, mengelola, dan memantau seleksi pengurus organisasi mahasiswa di lingkungan <strong class="text-white font-semibold">Universitas Jenderal Soedirman</strong>.
            </p>

            {{-- Main Call-To-Action (CTA) --}}
            <div class="flex flex-col sm:flex-row items-center justify-center gap-3 sm:gap-4 px-2">
                <a href="{{ route('register') }}" class="group w-full sm:w-auto inline-flex items-center justify-center gap-2.5 px-6 py-3.5 sm:px-8 sm:py-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold text-sm sm:text-base rounded-xl sm:rounded-2xl transition-all duration-300 shadow-xl shadow-blue-600/30 hover:-translate-y-0.5 hover:shadow-blue-500/50">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                    <span>Daftar Sebagai Mahasiswa</span>
                    <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </a>
                <a href="{{ route('login') }}" class="group w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3.5 sm:px-8 sm:py-4 text-white font-bold text-sm sm:text-base bg-white/10 hover:bg-white/20 backdrop-blur-md border border-white/25 rounded-xl sm:rounded-2xl transition-all duration-300 shadow-lg hover:-translate-y-0.5">
                    <svg class="w-5 h-5 text-slate-300 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                    </svg>
                    <span>Masuk ke Dasbor</span>
                </a>
            </div>

            {{-- Compact Stats Bar --}}
            <div class="grid grid-cols-3 gap-2 sm:gap-8 max-w-xl mx-auto mt-10 sm:mt-14 pt-6 sm:pt-8 border-t border-white/15">
                <div class="text-center">
                    <p class="text-2xl sm:text-4xl font-black text-white tracking-tight">{{ $stats['ormawa'] }}</p>
                    <p class="text-[10px] sm:text-xs font-semibold text-white/60 mt-1 uppercase tracking-wider">Ormawa Aktif</p>
                </div>
                <div class="text-center border-x border-white/15">
                    <p class="text-2xl sm:text-4xl font-black text-white tracking-tight">{{ $stats['rekrutmen'] }}</p>
                    <p class="text-[10px] sm:text-xs font-semibold text-white/60 mt-1 uppercase tracking-wider">Rekrutmen</p>
                </div>
                <div class="text-center">
                    <p class="text-2xl sm:text-4xl font-black text-white tracking-tight">{{ $stats['mahasiswa'] }}</p>
                    <p class="text-[10px] sm:text-xs font-semibold text-white/60 mt-1 uppercase tracking-wider">Mahasiswa Terdaftar</p>
                </div>
            </div>

        </div>
    </main>

    {{-- ============================================================ --}}
    {{-- MINIMAL FOOTER --}}
    {{-- ============================================================ --}}
    <footer class="relative z-10 py-4 px-4 text-center border-t border-white/10 bg-slate-950/40 backdrop-blur-md">
        <p class="text-xs text-slate-400 font-medium">
            &copy; {{ date('Y') }} Rekrutmen Ormawa UNSOED &bull; Universitas Jenderal Soedirman. All rights reserved.
        </p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>
</html>
