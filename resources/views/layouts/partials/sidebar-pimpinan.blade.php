@php
    $currentRoute = request()->route()?->getName() ?? '';
@endphp

<a href="{{ route('pimpinan.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ str_starts_with($currentRoute, 'pimpinan.dashboard') ? 'bg-blue-600/20 text-blue-400' : 'text-slate-300 hover:text-white hover:bg-white/5' }}">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
    Dashboard
</a>

<p class="px-3 mt-4 mb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">Monitoring</p>

<a href="{{ route('pimpinan.ormawa.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ str_starts_with($currentRoute, 'pimpinan.ormawa') ? 'bg-blue-600/20 text-blue-400' : 'text-slate-300 hover:text-white hover:bg-white/5' }}">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
    Data Organisasi
</a>

<a href="{{ route('pimpinan.recruitment.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ str_starts_with($currentRoute, 'pimpinan.recruitment') ? 'bg-blue-600/20 text-blue-400' : 'text-slate-300 hover:text-white hover:bg-white/5' }}">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
    Data Rekrutmen
</a>
