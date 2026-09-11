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
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Tingkat *</label>
                    <select name="tingkat" id="tingkat" required class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                        @foreach(['Universitas','Fakultas','Jurusan'] as $t)
                        <option value="{{ $t }}" {{ old('tingkat', $ormawa->tingkat) == $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Fakultas <span class="text-xs text-slate-400 font-normal">(opsional)</span></label>
                    <input type="text" name="fakultas" id="fakultas" value="{{ old('fakultas', $ormawa->fakultas) }}" placeholder="MIPA" class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Jurusan <span class="text-xs text-slate-400 font-normal">(opsional)</span></label>
                    <input type="text" name="jurusan" id="jurusan" value="{{ old('jurusan', $ormawa->jurusan) }}" placeholder="Informatika" class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
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
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Email Kontak</label>
                    <input type="email" name="kontak_email" value="{{ old('kontak_email', $ormawa->kontak_email) }}" class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Instagram</label>
                    <input type="text" name="kontak_instagram" value="{{ old('kontak_instagram', $ormawa->kontak_instagram) }}" class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Logo</label>
                @if($ormawa->logo)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $ormawa->logo) }}" alt="Logo" class="w-16 h-16 object-contain rounded-lg border border-slate-200">
                    </div>
                @endif
                <input type="file" name="logo" accept="image/*" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            </div>
            
            <div class="flex gap-3 pt-2">
                <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-xl shadow-lg shadow-blue-500/25">Simpan Perubahan</button>
                <a href="{{ route('superadmin.ormawa.index') }}" class="px-6 py-2.5 bg-slate-100 text-slate-700 font-medium rounded-xl hover:bg-slate-200">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection