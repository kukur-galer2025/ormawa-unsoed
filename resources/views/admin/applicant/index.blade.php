@extends("layouts.app")
@section("title","Pelamar - ".$recruitment->judul)@section("page-title","Data Pelamar")
@section("sidebar")@include("layouts.partials.sidebar-admin")@endsection
@section("content")
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
    <div>
        <p class="text-sm text-slate-500">Rekrutmen: <span class="font-semibold text-slate-700">{{ $recruitment->judul }}</span></p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('admin.recruitment.show', $recruitment) }}" class="px-4 py-2 bg-slate-100 text-slate-700 text-sm font-medium rounded-xl hover:bg-slate-200">← Kembali</a>
    </div>
</div>

<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-6">
    <div class="p-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
        <form method="GET" action="{{ route('admin.applicants.index', $recruitment) }}" class="flex items-center gap-2">
            <span class="text-sm font-medium text-slate-600">Filter Divisi:</span>
            <select name="division_id" onchange="this.form.submit()" class="px-3 py-1.5 border border-slate-300 rounded-lg text-sm bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                <option value="">-- Semua Divisi --</option>
                @foreach($recruitment->divisions as $div)
                    <option value="{{ $div->id }}" {{ request('division_id') == $div->id ? 'selected' : '' }}>{{ $div->nama }}</option>
                @endforeach
            </select>
        </form>
    </div>
    @if($applications->isEmpty())
        <div class="p-8 text-center text-slate-500">Belum ada pelamar.</div>
    @else
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-slate-50 border-b border-slate-200 text-slate-600 font-semibold">
                <tr><th class="px-6 py-4">NIM</th><th class="px-6 py-4">Nama Pelamar</th><th class="px-6 py-4">Divisi</th><th class="px-6 py-4">Tanggal Daftar</th><th class="px-6 py-4">Status</th><th class="px-6 py-4 text-center">Aksi</th></tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($applications as $app)
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-6 py-4 text-slate-500">{{ $app->user->mahasiswaProfile->nim ?? '-' }}</td>
                    <td class="px-6 py-4">
                        <p class="font-bold text-slate-800">{{ $app->user->name }}</p>
                        <p class="text-xs text-slate-500">{{ $app->user->mahasiswaProfile->jurusan ?? '-' }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2.5 py-1 bg-slate-100 text-slate-700 rounded-lg text-xs font-semibold">{{ $app->division->nama }}</span>
                    </td>
                    <td class="px-6 py-4 text-slate-500">{{ $app->created_at->format('d/m/Y H:i') }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2.5 py-1 rounded-full text-xs font-medium 
                            {{ $app->status === 'pending' ? 'bg-amber-100 text-amber-700' : 
                               ($app->status === 'diproses' ? 'bg-blue-100 text-blue-700' : 
                               ($app->status === 'diterima' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700')) }}">
                            {{ ucfirst($app->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <a href="{{ route('admin.applicants.show', [$recruitment, $app]) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors" title="Lihat Detail">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t border-slate-100">
        {{ $applications->links() }}
    </div>
    @endif
</div>
@endsection