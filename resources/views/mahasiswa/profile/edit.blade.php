@extends("layouts.app")
@section("title","Profil Saya")@section("page-title","Edit Profil")
@section("sidebar")@include("layouts.partials.sidebar-mahasiswa")@endsection
@section("content")
<div class="max-w-2xl">

{{-- Flash error dari middleware EnsureProfileComplete --}}
@if(session('error'))
<div class="mb-4 p-4 bg-amber-50 border border-amber-300 rounded-xl flex items-start gap-3">
    <svg class="w-5 h-5 text-amber-500 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
    <p class="text-sm font-medium text-amber-800">{{ session('error') }}</p>
</div>
@endif

<div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 sm:p-6" 
     x-data="{
        fakultas_id: '{{ old('fakultas_id', $profile->fakultas_id ?? '') }}',
        jurusan_id: '{{ old('jurusan_id', $profile->jurusan_id ?? '') }}',
        allJurusan: {{ $jurusanList->toJson() }},
        get filteredJurusan() {
            if (!this.fakultas_id) return [];
            return this.allJurusan.filter(j => j.fakultas_id == this.fakultas_id);
        }
     }">
<form method="POST" action="{{ route('mahasiswa.profile.update') }}" enctype="multipart/form-data" class="space-y-5">@csrf @method('PUT')

<div>
    <label class="block text-sm font-medium text-slate-700 mb-1">Nama Lengkap</label>
    <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
    @error('name')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
</div>

<div>
    <label class="block text-sm font-medium text-slate-700 mb-1">
        NIM
        <span class="ml-1 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-700">Wajib diisi</span>
    </label>
    <input type="text" name="nim" value="{{ old('nim', $profile->nim) }}" required placeholder="Contoh: H1A020001" class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
    @error('nim')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">
            Fakultas
            <span class="ml-1 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-700">Wajib diisi</span>
        </label>
        <select name="fakultas_id" x-model="fakultas_id" @change="jurusan_id = ''" required class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 bg-white">
            <option value="">Pilih Fakultas</option>
            @foreach($fakultasList as $f)
                <option value="{{ $f->id }}">{{ $f->nama_fakultas }}</option>
            @endforeach
        </select>
        @error('fakultas_id')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">
            Jurusan
            <span class="ml-1 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-700">Wajib diisi</span>
        </label>
        <select name="jurusan_id" x-model="jurusan_id" required class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 bg-white" :disabled="!fakultas_id || filteredJurusan.length === 0">
            <option value="">Pilih Jurusan</option>
            <template x-for="j in filteredJurusan" :key="j.id">
                <option :value="j.id" x-text="j.nama_jurusan"></option>
            </template>
        </select>
        @error('jurusan_id')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Angkatan</label>
        <input type="number" name="angkatan" value="{{ old('angkatan', $profile->angkatan) }}" required min="2015" max="{{ now()->year }}" class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
        @error('angkatan')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">No. HP</label>
        <input type="text" name="no_hp" value="{{ old('no_hp', $profile->no_hp) }}" class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
        @error('no_hp')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
    </div>
</div>

<div x-data="{ photoPreview: '{{ $profile->foto ? asset('storage/' . $profile->foto) : '' }}' }">
    <label class="block text-sm font-medium text-slate-700 mb-2">Foto Profil</label>
    <div class="flex items-center gap-4">
        <!-- Preview Box -->
        <template x-if="photoPreview">
            <img :src="photoPreview" class="h-16 w-16 object-cover rounded-xl border border-slate-200 shrink-0">
        </template>
        <template x-if="!photoPreview">
            <div class="h-16 w-16 bg-slate-100 border border-slate-200 rounded-xl flex items-center justify-center text-slate-400 shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
        </template>

        <!-- Input File -->
        <div class="flex-1">
            <input type="file" name="foto" accept="image/jpeg,image/png,image/jpg" 
                   @change="const file = $event.target.files[0]; if (file) { const reader = new FileReader(); reader.onload = (e) => { photoPreview = e.target.result; }; reader.readAsDataURL(file); } else { photoPreview = '{{ $profile->foto ? asset('storage/' . $profile->foto) : '' }}'; }" 
                   class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            <p class="mt-1.5 text-xs text-slate-500">Format: JPG/PNG/JPEG. Ukuran maksimal: 2MB.</p>
            @error('foto')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
        </div>
    </div>
</div>

<div class="pt-2">
    <button type="submit" class="w-full sm:w-auto px-6 py-3 sm:py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-xl shadow-lg shadow-blue-500/25">Simpan Profil</button>
</div>

</form></div></div>
@endsection