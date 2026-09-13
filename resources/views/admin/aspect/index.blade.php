@extends("layouts.app")
@section("title","Aspek & Kriteria")@section("page-title","Kelola Aspek Penilaian")
@section("sidebar")@include("layouts.partials.sidebar-admin")@endsection
@section("content")
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
    <div>
        <p class="text-sm text-slate-500">Rekrutmen: <span class="font-semibold text-slate-700">{{ $recruitment->judul }}</span></p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('admin.criteria.index', $recruitment) }}" class="px-4 py-2 bg-blue-50 text-blue-700 text-sm font-semibold rounded-xl hover:bg-blue-100">📋 Kelola Kriteria</a>
        <a href="{{ route('admin.recruitment.show', $recruitment) }}" class="px-4 py-2 bg-slate-100 text-slate-700 text-sm font-medium rounded-xl hover:bg-slate-200">← Kembali</a>
    </div>
</div>

<div x-data="{ selectedDivision: 'all' }">
    <div class="mb-6 max-w-xs">
        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Filter Divisi</label>
        <div class="relative">
            <select x-model="selectedDivision" class="w-full appearance-none bg-white border border-slate-300 text-slate-700 py-2.5 pl-4 pr-10 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all shadow-sm">
                <option value="all">Semua Divisi</option>
                @foreach($recruitment->divisions as $divOption)
                    <option value="{{ $divOption->id }}">{{ $divOption->nama }}</option>
                @endforeach
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-400">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
            </div>
        </div>
    </div>

    @foreach($recruitment->divisions as $div)
    <div x-show="selectedDivision === 'all' || selectedDivision == '{{ $div->id }}'" x-transition.opacity.duration.300ms class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-6">
    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex items-center justify-between">
        <div>
            <h3 class="font-bold text-slate-800 text-lg">{{ $div->nama }}</h3>
            @php $totalBobot = $div->aspects->sum('bobot'); @endphp
            <p class="text-xs text-slate-500 mt-1">
                Total Bobot: <span class="{{ abs($totalBobot - 1.0) < 0.01 ? 'text-green-600 font-bold' : 'text-red-500 font-bold' }}">{{ round($totalBobot * 100) }}%</span>
                @if(abs($totalBobot - 1.0) > 0.01)
                    <span class="text-red-500"> (harus 100%)</span>
                @else
                    <span class="text-green-600"> ✓</span>
                @endif
            </p>
        </div>
    </div>

    {{-- Existing Aspects --}}
    @if($div->aspects->isEmpty())
        <div class="p-6 text-center text-slate-500 text-sm">Belum ada aspek penilaian di divisi ini.</div>
    @else
        <div class="divide-y divide-slate-100">
            @foreach($div->aspects as $aspect)
            <div class="p-5" x-data="{ editing: false }">
                <div x-show="!editing" class="flex items-center justify-between">
                    <div>
                        <p class="font-bold text-slate-800">{{ $aspect->nama }}</p>
                        <div class="flex gap-3 mt-1 text-xs text-slate-500">
                            <span class="px-2 py-0.5 bg-indigo-50 text-indigo-600 rounded font-semibold">Bobot: {{ round($aspect->bobot * 100) }}%</span>
                            <span class="px-2 py-0.5 bg-blue-50 text-blue-600 rounded">CF: {{ $aspect->cf_percentage }}%</span>
                            <span class="px-2 py-0.5 bg-amber-50 text-amber-600 rounded">SF: {{ $aspect->sf_percentage }}%</span>
                            <span class="text-slate-400">{{ $aspect->criteria->count() }} kriteria</span>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button @click="editing = true" class="px-3 py-1.5 text-xs bg-slate-100 text-slate-600 rounded-lg hover:bg-slate-200">Edit</button>
                        <form action="{{ route('admin.aspect.destroy', [$recruitment, $aspect]) }}" method="POST" onsubmit="confirmForm(event, 'Hapus aspek ini beserta semua kriterianya?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="px-3 py-1.5 text-xs bg-red-50 text-red-600 rounded-lg hover:bg-red-100">Hapus</button>
                        </form>
                    </div>
                </div>

                {{-- Inline Edit Form --}}
                <form x-show="editing" action="{{ route('admin.aspect.update', [$recruitment, $aspect]) }}" method="POST" class="space-y-3">
                    @csrf @method('PUT')
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-slate-600 mb-1">Nama Aspek</label>
                            <input type="text" name="nama" value="{{ $aspect->nama }}" required class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-600 mb-1">Bobot (%)</label>
                            <input type="number" name="bobot" value="{{ $aspect->bobot * 100 }}" step="1" min="1" max="100" required class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-600 mb-1">CF %</label>
                            <input type="number" name="cf_percentage" value="{{ $aspect->cf_percentage }}" step="0.01" min="0" max="100" required class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-600 mb-1">SF %</label>
                            <input type="number" name="sf_percentage" value="{{ $aspect->sf_percentage }}" step="0.01" min="0" max="100" required class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg">
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="px-4 py-1.5 text-xs bg-blue-600 text-white font-semibold rounded-lg">Simpan</button>
                        <button type="button" @click="editing = false" class="px-4 py-1.5 text-xs bg-slate-100 text-slate-600 rounded-lg">Batal</button>
                    </div>
                </form>
            </div>
            @endforeach
        </div>
    @endif

    {{-- Add New Aspect Form --}}
    <div class="p-5 bg-slate-50 border-t border-slate-100" x-data="{ open: false }">
        <button @click="open = !open" class="text-sm font-semibold text-blue-600 hover:text-blue-800 flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            Tambah Aspek Baru
        </button>
        <form x-show="open" x-transition action="{{ route('admin.aspect.store', $recruitment) }}" method="POST" class="mt-4 space-y-3">
            @csrf
            <input type="hidden" name="recruitment_division_id" value="{{ $div->id }}">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">Nama Aspek *</label>
                    <input type="text" name="nama" required placeholder="e.g. Kecerdasan" class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">Bobot (%) *</label>
                    <input type="number" name="bobot" step="1" min="1" max="100" required placeholder="30" class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">CF % *</label>
                    <input type="number" name="cf_percentage" step="0.01" min="0" max="100" required value="60" class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">SF % *</label>
                    <input type="number" name="sf_percentage" step="0.01" min="0" max="100" required value="40" class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg">
                </div>
            </div>
            <p class="text-[11px] text-slate-500">CF% + SF% harus = 100%.</p>
            <button type="submit" class="px-4 py-2 text-sm bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-xl shadow-sm">Tambah Aspek</button>
        </form>
    </div>
</div>
@endforeach
</div>
@endsection
