@extends('layouts.app')
@section('title', 'Edit Program Kerja')
@section('page-title', 'Edit Program Kerja')
@section('sidebar')
    @include('layouts.partials.sidebar-admin')
@endsection

@section('content')
<div class="w-full">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
        <form method="POST" action="{{ route('admin.proker.update', $proker) }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')
            
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Nama Program Kerja *</label>
                <input type="text" name="nama" value="{{ old('nama', $proker->nama) }}" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition-all text-sm">
                @error('nama')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Deskripsi <span class="text-xs text-slate-400 font-normal">(opsional)</span></label>
                <textarea name="deskripsi" rows="4" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition-all text-sm">{{ old('deskripsi', $proker->deskripsi) }}</textarea>
            </div>
            
            <div x-data="{ 
                previewUrl: '{{ $proker->foto ? asset('storage/' . $proker->foto) : '' }}', 
                errorMsg: '',
                fileChosen(event) {
                    const file = event.target.files[0];
                    this.errorMsg = '';
                    if (file) {
                        if (!file.type.startsWith('image/')) {
                            this.errorMsg = 'Format file tidak valid. Harap pilih gambar (JPG/PNG/GIF).';
                            this.previewUrl = '{{ $proker->foto ? asset('storage/' . $proker->foto) : '' }}';
                            event.target.value = '';
                            return;
                        }
                        if (file.size > 2 * 1024 * 1024) {
                            this.errorMsg = 'Ukuran file terlalu besar. Maksimal 2MB.';
                            this.previewUrl = '{{ $proker->foto ? asset('storage/' . $proker->foto) : '' }}';
                            event.target.value = '';
                            return;
                        }
                        this.previewUrl = URL.createObjectURL(file);
                    } else {
                        this.previewUrl = '{{ $proker->foto ? asset('storage/' . $proker->foto) : '' }}';
                    }
                }
            }">
                <label class="block text-sm font-bold text-slate-700 mb-2">Foto Dokumentasi / Poster <span class="text-xs text-slate-400 font-normal">(opsional)</span></label>
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
                        <input type="file" name="foto" accept="image/*" @change="fileChosen" class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-colors cursor-pointer border border-slate-200">
                        <p class="mt-1.5 text-xs text-slate-500">Maksimal 2MB. Kosongkan jika tidak ingin mengubah.</p>
                        <template x-if="errorMsg">
                            <p class="mt-1 text-xs text-red-500 font-medium" x-text="errorMsg"></p>
                        </template>
                        @error('foto')<p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>
            
            <div class="flex gap-3 pt-4 border-t border-slate-100">
                <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold rounded-xl shadow-lg shadow-blue-500/25 hover:-translate-y-0.5 transition-all">Simpan Perubahan</button>
                <a href="{{ route('admin.proker.index') }}" class="px-6 py-2.5 bg-slate-100 text-slate-600 font-bold rounded-xl hover:bg-slate-200 transition-colors">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
