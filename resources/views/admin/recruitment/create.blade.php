@extends("layouts.app")
@section("title","Buat Rekrutmen")@section("page-title","Buat Rekrutmen Baru")
@section("sidebar")@include("layouts.partials.sidebar-admin")@endsection
@section("content")
<div class="w-full" x-data="divisionManager()">
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
<form method="POST" action="{{ route('admin.recruitment.store') }}" class="space-y-5">@csrf

{{-- Info Umum --}}
<div><label class="block text-sm font-medium text-slate-700 mb-1">Judul Rekrutmen *</label><input type="text" name="judul" value="{{ old('judul') }}" required class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">@error('judul')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror</div>
<div><label class="block text-sm font-medium text-slate-700 mb-1">Deskripsi</label><textarea name="deskripsi" rows="3" class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">{{ old('deskripsi') }}</textarea></div>
<div><label class="block text-sm font-medium text-slate-700 mb-1">Persyaratan</label><textarea name="persyaratan" rows="4" class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500" placeholder="1. Mahasiswa aktif UNSOED...">{{ old('persyaratan') }}</textarea></div>
<div class="grid grid-cols-2 gap-4"><div><label class="block text-sm font-medium text-slate-700 mb-1">Tanggal Buka *</label><input type="date" name="tanggal_buka" value="{{ old('tanggal_buka') }}" required class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"></div><div><label class="block text-sm font-medium text-slate-700 mb-1">Tanggal Tutup *</label><input type="date" name="tanggal_tutup" value="{{ old('tanggal_tutup') }}" required class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"></div></div>

{{-- Divisi --}}
<div class="border-t border-slate-200 pt-5 mt-5">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h3 class="text-sm font-bold text-slate-800">Divisi Rekrutmen *</h3>
            <p class="text-xs text-slate-500">Tambahkan divisi yang dibuka beserta kuotanya</p>
        </div>
        <button type="button" @click="addDivision()" class="px-3 py-1.5 text-xs font-semibold bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors">+ Tambah Divisi</button>
    </div>
    
    <div class="space-y-3">
        <template x-for="(div, index) in divisions" :key="index">
            <div class="bg-slate-50 rounded-xl p-4 border border-slate-200 relative">
                <button type="button" @click="removeDivision(index)" x-show="divisions.length > 1" class="absolute top-2 right-2 w-6 h-6 rounded-full bg-red-50 text-red-500 hover:bg-red-100 flex items-center justify-center text-xs">&times;</button>
                <div class="grid grid-cols-3 gap-3">
                    <div class="col-span-2">
                        <label class="block text-xs font-medium text-slate-600 mb-1">Nama Divisi *</label>
                        <input type="text" :name="'divisions['+index+'][nama]'" x-model="div.nama" required placeholder="Misal: Divisi Sosial" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1">Kuota *</label>
                        <input type="number" :name="'divisions['+index+'][kuota]'" x-model="div.kuota" required min="1" placeholder="5" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                    </div>
                </div>
                <div class="mt-2">
                    <label class="block text-xs font-medium text-slate-600 mb-1">Deskripsi Divisi</label>
                    <input type="text" :name="'divisions['+index+'][deskripsi]'" x-model="div.deskripsi" placeholder="Opsional: Deskripsi singkat divisi" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                </div>
            </div>
        </template>
    </div>
</div>

<div class="flex gap-3 pt-2"><button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-xl shadow-lg shadow-blue-500/25">Simpan</button><a href="{{ route('admin.recruitment.index') }}" class="px-6 py-2.5 bg-slate-100 text-slate-700 font-medium rounded-xl hover:bg-slate-200">Batal</a></div>
</form></div></div>

<script>
function divisionManager() {
    return {
        divisions: [{ nama: '', deskripsi: '', kuota: 5 }],
        addDivision() {
            this.divisions.push({ nama: '', deskripsi: '', kuota: 5 });
        },
        removeDivision(index) {
            this.divisions.splice(index, 1);
        }
    };
}
</script>
@endsection