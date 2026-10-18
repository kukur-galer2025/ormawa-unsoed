@extends('layouts.app')
@section('title', $recruitment->judul . ' — Detail Rekrutmen')
@section('page-title', 'Detail Rekrutmen')
@section('sidebar')
    @include('layouts.partials.sidebar-pimpinan')
@endsection

@section('content')
<div class="space-y-4 sm:space-y-6">

    {{-- Back Link --}}
    <div class="flex items-center justify-between gap-2">
        <a href="{{ route('pimpinan.recruitment.index') }}" class="inline-flex items-center gap-1.5 text-xs sm:text-sm font-semibold text-slate-500 hover:text-blue-600 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            <span>Kembali ke daftar rekrutmen</span>
        </a>
        <span class="text-[10px] sm:text-xs px-2.5 py-1 rounded-full bg-slate-100 text-slate-600 font-medium">
            Mode Monitoring
        </span>
    </div>

    {{-- Hero Card --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="h-28 sm:h-36 bg-gradient-to-r from-blue-600 to-indigo-600 relative overflow-hidden">
            @if($recruitment->ormawa?->cover_photo)
                <img src="{{ asset('storage/' . $recruitment->ormawa->cover_photo) }}"
                     alt="{{ $recruitment->ormawa->nama }}"
                     class="w-full h-full object-cover opacity-50">
            @endif
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>

            <div class="absolute bottom-3 left-4 right-4 sm:bottom-4 sm:left-6 sm:right-6 flex items-center justify-between gap-3">
                <div class="flex items-center gap-2.5 sm:gap-3 min-w-0">
                    @if($recruitment->ormawa?->logo)
                        <img src="{{ asset('storage/' . $recruitment->ormawa->logo) }}"
                             alt="{{ $recruitment->ormawa->nama }}"
                             class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl object-contain bg-white p-1 border-2 border-white/50 shadow-md shrink-0">
                    @else
                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-white/20 backdrop-blur-md text-white font-bold flex items-center justify-center shrink-0 border border-white/30 text-xs sm:text-sm">
                            {{ strtoupper(substr($recruitment->ormawa->nama ?? 'O', 0, 1)) }}
                        </div>
                    @endif
                    <div class="min-w-0">
                        <h1 class="text-white text-sm sm:text-xl font-bold truncate drop-shadow-xs">{{ $recruitment->judul }}</h1>
                        <p class="text-blue-100 text-[11px] sm:text-sm truncate">{{ $recruitment->ormawa?->nama ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <div class="absolute top-3 right-3 sm:right-4">
                <span class="text-[10px] sm:text-xs px-2.5 py-1 rounded-full font-semibold shadow-xs
                    {{ $recruitment->status === 'dibuka' ? 'bg-emerald-500 text-white' : 'bg-slate-700 text-white' }}">
                    {{ ucfirst($recruitment->status) }}
                </span>
            </div>
        </div>

        {{-- Meta Info (Responsive 2x2 on mobile, 4x1 on desktop) --}}
        <div class="p-3.5 sm:p-5 grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs sm:text-sm border-b border-slate-100 bg-slate-50/40">
            <div>
                <p class="text-slate-400 text-[10px] sm:text-xs mb-0.5">Tanggal Buka</p>
                <p class="font-semibold text-slate-700">{{ $recruitment->tanggal_buka?->format('d M Y') ?? '-' }}</p>
            </div>
            <div>
                <p class="text-slate-400 text-[10px] sm:text-xs mb-0.5">Tanggal Tutup</p>
                <p class="font-semibold text-slate-700">{{ $recruitment->tanggal_tutup?->format('d M Y') ?? '-' }}</p>
            </div>
            <div>
                <p class="text-slate-400 text-[10px] sm:text-xs mb-0.5">Total Pendaftar</p>
                <p class="font-semibold text-slate-700">{{ $recruitment->applications->count() }} orang</p>
            </div>
            <div>
                <p class="text-slate-400 text-[10px] sm:text-xs mb-0.5">Diumumkan</p>
                <p class="font-semibold {{ $recruitment->is_announced ? 'text-emerald-600' : 'text-slate-400' }}">
                    {{ $recruitment->is_announced ? 'Ya' : 'Belum' }}
                </p>
            </div>
        </div>

        @if($recruitment->deskripsi)
        <div class="p-4 sm:p-5 border-b border-slate-100">
            <h3 class="text-xs sm:text-sm font-semibold text-slate-700 mb-1.5">Deskripsi Rekrutmen</h3>
            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">{{ $recruitment->deskripsi }}</p>
        </div>
        @endif
    </div>

    {{-- Divisions --}}
    <div class="flex items-center justify-between">
        <h2 class="text-sm sm:text-lg font-bold text-slate-800">Divisi & Hasil Profile Matching</h2>
        <span class="text-xs text-slate-500 font-medium">{{ $recruitment->divisions->count() }} Divisi</span>
    </div>

    @forelse($recruitment->divisions as $division)
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-4 sm:p-5 border-b border-slate-100 flex items-center justify-between gap-2">
            <div>
                <h3 class="font-bold text-slate-800 text-sm sm:text-base">{{ $division->nama }}</h3>
                <p class="text-xs text-slate-500 mt-0.5">
                    {{ $division->applications->count() }} pendaftar ·
                    @if($division->is_finalized)
                        <span class="text-emerald-600 font-medium">Terfinalisasi</span>
                    @else
                        <span class="text-amber-600 font-medium">Belum difinalisasi</span>
                    @endif
                </p>
            </div>
            <div class="text-right shrink-0">
                <span class="text-[11px] sm:text-xs px-2.5 py-1 rounded-full bg-blue-50 text-blue-700 font-semibold border border-blue-100">
                    Kuota: {{ $division->kuota ?? '-' }}
                </span>
            </div>
        </div>

        @php
            $results = $division->profileMatchingResults->sortByDesc('total_score');
        @endphp

        @if($results->isEmpty())
            <div class="p-6 text-center text-slate-400 text-xs sm:text-sm">
                Belum ada hasil Profile Matching untuk divisi ini.
            </div>
        @else
            {{-- Mobile Card List View (Visible on phone screens) --}}
            <div class="block sm:hidden divide-y divide-slate-100">
                @foreach($results as $index => $result)
                @php
                    $app = $result->application;
                    $isTop = ($result->ranking ?? ($index + 1)) <= $division->kuota;
                @endphp
                <div class="p-3.5 space-y-2 hover:bg-slate-50/80 transition-colors">
                    <div class="flex items-center justify-between gap-2">
                        <div class="flex items-center gap-2 min-w-0">
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full text-[11px] font-bold shrink-0
                                {{ $index === 0 ? 'bg-yellow-100 text-yellow-800' : ($index === 1 ? 'bg-slate-100 text-slate-700' : ($index === 2 ? 'bg-orange-100 text-orange-800' : 'bg-slate-50 text-slate-500 border border-slate-200')) }}">
                                {{ $result->ranking ?? ($index + 1) }}
                            </span>
                            <span class="text-xs font-bold text-slate-800 truncate">
                                {{ $app?->user?->name ?? '-' }}
                            </span>
                        </div>

                        {{-- Status pill --}}
                        <div class="shrink-0">
                            @if($division->is_finalized)
                                @if($app?->status === 'diterima')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-100 text-emerald-700">
                                        Diterima
                                    </span>
                                @elseif($app?->status === 'ditolak')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-red-100 text-red-700">
                                        Ditolak
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-slate-100 text-slate-500">—</span>
                                @endif
                            @else
                                @if($app?->status_rekomendasi === 'direkomendasikan' || $isTop)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-blue-100 text-blue-700">
                                        Masuk Kuota
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-slate-100 text-slate-600">
                                        Luar Kuota
                                    </span>
                                @endif
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center justify-between text-[11px] text-slate-500 pt-0.5 pl-8">
                        <span>NIM: <strong class="text-slate-700">{{ $app?->user?->mahasiswaProfile?->nim ?? '-' }}</strong></span>
                        <span>Skor: <strong class="text-blue-700 font-bold">{{ number_format($result->total_score, 3) }}</strong></span>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Desktop Table View (Visible on tablets and laptops) --}}
            <div class="hidden sm:block overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 border-b border-slate-100">
                        <tr>
                            <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Rank</th>
                            <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Nama Pendaftar</th>
                            <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">NIM</th>
                            <th class="text-right px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Skor</th>
                            <th class="text-center px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Status / Hasil</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($results as $index => $result)
                        @php
                            $app = $result->application;
                            $isTop = ($result->ranking ?? ($index + 1)) <= $division->kuota;
                        @endphp
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-5 py-3">
                                <span class="inline-flex items-center justify-center w-7 h-7 rounded-full text-xs font-bold
                                    {{ $index === 0 ? 'bg-yellow-100 text-yellow-700' : ($index === 1 ? 'bg-slate-100 text-slate-600' : ($index === 2 ? 'bg-orange-100 text-orange-700' : 'bg-slate-50 text-slate-500')) }}">
                                    {{ $result->ranking ?? ($index + 1) }}
                                </span>
                            </td>
                            <td class="px-5 py-3 font-medium text-slate-800">
                                {{ $app?->user?->name ?? '-' }}
                            </td>
                            <td class="px-5 py-3 text-slate-500">
                                {{ $app?->user?->mahasiswaProfile?->nim ?? '-' }}
                            </td>
                            <td class="px-5 py-3 text-right font-semibold text-blue-700">
                                {{ number_format($result->total_score, 3) }}
                            </td>
                            <td class="px-5 py-3 text-center">
                                @if($division->is_finalized)
                                    @if($app?->status === 'diterima')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                            Diterima
                                        </span>
                                    @elseif($app?->status === 'ditolak')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                                            Ditolak
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-500">—</span>
                                    @endif
                                @else
                                    @if($app?->status_rekomendasi === 'direkomendasikan' || $isTop)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                                            Masuk Kuota
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600">
                                            Luar Kuota
                                        </span>
                                    @endif
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
    @empty
        <div class="bg-white rounded-2xl p-8 sm:p-10 text-center text-slate-400 shadow-sm border border-slate-100 text-xs sm:text-sm">
            Belum ada divisi untuk rekrutmen ini.
        </div>
    @endforelse

</div>
@endsection
