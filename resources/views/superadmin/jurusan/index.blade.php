@extends('layouts.app')
@section('title', 'Kelola Jurusan')
@section('page-title', 'Kelola Jurusan')
@section('sidebar')
    @include('layouts.partials.sidebar-superadmin')
@endsection
@section('content')
<div x-data="{ showJurusanModal: {{ $errors->any() ? 'true' : 'false' }} }">
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
    <div>
        <h2 class="text-xl font-bold text-slate-800">Daftar Jurusan</h2>
        <p class="text-sm text-slate-500">Kelola master data jurusan per fakultas</p>
    </div>
    <button @click="showJurusanModal = true" class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Tambah Jurusan
    </button>
</div>

@if(session('success'))
    <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm">
        {{ session('error') }}
    </div>
@endif

<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-slate-50">
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Fakultas</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Nama Jurusan</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-slate-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($jurusans as $j)
                <tr class="hover:bg-slate-50">
                    <td class="px-6 py-4 font-medium text-slate-600">{{ $j->fakultas->nama_fakultas }}</td>
                    <td class="px-6 py-4 font-bold text-slate-800">{{ $j->nama_jurusan }}</td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('superadmin.jurusan.edit', $j) }}" class="p-2 text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg">Edit</a>
                            <form action="{{ route('superadmin.jurusan.destroy', $j) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?')">
                                @csrf @method('DELETE')
                                <button class="p-2 text-red-600 bg-red-50 hover:bg-red-100 rounded-lg">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="px-6 py-8 text-center text-slate-400">Belum ada data jurusan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($jurusans->hasPages())
        <div class="px-6 py-4 border-t border-slate-200">{{ $jurusans->links() }}</div>
    @endif
</div>

    <!-- Modal Tambah Jurusan -->
    <div x-show="showJurusanModal" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 backdrop-blur-sm p-4" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" style="display: none;">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden transform transition-all" @click.away="showJurusanModal = false" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                <h3 class="text-lg font-bold text-slate-800">Tambah Jurusan Baru</h3>
                <button @click="showJurusanModal = false" class="text-slate-400 hover:text-slate-600 p-1 hover:bg-slate-100 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            
            <form action="{{ route('superadmin.jurusan.store') }}" method="POST" class="p-6">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Fakultas Induk <span class="text-red-500">*</span></label>
                        <select name="fakultas_id" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-sm text-slate-700">
                            <option value="">-- Pilih Fakultas --</option>
                            @foreach($fakultas as $f)
                                <option value="{{ $f->id }}" {{ old('fakultas_id') == $f->id ? 'selected' : '' }}>{{ $f->nama_fakultas }}</option>
                            @endforeach
                        </select>
                        @error('fakultas_id')<p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Jurusan <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_jurusan" value="{{ old('nama_jurusan') }}" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-sm text-slate-700" placeholder="Contoh: Informatika">
                        @error('nama_jurusan')<p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" @click="showJurusanModal = false" class="px-5 py-2.5 text-sm font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">Batal</button>
                    <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-lg shadow-blue-600/20 transition-all">Simpan Jurusan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
