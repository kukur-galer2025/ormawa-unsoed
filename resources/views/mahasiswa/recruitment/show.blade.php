@extends("layouts.app")
@section("title",$ormawa->nama)
@section("sidebar")@include("layouts.partials.sidebar-mahasiswa")@endsection
@section("content")
<div class="mb-6"><a href="{{ route('mahasiswa.recruitment.index') }}" class="px-4 py-2 bg-slate-100 text-slate-700 text-sm font-medium rounded-xl hover:bg-slate-200">← Kembali ke Katalog</a></div>

<div x-data="{ mediaModalOpen: false, modalTitle: '', modalImage: '', modalDesc: '', modalYear: '' }">

<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mb-8">
    <div class="h-48 bg-gradient-to-r from-blue-600 to-indigo-700 relative">
        <div class="absolute inset-0 bg-black/20"></div>
        <div class="absolute bottom-6 left-6 flex items-end gap-5">
            <div class="w-24 h-24 rounded-2xl bg-white p-2 shadow-xl">
                <img src="{{ $ormawa->logo ? Storage::url($ormawa->logo) : asset('images/default-logo.png') }}" class="w-full h-full object-contain rounded-xl">
            </div>
            <div class="text-white mb-2">
                <h1 class="text-3xl font-black mb-1">{{ $ormawa->nama }}</h1>
                <p class="text-blue-100 font-medium text-sm">{{ $ormawa->fakultas }} • {{ $ormawa->jurusan }}</p>
            </div>
        </div>
    </div>
    
    <div class="p-6">
        <p class="text-slate-600 mb-6 leading-relaxed">{{ $ormawa->deskripsi }}</p>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @if($ormawa->prestasis->count() > 0)
            <div>
                <h3 class="font-bold text-slate-800 mb-3 flex items-center gap-2">
                    <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                    Prestasi Unggulan
                </h3>
                <ul class="space-y-3">
                    @foreach($ormawa->prestasis->take(3) as $prestasi)
                    <li @click="mediaModalOpen = true; modalTitle = '{{ addslashes($prestasi->judul) }}'; modalImage = '{{ $prestasi->foto ? Storage::url($prestasi->foto) : '' }}'; modalDesc = '{{ addslashes(str_replace(["\r", "\n"], ' ', $prestasi->deskripsi)) }}'; modalYear = '{{ $prestasi->tahun }}'" class="flex items-start gap-3 p-3 bg-slate-50 rounded-xl border border-slate-100 cursor-pointer hover:bg-slate-100 transition-colors group">
                        @if($prestasi->foto)
                            <div class="w-16 h-16 shrink-0 rounded-lg overflow-hidden bg-slate-200">
                                <img src="{{ Storage::url($prestasi->foto) }}" alt="{{ $prestasi->judul }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                            </div>
                        @endif
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="px-2 py-0.5 bg-amber-100 text-amber-700 text-[10px] font-bold rounded">{{ $prestasi->tahun }}</span>
                                <p class="font-semibold text-slate-800 text-sm leading-tight group-hover:text-amber-600 transition-colors">{{ $prestasi->judul }}</p>
                            </div>
                            @if($prestasi->deskripsi)
                                <p class="text-xs text-slate-500 line-clamp-2">{{ $prestasi->deskripsi }}</p>
                            @endif
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif

            @if($ormawa->programKerjas->count() > 0)
            <div>
                <h3 class="font-bold text-slate-800 mb-3 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    Program Kerja
                </h3>
                <div class="grid grid-cols-1 gap-3">
                    @foreach($ormawa->programKerjas as $proker)
                    <div @click="mediaModalOpen = true; modalTitle = '{{ addslashes($proker->nama) }}'; modalImage = '{{ $proker->foto ? Storage::url($proker->foto) : '' }}'; modalDesc = '{{ addslashes(str_replace(["\r", "\n"], ' ', $proker->deskripsi)) }}'; modalYear = ''" class="flex items-start gap-3 p-3 bg-slate-50 rounded-xl border border-slate-100 cursor-pointer hover:bg-slate-100 transition-colors group">
                        @if($proker->foto)
                            <div class="w-16 h-16 shrink-0 rounded-lg overflow-hidden bg-slate-200">
                                <img src="{{ Storage::url($proker->foto) }}" alt="{{ $proker->nama }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                            </div>
                        @else
                            <div class="w-16 h-16 shrink-0 rounded-lg bg-blue-100 flex items-center justify-center text-blue-500">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                            </div>
                        @endif
                        <div class="flex-1">
                            <p class="font-semibold text-slate-800 text-sm group-hover:text-blue-600 transition-colors">{{ $proker->nama }}</p>
                            @if($proker->deskripsi)
                                <p class="text-xs text-slate-500 line-clamp-2 mt-0.5">{{ $proker->deskripsi }}</p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<h2 class="text-xl font-bold text-slate-800 mb-4">Rekrutmen Dibuka</h2>

@if($ormawa->recruitments->isEmpty())
    <div class="bg-white rounded-2xl border border-slate-200 p-8 text-center shadow-sm">
        <p class="text-slate-500">Saat ini tidak ada rekrutmen yang sedang dibuka oleh ormawa ini.</p>
    </div>
@else
    <div class="space-y-6" x-data="{ modalOpen: false, selectedRecruitment: null, selectedDivision: null }">
        @foreach($ormawa->recruitments as $rec)
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100">
                <div class="flex justify-between items-start mb-2">
                    <h3 class="text-lg font-bold text-slate-800">{{ $rec->judul }}</h3>
                    @if($rec->isOpen())
                        <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full border border-green-200 animate-pulse">BUKA</span>
                    @elseif(now() < $rec->tanggal_buka)
                        <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-bold rounded-full border border-blue-200">SEGERA</span>
                    @else
                        <span class="px-3 py-1 bg-red-100 text-red-700 text-xs font-bold rounded-full border border-red-200">DITUTUP</span>
                    @endif
                </div>
                <p class="text-sm text-slate-600 mb-4">{{ $rec->deskripsi }}</p>
                <div class="flex items-center gap-4 text-xs font-medium text-slate-500">
                    <span class="flex items-center gap-1"><svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg> {{ $rec->tanggal_buka->format('d M') }} - {{ $rec->tanggal_tutup->format('d M Y') }}</span>
                    <span class="flex items-center gap-1"><svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg> {{ $rec->divisions->sum('applications_count') }} Pendaftar Keseluruhan</span>
                </div>
            </div>

            <div class="bg-slate-50 p-6">
                <h4 class="text-sm font-bold text-slate-800 mb-4">Pilih Divisi untuk Melamar:</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($rec->divisions as $div)
                        @php
                            $isApplied = in_array($div->id, $appliedDivisionIds);
                            $isFull = $div->kuota > 0 && $div->applications()->where('status', 'diterima')->count() >= $div->kuota;
                        @endphp
                        <div class="bg-white rounded-xl border {{ $isApplied ? 'border-green-300 ring-1 ring-green-300' : 'border-slate-200' }} p-4 shadow-sm flex flex-col h-full">
                            <div class="flex-grow">
                                <div class="flex justify-between items-start mb-2">
                                    <h5 class="font-bold text-slate-800">{{ $div->nama }}</h5>
                                    @if($isApplied)
                                        <span class="px-2 py-0.5 bg-green-100 text-green-700 text-[10px] font-bold rounded">TERDAFTAR</span>
                                    @endif
                                </div>
                                <p class="text-xs text-slate-500 mb-3">{{ $div->deskripsi ?: 'Tidak ada deskripsi' }}</p>
                                <div class="flex gap-2 text-xs mb-4">
                                    <span class="px-2 py-1 bg-slate-100 text-slate-600 rounded">Kuota: {{ $div->kuota }}</span>
                                    <span class="px-2 py-1 bg-slate-100 text-slate-600 rounded">{{ $div->applications_count }} Pelamar</span>
                                </div>
                            </div>
                            
                            @if($isApplied)
                                <button disabled class="w-full py-2 bg-slate-100 text-green-600 text-xs font-bold rounded-lg cursor-not-allowed border border-green-200">
                                    Sudah Melamar
                                </button>
                            @elseif($isFull)
                                <button disabled class="w-full py-2 bg-red-50 text-red-500 text-xs font-bold rounded-lg cursor-not-allowed">
                                    Kuota Penuh
                                </button>
                            @elseif(!$rec->isOpen())
                                <button disabled class="w-full py-2 bg-slate-100 text-slate-500 text-xs font-bold rounded-lg cursor-not-allowed border border-slate-200">
                                    @if(now() < $rec->tanggal_buka) Belum Dibuka @else Ditutup @endif
                                </button>
                            @else
                                <button @click="modalOpen = true; selectedRecruitment = {{ $rec->id }}; selectedDivision = {{ $div->id }}" class="w-full py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-lg transition-colors shadow-sm">
                                    Lamar Divisi Ini
                                </button>
                            @endif
                        </div>
                    @endforeach
                </div>
                
                @if($rec->persyaratan)
                <div class="mt-6 pt-6 border-t border-slate-200">
                    <h4 class="text-sm font-bold text-slate-800 mb-2">Persyaratan Umum</h4>
                    <div class="text-sm text-slate-600 whitespace-pre-line bg-white p-4 rounded-xl border border-slate-100">{{ $rec->persyaratan }}</div>
                </div>
                @endif
            </div>
        </div>
        @endforeach

        <!-- Modal Pendaftaran -->
        <div x-show="modalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
                <div x-show="modalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity bg-slate-900/50 backdrop-blur-sm" @click="modalOpen = false"></div>

                <div x-show="modalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative inline-block w-full max-w-lg p-6 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl">
                    <div class="flex justify-between items-center border-b border-slate-100 pb-4 mb-4">
                        <h3 class="text-lg font-bold text-slate-800">Form Pendaftaran</h3>
                        <button @click="modalOpen = false" class="text-slate-400 hover:text-slate-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                    
                    <form :action="'{{ url('mahasiswa/recruitment') }}/' + selectedRecruitment + '/apply'" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <input type="hidden" name="recruitment_division_id" :value="selectedDivision">
                        
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Motivasi Bergabung *</label>
                            <textarea name="motivasi" required rows="4" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-sm" placeholder="Ceritakan mengapa Anda ingin bergabung..."></textarea>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Berkas Pendukung (CV/Portofolio)</label>
                            <input type="file" name="berkas_pendukung" accept=".pdf,.doc,.docx" class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            <p class="text-xs text-slate-500 mt-1">Format PDF/DOC/DOCX, maksimal 5MB. Opsional.</p>
                        </div>
                        
                        <div class="pt-2">
                            <button type="submit" class="w-full px-4 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold rounded-xl shadow-lg shadow-blue-500/30 hover:shadow-xl transition-all">Kirim Lamaran</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endif

<!-- Modal Prestasi/Proker Viewer -->
<div x-show="mediaModalOpen" class="fixed inset-0 z-[60] overflow-y-auto" style="display: none;">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <div x-show="mediaModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity bg-slate-900/80 backdrop-blur-sm" @click="mediaModalOpen = false"></div>

        <div x-show="mediaModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="relative inline-block w-full max-w-2xl overflow-hidden text-left align-middle transition-all transform bg-white shadow-2xl rounded-2xl">
            <button @click="mediaModalOpen = false" class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center bg-black/10 hover:bg-black/20 text-slate-800 rounded-full transition-colors z-10">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
            
            <div class="flex flex-col max-h-[85vh]">
                <div x-show="modalImage" class="w-full bg-slate-100 border-b border-slate-200 flex items-center justify-center relative overflow-hidden" style="min-h: 200px; max-h: 400px;">
                    <img :src="modalImage" class="w-full h-full object-cover">
                </div>
                
                <div class="p-6 overflow-y-auto">
                    <div class="flex items-center gap-2 mb-2">
                        <span x-show="modalYear" x-text="modalYear" class="px-2 py-1 bg-amber-100 text-amber-700 text-xs font-bold rounded"></span>
                        <h3 class="text-xl font-bold text-slate-800" x-text="modalTitle"></h3>
                    </div>
                    <p class="text-slate-600 leading-relaxed whitespace-pre-line mt-4" x-text="modalDesc"></p>
                </div>
            </div>
        </div>
    </div>
</div>

</div>
@endsection