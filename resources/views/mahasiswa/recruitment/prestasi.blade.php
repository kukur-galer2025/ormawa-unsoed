@extends("layouts.app")
@section("title", "Prestasi " . $ormawa->nama)
@section("sidebar")@include("layouts.partials.sidebar-mahasiswa")@endsection
@section("content")

<div class="relative">

{{-- Breadcrumb --}}
<div class="mb-6 flex items-center gap-2 text-sm">
    <a href="{{ route('mahasiswa.recruitment.index') }}" class="text-slate-500 hover:text-blue-600 transition-colors font-medium">Katalog</a>
    <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
    <a href="{{ route('mahasiswa.recruitment.show', $ormawa->slug) }}" class="text-slate-500 hover:text-blue-600 transition-colors font-medium truncate max-w-[120px] sm:max-w-none">{{ $ormawa->nama }}</a>
    <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
    <span class="text-slate-800 font-bold">Prestasi</span>
</div>

{{-- Header --}}
<div class="relative bg-gradient-to-r from-amber-500 via-orange-500 to-red-500 rounded-2xl p-6 sm:p-8 mb-8 overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute -right-10 -top-10 w-48 h-48 bg-white rounded-full"></div>
        <div class="absolute -left-10 -bottom-10 w-32 h-32 bg-white rounded-full"></div>
    </div>
    <div class="relative flex items-center gap-4">
        <div class="shrink-0 w-14 h-14 sm:w-16 sm:h-16 bg-white/20 backdrop-blur rounded-2xl flex items-center justify-center">
            <svg class="w-7 h-7 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
        </div>
        <div>
            <h1 class="text-xl sm:text-2xl md:text-3xl font-black text-white leading-tight">Prestasi Organisasi</h1>
            <p class="text-white/80 font-medium text-sm mt-1">{{ $ormawa->nama }} — {{ $ormawa->prestasis->count() }} prestasi tercatat</p>
        </div>
    </div>
</div>

{{-- Prestasi Grid --}}
@if($ormawa->prestasis->isEmpty())
    <div class="bg-white rounded-2xl border border-slate-200 p-8 sm:p-12 text-center shadow-sm">
        <div class="w-16 h-16 bg-amber-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
        </div>
        <h3 class="text-lg font-bold text-slate-800 mb-2">Belum Ada Prestasi</h3>
        <p class="text-sm text-slate-500">Organisasi ini belum menambahkan data prestasi.</p>
    </div>
@else
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($ormawa->prestasis as $prestasi)
        <a href="{{ route('mahasiswa.recruitment.prestasi.show', [$ormawa->slug, $prestasi->id]) }}"
             class="group bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-xl hover:shadow-amber-100/30 hover:-translate-y-1 transition-all duration-300 cursor-pointer block">
            
            {{-- Image --}}
            @if($prestasi->foto)
            <div class="relative h-48 overflow-hidden bg-slate-100">
                <img src="{{ Storage::url($prestasi->foto) }}" alt="{{ $prestasi->judul }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div class="absolute top-3 left-3">
                    <span class="px-3 py-1 bg-amber-500 text-white text-xs font-bold rounded-lg shadow-md">{{ $prestasi->tahun }}</span>
                </div>
            </div>
            @else
            <div class="relative h-36 bg-gradient-to-br from-amber-50 to-orange-50 flex items-center justify-center">
                <svg class="w-12 h-12 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                <div class="absolute top-3 left-3">
                    <span class="px-3 py-1 bg-amber-500 text-white text-xs font-bold rounded-lg shadow-md">{{ $prestasi->tahun }}</span>
                </div>
            </div>
            @endif
            
            {{-- Content --}}
            <div class="p-5">
                <h3 class="font-bold text-slate-800 text-base leading-snug mb-2 group-hover:text-amber-600 transition-colors line-clamp-2">{{ $prestasi->judul }}</h3>
                <div class="mt-4 flex items-center gap-2 text-xs font-semibold text-amber-600">
                    <span>Lihat Detail</span>
                    <svg class="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </div>
            </div>
        </a>
        @endforeach
    </div>
@endif



</div>

@endsection
