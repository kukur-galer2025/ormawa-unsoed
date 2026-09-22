@extends("layouts.app")
@section("title","Kelola Admin")@section("page-title","Kelola Admin")
@section("sidebar")@include("layouts.partials.sidebar-superadmin")@endsection
@section("content")
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
    <p class="text-slate-600 text-sm">Daftar akun administrator.</p>
    <a href="{{ route('superadmin.admin.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-sm font-semibold rounded-xl shadow-lg shadow-blue-500/25">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>Tambah Admin
    </a>
</div>

{{-- Desktop Table --}}
<div class="hidden md:block bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-slate-50">
                    <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Nama</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Email</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Ormawa</th>
                    <th class="px-5 py-3 text-center text-xs font-semibold text-slate-500 uppercase">Status</th>
                    <th class="px-5 py-3 text-center text-xs font-semibold text-slate-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($admins as $admin)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-5 py-4 font-medium text-sm text-slate-800">{{ $admin->name }}</td>
                    <td class="px-5 py-4 text-sm text-slate-600">{{ $admin->email }}</td>
                    <td class="px-5 py-4">
                        <div class="flex flex-wrap gap-1">
                            @foreach($admin->ormawas as $o)
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700">{{ $o->nama }}</span>
                            @endforeach
                        </div>
                    </td>
                    <td class="px-5 py-4 text-center">
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $admin->is_active ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700' }}">{{ $admin->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                    </td>
                    <td class="px-5 py-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('superadmin.admin.edit', $admin) }}" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg" title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            <form method="POST" action="{{ route('superadmin.admin.toggle', $admin) }}">@csrf @method('PATCH')
                                <button class="p-2 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg" title="Toggle Status">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-5 py-8 text-center text-slate-400">Belum ada admin.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($admins->hasPages())
    <div class="px-5 py-4 border-t border-slate-200">{{ $admins->links() }}</div>
    @endif
</div>

{{-- Mobile Cards --}}
<div class="md:hidden space-y-3">
    @forelse($admins as $admin)
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4">
        <div class="flex items-start justify-between gap-3 mb-2">
            <div class="min-w-0 flex-1">
                <h3 class="font-semibold text-slate-800 text-sm truncate">{{ $admin->name }}</h3>
                <p class="text-xs text-slate-500 mt-0.5 truncate">{{ $admin->email }}</p>
            </div>
            <span class="shrink-0 px-2 py-0.5 rounded-full text-[10px] font-medium {{ $admin->is_active ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700' }}">{{ $admin->is_active ? 'Aktif' : 'Nonaktif' }}</span>
        </div>

        @if($admin->ormawas->count() > 0)
        <div class="mb-3">
            <span class="text-[10px] text-slate-400 uppercase font-semibold block mb-1">Ormawa</span>
            <div class="flex flex-wrap gap-1">
                @foreach($admin->ormawas as $o)
                <span class="px-2 py-0.5 rounded-full text-[10px] font-medium bg-indigo-50 text-indigo-700">{{ $o->nama }}</span>
                @endforeach
            </div>
        </div>
        @endif

        <div class="flex gap-2">
            <a href="{{ route('superadmin.admin.edit', $admin) }}" class="flex-1 text-xs font-semibold px-3 py-2.5 rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors flex items-center justify-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>Edit
            </a>
            <form method="POST" action="{{ route('superadmin.admin.toggle', $admin) }}" class="flex-1">@csrf @method('PATCH')
                <button class="w-full text-xs font-semibold px-3 py-2.5 rounded-xl {{ $admin->is_active ? 'bg-red-50 text-red-600 hover:bg-red-100' : 'bg-green-50 text-green-600 hover:bg-green-100' }} transition-colors flex items-center justify-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>{{ $admin->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                </button>
            </form>
        </div>
    </div>
    @empty
    <div class="bg-white rounded-2xl border border-slate-200 p-6 text-center shadow-sm">
        <p class="text-sm text-slate-400">Belum ada admin.</p>
    </div>
    @endforelse

    @if($admins->hasPages())
    <div class="mt-4">{{ $admins->links() }}</div>
    @endif
</div>
@endsection