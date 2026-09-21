@extends('layouts.guest')
@section('title', 'Daftar Akun')

@section('left-image')
    <img src="{{ asset('images/register_bg.webp') }}" alt="Study Group" class="absolute inset-0 w-full h-full object-cover opacity-50 mix-blend-overlay scale-105 hover:scale-100 transition-transform duration-[10s]">
@endsection

@section('content')
<h2 class="text-xl sm:text-2xl font-black text-slate-900 mb-1 tracking-tight">Daftar Akun Baru</h2>
<p class="text-slate-500 text-sm mb-6 font-medium">Lengkapi data diri Anda di bawah ini untuk memulai</p>

@error('throttle')
<div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-xl text-sm text-red-600 font-medium">
    {{ $message }}
</div>
@enderror

<form method="POST" action="{{ route('register') }}" class="space-y-4" x-data="registerForm()">
    @csrf
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        {{-- Nama Lengkap --}}
        <div>
            <label for="name" class="block text-xs font-bold text-slate-700 mb-1.5">Nama Lengkap</label>
            <div class="relative flex items-center">
                <input type="text" id="name" name="name" value="{{ old('name') }}" required placeholder="Misal: Budi Santoso"
                       class="peer w-full pl-9 focus:pl-3 pr-3 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-slate-900 text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition-all duration-300">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 peer-focus:opacity-0 peer-focus:-translate-x-2 transition-all duration-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
            </div>
            @error('name')<p class="mt-1 text-[10px] font-medium text-red-500">{{ $message }}</p>@enderror
        </div>
        
        {{-- NIM --}}
        <div>
            <label for="nim" class="block text-xs font-bold text-slate-700 mb-1.5">NIM <span class="font-normal text-slate-400">(maks. 9 digit)</span></label>
            <div class="relative flex items-center">
                <input type="text" id="nim" name="nim" value="{{ old('nim') }}" required maxlength="9" placeholder="Misal: H1D020001"
                       class="peer w-full pl-9 focus:pl-3 pr-3 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-slate-900 text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition-all duration-300">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 peer-focus:opacity-0 peer-focus:-translate-x-2 transition-all duration-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/></svg>
                </div>
            </div>
            @error('nim')<p class="mt-1 text-[10px] font-medium text-red-500">{{ $message }}</p>@enderror
        </div>
        
        {{-- Fakultas (Modal Picker) --}}
        <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">Fakultas</label>
            <input type="hidden" name="fakultas_id" :value="selectedFakultas">
            <button type="button" @click="openModal('fakultas')"
                    class="w-full flex items-center gap-2 pl-9 pr-3 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-left transition-all duration-300 hover:border-blue-400 hover:ring-2 hover:ring-blue-500/20 relative"
                    :class="selectedFakultasName ? 'text-slate-900' : 'text-slate-400'">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <span class="flex-1 truncate" x-text="selectedFakultasName || '-- Pilih Fakultas --'"></span>
                <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
            @error('fakultas_id')<p class="mt-1 text-[10px] font-medium text-red-500">{{ $message }}</p>@enderror
        </div>
        
        {{-- Jurusan (Modal Picker) --}}
        <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">Jurusan</label>
            <input type="hidden" name="jurusan_id" :value="selectedJurusan">
            <button type="button" @click="selectedFakultas ? openModal('jurusan') : null"
                    class="w-full flex items-center gap-2 pl-9 pr-3 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-left transition-all duration-300 relative"
                    :class="[
                        selectedJurusanName ? 'text-slate-900' : 'text-slate-400',
                        selectedFakultas ? 'hover:border-blue-400 hover:ring-2 hover:ring-blue-500/20 cursor-pointer' : 'opacity-50 cursor-not-allowed'
                    ]">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
                <span class="flex-1 truncate" x-text="selectedJurusanName || (selectedFakultas ? '-- Pilih Jurusan --' : 'Pilih Fakultas dulu')"></span>
                <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
            @error('jurusan_id')<p class="mt-1 text-[10px] font-medium text-red-500">{{ $message }}</p>@enderror
        </div>
        
        {{-- Angkatan --}}
        <div>
            <label for="angkatan" class="block text-xs font-bold text-slate-700 mb-1.5">Angkatan</label>
            <div class="relative flex items-center">
                <input type="text" id="angkatan" name="angkatan" value="{{ old('angkatan') }}" required maxlength="4" placeholder="Misal: 2022"
                       class="peer w-full pl-9 focus:pl-3 pr-3 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-slate-900 text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition-all duration-300">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 peer-focus:opacity-0 peer-focus:-translate-x-2 transition-all duration-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
            </div>
            @error('angkatan')<p class="mt-1 text-[10px] font-medium text-red-500">{{ $message }}</p>@enderror
        </div>
        
        {{-- No. HP --}}
        <div>
            <label for="no_hp" class="block text-xs font-bold text-slate-700 mb-1.5">No. HP <span class="font-normal text-slate-400">(Opsional)</span></label>
            <div class="relative flex items-center">
                <input type="text" id="no_hp" name="no_hp" value="{{ old('no_hp') }}" placeholder="08123456789"
                       class="peer w-full pl-9 focus:pl-3 pr-3 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-slate-900 text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition-all duration-300">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 peer-focus:opacity-0 peer-focus:-translate-x-2 transition-all duration-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                </div>
            </div>
        </div>
        
        {{-- Email --}}
        <div class="col-span-1 sm:col-span-2">
            <label for="email" class="block text-xs font-bold text-slate-700 mb-1.5">Email</label>
            <div class="relative flex items-center">
                <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="contoh@gmail.com"
                       class="peer w-full pl-9 focus:pl-3 pr-3 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-slate-900 text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition-all duration-300">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 peer-focus:opacity-0 peer-focus:-translate-x-2 transition-all duration-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/></svg>
                </div>
            </div>
            @error('email')<p class="mt-1 text-[10px] font-medium text-red-500">{{ $message }}</p>@enderror
        </div>
        
        {{-- Kata Sandi --}}
        <div x-data="{ show: false }">
            <label for="password" class="block text-xs font-bold text-slate-700 mb-1.5">Kata Sandi</label>
            <div class="relative flex items-center">
                <input :type="show ? 'text' : 'password'" id="password" name="password" required placeholder="••••••••"
                       class="peer w-full pl-9 focus:pl-3 pr-10 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-slate-900 text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition-all duration-300">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 peer-focus:opacity-0 peer-focus:-translate-x-2 transition-all duration-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>
                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-blue-600 focus:outline-none transition-colors">
                    <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                    <svg x-show="show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                </button>
            </div>
            <p class="mt-1 text-[10px] text-slate-400 font-medium">Min. 8 karakter, harus ada huruf besar & simbol</p>
            @error('password')<p class="mt-0.5 text-[10px] font-medium text-red-500">{{ $message }}</p>@enderror
        </div>
        
        {{-- Konfirmasi Sandi --}}
        <div x-data="{ show: false }">
            <label for="password_confirmation" class="block text-xs font-bold text-slate-700 mb-1.5">Konfirmasi Sandi</label>
            <div class="relative flex items-center">
                <input :type="show ? 'text' : 'password'" id="password_confirmation" name="password_confirmation" required placeholder="••••••••"
                       class="peer w-full pl-9 focus:pl-3 pr-10 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-slate-900 text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition-all duration-300">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 peer-focus:opacity-0 peer-focus:-translate-x-2 transition-all duration-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>
                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-blue-600 focus:outline-none transition-colors">
                    <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                    <svg x-show="show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                </button>
            </div>
        </div>
    </div>
    
    <button type="submit" class="w-full py-3 px-4 mt-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-black text-sm rounded-lg hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-4 focus:ring-blue-500/30 transition-all shadow-md shadow-blue-500/25 hover:-translate-y-0.5">
        Daftar Sekarang
    </button>

    {{-- ==================== MODAL PICKER ==================== --}}
    <template x-teleport="body">
        <div x-show="modalOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-[9999] flex items-center justify-center p-4" style="display:none;">
            {{-- Backdrop --}}
            <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="closeModal()"></div>
            
            {{-- Modal Content --}}
            <div x-show="modalOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                 class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm max-h-[70vh] flex flex-col overflow-hidden">
                
                {{-- Modal Header --}}
                <div class="p-4 border-b border-slate-100 flex items-center justify-between shrink-0">
                    <h3 class="font-bold text-slate-800 text-base" x-text="modalType === 'fakultas' ? 'Pilih Fakultas' : 'Pilih Jurusan'"></h3>
                    <button type="button" @click="closeModal()" class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 flex items-center justify-center transition-colors">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                
                {{-- Search --}}
                <div class="p-3 border-b border-slate-100 shrink-0">
                    <div class="relative">
                        <input type="text" x-model="modalSearch" x-ref="modalSearchInput" placeholder="Cari..."
                               class="w-full pl-9 pr-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition-all">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                    </div>
                </div>
                
                {{-- Options List --}}
                <div class="overflow-y-auto flex-1 p-2">
                    <template x-for="item in filteredModalItems" :key="item.id">
                        <button type="button" @click="selectItem(item)"
                                class="w-full text-left px-4 py-3 rounded-xl text-sm font-medium transition-all duration-150 flex items-center justify-between group"
                                :class="isSelected(item) ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'text-slate-700 hover:bg-slate-50 border border-transparent'">
                            <span x-text="modalType === 'fakultas' ? item.nama_fakultas : item.nama_jurusan"></span>
                            <svg x-show="isSelected(item)" class="w-5 h-5 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        </button>
                    </template>
                    <div x-show="filteredModalItems.length === 0" class="text-center py-8 text-slate-400 text-sm">
                        Tidak ditemukan
                    </div>
                </div>
            </div>
        </div>
    </template>
</form>

{{-- Divider --}}
<div class="flex items-center gap-4 my-5">
    <div class="flex-1 h-px bg-slate-200"></div>
    <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">atau</span>
    <div class="flex-1 h-px bg-slate-200"></div>
</div>

{{-- Google Register Button --}}
<a href="{{ route('auth.google') }}" class="w-full inline-flex items-center justify-center gap-2.5 py-2.5 px-4 bg-white border-2 border-slate-200 rounded-lg text-xs font-bold text-slate-700 hover:bg-slate-50 hover:border-slate-300 hover:text-slate-900 transition-all duration-300 shadow-sm hover:shadow-md group">
    <svg class="w-4 h-4 transition-transform group-hover:scale-110" viewBox="0 0 24 24">
        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 01-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z" fill="#4285F4"/>
        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
    </svg>
    Daftar dengan Google
</a>

<div class="mt-4 pt-4 border-t border-slate-100 text-center">
    <p class="text-xs text-slate-500 font-medium">
        Sudah punya akun? <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800 font-bold transition-colors">Masuk di sini</a>
    </p>
</div>

@push('scripts')
<script>
    function registerForm() {
        const fakultasData = @json($fakultas);
        const oldFakultas = '{{ old("fakultas_id", "") }}';
        const oldJurusan = '{{ old("jurusan_id", "") }}';

        return {
            selectedFakultas: oldFakultas,
            selectedFakultasName: '',
            selectedJurusan: oldJurusan,
            selectedJurusanName: '',
            fakultasData: fakultasData,

            modalOpen: false,
            modalType: '', // 'fakultas' or 'jurusan'
            modalSearch: '',

            init() {
                // Restore old values on page load
                if (this.selectedFakultas) {
                    const fak = this.fakultasData.find(f => f.id == this.selectedFakultas);
                    if (fak) this.selectedFakultasName = fak.nama_fakultas;
                }
                if (this.selectedJurusan && this.selectedFakultas) {
                    const fak = this.fakultasData.find(f => f.id == this.selectedFakultas);
                    if (fak) {
                        const jur = fak.jurusans.find(j => j.id == this.selectedJurusan);
                        if (jur) this.selectedJurusanName = jur.nama_jurusan;
                    }
                }
            },

            openModal(type) {
                this.modalType = type;
                this.modalSearch = '';
                this.modalOpen = true;
                this.$nextTick(() => {
                    if (this.$refs.modalSearchInput) this.$refs.modalSearchInput.focus();
                });
            },

            closeModal() {
                this.modalOpen = false;
            },

            get filteredModalItems() {
                let items = [];
                if (this.modalType === 'fakultas') {
                    items = this.fakultasData;
                    if (this.modalSearch) {
                        const q = this.modalSearch.toLowerCase();
                        items = items.filter(f => f.nama_fakultas.toLowerCase().includes(q));
                    }
                } else {
                    const fak = this.fakultasData.find(f => f.id == this.selectedFakultas);
                    items = fak ? fak.jurusans : [];
                    if (this.modalSearch) {
                        const q = this.modalSearch.toLowerCase();
                        items = items.filter(j => j.nama_jurusan.toLowerCase().includes(q));
                    }
                }
                return items;
            },

            isSelected(item) {
                if (this.modalType === 'fakultas') return item.id == this.selectedFakultas;
                return item.id == this.selectedJurusan;
            },

            selectItem(item) {
                if (this.modalType === 'fakultas') {
                    this.selectedFakultas = item.id;
                    this.selectedFakultasName = item.nama_fakultas;
                    // Reset jurusan when fakultas changes
                    this.selectedJurusan = '';
                    this.selectedJurusanName = '';
                } else {
                    this.selectedJurusan = item.id;
                    this.selectedJurusanName = item.nama_jurusan;
                }
                this.closeModal();
            }
        };
    }
</script>
@endpush
@endsection