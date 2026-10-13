@extends("layouts.app")
@section("title","Buat Rekrutmen")@section("page-title","Buat Rekrutmen Baru")
@section("sidebar")@include("layouts.partials.sidebar-admin")@endsection
@section("content")
<div class="w-full max-w-3xl" x-data="recruitmentForm()">
    <form method="POST" action="{{ route('admin.recruitment.store') }}" novalidate>
        @csrf

        {{-- Error Banner --}}
        @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-2xl flex items-start gap-3 shadow-sm">
                <div class="w-8 h-8 rounded-xl bg-red-100 text-red-600 flex items-center justify-center shrink-0 mt-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <div class="flex-1">
                    <h4 class="text-sm font-bold text-red-800">Validasi Data Rekrutmen Gagal</h4>
                    <p class="text-xs text-red-600 mt-0.5">Silakan periksa kembali bagian yang belum sesuai:</p>
                    <ul class="mt-2 text-xs text-red-700 list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        {{-- Tab Navigation --}}
        <div class="flex gap-1 bg-slate-100 p-1 rounded-2xl mb-6 w-fit">
            <button type="button" @click="activeTab = 'info'"
                :class="activeTab === 'info' ? 'bg-white text-slate-800 shadow-sm' : 'text-slate-500 hover:text-slate-700'"
                class="px-5 py-2 rounded-xl text-sm font-semibold transition-all duration-200 flex items-center gap-2 relative">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Informasi Umum
                @if($errors->has('judul') || $errors->has('tanggal_buka') || $errors->has('tanggal_tutup'))
                    <span class="w-2.5 h-2.5 rounded-full bg-red-500 ring-2 ring-white animate-pulse" title="Terdapat error pada bagian ini"></span>
                @endif
            </button>
            <button type="button" @click="activeTab = 'divisi'"
                :class="activeTab === 'divisi' ? 'bg-white text-slate-800 shadow-sm' : 'text-slate-500 hover:text-slate-700'"
                class="px-5 py-2 rounded-xl text-sm font-semibold transition-all duration-200 flex items-center gap-2 relative">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                Daftar Divisi
                <span x-text="divisions.length" class="bg-indigo-100 text-indigo-700 text-xs font-bold px-2 py-0.5 rounded-full min-w-[20px] text-center"></span>
                @if($errors->has('divisions') || $errors->has('divisions.*'))
                    <span class="w-2.5 h-2.5 rounded-full bg-red-500 ring-2 ring-white animate-pulse" title="Terdapat error pada bagian ini"></span>
                @endif
            </button>
        </div>

        {{-- Tab: Info Umum --}}
        <div x-show="activeTab === 'info'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-5">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Judul Rekrutmen <span class="text-red-500">*</span></label>
                    <input type="text" name="judul" value="{{ old('judul') }}" placeholder="Contoh: Rekrutmen Pengurus BEM 2024/2025"
                        class="w-full px-4 py-2.5 bg-slate-50 border @error('judul') border-red-500 ring-2 ring-red-500/20 @else border-slate-200 @enderror rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                    @error('judul')<p class="mt-1.5 text-xs text-red-500 flex items-center gap-1"><svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tanggal Buka <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_buka" value="{{ old('tanggal_buka') }}"
                            class="w-full px-4 py-2.5 bg-slate-50 border @error('tanggal_buka') border-red-500 ring-2 ring-red-500/20 @else border-slate-200 @enderror rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                        @error('tanggal_buka')<p class="mt-1.5 text-xs text-red-500 flex items-center gap-1"><svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tanggal Tutup <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_tutup" value="{{ old('tanggal_tutup') }}"
                            class="w-full px-4 py-2.5 bg-slate-50 border @error('tanggal_tutup') border-red-500 ring-2 ring-red-500/20 @else border-slate-200 @enderror rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                        @error('tanggal_tutup')<p class="mt-1.5 text-xs text-red-500 flex items-center gap-1"><svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>{{ $message }}</p>@enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Deskripsi</label>
                    <textarea name="deskripsi" rows="3" placeholder="Tuliskan deskripsi singkat rekrutmen ini..."
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all resize-none">{{ old('deskripsi') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Persyaratan Pendaftaran</label>
                    <textarea name="persyaratan" rows="4" placeholder="1. Mahasiswa aktif UNSOED&#10;2. Minimal semester 3&#10;3. ..."
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all resize-none">{{ old('persyaratan') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Pesan / Instruksi Lanjutan (Opsional)</label>
                    <p class="text-xs text-slate-500 mb-2">Pesan ini akan ditampilkan kepada mahasiswa setelah mereka berhasil mengirim pendaftaran. Cocok untuk memberikan link Grup WhatsApp seleksi atau instruksi lainnya.</p>
                    <textarea name="pesan_setelah_mendaftar" rows="3" placeholder="Terima kasih telah mendaftar! Silakan bergabung ke Grup WA seleksi berikut: https://chat.whatsapp.com/..."
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all resize-none">{{ old('pesan_setelah_mendaftar') }}</textarea>
                </div>
            </div>

            <div class="flex justify-end mt-4">
                <button type="button" @click="activeTab = 'divisi'"
                    class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-xl shadow-lg shadow-blue-500/25 hover:shadow-blue-500/40 hover:from-blue-700 hover:to-indigo-700 transition-all">
                    Lanjut ke Divisi
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>
        </div>

        {{-- Tab: Divisi --}}
        <div x-show="activeTab === 'divisi'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm">
                {{-- Header --}}
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
                    <p class="text-sm text-slate-500">Tambahkan divisi atau bidang yang membuka lowongan rekrutmen.</p>
                    <button type="button" @click="addDivision()"
                        class="inline-flex items-center gap-1.5 px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition-colors shadow-md shadow-indigo-500/25">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Tambah Divisi
                    </button>
                </div>

                {{-- General division validation error --}}
                @error('divisions')
                    <div class="px-6 py-3 bg-red-50 border-b border-red-100 text-xs font-semibold text-red-600 flex items-center gap-2">
                        <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        {{ $message }}
                    </div>
                @enderror

                {{-- Empty state when 0 divisions --}}
                <template x-if="divisions.length === 0">
                    <div class="px-6 py-10 text-center text-slate-400">
                        <svg class="w-10 h-10 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        <p class="text-sm font-medium text-slate-600">Belum ada divisi yang ditambahkan.</p>
                        <p class="text-xs text-slate-400 mt-0.5">Rekrutmen membutuhkan minimal 1 divisi.</p>
                        <button type="button" @click="addDivision()" class="mt-3 px-4 py-1.5 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 rounded-lg text-xs font-bold transition-colors">
                            + Tambah Divisi Pertama
                        </button>
                    </div>
                </template>

                {{-- Division List --}}
                <div class="divide-y divide-slate-100">
                    <template x-for="(div, index) in divisions" :key="index">
                        <div class="px-6 py-4 hover:bg-slate-50/50 transition-colors group">
                            <div class="flex items-start gap-4">
                                {{-- Number Badge --}}
                                <div class="shrink-0 w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm font-black mt-0.5" x-text="index + 1"></div>

                                {{-- Fields --}}
                                <div class="flex-1 grid grid-cols-1 sm:grid-cols-3 gap-3">
                                    <div class="sm:col-span-2">
                                        <label class="block text-[10px] uppercase tracking-widest font-bold text-slate-400 mb-1">Nama Divisi *</label>
                                        <input type="text" :name="'divisions['+index+'][nama]'" x-model="div.nama"
                                            placeholder="Contoh: Humas" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] uppercase tracking-widest font-bold text-slate-400 mb-1">Kuota *</label>
                                        <input type="number" :name="'divisions['+index+'][kuota]'" x-model="div.kuota"
                                            placeholder="5" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                                    </div>
                                    <div class="sm:col-span-3">
                                        <label class="block text-[10px] uppercase tracking-widest font-bold text-slate-400 mb-1">Deskripsi <span class="normal-case font-normal text-slate-400">(opsional)</span></label>
                                        <input type="text" :name="'divisions['+index+'][deskripsi]'" x-model="div.deskripsi"
                                            placeholder="Tugas dan tanggung jawab singkat..." class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                                    </div>
                                </div>

                                {{-- Delete --}}
                                <button type="button" @click="removeDivision(index)"
                                    class="shrink-0 w-8 h-8 rounded-lg text-slate-300 hover:bg-red-50 hover:text-red-500 flex items-center justify-center transition-colors mt-0.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-between mt-4">
                <button type="button" @click="activeTab = 'info'"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-slate-200 text-slate-600 font-semibold rounded-xl hover:bg-slate-50 transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Kembali
                </button>
                <div class="flex gap-3">
                    <a href="{{ route('admin.recruitment.index') }}"
                        class="px-5 py-2.5 bg-white border border-slate-200 text-slate-600 font-semibold rounded-xl hover:bg-slate-50 transition-colors shadow-sm">
                        Batal
                    </a>
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-xl shadow-lg shadow-blue-500/25 hover:shadow-blue-500/40 hover:from-blue-700 hover:to-indigo-700 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Simpan Rekrutmen
                    </button>
                </div>
            </div>
        </div>

    </form>
</div>

@php
    $defaultDivisions = old('divisions', [
        ['nama' => '', 'deskripsi' => '', 'kuota' => 5]
    ]);
@endphp

<script>
function recruitmentForm() {
    return {
        activeTab: '{{ ($errors->has('divisions') || $errors->has('divisions.*')) && !$errors->has('judul') && !$errors->has('tanggal_buka') && !$errors->has('tanggal_tutup') ? 'divisi' : 'info' }}',
        divisions: @json($defaultDivisions),
        addDivision() {
            this.divisions.push({ nama: '', deskripsi: '', kuota: 5 });
        },
        removeDivision(index) {
            this.divisions.splice(index, 1);
        }
    };
}
</script>

@if($errors->any())
<script>
document.addEventListener('DOMContentLoaded', () => {
    Swal.fire({
        title: 'Validasi Gagal!',
        html: `<div class="text-left text-sm space-y-1.5 mt-2">
            @foreach($errors->all() as $error)
                <div class="flex items-start gap-2 text-red-600">
                    <span class="text-red-500 font-bold">•</span>
                    <span>{{ addslashes($error) }}</span>
                </div>
            @endforeach
        </div>`,
        icon: 'error',
        confirmButtonColor: '#2563eb',
        confirmButtonText: 'Periksa Data',
        customClass: { popup: 'rounded-2xl', confirmButton: 'rounded-lg' }
    });
});
</script>
@endif
@endsection