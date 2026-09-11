@extends("layouts.app")
@section("title",$recruitment->judul)@section("page-title",$recruitment->judul)
@section("sidebar")@include("layouts.partials.sidebar-admin")@endsection
@section("content")
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
<div class="lg:col-span-2 space-y-6">
{{-- Info Umum --}}
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
<div class="flex justify-between items-start mb-4"><span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $recruitment->status === 'dibuka' ? 'bg-green-50 text-green-700' : ($recruitment->status === 'ditutup' ? 'bg-red-50 text-red-700' : 'bg-slate-100 text-slate-600') }}">{{ ucfirst($recruitment->status) }}</span><a href="{{ route('admin.recruitment.edit', $recruitment) }}" class="text-sm text-blue-600 hover:text-blue-500">Edit</a></div>
<p class="text-slate-600 mb-4">{{ $recruitment->deskripsi }}</p>
<div class="text-sm"><div><span class="text-slate-500">Periode:</span><p class="font-medium">{{ $recruitment->tanggal_buka->format('d M Y') }} — {{ $recruitment->tanggal_tutup->format('d M Y') }}</p></div></div>
@if($recruitment->persyaratan)<div class="mt-4 pt-4 border-t border-slate-100"><h4 class="text-sm font-semibold text-slate-700 mb-2">Persyaratan</h4><div class="text-sm text-slate-600 whitespace-pre-line">{{ $recruitment->persyaratan }}</div></div>@endif
</div>

{{-- Divisi --}}
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
<h3 class="text-sm font-bold text-slate-800 mb-4">Divisi ({{ $recruitment->divisions->count() }})</h3>
<div class="space-y-3">
@foreach($recruitment->divisions as $div)
<div class="bg-slate-50 rounded-xl p-4 border border-slate-100">
    <div class="flex items-center justify-between">
        <div>
            <p class="font-semibold text-slate-800">{{ $div->nama }}</p>
            @if($div->deskripsi)<p class="text-xs text-slate-500 mt-0.5">{{ $div->deskripsi }}</p>@endif
        </div>
        <div class="flex items-center gap-3 text-xs">
            <span class="px-2 py-1 bg-blue-50 text-blue-700 rounded-lg font-medium">Kuota: {{ $div->kuota }}</span>
            <span class="px-2 py-1 bg-slate-100 text-slate-600 rounded-lg font-medium">{{ $div->applications->count() }} pelamar</span>
            <span class="px-2 py-1 bg-indigo-50 text-indigo-700 rounded-lg font-medium">{{ $div->aspects->count() }} aspek, {{ $div->aspects->sum(fn($a) => $a->criteria->count()) }} kriteria</span>
        </div>
    </div>
</div>
@endforeach
</div>
</div>
</div>

{{-- Quick Links --}}
<div class="space-y-4">
<a href="{{ route('admin.criteria.index', $recruitment) }}" class="block bg-white rounded-2xl border border-slate-200 shadow-sm p-5 hover:border-blue-300 hover:shadow-md transition-all"><div class="flex items-center gap-3"><div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center"><svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg></div><div><p class="font-medium text-slate-800">Kriteria per Divisi</p><p class="text-xs text-slate-500">Kelola kriteria penilaian</p></div></div></a>
<a href="{{ route('admin.applicants.index', $recruitment) }}" class="block bg-white rounded-2xl border border-slate-200 shadow-sm p-5 hover:border-blue-300 hover:shadow-md transition-all"><div class="flex items-center gap-3"><div class="w-10 h-10 bg-green-50 rounded-xl flex items-center justify-center"><svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg></div><div><p class="font-medium text-slate-800">Pelamar</p><p class="text-xs text-slate-500">{{ $recruitment->applications->count() }} pelamar</p></div></div></a>
<a href="{{ route('admin.scoring.index', $recruitment) }}" class="block bg-white rounded-2xl border border-slate-200 shadow-sm p-5 hover:border-blue-300 hover:shadow-md transition-all"><div class="flex items-center gap-3"><div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center"><svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/></svg></div><div><p class="font-medium text-slate-800">Input Nilai</p><p class="text-xs text-slate-500">Scoring per divisi</p></div></div></a>
</div></div>
@endsection