@extends('layouts.app')
@section('title', 'Tambah Prestasi')
@section('page-title', 'Tambah Prestasi')
@section('sidebar')
    @include('layouts.partials.sidebar-admin')
@endsection

@section('content')
<div class="w-full">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
        <form method="POST" action="{{ route('admin.prestasi.store') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Judul Prestasi *</label>
                <input type="text" name="judul" value="{{ old('judul') }}" required placeholder="Juara 1 Lomba Nasional..." class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition-all text-sm">
                @error('judul')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Tahun <span class="text-xs text-slate-400 font-normal">(opsional)</span></label>
                <input type="number" name="tahun" value="{{ old('tahun', date('Y')) }}" min="2000" max="{{ date('Y')+1 }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition-all text-sm">
                @error('tahun')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Deskripsi Singkat <span class="text-xs text-slate-400 font-normal">(opsional)</span></label>
                <textarea name="deskripsi" rows="3" placeholder="Deskripsikan prestasi ini..." class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition-all text-sm">{{ old('deskripsi') }}</textarea>
            </div>
            
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Foto / Dokumentasi <span class="text-xs text-slate-400 font-normal">(opsional, max 2MB)</span></label>
                <input type="file" name="foto" accept="image/*" class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-colors">
                @error('foto')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>
            
            <div class="flex gap-3 pt-4 border-t border-slate-100">
                <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold rounded-xl shadow-lg shadow-blue-500/25 hover:-translate-y-0.5 transition-all">Simpan Prestasi</button>
                <a href="{{ route('admin.prestasi.index') }}" class="px-6 py-2.5 bg-slate-100 text-slate-600 font-bold rounded-xl hover:bg-slate-200 transition-colors">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
