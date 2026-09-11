@extends("layouts.app")
@section("title","Profile Matching")@section("page-title","Divisi Rekrutmen")
@section("sidebar")@include("layouts.partials.sidebar-admin")@endsection
@section("content")
<div class="flex justify-between items-center mb-6">
    <div>
        <p class="text-sm text-slate-500">Rekrutmen: <span class="font-semibold text-slate-700">{{ $recruitment->judul }}</span></p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('admin.profile-matching.index') }}" class="px-4 py-2 bg-slate-100 text-slate-700 text-sm font-medium rounded-xl hover:bg-slate-200">← Kembali</a>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    @foreach($recruitment->divisions as $div)
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col">
        <div class="px-6 py-5 border-b border-slate-100">
            <h3 class="font-bold text-slate-800 text-lg mb-1">{{ $div->nama }}</h3>
            <p class="text-xs text-slate-500">{{ $div->deskripsi ?: 'Tidak ada deskripsi' }}</p>
        </div>
        <div class="p-6 flex-grow">
            <div class="grid grid-cols-2 gap-4 mb-6 text-sm">
                <div class="bg-slate-50 rounded-lg p-3 text-center border border-slate-100">
                    <span class="block text-xs font-semibold text-slate-500 mb-1">Kuota</span>
                    <span class="font-bold text-slate-800">{{ $div->kuota }}</span>
                </div>
                <div class="bg-slate-50 rounded-lg p-3 text-center border border-slate-100">
                    <span class="block text-xs font-semibold text-slate-500 mb-1">Pelamar</span>
                    <span class="font-bold text-slate-800">{{ $div->applications_count }}</span>
                </div>
            </div>

            <div class="space-y-3 mb-6">
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-3">Kesiapan Data</p>
                
                {{-- Cek Bobot Aspek --}}
                <div class="flex items-center justify-between p-3 rounded-xl border {{ (!$div->hasAspects || !$div->bobotValid) ? 'bg-red-50/50 border-red-100' : 'bg-slate-50 border-slate-100' }}">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center {{ (!$div->hasAspects || !$div->bobotValid) ? 'bg-red-100 text-red-600' : 'bg-green-100 text-green-600' }}">
                            @if(!$div->hasAspects || !$div->bobotValid)
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            @else
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            @endif
                        </div>
                        <span class="text-xs font-medium {{ (!$div->hasAspects || !$div->bobotValid) ? 'text-red-900' : 'text-slate-700' }}">Total Bobot Aspek 100%</span>
                    </div>
                    @if(!$div->hasAspects)
                        <span class="text-xs font-bold text-red-600 bg-red-100 px-2 py-1 rounded">Belum Ada Aspek</span>
                    @elseif($div->bobotValid)
                        <span class="text-xs font-bold text-green-700 bg-green-100 px-2 py-1 rounded">Valid</span>
                    @else
                        <span class="text-xs font-bold text-red-600 bg-red-100 px-2 py-1 rounded">{{ round($div->aspects->sum('bobot') * 100) }}% / 100%</span>
                    @endif
                </div>
                
                {{-- Cek CF/SF --}}
                <div class="flex items-center justify-between p-3 rounded-xl border {{ (!$div->hasAspects || !$div->aspectsReady) ? 'bg-red-50/50 border-red-100' : 'bg-slate-50 border-slate-100' }}">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center {{ (!$div->hasAspects || !$div->aspectsReady) ? 'bg-red-100 text-red-600' : 'bg-green-100 text-green-600' }}">
                            @if(!$div->hasAspects || !$div->aspectsReady)
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            @else
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            @endif
                        </div>
                        <span class="text-xs font-medium {{ (!$div->hasAspects || !$div->aspectsReady) ? 'text-red-900' : 'text-slate-700' }}">Persentase CF & SF Tiap Aspek</span>
                    </div>
                    @if($div->hasAspects && $div->aspectsReady)
                        <span class="text-xs font-bold text-green-700 bg-green-100 px-2 py-1 rounded">Valid</span>
                    @else
                        <span class="text-xs font-bold text-red-600 bg-red-100 px-2 py-1 rounded">Tidak Valid</span>
                    @endif
                </div>

                {{-- Cek Penilaian --}}
                <div class="flex items-center justify-between p-3 rounded-xl border {{ ($div->applications_count > 0 && !$div->allScored) ? 'bg-amber-50 border-amber-100' : 'bg-slate-50 border-slate-100' }}">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center {{ $div->applications_count == 0 ? 'bg-slate-200 text-slate-400' : ($div->allScored ? 'bg-green-100 text-green-600' : 'bg-amber-100 text-amber-600') }}">
                            @if($div->applications_count == 0)
                                <span class="font-bold">-</span>
                            @elseif($div->allScored)
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            @else
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            @endif
                        </div>
                        <span class="text-xs font-medium {{ ($div->applications_count > 0 && !$div->allScored) ? 'text-amber-900' : 'text-slate-700' }}">Kelengkapan Nilai Pelamar</span>
                    </div>
                    @if($div->applications_count == 0)
                        <span class="text-xs font-bold text-slate-500 bg-slate-200 px-2 py-1 rounded">Kosong</span>
                    @elseif($div->allScored)
                        <span class="text-xs font-bold text-green-700 bg-green-100 px-2 py-1 rounded">Lengkap</span>
                    @else
                        <span class="text-xs font-bold text-amber-700 bg-amber-100 px-2 py-1 rounded">Belum Lengkap</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex gap-2 justify-end">
            @if($div->applications_count > 0 && $div->hasAspects && $div->bobotValid && $div->aspectsReady && $div->allScored)
                <a href="{{ route('admin.profile-matching.result', [$recruitment, $div]) }}" class="px-4 py-2 bg-white border border-slate-300 text-slate-700 text-sm font-semibold rounded-lg hover:bg-slate-100 transition-colors">
                    Lihat Hasil
                </a>
                <form action="{{ route('admin.profile-matching.calculate', [$recruitment, $div]) }}" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-gradient-to-r from-purple-600 to-indigo-600 text-white text-sm font-semibold rounded-lg shadow-sm hover:shadow-md transition-all">
                        Hitung Ranking
                    </button>
                </form>
            @else
                @if($div->applications_count > 0 && $div->hasAspects && $div->bobotValid && $div->aspectsReady && !$div->allScored)
                    <a href="{{ route('admin.profile-matching.result', [$recruitment, $div]) }}" class="px-4 py-2 bg-white border border-slate-300 text-slate-700 text-sm font-semibold rounded-lg hover:bg-slate-100 transition-colors">
                        Lihat Hasil (Lama)
                    </a>
                @endif
                <button disabled class="px-4 py-2 bg-slate-200 text-slate-400 text-sm font-semibold rounded-lg cursor-not-allowed" title="Harap lengkapi semua checklist kesiapan data terlebih dahulu">
                    Data Belum Siap
                </button>
            @endif
        </div>
    </div>
    @endforeach
</div>
@endsection
