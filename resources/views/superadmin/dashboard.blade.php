@extends("layouts.app")
@section("title","Dashboard Superadmin")@section("page-title","Dashboard")
@section("sidebar")@include("layouts.partials.sidebar-superadmin")@endsection
@section("content")
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-2xl p-4 sm:p-6 border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
        <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center mb-4"><svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg></div>
        <p class="text-2xl sm:text-3xl font-bold text-slate-800">{{ $stats["total_ormawa"] }}</p>
        <p class="text-sm text-slate-500 mt-1">Total Ormawa</p>
    </div>
    <div class="bg-white rounded-2xl p-4 sm:p-6 border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
        <div class="w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center mb-4"><svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg></div>
        <p class="text-2xl sm:text-3xl font-bold text-slate-800">{{ $stats["total_admin"] }}</p>
        <p class="text-sm text-slate-500 mt-1">Total Admin</p>
    </div>
    <div class="bg-white rounded-2xl p-4 sm:p-6 border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
        <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center mb-4"><svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/></svg></div>
        <p class="text-2xl sm:text-3xl font-bold text-slate-800">{{ $stats["total_mahasiswa"] }}</p>
        <p class="text-sm text-slate-500 mt-1">Total Mahasiswa</p>
    </div>
    <div class="bg-white rounded-2xl p-4 sm:p-6 border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
        <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center mb-4"><svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg></div>
        <p class="text-2xl sm:text-3xl font-bold text-slate-800">{{ $stats["rekrutmen_aktif"] }}</p>
        <p class="text-sm text-slate-500 mt-1">Rekrutmen Aktif</p>
    </div>
</div>
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm">
    <div class="px-4 sm:px-6 py-4 border-b border-slate-200"><h3 class="text-base sm:text-lg font-semibold text-slate-800">Rekrutmen Terbaru</h3></div>
    <div class="overflow-x-auto"><table class="w-full"><thead><tr class="bg-slate-50"><th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Judul</th><th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Ormawa</th><th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Status</th><th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Tanggal</th></tr></thead>
    <tbody class="divide-y divide-slate-100">@forelse($recentRecruitments as $r)<tr class="hover:bg-slate-50"><td class="px-6 py-4 text-sm font-medium text-slate-800">{{ $r->judul }}</td><td class="px-6 py-4 text-sm text-slate-600">{{ $r->ormawa->nama }}</td><td class="px-6 py-4"><span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $r->status === 'dibuka' ? 'bg-green-50 text-green-700' : ($r->status === 'ditutup' ? 'bg-red-50 text-red-700' : 'bg-slate-100 text-slate-600') }}">{{ ucfirst($r->status) }}</span></td><td class="px-6 py-4 text-sm text-slate-500">{{ $r->tanggal_buka->format('d M Y') }}</td></tr>@empty<tr><td colspan="4" class="px-6 py-8 text-center text-slate-400">Belum ada rekrutmen.</td></tr>@endforelse</tbody></table></div>
</div>
@endsection