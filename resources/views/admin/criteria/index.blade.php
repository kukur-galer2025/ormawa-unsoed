@extends("layouts.app")
@section("title","Kriteria")@section("page-title","Kelola Kriteria")
@section("sidebar")@include("layouts.partials.sidebar-admin")@endsection
@section("content")
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
    <div>
        <p class="text-sm text-slate-500">Rekrutmen: <span class="font-semibold text-slate-700">{{ $recruitment->judul }}</span></p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('admin.criteria.create', $recruitment) }}" class="px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-sm font-semibold rounded-xl shadow-sm hover:shadow-md">+ Tambah Kriteria</a>
        <a href="{{ route('admin.aspect.index', $recruitment) }}" class="px-4 py-2 bg-purple-50 text-purple-700 text-sm font-semibold rounded-xl hover:bg-purple-100">⚙ Kelola Aspek</a>
        <a href="{{ route('admin.recruitment.show', $recruitment) }}" class="px-4 py-2 bg-slate-100 text-slate-700 text-sm font-medium rounded-xl hover:bg-slate-200">← Kembali</a>
    </div>
</div>

@foreach($recruitment->divisions as $div)
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-6">
    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50">
        <h3 class="font-bold text-slate-800 text-lg">{{ $div->nama }}</h3>
        <p class="text-xs text-slate-500 mt-1">{{ $div->aspects->count() }} Aspek • {{ $div->aspects->sum(fn($a) => $a->criteria->count()) }} Kriteria total</p>
    </div>

    @if($div->aspects->isEmpty())
        <div class="p-6 text-center text-slate-500 text-sm">
            Belum ada aspek. <a href="{{ route('admin.aspect.index', $recruitment) }}" class="text-blue-600 font-semibold">Buat aspek terlebih dahulu</a>.
        </div>
    @else
        @foreach($div->aspects as $aspect)
        <div class="border-b border-slate-100 last:border-b-0">
            <div class="px-6 py-3 bg-gradient-to-r from-indigo-50 to-blue-50 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="px-2.5 py-1 bg-indigo-100 text-indigo-700 rounded-lg text-xs font-bold">{{ $aspect->nama }}</span>
                    <span class="text-xs text-slate-500">Bobot: {{ round($aspect->bobot * 100) }}%</span>
                    <span class="text-xs text-slate-500">CF: {{ $aspect->cf_percentage }}% / SF: {{ $aspect->sf_percentage }}%</span>
                </div>
            </div>

            @if($aspect->criteria->isEmpty())
                <div class="px-6 py-4 text-sm text-slate-400 italic">Belum ada kriteria di aspek ini.</div>
            @else
                <table class="w-full text-sm text-left">
                    <thead class="text-slate-500 text-xs uppercase border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-3">Nama Kriteria</th>
                            <th class="px-6 py-3">Tipe</th>
                            <th class="px-6 py-3 text-center">Target</th>
                            <th class="px-6 py-3">Label Nilai</th>
                            <th class="px-6 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($aspect->criteria as $c)
                        <tr class="hover:bg-slate-50/50">
                            <td class="px-6 py-3">
                                <p class="font-semibold text-slate-800">{{ $c->nama_kriteria }}</p>
                                @if($c->keterangan)<p class="text-xs text-slate-500 mt-0.5">{{ $c->keterangan }}</p>@endif
                            </td>
                            <td class="px-6 py-3">
                                <span class="px-2 py-0.5 rounded text-xs font-bold {{ $c->tipe == 'core' ? 'bg-blue-100 text-blue-700' : 'bg-amber-100 text-amber-700' }}">
                                    {{ $c->tipe == 'core' ? 'CF' : 'SF' }}
                                </span>
                            </td>
                            <td class="px-6 py-3 text-center font-bold text-slate-700">{{ $c->target_value }}</td>
                            <td class="px-6 py-3">
                                @if($c->valueLabels->isNotEmpty())
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($c->valueLabels as $vl)
                                            <span class="text-[10px] px-1.5 py-0.5 bg-slate-100 text-slate-600 rounded">{{ $vl->value }}={{ $vl->label }}</span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-xs text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-3 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    <a href="{{ route('admin.criteria.edit', [$recruitment, $c]) }}" class="p-1.5 bg-slate-100 text-slate-600 rounded-lg hover:bg-slate-200" title="Edit">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <form action="{{ route('admin.criteria.destroy', [$recruitment, $c]) }}" method="POST" onsubmit="confirmForm(event, 'Hapus kriteria ini?')">@csrf @method('DELETE')
                                        <button type="submit" class="p-1.5 bg-red-50 text-red-500 rounded-lg hover:bg-red-100">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
        @endforeach
    @endif
</div>
@endforeach
@endsection