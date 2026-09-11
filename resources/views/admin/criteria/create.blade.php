@extends("layouts.app")
@section("title","Tambah Kriteria")@section("page-title","Tambah Kriteria")
@section("sidebar")@include("layouts.partials.sidebar-admin")@endsection
@section("content")
<div class="max-w-2xl">
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
<p class="text-sm text-slate-500 mb-4">Rekrutmen: <span class="font-semibold text-slate-700">{{ $recruitment->judul }}</span></p>
<form method="POST" action="{{ route('admin.criteria.store', $recruitment) }}" class="space-y-5">@csrf

<div>
    <label class="block text-sm font-semibold text-slate-700 mb-1">Aspek Penilaian *</label>
    <select name="aspect_id" required class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
        <option value="">-- Pilih Aspek --</option>
        @foreach($recruitment->divisions as $div)
            <optgroup label="Divisi: {{ $div->nama }}">
                @foreach($div->aspects as $aspect)
                    <option value="{{ $aspect->id }}" {{ old('aspect_id') == $aspect->id ? 'selected' : '' }}>
                        {{ $aspect->nama }} (Bobot {{ round($aspect->bobot * 100) }}%)
                    </option>
                @endforeach
            </optgroup>
        @endforeach
    </select>
    @if($recruitment->divisions->flatMap->aspects->isEmpty())
        <p class="mt-1 text-xs text-red-500">Belum ada aspek. <a href="{{ route('admin.aspect.index', $recruitment) }}" class="font-bold underline">Buat aspek dulu</a>.</p>
    @endif
</div>

<div>
    <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Kriteria *</label>
    <input type="text" name="nama_kriteria" value="{{ old('nama_kriteria') }}" required class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500" placeholder="e.g. Intelektual, Bijaksana">
    @error('nama_kriteria')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
</div>

<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-1">Tipe *</label>
        <select name="tipe" required class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
            <option value="core" {{ old('tipe')=='core'?'selected':'' }}>Core Factor (CF)</option>
            <option value="secondary" {{ old('tipe')=='secondary'?'selected':'' }}>Secondary Factor (SF)</option>
        </select>
    </div>
    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-1">Target (Nilai Ideal) *</label>
        <input type="number" name="target_value" value="{{ old('target_value', 4) }}" required min="1" max="5" class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
    </div>
</div>

<div>
    <label class="block text-sm font-semibold text-slate-700 mb-1">Keterangan (Opsional)</label>
    <textarea name="keterangan" rows="2" class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500" placeholder="Deskripsi tambahan tentang kriteria ini">{{ old('keterangan') }}</textarea>
</div>

<div class="border border-slate-200 rounded-xl p-4 bg-slate-50">
    <h4 class="text-sm font-bold text-slate-700 mb-3 flex items-center gap-2">
        <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
        Label Deskripsi Nilai (1-5) *
    </h4>
    <p class="text-xs text-slate-500 mb-3">Tentukan deskripsi untuk setiap level nilai penilaian kriteria ini.</p>
    <div class="space-y-2">
        @for($i = 1; $i <= 5; $i++)
        <div class="flex items-center gap-3">
            <span class="w-8 h-8 flex items-center justify-center bg-{{ $i <= 2 ? 'red' : ($i == 3 ? 'amber' : 'green') }}-100 text-{{ $i <= 2 ? 'red' : ($i == 3 ? 'amber' : 'green') }}-700 rounded-lg text-sm font-bold shrink-0">{{ $i }}</span>
            <input type="text" name="labels[]" value="{{ old('labels.' . ($i-1), ['Sangat Kurang','Kurang','Cukup','Baik','Sangat Baik'][$i-1]) }}" required class="flex-1 px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500" placeholder="Deskripsi untuk nilai {{ $i }}">
        </div>
        @endfor
    </div>
</div>

<div class="flex gap-3 pt-2">
    <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-xl shadow-lg shadow-blue-500/25">Simpan</button>
    <a href="{{ route('admin.criteria.index', $recruitment) }}" class="px-6 py-2.5 bg-slate-100 text-slate-700 font-medium rounded-xl hover:bg-slate-200">Batal</a>
</div>
</form>
</div>
</div>
@endsection