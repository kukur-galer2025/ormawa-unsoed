@extends("layouts.app")
@section("title","Edit Ormawa")
@section("page-title","Edit Ormawa")
@section("sidebar")
    @include("layouts.partials.sidebar-superadmin")
@endsection

@section("content")
<div class="max-w-2xl">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
        <form method="POST" action="{{ route('superadmin.ormawa.update', $ormawa) }}" enctype="multipart/form-data" class="space-y-5">
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

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Deskripsi</label>
                <textarea name="deskripsi" rows="3" class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">{{ old('deskripsi', $ormawa->deskripsi) }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Visi & Misi</label>
                <textarea name="visi_misi" rows="3" class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">{{ old('visi_misi', $ormawa->visi_misi) }}</textarea>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Email Kontak</label>
                    <input type="email" name="kontak_email" value="{{ old('kontak_email', $ormawa->kontak_email) }}" class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Instagram</label>
                    <input type="text" name="kontak_instagram" value="{{ old('kontak_instagram', $ormawa->kontak_instagram) }}" class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                </div>
            </div>
            
            <div x-data="{ 
                previewUrl: '{{ $ormawa->logo ? asset('storage/' . $ormawa->logo) : '' }}', 
                errorMsg: '',
                fileChosen(event) {
                    const file = event.target.files[0];
                    this.errorMsg = '';
                    if (file) {
                        if (!file.type.startsWith('image/')) {
                            this.errorMsg = 'Format file tidak valid. Harap pilih gambar (JPG/PNG/GIF).';
                            this.previewUrl = '{{ $ormawa->logo ? asset('storage/' . $ormawa->logo) : '' }}';
                            event.target.value = '';
                            return;
                        }
                        if (file.size > 2 * 1024 * 1024) {
                            this.errorMsg = 'Ukuran file terlalu besar. Maksimal 2MB.';
                            this.previewUrl = '{{ $ormawa->logo ? asset('storage/' . $ormawa->logo) : '' }}';
                            event.target.value = '';
                            return;
                        }
                        this.previewUrl = URL.createObjectURL(file);
                    } else {
                        this.previewUrl = '{{ $ormawa->logo ? asset('storage/' . $ormawa->logo) : '' }}';
                    }
                }
            }">
                <label class="block text-sm font-medium text-slate-700 mb-2">Logo <span class="text-xs text-slate-400 font-normal">(opsional)</span></label>
                <div class="flex items-center gap-4">
                    <div class="shrink-0">
                        <template x-if="previewUrl">
                            <img :src="previewUrl" class="h-16 w-16 object-contain rounded-xl border border-slate-200 shadow-sm p-0.5 bg-white">
                        </template>
                        <template x-if="!previewUrl">
                            <div class="h-16 w-16 bg-slate-50 border-2 border-dashed border-slate-200 rounded-xl flex items-center justify-center text-slate-300">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        </template>
                    </div>
                    <div class="flex-1 min-w-0">
                        <input type="file" name="logo" accept="image/*" @change="fileChosen" class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-colors cursor-pointer border border-slate-200">
                        <p class="mt-1.5 text-xs text-slate-500">Maksimal 2MB. Format yang didukung: JPG, PNG, GIF. Kosongkan jika tidak ingin mengubah.</p>
                        <template x-if="errorMsg">
                            <p class="mt-1 text-xs text-red-500 font-medium" x-text="errorMsg"></p>
                        </template>
                        @error('logo')<p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <div x-data="{ 
                previewUrl: '{{ $ormawa->cover_photo ? asset('storage/' . $ormawa->cover_photo) : '' }}', 
                errorMsg: '',
                fileChosen(event) {
                    const file = event.target.files[0];
                    this.errorMsg = '';
                    if (file) {
                        if (!file.type.startsWith('image/')) {
                            this.errorMsg = 'Format file tidak valid. Harap pilih gambar (JPG/PNG/GIF).';
                            this.previewUrl = '{{ $ormawa->cover_photo ? asset('storage/' . $ormawa->cover_photo) : '' }}';
                            event.target.value = '';
                            return;
                        }
                        if (file.size > 4 * 1024 * 1024) { // Cover boleh 4MB
                            this.errorMsg = 'Ukuran file terlalu besar. Maksimal 4MB.';
                            this.previewUrl = '{{ $ormawa->cover_photo ? asset('storage/' . $ormawa->cover_photo) : '' }}';
                            event.target.value = '';
                            return;
                        }
                        this.previewUrl = URL.createObjectURL(file);
                    } else {
                        this.previewUrl = '{{ $ormawa->cover_photo ? asset('storage/' . $ormawa->cover_photo) : '' }}';
                    }
                }
            }">
                <label class="block text-sm font-medium text-slate-700 mb-2">Foto Sampul (Cover) <span class="text-xs text-slate-400 font-normal">(opsional)</span></label>
                <div class="flex items-center gap-4">
                    <div class="shrink-0">
                        <template x-if="previewUrl">
                            <img :src="previewUrl" class="h-16 w-24 object-cover rounded-xl border border-slate-200 shadow-sm p-0.5">
                        </template>
                        <template x-if="!previewUrl">
                            <div class="h-16 w-24 bg-slate-50 border-2 border-dashed border-slate-200 rounded-xl flex items-center justify-center text-slate-300">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        </template>
                    </div>
                    <div class="flex-1 min-w-0">
                        <input type="file" name="cover_photo" accept="image/*" @change="fileChosen" class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-colors cursor-pointer border border-slate-200">
                        <p class="mt-1.5 text-xs text-slate-500">Gambar landscape untuk banner organisasi di halaman katalog. Maks 4MB.</p>
                        <template x-if="errorMsg">
                            <p class="mt-1 text-xs text-red-500 font-medium" x-text="errorMsg"></p>
                        </template>
                        @error('cover_photo')<p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>@enderror
                    </div>
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