@extends('layouts.app')
@section('title', 'Dashboard Pimpinan')
@section('page-title', 'Dashboard Pimpinan')
@section('sidebar')
    @include('layouts.partials.sidebar-pimpinan')
@endsection

@section('content')
<div class="space-y-6">

    {{-- Welcome Banner --}}
    <div class="relative overflow-hidden bg-gradient-to-r from-indigo-600 via-blue-600 to-cyan-500 rounded-2xl p-6 text-white shadow-xl">
        <div class="absolute inset-0 opacity-10">
            <svg viewBox="0 0 400 200" xmlns="http://www.w3.org/2000/svg" class="w-full h-full">
                <circle cx="350" cy="50" r="120" fill="white"/>
                <circle cx="50" cy="180" r="80" fill="white"/>
            </svg>
        </div>
        <div class="relative z-10">
            <p class="text-blue-100 text-sm font-medium mb-1">Selamat datang,</p>
            <h1 class="text-2xl font-bold">{{ auth()->user()->name }}</h1>
            <p class="text-blue-100 mt-1">
                @if(auth()->user()->isWakilDekan())
                    Wakil Dekan
                    @if(auth()->user()->fakultas)
                        — {{ auth()->user()->fakultas->nama_fakultas }}
                    @endif
                @else
                    Wakil Rektor — Universitas Jenderal Soedirman
                @endif
            </p>
            <span class="mt-3 inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/20 text-xs font-medium">
                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z" clip-rule="evenodd"/></svg>
                Mode Hanya-Baca (Read Only)
            </span>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex items-center gap-4">
            <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-slate-800">{{ $totalOrmawa }}</p>
                <p class="text-sm text-slate-500">Total Ormawa</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex items-center gap-4">
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-slate-800">{{ $totalRekrutmen }}</p>
                <p class="text-sm text-slate-500">Total Rekrutmen</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex items-center gap-4">
            <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-slate-800">{{ $rekrutmenAktif }}</p>
                <p class="text-sm text-slate-500">Rekrutmen Aktif</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Recent Recruitments --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <h3 class="font-semibold text-slate-800">Rekrutmen Terbaru</h3>
                <a href="{{ route('pimpinan.recruitment.index') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">Lihat semua →</a>
            </div>
            <div class="divide-y divide-slate-50">
                @forelse($recentRecruitments as $recruitment)
                <a href="{{ route('pimpinan.recruitment.show', $recruitment) }}" class="flex items-center gap-4 p-4 hover:bg-slate-50 transition-colors">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-sm font-bold shrink-0">
                        {{ strtoupper(substr($recruitment->ormawa->nama ?? 'O', 0, 1)) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium text-slate-800 truncate">{{ $recruitment->judul }}</p>
                        <p class="text-xs text-slate-500">{{ $recruitment->ormawa->nama ?? '-' }}</p>
                    </div>
                    <span class="text-xs px-2.5 py-1 rounded-full font-medium shrink-0
                        {{ $recruitment->status === 'dibuka' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                        {{ ucfirst($recruitment->status) }}
                    </span>
                </a>
                @empty
                <div class="p-8 text-center text-slate-400 text-sm">Tidak ada rekrutmen.</div>
                @endforelse
            </div>
        </div>

        {{-- Ormawa List --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-5 border-b border-slate-100">
                <h3 class="font-semibold text-slate-800">Daftar Ormawa</h3>
            </div>
            <div class="divide-y divide-slate-50">
                @forelse($ormawas as $ormawa)
                <div class="flex items-center gap-4 p-4">
                    @if($ormawa->logo)
                        <img src="{{ asset('storage/' . $ormawa->logo) }}" alt="{{ $ormawa->nama }}" class="w-10 h-10 rounded-xl object-cover shrink-0">
                    @else
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-sm font-bold shrink-0">
                            {{ strtoupper(substr($ormawa->nama, 0, 1)) }}
                        </div>
                    @endif
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium text-slate-800 truncate">{{ $ormawa->nama }}</p>
                        <p class="text-xs text-slate-500">{{ $ormawa->tingkat }} · {{ $ormawa->recruitments_count }} rekrutmen</p>
                    </div>
                </div>
                @empty
                <div class="p-8 text-center text-slate-400 text-sm">Tidak ada ormawa.</div>
                @endforelse
            </div>
        </div>
    </div>

</div>
@endsection
