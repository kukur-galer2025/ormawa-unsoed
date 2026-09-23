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
    <div class="p-4 border-b border-slate-200 bg-slate-50 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-amber-100 text-amber-600 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/></svg>
            </div>
            <div>
                <h3 class="font-bold text-slate-800">Input Nilai Kriteria</h3>
                <p class="text-xs text-slate-500">Pilih divisi untuk menginput nilai pelamar</p>
            </div>
        </div>
        <form method="GET" action="{{ route('admin.scoring.index', $recruitment) }}" class="flex flex-col md:flex-row md:items-center gap-4">
            <div class="flex items-center gap-2">
                <span class="text-sm font-medium text-slate-600">Divisi:</span>
                <select name="division_id" onchange="this.form.submit()" class="px-3 py-1.5 border border-slate-300 rounded-lg text-sm bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 min-w-[150px]">
                    <option value="">-- Pilih Divisi --</option>
                    @foreach($recruitment->divisions as $div)
                        <option value="{{ $div->id }}" {{ ($division && $division->id == $div->id) ? 'selected' : '' }}>{{ $div->nama }}</option>
                    @endforeach
                </select>
            </div>
            
            @if($division && $allDivisionApplications->isNotEmpty())
            <div class="flex items-center gap-2 md:border-l md:border-slate-200 md:pl-4">
                <span class="text-sm font-medium text-slate-600">Pelamar:</span>
                <select name="application_id" onchange="this.form.submit()" class="px-3 py-1.5 border border-slate-300 rounded-lg text-sm bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 min-w-[200px]">
                    <option value="">Semua Pelamar ({{ $allDivisionApplications->count() }})</option>
                    @foreach($allDivisionApplications as $appDropdown)
                        <option value="{{ $appDropdown->id }}" {{ request('application_id') == $appDropdown->id ? 'selected' : '' }}>
                            {{ $appDropdown->user->name }} {{ $appDropdown->status === 'pending' ? '(Belum Dinilai)' : '(Selesai)' }}
                        </option>
                    @endforeach
                </select>
            </div>
            @endif
        </form>
    </div>

    @if(!$division)
        <div class="p-12 text-center text-slate-500 flex flex-col items-center">
            <svg class="w-12 h-12 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
            <p>Silakan pilih divisi terlebih dahulu.</p>
        </div>
    @elseif($applications->isEmpty())
        <div class="p-8 text-center text-slate-500">Belum ada pelamar di divisi ini.</div>
    @elseif($aspects->isEmpty())
        <div class="p-8 text-center text-amber-600 bg-amber-50">Belum ada aspek yang ditentukan untuk divisi ini. <a href="{{ route('admin.aspect.index', $recruitment) }}" class="font-bold underline">Atur Aspek & Kriteria</a></div>
    @else
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
                                                <select name="scores[{{ $c->id }}]" required class="w-full px-3 py-2 border {{ $actualValue ? 'border-slate-300 bg-slate-50 text-slate-800 font-medium' : 'border-blue-300 bg-blue-50/30' }} rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
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
                    
                    <div class="flex justify-end pt-3">
                        <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-sm font-semibold rounded-xl shadow-md hover:shadow-lg transition-all">Simpan Nilai Pelamar</button>
                    </div>
                </form>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection