@extends('layouts.app')
@section('title', 'Data Organisasi Mahasiswa')
@section('page-title', 'Data Organisasi Mahasiswa')
@section('sidebar')
    @include('layouts.partials.sidebar-pimpinan')
@endsection

@section('content')
<div class="space-y-5 sm:space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-800">Data Organisasi Mahasiswa</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                @if($user->isWakilDekan())
                    Memantau profil, program kerja, dan prestasi ormawa di lingkungan {{ $user->fakultas?->nama_fakultas ?? 'Fakultas' }}.
                @else
                    Memantau profil, program kerja, dan prestasi seluruh ormawa di Universitas Jenderal Soedirman.
                @endif
            </p>
        </div>
        <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-blue-50 border border-blue-200 text-blue-700 text-xs font-semibold self-start sm:self-auto">
            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            <span>Total: {{ $ormawas->total() }} Organisasi</span>
        </div>
    </div>

    {{-- Search & Filter Bar (Mobile-first responsive) --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 sm:p-5">
        <form method="GET" action="{{ route('pimpinan.ormawa.index') }}" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
            {{-- Search Input --}}
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Cari nama ormawa..."
                       class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all">
            </div>

            {{-- Filter Tingkat (hanya relevan jika wakil rektor yang melihat semua tingkat) --}}
            @if($user->isWakilRektor())
            <div class="sm:w-48">
                <select name="tingkat"
                        onchange="this.form.submit()"
                        class="w-full py-2.5 px-3 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all">
                    <option value="">Semua Tingkat</option>
                    <option value="Universitas" {{ request('tingkat') === 'Universitas' ? 'selected' : '' }}>Universitas</option>
                    <option value="Fakultas" {{ request('tingkat') === 'Fakultas' ? 'selected' : '' }}>Fakultas</option>
                    <option value="Jurusan" {{ request('tingkat') === 'Jurusan' ? 'selected' : '' }}>Jurusan</option>
                </select>
            </div>
            @endif

            {{-- Buttons --}}
            <div class="flex items-center gap-2">
                <button type="submit"
                        class="flex-1 sm:flex-initial inline-flex items-center justify-center gap-1.5 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-semibold shadow-sm shadow-blue-500/20 active:scale-95 transition-all">
                    <span>Cari</span>
                </button>
                @if(request('search') || request('tingkat'))
                <a href="{{ route('pimpinan.ormawa.index') }}"
                   class="inline-flex items-center justify-center px-3 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-sm font-medium transition-all"
                   title="Reset Filter">
                    Reset
                </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Ormawa Grid (1 col mobile, 2 col tablet, 3 col desktop) --}}
    @if($ormawas->isEmpty())
        <div class="bg-white rounded-2xl p-8 sm:p-12 text-center border border-slate-100 shadow-sm">
            <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl bg-slate-100 text-slate-400 mx-auto flex items-center justify-center mb-3">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            </div>
            <h3 class="text-base font-bold text-slate-700">Tidak ada organisasi ditemukan</h3>
            <p class="text-xs sm:text-sm text-slate-400 mt-1 max-w-sm mx-auto">
                Coba gunakan kata kunci pencarian yang lain atau reset filter.
            </p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5">
            @foreach($ormawas as $ormawa)
            <div class="group bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 flex flex-col overflow-hidden">

                {{-- Header Card / Cover --}}
                <div class="h-28 sm:h-32 bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-600 relative overflow-hidden flex-shrink-0">
                    @if($ormawa->cover_photo)
                        <img src="{{ asset('storage/' . $ormawa->cover_photo) }}"
                             alt="{{ $ormawa->nama }}"
                             class="w-full h-full object-cover opacity-60 group-hover:opacity-75 group-hover:scale-105 transition-all duration-300">
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/70 via-slate-900/30 to-transparent"></div>

                    {{-- Badges on top --}}
                    <div class="absolute top-2.5 left-2.5 right-2.5 flex items-center justify-between gap-1">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] sm:text-xs font-semibold backdrop-blur-md bg-white/25 text-white border border-white/20">
                            {{ $ormawa->tingkat ?? 'Ormawa' }}
                        </span>
                        @if($ormawa->is_active)
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] sm:text-xs font-semibold bg-emerald-500/90 text-white backdrop-blur-md">
                                <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                                Aktif
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] sm:text-xs font-semibold bg-slate-700/80 text-slate-300">
                                Nonaktif
                            </span>
                        @endif
                    </div>

                    {{-- Logo & Short Info on Bottom of Cover --}}
                    <div class="absolute bottom-2.5 left-2.5 right-2.5 flex items-center gap-2.5">
                        <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-xl bg-white p-1 shadow-md border border-white/80 flex-shrink-0 flex items-center justify-center overflow-hidden">
                            @if($ormawa->logo)
                                <img src="{{ asset('storage/' . $ormawa->logo) }}" alt="{{ $ormawa->nama }}" class="w-full h-full object-contain">
                            @else
                                <span class="text-sm sm:text-base font-black text-indigo-700 uppercase">
                                    {{ substr($ormawa->nama, 0, 2) }}
                                </span>
                            @endif
                        </div>
                        <div class="min-w-0 flex-1">
                            <h2 class="text-sm sm:text-base font-bold text-white truncate leading-tight drop-shadow-sm">
                                {{ $ormawa->nama }}
                            </h2>
                            <p class="text-[11px] sm:text-xs text-blue-100 truncate">
                                @if($ormawa->tingkat === 'Universitas')
                                    Tingkat Universitas
                                @elseif($ormawa->fakultasRel)
                                    {{ $ormawa->fakultasRel->nama_fakultas }}
                                @else
                                    Universitas Jenderal Soedirman
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Body Info --}}
                <div class="p-4 flex-1 flex flex-col justify-between space-y-3">
                    {{-- Deskripsi singkat --}}
                    <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed">
                        {{ $ormawa->deskripsi ?: 'Tidak ada deskripsi singkat untuk organisasi ini.' }}
                    </p>

                    {{-- 3 Key Metrics: Proker, Prestasi, Rekrutmen --}}
                    <div class="grid grid-cols-3 gap-2 pt-2 border-t border-slate-100 text-center">
                        <div class="bg-blue-50/60 rounded-xl p-2 border border-blue-100/60">
                            <div class="text-base sm:text-lg font-bold text-blue-700 leading-tight">
                                {{ $ormawa->program_kerjas_count }}
                            </div>
                            <div class="text-[10px] sm:text-[11px] font-medium text-blue-600 mt-0.5">
                                Proker
                            </div>
                        </div>

                        <div class="bg-amber-50/60 rounded-xl p-2 border border-amber-100/60">
                            <div class="text-base sm:text-lg font-bold text-amber-700 leading-tight">
                                {{ $ormawa->prestasis_count }}
                            </div>
                            <div class="text-[10px] sm:text-[11px] font-medium text-amber-600 mt-0.5">
                                Prestasi
                            </div>
                        </div>

                        <div class="bg-emerald-50/60 rounded-xl p-2 border border-emerald-100/60">
                            <div class="text-base sm:text-lg font-bold text-emerald-700 leading-tight">
                                {{ $ormawa->recruitments_count }}
                            </div>
                            <div class="text-[10px] sm:text-[11px] font-medium text-emerald-600 mt-0.5">
                                Rekrutmen
                            </div>
                        </div>
                    </div>

                    {{-- Action Button --}}
                    <div class="pt-1">
                        <a href="{{ route('pimpinan.ormawa.show', $ormawa) }}"
                           class="w-full inline-flex items-center justify-center gap-1.5 py-2 px-3 rounded-xl text-xs sm:text-sm font-semibold text-blue-700 bg-blue-50 hover:bg-blue-600 hover:text-white transition-all duration-200 group-hover:bg-blue-600 group-hover:text-white">
                            <span>Lihat Detail Profil & Proker</span>
                            <svg class="w-4 h-4 transition-transform group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>

            </div>
            @endforeach
        </div>

        {{-- Pagination (Mobile responsive) --}}
        <div class="pt-3">
            {{ $ormawas->links() }}
        </div>
    @endif

</div>
@endsection
