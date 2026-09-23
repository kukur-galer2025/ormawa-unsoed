@extends("layouts.app")
@section("title","Input Nilai")@section("page-title","Scoring Pelamar")
@section("sidebar")@include("layouts.partials.sidebar-admin")@endsection
@section("content")
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
    <div>
        <p class="text-sm text-slate-500">Rekrutmen: <span class="font-semibold text-slate-700">{{ $recruitment->judul }}</span></p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('admin.recruitment.show', $recruitment) }}" class="px-4 py-2 bg-slate-100 text-slate-700 text-sm font-medium rounded-xl hover:bg-slate-200">← Kembali</a>
    </div>
</div>

<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-6">
    <div class="p-4 border-b border-slate-200 bg-slate-50 flex flex-col md:flex-row md:items-center justify-between gap-4" x-data="{ modalOpen: false, divisions: {{ $divisionsJson }}, selectedDivId: '{{ request('division_id', $division?->id) }}', selectedAppId: '{{ request('application_id') }}' }">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-amber-100 text-amber-600 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/></svg>
            </div>
            <div>
                <h3 class="font-bold text-slate-800">Input Nilai Kriteria</h3>
                <p class="text-xs text-slate-500">
                    @if($division && request('application_id') && $applications->isNotEmpty())
                        Menilai pelamar: <span class="font-bold">{{ $applications->first()->user->name }}</span> ({{ $division->nama }})
                    @else
                        Pilih divisi dan pelamar untuk mulai menilai
                    @endif
                </p>
            </div>
        </div>
        
        <button @click="modalOpen = true" class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl text-sm font-semibold hover:shadow-lg hover:from-blue-700 hover:to-indigo-700 transition-all shadow-md flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
            Pilih Divisi & Pelamar
        </button>

        <!-- AlpineJS Modal -->
        <div x-show="modalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" x-transition.opacity @click="modalOpen = false"></div>
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative transform overflow-visible rounded-2xl bg-white p-6 text-left shadow-xl transition-all w-full max-w-md" x-transition>
                    <div class="flex justify-between items-center mb-5">
                        <h3 class="text-lg font-bold text-slate-800">Pilih Divisi & Pelamar</h3>
                        <button @click="modalOpen = false" class="text-slate-400 hover:text-slate-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                    </div>
                    
                    <form method="GET" action="{{ route('admin.scoring.index', $recruitment) }}" class="space-y-5">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Divisi</label>
                            <select name="division_id" x-model="selectedDivId" @change="selectedAppId = ''" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none">
                                <option value="">-- Pilih Divisi --</option>
                                <template x-for="div in divisions" :key="div.id">
                                    <option :value="div.id" x-text="div.nama" :selected="div.id == selectedDivId"></option>
                                </template>
                            </select>
                        </div>
                        
                        <div x-show="selectedDivId" x-transition>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Pelamar</label>
                            
                            <!-- Custom Luxury Dropdown -->
                            <div class="relative" x-data="{ open: false }">
                                <div @click="open = !open" class="w-full px-4 py-2.5 border border-slate-300 rounded-lg bg-white cursor-pointer flex justify-between items-center hover:border-blue-400 transition-colors" :class="open ? 'ring-2 ring-blue-500/20 border-blue-500' : ''">
                                    <template x-if="!selectedAppId">
                                        <span class="text-slate-500 text-sm">-- Pilih Pelamar --</span>
                                    </template>
                                    <template x-if="selectedAppId">
                                        <div class="flex flex-col">
                                            <span class="font-bold text-slate-800 text-sm" x-text="(divisions.find(d => d.id == selectedDivId)?.applications || []).find(a => a.id == selectedAppId)?.name"></span>
                                            <span class="text-xs text-slate-500" x-text="(divisions.find(d => d.id == selectedDivId)?.applications || []).find(a => a.id == selectedAppId)?.nim + ' • ' + (divisions.find(d => d.id == selectedDivId)?.applications || []).find(a => a.id == selectedAppId)?.jurusan"></span>
                                        </div>
                                    </template>
                                    <svg class="w-4 h-4 text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                                
                                <div x-show="open" @click.away="open = false" x-transition class="absolute z-10 w-full mt-2 bg-white border border-slate-200 rounded-xl shadow-xl max-h-60 overflow-y-auto" style="display: none;">
                                    <template x-for="app in (divisions.find(d => d.id == selectedDivId)?.applications || [])" :key="app.id">
                                        <div @click="selectedAppId = app.id; open = false" class="px-4 py-3 hover:bg-slate-50 cursor-pointer border-b border-slate-100 last:border-0 flex justify-between items-center transition-colors">
                                            <div>
                                                <p class="font-bold text-slate-800 text-sm" x-text="app.name"></p>
                                                <p class="text-xs text-slate-500 mt-0.5" x-text="app.nim + ' • ' + app.jurusan"></p>
                                            </div>
                                            <span class="px-2.5 py-1 rounded-lg text-[10px] font-extrabold uppercase tracking-wide" 
                                                  :class="app.status_text === 'Belum Dinilai' ? 'bg-amber-100 text-amber-700' : 'bg-green-100 text-green-700'"
                                                  x-text="app.status_text"></span>
                                        </div>
                                    </template>
                                    <template x-if="(divisions.find(d => d.id == selectedDivId)?.applications || []).length === 0">
                                        <div class="px-4 py-6 text-center text-sm text-slate-500">
                                            Belum ada pelamar di divisi ini.
                                        </div>
                                    </template>
                                </div>
                                <input type="hidden" name="application_id" :value="selectedAppId">
                            </div>
                        </div>
                        
                        <div class="mt-6 flex justify-end gap-3 pt-4 border-t border-slate-100">
                            <button type="button" @click="modalOpen = false" class="px-4 py-2 bg-slate-100 text-slate-700 text-sm font-semibold rounded-xl hover:bg-slate-200 transition-colors">Batal</button>
                            <button type="submit" :disabled="!selectedDivId || !selectedAppId" class="px-5 py-2 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">Lanjutkan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if(!$division || !request('application_id'))
        <div class="p-12 text-center text-slate-500 flex flex-col items-center">
            <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            </div>
            <h4 class="font-bold text-slate-700 text-lg mb-1">Pilih Pelamar</h4>
            <p class="text-sm">Klik tombol <span class="font-semibold text-blue-600">Pilih Divisi & Pelamar</span> di kanan atas untuk mulai menilai.</p>
        </div>
    @elseif($applications->isEmpty())
        <div class="p-12 text-center text-slate-500 flex flex-col items-center">
            <svg class="w-12 h-12 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
            <p>Data pelamar tidak ditemukan.</p>
        </div>
    @elseif($aspects->isEmpty())
        <div class="p-8 text-center text-amber-600 bg-amber-50 rounded-xl m-6 border border-amber-200">Belum ada aspek yang ditentukan untuk divisi ini. <a href="{{ route('admin.aspect.index', $recruitment) }}" class="font-bold underline hover:text-amber-800">Atur Aspek & Kriteria</a></div>
    @else
    @php
        $isReadOnly = $recruitment->status !== 'ditutup' || ($division && $division->is_finalized);
    @endphp
    <div class="overflow-x-auto p-6">
        <div class="space-y-8">
            @foreach($applications as $app)
            <div class="border border-slate-200 rounded-xl overflow-hidden shadow-sm">
                <div class="bg-slate-50 px-5 py-4 border-b border-slate-200 flex justify-between items-center">
                    <div>
                        <p class="font-bold text-slate-800 text-lg">{{ $app->user->name }}</p>
                        <p class="text-sm text-slate-500">{{ $app->user->mahasiswaProfile->nim ?? '-' }}</p>
                    </div>
                    <span class="px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-semibold text-slate-600">
                        {{ $app->status === 'pending' ? 'Belum Dinilai' : 'Sudah Dinilai' }}
                    </span>
                </div>
                
                <form method="POST" action="{{ route('admin.scoring.store', [$recruitment, $app]) }}" class="p-5">
                    @csrf
                    
                    <div class="space-y-6 mb-6">
                        @foreach($aspects as $aspect)
                            <div class="border border-slate-100 rounded-xl p-4 bg-white shadow-sm">
                                <h4 class="font-bold text-slate-700 border-b border-slate-100 pb-2 mb-4 flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                                    Aspek: {{ $aspect->nama }} 
                                    <span class="text-xs font-normal text-slate-500 ml-2">(Bobot {{ rtrim(rtrim(number_format($aspect->bobot, 2), '0'), '.') }}%)</span>
                                </h4>
                                
                                @if($aspect->criteria->isEmpty())
                                    <p class="text-sm text-slate-400 italic">Belum ada kriteria di aspek ini.</p>
                                @else
                                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                                        @foreach($aspect->criteria as $c)
                                            @php
                                                $score = $app->scores->firstWhere('criteria_id', $c->id);
                                                $actualValue = $score ? $score->actual_value : null;
                                            @endphp
                                            <div>
                                                <label class="block text-sm font-semibold text-slate-700 mb-1 flex justify-between" title="{{ $c->keterangan }}">
                                                    <span>{{ $c->nama_kriteria }}</span>
                                                    <span class="px-1.5 py-0.5 rounded text-[10px] uppercase {{ $c->tipe == 'core' ? 'bg-blue-100 text-blue-700' : 'bg-amber-100 text-amber-700' }}">{{ $c->tipe == 'core' ? 'CF' : 'SF' }}</span>
                                                </label>
                                                <select name="scores[{{ $c->id }}]" {{ $isReadOnly ? 'disabled' : 'required' }} class="w-full px-3 py-2 border {{ $actualValue ? 'border-slate-300 bg-slate-50 text-slate-800 font-medium' : 'border-blue-300 bg-blue-50/30' }} rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 {{ $isReadOnly ? 'opacity-70 cursor-not-allowed' : '' }}">
                                                    <option value="">- Pilih Nilai -</option>
                                                    @foreach($c->valueLabels as $vl)
                                                        <option value="{{ $vl->value }}" {{ $actualValue == $vl->value ? 'selected' : '' }}>
                                                            {{ $vl->value }} - {{ $vl->label }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                    
                    @if(!$isReadOnly)
                    <div class="flex justify-end pt-3">
                        <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-sm font-semibold rounded-xl shadow-md hover:shadow-lg transition-all">Simpan Nilai Pelamar</button>
                    </div>
                    @else
                    <div class="mt-4 p-3 bg-slate-100 text-slate-500 text-center rounded-xl text-sm font-medium">
                        Form terkunci. Nilai tidak dapat diubah lagi.
                    </div>
                    @endif
                </form>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection