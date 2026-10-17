@extends('layouts.app')
@section('title', 'Data Rekrutmen')
@section('page-title', 'Data Rekrutmen')
@section('sidebar')
    @include('layouts.partials.sidebar-pimpinan')
@endsection

@section('content')
<div class="space-y-4 sm:space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2.5 sm:gap-4">
        <div>
            <h1 class="text-lg sm:text-2xl font-bold text-slate-800 leading-tight">Data Rekrutmen</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Pantau semua rekrutmen ormawa dalam lingkup wewenang Anda.</p>
        </div>
        <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-blue-50 border border-blue-200/80 text-blue-700 text-xs font-semibold self-start sm:self-auto shrink-0 shadow-xs">
            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            <span>Total: {{ $recruitments->total() }} Rekrutmen</span>
        </div>
    </div>

    {{-- Recruitment Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3.5 sm:gap-5">
        @forelse($recruitments as $recruitment)
        <a href="{{ route('pimpinan.recruitment.show', $recruitment) }}"
           class="group bg-white rounded-2xl shadow-xs border border-slate-100 overflow-hidden hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 flex flex-col justify-between">

            {{-- Cover Banner --}}
            <div class="h-28 sm:h-32 bg-gradient-to-br from-blue-500 to-indigo-600 relative overflow-hidden shrink-0">
                @if($recruitment->ormawa?->cover_photo)
                    <img src="{{ asset('storage/' . $recruitment->ormawa->cover_photo) }}"
                         alt="{{ $recruitment->ormawa->nama }}"
                         class="w-full h-full object-cover opacity-65 group-hover:opacity-75 group-hover:scale-105 transition-all duration-300">
                @endif
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/70 via-slate-900/20 to-transparent"></div>

                <span class="absolute top-2.5 right-2.5 text-[10px] sm:text-xs px-2.5 py-0.5 rounded-full font-semibold shadow-xs backdrop-blur-md
                    {{ $recruitment->status === 'dibuka' ? 'bg-emerald-500/90 text-white' : 'bg-slate-700/80 text-slate-200' }}">
                    {{ ucfirst($recruitment->status) }}
                </span>

                @if($recruitment->ormawa?->logo)
                    <img src="{{ asset('storage/' . $recruitment->ormawa->logo) }}"
                         alt="{{ $recruitment->ormawa->nama }}"
                         class="absolute bottom-2.5 left-2.5 w-9 h-9 sm:w-10 sm:h-10 rounded-xl object-contain bg-white p-1 border border-white/50 shadow-md">
                @endif
            </div>

            <div class="p-3.5 sm:p-4 flex-1 flex flex-col justify-between space-y-3">
                <div>
                    <h3 class="font-bold text-slate-800 text-sm sm:text-base line-clamp-1 group-hover:text-blue-600 transition-colors leading-snug">
                        {{ $recruitment->judul }}
                    </h3>
                    <p class="text-xs text-slate-500 mt-0.5 line-clamp-1 font-medium">{{ $recruitment->ormawa?->nama ?? '-' }}</p>

                    <div class="mt-3 flex items-center justify-between text-xs text-slate-500 pt-2 border-t border-slate-50">
                        <span class="flex items-center gap-1 font-medium text-slate-700 text-xs">
                            <svg class="w-3.5 h-3.5 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            {{ $recruitment->applications->count() }} Pendaftar
                        </span>
                        <span class="text-[11px] text-slate-400">
                            {{ $recruitment->tanggal_buka?->format('d M') }} – {{ $recruitment->tanggal_tutup?->format('d M Y') ?? '∞' }}
                        </span>
                    </div>
                </div>

                <div class="flex items-center justify-between gap-2 pt-1">
                    <div class="flex items-center gap-1.5 flex-wrap">
                        <span class="text-[10px] sm:text-xs px-2 py-0.5 rounded-full bg-slate-100 text-slate-600 font-medium">
                            {{ $recruitment->divisions->count() }} Divisi
                        </span>
                        @if($recruitment->is_announced)
                        <span class="text-[10px] sm:text-xs px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700 font-medium">
                            Diumumkan
                        </span>
                        @endif
                    </div>
                    <span class="text-xs text-blue-600 font-semibold group-hover:translate-x-0.5 transition-transform inline-flex items-center gap-0.5">
                        Lihat →
                    </span>
                </div>
            </div>
        </a>
        @empty
        <div class="col-span-full flex flex-col items-center justify-center py-12 sm:py-16 text-center bg-white rounded-2xl border border-slate-100 p-6">
            <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-2xl bg-slate-100 text-slate-400 mx-auto flex items-center justify-center mb-3">
                <svg class="w-6 h-6 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
            <p class="text-slate-700 font-bold text-sm sm:text-base">Belum ada rekrutmen</p>
            <p class="text-slate-400 text-xs sm:text-sm mt-1 max-w-sm">Tidak ada data rekrutmen dalam lingkup wewenang Anda saat ini.</p>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="pt-2">
        {{ $recruitments->links() }}
    </div>

</div>
@endsection
