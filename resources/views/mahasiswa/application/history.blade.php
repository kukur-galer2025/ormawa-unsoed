@extends("layouts.app")
@section("title","Riwayat Lamaran")@section("page-title","Riwayat Pendaftaran")
@section("sidebar")@include("layouts.partials.sidebar-mahasiswa")@endsection
@section("content")
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-6">
    @if($applications->isEmpty())
        <div class="p-12 text-center text-slate-500 flex flex-col items-center">
            <svg class="w-16 h-16 text-slate-200 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
            <p class="font-medium text-slate-600 mb-1">Belum Ada Riwayat Lamaran</p>
            <p class="text-sm text-slate-500 mb-6">Anda belum pernah mendaftar ke organisasi manapun.</p>
            <a href="{{ route('mahasiswa.recruitment.index') }}" class="px-6 py-2.5 bg-blue-600 text-white font-semibold text-sm rounded-xl hover:bg-blue-700 shadow-sm transition-colors">Cari Organisasi</a>
        </div>
    @else
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-slate-50 border-b border-slate-200 text-slate-600 font-semibold uppercase text-xs tracking-wider">
                <tr><th class="px-6 py-4">Organisasi</th><th class="px-6 py-4">Rekrutmen & Divisi</th><th class="px-6 py-4">Tanggal Daftar</th><th class="px-6 py-4 text-center">Ranking</th><th class="px-6 py-4">Status</th></tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($applications as $app)
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-6 py-4">
                        <p class="font-bold text-slate-800">{{ $app->recruitment->ormawa->nama }}</p>
                        <p class="text-xs text-slate-500">{{ $app->recruitment->ormawa->fakultasRel->nama_fakultas ?? '' }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <p class="font-semibold text-slate-700">{{ $app->recruitment->judul }}</p>
                        <span class="inline-block mt-1 px-2 py-0.5 bg-slate-100 text-slate-600 rounded text-xs border border-slate-200">{{ $app->division->nama }}</span>
                    </td>
                    <td class="px-6 py-4 text-slate-500 font-medium">{{ $app->created_at->format('d/m/Y') }}</td>
                    <td class="px-6 py-4 text-center">
                        @if($app->profileMatchingResult)
                            <span class="font-bold text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-full border border-indigo-100">#{{ $app->profileMatchingResult->ranking }}</span>
                        @else
                            <span class="text-slate-400 text-xs">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full text-xs font-bold 
                            {{ $app->status === 'pending' ? 'bg-amber-100 text-amber-700' : 
                               ($app->status === 'diproses' ? 'bg-blue-100 text-blue-700' : 
                               ($app->status === 'diterima' ? 'bg-green-100 text-green-700 shadow-sm shadow-green-500/20' : 'bg-red-100 text-red-700')) }}">
                            {{ strtoupper($app->status) }}
                        </span>
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