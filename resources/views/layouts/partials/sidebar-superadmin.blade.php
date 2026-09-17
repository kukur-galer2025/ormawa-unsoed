@php
    $currentRoute = request()->route()->getName() ?? '';
@endphp

<a href="{{ route('superadmin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ str_starts_with($currentRoute, 'superadmin.dashboard') ? 'bg-blue-600/20 text-blue-400' : 'text-slate-300 hover:text-white hover:bg-white/5' }}">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
    Dashboard
</a>

<p class="px-3 mt-4 mb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">Master Data</p>

<a href="{{ route('superadmin.ormawa.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ str_starts_with($currentRoute, 'superadmin.ormawa') ? 'bg-blue-600/20 text-blue-400' : 'text-slate-300 hover:text-white hover:bg-white/5' }}">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
    Kelola Ormawa
</a>

<a href="{{ route('superadmin.fakultas.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ str_starts_with($currentRoute, 'superadmin.fakultas') ? 'bg-blue-600/20 text-blue-400' : 'text-slate-300 hover:text-white hover:bg-white/5' }}">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
    Fakultas
</a>

<a href="{{ route('superadmin.jurusan.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ str_starts_with($currentRoute, 'superadmin.jurusan') ? 'bg-blue-600/20 text-blue-400' : 'text-slate-300 hover:text-white hover:bg-white/5' }}">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
    Jurusan
</a>

<p class="px-3 mt-4 mb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">Pengguna</p>

<a href="{{ route('superadmin.admin.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ str_starts_with($currentRoute, 'superadmin.admin') ? 'bg-blue-600/20 text-blue-400' : 'text-slate-300 hover:text-white hover:bg-white/5' }}">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
    Kelola Admin
</a>

<a href="{{ route('superadmin.mahasiswa.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ str_starts_with($currentRoute, 'superadmin.mahasiswa') ? 'bg-blue-600/20 text-blue-400' : 'text-slate-300 hover:text-white hover:bg-white/5' }}">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
    Kelola Mahasiswa
</a>

<p class="px-3 mt-4 mb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">Monitoring</p>

<a href="{{ route('superadmin.recruitment-monitoring.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ str_starts_with($currentRoute, 'superadmin.recruitment-monitoring') ? 'bg-blue-600/20 text-blue-400' : 'text-slate-300 hover:text-white hover:bg-white/5' }}">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
    Monitoring Rekrutmen
</a>