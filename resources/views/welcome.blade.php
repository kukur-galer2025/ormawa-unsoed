<!DOCTYPE html>
<html lang="id">
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
<body class="min-h-screen bg-slate-50 text-slate-800 overflow-x-hidden selection:bg-blue-200 selection:text-blue-900">

    {{-- ============================================================ --}}
    {{-- NAVBAR --}}
    {{-- ============================================================ --}}
    <nav class="relative z-50 bg-white/70 backdrop-blur-xl border-b border-white/50 sticky top-0 shadow-sm shadow-slate-200/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 sm:h-20">
                {{-- Logo --}}
                <div class="flex items-center gap-2.5 sm:gap-3">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/20">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <div>
                        <p class="text-slate-900 font-extrabold text-base sm:text-lg leading-tight tracking-tight">Ormawa UNSOED</p>
                        <p class="text-slate-500 text-[10px] sm:text-[11px] uppercase tracking-wider font-bold">Portal Rekrutmen</p>
                    </div>
                </div>

                {{-- Desktop Nav --}}
                <div class="hidden md:flex items-center gap-6">
                    <a href="#fitur" class="text-sm font-semibold text-slate-500 hover:text-blue-600 transition-colors">Fitur</a>
                    <a href="#testimoni" class="text-sm font-semibold text-slate-500 hover:text-blue-600 transition-colors">Testimoni</a>
                    <a href="#faq" class="text-sm font-semibold text-slate-500 hover:text-blue-600 transition-colors">FAQ</a>
                    <div class="w-px h-6 bg-slate-200"></div>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('login') }}" class="px-6 py-2.5 text-sm font-bold text-slate-700 bg-white/80 border border-slate-200 rounded-xl hover:bg-slate-50 hover:text-blue-600 transition-all shadow-sm">Masuk</a>
                        <a href="{{ route('register') }}" class="px-6 py-2.5 text-sm font-bold bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all shadow-lg shadow-blue-500/25">Daftar Sekarang</a>
                    </div>
                </div>

                {{-- Mobile Menu Button --}}
                <div class="md:hidden" x-data="{ open: false }">
                    <button @click="open = !open" class="p-2 text-slate-600 hover:text-blue-600 bg-white rounded-xl shadow-sm border border-slate-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <div x-show="open" x-transition @click.away="open = false" class="absolute top-24 left-4 right-4 bg-white/95 backdrop-blur-xl border border-slate-200 rounded-2xl p-4 space-y-2 shadow-2xl shadow-slate-200/50">
                        <a href="#fitur" @click="open = false" class="block px-4 py-3 text-sm font-semibold text-slate-600 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-colors">Fitur</a>
                        <a href="#testimoni" @click="open = false" class="block px-4 py-3 text-sm font-semibold text-slate-600 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-colors">Testimoni</a>
                        <a href="#faq" @click="open = false" class="block px-4 py-3 text-sm font-semibold text-slate-600 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-colors">FAQ</a>
                        <div class="border-t border-slate-100 pt-3 mt-3 space-y-3">
                            <a href="{{ route('login') }}" class="block text-center w-full px-4 py-3 text-sm font-bold text-slate-700 bg-slate-50 border border-slate-200 rounded-xl shadow-sm">Masuk</a>
                            <a href="{{ route('register') }}" class="block text-center w-full px-4 py-3 text-sm font-bold bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl shadow-md">Daftar Sekarang</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    {{-- ============================================================ --}}
    {{-- SECTION 1: HERO WITH SLIDESHOW BACKGROUND --}}
    {{-- ============================================================ --}}
    <section class="relative z-10 overflow-hidden">
        {{-- Background Slideshow --}}
        <div class="absolute inset-0 z-0">
            <div class="absolute inset-0 hero-slide-1">
                <img src="{{ asset('images/hero_bg_1.webp') }}" alt="" class="w-full h-full object-cover">
            </div>
            <div class="absolute inset-0 hero-slide-2">
                <img src="{{ asset('images/hero_bg_2.webp') }}" alt="" class="w-full h-full object-cover">
            </div>
            <div class="absolute inset-0 hero-slide-3">
                <img src="{{ asset('images/hero_bg_3.webp') }}" alt="" class="w-full h-full object-cover">
            </div>
            {{-- Dark overlay for text readability --}}
            <div class="absolute inset-0 bg-gradient-to-b from-slate-900/70 via-slate-900/60 to-slate-900/80"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-5 sm:px-6 lg:px-8 pt-12 pb-10 sm:pt-32 sm:pb-28">
            <div class="text-center max-w-4xl mx-auto">
                {{-- Headline --}}
                <h1 class="text-2xl sm:text-5xl lg:text-7xl font-black leading-[1.15] tracking-tight mb-3 sm:mb-6 text-white">
                    Sistem Rekrutmen <br>
                    <span class="relative inline-block mt-2">
                        <span class="relative z-10 bg-gradient-to-r from-blue-400 via-indigo-400 to-purple-400 bg-clip-text text-transparent">Ormawa UNSOED</span>
                        <div class="absolute -bottom-2 left-0 w-full h-4 bg-blue-500/30 -rotate-1 skew-x-12 z-0 rounded-full"></div>
                    </span>
                </h1>

                {{-- Badge (below headline as inline accent) --}}
                <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-white/10 backdrop-blur-md border border-white/20 rounded-full mb-6 shadow-sm">
                    <div class="flex h-2 w-2 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-green-400"></span>
                    </div>
                    <span class="text-white/80 text-xs font-medium tracking-wide">Pendaftaran Dibuka — {{ $openCount }} rekrutmen tersedia</span>
                </div>

                {{-- Subtitle --}}
                <p class="text-sm sm:text-lg text-slate-300 max-w-2xl mx-auto mb-6 sm:mb-10 leading-relaxed font-medium px-2 sm:px-0">
                    Satu portal terpusat untuk mendaftar, mengelola, dan memantau seleksi pengurus organisasi mahasiswa di lingkungan <strong class="text-white">Universitas Jenderal Soedirman</strong>.
                </p>

                {{-- CTA Buttons --}}
                <div class="flex flex-col sm:flex-row items-center justify-center gap-3 sm:gap-4 px-2 sm:px-0">
                    <a href="{{ route('register') }}" class="group w-full sm:w-auto inline-flex items-center justify-center gap-2 sm:gap-3 px-5 py-3 sm:px-8 sm:py-4 bg-white text-blue-700 font-bold text-sm sm:text-lg rounded-xl sm:rounded-2xl hover:bg-blue-50 transition-all duration-300 shadow-xl shadow-black/20 hover:-translate-y-1">
                        Daftar Sebagai Mahasiswa
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </a>
                    <a href="{{ route('login') }}" class="group w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3 sm:px-8 sm:py-4 text-white font-bold text-sm sm:text-lg bg-white/10 backdrop-blur-sm border border-white/30 rounded-xl sm:rounded-2xl hover:bg-white/20 transition-all duration-300 shadow-lg hover:-translate-y-1">
                        Masuk ke Dasbor
                    </a>
                </div>

                {{-- Stats bar inside hero --}}
                <div class="grid grid-cols-3 gap-2 sm:gap-8 max-w-2xl mx-auto mt-8 sm:mt-14 pt-6 sm:pt-10 border-t border-white/15">
                    <div class="text-center">
                        <p class="text-xl sm:text-4xl font-black text-white">{{ $stats['ormawa'] }}</p>
                        <p class="text-[9px] sm:text-xs font-semibold text-white/50 mt-0.5 sm:mt-1 uppercase tracking-wider sm:tracking-widest">Ormawa Aktif</p>
                    </div>
                    <div class="text-center border-x border-white/15">
                        <p class="text-xl sm:text-4xl font-black text-white">{{ $stats['rekrutmen'] }}</p>
                        <p class="text-[9px] sm:text-xs font-semibold text-white/50 mt-0.5 sm:mt-1 uppercase tracking-wider sm:tracking-widest">Rekrutmen</p>
                    </div>
                    <div class="text-center">
                        <p class="text-xl sm:text-4xl font-black text-white">{{ $stats['mahasiswa'] }}</p>
                        <p class="text-[9px] sm:text-xs font-semibold text-white/50 mt-0.5 sm:mt-1 uppercase tracking-wider sm:tracking-widest">Pendaftar</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================================ --}}
    {{-- SECTION 3: FITUR UNGGULAN --}}
    {{-- ============================================================ --}}
    <section id="fitur" class="relative py-14 sm:py-24 bg-white">
        <div class="absolute inset-0 bg-slate-50/50 rounded-[2rem] sm:rounded-[5rem] mx-3 sm:mx-8"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
            <div class="text-center mb-10 sm:mb-16">
                <span class="text-blue-600 font-bold uppercase tracking-wider text-xs sm:text-sm">Mengapa Platform Ini?</span>
                <h2 class="text-2xl sm:text-4xl font-black text-slate-900 tracking-tight mt-2">Lebih Mudah, Cepat & Transparan</h2>
                <p class="mt-3 sm:mt-4 text-slate-500 text-sm sm:text-lg max-w-2xl mx-auto font-medium">Platform ini dirancang khusus untuk menyederhanakan proses rekrutmen organisasi mahasiswa.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 sm:gap-8 lg:gap-12">
                {{-- Feature 1 --}}
                <div class="group relative">
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-100 to-transparent rounded-2xl sm:rounded-3xl blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="relative bg-white border border-slate-100 rounded-2xl sm:rounded-3xl p-6 sm:p-8 shadow-lg shadow-slate-200/40 hover:shadow-xl hover:shadow-blue-500/10 transition-all duration-300 h-full">
                        <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center mb-5 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 mb-2 group-hover:text-blue-600 transition-colors">Pendaftaran Satu Pintu</h3>
                        <p class="text-slate-500 text-sm leading-relaxed font-medium">Isi profil satu kali, daftar ke berbagai rekrutmen ormawa hanya dengan sekali klik.</p>
                    </div>
                </div>

                {{-- Feature 2 --}}
                <div class="group relative">
                    <div class="absolute inset-0 bg-gradient-to-br from-green-100 to-transparent rounded-2xl sm:rounded-3xl blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="relative bg-white border border-slate-100 rounded-2xl sm:rounded-3xl p-6 sm:p-8 shadow-lg shadow-slate-200/40 hover:shadow-xl hover:shadow-green-500/10 transition-all duration-300 h-full">
                        <div class="w-14 h-14 bg-green-50 rounded-2xl flex items-center justify-center mb-5 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 mb-2 group-hover:text-green-600 transition-colors">Katalog Ormawa</h3>
                        <p class="text-slate-500 text-sm leading-relaxed font-medium">Jelajahi seluruh organisasi mahasiswa lengkap dengan profil, proker, dan prestasi.</p>
                    </div>
                </div>

                {{-- Feature 3 --}}
                <div class="group relative">
                    <div class="absolute inset-0 bg-gradient-to-br from-indigo-100 to-transparent rounded-2xl sm:rounded-3xl blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="relative bg-white border border-slate-100 rounded-2xl sm:rounded-3xl p-6 sm:p-8 shadow-lg shadow-slate-200/40 hover:shadow-xl hover:shadow-indigo-500/10 transition-all duration-300 h-full">
                        <div class="w-14 h-14 bg-indigo-50 rounded-2xl flex items-center justify-center mb-5 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 mb-2 group-hover:text-indigo-600 transition-colors">Pantau Status Real-time</h3>
                        <p class="text-slate-500 text-sm leading-relaxed font-medium">Lacak progress pendaftaran dari seleksi hingga pengumuman di dashboard personal.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================================ --}}
    {{-- SECTION 4: TESTIMONI MAHASISWA --}}
    {{-- ============================================================ --}}
    <section id="testimoni" class="relative py-14 sm:py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10 sm:mb-16">
                <span class="text-blue-600 font-bold uppercase tracking-wider text-xs sm:text-sm">Apa Kata Mereka?</span>
                <h2 class="text-2xl sm:text-4xl font-black text-slate-900 tracking-tight mt-2">Testimoni Mahasiswa</h2>
                <p class="mt-3 sm:mt-4 text-slate-500 text-sm sm:text-lg max-w-2xl mx-auto font-medium">Pendapat mahasiswa yang sudah merasakan kemudahan portal rekrutmen ini.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 sm:gap-8">
                @php
                    $testimonials = [
                        [
                            'name' => 'Andi Pratama',
                            'role' => 'Pengurus BEM UNSOED',
                            'faculty' => 'Fakultas Teknik',
                            'quote' => 'Dulu mendaftar ke ormawa harus datang langsung dan antri. Sekarang tinggal klik dari laptop, sangat efisien dan prosesnya transparan!',
                            'color' => 'blue',
                        ],
                        [
                            'name' => 'Citra Dewi',
                            'role' => 'Anggota HMPS Informatika',
                            'faculty' => 'Fakultas MIPA',
                            'quote' => 'Fitur katalog ormawa sangat membantu saya menemukan organisasi yang sesuai minat. Bisa langsung lihat proker dan prestasinya sebelum mendaftar.',
                            'color' => 'indigo',
                        ],
                        [
                            'name' => 'Fajar Hidayat',
                            'role' => 'Pengurus DPM UNSOED',
                            'faculty' => 'Fakultas Hukum',
                            'quote' => 'Proses seleksi di portal ini sangat transparan. Status pendaftaran bisa langsung dipantau di dashboard, tidak perlu menunggu pengumuman manual lagi.',
                            'color' => 'purple',
                        ],
                    ];
                @endphp

                @foreach($testimonials as $t)
                <div class="bg-white/80 backdrop-blur-xl border border-white rounded-2xl sm:rounded-[2rem] p-6 sm:p-8 shadow-xl shadow-slate-200/50 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 flex flex-col">
                    {{-- Quote Icon --}}
                    <div class="mb-4 sm:mb-6">
                        <svg class="w-8 h-8 sm:w-10 sm:h-10 text-{{ $t['color'] }}-200" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10H14.017zM0 21v-7.391c0-5.704 3.731-9.57 8.983-10.609L9.978 5.151C7.546 6.068 5.983 8.789 5.983 11h4v10H0z"/></svg>
                    </div>

                    {{-- Quote Text --}}
                    <p class="text-slate-600 text-sm sm:text-base font-medium leading-relaxed mb-6 sm:mb-8 flex-grow italic">"{{ $t['quote'] }}"</p>

                    {{-- Author --}}
                    <div class="flex items-center gap-3 sm:gap-4 pt-5 sm:pt-6 border-t border-slate-100">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-{{ $t['color'] }}-500 to-{{ $t['color'] }}-700 rounded-xl flex items-center justify-center text-white text-xs sm:text-sm font-black shadow-md shrink-0">
                            {{ strtoupper(substr($t['name'], 0, 1)) . strtoupper(substr(explode(' ', $t['name'])[1] ?? '', 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <p class="text-slate-900 font-bold text-sm sm:text-base">{{ $t['name'] }}</p>
                            <p class="text-slate-500 text-[11px] sm:text-xs font-medium truncate">{{ $t['role'] }} • {{ $t['faculty'] }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============================================================ --}}
    {{-- SECTION 5: FAQ --}}
    {{-- ============================================================ --}}
    <section id="faq" class="relative py-14 sm:py-24 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10 sm:mb-16">
                <span class="text-blue-600 font-bold uppercase tracking-wider text-xs sm:text-sm">Punya Pertanyaan?</span>
                <h2 class="text-2xl sm:text-4xl font-black text-slate-900 tracking-tight mt-2">Pertanyaan yang Sering Diajukan</h2>
                <p class="mt-3 sm:mt-4 text-slate-500 text-sm sm:text-lg max-w-2xl mx-auto font-medium">Berikut jawaban atas pertanyaan yang sering ditanyakan oleh mahasiswa.</p>
            </div>

            @php
                $faqs = [
                    [
                        'q' => 'Siapa saja yang bisa mendaftar di portal ini?',
                        'a' => 'Seluruh mahasiswa aktif Universitas Jenderal Soedirman (UNSOED) dari semua fakultas dan jurusan dapat mendaftar. Anda hanya perlu membuat akun menggunakan data diri dan NIM aktif.',
                    ],
                    [
                        'q' => 'Bagaimana cara mengetahui rekrutmen yang sedang dibuka?',
                        'a' => 'Setelah login, Anda bisa mengakses menu "Jelajah Rekrutmen" untuk melihat seluruh organisasi mahasiswa. Di halaman detail setiap ormawa, akan terlihat rekrutmen mana yang sedang dibuka beserta batas akhir pendaftarannya.',
                    ],
                    [
                        'q' => 'Apakah saya bisa mendaftar ke lebih dari satu organisasi?',
                        'a' => 'Ya, tentu saja! Anda bebas mendaftar ke beberapa rekrutmen ormawa sekaligus. Status masing-masing pendaftaran bisa Anda pantau secara terpisah di halaman Riwayat Pendaftaran.',
                    ],

                    [
                        'q' => 'Bagaimana cara mengetahui hasil seleksi?',
                        'a' => 'Hasil seleksi akan diperbarui secara real-time di dashboard Anda. Anda bisa melihat status pendaftaran (diterima, ditolak, atau masih dalam proses) langsung di halaman Riwayat Pendaftaran tanpa perlu menunggu pengumuman manual.',
                    ],
                    [
                        'q' => 'Apakah data pribadi saya aman?',
                        'a' => 'Ya. Data Anda hanya digunakan untuk keperluan proses rekrutmen dan hanya dapat diakses oleh admin ormawa terkait serta superadmin sistem. Kami menjaga privasi data Anda sesuai standar keamanan yang berlaku.',
                    ],
                ];
            @endphp

            <div class="space-y-3 sm:space-y-4" x-data="{ active: null }">
                @foreach($faqs as $i => $faq)
                <div class="bg-white border border-slate-200 rounded-xl sm:rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                    <button 
                        @click="active = active === {{ $i }} ? null : {{ $i }}" 
                        class="w-full flex items-center justify-between px-4 py-4 sm:px-6 sm:py-5 text-left focus:outline-none group"
                    >
                        <span class="text-sm sm:text-base font-bold text-slate-900 pr-3 sm:pr-4 group-hover:text-blue-600 transition-colors">{{ $faq['q'] }}</span>
                        <div class="shrink-0 w-7 h-7 sm:w-8 sm:h-8 rounded-lg sm:rounded-xl flex items-center justify-center transition-all duration-300" :class="active === {{ $i }} ? 'bg-blue-600 text-white rotate-45' : 'bg-slate-100 text-slate-500'">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                        </div>
                    </button>
                    <div 
                        x-show="active === {{ $i }}" 
                        x-transition:enter="transition-all ease-out duration-300"
                        x-transition:enter-start="opacity-0 max-h-0"
                        x-transition:enter-end="opacity-100 max-h-96"
                        x-transition:leave="transition-all ease-in duration-200"
                        x-transition:leave-start="opacity-100 max-h-96"
                        x-transition:leave-end="opacity-0 max-h-0"
                        class="overflow-hidden"
                        style="display: none;"
                    >
                        <div class="px-4 pb-4 sm:px-6 sm:pb-5 text-slate-500 text-sm sm:text-base font-medium leading-relaxed border-t border-slate-100 pt-3 sm:pt-4">
                            {{ $faq['a'] }}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- CTA di bawah FAQ --}}
            <div class="mt-10 sm:mt-16 text-center bg-gradient-to-br from-blue-900 to-indigo-900 rounded-2xl sm:rounded-[2rem] p-8 sm:p-12 shadow-xl relative overflow-hidden">
                <div class="absolute inset-0 overflow-hidden">
                    <div class="absolute -top-[50%] left-[20%] w-[600px] h-[600px] bg-blue-500/10 rounded-full blur-[120px]"></div>
                </div>
                <div class="relative z-10">
                    <h3 class="text-2xl sm:text-4xl font-black text-white mb-3 sm:mb-4 tracking-tight">Siap Memulai Perjalananmu?</h3>
                    <p class="text-blue-200 text-sm sm:text-lg mb-6 sm:mb-8 font-medium max-w-xl mx-auto">Tingkatkan soft skill, bangun relasi, dan berikan dampak positif untuk almamater.</p>
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center gap-2 sm:gap-3 px-7 py-3 sm:px-10 sm:py-4 bg-white text-blue-700 font-bold text-base sm:text-lg rounded-xl sm:rounded-2xl hover:bg-blue-50 transition-all shadow-xl hover:-translate-y-1 hover:shadow-2xl">
                        Daftar Sekarang Juga
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================================ --}}
    {{-- FOOTER --}}
    {{-- ============================================================ --}}
    <footer class="bg-slate-900 py-8 sm:py-12 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 bg-slate-800 rounded-xl flex items-center justify-center border border-slate-700">
                    <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <div>
                    <span class="text-white font-bold block">Rekrutmen Ormawa UNSOED</span>
                    <span class="text-slate-500 text-sm font-medium">Universitas Jenderal Soedirman</span>
                </div>
            </div>
            <p class="text-slate-500 text-sm font-medium">
                &copy; {{ date('Y') }} All rights reserved.
            </p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>
</html>
