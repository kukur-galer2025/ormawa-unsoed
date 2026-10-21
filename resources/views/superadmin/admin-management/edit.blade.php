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
            <div x-data="{
                modalOpen: false,
                selectedId: '{{ $oldOrmawaId }}',
                selectedName: '{{ addslashes($oldOrmawaName) }}',
                search: '',
                openModal() {
                    this.search = '';
                    this.modalOpen = true;
                    this.$nextTick(() => {
                        if (this.$refs.searchInput) {
                            this.$refs.searchInput.focus();
                        }
                    });
                },
                closeModal() {
                    this.modalOpen = false;
                },
                selectOrmawa(id, name) {
                    this.selectedId = id;
                    this.selectedName = name;
                    this.modalOpen = false;
                },
                rejectTaken() {
                    Swal.fire({
                        title: 'Penugasan Ditolak!',
                        text: 'Organisasi ini sudah memiliki Admin.',
                        icon: 'warning',
                        confirmButtonColor: '#4f46e5',
                        confirmButtonText: 'Tutup',
                        customClass: { popup: 'rounded-2xl', confirmButton: 'rounded-lg' }
                    });
                }
            }">
                <input type="hidden" name="ormawa_id" x-model="selectedId">
                <button type="button" 
                        @click="openModal()" 
                        class="w-full flex items-center justify-between px-4 py-3 bg-slate-50 hover:bg-slate-100/80 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-sm text-slate-700 text-left group cursor-pointer shadow-sm">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 transition-colors"
                             :class="selectedId ? 'bg-blue-100 text-blue-600' : 'bg-slate-200 text-slate-500'">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <span x-text="selectedName" class="truncate block" :class="selectedId ? 'text-slate-800 font-semibold' : 'text-slate-500 font-normal'"></span>
                            <span x-show="selectedId" class="text-[11px] text-blue-600 font-medium">Klik untuk mengganti ormawa</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 shrink-0 ml-2">
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-lg bg-white border border-slate-200 text-slate-600 group-hover:border-blue-300 group-hover:text-blue-600 transition-all shadow-2xs flex items-center gap-1">
                            <span>Pilih Ormawa</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/></svg>
                        </span>
                    </div>
                </button>

                {{-- Modal Popup di Tengah Layar --}}
                <template x-teleport="body">
                    <div x-show="modalOpen" 
                         x-transition:enter="transition ease-out duration-200" 
                         x-transition:enter-start="opacity-0" 
                         x-transition:enter-end="opacity-100" 
                         x-transition:leave="transition ease-in duration-150" 
                         x-transition:leave-start="opacity-100" 
                         x-transition:leave-end="opacity-0"
                         @keydown.escape.window="closeModal()"
                         class="fixed inset-0 z-[9999] flex items-center justify-center p-4 sm:p-6" 
                         style="display:none;">
                        
                        {{-- Backdrop --}}
                        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="closeModal()"></div>

                        {{-- Modal Content Card --}}
                        <div x-show="modalOpen" 
                             x-transition:enter="transition ease-out duration-200" 
                             x-transition:enter-start="opacity-0 scale-95 translate-y-4" 
                             x-transition:enter-end="opacity-100 scale-100 translate-y-0" 
                             x-transition:leave="transition ease-in duration-150" 
                             x-transition:leave-start="opacity-100 scale-100 translate-y-0" 
                             x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                             class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[85vh] flex flex-col overflow-hidden border border-slate-100">
                            
                            {{-- Header --}}
                            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between shrink-0 bg-gradient-to-r from-slate-50 to-white">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-slate-800 text-base">Pilih Organisasi Mahasiswa</h3>
                                        <p class="text-xs text-slate-500">Pilih ormawa yang akan dikelola oleh admin ini</p>
                                    </div>
                                </div>
                                <button type="button" @click="closeModal()" class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-700 flex items-center justify-center transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>

                            {{-- Search Box --}}
                            <div class="p-4 border-b border-slate-100 bg-white shrink-0">
                                <div class="relative">
                                    <input type="text" 
                                           x-model="search" 
                                           x-ref="searchInput" 
                                           placeholder="Ketik nama ormawa untuk mencari..."
                                           class="w-full pl-10 pr-9 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                    </div>
                                    <button type="button" x-show="search" @click="search = ''" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </div>
                            </div>

                            {{-- Ormawa List --}}
                            <div class="overflow-y-auto flex-1 p-4 space-y-2">
                                <div x-show="!search" 
                                     @click="selectOrmawa('', 'Pilih Ormawa...')" 
                                     class="px-4 py-3 rounded-xl border border-dashed border-slate-200 text-slate-500 hover:bg-slate-50 hover:text-slate-700 cursor-pointer flex items-center justify-between text-sm transition-all group">
                                    <span class="font-medium italic">-- Kosongkan Pilihan (Batal Pilih) --</span>
                                    <span class="text-xs text-slate-400 group-hover:text-slate-600">Reset</span>
                                </div>

                                @foreach($allOrmawas as $o)
                                    @php 
                                        $isTaken = in_array($o->id, $takenOrmawaIds); 
                                        $isCurrent = $o->id == $assignedOrmawaId;
                                    @endphp
                                    @if($isTaken && !$isCurrent)
                                        {{-- Ormawa Sudah Memiliki Admin --}}
                                        <div x-show="!search || '{{ strtolower(addslashes($o->nama)) }}'.includes(search.toLowerCase().trim())"
                                             @click="rejectTaken()"
                                             class="p-3.5 rounded-xl border border-amber-200/70 bg-amber-50/40 hover:bg-amber-50 cursor-pointer flex items-center justify-between gap-3 transition-all">
                                            <div class="flex items-center gap-3 min-w-0">
                                                <div class="w-9 h-9 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center font-bold text-xs shrink-0">
                                                    {{ strtoupper(substr($o->nama, 0, 2)) }}
                                                </div>
                                                <div class="min-w-0">
                                                    <h4 class="text-sm font-semibold text-slate-700 truncate">{{ $o->nama }}</h4>
                                                    <p class="text-xs text-slate-400 capitalize">{{ $o->tingkat ?? 'Ormawa' }}</p>
                                                </div>
                                            </div>
                                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700 shadow-2xs whitespace-nowrap shrink-0 flex items-center gap-1.5 border border-amber-200">
                                                <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                                Sudah Ada Admin
                                            </span>
                                        </div>
                                    @else
                                        {{-- Ormawa Tersedia / Saat Ini --}}
                                        <div x-show="!search || '{{ strtolower(addslashes($o->nama)) }}'.includes(search.toLowerCase().trim())"
                                             @click="selectOrmawa('{{ $o->id }}', '{{ addslashes($o->nama) }}')"
                                             class="p-3.5 rounded-xl border cursor-pointer flex items-center justify-between gap-3 transition-all"
                                             :class="selectedId == '{{ $o->id }}' ? 'bg-blue-50/90 border-blue-300 shadow-xs' : 'border-slate-200 hover:border-blue-300 hover:bg-blue-50/40 bg-white'">
                                            <div class="flex items-center gap-3 min-w-0">
                                                <div class="w-9 h-9 rounded-lg flex items-center justify-center font-bold text-xs shrink-0 transition-colors"
                                                     :class="selectedId == '{{ $o->id }}' ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-600'">
                                                    {{ strtoupper(substr($o->nama, 0, 2)) }}
                                                </div>
                                                <div class="min-w-0">
                                                    <h4 class="text-sm font-semibold truncate"
                                                        :class="selectedId == '{{ $o->id }}' ? 'text-blue-900' : 'text-slate-800'">{{ $o->nama }}</h4>
                                                    <p class="text-xs text-slate-400 capitalize">{{ $o->tingkat ?? 'Ormawa' }}</p>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-2 shrink-0">
                                                @if($isCurrent)
                                                    <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-indigo-100 text-indigo-700">Saat Ini</span>
                                                @endif
                                                <div x-show="selectedId == '{{ $o->id }}'" class="w-6 h-6 rounded-full bg-blue-600 text-white flex items-center justify-center shadow-xs">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                                </div>
                                                <span x-show="selectedId != '{{ $o->id }}'" class="text-xs font-semibold px-2.5 py-1 rounded-lg bg-slate-50 text-slate-600 border border-slate-200 group-hover:bg-blue-600 group-hover:text-white transition-all">Pilih</span>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>

                            {{-- Footer --}}
                            <div class="px-6 py-3.5 border-t border-slate-100 bg-slate-50 flex items-center justify-between shrink-0">
                                <span class="text-xs text-slate-500">Total {{ $allOrmawas->count() }} organisasi terdaftar</span>
                                <button type="button" @click="closeModal()" class="px-4 py-2 text-xs font-semibold text-slate-600 bg-white hover:bg-slate-100 border border-slate-200 rounded-xl transition-colors shadow-2xs">
                                    Tutup
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
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