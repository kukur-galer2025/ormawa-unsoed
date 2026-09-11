@extends("layouts.app")
@section("title",$ormawa->nama)@section("page-title",$ormawa->nama)
@section("sidebar")@include("layouts.partials.sidebar-superadmin")@endsection
@section("content")
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 mb-6">
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
<div><h3 class="text-lg font-semibold text-slate-800 mb-3">Informasi</h3><dl class="space-y-2"><div><dt class="text-sm text-slate-500">Kategori</dt><dd class="font-medium">{{ $ormawa->kategori }}</dd></div><div><dt class="text-sm text-slate-500">Deskripsi</dt><dd>{{ $ormawa->deskripsi ?: '-' }}</dd></div><div><dt class="text-sm text-slate-500">Kontak</dt><dd>{{ $ormawa->kontak_email }} | {{ $ormawa->kontak_instagram }}</dd></div></dl></div>
<div><h3 class="text-lg font-semibold text-slate-800 mb-3">Admin</h3>@forelse($ormawa->admins as $a)<div class="flex items-center gap-2 py-1"><div class="w-7 h-7 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 text-xs font-bold">{{ strtoupper(substr($a->name,0,1)) }}</div><span class="text-sm">{{ $a->name }} ({{ $a->email }})</span></div>@empty<p class="text-sm text-slate-400">Belum ada admin.</p>@endforelse</div>
</div></div>
@endsection