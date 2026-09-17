@extends('layouts.app')
@section('title', 'Edit Fakultas')
@section('page-title', 'Edit Fakultas')
@section('sidebar')
    @include('layouts.partials.sidebar-superadmin')
@endsection
@section('content')
<div class="mb-6">
    <a href="{{ route('superadmin.fakultas.index') }}" class="text-sm text-blue-600 hover:underline">&larr; Kembali ke Daftar Fakultas</a>
</div>

<div class="max-w-2xl bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
    <form action="{{ route('superadmin.fakultas.update', $fakulta) }}" method="POST" class="space-y-4">
        @csrf @method('PUT')
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Nama Fakultas</label>
            <input type="text" name="nama_fakultas" value="{{ old('nama_fakultas', $fakulta->nama_fakultas) }}" required class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
            @error('nama_fakultas')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
        </div>
        <div class="pt-2">
            <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white font-medium rounded-xl hover:bg-blue-700">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection
