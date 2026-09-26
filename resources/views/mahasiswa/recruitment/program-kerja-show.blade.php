@extends("layouts.app")
@section("title", $proker->nama . " - " . $ormawa->nama)
@section("sidebar")@include("layouts.partials.sidebar-mahasiswa")@endsection
@section("content")

{{-- Breadcrumb --}}
<div class="mb-6 flex flex-wrap items-center gap-2 text-sm">
    <a href="{{ route('mahasiswa.recruitment.index') }}" class="text-slate-500 hover:text-blue-600 transition-colors font-medium">Katalog</a>
    <svg class="w-4 h-4 text-slate-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
    <a href="{{ route('mahasiswa.recruitment.show', $ormawa->slug) }}" class="text-slate-500 hover:text-blue-600 transition-colors font-medium truncate max-w-[120px] sm:max-w-none">{{ $ormawa->nama }}</a>
    <svg class="w-4 h-4 text-slate-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
    <a href="{{ route('mahasiswa.recruitment.proker', $ormawa->slug) }}" class="text-slate-500 hover:text-blue-600 transition-colors font-medium">Program Kerja</a>
    <svg class="w-4 h-4 text-slate-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
    <span class="text-slate-800 font-bold truncate max-w-[150px] sm:max-w-[250px]">{{ $proker->nama }}</span>
</div>

<div class="mb-6">
    <a href="{{ route('mahasiswa.recruitment.proker', $ormawa->slug) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 text-slate-600 text-sm font-bold rounded-xl hover:bg-slate-50 hover:text-slate-800 transition-colors shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Kembali ke Daftar Program Kerja
    </a>
</div>

<div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden mb-12">
    @if($proker->foto)
    <div class="w-full h-[300px] sm:h-[400px] md:h-[500px] relative bg-slate-100">
        <img src="{{ Storage::url($proker->foto) }}" alt="{{ $proker->nama }}" class="absolute inset-0 w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 to-transparent"></div>
    </div>
    @else
    <div class="w-full h-48 sm:h-64 relative bg-gradient-to-br from-blue-100 to-indigo-100 flex items-center justify-center">
        <svg class="w-20 h-20 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
    </div>
    @endif
    
    <div class="p-6 sm:p-8 md:p-10 lg:p-12 relative {{ $proker->foto ? '-mt-16 sm:-mt-24' : '' }}">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-2xl shadow-xl border border-slate-100 p-6 sm:p-8 md:p-10 relative">
                {{-- Decorative element --}}
                <div class="absolute -top-6 -right-6 w-24 h-24 bg-gradient-to-br from-blue-200/40 to-indigo-200/40 rounded-full blur-2xl"></div>
                
                <div class="relative">
                    <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-black text-slate-800 leading-tight mb-6">
                        {{ $proker->nama }}
                    </h1>
                    
                    @if($proker->deskripsi)
                    <div class="prose prose-slate max-w-none text-slate-600 leading-relaxed whitespace-pre-line text-sm sm:text-base md:text-lg">
                        {{ $proker->deskripsi }}
                    </div>
                    @else
                    <p class="text-slate-400 italic">Tidak ada deskripsi detail untuk program kerja ini.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
