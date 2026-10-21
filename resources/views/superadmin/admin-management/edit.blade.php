@extends("layouts.app")
@section("title", "Edit Admin")
@section("page-title", "Edit Admin")
@section("sidebar")
    @include("layouts.partials.sidebar-superadmin")
@endsection

@php
    $oldOrmawaId   = old('ormawa_id', $assignedOrmawaId);
    $oldOrmawaName = 'Pilih Ormawa...';
    if ($oldOrmawaId) {
        $selectedOrmawa = $allOrmawas->firstWhere('id', $oldOrmawaId);
        if ($selectedOrmawa) $oldOrmawaName = $selectedOrmawa->nama;
    }
@endphp

@section("content")
<div class="mb-6">
    <a href="{{ route('superadmin.admin.index') }}" class="inline-flex items-center gap-1.5 text-sm text-blue-600 hover:text-blue-800 font-medium">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Kembali ke Daftar Admin
    </a>
</div>

<div class="max-w-2xl bg-white rounded-2xl border border-slate-200 shadow-sm p-5 sm:p-6">
    <div class="mb-6 pb-4 border-b border-slate-100">
        <h3 class="text-lg font-bold text-slate-800">Edit Akun Admin</h3>
        <p class="text-sm text-slate-500 mt-0.5">Perbarui informasi dan penugasan organisasi untuk akun admin ini.</p>
    </div>

    <form method="POST" action="{{ route('superadmin.admin.update', $admin) }}" class="space-y-5">
        @csrf @method('PUT')

        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama <span class="text-red-500">*</span></label>
            <input type="text" name="name" value="{{ old('name', $admin->name) }}" required
                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-sm text-slate-700"
                   placeholder="Masukkan nama admin">
            @error('name')<p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Email <span class="text-red-500">*</span></label>
            <input type="email" name="email" value="{{ old('email', $admin->email) }}" required
                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-sm text-slate-700"
                   placeholder="admin@contoh.com">
            @error('email')<p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Password</label>
            <input type="password" name="password"
                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-sm text-slate-700"
                   placeholder="Kosongkan jika tidak ingin mengubah password">
            @error('password')<p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p>@enderror
        </div>

        {{-- Assign ke Ormawa --}}
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                Assign ke Ormawa <span class="text-red-500">*</span>
            </label>

            <input type="hidden" name="ormawa_id" id="ormawaIdInput" value="{{ $oldOrmawaId }}">

            {{-- Trigger button --}}
            <button type="button" id="btnOpenOrmawaPicker"
                    class="w-full flex items-center justify-between px-4 py-3 border rounded-xl transition-all text-left cursor-pointer shadow-sm group {{ $oldOrmawaId ? 'bg-blue-50/40 border-blue-300' : 'bg-slate-50 border-slate-200 hover:bg-blue-50/40 hover:border-blue-300' }}">
                <div class="flex items-center gap-3 min-w-0">
                    <div id="ormawaPickerIcon"
                         class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 transition-colors {{ $oldOrmawaId ? 'bg-blue-600 text-white' : 'bg-slate-200 text-slate-500' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <span id="ormawaPickerLabel"
                              class="block text-sm break-words leading-snug {{ $oldOrmawaId ? 'font-semibold text-slate-800' : 'font-normal text-slate-400' }}">
                            {{ $oldOrmawaName }}
                        </span>
                        <span id="ormawaPickerSub" class="text-xs {{ $oldOrmawaId ? 'text-blue-500' : 'text-slate-400' }}">
                            {{ $oldOrmawaId ? 'Ketuk untuk mengganti' : 'Ketuk untuk memilih organisasi' }}
                        </span>
                    </div>
                </div>
                <div class="shrink-0 ml-3">
                    <span id="ormawaPickerBadge"
                          class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg border text-xs font-semibold {{ $oldOrmawaId ? 'bg-blue-100 border-blue-300 text-blue-700' : 'bg-white border-slate-200 text-slate-500' }}">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/></svg>
                        <span>{{ $oldOrmawaId ? 'Ganti' : 'Pilih' }}</span>
                    </span>
                </div>
            </button>
            @error('ormawa_id')<p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p>@enderror
        </div>

        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 pt-4 border-t border-slate-100">
            <button type="submit"
                    class="flex-1 sm:flex-none px-6 py-3 sm:py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 rounded-xl shadow-lg shadow-blue-600/20 transition-all text-center">
                Simpan Perubahan
            </button>
            <a href="{{ route('superadmin.admin.index') }}"
               class="px-6 py-3 sm:py-2.5 text-sm font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors text-center">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection

{{-- =====================================================================
     ORMAWA PICKER MODAL — di-push ke @stack('modals') yang ada di luar
     container overflow-y-auto, sehingga position:fixed selalu ke viewport.
     ===================================================================== --}}
@push('modals')
<div id="ormawaPicker" style="display:none;"
     aria-modal="true" role="dialog" aria-label="Pilih Organisasi Mahasiswa">

    {{-- Backdrop --}}
    <div id="ormawaPickerBackdrop"
         style="position:fixed;inset:0;z-index:99998;background:rgba(15,23,42,0.55);backdrop-filter:blur(4px);-webkit-backdrop-filter:blur(4px);"
         onclick="closeOrmawaPicker()"></div>

    {{-- Modal Card --}}
    <div id="ormawaPickerCard"
         style="position:fixed;z-index:99999;left:50%;top:50%;transform:translate(-50%,-50%);
                width:min(520px,calc(100vw - 2rem));
                max-height:min(85vh,640px);
                display:flex;flex-direction:column;
                background:#fff;border-radius:1.25rem;
                box-shadow:0 25px 60px -12px rgba(0,0,0,0.3),0 0 0 1px rgba(148,163,184,0.15);
                overflow:hidden;">

        {{-- Mobile drag handle --}}
        <div class="mobile-drag-handle" style="display:none;width:36px;height:4px;background:#cbd5e1;border-radius:999px;margin:0.625rem auto 0 auto;flex-shrink:0;"></div>

        {{-- Header --}}
        <div class="ormawa-modal-header" style="padding:1.25rem 1.5rem 1rem;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;background:linear-gradient(to right,#f8fafc,#fff);flex-shrink:0;">
            <div style="display:flex;align-items:center;gap:0.75rem;">
                <div style="width:2.5rem;height:2.5rem;border-radius:0.875rem;background:#dbeafe;color:#2563eb;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <div>
                    <h3 style="font-weight:700;color:#1e293b;font-size:1rem;line-height:1.4;">Pilih Organisasi Mahasiswa</h3>
                    <p style="font-size:0.75rem;color:#94a3b8;margin-top:1px;">Pilih ormawa yang akan dikelola admin ini</p>
                </div>
            </div>
            <button type="button" onclick="closeOrmawaPicker()" aria-label="Tutup"
                    style="width:2rem;height:2rem;border-radius:0.5rem;background:#f1f5f9;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;color:#64748b;"
                    onmouseenter="this.style.background='#e2e8f0'" onmouseleave="this.style.background='#f1f5f9'">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Search --}}
        <div class="ormawa-search-wrap" style="padding:0.875rem 1.25rem;border-bottom:1px solid #f1f5f9;flex-shrink:0;">
            <div style="position:relative;">
                <input type="text" id="ormawaPickerSearch" autocomplete="off"
                       placeholder="Cari nama ormawa..."
                       oninput="filterOrmawa(this.value)"
                       style="width:100%;padding:0.625rem 2.25rem 0.625rem 2.5rem;border:1px solid #e2e8f0;border-radius:0.875rem;font-size:0.875rem;background:#f8fafc;outline:none;box-sizing:border-box;color:#374151;"
                       onfocus="this.style.borderColor='#93c5fd';this.style.boxShadow='0 0 0 3px rgba(147,197,253,0.3)'"
                       onblur="this.style.borderColor='#e2e8f0';this.style.boxShadow='none'">
                <span style="position:absolute;left:0.75rem;top:50%;transform:translateY(-50%);color:#94a3b8;pointer-events:none;">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </span>
                <button type="button" id="ormawaSearchClear"
                        onclick="document.getElementById('ormawaPickerSearch').value='';filterOrmawa('');document.getElementById('ormawaPickerSearch').focus();"
                        style="position:absolute;right:0.75rem;top:50%;transform:translateY(-50%);color:#94a3b8;background:none;border:none;cursor:pointer;display:none;padding:2px;">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>

        {{-- List --}}
        <div id="ormawaPickerList" style="overflow-y:auto;flex:1;padding:0.875rem;display:flex;flex-direction:column;gap:0.5rem;-webkit-overflow-scrolling:touch;">
            <div class="ormawa-item-reset"
                 onclick="selectOrmawa('', 'Pilih Ormawa...')"
                 style="padding:0.75rem 1rem;border-radius:0.875rem;border:1.5px dashed #e2e8f0;cursor:pointer;display:flex;align-items:center;justify-content:space-between;transition:background 0.15s;"
                 onmouseenter="this.style.background='#f8fafc'" onmouseleave="this.style.background='transparent'">
                <span style="font-size:0.875rem;font-style:italic;color:#94a3b8;font-weight:500;">— Batal Pilih / Kosongkan —</span>
                <span style="font-size:0.7rem;color:#cbd5e1;">Reset</span>
            </div>

            @foreach($allOrmawas as $o)
                @php
                    $isTaken   = in_array($o->id, $takenOrmawaIds);
                    $isCurrent = $o->id == $assignedOrmawaId;
                @endphp
                @if($isTaken && !$isCurrent)
                {{-- Sudah Punya Admin --}}
                <div class="ormawa-item ormawa-item-taken"
                     data-nama="{{ strtolower($o->nama) }}"
                     onclick="rejectTakenOrmawa()"
                     title="{{ $o->nama }}"
                     style="padding:0.75rem 0.875rem;border-radius:0.875rem;border:1px solid rgba(251,191,36,0.5);background:rgba(255,251,235,0.5);cursor:pointer;display:flex;align-items:center;justify-content:space-between;gap:0.75rem;transition:background 0.15s;"
                     onmouseenter="this.style.background='rgba(255,251,235,0.9)'" onmouseleave="this.style.background='rgba(255,251,235,0.5)'">
                    <div style="display:flex;align-items:center;gap:0.75rem;min-width:0;flex:1;">
                        <div style="width:2.25rem;height:2.25rem;border-radius:0.625rem;background:#fef3c7;color:#b45309;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:0.75rem;flex-shrink:0;">
                            {{ strtoupper(substr($o->nama, 0, 2)) }}
                        </div>
                        <div style="min-width:0;flex:1;">
                            <div class="ormawa-item-nama" style="font-size:0.875rem;font-weight:600;color:#374151;line-height:1.35;word-break:break-word;">{{ $o->nama }}</div>
                            <div style="font-size:0.7rem;color:#94a3b8;text-transform:capitalize;margin-top:2px;">{{ $o->tingkat ?? 'Ormawa' }}</div>
                        </div>
                    </div>
                    <span class="ormawa-badge-taken" style="flex-shrink:0;padding:0.25rem 0.625rem;border-radius:999px;font-size:0.7rem;font-weight:700;background:#fef3c7;color:#b45309;border:1px solid #fcd34d;white-space:nowrap;display:flex;align-items:center;gap:0.375rem;">
                        <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        <span class="badge-text-full">Sudah Ada Admin</span>
                        <span class="badge-text-short">Ada Admin</span>
                    </span>
                </div>
                @else
                {{-- Available / Current --}}
                <div class="ormawa-item {{ $isCurrent ? 'selected' : '' }}"
                     data-nama="{{ strtolower($o->nama) }}"
                     data-id="{{ $o->id }}"
                     data-label="{{ $o->nama }}"
                     onclick="selectOrmawa('{{ $o->id }}', '{{ addslashes($o->nama) }}')"
                     title="{{ $o->nama }}"
                     style="padding:0.75rem 0.875rem;border-radius:0.875rem;border:1px solid {{ $isCurrent ? '#93c5fd' : '#e2e8f0' }};background:{{ $isCurrent ? 'rgba(239,246,255,0.9)' : '#fff' }};cursor:pointer;display:flex;align-items:center;justify-content:space-between;gap:0.75rem;transition:all 0.15s;"
                     onmouseenter="if(!this.classList.contains('selected'))this.style.borderColor='#93c5fd',this.style.background='rgba(239,246,255,0.5)'"
                     onmouseleave="if(!this.classList.contains('selected'))this.style.borderColor='#e2e8f0',this.style.background='#fff'">
                    <div style="display:flex;align-items:center;gap:0.75rem;min-width:0;flex:1;">
                        <div class="ormawa-avatar"
                             style="width:2.25rem;height:2.25rem;border-radius:0.625rem;background:{{ $isCurrent ? '#2563eb' : '#f1f5f9' }};color:{{ $isCurrent ? '#fff' : '#475569' }};display:flex;align-items:center;justify-content:center;font-weight:700;font-size:0.75rem;flex-shrink:0;transition:all 0.15s;">
                            {{ strtoupper(substr($o->nama, 0, 2)) }}
                        </div>
                        <div style="min-width:0;flex:1;">
                            <div class="ormawa-nama ormawa-item-nama"
                                 style="font-size:0.875rem;font-weight:600;color:{{ $isCurrent ? '#1e40af' : '#1e293b' }};line-height:1.35;word-break:break-word;">{{ $o->nama }}</div>
                            <div style="display:flex;align-items:center;gap:0.375rem;margin-top:2px;">
                                <span style="font-size:0.7rem;color:#94a3b8;text-transform:capitalize;">{{ $o->tingkat ?? 'Ormawa' }}</span>
                                @if($isCurrent)
                                <span style="font-size:0.65rem;font-weight:700;padding:0.1rem 0.375rem;border-radius:999px;background:#e0e7ff;color:#4338ca;">Saat Ini</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div style="display:flex;align-items:center;gap:0.5rem;flex-shrink:0;">
                        <div class="ormawa-check"
                             style="display:{{ $isCurrent ? 'flex' : 'none' }};width:1.5rem;height:1.5rem;border-radius:999px;background:#2563eb;color:#fff;align-items:center;justify-content:center;">
                            <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <span class="ormawa-badge"
                              style="display:{{ $isCurrent ? 'none' : 'inline-flex' }};font-size:0.7rem;font-weight:600;padding:0.25rem 0.625rem;border-radius:0.5rem;background:#f8fafc;border:1px solid #e2e8f0;color:#64748b;">Pilih</span>
                    </div>
                </div>
                @endif
            @endforeach

            <div id="ormawaEmptyState" style="display:none;text-align:center;padding:2rem;color:#94a3b8;font-size:0.875rem;">
                <svg width="40" height="40" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin:0 auto 0.75rem;opacity:0.4;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Tidak ada ormawa yang cocok
            </div>
        </div>

        {{-- Footer --}}
        <div style="padding:0.875rem 1.5rem;border-top:1px solid #f1f5f9;background:#f8fafc;display:flex;align-items:center;justify-content:space-between;flex-shrink:0;">
            <span style="font-size:0.75rem;color:#94a3b8;">{{ $allOrmawas->count() }} organisasi terdaftar</span>
            <button type="button" onclick="closeOrmawaPicker()"
                    style="padding:0.5rem 1rem;font-size:0.75rem;font-weight:600;color:#475569;background:#fff;border:1px solid #e2e8f0;border-radius:0.75rem;cursor:pointer;"
                    onmouseenter="this.style.background='#f1f5f9'" onmouseleave="this.style.background='#fff'">
                Tutup
            </button>
        </div>
    </div>
</div>

<style>
/* Responsive Ormawa Picker & Bottom Sheet */
.ormawa-item-nama {
    white-space: normal;
    word-break: break-word;
    overflow-wrap: break-word;
}
.ormawa-badge-taken .badge-text-short {
    display: none;
}

@media (max-width: 600px) {
    #ormawaPickerCard {
        top: auto !important;
        bottom: 0 !important;
        left: 0 !important;
        right: 0 !important;
        transform: none !important;
        width: 100% !important;
        max-height: 90vh !important;
        border-radius: 1.5rem 1.5rem 0 0 !important;
    }
    .mobile-drag-handle {
        display: block !important;
    }
    .ormawa-modal-header {
        padding: 0.875rem 1rem 0.75rem !important;
    }
    .ormawa-search-wrap {
        padding: 0.625rem 1rem !important;
    }
    #ormawaPickerList {
        padding: 0.625rem !important;
        gap: 0.375rem !important;
    }
    .ormawa-item {
        padding: 0.625rem 0.75rem !important;
        gap: 0.5rem !important;
    }
    .ormawa-item-nama {
        font-size: 0.8125rem !important;
        line-height: 1.3 !important;
    }
    .ormawa-badge-taken {
        padding: 0.2rem 0.45rem !important;
        font-size: 0.65rem !important;
    }
    .ormawa-badge-taken .badge-text-full {
        display: none !important;
    }
    .ormawa-badge-taken .badge-text-short {
        display: inline !important;
    }
    .ormawa-badge {
        padding: 0.2rem 0.5rem !important;
        font-size: 0.65rem !important;
    }
}

/* Smooth open/close animation */
#ormawaPicker.is-open #ormawaPickerBackdrop { animation: fadeIn 0.2s ease; }
#ormawaPicker.is-open #ormawaPickerCard { animation: slideUp 0.25s cubic-bezier(0.34,1.56,0.64,1); }
@media (min-width: 601px) {
    #ormawaPicker.is-open #ormawaPickerCard { animation: popIn 0.2s cubic-bezier(0.34,1.56,0.64,1); }
}
@keyframes fadeIn  { from{opacity:0} to{opacity:1} }
@keyframes slideUp { from{transform:translateY(100%)} to{transform:translateY(0)} }
@keyframes popIn   { from{opacity:0;transform:translate(-50%,-50%) scale(0.93)} to{opacity:1;transform:translate(-50%,-50%) scale(1)} }
</style>

<script>
(function(){
    const CURRENT_ORMAWA_ID = '{{ $oldOrmawaId }}';

    window.openOrmawaPicker = function() {
        const picker = document.getElementById('ormawaPicker');
        if (!picker) return;
        picker.style.display = 'block';
        picker.classList.add('is-open');
        document.body.style.overflow = 'hidden';
        const inp = document.getElementById('ormawaPickerSearch');
        if (inp) { inp.value = ''; filterOrmawa(''); setTimeout(()=>inp.focus(), 80); }
    };
    window.closeOrmawaPicker = function() {
        const picker = document.getElementById('ormawaPicker');
        if (!picker) return;
        picker.style.display = 'none';
        picker.classList.remove('is-open');
        document.body.style.overflow = '';
    };
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeOrmawaPicker();
    });
    window.filterOrmawa = function(q) {
        const term = (q||'').toLowerCase().trim();
        let visible = 0;
        document.querySelectorAll('#ormawaPicker .ormawa-item').forEach(function(el){
            const show = !term || (el.dataset.nama||'').includes(term);
            el.style.display = show ? 'flex' : 'none';
            if(show) visible++;
        });
        const resetEl = document.querySelector('#ormawaPicker .ormawa-item-reset');
        if(resetEl) resetEl.style.display = term ? 'none' : 'flex';
        const empty = document.getElementById('ormawaEmptyState');
        if(empty) empty.style.display = visible===0 ? 'block' : 'none';
        const clr = document.getElementById('ormawaSearchClear');
        if(clr) clr.style.display = term ? 'flex' : 'none';
    };
    window.selectOrmawa = function(id, name) {
        document.getElementById('ormawaIdInput').value = id;
        const label   = document.getElementById('ormawaPickerLabel');
        const sub     = document.getElementById('ormawaPickerSub');
        const icon    = document.getElementById('ormawaPickerIcon');
        const badge   = document.getElementById('ormawaPickerBadge');
        const trigger = document.getElementById('btnOpenOrmawaPicker');
        if (id) {
            label.textContent = name; label.style.fontWeight='600'; label.style.color='#1e293b';
            sub.textContent = 'Ketuk untuk mengganti'; sub.style.color='#3b82f6';
            icon.style.background='#2563eb'; icon.style.color='#fff';
            badge.innerHTML = '<svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/></svg> Ganti';
            badge.style.background='#dbeafe'; badge.style.borderColor='#93c5fd'; badge.style.color='#1d4ed8';
            trigger.style.borderColor='#93c5fd'; trigger.style.background='rgba(239,246,255,0.6)';
        } else {
            label.textContent = 'Pilih Ormawa...'; label.style.fontWeight='400'; label.style.color='#94a3b8';
            sub.textContent = 'Ketuk untuk memilih organisasi'; sub.style.color='#94a3b8';
            icon.style.background='#e2e8f0'; icon.style.color='#64748b';
            badge.innerHTML = '<svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/></svg> Pilih';
            badge.style.background='#fff'; badge.style.borderColor='#e2e8f0'; badge.style.color='#64748b';
            trigger.style.borderColor='#e2e8f0'; trigger.style.background='';
        }
        document.querySelectorAll('#ormawaPicker .ormawa-item[data-id]').forEach(function(el){
            const sel = el.dataset.id == id;
            const check = el.querySelector('.ormawa-check');
            const bEl   = el.querySelector('.ormawa-badge');
            const av    = el.querySelector('.ormawa-avatar');
            const nm    = el.querySelector('.ormawa-nama');
            if(sel){
                el.style.borderColor='#93c5fd'; el.style.background='rgba(239,246,255,0.9)';
                if(check) check.style.display='flex';
                if(bEl)   bEl.style.display='none';
                if(av)    { av.style.background='#2563eb'; av.style.color='#fff'; }
                if(nm)    nm.style.color='#1e40af';
                el.classList.add('selected');
            } else {
                el.style.borderColor='#e2e8f0'; el.style.background='#fff';
                if(check) check.style.display='none';
                if(bEl)   bEl.style.display='inline-flex';
                if(av)    { av.style.background='#f1f5f9'; av.style.color='#475569'; }
                if(nm)    nm.style.color='#1e293b';
                el.classList.remove('selected');
            }
        });
        closeOrmawaPicker();
    };
    window.rejectTakenOrmawa = function() {
        closeOrmawaPicker();
        Swal.fire({
            title: 'Penugasan Ditolak!',
            text: 'Organisasi ini sudah memiliki Admin.',
            icon: 'warning',
            confirmButtonColor: '#4f46e5',
            confirmButtonText: 'Tutup',
            customClass: { popup: 'rounded-2xl', confirmButton: 'rounded-lg' }
        });
    };
    document.addEventListener('DOMContentLoaded', function(){
        const btn = document.getElementById('btnOpenOrmawaPicker');
        if(btn) btn.addEventListener('click', openOrmawaPicker);
    });
})();
</script>
@endpush