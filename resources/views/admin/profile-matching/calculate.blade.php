@extends("layouts.app")
@section("title","Profile Matching")@section("page-title","Profile Matching — " . $recruitment->judul)
@section("sidebar")@include("layouts.partials.sidebar-admin")@endsection
@section("content")
<div class="mb-6"><a href="{{ route('admin.recruitment.show', $recruitment) }}" class="text-sm text-slate-500 hover:text-slate-700">&larr; Kembali ke Rekrutmen</a></div>
<div class="max-w-2xl">
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
<h3 class="text-lg font-semibold text-slate-800 mb-4">Kalkulasi Profile Matching</h3>
<div class="space-y-3 mb-6">
<div class="flex items-center gap-3 p-3 rounded-xl {{ $hasCore ? 'bg-green-50 border border-green-100' : 'bg-red-50 border border-red-100' }}"><span class="text-sm {{ $hasCore ? 'text-green-700' : 'text-red-700' }}">{{ $hasCore ? '✓' : '✗' }} Core Factor tersedia</span></div>
<div class="flex items-center gap-3 p-3 rounded-xl {{ $hasSecondary ? 'bg-green-50 border border-green-100' : 'bg-red-50 border border-red-100' }}"><span class="text-sm {{ $hasSecondary ? 'text-green-700' : 'text-red-700' }}">{{ $hasSecondary ? '✓' : '✗' }} Secondary Factor tersedia</span></div>
<div class="flex items-center gap-3 p-3 rounded-xl {{ $allScored ? 'bg-green-50 border border-green-100' : 'bg-amber-50 border border-amber-100' }}"><span class="text-sm {{ $allScored ? 'text-green-700' : 'text-amber-700' }}">{{ $allScored ? '✓ Semua pelamar sudah dinilai' : '⚠ Ada pelamar yang belum dinilai' }}</span></div>
</div>
<div class="p-4 bg-blue-50 border border-blue-100 rounded-xl mb-6"><h4 class="text-sm font-semibold text-blue-800 mb-2">Bobot Penilaian</h4><p class="text-sm text-blue-700">Core Factor: {{ $recruitment->cf_percentage }}% | Secondary Factor: {{ $recruitment->sf_percentage }}%</p></div>
<div class="p-4 bg-slate-50 border border-slate-200 rounded-xl mb-6"><h4 class="text-sm font-semibold text-slate-800 mb-2">Tabel Konversi GAP → Bobot</h4>
<table class="w-full text-xs"><thead><tr><th class="py-1 text-left">Gap</th><th class="py-1 text-center">Bobot</th><th class="py-1 text-left">Keterangan</th></tr></thead>
<tbody><tr><td>0</td><td class="text-center">5.0</td><td>Kompetensi sesuai</td></tr><tr><td>1</td><td class="text-center">4.5</td><td>Kelebihan 1 tingkat</td></tr><tr><td>-1</td><td class="text-center">4.0</td><td>Kekurangan 1 tingkat</td></tr><tr><td>2</td><td class="text-center">3.5</td><td>Kelebihan 2 tingkat</td></tr><tr><td>-2</td><td class="text-center">3.0</td><td>Kekurangan 2 tingkat</td></tr><tr><td>3</td><td class="text-center">2.5</td><td>Kelebihan 3 tingkat</td></tr><tr><td>-3</td><td class="text-center">2.0</td><td>Kekurangan 3 tingkat</td></tr><tr><td>4</td><td class="text-center">1.5</td><td>Kelebihan 4 tingkat</td></tr><tr><td>-4</td><td class="text-center">1.0</td><td>Kekurangan 4 tingkat</td></tr></tbody></table></div>
@if($hasCore && $hasSecondary && $allScored)
<form method="POST" action="{{ route('admin.profile-matching.calculate', $recruitment) }}">@csrf
<button type="submit" class="w-full py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-semibold rounded-xl shadow-lg shadow-purple-500/25 hover:from-purple-500 hover:to-indigo-500" onclick="confirmClick(event, 'Jalankan kalkulasi Profile Matching?')">🔄 Jalankan Kalkulasi Profile Matching</button>
</form>
@else<button disabled class="w-full py-3 bg-slate-200 text-slate-400 font-semibold rounded-xl cursor-not-allowed">Lengkapi prasyarat terlebih dahulu</button>@endif
@if($recruitment->profileMatchingResults && $recruitment->profileMatchingResults->count() > 0)<div class="mt-4"><a href="{{ route('admin.profile-matching.result', $recruitment) }}" class="block text-center py-2 text-sm text-blue-600 hover:text-blue-500 font-medium">Lihat Hasil Kalkulasi Terakhir →</a></div>@endif
</div></div>
@endsection