@extends('layouts.app')
@section('title', 'Jelajah Rekrutmen')
@section('page-title', 'Jelajah Organisasi & Rekrutmen')
@section('sidebar')
    @include('layouts.partials.sidebar-mahasiswa')
@endsection

@section('content')
<div class="flex flex-col lg:flex-row gap-8">
    {{-- SIDEBAR FILTER --}}
    <div class="w-full lg:w-80 shrink-0">
        <form action="{{ route('mahasiswa.recruitment.index') }}" method="GET" class="bg-white/80 backdrop-blur-xl border border-slate-200/60 rounded-3xl p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] sticky top-24 lg:top-6">
            <h3 class="text-lg font-black text-slate-900 mb-6 flex items-center gap-3">
                <div class="p-2 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl shadow-md shadow-blue-500/20">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                </div>
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
            <div class="mb-8">
                <label class="block text-sm font-bold text-slate-700 mb-2">Jurusan</label>
                <select name="jurusan" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition-all text-sm font-medium shadow-sm">
                    <option value="">Semua Jurusan</option>
                    @foreach($jurusanList as $jur)
                        <option value="{{ $jur }}" {{ request('jurusan') == $jur ? 'selected' : '' }}>{{ $jur }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="flex-1 py-3 px-4 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold text-sm rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all shadow-md shadow-blue-500/25">Terapkan</button>
                <a href="{{ route('mahasiswa.recruitment.index') }}" class="py-3 px-6 bg-slate-100 text-slate-600 font-bold text-sm rounded-xl hover:bg-slate-200 transition-all text-center">Reset</a>
            </div>
        </form>
    </div>

    {{-- CATALOG GRID --}}
    <div class="flex-1">
        @if($ormawas->isNotEmpty())
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                @foreach($ormawas as $ormawa)
                <a href="{{ route('mahasiswa.recruitment.show', $ormawa->slug) }}" class="group relative flex flex-col h-full bg-white rounded-3xl p-6 transition-all duration-500 hover:-translate-y-2 hover:shadow-[0_20px_40px_-15px_rgba(59,130,246,0.15)] ring-1 ring-slate-200/80 hover:ring-blue-500/30 overflow-hidden">
                    
                    <!-- Decorative background blob -->
                    <div class="absolute -right-10 -top-10 w-32 h-32 bg-gradient-to-br from-blue-100 to-indigo-50 rounded-full blur-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-700 pointer-events-none"></div>

                    <div class="relative z-10 flex flex-col h-full">
                        <div class="flex items-start gap-4 mb-5">
                            @if($ormawa->logo)
                                <div class="relative w-14 h-14 shrink-0 rounded-2xl overflow-hidden bg-white shadow-sm ring-1 ring-slate-100 group-hover:shadow-md transition-shadow duration-300">
                                    <img src="{{ asset('storage/' . $ormawa->logo) }}" alt="Logo {{ $ormawa->nama }}" class="w-full h-full object-contain p-1 transform group-hover:scale-110 transition-transform duration-500">
                                </div>
                            @else
                                <div class="relative w-14 h-14 bg-gradient-to-br from-slate-50 to-slate-100 group-hover:from-blue-50 group-hover:to-indigo-50 rounded-2xl flex items-center justify-center text-slate-700 group-hover:text-blue-700 text-lg font-black shrink-0 shadow-sm ring-1 ring-slate-200 group-hover:ring-blue-200 group-hover:shadow-md transition-all duration-300">
                                    {{ strtoupper(substr($ormawa->nama, 0, 2)) }}
                                </div>
                            @endif

                            <div class="min-w-0 pt-1">
                                <h3 class="text-[17px] font-black text-slate-900 leading-snug group-hover:text-blue-600 transition-colors line-clamp-2" title="{{ $ormawa->nama }}">{{ $ormawa->nama }}</h3>
                                <div class="mt-2 flex items-center gap-2 flex-wrap">
                                    <span class="inline-flex px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-600 group-hover:bg-blue-50 group-hover:text-blue-700 transition-colors">
                                        {{ $ormawa->tingkat }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        @if($ormawa->fakultas_id || $ormawa->jurusan_id)
                        <div class="mb-4 inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 bg-slate-50/80 px-3 py-1.5 rounded-xl border border-slate-100/50">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span class="line-clamp-1">{{ $ormawa->fakultasRel->nama_fakultas ?? '' }} {{ $ormawa->jurusan_id ? ' • ' . $ormawa->jurusanRel->nama_jurusan : '' }}</span>
                        </div>
                        @endif

                        <p class="text-slate-500 text-[13px] leading-relaxed mb-6 flex-grow font-medium line-clamp-3">
                            {{ $ormawa->deskripsi ?: 'Tidak ada deskripsi tersedia untuk organisasi ini.' }}
                        </p>
                        
                        <div class="flex items-center justify-between pt-4 border-t border-slate-100/80 mt-auto">
                            @if($ormawa->recruitments_count > 0)
                                <div class="flex items-center gap-2 text-xs font-bold text-emerald-700 bg-emerald-50 px-3 py-1.5 rounded-full border border-emerald-100/50">
                                    <div class="relative flex h-2 w-2">
                                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                      <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                                    </div>
                                    Buka Rekrutmen
                                </div>
                            @else
                                <div class="flex items-center gap-1.5 text-xs font-bold text-slate-400 bg-slate-50 px-3 py-1.5 rounded-full border border-slate-100">
                                    Tutup
                                </div>
                            @endif
                            
                            <div class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                                <svg class="w-4 h-4 transform group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                            </div>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $ormawas->links() }}
            </div>
        @else
            <div class="text-center py-16 bg-white rounded-2xl border border-slate-200 border-dashed shadow-sm">
                <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-slate-200">
                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">Tidak Ditemukan</h3>
                <p class="text-slate-500 text-sm font-medium">Tidak ada organisasi mahasiswa yang sesuai dengan filter pencarian.</p>
                <a href="{{ route('mahasiswa.recruitment.index') }}" class="inline-block mt-4 px-5 py-2 bg-slate-50 border border-slate-200 text-slate-700 text-sm font-bold rounded-xl hover:bg-slate-100 shadow-sm transition-all">Reset Filter</a>
            </div>
        @endif
    </div>
</div>
@endsection