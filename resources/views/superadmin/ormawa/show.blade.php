@extends("layouts.app")
@section("title", $ormawa->nama)
@section("page-title", $ormawa->nama)
@section("sidebar")
    @include("layouts.partials.sidebar-superadmin")
@endsection

@section("content")
<div class="mb-6">
    <a href="{{ route('superadmin.ormawa.index') }}" class="text-sm text-blue-600 hover:underline">&larr; Kembali ke Daftar Ormawa</a>
</div>

<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-6">
    <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-2xl bg-white border border-slate-200 shadow-sm p-2 flex items-center justify-center">
                @if($ormawa->logo)
                    <img src="{{ asset('storage/' . $ormawa->logo) }}" alt="Logo" class="w-full h-full object-contain">
                @else
                    <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                @endif
            </div>
            <div>
                <h2 class="text-xl font-bold text-slate-800">{{ $ormawa->nama }}</h2>
                <div class="flex items-center gap-2 mt-1">
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700">{{ $ormawa->tingkat }}</span>
                    @if($ormawa->fakultas_id || $ormawa->jurusan_id)
                        <span class="text-sm text-slate-500">
                            &bull; {{ $ormawa->fakultasRel->nama_fakultas ?? '' }}{{ $ormawa->jurusan_id ? ' - ' . $ormawa->jurusanRel->nama_jurusan : '' }}
                        </span>
                    @endif
                </div>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <span class="px-3 py-1 rounded-full text-sm font-medium {{ $ormawa->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                {{ $ormawa->is_active ? 'Aktif' : 'Nonaktif' }}
            </span>
            <a href="{{ route('superadmin.ormawa.edit', $ormawa) }}" class="px-4 py-2 bg-blue-50 text-blue-600 hover:bg-blue-100 text-sm font-semibold rounded-xl transition-colors">Edit Data</a>
        </div>
    </div>
    
    <div class="p-6 grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-6">
            <div>
                <h3 class="text-base font-semibold text-slate-800 mb-2">Deskripsi</h3>
                <div class="text-sm text-slate-600 leading-relaxed text-justify">
                    {{ $ormawa->deskripsi ?: 'Belum ada deskripsi yang ditambahkan.' }}
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-base font-semibold text-slate-800 mb-2">Visi</h3>
                    <div class="text-sm text-slate-600 leading-relaxed text-justify">
                        {{ $ormawa->visi ?: 'Belum ada visi.' }}
                    </div>
                </div>
                <div>
                    <h3 class="text-base font-semibold text-slate-800 mb-2">Misi</h3>
                    <div class="text-sm text-slate-600 leading-relaxed text-justify">
                        {{ $ormawa->misi ?: 'Belum ada misi.' }}
                    </div>
                </div>
            </div>
        </div>
        
        <div class="space-y-6">
            <div class="bg-slate-50 rounded-2xl p-5 border border-slate-100">
                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-4">Admin Pengelola</h3>
                @if($ormawa->admin)
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-bold shadow-md">
                            {{ strtoupper(substr($ormawa->admin->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-800">{{ $ormawa->admin->name }}</p>
                            <p class="text-xs text-slate-500">{{ $ormawa->admin->email }}</p>
                        </div>
                    </div>
                @else
                    <div class="flex items-center gap-3 text-slate-400">
                        <div class="w-10 h-10 bg-slate-200 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <span class="text-sm">Belum ada admin ditugaskan.</span>
                    </div>
                @endif
            </div>

            <div class="bg-slate-50 rounded-2xl p-5 border border-slate-100">
                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-4">Kontak Organisasi</h3>
                <ul class="space-y-3">
                    <li class="flex items-center gap-3 text-sm">
                        <div class="w-8 h-8 rounded-lg bg-white shadow-sm flex items-center justify-center text-slate-400 shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <span class="text-slate-600 truncate">{{ $ormawa->kontak_email ?: '-' }}</span>
                    </li>
                    <li class="flex items-center gap-3 text-sm">
                        <div class="w-8 h-8 rounded-lg bg-white shadow-sm flex items-center justify-center text-pink-500 shrink-0">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.88z"/></svg>
                        </div>
                        <span class="text-slate-600 truncate">{{ $ormawa->kontak_instagram ?: '-' }}</span>
                    </li>
                </ul>
            </div>
            
            <div class="bg-slate-50 rounded-2xl p-5 border border-slate-100">
                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-4">Statistik</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-white p-4 rounded-xl border border-slate-100 shadow-sm text-center">
                        <span class="block text-2xl font-bold text-blue-600">{{ $ormawa->recruitments->count() }}</span>
                        <span class="text-xs font-medium text-slate-500 uppercase mt-1 block">Rekrutmen</span>
                    </div>
                    <div class="bg-white p-4 rounded-xl border border-slate-100 shadow-sm text-center">
                        <span class="block text-2xl font-bold text-blue-600">{{ $ormawa->programKerjas->count() ?? 0 }}</span>
                        <span class="text-xs font-medium text-slate-500 uppercase mt-1 block">Proker</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection