@extends("layouts.app")
@section("title","Kelola Ormawa")
@section("page-title","Kelola Ormawa")
@section("sidebar")
    @include("layouts.partials.sidebar-superadmin")
@endsection

@section("content")
<div class="flex justify-between items-center mb-6">
    <p class="text-slate-600">Daftar organisasi mahasiswa.</p>
    <a href="{{ route('superadmin.ormawa.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-sm font-semibold rounded-xl shadow-lg shadow-blue-500/25">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Tambah Ormawa
    </a>
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
@endsection