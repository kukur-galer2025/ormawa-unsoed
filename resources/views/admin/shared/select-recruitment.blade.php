@extends("layouts.app")
@section("title", $pageTitle ?? "Pilih Rekrutmen")
@section("page-title", $pageTitle ?? "Pilih Rekrutmen")
@section("sidebar")@include("layouts.partials.sidebar-admin")@endsection
@section("content")
<div class="mb-6">
    <p class="text-sm text-slate-500">{{ $pageDescription ?? 'Pilih rekrutmen untuk mengelola data.' }}</p>
</div>

<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-6">
    @if($recruitments->isEmpty())
        <div class="p-8 text-center text-slate-500">Belum ada rekrutmen saat ini.</div>
    @else
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-slate-50 border-b border-slate-200 text-slate-600 font-semibold">
                <tr>
                    <th class="px-6 py-4">Judul Rekrutmen</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Divisi</th>
                    <th class="px-6 py-4">Total Pelamar</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
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
                    <td class="px-6 py-4 text-center">
                        <a href="{{ route($targetRoute, $rec) }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-lg hover:from-blue-500 hover:to-indigo-500 shadow-sm transition-all text-xs">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/></svg>
                            Pilih
                        </a>
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
