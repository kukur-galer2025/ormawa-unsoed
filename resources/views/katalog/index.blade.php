<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Ormawa — Universitas Jenderal Soedirman</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 text-slate-800 font-sans selection:bg-blue-200 selection:text-blue-900">

    {{-- NAVBAR --}}
    <nav class="bg-white/70 backdrop-blur-xl border-b border-white/50 sticky top-0 z-50 shadow-sm shadow-slate-200/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                {{-- Logo --}}
                <div class="flex items-center gap-3">
                    <a href="{{ route('home') }}" class="w-12 h-12 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/20 hover:scale-105 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </a>
                    <div>
                        <p class="text-slate-900 font-extrabold text-lg leading-tight tracking-tight">Katalog Ormawa</p>
                        <p class="text-slate-500 text-[11px] uppercase tracking-wider font-bold">UNSOED</p>
                    </div>
                </div>

                {{-- Desktop Nav --}}
                <div class="hidden md:flex items-center gap-6">
                    <a href="{{ route('home') }}" class="text-sm font-semibold text-slate-500 hover:text-blue-600 transition-colors">Beranda</a>
                    <div class="w-px h-6 bg-slate-200"></div>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('login') }}" class="px-6 py-2.5 text-sm font-bold text-slate-700 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition-all shadow-sm">Masuk</a>
                        <a href="{{ route('register') }}" class="px-6 py-2.5 text-sm font-bold bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all shadow-lg shadow-blue-500/25">Daftar</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    {{-- HEADER CONTENT --}}
    <div class="bg-white border-b border-slate-200 pt-12 pb-16 relative overflow-hidden">
        <div class="absolute inset-0 z-0">
            <div class="absolute top-[-50%] right-[-10%] w-[500px] h-[500px] bg-blue-400/10 rounded-full blur-[100px]"></div>
            <div class="absolute bottom-[-50%] left-[-10%] w-[500px] h-[500px] bg-gold-400/10 rounded-full blur-[100px]"></div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <h1 class="text-4xl md:text-5xl font-black text-slate-900 tracking-tight mb-4">Temukan Tempat Berkembangmu</h1>
            <p class="text-lg text-slate-500 font-medium max-w-2xl mx-auto">Jelajahi berbagai organisasi mahasiswa di Universitas Jenderal Soedirman. Temukan yang paling sesuai dengan minat dan bakatmu.</p>
        </div>
    </div>

    {{-- MAIN CONTENT --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="flex flex-col lg:flex-row gap-8">
            
            {{-- SIDEBAR FILTER --}}
            <div class="w-full lg:w-72 shrink-0">
                <form action="{{ route('katalog.index') }}" method="GET" class="bg-white/80 backdrop-blur-xl border border-white rounded-[2rem] p-6 shadow-xl shadow-slate-200/50 sticky top-28">
                    <h3 class="text-lg font-black text-slate-900 mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                        Filter Pencarian
                    </h3>

                    {{-- Search --}}
                    <div class="mb-5">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Cari Nama</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Contoh: BEM, HIMA..." class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition-all text-sm font-medium shadow-sm">
                    </div>

                    {{-- Tingkat --}}
                    <div class="mb-5">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Tingkat</label>
                        <select name="tingkat" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition-all text-sm font-medium shadow-sm">
                            <option value="">Semua Tingkat</option>
                            <option value="Universitas" {{ request('tingkat') == 'Universitas' ? 'selected' : '' }}>Universitas (Pusat)</option>
                            <option value="Fakultas" {{ request('tingkat') == 'Fakultas' ? 'selected' : '' }}>Fakultas</option>
                            <option value="Jurusan" {{ request('tingkat') == 'Jurusan' ? 'selected' : '' }}>Jurusan</option>
                        </select>
                    </div>

                    {{-- Fakultas --}}
                    <div class="mb-5">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Fakultas</label>
                        <select name="fakultas" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition-all text-sm font-medium shadow-sm">
                            <option value="">Semua Fakultas</option>
                            @foreach($fakultasList as $fak)
                                <option value="{{ $fak }}" {{ request('fakultas') == $fak ? 'selected' : '' }}>{{ $fak }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Jurusan --}}
                    <div class="mb-6">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Jurusan</label>
                        <select name="jurusan" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition-all text-sm font-medium shadow-sm">
                            <option value="">Semua Jurusan</option>
                            @foreach($jurusanList as $jur)
                                <option value="{{ $jur }}" {{ request('jurusan') == $jur ? 'selected' : '' }}>{{ $jur }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 py-3 px-4 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold text-sm rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all shadow-md shadow-blue-500/25">Terapkan</button>
                        <a href="{{ route('katalog.index') }}" class="py-3 px-4 bg-slate-100 text-slate-600 font-bold text-sm rounded-xl hover:bg-slate-200 transition-all text-center">Reset</a>
                    </div>
                </form>
            </div>

            {{-- CATALOG GRID --}}
            <div class="flex-1">
                @if($ormawas->isNotEmpty())
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                        @foreach($ormawas as $ormawa)
                        <a href="{{ route('katalog.show', $ormawa->slug) }}" class="group flex flex-col h-full bg-white/80 backdrop-blur-xl border border-white rounded-[2rem] p-6 shadow-xl shadow-slate-200/50 hover:shadow-2xl hover:shadow-blue-500/10 hover:-translate-y-1 transition-all duration-300">
                            
                            <div class="flex items-start gap-4 mb-4">
                                @if($ormawa->logo)
                                    <img src="{{ asset('storage/' . $ormawa->logo) }}" alt="Logo {{ $ormawa->nama }}" class="w-14 h-14 object-contain rounded-xl border border-slate-100 shrink-0 bg-white">
                                @else
                                    <div class="w-14 h-14 bg-gradient-to-br from-slate-100 to-slate-200 rounded-xl flex items-center justify-center text-slate-800 text-lg font-black shrink-0 shadow-inner">
                                        {{ strtoupper(substr($ormawa->nama, 0, 2)) }}
                                    </div>
                                @endif

                                <div class="min-w-0 pt-0.5">
                                    <h3 class="text-lg font-black text-slate-900 leading-tight group-hover:text-blue-600 transition-colors line-clamp-2" title="{{ $ormawa->nama }}">{{ $ormawa->nama }}</h3>
                                    <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-blue-50 text-blue-700 mt-1.5 border border-blue-100">
                                        {{ $ormawa->tingkat }}
                                    </span>
                                </div>
                            </div>
                            
                            @if($ormawa->fakultas_id || $ormawa->jurusan_id)
                            <div class="mb-4 inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 bg-slate-50 px-3 py-1.5 rounded-xl border border-slate-100">
                                <span class="line-clamp-1">{{ $ormawa->fakultasRel->nama_fakultas ?? '' }} {{ $ormawa->jurusan_id ? ' • ' . $ormawa->jurusanRel->nama_jurusan : '' }}</span>
                            </div>
                            @endif

                            <p class="text-slate-500 text-sm leading-relaxed mb-6 flex-grow font-medium line-clamp-3">
                                {{ $ormawa->deskripsi ?: 'Tidak ada deskripsi tersedia untuk organisasi ini.' }}
                            </p>
                            
                            <div class="flex items-center justify-between pt-4 border-t border-slate-100 mt-auto">
                                <div class="flex items-center gap-1.5 text-xs font-bold {{ $ormawa->recruitments_count > 0 ? 'text-green-600 bg-green-50 px-2.5 py-1 rounded-lg border border-green-100' : 'text-slate-400' }}">
                                    @if($ormawa->recruitments_count > 0)
                                        <div class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></div>
                                        Buka Rekrutmen
                                    @else
                                        Tutup
                                    @endif
                                </div>
                                
                                <div class="text-blue-600 font-bold text-sm flex items-center gap-1 group-hover:translate-x-1 transition-transform">
                                    Detail
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>

                    <div class="mt-8">
                        {{ $ormawas->links() }}
                    </div>
                @else
                    <div class="text-center py-24 bg-white/50 backdrop-blur-sm rounded-[2rem] border border-slate-200 border-dashed shadow-sm">
                        <div class="w-20 h-20 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-5 border border-slate-200">
                            <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-2">Tidak Ditemukan</h3>
                        <p class="text-slate-500 font-medium">Tidak ada organisasi mahasiswa yang sesuai dengan filter pencarian Anda.</p>
                        <a href="{{ route('katalog.index') }}" class="inline-block mt-6 px-6 py-2.5 bg-white border border-slate-200 text-slate-700 font-bold rounded-xl hover:bg-slate-50 shadow-sm transition-all">Reset Pencarian</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</body>
</html>
