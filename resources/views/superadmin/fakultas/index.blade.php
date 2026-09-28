@extends('layouts.app')
@section('title', 'Kelola Fakultas')
@section('page-title', 'Kelola Fakultas')
@section('sidebar')
    @include('layouts.partials.sidebar-superadmin')
@endsection
@section('content')
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
    <div>
        <h2 class="text-xl font-bold text-slate-800">Daftar Fakultas</h2>
        <p class="text-sm text-slate-500">Kelola master data fakultas</p>
    </div>
    <a href="{{ route('superadmin.fakultas.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Tambah Fakultas
    </a>
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
                    <th class="px-4 py-3 sm:px-6 sm:py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Nama Fakultas</th>
                    <th class="px-4 py-3 sm:px-6 sm:py-4 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Jumlah Jurusan</th>
                    <th class="px-4 py-3 sm:px-6 sm:py-4 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($fakultas as $f)
                <tr class="hover:bg-slate-50">
                    <td class="px-4 py-3 sm:px-6 sm:py-4 text-sm sm:text-base font-bold text-slate-800">{{ $f->nama_fakultas }}</td>
                    <td class="px-4 py-3 sm:px-6 sm:py-4 text-center text-sm sm:text-base text-slate-600">{{ $f->jurusans_count }}</td>
                    <td class="px-4 py-3 sm:px-6 sm:py-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('superadmin.fakultas.edit', $f) }}" class="px-2.5 py-1.5 sm:px-3 sm:py-2 text-xs sm:text-sm font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">Edit</a>
                            <form action="{{ route('superadmin.fakultas.destroy', $f) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?')">
                                @csrf @method('DELETE')
                                <button class="px-2.5 py-1.5 sm:px-3 sm:py-2 text-xs sm:text-sm font-medium text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition-colors">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="px-6 py-8 text-center text-slate-400">Belum ada data fakultas.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($fakultas->hasPages())
        <div class="px-6 py-4 border-t border-slate-200">{{ $fakultas->links() }}</div>
    @endif
</div>
@endsection
