@extends("layouts.app")
@section("title","Monitoring Rekrutmen")@section("page-title","Monitoring Rekrutmen")
@section("sidebar")@include("layouts.partials.sidebar-superadmin")@endsection
@section("content")
{{-- Stats Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-slate-800">{{ $stats['total'] }}</p>
                <p class="text-xs text-slate-500">Total Rekrutmen</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-green-50 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-green-700">{{ $stats['dibuka'] }}</p>
                <p class="text-xs text-slate-500">Sedang Dibuka</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-red-50 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-red-700">{{ $stats['ditutup'] }}</p>
                <p class="text-xs text-slate-500">Ditutup</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-blue-700">{{ $stats['selesai'] }}</p>
                <p class="text-xs text-slate-500">Selesai</p>
            </div>
        </div>
    </div>
</div>

{{-- Filter & Search --}}
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm mb-6">
    <div class="px-6 py-4 border-b border-slate-200">
        <form method="GET" class="flex flex-col sm:flex-row items-start sm:items-center gap-3">
            <div class="flex-1 w-full sm:w-auto">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul atau nama ormawa..." class="w-full px-4 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
            </div>
            <select name="status" class="px-4 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                <option value="">Semua Status</option>
                <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="dibuka" {{ request('status') === 'dibuka' ? 'selected' : '' }}>Dibuka</option>
                <option value="ditutup" {{ request('status') === 'ditutup' ? 'selected' : '' }}>Ditutup</option>
                <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-slate-800 text-white text-sm font-medium rounded-xl hover:bg-slate-700 transition-colors">Filter</button>
            @if(request('search') || request('status'))
                <a href="{{ route('superadmin.recruitment-monitoring.index') }}" class="px-4 py-2 text-sm text-slate-500 hover:text-slate-700">Reset</a>
            @endif
        </form>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-slate-50">
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Judul Rekrutmen</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Ormawa</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Pelamar</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Periode</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($recruitments as $r)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4">
                        <p class="text-sm font-medium text-slate-800">{{ $r->judul }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center text-white text-xs font-bold">{{ strtoupper(substr($r->ormawa->nama, 0, 1)) }}</div>
                            <span class="text-sm text-slate-700">{{ $r->ormawa->nama }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @php
                            $statusClasses = [
                                'draft' => 'bg-slate-100 text-slate-600',
                                'dibuka' => 'bg-green-50 text-green-700',
                                'ditutup' => 'bg-red-50 text-red-700',
                                'selesai' => 'bg-blue-50 text-blue-700',
                            ];
                        @endphp
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClasses[$r->status] ?? 'bg-slate-100 text-slate-600' }}">
                            {{ ucfirst($r->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-sm font-semibold text-slate-800">{{ $r->applications_count }}</span>
                        <span class="text-xs text-slate-400">orang</span>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-sm text-slate-600">{{ $r->tanggal_buka->format('d M Y') }}</p>
                        <p class="text-xs text-slate-400">s/d {{ $r->tanggal_tutup->format('d M Y') }}</p>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                        <svg class="w-12 h-12 mx-auto mb-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        Belum ada rekrutmen yang ditemukan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($recruitments->hasPages())
    <div class="px-6 py-4 border-t border-slate-200">
        {{ $recruitments->links() }}
    </div>
    @endif
</div>
@endsection
