@extends("layouts.app")
@section("title","Profil Ormawa")@section("page-title","Edit Profil Ormawa")
@section("sidebar")@include("layouts.partials.sidebar-admin")@endsection
@section("content")
<div class="w-full">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 bg-slate-50 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-slate-800 text-lg">Informasi Organisasi</h3>
                <p class="text-xs text-slate-500 mt-1">Lengkapi data organisasi Anda untuk ditampilkan di halaman katalog.</p>
            </div>
            @if($ormawa->logo)
                <img src="{{ Storage::url($ormawa->logo) }}" alt="Logo {{ $ormawa->nama }}" class="w-16 h-16 object-contain bg-white rounded-xl shadow-sm border border-slate-200 p-2">
            @else
                <div class="w-16 h-16 bg-white rounded-xl shadow-sm border border-slate-200 flex items-center justify-center">
                    <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
            @endif
        </div>

        <form method="POST" action="{{ route('admin.ormawa-profile.update') }}" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama Ormawa (Disabled) -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5 flex items-center gap-2">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Nama Organisasi
                    </label>
                    <input type="text" value="{{ $ormawa->nama }} ({{ $ormawa->tingkat }})" disabled class="w-full px-4 py-2.5 border border-slate-200 rounded-xl bg-slate-100 text-slate-500 font-medium">
                    <p class="text-[11px] text-slate-500 mt-1">Nama organisasi tidak dapat diubah oleh admin.</p>
                </div>

                <!-- Deskripsi -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5 flex items-center gap-2">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                        Deskripsi
                    </label>
                    <textarea name="deskripsi" rows="3" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-sm placeholder-slate-400" placeholder="Ceritakan secara singkat tentang organisasi ini...">{{ old('deskripsi', $ormawa->deskripsi) }}</textarea>
                </div>

                <!-- Visi -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5 flex items-center gap-2">
                        <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        Visi
                    </label>
                    <textarea name="visi" rows="4" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-sm placeholder-slate-400" placeholder="Visi organisasi Anda...">{{ old('visi', $ormawa->visi) }}</textarea>
                </div>

                <!-- Misi -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5 flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                        Misi
                    </label>
                    <textarea name="misi" rows="4" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-sm placeholder-slate-400" placeholder="1. Misi pertama&#10;2. Misi kedua&#10;3. Misi ketiga">{{ old('misi', $ormawa->misi) }}</textarea>
                </div>

                <!-- Kontak Email -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5 flex items-center gap-2">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        Email Resmi
                    </label>
                    <input type="email" name="kontak_email" value="{{ old('kontak_email', $ormawa->kontak_email) }}" class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-sm" placeholder="email@ormawa.com">
                </div>

                <!-- Instagram -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5 flex items-center gap-2">
                        <svg class="w-4 h-4 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect width="14" height="14" x="5" y="5" rx="4" stroke-width="2"/><circle cx="12" cy="12" r="3" stroke-width="2"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.5 7.5h.01"/></svg>
                        Instagram
                    </label>
                    <input type="text" name="kontak_instagram" value="{{ old('kontak_instagram', $ormawa->kontak_instagram) }}" class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-sm" placeholder="@username">
                </div>

                <!-- Logo -->
                <div class="md:col-span-2" x-data="{ 
                    previewUrl: '{{ $ormawa->logo ? asset('storage/' . $ormawa->logo) : '' }}',
                    fileChosen(event) {
                        const file = event.target.files[0];
                        if (file) {
                            this.previewUrl = URL.createObjectURL(file);
                        }
                    }
                }">
                    <label class="block text-sm font-semibold text-slate-700 mb-2 flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Logo Organisasi
                    </label>
                    <div class="flex items-center gap-6">
                        <!-- Preview Image -->
                        <div class="shrink-0">
                            <template x-if="previewUrl">
                                <img :src="previewUrl" alt="Preview Logo" class="h-24 w-24 object-contain rounded-xl border border-slate-200 shadow-sm bg-white p-2">
                            </template>
                            <template x-if="!previewUrl">
                                <div class="h-24 w-24 rounded-xl border-2 border-dashed border-slate-300 flex items-center justify-center bg-slate-50 text-slate-400">
                                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                            </template>
                        </div>
                        
                        <!-- File Input -->
                        <div class="flex-1">
                            <label class="block">
                                <span class="sr-only">Pilih file logo</span>
                                <input type="file" name="logo" id="logo" accept="image/*" @change="fileChosen"
                                    class="block w-full text-sm text-slate-500
                                    file:mr-4 file:py-2.5 file:px-4
                                    file:rounded-xl file:border-0
                                    file:text-sm file:font-semibold
                                    file:bg-blue-50 file:text-blue-700
                                    hover:file:bg-blue-100 transition-all cursor-pointer border border-slate-200 rounded-xl"
                                />
                            </label>
                            <p class="mt-2 text-xs text-slate-500">Maksimal 2MB. Format yang didukung: PNG, JPG, GIF.</p>
                        </div>
                    </div>
                </div>

                <!-- Cover Photo -->
                <div class="md:col-span-2 mt-4" x-data="{ 
                    previewUrl: '{{ $ormawa->cover_photo ? asset('storage/' . $ormawa->cover_photo) : '' }}',
                    fileChosen(event) {
                        const file = event.target.files[0];
                        if (file) {
                            this.previewUrl = URL.createObjectURL(file);
                        }
                    }
                }">
                    <label class="block text-sm font-semibold text-slate-700 mb-2 flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Foto Sampul (Cover)
                    </label>
                    <div class="flex items-center gap-6">
                        <!-- Preview Image -->
                        <div class="shrink-0">
                            <template x-if="previewUrl">
                                <img :src="previewUrl" alt="Preview Cover" class="w-48 h-24 object-cover rounded-xl border border-slate-200 shadow-sm bg-white p-1">
                            </template>
                            <template x-if="!previewUrl">
                                <div class="w-48 h-24 rounded-xl border-2 border-dashed border-slate-300 flex items-center justify-center bg-slate-50 text-slate-400">
                                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                            </template>
                        </div>
                        
                        <!-- File Input -->
                        <div class="flex-1">
                            <label class="block">
                                <span class="sr-only">Pilih file cover</span>
                                <input type="file" name="cover_photo" id="cover_photo" accept="image/*" @change="fileChosen"
                                    class="block w-full text-sm text-slate-500
                                    file:mr-4 file:py-2.5 file:px-4
                                    file:rounded-xl file:border-0
                                    file:text-sm file:font-semibold
                                    file:bg-blue-50 file:text-blue-700
                                    hover:file:bg-blue-100 transition-all cursor-pointer border border-slate-200 rounded-xl"
                                />
                            </label>
                            <p class="mt-2 text-xs text-slate-500">Gambar landscape untuk banner organisasi di katalog. Maksimal 4MB.</p>
                            @error('cover_photo')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="pt-4 border-t border-slate-100 flex justify-end">
                <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold rounded-xl shadow-lg shadow-blue-500/30 hover:shadow-xl hover:scale-[1.02] transition-all">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection