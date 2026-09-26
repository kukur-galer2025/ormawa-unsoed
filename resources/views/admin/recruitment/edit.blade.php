@extends("layouts.app")
@section("title","Edit Rekrutmen")@section("page-title","Edit Rekrutmen")
@section("sidebar")@include("layouts.partials.sidebar-admin")@endsection
@section("content")
<div class="w-full max-w-3xl" x-data="recruitmentForm()">
    <form method="POST" action="{{ route('admin.recruitment.update', $recruitment) }}">
        @csrf @method('PUT')

        {{-- Tab Navigation --}}
        <div class="flex gap-1 bg-slate-100 p-1 rounded-2xl mb-6 w-fit">
            <button type="button" @click="activeTab = 'info'"
                :class="activeTab === 'info' ? 'bg-white text-slate-800 shadow-sm' : 'text-slate-500 hover:text-slate-700'"
                class="px-5 py-2 rounded-xl text-sm font-semibold transition-all duration-200 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Informasi Umum
            </button>
            <button type="button" @click="activeTab = 'divisi'"
                :class="activeTab === 'divisi' ? 'bg-white text-slate-800 shadow-sm' : 'text-slate-500 hover:text-slate-700'"
                class="px-5 py-2 rounded-xl text-sm font-semibold transition-all duration-200 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                Daftar Divisi
                <span x-text="divisions.length" class="bg-indigo-100 text-indigo-700 text-xs font-bold px-2 py-0.5 rounded-full min-w-[20px] text-center"></span>
            </button>
        </div>

        {{-- Tab: Info Umum --}}
        <div x-show="activeTab === 'info'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-5">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Judul Rekrutmen <span class="text-red-500">*</span></label>
                    <input type="text" name="judul" value="{{ old('judul', $recruitment->judul) }}" required
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                    @error('judul')<p class="mt-1.5 text-xs text-red-500 flex items-center gap-1"><svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tanggal Buka <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_buka" value="{{ old('tanggal_buka', $recruitment->tanggal_buka->format('Y-m-d')) }}" required
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tanggal Tutup <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_tutup" value="{{ old('tanggal_tutup', $recruitment->tanggal_tutup->format('Y-m-d')) }}" required
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Status</label>
                    <select name="status" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                        @foreach(['draft','dibuka','ditutup','selesai'] as $s)
                            <option value="{{ $s }}" {{ old('status', $recruitment->status) == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Deskripsi</label>
                    <textarea name="deskripsi" rows="3" placeholder="Tuliskan deskripsi singkat rekrutmen ini..."
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all resize-none">{{ old('deskripsi', $recruitment->deskripsi) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Persyaratan Pendaftaran</label>
                    <textarea name="persyaratan" rows="4" placeholder="1. Mahasiswa aktif UNSOED&#10;2. ..."
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all resize-none">{{ old('persyaratan', $recruitment->persyaratan) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Pesan / Instruksi Lanjutan (Opsional)</label>
                    <p class="text-xs text-slate-500 mb-2">Pesan ini akan ditampilkan kepada mahasiswa setelah mereka berhasil mengirim pendaftaran. Cocok untuk memberikan link Grup WhatsApp seleksi atau instruksi lainnya.</p>
                    <textarea name="pesan_setelah_mendaftar" rows="3" placeholder="Terima kasih telah mendaftar! Silakan bergabung ke Grup WA seleksi berikut: https://chat.whatsapp.com/..."
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all resize-none">{{ old('pesan_setelah_mendaftar', $recruitment->pesan_setelah_mendaftar) }}</textarea>
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
                                        <input type="text" :name="'divisions['+index+'][nama]'" x-model="div.nama" required
                                            placeholder="Contoh: Humas" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] uppercase tracking-widest font-bold text-slate-400 mb-1">Kuota *</label>
                                        <input type="number" :name="'divisions['+index+'][kuota]'" x-model="div.kuota" required min="1"
                                            placeholder="5" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                                    </div>
                                    <div class="sm:col-span-3">
                                        <label class="block text-[10px] uppercase tracking-widest font-bold text-slate-400 mb-1">Deskripsi <span class="normal-case font-normal text-slate-400">(opsional)</span></label>
                                        <input type="text" :name="'divisions['+index+'][deskripsi]'" x-model="div.deskripsi"
                                            placeholder="Tugas dan tanggung jawab singkat..." class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                                    </div>
                                    <input type="hidden" :name="'divisions['+index+'][id]'" :value="div.id || ''">
                                </div>

                                {{-- Delete --}}
                                <button type="button" @click="removeDivision(index)" x-show="divisions.length > 1"
                                    class="shrink-0 w-8 h-8 rounded-lg text-slate-300 hover:bg-red-50 hover:text-red-500 flex items-center justify-center transition-colors mt-0.5 opacity-0 group-hover:opacity-100">
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
                        Update Rekrutmen
                    </button>
                </div>
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
function recruitmentForm() {
    return {
        activeTab: 'info',
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