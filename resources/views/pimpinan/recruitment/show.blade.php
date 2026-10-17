@extends('layouts.app')
@section('title', $recruitment->judul . ' — Detail Rekrutmen')
@section('page-title', 'Detail Rekrutmen')
@section('sidebar')
    @include('layouts.partials.sidebar-pimpinan')
@endsection

@section('content')
<div class="space-y-6">

    {{-- Back Link --}}
    <a href="{{ route('pimpinan.recruitment.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-slate-700 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Kembali ke daftar rekrutmen
    </a>

    {{-- Hero Card --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="h-36 bg-gradient-to-r from-blue-600 to-indigo-600 relative overflow-hidden">
            @if($recruitment->ormawa?->cover_photo)
                <img src="{{ asset('storage/' . $recruitment->ormawa->cover_photo) }}"
                     alt="{{ $recruitment->ormawa->nama }}"
                     class="w-full h-full object-cover opacity-50">
            @endif
            <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
            <div class="absolute bottom-4 left-6 flex items-center gap-3">
                @if($recruitment->ormawa?->logo)
                    <img src="{{ asset('storage/' . $recruitment->ormawa->logo) }}"
                         alt="{{ $recruitment->ormawa->nama }}"
                         class="w-12 h-12 rounded-xl object-cover border-2 border-white/30 shadow-lg">
                @endif
                <div>
                    <h1 class="text-white text-xl font-bold">{{ $recruitment->judul }}</h1>
                    <p class="text-blue-100 text-sm">{{ $recruitment->ormawa?->nama ?? '-' }}</p>
                </div>
            </div>
            <div class="absolute top-3 right-4">
                <span class="text-xs px-3 py-1.5 rounded-full font-semibold
                    {{ $recruitment->status === 'dibuka' ? 'bg-emerald-500 text-white' : 'bg-slate-700 text-white' }}">
                    {{ ucfirst($recruitment->status) }}
                </span>
            </div>
        </div>

        {{-- Meta Info --}}
        <div class="p-5 grid grid-cols-2 sm:grid-cols-4 gap-4 text-sm border-b border-slate-100">
            <div>
                <p class="text-slate-400 text-xs mb-1">Tanggal Buka</p>
                <p class="font-medium text-slate-700">{{ $recruitment->tanggal_buka?->format('d M Y') ?? '-' }}</p>
            </div>
            <div>
                <p class="text-slate-400 text-xs mb-1">Tanggal Tutup</p>
                <p class="font-medium text-slate-700">{{ $recruitment->tanggal_tutup?->format('d M Y') ?? '-' }}</p>
            </div>
            <div>
                <p class="text-slate-400 text-xs mb-1">Total Pendaftar</p>
                <p class="font-medium text-slate-700">{{ $recruitment->applications->count() }} orang</p>
            </div>
            <div>
                <p class="text-slate-400 text-xs mb-1">Diumumkan</p>
                <p class="font-medium {{ $recruitment->is_announced ? 'text-emerald-600' : 'text-slate-400' }}">
                    {{ $recruitment->is_announced ? 'Ya' : 'Belum' }}
                </p>
            </div>
        </div>

        @if($recruitment->deskripsi)
        <div class="p-5 border-b border-slate-100">
            <h3 class="text-sm font-semibold text-slate-700 mb-2">Deskripsi</h3>
            <p class="text-sm text-slate-600 leading-relaxed">{{ $recruitment->deskripsi }}</p>
        </div>
        @endif
    </div>

    {{-- Divisions --}}
    <h2 class="text-lg font-bold text-slate-800">Divisi & Hasil Profile Matching</h2>

    @forelse($recruitment->divisions as $division)
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="font-semibold text-slate-800">{{ $division->nama_divisi }}</h3>
                <p class="text-xs text-slate-500 mt-0.5">
                    {{ $division->applications->count() }} pendaftar ·
                    @if($division->is_finalized)
                        <span class="text-emerald-600 font-medium">Terfinalisasi</span>
                    @else
                        <span class="text-amber-600">Belum difinalisasi</span>
                    @endif
                </p>
            </div>
        </div>

        @php
            $results = $division->profileMatchingResults->sortByDesc('total_score');
        @endphp

        @if($results->isEmpty())
            <div class="p-6 text-center text-slate-400 text-sm">
                Belum ada hasil Profile Matching untuk divisi ini.
            </div>
        @else
            <div class="overflow-x-auto">
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
        <div class="bg-white rounded-2xl p-10 text-center text-slate-400 shadow-sm border border-slate-100">
            Belum ada divisi untuk rekrutmen ini.
        </div>
    @endforelse

</div>
@endsection
