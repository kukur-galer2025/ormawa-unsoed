@extends("layouts.app")
@section("title","Kelola Ormawa")
@section("page-title","Kelola Ormawa")
@section("sidebar")
    @include("layouts.partials.sidebar-superadmin")
@endsection

@section("content")
<div x-data="ormawaManager()">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <p class="text-slate-600">Daftar organisasi mahasiswa.</p>
        <button @click="openModal()" type="button" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-sm font-semibold rounded-xl shadow-lg shadow-blue-500/25 hover:from-blue-700 hover:to-indigo-700 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Ormawa
        </button>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-50">
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Tingkat</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-slate-500 uppercase">Admin</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-slate-500 uppercase">Rekrutmen</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-slate-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-slate-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($ormawas as $o)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4 font-medium text-slate-800">{{ $o->nama }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700">{{ $o->tingkat }}</span>
                            @if($o->fakultas_id || $o->jurusan_id)
                                <div class="text-xs text-slate-500 mt-1">{{ $o->fakultasRel->nama_fakultas ?? '-' }}{{ $o->jurusan_id ? ' - ' . $o->jurusanRel->nama_jurusan : '' }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center text-sm">{{ $o->admins_count }}</td>
                        <td class="px-6 py-4 text-center text-sm">{{ $o->recruitments_count }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $o->is_active ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700' }}">{{ $o->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('superadmin.ormawa.edit', $o) }}" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form method="POST" action="{{ route('superadmin.ormawa.destroy', $o) }}" onsubmit="confirmForm(event, 'Yakin hapus?')">
                                    @csrf @method('DELETE')
                                    <button class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-slate-400">Belum ada ormawa.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($ormawas->hasPages())
            <div class="px-6 py-4 border-t border-slate-200">{{ $ormawas->links() }}</div>
        @endif
    </div>

    {{-- ===== MODAL TAMBAH ORMAWA ===== --}}
    <div x-show="showModal" x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0">

        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" @click="closeModal()"></div>

        {{-- Modal Content --}}
        <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden"
            x-show="showModal"
            x-transition:enter="transition ease-out duration-200 delay-50"
            x-transition:enter-start="opacity-0 scale-95 translate-y-4"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            @click.away="closeModal()">

            {{-- Header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-md shadow-blue-500/25">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">Tambah Ormawa</h3>
                        <p class="text-xs text-slate-500">Detail lainnya bisa diisi oleh Admin Ormawa.</p>
                    </div>
                </div>
                <button @click="closeModal()" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-xl transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- Body --}}
            <form method="POST" action="{{ route('superadmin.ormawa.store') }}" class="px-6 py-5 space-y-4">
                @csrf

                {{-- Nama Ormawa --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Ormawa <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" required placeholder="Contoh: BEM UNSOED"
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                    @error('nama')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                {{-- Tingkat --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tingkat <span class="text-red-500">*</span></label>
                    <select name="tingkat" x-model="tingkat"
                        @change="if(tingkat === 'Universitas') { fakultas_id = ''; jurusan_id = ''; } else if(tingkat === 'Fakultas') { jurusan_id = ''; }"
                        required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                        <option value="Universitas">Universitas</option>
                        <option value="Fakultas">Fakultas</option>
                        <option value="Jurusan">Jurusan</option>
                    </select>
                    @error('tingkat')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                {{-- Fakultas (conditional) --}}
                <div x-show="tingkat === 'Fakultas' || tingkat === 'Jurusan'" x-cloak
                    x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Fakultas <span class="text-red-500">*</span></label>
                    <select name="fakultas_id" x-model="fakultas_id" @change="jurusan_id = ''"
                        :required="tingkat === 'Fakultas' || tingkat === 'Jurusan'"
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                        <option value="">-- Pilih Fakultas --</option>
                        <template x-for="f in fakultasList" :key="f.id">
                            <option :value="f.id" x-text="f.nama_fakultas"></option>
                        </template>
                    </select>
                    @error('fakultas_id')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                {{-- Jurusan (conditional) --}}
                <div x-show="tingkat === 'Jurusan'" x-cloak
                    x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Jurusan <span class="text-red-500">*</span></label>
                    <select name="jurusan_id" x-model="jurusan_id"
                        :required="tingkat === 'Jurusan'"
                        :disabled="!fakultas_id || filteredJurusan.length === 0"
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                        <option value="">-- Pilih Jurusan --</option>
                        <template x-for="j in filteredJurusan" :key="j.id">
                            <option :value="j.id" x-text="j.nama_jurusan"></option>
                        </template>
                    </select>
                    @error('jurusan_id')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                {{-- Actions --}}
                <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                    <button type="button" @click="closeModal()"
                        class="px-5 py-2.5 bg-white border border-slate-200 text-slate-600 font-semibold text-sm rounded-xl hover:bg-slate-50 transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold text-sm rounded-xl shadow-lg shadow-blue-500/25 hover:from-blue-700 hover:to-indigo-700 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function ormawaManager() {
    return {
        showModal: false,
        tingkat: 'Universitas',
        fakultas_id: '',
        jurusan_id: '',
        fakultasList: @json($fakultas),
        get filteredJurusan() {
            const f = this.fakultasList.find(f => f.id == this.fakultas_id);
            return f ? f.jurusans : [];
        },
        openModal() {
            this.tingkat = 'Universitas';
            this.fakultas_id = '';
            this.jurusan_id = '';
            this.showModal = true;
        },
        closeModal() {
            this.showModal = false;
        }
    };
}
</script>
@endsection