@extends('layouts.app')
@section('title', 'Jelajah Rekrutmen')
@section('page-title', 'Jelajah Organisasi & Rekrutmen')
@section('sidebar')
    @include('layouts.partials.sidebar-mahasiswa')
@endsection

@section('content')
<div class="flex flex-col lg:flex-row gap-8">
    {{-- SIDEBAR FILTER --}}
    <div class="w-full lg:w-80 shrink-0" x-data="filterPicker()">
        <form action="{{ route('mahasiswa.recruitment.index') }}" method="GET" class="bg-white/80 backdrop-blur-xl border border-slate-200/60 rounded-3xl p-4 sm:p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] sticky top-24 lg:top-6">
            <h3 class="text-lg font-black text-slate-900 mb-6 flex items-center gap-3">
                <div class="p-2 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl shadow-md shadow-blue-500/20">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                </div>
                Filter Pencarian
            </h3>

            {{-- Search --}}
            <div class="mb-5">
                <label class="block text-sm font-bold text-slate-700 mb-2">Cari Nama</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Contoh: BEM, HIMA..." class="w-full px-4 py-2.5 sm:py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition-all text-sm font-medium shadow-sm">
            </div>

            {{-- Tingkat --}}
            <div class="mb-5">
                <label class="block text-sm font-bold text-slate-700 mb-2">Tingkat</label>
                <input type="hidden" name="tingkat" :value="selectedTingkat">
                <button type="button" @click="openModal('tingkat')"
                        class="w-full flex items-center justify-between px-4 py-2.5 sm:py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium shadow-sm hover:border-blue-400 hover:ring-2 hover:ring-blue-500/20 transition-all text-left"
                        :class="selectedTingkatName ? 'text-slate-900' : 'text-slate-400'">
                    <span class="truncate" x-text="selectedTingkatName || 'Semua Tingkat'"></span>
                    <svg class="w-4 h-4 text-slate-400 shrink-0 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
            </div>

            {{-- Fakultas (Modal Picker) — hidden when Universitas --}}
            <div class="mb-5" x-show="showFakultas" x-transition>
                <label class="block text-sm font-bold text-slate-700 mb-2">Fakultas</label>
                <input type="hidden" name="fakultas" :value="showFakultas ? selectedFakultas : ''">
                <button type="button" @click="openModal('fakultas')"
                        class="w-full flex items-center justify-between px-4 py-2.5 sm:py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium shadow-sm hover:border-blue-400 hover:ring-2 hover:ring-blue-500/20 transition-all text-left"
                        :class="selectedFakultasName ? 'text-slate-900' : 'text-slate-400'">
                    <span class="truncate" x-text="selectedFakultasName || 'Semua Fakultas'"></span>
                    <svg class="w-4 h-4 text-slate-400 shrink-0 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
            </div>

            {{-- Jurusan (Modal Picker) — only when Jurusan or Semua --}}
            <div class="mb-8" x-show="showJurusan" x-transition>
                <label class="block text-sm font-bold text-slate-700 mb-2">Jurusan</label>
                <input type="hidden" name="jurusan" :value="showJurusan ? selectedJurusan : ''">
                <button type="button" @click="selectedFakultas ? openModal('jurusan') : null"
                        class="w-full flex items-center justify-between px-4 py-2.5 sm:py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium shadow-sm transition-all text-left"
                        :class="[
                            selectedJurusanName ? 'text-slate-900' : 'text-slate-400',
                            selectedFakultas ? 'hover:border-blue-400 hover:ring-2 hover:ring-blue-500/20 cursor-pointer' : 'opacity-50 cursor-not-allowed'
                        ]">
                    <span class="truncate" x-text="selectedJurusanName || (selectedFakultas ? 'Semua Jurusan' : 'Pilih Fakultas dulu')"></span>
                    <svg class="w-4 h-4 text-slate-400 shrink-0 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="flex-1 py-3 px-4 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold text-sm rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all shadow-md shadow-blue-500/25">Terapkan</button>
                <a href="{{ route('mahasiswa.recruitment.index') }}" class="py-3 px-6 bg-slate-100 text-slate-600 font-bold text-sm rounded-xl hover:bg-slate-200 transition-all text-center">Reset</a>
            </div>
        </form>

        {{-- ==================== MODAL PICKER ==================== --}}
        <template x-teleport="body">
            <div x-show="modalOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                 class="fixed inset-0 z-[9999] flex items-center justify-center p-4" style="display:none;">
                {{-- Backdrop --}}
                <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="closeModal()"></div>
                
                {{-- Modal Content --}}
                <div x-show="modalOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                     class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm max-h-[70vh] flex flex-col overflow-hidden">
                    
                    {{-- Modal Header --}}
                    <div class="p-4 border-b border-slate-100 flex items-center justify-between shrink-0">
                        <h3 class="font-bold text-slate-800 text-base" x-text="modalType === 'fakultas' ? 'Pilih Fakultas' : (modalType === 'jurusan' ? 'Pilih Jurusan' : 'Pilih Tingkat')"></h3>
                        <button type="button" @click="closeModal()" class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 flex items-center justify-center transition-colors">
                            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                    
                    {{-- Search --}}
                    <div class="p-3 border-b border-slate-100 shrink-0">
                        <div class="relative">
                            <input type="text" x-model="modalSearch" x-ref="modalSearchInput" placeholder="Cari..."
                                   class="w-full pl-9 pr-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition-all">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Options List --}}
                    <div class="overflow-y-auto flex-1 p-2">
                        {{-- "Semua" option --}}
                        <button type="button" @click="selectItem(null)"
                                class="w-full text-left px-4 py-3 rounded-xl text-sm font-medium transition-all duration-150 flex items-center justify-between"
                                :class="!isSelected(null) ? 'text-slate-500 hover:bg-slate-50 border border-transparent' : 'bg-blue-50 text-blue-700 border border-blue-200'">
                            <span x-text="modalType === 'fakultas' ? 'Semua Fakultas' : (modalType === 'jurusan' ? 'Semua Jurusan' : 'Semua Tingkat')"></span>
                            <svg x-show="isSelected(null)" class="w-5 h-5 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        </button>

                        <template x-for="item in filteredModalItems" :key="item.id">
                            <button type="button" @click="selectItem(item)"
                                    class="w-full text-left px-4 py-3 rounded-xl text-sm font-medium transition-all duration-150 flex items-center justify-between"
                                    :class="isSelected(item) ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'text-slate-700 hover:bg-slate-50 border border-transparent'">
                                <span x-text="modalType === 'fakultas' ? item.nama_fakultas : (modalType === 'jurusan' ? item.nama_jurusan : item.nama)"></span>
                                <svg x-show="isSelected(item)" class="w-5 h-5 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </button>
                        </template>
                        <div x-show="filteredModalItems.length === 0" class="text-center py-8 text-slate-400 text-sm">
                            Tidak ditemukan
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>

    {{-- CATALOG GRID --}}
    <div class="flex-1">
        @if($ormawas->isNotEmpty())
            {{-- Results count --}}
            <div class="mb-6 flex items-center justify-between">
                <p class="text-sm font-semibold text-slate-500">
                    Menampilkan <span class="text-slate-800">{{ $ormawas->total() }}</span> organisasi
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                @foreach($ormawas as $ormawa)
                @php
                    $gradients = [
                        'Universitas' => 'from-blue-600 via-indigo-600 to-violet-700',
                        'Fakultas'    => 'from-emerald-600 via-teal-600 to-cyan-700',
                        'Jurusan'     => 'from-amber-500 via-orange-500 to-rose-600',
                    ];
                    $gradient = $gradients[$ormawa->tingkat] ?? 'from-slate-600 via-slate-700 to-slate-800';
                    $badgeColors = [
                        'Universitas' => 'bg-white/20 text-white',
                        'Fakultas'    => 'bg-white/20 text-white',
                        'Jurusan'     => 'bg-white/20 text-white',
                    ];
                    $badgeColor = $badgeColors[$ormawa->tingkat] ?? 'bg-white/20 text-white';
                @endphp
                <a href="{{ route('mahasiswa.recruitment.show', $ormawa->slug) }}" 
                   class="group relative flex flex-col bg-white rounded-2xl overflow-hidden transition-all duration-500 hover:-translate-y-1.5 hover:shadow-[0_25px_50px_-12px_rgba(0,0,0,0.15)] ring-1 ring-slate-200/80 hover:ring-transparent">
                    
                    {{-- Hero Banner --}}
                    <div class="relative w-full aspect-[21/9] sm:aspect-video {{ $ormawa->cover_photo ? 'bg-slate-100' : 'bg-gradient-to-br ' . $gradient }}">
                        @if($ormawa->cover_photo)
                            <img src="{{ asset('storage/' . $ormawa->cover_photo) }}" alt="Cover {{ $ormawa->nama }}" class="absolute inset-0 w-full h-full object-cover">
                            <div class="absolute inset-0 bg-gradient-to-b from-black/40 via-transparent to-transparent"></div>
                        @endif
                        {{-- Abstract decorative shapes (only show if no cover photo for cleaner look) --}}
                        @if(!$ormawa->cover_photo)
                        <div class="absolute inset-0 overflow-hidden">
                            <div class="absolute -right-8 -top-8 w-32 h-32 bg-white/10 rounded-full blur-xl"></div>
                            <div class="absolute -left-6 bottom-0 w-24 h-24 bg-white/5 rounded-full blur-lg"></div>
                            <div class="absolute right-1/4 top-1/3 w-16 h-16 bg-white/5 rounded-full blur-md"></div>
                            {{-- Subtle grid pattern overlay --}}
                            <div class="absolute inset-0 opacity-[0.04]" style="background-image: url('data:image/svg+xml,%3Csvg width=\'40\' height=\'40\' viewBox=\'0 0 40 40\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'%23fff\' fill-opacity=\'1\'%3E%3Cpath d=\'M0 0h1v1H0zM20 0h1v1h-1zM0 20h1v1H0zM20 20h1v1h-1z\'/%3E%3C/g%3E%3C/svg%3E');"></div>
                        </div>
                        @endif
                        
                        {{-- Tingkat badge --}}
                        <div class="absolute top-3 left-3 z-10">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-extrabold uppercase tracking-wider {{ $badgeColor }} backdrop-blur-md border border-white/10">
                                @if($ormawa->tingkat === 'Universitas')
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21h18M9 8h1m4 0h1m-9 4h1m4 0h1m-9 4h1m4 0h1M5 21V5a2 2 0 012-2h10a2 2 0 012 2v16"/></svg>
                                @elseif($ormawa->tingkat === 'Fakultas')
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                @else
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                @endif
                                {{ $ormawa->tingkat }}
                            </span>
                        </div>

                        {{-- Recruitment status badge --}}
                        @if($ormawa->recruitments_count > 0)
                        <div class="absolute top-3 right-3 z-10">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[10px] font-extrabold uppercase tracking-wider bg-emerald-500 text-white shadow-lg shadow-emerald-500/30">
                                <span class="relative flex h-1.5 w-1.5">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-white"></span>
                                </span>
                                Buka Rekrutmen
                            </span>
                        </div>
                        @endif
                    </div>

                    {{-- Card Body --}}
                    <div class="relative flex flex-col flex-1 p-4 sm:p-5">
                        {{-- Header: Logo + Name --}}
                        <div class="flex items-start gap-3 mb-3">
                            {{-- Inline Logo --}}
                            <div class="shrink-0 -mt-10">
                                @if($ormawa->logo)
                                    <div class="w-14 h-14 rounded-2xl bg-white p-1 shadow-lg ring-2 ring-white overflow-hidden group-hover:shadow-xl transition-shadow duration-300">
                                        <img src="{{ asset('storage/' . $ormawa->logo) }}" alt="Logo {{ $ormawa->nama }}" class="w-full h-full object-contain rounded-xl">
                                    </div>
                                @else
                                    <div class="w-14 h-14 rounded-2xl bg-white shadow-lg ring-2 ring-white flex items-center justify-center font-black text-base text-slate-700 group-hover:shadow-xl transition-shadow duration-300">
                                        {{ strtoupper(substr($ormawa->nama, 0, 2)) }}
                                    </div>
                                @endif
                            </div>
                            <div class="min-w-0 flex-1 pt-0.5">
                                <h3 class="text-[15px] sm:text-base font-extrabold text-slate-900 leading-snug group-hover:text-blue-700 transition-colors duration-300 line-clamp-2">
                                    {{ $ormawa->nama }}
                                </h3>
                                @if($ormawa->fakultas_id || $ormawa->jurusan_id)
                                <p class="text-[11px] text-slate-500 font-medium mt-0.5 line-clamp-1">
                                    {{ $ormawa->fakultasRel->nama_fakultas ?? '' }}{{ $ormawa->jurusan_id ? ' · ' . ($ormawa->jurusanRel->nama_jurusan ?? '') : '' }}
                                </p>
                                @endif
                            </div>
                        </div>

                        {{-- Description --}}
                        <p class="text-slate-500 text-[13px] leading-relaxed flex-grow line-clamp-3 font-medium mb-4">
                            {{ $ormawa->deskripsi ?: 'Organisasi mahasiswa yang aktif dalam berbagai kegiatan kampus.' }}
                        </p>

                        {{-- Footer --}}
                        <div class="flex items-center justify-between pt-3 border-t border-slate-100 mt-auto">
                            {{-- Contact Icons --}}
                            <div class="flex items-center gap-1.5">
                                @if($ormawa->kontak_instagram)
                                <span onclick="event.preventDefault(); event.stopPropagation(); window.open('https://instagram.com/{{ ltrim($ormawa->kontak_instagram, '@') }}', '_blank');" 
                                      class="w-8 h-8 rounded-full bg-slate-100 hover:bg-gradient-to-br hover:from-pink-500 hover:to-purple-600 text-slate-500 hover:text-white flex items-center justify-center transition-all duration-300 cursor-pointer" 
                                      title="{{ $ormawa->kontak_instagram }}">
                                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                                </span>
                                @endif
                                @if($ormawa->kontak_email)
                                <span onclick="event.preventDefault(); event.stopPropagation(); window.open('mailto:{{ $ormawa->kontak_email }}');" 
                                      class="w-8 h-8 rounded-full bg-slate-100 hover:bg-blue-600 text-slate-500 hover:text-white flex items-center justify-center transition-all duration-300 cursor-pointer" 
                                      title="{{ $ormawa->kontak_email }}">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                </span>
                                @endif
                                @if(!$ormawa->kontak_instagram && !$ormawa->kontak_email)
                                    @if($ormawa->recruitments_count > 0)
                                        <span class="text-[11px] font-bold text-emerald-600">{{ $ormawa->recruitments_count }} rekrutmen aktif</span>
                                    @else
                                        <span class="text-[11px] font-bold text-slate-400">Belum ada rekrutmen</span>
                                    @endif
                                @endif
                            </div>

                            {{-- CTA --}}
                            <span class="inline-flex items-center gap-1.5 text-xs font-bold text-blue-600 group-hover:text-white group-hover:bg-blue-600 px-3 py-1.5 rounded-full transition-all duration-300">
                                Lihat Detail
                                <svg class="w-3.5 h-3.5 transform group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                            </span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $ormawas->links() }}
            </div>
        @else
            <div class="text-center py-20 bg-white rounded-2xl border border-slate-200 border-dashed shadow-sm">
                <div class="w-20 h-20 bg-gradient-to-br from-slate-100 to-slate-200 rounded-3xl flex items-center justify-center mx-auto mb-5">
                    <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <h3 class="text-xl font-extrabold text-slate-900 mb-2">Tidak Ditemukan</h3>
                <p class="text-slate-500 text-sm font-medium max-w-md mx-auto mb-6">Tidak ada organisasi mahasiswa yang sesuai dengan filter pencarian Anda.</p>
                <a href="{{ route('mahasiswa.recruitment.index') }}" class="inline-flex items-center gap-2 px-6 py-2.5 bg-slate-900 text-white text-sm font-bold rounded-xl hover:bg-slate-800 transition-all shadow-md">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    Reset Filter
                </a>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    function filterPicker() {
        const fakultasData = @json($fakultasList);
        const oldFakultas = '{{ request("fakultas", "") }}';
        const oldJurusan = '{{ request("jurusan", "") }}';
        const oldTingkat = '{{ request("tingkat", "") }}';

        return {
            selectedFakultas: oldFakultas,
            selectedFakultasName: '',
            selectedJurusan: oldJurusan,
            selectedJurusanName: '',
            selectedTingkat: oldTingkat,
            selectedTingkatName: '',
            fakultasData: fakultasData,
            tingkatData: [
                {id: 'Universitas', nama: 'Universitas (Pusat)'},
                {id: 'Fakultas', nama: 'Fakultas'},
                {id: 'Jurusan', nama: 'Jurusan'}
            ],

            modalOpen: false,
            modalType: '',
            modalSearch: '',

            get showFakultas() {
                return this.selectedTingkat !== 'Universitas';
            },

            get showJurusan() {
                return this.selectedTingkat !== 'Universitas' && this.selectedTingkat !== 'Fakultas';
            },

            onTingkatChange() {
                if (!this.showFakultas) {
                    this.selectedFakultas = '';
                    this.selectedFakultasName = '';
                }
                if (!this.showJurusan) {
                    this.selectedJurusan = '';
                    this.selectedJurusanName = '';
                }
            },

            init() {
                if (this.selectedTingkat) {
                    const tk = this.tingkatData.find(t => t.id == this.selectedTingkat);
                    if (tk) this.selectedTingkatName = tk.nama;
                }
                if (this.selectedFakultas) {
                    const fak = this.fakultasData.find(f => f.id == this.selectedFakultas);
                    if (fak) this.selectedFakultasName = fak.nama_fakultas;
                }
                if (this.selectedJurusan && this.selectedFakultas) {
                    const fak = this.fakultasData.find(f => f.id == this.selectedFakultas);
                    if (fak) {
                        const jur = fak.jurusans.find(j => j.id == this.selectedJurusan);
                        if (jur) this.selectedJurusanName = jur.nama_jurusan;
                    }
                }
            },

            openModal(type) {
                this.modalType = type;
                this.modalSearch = '';
                this.modalOpen = true;
                this.$nextTick(() => {
                    if (this.$refs.modalSearchInput) this.$refs.modalSearchInput.focus();
                });
            },

            closeModal() {
                this.modalOpen = false;
            },

            get filteredModalItems() {
                let items = [];
                if (this.modalType === 'tingkat') {
                    items = this.tingkatData;
                    if (this.modalSearch) {
                        const q = this.modalSearch.toLowerCase();
                        items = items.filter(t => t.nama.toLowerCase().includes(q));
                    }
                } else if (this.modalType === 'fakultas') {
                    items = this.fakultasData;
                    if (this.modalSearch) {
                        const q = this.modalSearch.toLowerCase();
                        items = items.filter(f => f.nama_fakultas.toLowerCase().includes(q));
                    }
                } else {
                    const fak = this.fakultasData.find(f => f.id == this.selectedFakultas);
                    items = fak ? fak.jurusans : [];
                    if (this.modalSearch) {
                        const q = this.modalSearch.toLowerCase();
                        items = items.filter(j => j.nama_jurusan.toLowerCase().includes(q));
                    }
                }
                return items;
            },

            isSelected(item) {
                if (item === null) {
                    if (this.modalType === 'tingkat') return !this.selectedTingkat;
                    if (this.modalType === 'fakultas') return !this.selectedFakultas;
                    return !this.selectedJurusan;
                }
                if (this.modalType === 'tingkat') return item.id == this.selectedTingkat;
                if (this.modalType === 'fakultas') return item.id == this.selectedFakultas;
                return item.id == this.selectedJurusan;
            },

            selectItem(item) {
                if (this.modalType === 'tingkat') {
                    if (item === null) {
                        this.selectedTingkat = '';
                        this.selectedTingkatName = '';
                    } else {
                        this.selectedTingkat = item.id;
                        this.selectedTingkatName = item.nama;
                    }
                    this.onTingkatChange();
                } else if (this.modalType === 'fakultas') {
                    if (item === null) {
                        this.selectedFakultas = '';
                        this.selectedFakultasName = '';
                    } else {
                        this.selectedFakultas = item.id;
                        this.selectedFakultasName = item.nama_fakultas;
                    }
                    this.selectedJurusan = '';
                    this.selectedJurusanName = '';
                } else {
                    if (item === null) {
                        this.selectedJurusan = '';
                        this.selectedJurusanName = '';
                    } else {
                        this.selectedJurusan = item.id;
                        this.selectedJurusanName = item.nama_jurusan;
                    }
                }
                this.closeModal();
            }
        };
    }
</script>
@endpush
@endsection