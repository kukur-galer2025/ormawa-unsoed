@extends("layouts.app")
@section("title", "Edit Admin")
@section("page-title", "Edit Admin")
@section("sidebar")
    @include("layouts.partials.sidebar-superadmin")
@endsection

@section("content")
<div class="mb-6">
    <a href="{{ route('superadmin.admin.index') }}" class="text-sm text-blue-600 hover:underline">&larr; Kembali ke Daftar Admin</a>
</div>

<div class="max-w-2xl bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
    <div class="mb-6 pb-4 border-b border-slate-100">
        <h3 class="text-lg font-bold text-slate-800">Edit Akun Admin</h3>
        <p class="text-sm text-slate-500">Perbarui informasi dan penugasan organisasi untuk akun admin ini.</p>
    </div>

    <form method="POST" action="{{ route('superadmin.admin.update', $admin) }}" class="space-y-5">
        @csrf @method('PUT')
        
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama <span class="text-red-500">*</span></label>
            <input type="text" name="name" value="{{ old('name', $admin->name) }}" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-sm text-slate-700" placeholder="Masukkan nama admin">
            @error('name')<p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p>@enderror
        </div>
        
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Email <span class="text-red-500">*</span></label>
            <input type="email" name="email" value="{{ old('email', $admin->email) }}" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-sm text-slate-700" placeholder="admin@contoh.com">
            @error('email')<p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p>@enderror
        </div>
        
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Password</label>
            <input type="password" name="password" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-sm text-slate-700" placeholder="Kosongkan jika tidak ingin mengubah password">
            @error('password')<p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p>@enderror
        </div>
        
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Assign ke Ormawa <span class="text-red-500">*</span></label>
            @php
                $oldOrmawaId = old('ormawa_id', $assignedOrmawaId);
                $oldOrmawaName = 'Pilih Ormawa...';
                if ($oldOrmawaId) {
                    $selectedOrmawa = $allOrmawas->firstWhere('id', $oldOrmawaId);
                    if ($selectedOrmawa) $oldOrmawaName = $selectedOrmawa->nama;
                }
            @endphp
            <div x-data="{ open: false, selectedId: '{{ $oldOrmawaId }}', selectedName: '{{ addslashes($oldOrmawaName) }}' }" class="relative">
                <input type="hidden" name="ormawa_id" x-model="selectedId">
                <button type="button" @click="open = !open" @click.away="open = false" class="w-full flex items-center justify-between px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-sm text-slate-700 text-left">
                    <span x-text="selectedName" class="truncate"></span>
                    <svg class="w-4 h-4 text-slate-400 transition-transform duration-200 shrink-0 ml-2" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                
                <div x-show="open" x-transition.opacity class="absolute z-10 w-full mt-1.5 bg-white border border-slate-200 rounded-xl shadow-xl max-h-60 overflow-y-auto" style="display: none;">
                    <ul class="py-1.5">
                        <li @click="selectedId = ''; selectedName = 'Pilih Ormawa...'; open = false" class="px-4 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50 cursor-pointer border-b border-slate-100 last:border-0">
                            -- Pilih Ormawa --
                        </li>
                        @foreach($allOrmawas as $o)
                            @php 
                                $isTaken = in_array($o->id, $takenOrmawaIds); 
                                $isCurrent = $o->id == $assignedOrmawaId;
                            @endphp
                            @if($isTaken && !$isCurrent)
                            <li class="px-4 py-2.5 text-sm text-slate-400 bg-slate-50 cursor-not-allowed flex justify-between items-center border-b border-slate-100 last:border-0 gap-2">
                                <span class="truncate">{{ $o->nama }}</span>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-700 shadow-sm whitespace-nowrap shrink-0">Sudah Ada Admin</span>
                            </li>
                            @else
                            <li @click="selectedId = '{{ $o->id }}'; selectedName = '{{ addslashes($o->nama) }}'; open = false" class="px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-blue-50 hover:text-blue-700 cursor-pointer flex justify-between items-center transition-colors border-b border-slate-100 last:border-0 gap-2">
                                <span class="truncate">{{ $o->nama }}</span>
                                @if($isCurrent)
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-700 shadow-sm whitespace-nowrap shrink-0">Saat Ini</span>
                                @endif
                            </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>
            @error('ormawa_id')<p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p>@enderror
        </div>
        
        <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
            <button type="submit" class="px-6 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 rounded-xl shadow-lg shadow-blue-600/20 transition-all">Simpan Perubahan</button>
            <a href="{{ route('superadmin.admin.index') }}" class="px-6 py-2.5 text-sm font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">Batal</a>
        </div>
    </form>
</div>
@endsection