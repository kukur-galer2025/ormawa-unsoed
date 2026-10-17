@extends('layouts.app')
@section('title', 'Detail Organisasi — ' . $ormawa->nama)
@section('page-title', 'Detail Organisasi')
@section('sidebar')
    @include('layouts.partials.sidebar-pimpinan')
@endsection

@section('content')
<div class="space-y-4 sm:space-y-6"
     x-data="{
        activeTab: 'proker',
        modalOpen: false,
        modalTitle: '',
        modalType: '',
        modalYear: '',
        modalPhoto: '',
        modalDesc: '',
        openModal(type, title, year, photo, desc) {
            this.modalType = type;
            this.modalTitle = title;
            this.modalYear = year;
            this.modalPhoto = photo;
            this.modalDesc = desc;
            this.modalOpen = true;
        }
     }">

    {{-- Breadcrumb & Back --}}
    <div class="flex items-center justify-between gap-2">
        <a href="{{ route('pimpinan.ormawa.index') }}"
           class="inline-flex items-center gap-1.5 text-xs sm:text-sm font-semibold text-slate-500 hover:text-blue-600 transition-colors">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>Kembali ke Data Organisasi</span>
        </a>
        <span class="text-[10px] sm:text-xs px-2.5 py-1 rounded-full bg-slate-100 text-slate-600 font-medium shrink-0">
            Mode Monitoring
        </span>
    </div>

    {{-- Banner & Profile Header (Responsive for mobile & desktop) --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        {{-- Cover Image --}}
        <div class="h-28 sm:h-44 bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 relative overflow-hidden">
            @if($ormawa->cover_photo)
                <img src="{{ asset('storage/' . $ormawa->cover_photo) }}"
                     alt="{{ $ormawa->nama }}"
                     class="w-full h-full object-cover opacity-65">
            @endif
            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 to-transparent"></div>
        </div>

        {{-- Profile Bar --}}
        <div class="p-4 sm:p-6 -mt-10 sm:-mt-16 relative z-10 flex flex-col sm:flex-row sm:items-end justify-between gap-3 sm:gap-4">
            {{-- Logo & Names --}}
            <div class="flex flex-col sm:flex-row sm:items-end gap-3 sm:gap-4">
                <div class="w-16 h-16 sm:w-24 sm:h-24 rounded-2xl bg-white p-1.5 sm:p-2 shadow-md border-2 border-white ring-1 ring-slate-100 flex-shrink-0 flex items-center justify-center overflow-hidden self-start">
                    @if($ormawa->logo)
                        <img src="{{ asset('storage/' . $ormawa->logo) }}" alt="{{ $ormawa->nama }}" class="w-full h-full object-contain">
                    @else
                        <span class="text-xl sm:text-2xl font-black text-indigo-700 uppercase">
                            {{ substr($ormawa->nama, 0, 2) }}
                        </span>
                    @endif
                </div>

                <div class="space-y-1">
                    <div class="flex flex-wrap items-center gap-1.5 sm:gap-2">
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] sm:text-xs font-semibold bg-blue-100 text-blue-700">
                            {{ $ormawa->tingkat ?? 'Ormawa' }}
                        </span>
                        @if($ormawa->is_active)
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] sm:text-xs font-semibold bg-emerald-100 text-emerald-700 flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                                Aktif
                            </span>
                        @else
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] sm:text-xs font-semibold bg-slate-100 text-slate-500">
                                Nonaktif
                            </span>
                        @endif
                    </div>
                    <h1 class="text-base sm:text-2xl font-bold text-slate-800 leading-snug">
                        {{ $ormawa->nama }}
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-500">
                        @if($ormawa->tingkat === 'Universitas')
                            Tingkat Universitas — Universitas Jenderal Soedirman
                        @elseif($ormawa->jurusanRel)
                            Jurusan {{ $ormawa->jurusanRel->nama_jurusan }} ({{ $ormawa->fakultasRel?->nama_fakultas }})
                        @elseif($ormawa->fakultasRel)
                            {{ $ormawa->fakultasRel->nama_fakultas }}
                        @else
                            Universitas Jenderal Soedirman
                        @endif
                    </p>
                </div>
            </div>

            {{-- Social & Contact Quick Links --}}
            <div class="flex items-center gap-2 flex-wrap pt-1 sm:pt-0">
                @if($ormawa->kontak_email)
                <a href="mailto:{{ $ormawa->kontak_email }}"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-medium bg-slate-100 text-slate-700 hover:bg-slate-200 transition-colors">
                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    <span>Email</span>
                </a>
                @endif
                @if($ormawa->kontak_instagram)
                <a href="https://instagram.com/{{ ltrim($ormawa->kontak_instagram, '@') }}"
                   target="_blank" rel="noopener noreferrer"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-medium bg-pink-50 text-pink-700 hover:bg-pink-100 transition-colors max-w-[200px]">
                    <svg class="w-3.5 h-3.5 text-pink-600 shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                    <span class="truncate">{{ '@' . ltrim($ormawa->kontak_instagram, '@') }}</span>
                </a>
                @endif
            </div>
        </div>

        {{-- Mobile-first Metric Stats Bar (Perfect 1px grid without layout break) --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-px bg-slate-200/80 border-t border-slate-100 overflow-hidden text-center">
            <div class="bg-white p-3 sm:p-4">
                <span class="block text-lg sm:text-2xl font-bold text-blue-700 leading-tight">{{ $ormawa->programKerjas->count() }}</span>
                <span class="text-[10px] sm:text-xs text-slate-500 font-medium mt-0.5 block">Program Kerja</span>
            </div>
            <div class="bg-white p-3 sm:p-4">
                <span class="block text-lg sm:text-2xl font-bold text-amber-700 leading-tight">{{ $ormawa->prestasis->count() }}</span>
                <span class="text-[10px] sm:text-xs text-slate-500 font-medium mt-0.5 block">Prestasi Diraih</span>
            </div>
            <div class="bg-white p-3 sm:p-4">
                <span class="block text-lg sm:text-2xl font-bold text-emerald-700 leading-tight">{{ $ormawa->recruitments->count() }}</span>
                <span class="text-[10px] sm:text-xs text-slate-500 font-medium mt-0.5 block">Total Rekrutmen</span>
            </div>
            <div class="bg-white p-3 sm:p-4">
                <span class="block text-lg sm:text-2xl font-bold text-indigo-700 leading-tight">{{ $ormawa->admins->count() }}</span>
                <span class="text-[10px] sm:text-xs text-slate-500 font-medium mt-0.5 block">Admin / Pengurus</span>
            </div>
        </div>
    </div>

    {{-- Tabs Navigation (Sticky on mobile for seamless navigation) --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-1.5 sm:p-2 sticky top-2 z-20 backdrop-blur-md bg-white/95">
        <div class="flex overflow-x-auto no-scrollbar gap-1.5 sm:gap-2">
            <button type="button"
                    @click="activeTab = 'proker'"
                    :class="activeTab === 'proker' ? 'bg-blue-600 text-white shadow-sm shadow-blue-500/20' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100'"
                    class="flex items-center gap-1.5 sm:gap-2 px-3.5 sm:px-4 py-2.5 rounded-xl text-xs sm:text-sm font-semibold whitespace-nowrap transition-all shrink-0">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                <span>Program Kerja ({{ $ormawa->programKerjas->count() }})</span>
            </button>

            <button type="button"
                    @click="activeTab = 'prestasi'"
                    :class="activeTab === 'prestasi' ? 'bg-blue-600 text-white shadow-sm shadow-blue-500/20' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100'"
                    class="flex items-center gap-1.5 sm:gap-2 px-3.5 sm:px-4 py-2.5 rounded-xl text-xs sm:text-sm font-semibold whitespace-nowrap transition-all shrink-0">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                <span>Prestasi ({{ $ormawa->prestasis->count() }})</span>
            </button>

            <button type="button"
                    @click="activeTab = 'profil'"
                    :class="activeTab === 'profil' ? 'bg-blue-600 text-white shadow-sm shadow-blue-500/20' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100'"
                    class="flex items-center gap-1.5 sm:gap-2 px-3.5 sm:px-4 py-2.5 rounded-xl text-xs sm:text-sm font-semibold whitespace-nowrap transition-all shrink-0">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Profil & Visi Misi</span>
            </button>

            <button type="button"
                    @click="activeTab = 'rekrutmen'"
                    :class="activeTab === 'rekrutmen' ? 'bg-blue-600 text-white shadow-sm shadow-blue-500/20' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100'"
                    class="flex items-center gap-1.5 sm:gap-2 px-3.5 sm:px-4 py-2.5 rounded-xl text-xs sm:text-sm font-semibold whitespace-nowrap transition-all shrink-0">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span>Rekrutmen ({{ $ormawa->recruitments->count() }})</span>
            </button>
        </div>
    </div>

    {{-- TAB 1: PROGRAM KERJA --}}
    <div x-show="activeTab === 'proker'" x-transition class="space-y-4">
        @if($ormawa->programKerjas->isEmpty())
            <div class="bg-white rounded-2xl p-8 sm:p-12 text-center border border-slate-100 shadow-sm">
                <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-blue-50 text-blue-500 mx-auto flex items-center justify-center mb-3">
                    <svg class="w-6 h-6 sm:w-7 sm:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                </div>
                <h3 class="text-sm sm:text-base font-bold text-slate-700">Belum Ada Program Kerja</h3>
                <p class="text-xs sm:text-sm text-slate-400 mt-1 max-w-sm mx-auto">
                    Organisasi ini belum mempublikasikan program kerja di sistem.
                </p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3.5 sm:gap-5">
                @foreach($ormawa->programKerjas as $proker)
                <div class="bg-white rounded-2xl border border-slate-100 shadow-xs hover:shadow-md transition-all duration-200 overflow-hidden flex flex-col justify-between">
                    {{-- Foto Proker --}}
                    <div>
                        @if($proker->foto)
                        <div class="h-36 sm:h-44 bg-slate-100 relative overflow-hidden group">
                            <img src="{{ asset('storage/' . $proker->foto) }}"
                                 alt="{{ $proker->nama }}"
                                 class="w-full h-full object-cover">
                        </div>
                        @else
                        <div class="h-24 sm:h-28 bg-gradient-to-r from-blue-500 to-indigo-600 flex items-center justify-center text-white/40">
                            <svg class="w-8 h-8 sm:w-10 sm:h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                        </div>
                        @endif

                        <div class="p-4 sm:p-5 space-y-2">
                            <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-blue-50 text-blue-700 border border-blue-100/80">
                                Program Kerja
                            </span>
                            <h3 class="text-sm sm:text-base font-bold text-slate-800 leading-snug line-clamp-2">
                                {{ $proker->nama }}
                            </h3>
                            <p class="text-xs text-slate-600 leading-relaxed whitespace-pre-line line-clamp-3">
                                {{ $proker->deskripsi ?: 'Tidak ada deskripsi tambahan untuk program kerja ini.' }}
                            </p>
                        </div>
                    </div>

                    {{-- Action Read Full Proposal/Detail --}}
                    <div class="p-4 sm:p-5 pt-0">
                        <button type="button"
                                @click="openModal('Program Kerja', '{{ addslashes($proker->nama) }}', '', '{{ $proker->foto ? asset('storage/' . $proker->foto) : '' }}', `{{ addslashes($proker->deskripsi ?: 'Tidak ada deskripsi tambahan.') }}`)"
                                class="w-full inline-flex items-center justify-center gap-1.5 py-2 px-3 rounded-xl text-xs font-semibold text-blue-700 bg-blue-50/70 hover:bg-blue-600 hover:text-white transition-all">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <span>Baca Detail Lengkap</span>
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- TAB 2: PRESTASI --}}
    <div x-show="activeTab === 'prestasi'" x-transition class="space-y-4">
        @if($ormawa->prestasis->isEmpty())
            <div class="bg-white rounded-2xl p-8 sm:p-12 text-center border border-slate-100 shadow-sm">
                <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-amber-50 text-amber-500 mx-auto flex items-center justify-center mb-3">
                    <svg class="w-6 h-6 sm:w-7 sm:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                </div>
                <h3 class="text-sm sm:text-base font-bold text-slate-700">Belum Ada Catatan Prestasi</h3>
                <p class="text-xs sm:text-sm text-slate-400 mt-1 max-w-sm mx-auto">
                    Organisasi ini belum mencatatkan prestasi capaian di sistem.
                </p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3.5 sm:gap-5">
                @foreach($ormawa->prestasis as $prestasi)
                <div class="bg-white rounded-2xl border border-slate-100 shadow-xs hover:shadow-md transition-all duration-200 overflow-hidden flex flex-col justify-between">
                    {{-- Foto Prestasi --}}
                    <div>
                        @if($prestasi->foto)
                        <div class="h-36 sm:h-44 bg-slate-100 relative overflow-hidden">
                            <img src="{{ asset('storage/' . $prestasi->foto) }}"
                                 alt="{{ $prestasi->judul }}"
                                 class="w-full h-full object-cover">
                        </div>
                        @else
                        <div class="h-24 sm:h-28 bg-gradient-to-r from-amber-500 to-orange-600 flex items-center justify-center text-white/40">
                            <svg class="w-8 h-8 sm:w-10 sm:h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                        </div>
                        @endif

                        <div class="p-4 sm:p-5 space-y-2">
                            <div class="flex items-center justify-between gap-1.5">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-amber-50 text-amber-700 border border-amber-100 flex items-center gap-1">
                                    <svg class="w-3 h-3 text-amber-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    Prestasi
                                </span>
                                @if($prestasi->tahun)
                                <span class="text-[11px] sm:text-xs font-bold text-slate-500">
                                    Tahun {{ $prestasi->tahun }}
                                </span>
                                @endif
                            </div>

                            <h3 class="text-sm sm:text-base font-bold text-slate-800 leading-snug line-clamp-2">
                                {{ $prestasi->judul }}
                            </h3>

                            <p class="text-xs text-slate-600 leading-relaxed whitespace-pre-line line-clamp-3">
                                {{ $prestasi->deskripsi ?: 'Tidak ada deskripsi rinci untuk prestasi ini.' }}
                            </p>
                        </div>
                    </div>

                    {{-- Action Read Full Prestasi Detail --}}
                    <div class="p-4 sm:p-5 pt-0">
                        <button type="button"
                                @click="openModal('Prestasi Ormawa', '{{ addslashes($prestasi->judul) }}', '{{ $prestasi->tahun ? 'Tahun ' . $prestasi->tahun : '' }}', '{{ $prestasi->foto ? asset('storage/' . $prestasi->foto) : '' }}', `{{ addslashes($prestasi->deskripsi ?: 'Tidak ada deskripsi rinci.') }}`)"
                                class="w-full inline-flex items-center justify-center gap-1.5 py-2 px-3 rounded-xl text-xs font-semibold text-amber-700 bg-amber-50/70 hover:bg-amber-600 hover:text-white transition-all">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <span>Baca Detail Prestasi</span>
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- TAB 3: PROFIL LENGKAP & VISI MISI --}}
    <div x-show="activeTab === 'profil'" x-transition class="space-y-4">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-3.5 sm:gap-5">

            {{-- Kolom Kiri: Deskripsi, Visi, Misi --}}
            <div class="lg:col-span-2 space-y-3.5 sm:space-y-4">
                {{-- Deskripsi --}}
                <div class="bg-white rounded-2xl p-4 sm:p-6 border border-slate-100 shadow-xs space-y-2">
                    <h3 class="text-sm sm:text-base font-bold text-slate-800 flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                        <span>Tentang Organisasi</span>
                    </h3>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed whitespace-pre-line">
                        {{ $ormawa->deskripsi ?: 'Belum ada deskripsi profil untuk organisasi ini.' }}
                    </p>
                </div>

                {{-- Visi --}}
                <div class="bg-white rounded-2xl p-4 sm:p-6 border border-slate-100 shadow-xs space-y-2">
                    <h3 class="text-sm sm:text-base font-bold text-slate-800 flex items-center gap-2">
                        <svg class="w-4 h-4 text-amber-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        <span>Visi</span>
                    </h3>
                    <div class="p-3 bg-amber-50/40 rounded-xl border border-amber-100/60">
                        <p class="text-xs sm:text-sm text-slate-700 leading-relaxed whitespace-pre-line italic">
                            {{ $ormawa->visi ?: 'Visi belum diatur oleh admin ormawa.' }}
                        </p>
                    </div>
                </div>

                {{-- Misi --}}
                <div class="bg-white rounded-2xl p-4 sm:p-6 border border-slate-100 shadow-xs space-y-2">
                    <h3 class="text-sm sm:text-base font-bold text-slate-800 flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Misi</span>
                    </h3>
                    <div class="p-3 bg-emerald-50/40 rounded-xl border border-emerald-100/60">
                        <p class="text-xs sm:text-sm text-slate-700 leading-relaxed whitespace-pre-line">
                            {{ $ormawa->misi ?: 'Misi belum diatur oleh admin ormawa.' }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Kolom Kanan: Info Administrasi & Kontak --}}
            <div class="space-y-3.5 sm:space-y-4">
                {{-- Data Administrasi --}}
                <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-100 shadow-xs space-y-3">
                    <h3 class="text-[11px] sm:text-xs font-bold text-slate-400 uppercase tracking-wider">
                        Informasi Struktur
                    </h3>
                    <div class="space-y-2 text-xs sm:text-sm">
                        <div class="flex justify-between items-center py-1.5 border-b border-slate-100 gap-2">
                            <span class="text-slate-500">Tingkat:</span>
                            <span class="font-semibold text-slate-800 text-right">{{ $ormawa->tingkat ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between items-center py-1.5 border-b border-slate-100 gap-2">
                            <span class="text-slate-500">Fakultas:</span>
                            <span class="font-semibold text-slate-800 text-right">{{ $ormawa->fakultasRel?->nama_fakultas ?? 'Tingkat Universitas' }}</span>
                        </div>
                        @if($ormawa->jurusanRel)
                        <div class="flex justify-between items-center py-1.5 border-b border-slate-100 gap-2">
                            <span class="text-slate-500">Jurusan:</span>
                            <span class="font-semibold text-slate-800 text-right">{{ $ormawa->jurusanRel->nama_jurusan }}</span>
                        </div>
                        @endif
                        <div class="flex justify-between items-center py-1.5 gap-2">
                            <span class="text-slate-500">Status:</span>
                            <span class="font-semibold {{ $ormawa->is_active ? 'text-emerald-600' : 'text-red-600' }}">
                                {{ $ormawa->is_active ? 'Aktif Beroperasi' : 'Nonaktif' }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Kontak --}}
                <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-100 shadow-xs space-y-3">
                    <h3 class="text-[11px] sm:text-xs font-bold text-slate-400 uppercase tracking-wider">
                        Kontak & Media Sosial
                    </h3>
                    <div class="space-y-2 text-xs sm:text-sm">
                        <div class="flex items-center gap-2 text-slate-600">
                            <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            <span class="truncate">{{ $ormawa->kontak_email ?: 'Email belum diisi' }}</span>
                        </div>
                        <div class="flex items-center gap-2 text-slate-600">
                            <svg class="w-4 h-4 text-pink-500 shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                            <span class="truncate">{{ $ormawa->kontak_instagram ? '@' . ltrim($ormawa->kontak_instagram, '@') : 'Instagram belum diisi' }}</span>
                        </div>
                    </div>
                </div>

                {{-- Admin Pengurus --}}
                <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-100 shadow-xs space-y-3">
                    <h3 class="text-[11px] sm:text-xs font-bold text-slate-400 uppercase tracking-wider">
                        Admin Pengurus Ormawa
                    </h3>
                    @if($ormawa->admins->isEmpty())
                        <p class="text-xs text-slate-400">Belum ada admin yang ditautkan ke ormawa ini.</p>
                    @else
                        <div class="space-y-2">
                            @foreach($ormawa->admins as $adminUser)
                            <div class="flex items-center gap-2.5 p-2 rounded-xl bg-slate-50 border border-slate-100">
                                <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-700 font-bold flex items-center justify-center text-xs shrink-0">
                                    {{ strtoupper(substr($adminUser->name ?? 'A', 0, 1)) }}
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-xs font-semibold text-slate-800 truncate">{{ $adminUser->name ?? '-' }}</p>
                                    <p class="text-[11px] text-slate-400 truncate">{{ $adminUser->email ?? '-' }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

    {{-- TAB 4: REKRUTMEN --}}
    <div x-show="activeTab === 'rekrutmen'" x-transition class="space-y-4">
        @if($ormawa->recruitments->isEmpty())
            <div class="bg-white rounded-2xl p-8 sm:p-12 text-center border border-slate-100 shadow-sm">
                <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-emerald-50 text-emerald-500 mx-auto flex items-center justify-center mb-3">
                    <svg class="w-6 h-6 sm:w-7 sm:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <h3 class="text-sm sm:text-base font-bold text-slate-700">Belum Ada Rekrutmen</h3>
                <p class="text-xs sm:text-sm text-slate-400 mt-1 max-w-sm mx-auto">
                    Organisasi ini belum memiliki riwayat kegiatan rekrutmen.
                </p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3.5 sm:gap-5">
                @foreach($ormawa->recruitments as $recruitment)
                <div class="bg-white rounded-2xl border border-slate-100 shadow-xs hover:shadow-md transition-all duration-200 overflow-hidden flex flex-col justify-between p-4 sm:p-5 space-y-4">
                    <div class="space-y-2">
                        <div class="flex items-center justify-between gap-2">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] sm:text-xs font-semibold
                                {{ $recruitment->status === 'dibuka' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                {{ ucfirst($recruitment->status) }}
                            </span>
                            <span class="text-[10px] sm:text-[11px] text-slate-400 font-medium">
                                {{ $recruitment->divisions_count }} Divisi
                            </span>
                        </div>

                        <h3 class="text-sm sm:text-base font-bold text-slate-800 line-clamp-2 leading-snug">
                            {{ $recruitment->judul }}
                        </h3>

                        <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed">
                            {{ $recruitment->deskripsi ?: 'Tidak ada deskripsi rekrutmen.' }}
                        </p>
                    </div>

                    <div class="pt-3 border-t border-slate-100 space-y-3">
                        <div class="flex items-center justify-between text-xs text-slate-500">
                            <span class="flex items-center gap-1 font-medium text-slate-700 text-xs">
                                <svg class="w-3.5 h-3.5 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                {{ $recruitment->applications_count }} Pendaftar
                            </span>
                            <span class="text-[11px] text-slate-400">
                                {{ $recruitment->tanggal_buka?->format('d M') }} - {{ $recruitment->tanggal_tutup?->format('d M Y') ?? 'Selesai' }}
                            </span>
                        </div>

                        <a href="{{ route('pimpinan.recruitment.show', $recruitment) }}"
                           class="w-full inline-flex items-center justify-center gap-1.5 py-2.5 px-3 rounded-xl text-xs sm:text-sm font-semibold text-blue-700 bg-blue-50 hover:bg-blue-600 hover:text-white transition-all active:scale-98">
                            <span>Pantau Hasil Seleksi</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Interactive Modal for Proker / Prestasi Details (Mobile bottom-sheet & desktop modal) --}}
    <div x-show="modalOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto bg-black/60 backdrop-blur-xs flex items-end sm:items-center justify-center p-0 sm:p-4"
         style="display: none;"
         @keydown.escape.window="modalOpen = false">

        {{-- Backdrop Click --}}
        <div class="fixed inset-0" @click="modalOpen = false"></div>

        {{-- Modal Card Container --}}
        <div class="relative bg-white w-full sm:max-w-lg rounded-t-3xl sm:rounded-2xl shadow-2xl overflow-hidden max-h-[88vh] flex flex-col z-10 animate-in fade-in slide-in-from-bottom duration-200"
             @click.stop>

            {{-- Mobile Drag Handle --}}
            <div class="w-12 h-1.5 bg-slate-300 rounded-full mx-auto my-2.5 sm:hidden"></div>

            {{-- Header Image (If available) --}}
            <template x-if="modalPhoto">
                <div class="h-44 sm:h-56 w-full bg-slate-100 relative overflow-hidden shrink-0">
                    <img :src="modalPhoto" :alt="modalTitle" class="w-full h-full object-cover">
                    <button type="button"
                            @click="modalOpen = false"
                            class="absolute top-3 right-3 w-8 h-8 rounded-full bg-black/50 text-white flex items-center justify-center hover:bg-black/70 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </template>

            {{-- Modal Body --}}
            <div class="p-4 sm:p-6 overflow-y-auto flex-1 space-y-3">
                <div class="flex items-center justify-between gap-2">
                    <div class="flex items-center gap-1.5">
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold"
                              :class="modalType === 'Program Kerja' ? 'bg-blue-100 text-blue-700' : 'bg-amber-100 text-amber-700'"
                              x-text="modalType"></span>
                        <template x-if="modalYear">
                            <span class="text-xs font-bold text-slate-500" x-text="modalYear"></span>
                        </template>
                    </div>

                    {{-- Close button for non-photo header --}}
                    <template x-if="!modalPhoto">
                        <button type="button"
                                @click="modalOpen = false"
                                class="w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 flex items-center justify-center transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </template>
                </div>

                <h2 class="text-base sm:text-lg font-bold text-slate-800 leading-snug" x-text="modalTitle"></h2>

                <div class="pt-2 border-t border-slate-100">
                    <h4 class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Deskripsi Lengkap</h4>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed whitespace-pre-line" x-text="modalDesc"></p>
                </div>
            </div>

            {{-- Footer Button --}}
            <div class="p-4 border-t border-slate-100 bg-slate-50/70 flex justify-end shrink-0">
                <button type="button"
                        @click="modalOpen = false"
                        class="w-full sm:w-auto px-5 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-xl text-xs sm:text-sm font-semibold transition-all">
                    Tutup
                </button>
            </div>
        </div>
    </div>

</div>
@endsection
