@extends("layouts.app")
@section("title","Profile Matching")@section("page-title","Profile Matching")
@section("sidebar")@include("layouts.partials.sidebar-admin")@endsection
@section("content")
<div class="mb-6">
    <p class="text-sm text-slate-500">Pilih rekrutmen untuk mengelola Profile Matching. (Hanya rekrutmen yang sudah ditutup yang dapat dikalkulasi secara final).</p>
</div>

<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-6">
    @if($recruitments->isEmpty())
        <div class="p-8 text-center text-slate-500">Belum ada rekrutmen saat ini.</div>
    @else
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-slate-50 border-b border-slate-200 text-slate-600 font-semibold">
                <tr><th class="px-6 py-4">Judul Rekrutmen</th><th class="px-6 py-4">Status</th><th class="px-6 py-4">Divisi</th><th class="px-6 py-4">Total Pelamar</th><th class="px-6 py-4 text-center">Aksi</th></tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($recruitments as $rec)
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-6 py-4">
                        <p class="font-bold text-slate-800">{{ $rec->judul }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $rec->status === 'dibuka' ? 'bg-blue-50 text-blue-700' : ($rec->status === 'ditutup' ? 'bg-amber-50 text-amber-700' : 'bg-green-50 text-green-700') }}">
                            {{ ucfirst($rec->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-slate-600">{{ $rec->divisions_count }} Divisi</td>
                    <td class="px-6 py-4 text-slate-600">{{ $rec->applications_count }} Pelamar</td>
                    <td class="px-6 py-4">
                        @if($rec->status === 'dibuka')
                            <div class="flex flex-col items-center gap-1.5">
                                <button disabled class="inline-flex items-center gap-1.5 px-4 py-2 bg-slate-50 text-slate-400 font-semibold rounded-lg cursor-not-allowed text-xs border border-slate-200">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                    Proses Profile Matching
                                </button>
                                <p class="text-[10px] text-amber-600 font-medium italic flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                    Terkunci: Rekrutmen masih berjalan
                                </p>
                            </div>
                        @else
                            <a href="{{ route('admin.profile-matching.show', $rec) }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-semibold rounded-lg hover:from-purple-500 hover:to-indigo-500 shadow-sm transition-all text-xs">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                Proses Profile Matching
                            </a>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t border-slate-100">
        {{ $recruitments->links() }}
    </div>
    @endif
</div>
@endsection
