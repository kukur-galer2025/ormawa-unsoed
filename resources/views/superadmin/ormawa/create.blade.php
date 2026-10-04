@extends('layouts.app')
@section('title', 'Tambah Ormawa')
@section('page-title', 'Tambah Ormawa')
@section('sidebar')
    @include('layouts.partials.sidebar-superadmin')
@endsection
@section('content')
<div class="mb-6">
    <a href="{{ route('superadmin.ormawa.index') }}" class="text-sm text-blue-600 hover:underline">&larr; Kembali ke Daftar Ormawa</a>
</div>

<div class="max-w-2xl bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
    <div class="mb-6 pb-4 border-b border-slate-100">
        <h3 class="text-lg font-bold text-slate-800">Tambah Ormawa Baru</h3>
        <p class="text-sm text-slate-500">Detail profil organisasi dapat dilengkapi nanti oleh Admin Ormawa.</p>
    </div>

    <form method="POST" action="{{ route('superadmin.ormawa.store') }}" class="space-y-5">
        @csrf

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Nama Ormawa <span class="text-red-500">*</span></label>
            <input type="text" name="nama" value="{{ old('nama') }}" required placeholder="Contoh: BEM UNSOED" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
            @error('nama')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4" x-data="{ tingkat: '{{ old('tingkat', 'Universitas') }}', fakultas_id: '{{ old('fakultas_id') }}', jurusan_id: '{{ old('jurusan_id') }}', fakultasList: {{ Js::from($fakultas) }}, get filteredJurusan() { const f = this.fakultasList.find(f => f.id == this.fakultas_id); return f ? f.jurusans : []; } }">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Tingkat <span class="text-red-500">*</span></label>
                <select name="tingkat" x-model="tingkat" @change="if(tingkat === 'Universitas') { fakultas_id = ''; jurusan_id = ''; } else if(tingkat === 'Fakultas') { jurusan_id = ''; }" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                    <option value="Universitas">Universitas</option>
                    <option value="Fakultas">Fakultas</option>
                    <option value="Jurusan">Jurusan</option>
                </select>
                @error('tingkat')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>
            <div x-show="tingkat === 'Fakultas' || tingkat === 'Jurusan'" x-cloak>
                <label class="block text-sm font-medium text-slate-700 mb-1">Fakultas <span class="text-red-500">*</span></label>
                <select name="fakultas_id" x-model="fakultas_id" @change="jurusan_id = ''" :required="tingkat === 'Fakultas' || tingkat === 'Jurusan'" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                    <option value="">-- Pilih Fakultas --</option>
                    <template x-for="f in fakultasList" :key="f.id">
                        <option :value="f.id" x-text="f.nama_fakultas" :selected="f.id == fakultas_id"></option>
                    </template>
                </select>
                @error('fakultas_id')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>
            <div x-show="tingkat === 'Jurusan'" x-cloak>
                <label class="block text-sm font-medium text-slate-700 mb-1">Jurusan <span class="text-red-500">*</span></label>
                <select name="jurusan_id" x-model="jurusan_id" :required="tingkat === 'Jurusan'" :disabled="!fakultas_id || filteredJurusan.length === 0" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                    <option value="">-- Pilih Jurusan --</option>
                    <template x-for="j in filteredJurusan" :key="j.id">
                        <option :value="j.id" x-text="j.nama_jurusan" :selected="j.id == jurusan_id"></option>
                    </template>
                </select>
                @error('jurusan_id')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>
        </div>
        
        <div class="flex gap-3 pt-4 border-t border-slate-100">
            <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-xl shadow-lg shadow-blue-500/25 hover:from-blue-700 hover:to-indigo-700 transition-all">Simpan Ormawa</button>
            <a href="{{ route('superadmin.ormawa.index') }}" class="px-6 py-2.5 bg-slate-100 text-slate-700 font-medium rounded-xl hover:bg-slate-200 transition-colors">Batal</a>
        </div>
    </form>
</div>
@endsection
