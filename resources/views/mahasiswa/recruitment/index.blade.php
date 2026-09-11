@extends('layouts.app')
@section('title', 'Jelajah Rekrutmen')
@section('page-title', 'Jelajah Organisasi & Rekrutmen')
@section('sidebar')
    @include('layouts.partials.sidebar-mahasiswa')
@endsection

@section('content')
<div class="flex flex-col lg:flex-row gap-8">
    {{-- SIDEBAR FILTER --}}
    <div class="w-full lg:w-72 shrink-0">
        <form action="{{ route('mahasiswa.recruitment.index') }}" method="GET" class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm sticky top-6">
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
                <button type="submit" class="flex-1 py-3 px-4 bg-slate-800 text-white font-bold text-sm rounded-xl hover:bg-slate-900 transition-all shadow-sm">Terapkan</button>
                <a href="{{ route('mahasiswa.recruitment.index') }}" class="py-3 px-4 bg-slate-100 text-slate-600 font-bold text-sm rounded-xl hover:bg-slate-200 transition-all text-center">Reset</a>
            </div>
        </form>
    </div>

    {{-- CATALOG GRID --}}
    <div class="flex-1">
        @if($ormawas->isNotEmpty())
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                @foreach($ormawas as $ormawa)
                <a href="{{ route('mahasiswa.recruitment.show', $ormawa->slug) }}" class="group flex flex-col h-full bg-white border border-slate-200 rounded-2xl p-6 shadow-sm hover:shadow-lg hover:border-blue-200 hover:-translate-y-1 transition-all duration-300">
                    
                    <div class="flex items-start gap-4 mb-4">
                        @if($ormawa->logo)
                            <img src="{{ asset('storage/' . $ormawa->logo) }}" alt="Logo {{ $ormawa->nama }}" class="w-12 h-12 object-contain rounded-xl border border-slate-100 shrink-0 bg-white">
                        @else
                            <div class="w-12 h-12 bg-gradient-to-br from-slate-100 to-slate-200 rounded-xl flex items-center justify-center text-slate-800 text-base font-black shrink-0 shadow-inner">
                                {{ strtoupper(substr($ormawa->nama, 0, 2)) }}
                            </div>
                        @endif

                        <div class="min-w-0 pt-0.5">
                            <h3 class="text-base font-bold text-slate-900 leading-tight group-hover:text-blue-600 transition-colors line-clamp-2" title="{{ $ormawa->nama }}">{{ $ormawa->nama }}</h3>
                            <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-blue-50 text-blue-700 mt-1 border border-blue-100">
                                {{ $ormawa->tingkat }}
                            </span>
                        </div>
                    </div>
                    
                    @if($ormawa->fakultas || $ormawa->jurusan)
                    <div class="text-[11px] font-semibold text-slate-500 mb-3 bg-slate-50 p-2 rounded-lg border border-slate-100 line-clamp-1">
                        <span class="text-slate-700">Area:</span> 
                        {{ $ormawa->fakultas }} {{ $ormawa->jurusan ? ' • ' . $ormawa->jurusan : '' }}
                    </div>
                    @endif

                    <p class="text-slate-500 text-xs leading-relaxed mb-5 flex-grow font-medium line-clamp-3">
                        {{ $ormawa->deskripsi ?: 'Tidak ada deskripsi tersedia.' }}
                    </p>
                    
                    <div class="flex items-center justify-between pt-4 border-t border-slate-100 mt-auto">
                        <div class="flex items-center gap-1.5 text-[11px] font-bold {{ $ormawa->recruitments_count > 0 ? 'text-green-600 bg-green-50 px-2 py-1 rounded-lg border border-green-100' : 'text-slate-400' }}">
                            @if($ormawa->recruitments_count > 0)
                                <div class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></div>
                                Buka Rekrutmen
                            @else
                                Tutup
                            @endif
                        </div>
                        
                        <div class="text-blue-600 font-bold text-[11px] flex items-center gap-1 group-hover:translate-x-1 transition-transform">
                            Jelajahi
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
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