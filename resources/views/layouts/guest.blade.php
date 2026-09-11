<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistem Informasi Rekrutmen Pengurus Organisasi Mahasiswa UNSOED">
    <title>@yield('title', 'Auth') — Rekrutmen Ormawa UNSOED</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 flex font-sans text-slate-800 selection:bg-blue-200 selection:text-blue-900" style="font-family: 'Plus Jakarta Sans', sans-serif;">
    
    {{-- Decorative Background Gradients for Mobile (since image is hidden on mobile) --}}
    <div class="fixed inset-0 overflow-hidden pointer-events-none z-0 lg:hidden">
        <div class="absolute top-[-20%] right-[-10%] w-[600px] h-[600px] bg-blue-400/15 rounded-full blur-[120px] animate-pulse" style="animation-duration: 8s;"></div>
        <div class="absolute bottom-[-20%] left-[-10%] w-[600px] h-[600px] bg-gold-400/15 rounded-full blur-[120px] animate-pulse" style="animation-duration: 12s;"></div>
    </div>

    {{-- LEFT SIDE (IMAGE) --}}
    <div class="hidden lg:flex lg:w-1/2 relative bg-slate-900 overflow-hidden flex-col justify-end p-12 lg:p-16">
        {{-- Background Image --}}
        @hasSection('left-image')
            @yield('left-image')
        @else
            <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?q=80&w=2851&auto=format&fit=crop" alt="Students Gathering" class="absolute inset-0 w-full h-full object-cover opacity-50 mix-blend-overlay scale-105 hover:scale-100 transition-transform duration-[10s]">
        @endif
        
        {{-- Overlay Gradient --}}
        <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/60 to-transparent"></div>

        <div class="relative z-10 max-w-xl">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-white/10 backdrop-blur-md rounded-2xl border border-white/20 shadow-2xl mb-8">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            
            <h1 class="text-4xl lg:text-5xl font-black text-white tracking-tight mb-4 leading-tight">
                Rekrutmen Ormawa<br>
                <span class="text-blue-400">UNSOED</span>
            </h1>
            <p class="text-slate-300 text-lg font-medium leading-relaxed max-w-md">
                Bergabunglah dengan organisasi mahasiswa untuk mengembangkan potensi diri dan memperluas relasi di Universitas Jenderal Soedirman.
            </p>
        </div>
    </div>

    {{-- RIGHT SIDE (FORM) --}}
    <div class="w-full lg:w-1/2 flex flex-col justify-center relative z-10 px-6 py-12 lg:px-16 xl:px-24">
        
        <div class="w-full max-w-md mx-auto">
            {{-- Mobile Logo (Only visible on small screens) --}}
            <div class="lg:hidden text-center mb-8">
                <a href="{{ route('home') }}" class="inline-block group">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-2xl shadow-xl shadow-blue-500/20 mb-4 group-hover:scale-105 group-hover:shadow-blue-500/30 transition-all duration-300">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                </a>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">Rekrutmen Ormawa</h1>
                <p class="text-slate-500 text-xs mt-1 font-medium">Universitas Jenderal Soedirman</p>
            </div>

            {{-- Back to Home Link (Desktop only) --}}
            <a href="{{ route('home') }}" class="hidden lg:inline-flex items-center gap-1.5 text-sm font-semibold text-slate-400 hover:text-blue-600 transition-colors mb-10 group">
                <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Beranda
            </a>

            {{-- Notifications --}}
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-2xl text-sm flex items-center gap-3 font-medium">
                    <svg class="w-5 h-5 shrink-0 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-2xl text-sm flex items-center gap-3 font-medium">
                    <svg class="w-5 h-5 shrink-0 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ session('error') }}
                </div>
            @endif
            
            {{-- Form Content Injection --}}
            <div class="bg-white/80 lg:bg-transparent lg:backdrop-blur-none lg:shadow-none lg:border-none lg:p-0 backdrop-blur-xl border border-white rounded-[2rem] shadow-2xl shadow-slate-200/60 p-8 sm:p-10 relative">
                @yield('content')
            </div>

            {{-- Back to Home Link (Mobile only) --}}
            <div class="lg:hidden text-center mt-8">
                <a href="{{ route('home') }}" class="text-sm font-semibold text-slate-500 hover:text-blue-600 transition-colors inline-flex items-center gap-1.5 group">
                    <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Kembali ke Beranda
                </a>
            </div>
            
        </div>
    </div>
</body>
</html>