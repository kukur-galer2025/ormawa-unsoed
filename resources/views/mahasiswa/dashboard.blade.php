@extends("layouts.app")
@section("title","Dashboard Mahasiswa")@section("page-title","Dashboard")
@section("sidebar")@include("layouts.partials.sidebar-mahasiswa")@endsection
@section("content")
<div class="mb-6"><div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl p-6 text-white"><h3 class="text-xl font-bold">Selamat Datang, {{ auth()->user()->name }}!</h3><p class="text-blue-100 mt-1">Temukan dan daftar ke rekrutmen ormawa UNSOED.</p></div></div>
<div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-8">
<div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm"><p class="text-3xl font-bold text-slate-800">{{ $openRecruitments }}</p><p class="text-sm text-slate-500 mt-1">Rekrutmen Terbuka</p><a href="{{ route('mahasiswa.recruitment.index') }}" class="inline-block mt-3 text-sm text-blue-600 hover:text-blue-500 font-medium">Jelajahi →</a></div>
<div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm"><p class="text-3xl font-bold text-slate-800">{{ $applications->count() }}</p><p class="text-sm text-slate-500 mt-1">Pendaftaran Saya</p><a href="{{ route('mahasiswa.applications.history') }}" class="inline-block mt-3 text-sm text-blue-600 hover:text-blue-500 font-medium">Lihat Riwayat →</a></div>
</div>
@if($applications->count() > 0)
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm"><div class="px-6 py-4 border-b border-slate-200"><h3 class="text-lg font-semibold text-slate-800">Pendaftaran Terakhir</h3></div>
<div class="divide-y divide-slate-100">@foreach($applications as $app)<div class="px-6 py-4"><div class="flex justify-between items-center"><div><p class="font-medium text-slate-800">{{ $app->recruitment->judul }}</p><p class="text-sm text-slate-500">{{ $app->recruitment->ormawa->nama }}</p></div><span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $app->status === 'diterima' ? 'bg-green-50 text-green-700' : ($app->status === 'ditolak' ? 'bg-red-50 text-red-700' : ($app->status === 'diproses' ? 'bg-blue-50 text-blue-700' : 'bg-slate-100 text-slate-600')) }}">{{ ucfirst($app->status) }}</span></div></div>@endforeach</div></div>
@endif
@endsection