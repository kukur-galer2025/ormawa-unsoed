@extends("layouts.app")
@section("title",$recruitment->judul)@section("page-title",$recruitment->judul)
@section("sidebar")@include("layouts.partials.sidebar-admin")@endsection
@section("content")
<div class="space-y-6 mb-6">
{{-- Info Umum --}}
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
<div class="flex justify-between items-start mb-4"><span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $recruitment->status === 'dibuka' ? 'bg-green-50 text-green-700' : ($recruitment->status === 'ditutup' ? 'bg-red-50 text-red-700' : ($recruitment->status === 'selesai' ? 'bg-indigo-50 text-indigo-700' : 'bg-slate-100 text-slate-600')) }}">{{ ucfirst($recruitment->status) }}</span><a href="{{ route('admin.recruitment.edit', $recruitment) }}" class="text-sm text-blue-600 hover:text-blue-500">Edit</a></div>
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
@endsection