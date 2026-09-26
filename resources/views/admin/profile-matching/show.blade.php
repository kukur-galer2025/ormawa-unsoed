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

{{-- Tombol Umumkan Hasil --}}
@php
    $allFinalized = $recruitment->divisions->count() > 0 && $recruitment->divisions->every(fn($d) => $d->is_finalized);
@endphp
@if($recruitment->is_announced)
<div class="mb-6 p-5 bg-gradient-to-r from-emerald-50 to-teal-50 border border-emerald-200 rounded-2xl flex items-center gap-4">
    <div class="w-12 h-12 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    </div>
    <div>
        <p class="font-bold text-emerald-800 text-sm">Hasil Rekrutmen Telah Diumumkan</p>
        <p class="text-xs text-emerald-600 mt-1">Seluruh divisi telah selesai diproses dan hasil akhirnya sudah dapat dilihat oleh masing-masing pendaftar.</p>
    </div>
</div>
@elseif($allFinalized)
<div class="mb-6 p-5 bg-gradient-to-r from-indigo-50 to-blue-50 border border-indigo-200 rounded-2xl flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 shadow-sm">
    <div class="flex items-center gap-4">
        <div class="w-12 h-12 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center shrink-0 animate-pulse">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
        </div>
        <div>
            <p class="font-bold text-indigo-900 text-sm">Semua Divisi Telah Difinalisasi!</p>
            <p class="text-xs text-indigo-700 mt-1">Langkah terakhir: Umumkan hasilnya agar mahasiswa dapat melihat status kelulusan mereka.</p>
        </div>
    </div>
    <form action="{{ route('admin.profile-matching.announce', $recruitment) }}" method="POST" onsubmit="return confirmAnnounce(event)">
        @csrf
        <button type="submit" class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-blue-600 text-white font-bold text-sm rounded-xl shadow-lg shadow-indigo-500/30 hover:shadow-xl hover:-translate-y-0.5 transition-all flex items-center gap-2 whitespace-nowrap">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
            Umumkan Hasil Akhir
        </button>
    </form>
</div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    @foreach($recruitment->divisions as $div)
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col">
        <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-start">
            <div>
                <h3 class="font-bold text-slate-800 text-lg mb-1">{{ $div->nama }}</h3>
                <p class="text-xs text-slate-500">{{ $div->deskripsi ?: 'Tidak ada deskripsi' }}</p>
            </div>
            @if($div->is_finalized)
                <span class="px-2.5 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-lg border border-green-200 shadow-sm flex items-center gap-1 shrink-0">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Final
                </span>
            @endif
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
                        <span class="text-xs font-bold text-red-600 bg-red-100 px-2 py-1 rounded">{{ rtrim(rtrim(number_format($div->aspects->sum('bobot'), 2), '0'), '.') }}% / 100%</span>
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
                        <div class="w-8 h-8 rounded-full flex items-center justify-center {{ ($div->applications_count == 0 || !$div->hasAspects) ? 'bg-slate-200 text-slate-400' : ($div->allScored ? 'bg-green-100 text-green-600' : 'bg-amber-100 text-amber-600') }}">
                            @if($div->applications_count == 0 || !$div->hasAspects)
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
                    @elseif(!$div->hasAspects)
                        <span class="text-xs font-bold text-slate-500 bg-slate-200 px-2 py-1 rounded" title="Tambahkan aspek dan kriteria terlebih dahulu">Belum Bisa Dinilai</span>
                    @elseif($div->allScored)
                        <span class="text-xs font-bold text-green-700 bg-green-100 px-2 py-1 rounded">Lengkap</span>
                    @else
                        <a href="{{ route('admin.applicants.index', $recruitment) }}" class="text-xs font-bold text-amber-700 bg-amber-100 px-2 py-1 rounded hover:bg-amber-200 transition-colors" title="Klik untuk input nilai pelamar">Belum Lengkap →</a>
                    @endif
                </div>
            </div>
        </div>
        <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex gap-2 justify-end items-center">
            @if($div->is_finalized)
                <span class="text-xs font-medium text-green-600 mr-auto flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Selesai
                </span>
                <a href="{{ route('admin.profile-matching.result', [$recruitment, $div]) }}" class="px-4 py-2 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 shadow-sm transition-colors flex items-center gap-1.5">
                    Lihat Hasil Final
                </a>
            @elseif($div->applications_count > 0 && $div->hasAspects && $div->bobotValid && $div->aspectsReady && $div->allScored)
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
                @if($div->profile_matching_results_count > 0)
                    <a href="{{ route('admin.profile-matching.result', [$recruitment, $div]) }}" class="px-4 py-2 bg-white border border-slate-300 text-slate-700 text-sm font-semibold rounded-lg hover:bg-slate-100 transition-colors" title="Lihat hasil kalkulasi terakhir yang tersimpan">
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

@push('scripts')
<script>
function confirmAnnounce(event) {
    event.preventDefault();
    const form = event.target;
    Swal.fire({
        title: 'Umumkan Hasil Rekrutmen?',
        html: '<p>Semua mahasiswa akan dapat melihat status kelulusan mereka (diterima/ditolak) di portal pendaftar.</p><p class="text-amber-600 font-bold mt-2">Pastikan semua keputusan sudah benar, ini tidak bisa dibatalkan!</p>',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#4f46e5', // indigo-600
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Ya, Umumkan Sekarang!',
        cancelButtonText: 'Batal',
        customClass: {
            popup: 'rounded-2xl',
            confirmButton: 'rounded-lg',
            cancelButton: 'rounded-lg'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
    return false;
}
</script>
@endpush
@endsection
