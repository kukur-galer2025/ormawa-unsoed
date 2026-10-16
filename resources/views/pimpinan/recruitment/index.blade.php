@extends('layouts.app')
@section('title', 'Data Rekrutmen')
@section('page-title', 'Data Rekrutmen')
@section('sidebar')
    @include('layouts.partials.sidebar-pimpinan')
@endsection

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Data Rekrutmen</h1>
        <p class="text-slate-500 mt-1 text-sm">Pantau semua rekrutmen yang tersedia dalam lingkup Anda.</p>
    </div>

    {{-- Recruitment Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
        @forelse($recruitments as $recruitment)
        <a href="{{ route('pimpinan.recruitment.show', $recruitment) }}"
           class="group bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">

            {{-- Cover --}}
            <div class="h-28 bg-gradient-to-br from-blue-500 to-indigo-600 relative overflow-hidden">
                @if($recruitment->ormawa?->cover_photo)
                    <img src="{{ asset('storage/' . $recruitment->ormawa->cover_photo) }}"
                         alt="{{ $recruitment->ormawa->nama }}"
                         class="w-full h-full object-cover opacity-70 group-hover:opacity-80 transition-opacity">
                @endif
                <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
                <span class="absolute top-3 right-3 text-xs px-2.5 py-1 rounded-full font-semibold
                    {{ $recruitment->status === 'dibuka' ? 'bg-emerald-500 text-white' : 'bg-slate-700 text-slate-200' }}">
                    {{ ucfirst($recruitment->status) }}
                </span>
                @if($recruitment->ormawa?->logo)
                    <img src="{{ asset('storage/' . $recruitment->ormawa->logo) }}"
                         alt="{{ $recruitment->ormawa->nama }}"
                         class="absolute bottom-3 left-3 w-8 h-8 rounded-lg object-cover border-2 border-white/30">
                @endif
            </div>

            <div class="p-4">
                <h3 class="font-semibold text-slate-800 text-sm line-clamp-1">{{ $recruitment->judul }}</h3>
                <p class="text-xs text-slate-500 mt-1">{{ $recruitment->ormawa?->nama ?? '-' }}</p>

                <div class="mt-3 flex items-center justify-between text-xs text-slate-500">
                    <span class="flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        {{ $recruitment->applications->count() }} Pendaftar
                    </span>
                    <span class="flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        {{ $recruitment->tanggal_buka?->format('d M') }} – {{ $recruitment->tanggal_tutup?->format('d M Y') ?? '∞' }}
                    </span>
                </div>

                <div class="mt-3 flex items-center gap-2">
                    <span class="text-xs px-2 py-0.5 rounded-full bg-slate-100 text-slate-600">
                        {{ $recruitment->divisions->count() }} divisi
                    </span>
                    @if($recruitment->is_announced)
                    <span class="text-xs px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700">
                        Diumumkan
                    </span>
                    @endif
                </div>
            </div>
        </a>
        @empty
        <div class="col-span-full flex flex-col items-center justify-center py-16 text-center">
            <svg class="w-16 h-16 text-slate-200 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            <p class="text-slate-400 font-medium">Belum ada rekrutmen</p>
            <p class="text-slate-300 text-sm mt-1">Tidak ada data rekrutmen dalam lingkup Anda saat ini.</p>
        </div>
        @endforelse
    </div>

    {{ $recruitments->links() }}

</div>
@endsection
