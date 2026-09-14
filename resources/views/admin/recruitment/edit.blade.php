@extends("layouts.app")
@section("title","Edit Rekrutmen")@section("page-title","Edit Rekrutmen")
@section("sidebar")@include("layouts.partials.sidebar-admin")@endsection
@section("content")
<div class="w-full max-w-4xl" x-data="divisionManager()">
    <form method="POST" action="{{ route('admin.recruitment.update', $recruitment) }}">
        @csrf @method('PUT')
        
        <!-- Header -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-slate-800">Edit Rekrutmen</h2>
                <p class="text-sm text-slate-500 mt-1">Perbarui informasi dan divisi rekrutmen di bawah ini.</p>
            </div>
        </div>

        <div class="space-y-6">
            <!-- Info Umum Card -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                <div class="flex items-center gap-2 mb-5 pb-4 border-b border-slate-100">
                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-800">Informasi Umum</h3>
                </div>

                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Judul Rekrutmen <span class="text-red-500">*</span></label>
                        <input type="text" name="judul" value="{{ old('judul', $recruitment->judul) }}" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors">
                        @error('judul')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Deskripsi</label>
                        <textarea name="deskripsi" rows="3" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors">{{ old('deskripsi', $recruitment->deskripsi) }}</textarea>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Persyaratan Pendaftaran</label>
                        <textarea name="persyaratan" rows="4" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors">{{ old('persyaratan', $recruitment->persyaratan) }}</textarea>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tanggal Buka <span class="text-red-500">*</span></label>
                            <input type="date" name="tanggal_buka" value="{{ old('tanggal_buka', $recruitment->tanggal_buka->format('Y-m-d')) }}" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tanggal Tutup <span class="text-red-500">*</span></label>
                            <input type="date" name="tanggal_tutup" value="{{ old('tanggal_tutup', $recruitment->tanggal_tutup->format('Y-m-d')) }}" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Status</label>
                        <select name="status" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors">
                            @foreach(['draft','dibuka','ditutup','selesai'] as $s)
                                <option value="{{ $s }}" {{ old('status', $recruitment->status) == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Divisi Card -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                <div class="flex items-center justify-between mb-5 pb-4 border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        </div>
                        <h3 class="text-base font-bold text-slate-800">Daftar Divisi <span class="text-red-500">*</span></h3>
                    </div>
                    <button type="button" @click="addDivision()" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold bg-indigo-50 text-indigo-700 rounded-lg hover:bg-indigo-100 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Tambah Divisi
                    </button>
                </div>
                
                <div class="space-y-4">
                    <template x-for="(div, index) in divisions" :key="index">
                        <div class="bg-slate-50/50 rounded-xl p-5 border border-slate-200 relative group transition-all hover:border-indigo-300">
                            <button type="button" @click="removeDivision(index)" x-show="divisions.length > 1" class="absolute -top-3 -right-3 w-7 h-7 rounded-full bg-red-100 text-red-600 hover:bg-red-500 hover:text-white flex items-center justify-center shadow-sm transition-all text-sm" title="Hapus divisi">
                                &times;
                            </button>
                            <input type="hidden" :name="'divisions['+index+'][id]'" :value="div.id || ''">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="col-span-2">
                                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nama Divisi</label>
                                    <input type="text" :name="'divisions['+index+'][nama]'" x-model="div.nama" required placeholder="Contoh: Kementerian Dalam Negeri" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-colors">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Kuota</label>
                                    <input type="number" :name="'divisions['+index+'][kuota]'" x-model="div.kuota" required min="1" placeholder="Misal: 5" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-colors">
                                </div>
                            </div>
                            <div class="mt-4">
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Deskripsi Divisi</label>
                                <input type="text" :name="'divisions['+index+'][deskripsi]'" x-model="div.deskripsi" placeholder="Opsional: Deskripsi tugas/tanggung jawab singkat" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-colors">
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('admin.recruitment.index') }}" class="px-6 py-2.5 bg-white border border-slate-300 text-slate-700 font-semibold rounded-xl hover:bg-slate-50 transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-xl shadow-lg shadow-blue-500/30 hover:shadow-blue-500/40 hover:from-blue-700 hover:to-indigo-700 transition-all">
                    Update Rekrutmen
                </button>
            </div>
        </div>
    </form>
</div>

@php
    $divisionsData = $recruitment->divisions->map(function($d) {
        return [
            'id' => $d->id,
            'nama' => $d->nama,
            'deskripsi' => $d->deskripsi ?? '',
            'kuota' => $d->kuota
        ];
    })->values();
@endphp

<script>
function divisionManager() {
    return {
        divisions: @json($divisionsData),
        addDivision() {
            this.divisions.push({ id: null, nama: '', deskripsi: '', kuota: 5 });
        },
        removeDivision(index) {
            this.divisions.splice(index, 1);
        }
    };
}
</script>
@endsection