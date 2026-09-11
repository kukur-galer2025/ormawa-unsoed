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
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5 flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Upload Logo Baru
                    </label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-300 border-dashed rounded-xl hover:bg-slate-50 hover:border-blue-400 transition-colors">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-10 w-10 text-slate-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true"><path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg>
                            <div class="flex text-sm text-slate-600 justify-center">
                                <label for="logo" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                    <span>Pilih file</span>
                                    <input id="logo" name="logo" type="file" class="sr-only" accept="image/*">
                                </label>
                                <p class="pl-1">atau drag and drop</p>
                            </div>
                            <p class="text-xs text-slate-500">PNG, JPG, GIF max 2MB</p>
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