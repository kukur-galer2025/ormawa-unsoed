@extends("layouts.app")
@section("title","Riwayat Lamaran")@section("page-title","Riwayat Pendaftaran")
@section("sidebar")@include("layouts.partials.sidebar-mahasiswa")@endsection
@section("content")
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-6">
    @if($applications->isEmpty())
        <div class="p-8 sm:p-12 text-center text-slate-500 flex flex-col items-center">
            <svg class="w-16 h-16 text-slate-200 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
            <p class="font-medium text-slate-600 mb-1">Belum Ada Riwayat Lamaran</p>
            <p class="text-sm text-slate-500 mb-6">Anda belum pernah mendaftar ke organisasi manapun.</p>
            <a href="{{ route('mahasiswa.recruitment.index') }}" class="px-6 py-2.5 bg-blue-600 text-white font-semibold text-sm rounded-xl hover:bg-blue-700 shadow-sm transition-colors">Cari Organisasi</a>
        </div>
    @else
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-slate-50 border-b border-slate-200 text-slate-600 font-semibold uppercase text-xs tracking-wider">
                <tr><th class="px-6 py-4">Organisasi</th><th class="px-6 py-4">Rekrutmen & Divisi</th><th class="px-6 py-4">Tanggal Daftar</th><th class="px-6 py-4">Status</th></tr>
            </thead>
            @foreach($applications->groupBy('recruitment_id') as $recId => $group)
            @php
                $firstApp = $group->first();
                $recruitment = $firstApp->recruitment;
                $ormawa = $recruitment->ormawa;
            @endphp
            <tbody class="border-b-[6px] border-slate-100 last:border-0 hover:bg-slate-50/10 transition-colors">
                {{-- Baris Header Rekrutmen --}}
                <tr class="bg-slate-50/80">
                    <td colspan="4" class="px-6 py-4">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-2">
                            <div>
                                <p class="font-bold text-slate-800 text-lg">{{ $ormawa->nama }}</p>
                                <p class="text-sm font-semibold text-indigo-700 mt-0.5">{{ $recruitment->judul }}</p>
                            </div>
                            <span class="px-3 py-1 bg-white border border-slate-200 text-slate-600 rounded-lg text-xs font-bold shadow-sm whitespace-nowrap">
                                Mendaftar di {{ $group->count() }} Divisi
                            </span>
                        </div>
                    </td>
                </tr>

                {{-- Baris Divisi --}}
                @foreach($group as $app)
                <tr class="border-t border-slate-100 bg-white">
                    <td class="px-6 py-4 sm:pl-10" colspan="2">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-slate-300 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            <span class="inline-block px-2.5 py-1 bg-slate-100/80 text-slate-700 font-semibold rounded text-sm border border-slate-200">
                                {{ $app->division->nama }}
                            </span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-slate-500 font-medium">
                        {{ $app->created_at->format('d/m/Y') }}
                    </td>
                    <td class="px-6 py-4">
                        @php
                            $showStatus = $app->status;
                            if (in_array($app->status, ['diterima', 'ditolak']) && !$recruitment->is_announced) {
                                $showStatus = 'diproses';
                            }
                        @endphp
                        <span class="px-3 py-1 rounded-full text-xs font-bold 
                            {{ $showStatus === 'terkirim' ? 'bg-amber-100 text-amber-700' : 
                               ($showStatus === 'diproses' ? 'bg-blue-100 text-blue-700' : 
                               ($showStatus === 'diterima' ? 'bg-green-100 text-green-700 shadow-sm shadow-green-500/20' : 'bg-red-100 text-red-700')) }}">
                            {{ strtoupper($showStatus) }}
                        </span>
                    </td>
                </tr>
                @endforeach

                {{-- Pesan Panitia (Cukup 1 kali per Rekrutmen) --}}
                @if($recruitment->pesan_setelah_mendaftar)
                <tr class="bg-white">
                    <td colspan="4" class="px-6 pb-5 pt-2 border-t border-slate-50">
                        <div class="bg-blue-50/50 border border-blue-100 rounded-xl p-4 flex gap-3 text-sm shadow-sm sm:ml-6">
                            <svg class="w-5 h-5 text-blue-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <div class="flex-1">
                                <strong class="block text-blue-800 mb-1">Catatan Panitia / Instruksi Lanjutan:</strong>
                                <div class="text-blue-700/90 whitespace-pre-wrap leading-relaxed">{{ $recruitment->pesan_setelah_mendaftar }}</div>
                            </div>
                        </div>
                    </td>
                </tr>
                @endif
            </tbody>
            @endforeach
        </table>
    </div>
    <div class="p-4 border-t border-slate-100">
        {{ $applications->links() }}
    </div>
    @endif
</div>
@endsection