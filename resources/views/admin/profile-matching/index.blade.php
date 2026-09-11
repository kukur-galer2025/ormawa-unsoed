@extends("layouts.app")
@section("title","Profile Matching")@section("page-title","Profile Matching")
@section("sidebar")@include("layouts.partials.sidebar-admin")@endsection
@section("content")
<div class="mb-6">
    <p class="text-sm text-slate-500">Pilih rekrutmen yang sudah ditutup untuk melihat dan menghitung Profile Matching.</p>
</div>

<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-6">
    @if($recruitments->isEmpty())
        <div class="p-8 text-center text-slate-500">Tidak ada rekrutmen yang ditutup/selesai saat ini.</div>
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
                        <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-700">
                            {{ ucfirst($rec->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-slate-600">{{ $rec->divisions_count }} Divisi</td>
                    <td class="px-6 py-4 text-slate-600">{{ $rec->applications_count }} Pelamar</td>
                    <td class="px-6 py-4 text-center">
                        <a href="{{ route('admin.profile-matching.show', $rec) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-purple-50 text-purple-700 font-semibold rounded-lg hover:bg-purple-100 transition-colors text-xs">
                            Lihat Detail
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
