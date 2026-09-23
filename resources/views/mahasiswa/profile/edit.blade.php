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

<div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 sm:p-6" x-data="profileForm()">
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
    <input type="text" name="nim" value="{{ old('nim', $profile->nim) }}" required placeholder="Contoh: H1A020001" maxlength="9" pattern="[a-zA-Z0-9]{9}" oninput="this.value = this.value.toUpperCase()" class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
    <p class="mt-1 text-xs text-slate-400">Tepat 9 karakter huruf kapital & angka.</p>
    @error('nim')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">
            Fakultas
            <span class="ml-1 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-700">Wajib diisi</span>
        </label>
        <input type="hidden" name="fakultas_id" :value="selectedFakultas">
        <button type="button" @click="openModal('fakultas')"
                class="w-full flex items-center justify-between px-4 py-2.5 bg-white border border-slate-300 rounded-xl text-sm text-left transition-all duration-300 hover:border-blue-400 focus:ring-2 focus:ring-blue-500/20"
                :class="selectedFakultasName ? 'text-slate-900' : 'text-slate-500'">
            <span class="truncate" x-text="selectedFakultasName || 'Pilih Fakultas'"></span>
            <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
        </button>
        @error('fakultas_id')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">
            Jurusan
            <span class="ml-1 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-700">Wajib diisi</span>
        </label>
        <input type="hidden" name="jurusan_id" :value="selectedJurusan">
        <button type="button" @click="selectedFakultas ? openModal('jurusan') : null"
                class="w-full flex items-center justify-between px-4 py-2.5 bg-white border border-slate-300 rounded-xl text-sm text-left transition-all duration-300"
                :class="[
                    selectedJurusanName ? 'text-slate-900' : 'text-slate-500',
                    selectedFakultas ? 'hover:border-blue-400 focus:ring-2 focus:ring-blue-500/20 cursor-pointer' : 'bg-slate-50 opacity-75 cursor-not-allowed'
                ]">
            <span class="truncate" x-text="selectedJurusanName || (selectedFakultas ? 'Pilih Jurusan' : 'Pilih Fakultas dulu')"></span>
            <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
        </button>
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
        <input type="tel" name="no_hp" value="{{ old('no_hp', $profile->no_hp) }}" placeholder="Contoh: 08123456789" maxlength="13" pattern="[0-9]{10,13}" class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
        <p class="mt-1 text-xs text-slate-400">10–13 digit angka.</p>
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

{{-- ==================== MODAL PICKER ==================== --}}
<template x-teleport="body">
    <div x-show="modalOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[100] flex items-center justify-center p-4" style="display:none;">
        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" @click="closeModal()"></div>
        <div x-show="modalOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95 translate-y-4"
             class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm max-h-[70vh] flex flex-col overflow-hidden">
            
            <div class="p-4 border-b border-slate-100 flex items-center justify-between shrink-0">
                <h3 class="font-bold text-slate-800 text-base" x-text="modalType === 'fakultas' ? 'Pilih Fakultas' : 'Pilih Jurusan'"></h3>
                <button type="button" @click="closeModal()" class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 flex items-center justify-center transition-colors">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            
            <div class="p-3 border-b border-slate-100 shrink-0">
                <div class="relative">
                    <input type="text" x-model="modalSearch" x-ref="modalSearchInput" placeholder="Cari..."
                           class="w-full pl-9 pr-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition-all">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                </div>
            </div>
            
            <div class="overflow-y-auto flex-1 p-2">
                <template x-for="item in filteredModalItems" :key="item.id">
                    <button type="button" @click="selectItem(item)"
                            class="w-full text-left px-4 py-3 rounded-xl text-sm font-medium transition-all duration-150 flex items-center justify-between group"
                            :class="isSelected(item) ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'text-slate-700 hover:bg-slate-50 border border-transparent'">
                        <span x-text="modalType === 'fakultas' ? item.nama_fakultas : item.nama_jurusan"></span>
                        <svg x-show="isSelected(item)" class="w-5 h-5 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    </button>
                </template>
                <div x-show="filteredModalItems.length === 0" class="text-center py-8 text-slate-400 text-sm">
                    Tidak ditemukan
                </div>
            </div>
        </div>
    </div>
</template>

</form></div></div>

@push('scripts')
<script>
    function profileForm() {
        const fakultasData = @json($fakultasList);
        const oldFakultas = '{{ old("fakultas_id", $profile->fakultas_id ?? "") }}';
        const oldJurusan = '{{ old("jurusan_id", $profile->jurusan_id ?? "") }}';

        return {
            selectedFakultas: oldFakultas,
            selectedFakultasName: '',
            selectedJurusan: oldJurusan,
            selectedJurusanName: '',
            fakultasData: fakultasData,

            modalOpen: false,
            modalType: '', 
            modalSearch: '',

            init() {
                if (this.selectedFakultas) {
                    const fak = this.fakultasData.find(f => f.id == this.selectedFakultas);
                    if (fak) this.selectedFakultasName = fak.nama_fakultas;
                }
                if (this.selectedJurusan && this.selectedFakultas) {
                    const fak = this.fakultasData.find(f => f.id == this.selectedFakultas);
                    if (fak) {
                        const jur = fak.jurusans.find(j => j.id == this.selectedJurusan);
                        if (jur) this.selectedJurusanName = jur.nama_jurusan;
                    }
                }
            },

            openModal(type) {
                this.modalType = type;
                this.modalSearch = '';
                this.modalOpen = true;
                this.$nextTick(() => {
                    if (this.$refs.modalSearchInput) this.$refs.modalSearchInput.focus();
                });
            },

            closeModal() {
                this.modalOpen = false;
            },

            get filteredModalItems() {
                let items = [];
                if (this.modalType === 'fakultas') {
                    items = this.fakultasData;
                    if (this.modalSearch) {
                        const q = this.modalSearch.toLowerCase();
                        items = items.filter(f => f.nama_fakultas.toLowerCase().includes(q));
                    }
                } else {
                    const fak = this.fakultasData.find(f => f.id == this.selectedFakultas);
                    items = fak ? fak.jurusans : [];
                    if (this.modalSearch) {
                        const q = this.modalSearch.toLowerCase();
                        items = items.filter(j => j.nama_jurusan.toLowerCase().includes(q));
                    }
                }
                return items;
            },

            isSelected(item) {
                if (this.modalType === 'fakultas') return item.id == this.selectedFakultas;
                return item.id == this.selectedJurusan;
            },

            selectItem(item) {
                if (this.modalType === 'fakultas') {
                    this.selectedFakultas = item.id;
                    this.selectedFakultasName = item.nama_fakultas;
                    this.selectedJurusan = '';
                    this.selectedJurusanName = '';
                } else {
                    this.selectedJurusan = item.id;
                    this.selectedJurusanName = item.nama_jurusan;
                }
                this.closeModal();
            }
        };
    }
</script>
@endpush
@endsection