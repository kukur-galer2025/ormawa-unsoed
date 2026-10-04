@extends("layouts.app")
@section("title","Edit Ormawa")
@section("page-title","Edit Ormawa")
@section("sidebar")
    @include("layouts.partials.sidebar-superadmin")
@endsection

@section("content")
<div class="max-w-2xl">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
        <form method="POST" action="{{ route('superadmin.ormawa.update', $ormawa) }}" class="space-y-5">
            @csrf
            @method('PUT')
            
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Nama Ormawa *</label>
                <input type="text" name="nama" value="{{ old('nama', $ormawa->nama) }}" required class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                @error('nama')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4" x-data="{ tingkat: '{{ old('tingkat', $ormawa->tingkat) }}', fakultas_id: '{{ old('fakultas_id', $ormawa->fakultas_id) }}', jurusan_id: '{{ old('jurusan_id', $ormawa->jurusan_id) }}', fakultasList: {{ Js::from($fakultas) }}, get filteredJurusan() { const f = this.fakultasList.find(f => f.id == this.fakultas_id); return f ? f.jurusans : []; } }">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Tingkat *</label>
                    <select name="tingkat" x-model="tingkat" @change="if(tingkat === 'Universitas') { fakultas_id = ''; jurusan_id = ''; } else if(tingkat === 'Fakultas') { jurusan_id = ''; }" required class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 bg-white">
                        <option value="Universitas">Universitas</option>
                        <option value="Fakultas">Fakultas</option>
                        <option value="Jurusan">Jurusan</option>
                    </select>
                    @error('tingkat')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
                <div x-show="tingkat === 'Fakultas' || tingkat === 'Jurusan'" x-cloak>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Fakultas *</label>
                    <select name="fakultas_id" x-model="fakultas_id" @change="jurusan_id = ''" :required="tingkat === 'Fakultas' || tingkat === 'Jurusan'" class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 bg-white">
                        <option value="">-- Pilih Fakultas --</option>
                        <template x-for="f in fakultasList" :key="f.id">
                            <option :value="f.id" x-text="f.nama_fakultas" :selected="f.id == fakultas_id"></option>
                        </template>
                    </select>
                    @error('fakultas_id')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
                <div x-show="tingkat === 'Jurusan'" x-cloak>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Jurusan *</label>
                    <select name="jurusan_id" x-model="jurusan_id" :required="tingkat === 'Jurusan'" :disabled="!fakultas_id || filteredJurusan.length === 0" class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 bg-white">
                        <option value="">-- Pilih Jurusan --</option>
                        <template x-for="j in filteredJurusan" :key="j.id">
                            <option :value="j.id" x-text="j.nama_jurusan" :selected="j.id == jurusan_id"></option>
                        </template>
                    </select>
                    @error('jurusan_id')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
            </div>


            
            <div class="flex gap-3 pt-2">
                <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-xl shadow-lg shadow-blue-500/25">Simpan Perubahan</button>
                <a href="{{ route('superadmin.ormawa.index') }}" class="px-6 py-2.5 bg-slate-100 text-slate-700 font-medium rounded-xl hover:bg-slate-200">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection