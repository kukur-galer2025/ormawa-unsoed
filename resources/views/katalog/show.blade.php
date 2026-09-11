<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $ormawa->nama }} — Katalog Ormawa UNSOED</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 text-slate-800 font-sans selection:bg-blue-200 selection:text-blue-900" style="font-family: 'Plus Jakarta Sans', sans-serif;">

    {{-- NAVBAR --}}
    <nav class="bg-white/70 backdrop-blur-xl border-b border-white/50 sticky top-0 z-50 shadow-sm shadow-slate-200/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <div class="flex items-center gap-4">
                    <a href="{{ route('katalog.index') }}" class="w-10 h-10 bg-white border border-slate-200 rounded-full flex items-center justify-center text-slate-500 hover:text-blue-600 hover:border-blue-200 hover:bg-blue-50 transition-all shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    </a>
                    <div>
                        <p class="text-slate-900 font-extrabold text-lg leading-tight tracking-tight">Katalog Ormawa</p>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    {{-- HEADER PROFILE --}}
    <div class="bg-white border-b border-slate-200 relative overflow-hidden">
        <div class="absolute inset-0 z-0 opacity-40">
            <div class="absolute top-[-50%] right-[-10%] w-[500px] h-[500px] bg-blue-400/20 rounded-full blur-[100px]"></div>
        </div>
        
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-16 relative z-10">
            <div class="flex flex-col md:flex-row items-center md:items-start gap-8 text-center md:text-left">
                @if($ormawa->logo)
                    <img src="{{ asset('storage/' . $ormawa->logo) }}" alt="Logo {{ $ormawa->nama }}" class="w-40 h-40 object-contain rounded-3xl border-4 border-white shadow-2xl shadow-slate-200 bg-white shrink-0">
                @else
                    <div class="w-40 h-40 bg-gradient-to-br from-slate-100 to-slate-200 rounded-3xl border-4 border-white shadow-2xl shadow-slate-200 flex items-center justify-center text-slate-800 text-5xl font-black shrink-0">
                        {{ strtoupper(substr($ormawa->nama, 0, 2)) }}
                    </div>
                @endif
                
                <div class="flex-1 pt-2">
                    <div class="flex flex-wrap gap-2 justify-center md:justify-start mb-3">
                        <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-blue-50 text-blue-700 border border-blue-100">
                            Tingkat {{ $ormawa->tingkat }}
                        </span>
                        @if($ormawa->fakultas)
                            <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-slate-100 text-slate-600 border border-slate-200">
                                {{ $ormawa->fakultas }} {{ $ormawa->jurusan ? ' • ' . $ormawa->jurusan : '' }}
                            </span>
                        @endif
                    </div>
                    
                    <h1 class="text-3xl md:text-5xl font-black text-slate-900 tracking-tight mb-4">{{ $ormawa->nama }}</h1>
                    
                    @if($ormawa->kontak_instagram || $ormawa->kontak_email)
                    <div class="flex flex-wrap items-center justify-center md:justify-start gap-4 text-sm font-medium text-slate-600">
                        @if($ormawa->kontak_instagram)
                            <a href="https://instagram.com/{{ ltrim($ormawa->kontak_instagram, '@') }}" target="_blank" class="flex items-center gap-1.5 hover:text-pink-600 transition-colors">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                                {{ $ormawa->kontak_instagram }}
                            </a>
                        @endif
                        @if($ormawa->kontak_email)
                            <a href="mailto:{{ $ormawa->kontak_email }}" class="flex items-center gap-1.5 hover:text-blue-600 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                {{ $ormawa->kontak_email }}
                            </a>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- MAIN CONTENT --}}
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-16">
        
        {{-- PROFIL & VISI MISI --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-200/60 relative overflow-hidden">
                <div class="absolute top-0 right-0 p-8 text-slate-100">
                    <svg class="w-24 h-24 transform rotate-12" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                </div>
                <h3 class="text-xl font-black text-slate-900 mb-4 relative z-10">Tentang Kami</h3>
                <p class="text-slate-600 leading-relaxed relative z-10 whitespace-pre-line">{{ $ormawa->deskripsi ?: 'Tidak ada deskripsi.' }}</p>
            </div>
            
            <div class="bg-gradient-to-br from-blue-900 to-indigo-900 rounded-[2rem] p-8 shadow-xl shadow-blue-900/20 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 p-8 text-white/5">
                    <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24"><path d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                </div>
                <h3 class="text-xl font-black mb-4 relative z-10">Visi & Misi</h3>
                <p class="text-blue-100 leading-relaxed relative z-10 whitespace-pre-line">{{ $ormawa->visi_misi ?: 'Belum ada visi misi yang dicantumkan.' }}</p>
            </div>
        </div>

        {{-- PROGRAM KERJA --}}
        @if($ormawa->programKerjas->isNotEmpty())
        <div>
            <div class="flex items-center gap-3 mb-8">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white shadow-lg shadow-blue-500/30">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                </div>
                <h2 class="text-3xl font-black text-slate-900 tracking-tight">Program Kerja Unggulan</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($ormawa->programKerjas as $proker)
                <div class="bg-white rounded-[2rem] overflow-hidden shadow-sm hover:shadow-xl transition-shadow duration-300 border border-slate-100 group">
                    @if($proker->foto)
                        <div class="h-48 overflow-hidden bg-slate-100">
                            <img src="{{ asset('storage/' . $proker->foto) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        </div>
                    @else
                        <div class="h-48 bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center">
                            <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                    @endif
                    <div class="p-6">
                        <h4 class="text-lg font-black text-slate-900 mb-2 leading-tight group-hover:text-blue-600 transition-colors">{{ $proker->nama }}</h4>
                        <p class="text-sm text-slate-500 font-medium leading-relaxed">{{ $proker->deskripsi }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- PRESTASI --}}
        @if($ormawa->prestasis->isNotEmpty())
        <div>
            <div class="flex items-center gap-3 mb-8">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center text-white shadow-lg shadow-orange-500/30">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                </div>
                <h2 class="text-3xl font-black text-slate-900 tracking-tight">Prestasi & Pencapaian</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($ormawa->prestasis as $prestasi)
                <div class="flex gap-5 bg-white p-5 rounded-[2rem] shadow-sm hover:shadow-xl transition-shadow duration-300 border border-slate-100 items-start">
                    @if($prestasi->foto)
                        <img src="{{ asset('storage/' . $prestasi->foto) }}" class="w-24 h-24 rounded-2xl object-cover shadow-md shrink-0">
                    @else
                        <div class="w-24 h-24 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-center shrink-0">
                            <svg class="w-8 h-8 text-amber-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                        </div>
                    @endif
                    <div>
                        <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700 mb-2 border border-amber-100">
                            Tahun {{ $prestasi->tahun ?? '-' }}
                        </span>
                        <h4 class="text-lg font-black text-slate-900 mb-1 leading-tight">{{ $prestasi->judul }}</h4>
                        @if($prestasi->deskripsi)
                            <p class="text-sm text-slate-500 font-medium leading-relaxed">{{ $prestasi->deskripsi }}</p>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>

    {{-- FOOTER --}}
    <footer class="bg-white border-t border-slate-200 py-8 mt-12">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-slate-500 font-medium text-sm">© {{ date('Y') }} Portal Organisasi Mahasiswa Universitas Jenderal Soedirman.</p>
        </div>
    </footer>
</body>
</html>
