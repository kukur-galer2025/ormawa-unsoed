@php
    $currentRoute = request()->route()->getName() ?? '';
@endphp

<a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ str_starts_with($currentRoute, 'admin.dashboard') ? 'bg-blue-600/20 text-blue-400' : 'text-slate-300 hover:text-white hover:bg-white/5' }}">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
    Dashboard
</a>

<p class="px-3 mt-4 mb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">Organisasi</p>

<ul class="space-y-1">
    <li>
        <a href="{{ route('admin.ormawa-profile.edit') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-all duration-200 {{ request()->routeIs('admin.ormawa-profile.*') ? 'bg-blue-600/20 text-blue-400 font-semibold' : 'text-slate-300 hover:text-white hover:bg-white/5 font-medium' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            Profil Ormawa
        </a>
    </li>
    <li>
        <a href="{{ route('admin.prestasi.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-all duration-200 {{ request()->routeIs('admin.prestasi.*') ? 'bg-blue-600/20 text-blue-400 font-semibold' : 'text-slate-300 hover:text-white hover:bg-white/5 font-medium' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
            Kelola Prestasi
        </a>
    </li>
    <li>
        <a href="{{ route('admin.proker.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-all duration-200 {{ request()->routeIs('admin.proker.*') ? 'bg-blue-600/20 text-blue-400 font-semibold' : 'text-slate-300 hover:text-white hover:bg-white/5 font-medium' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
            Kelola Program Kerja
        </a>
    </li>
</ul>

<p class="px-3 mt-4 mb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">Rekrutmen</p>

<ul class="space-y-1">
    <li>
        <a href="{{ route('admin.recruitment.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.recruitment.*') ? 'bg-blue-600/20 text-blue-400' : 'text-slate-300 hover:text-white hover:bg-white/5' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            Info & Divisi
        </a>
    </li>
    <li>
        <a href="{{ route('admin.criteria.select') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.criteria.*') || request()->routeIs('admin.aspect.*') ? 'bg-blue-600/20 text-blue-400' : 'text-slate-300 hover:text-white hover:bg-white/5' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
            Kriteria Penilaian
        </a>
    </li>
</ul>

<p class="px-3 mt-4 mb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">Seleksi</p>

<ul class="space-y-1">
    <li>
        <a href="{{ route('admin.applicants.select') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.applicants.*') ? 'bg-blue-600/20 text-blue-400' : 'text-slate-300 hover:text-white hover:bg-white/5' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            Data Pelamar
        </a>
    </li>
    <li>
        <a href="{{ route('admin.scoring.select') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.scoring.*') ? 'bg-blue-600/20 text-blue-400' : 'text-slate-300 hover:text-white hover:bg-white/5' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            Input Nilai
        </a>
    </li>
    <li>
        <a href="{{ route('admin.profile-matching.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.profile-matching.*') ? 'bg-blue-600/20 text-blue-400' : 'text-slate-300 hover:text-white hover:bg-white/5' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            Profile Matching
        </a>
    </li>
</ul>