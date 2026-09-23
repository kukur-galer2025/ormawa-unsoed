@extends("layouts.app")
@section("title","Detail Pelamar")@section("page-title","Detail Pelamar: " . $application->user->name)
@section("sidebar")@include("layouts.partials.sidebar-admin")@endsection
@section("content")
<div class="mb-6 flex gap-2">
    <a href="{{ route('admin.applicants.index', $recruitment) }}" class="px-4 py-2 bg-slate-100 text-slate-700 text-sm font-medium rounded-xl hover:bg-slate-200">← Kembali ke Daftar</a>
</div>

<div class="space-y-6">
    {{-- Baris 1: Informasi Lamaran & Profil --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
            <h3 class="text-sm font-bold text-slate-800 border-b border-slate-100 pb-3 mb-4">Informasi Lamaran</h3>
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div><span class="block text-xs font-medium text-slate-500 mb-1">Status Saat Ini</span>
                    <span class="px-2.5 py-1 rounded-full text-xs font-medium 
                            {{ $application->status === 'pending' ? 'bg-amber-100 text-amber-700' : 
                               ($application->status === 'diproses' ? 'bg-blue-100 text-blue-700' : 
                               ($application->status === 'diterima' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700')) }}">
                            {{ $application->status === 'pending' ? 'Terkirim' : ucfirst($application->status) }}
                    </span>
                </div>
                <div><span class="block text-xs font-medium text-slate-500 mb-1">Tanggal Melamar</span><p class="text-sm text-slate-800 font-medium">{{ $application->created_at->format('d F Y, H:i') }}</p></div>
                <div class="col-span-2"><span class="block text-xs font-medium text-slate-500 mb-1">Divisi yang Dilamar</span><p class="text-sm text-slate-800 font-bold px-2 py-1 bg-slate-100 rounded inline-block">{{ $application->division->nama }}</p></div>
            </div>
            
            <div class="mt-4"><span class="block text-xs font-medium text-slate-500 mb-2">Motivasi</span>
                <div class="p-4 bg-slate-50 rounded-xl text-sm text-slate-700 leading-relaxed border border-slate-100 whitespace-pre-line">{{ $application->motivasi }}</div>
            </div>
            
            @if($application->berkas_pendukung)
            <div class="mt-4"><span class="block text-xs font-medium text-slate-500 mb-2">Berkas Persyaratan</span>
                <a href="{{ Storage::url($application->berkas_pendukung) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-700 rounded-xl text-sm font-medium hover:bg-blue-100 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg> Lihat Berkas
                </a>
            </div>
            @endif
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 flex flex-col">
            <h3 class="text-sm font-bold text-slate-800 border-b border-slate-100 pb-3 mb-4">Profil Mahasiswa</h3>
            <div class="space-y-4 text-sm flex-1">
                <div><span class="block text-xs font-medium text-slate-500 mb-1">Nama Lengkap</span><p class="font-bold text-slate-800 text-base">{{ $application->user->name }}</p></div>
                <div><span class="block text-xs font-medium text-slate-500 mb-1">NIM</span><p class="font-medium text-slate-800">{{ $application->user->mahasiswaProfile->nim ?? '-' }}</p></div>
                <div><span class="block text-xs font-medium text-slate-500 mb-1">Fakultas / Jurusan</span><p class="font-medium text-slate-800">{{ $application->user->mahasiswaProfile->fakultasRel->nama_fakultas ?? '-' }} &mdash; {{ $application->user->mahasiswaProfile->jurusanRel->nama_jurusan ?? '-' }}</p></div>
                <div><span class="block text-xs font-medium text-slate-500 mb-1">Angkatan</span><p class="font-medium text-slate-800">{{ $application->user->mahasiswaProfile->angkatan ?? '-' }}</p></div>
            </div>
        </div>
    </div>

    {{-- Baris 2: Profile Matching --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
        <h3 class="text-sm font-bold text-slate-800 border-b border-slate-100 pb-3 mb-4">Nilai Profile Matching</h3>
        @if($application->profileMatchingResult)
            <div class="mb-4">
                <div class="p-4 bg-green-50 rounded-xl border border-green-100 text-center max-w-sm mx-auto">
                    <p class="text-xs text-green-600 font-semibold uppercase mb-1">Total Score Final</p>
                    <p class="text-3xl font-black text-green-800">{{ rtrim(rtrim(number_format($application->profileMatchingResult->total_score, 4), '0'), '.') }}</p>
                </div>
            </div>
            
            @if($application->profileMatchingResult->detail_per_aspek)
                <div class="mt-8">
                    <h4 class="text-sm font-black text-slate-800 uppercase tracking-wider mb-4 border-b-2 border-indigo-100 pb-2 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        Jejak Perhitungan (Trace) & Penjelasan Lengkap
                    </h4>
                    
                    {{-- Panduan & Referensi Konversi --}}
                    <div class="bg-indigo-50/50 rounded-2xl p-5 border border-indigo-100 mb-6 text-sm text-slate-700 space-y-4 shadow-sm">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-indigo-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <div>
                                <h5 class="font-bold text-indigo-900 mb-1">Panduan Membaca Profile Matching</h5>
                                <p class="leading-relaxed">Profile Matching menghitung tingkat kecocokan pelamar dengan kriteria ideal yang dicari. Terdapat dua jenis kriteria: <b>Core Factor (CF)</b> yang paling utama/wajib, dan <b>Secondary Factor (SF)</b> sebagai pendukung.</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pl-8">
                            <div>
                                <h6 class="font-bold text-slate-800 mb-2 border-b border-indigo-200 pb-1">1. Perhitungan Selisih (Gap)</h6>
                                <p class="text-xs mb-2">Selisih/Gap dicari dengan mengurangkan Nilai Aktual (dari pelamar) dengan Nilai Target (profil ideal).</p>
                                <div class="bg-white px-3 py-2 rounded-lg border border-slate-200 font-mono text-xs font-semibold text-center text-indigo-700">Gap = Nilai Aktual - Target</div>
                            </div>
                            <div>
                                <h6 class="font-bold text-slate-800 mb-2 border-b border-indigo-200 pb-1">2. Konversi Bobot Nilai Gap</h6>
                                <p class="text-xs mb-2">Setiap nilai Gap dikonversi menjadi Bobot berdasarkan aturan pemetaan berikut:</p>
                                <div class="bg-white rounded-lg border border-slate-200 overflow-hidden text-xs">
                                    <table class="w-full text-center">
                                        <thead class="bg-slate-50 border-b border-slate-200 text-slate-500">
                                            <tr>
                                                <th class="py-1 px-2 border-r border-slate-200">Gap</th>
                                                <th class="py-1 px-2 border-r border-slate-200">Bobot</th>
                                                <th class="py-1 px-2 border-r border-slate-200">Gap</th>
                                                <th class="py-1 px-2">Bobot</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="py-1 px-2 border-r border-slate-100 font-medium">0</td><td class="py-1 px-2 border-r border-slate-200 font-bold text-blue-600">5.0</td>
                                                <td class="py-1 px-2 border-r border-slate-100 bg-slate-50 text-slate-400" colspan="2"><i>(Kecocokan 100%)</i></td>
                                            </tr>
                                            <tr>
                                                <td class="py-1 px-2 border-r border-slate-100 border-t border-slate-100">1</td><td class="py-1 px-2 border-r border-slate-200 border-t border-slate-100 font-bold text-blue-600">4.5</td>
                                                <td class="py-1 px-2 border-r border-slate-100 border-t border-slate-100">-1</td><td class="py-1 px-2 border-t border-slate-100 font-bold text-blue-600">4.0</td>
                                            </tr>
                                            <tr>
                                                <td class="py-1 px-2 border-r border-slate-100 border-t border-slate-100">2</td><td class="py-1 px-2 border-r border-slate-200 border-t border-slate-100 font-bold text-blue-600">3.5</td>
                                                <td class="py-1 px-2 border-r border-slate-100 border-t border-slate-100">-2</td><td class="py-1 px-2 border-t border-slate-100 font-bold text-blue-600">3.0</td>
                                            </tr>
                                            <tr>
                                                <td class="py-1 px-2 border-r border-slate-100 border-t border-slate-100">3</td><td class="py-1 px-2 border-r border-slate-200 border-t border-slate-100 font-bold text-blue-600">2.5</td>
                                                <td class="py-1 px-2 border-r border-slate-100 border-t border-slate-100">-3</td><td class="py-1 px-2 border-t border-slate-100 font-bold text-blue-600">2.0</td>
                                            </tr>
                                            <tr>
                                                <td class="py-1 px-2 border-r border-slate-100 border-t border-slate-100">4</td><td class="py-1 px-2 border-r border-slate-200 border-t border-slate-100 font-bold text-blue-600">1.5</td>
                                                <td class="py-1 px-2 border-r border-slate-100 border-t border-slate-100">-4</td><td class="py-1 px-2 border-t border-slate-100 font-bold text-blue-600">1.0</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="space-y-8">
                        @foreach($application->profileMatchingResult->detail_per_aspek as $aspekData)
                            @php
                                $aspekModel = $application->division->aspects->where('nama', $aspekData['nama'])->first();
                                $aspekScores = $aspekModel ? $application->scores->whereIn('criteria_id', $aspekModel->criteria->pluck('id')) : collect();
                                
                                $coreScores = $aspekScores->filter(fn($s) => $s->criteria->tipe == 'core');
                                $secondaryScores = $aspekScores->filter(fn($s) => $s->criteria->tipe == 'secondary');
                                
                                $sumCore = $coreScores->sum('bobot_gap');
                                $countCore = $coreScores->count();
                                
                                $sumSecondary = $secondaryScores->sum('bobot_gap');
                                $countSecondary = $secondaryScores->count();
                            @endphp
                            
                            <div class="border border-slate-200 rounded-xl overflow-hidden shadow-sm bg-white">
                                <div class="bg-slate-50 px-4 py-3 border-b border-slate-200 flex justify-between items-center">
                                    <h5 class="font-bold text-slate-800 flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                                        Aspek: {{ $aspekData['nama'] }} 
                                        <span class="text-xs font-normal text-slate-500 ml-1">(Bobot Aspek: {{ rtrim(rtrim(number_format($aspekData['bobot'], 2), '0'), '.') }}%)</span>
                                    </h5>
                                    <div class="text-xs font-mono bg-white px-2 py-1 rounded border border-slate-200 text-slate-600 shadow-sm">
                                        Rasio: CF {{ $aspekData['cf_percentage'] }}% | SF {{ $aspekData['sf_percentage'] }}%
                                    </div>
                                </div>
                                
                                <div class="p-0 overflow-x-auto">
                                    <table class="w-full text-xs text-left">
                                        <thead class="bg-slate-100/50 border-b border-slate-100 text-slate-600 uppercase">
                                            <tr>
                                                <th class="px-4 py-2">Kriteria</th>
                                                <th class="px-4 py-2 text-center">Tipe</th>
                                                <th class="px-4 py-2 text-center" title="Target Value (Ideal)">Target (T)</th>
                                                <th class="px-4 py-2 text-center" title="Nilai Aktual Pelamar">Aktual (A)</th>
                                                <th class="px-4 py-2 text-center text-red-500" title="Gap = Aktual - Target">Gap (A-T)</th>
                                                <th class="px-4 py-2 text-center font-bold text-blue-600" title="Konversi Nilai Bobot Gap">Bobot Gap</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-50">
                                            @foreach($aspekScores as $score)
                                                <tr class="hover:bg-slate-50/50">
                                                    <td class="px-4 py-2 font-medium text-slate-700">{{ $score->criteria->nama_kriteria }}</td>
                                                    <td class="px-4 py-2 text-center"><span class="px-1.5 py-0.5 rounded font-medium {{ $score->criteria->tipe == 'core' ? 'bg-blue-100 text-blue-700' : 'bg-amber-100 text-amber-700' }}">{{ $score->criteria->tipe == 'core' ? 'Core Factor (CF)' : 'Secondary Factor (SF)' }}</span></td>
                                                    <td class="px-4 py-2 text-center text-slate-500">{{ $score->criteria->target_value }}</td>
                                                    <td class="px-4 py-2 text-center font-bold text-slate-800">{{ $score->actual_value ?? '-' }}</td>
                                                    <td class="px-4 py-2 text-center font-medium {{ $score->gap < 0 ? 'text-red-500' : ($score->gap > 0 ? 'text-green-600' : 'text-slate-400') }}">{{ $score->gap ?? '-' }}</td>
                                                    <td class="px-4 py-2 text-center font-bold text-blue-600 bg-blue-50/30">{{ $score->bobot_gap ?? '-' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                
                                <div class="p-4 border-t border-slate-100 bg-slate-50/50 space-y-4">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        {{-- NCF Breakdown --}}
                                        <div class="bg-white p-3 rounded-xl border border-slate-200">
                                            <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">1. Nilai Core Factor (NCF)</p>
                                            <div class="text-xs font-mono text-slate-600 bg-slate-50 p-2 rounded mb-2 overflow-x-auto">
                                                NCF = (Σ Bobot Gap Core) / Jumlah Kriteria Core
                                            </div>
                                            <div class="text-sm font-medium text-slate-700">
                                                @if($countCore > 0)
                                                    NCF = ({{ $coreScores->pluck('bobot_gap')->implode(' + ') }}) / {{ $countCore }}<br>
                                                    NCF = {{ $sumCore }} / {{ $countCore }} = <span class="font-bold text-blue-700">{{ number_format($aspekData['ncf'], 2) }}</span>
                                                @else
                                                    Tidak ada kriteria core. NCF = 0.
                                                @endif
                                            </div>
                                        </div>
                                        
                                        {{-- NSF Breakdown --}}
                                        <div class="bg-white p-3 rounded-xl border border-slate-200">
                                            <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">2. Nilai Secondary Factor (NSF)</p>
                                            <div class="text-xs font-mono text-slate-600 bg-slate-50 p-2 rounded mb-2 overflow-x-auto">
                                                NSF = (Σ Bobot Gap Sec) / Jumlah Kriteria Sec
                                            </div>
                                            <div class="text-sm font-medium text-slate-700">
                                                @if($countSecondary > 0)
                                                    NSF = ({{ $secondaryScores->pluck('bobot_gap')->implode(' + ') }}) / {{ $countSecondary }}<br>
                                                    NSF = {{ $sumSecondary }} / {{ $countSecondary }} = <span class="font-bold text-amber-600">{{ number_format($aspekData['nsf'], 2) }}</span>
                                                @else
                                                    Tidak ada kriteria secondary. NSF = 0.
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    
                                    {{-- Aspek Total Breakdown --}}
                                    <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-3 mt-4">
                                        <p class="text-[11px] font-bold text-indigo-400 uppercase tracking-wider mb-2">3. Total Nilai Aspek ({{ $aspekData['nama'] }})</p>
                                        <div class="text-xs font-mono text-indigo-600 bg-white p-2 rounded mb-2 border border-indigo-100">
                                            Nilai Aspek = (NCF × {{ $aspekData['cf_percentage'] }}%) + (NSF × {{ $aspekData['sf_percentage'] }}%)
                                        </div>
                                        <div class="text-sm font-medium text-indigo-900">
                                            Nilai Aspek = ({{ number_format($aspekData['ncf'], 2) }} × {{ $aspekData['cf_percentage']/100 }}) + ({{ number_format($aspekData['nsf'], 2) }} × {{ $aspekData['sf_percentage']/100 }})<br>
                                            Nilai Aspek = {{ number_format($aspekData['ncf'] * ($aspekData['cf_percentage']/100), 2) }} + {{ number_format($aspekData['nsf'] * ($aspekData['sf_percentage']/100), 2) }} = <span class="font-black text-lg text-indigo-700 ml-1">{{ number_format($aspekData['nilai_aspek'], 2) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        
                        <div class="bg-gradient-to-br from-green-50 to-emerald-100 rounded-2xl p-6 border border-green-200 shadow-sm mt-8">
                            <h5 class="text-[11px] font-black text-green-600 uppercase tracking-widest mb-3">4. Rekapitulasi Total Score Final</h5>
                            <div class="text-xs font-mono text-green-800 bg-white/60 p-3 rounded-lg mb-4 border border-green-100">
                                Total Score = Σ (Nilai Aspek × Bobot Aspek)
                            </div>
                            <div class="text-sm font-medium text-green-900 mb-4">
                                Total Score = 
                                @php
                                    $parts = [];
                                    foreach($application->profileMatchingResult->detail_per_aspek as $aspek) {
                                        $parts[] = "({$aspek['nama']}: " . number_format($aspek['nilai_aspek'], 2) . " × " . rtrim(rtrim(number_format($aspek['bobot'], 2), '0'), '.') . "%)";
                                    }
                                    echo implode(' + ', $parts);
                                @endphp
                            </div>
                            <div class="flex items-center justify-between border-t border-green-200/50 pt-4">
                                <span class="font-bold text-green-800 text-lg">Maka Total Akhir adalah:</span>
                                <span class="text-4xl font-black text-green-700 bg-white px-4 py-2 rounded-xl shadow-sm border border-green-200">
                                    {{ rtrim(rtrim(number_format($application->profileMatchingResult->total_score, 4), '0'), '.') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            
            <div class="mt-6 p-4 bg-blue-50 rounded-xl text-center text-sm border border-blue-100 max-w-sm mx-auto">
                Ranking pelamar ini di divisi <b>{{ $application->division->nama }}</b>: <span class="font-bold text-xl text-blue-800 ml-2">#{{ $application->profileMatchingResult->ranking }}</span>
            </div>
        @else
            <div class="p-6 text-center text-slate-500 text-sm bg-slate-50 rounded-xl border border-slate-100">Belum ada hasil kalkulasi profile matching.</div>
        @endif
    </div>

    {{-- Baris 3: Status Keputusan (Otomatis dari Ranking) --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
        <h3 class="text-sm font-bold text-slate-800 border-b border-slate-100 pb-3 mb-4">Status Keputusan</h3>
        
        @if(in_array($application->status, ['diterima', 'ditolak']))
            <div class="p-4 rounded-xl text-center border {{ $application->status === 'diterima' ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200' }} max-w-lg mx-auto">
                <div class="w-12 h-12 mx-auto rounded-full flex items-center justify-center mb-2 {{ $application->status === 'diterima' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                    @if($application->status === 'diterima')
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    @else
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    @endif
                </div>
                <p class="font-bold text-sm {{ $application->status === 'diterima' ? 'text-green-800' : 'text-red-800' }}">
                    Pelamar ini {{ ucfirst($application->status) }}
                </p>
                <p class="text-xs mt-1 {{ $application->status === 'diterima' ? 'text-green-600' : 'text-red-600' }}">
                    Ditentukan berdasarkan finalisasi keputusan di halaman Profile Matching.
                </p>
            </div>
        @else
            @if($application->status === 'pending')
            <div class="p-4 rounded-xl text-center border bg-slate-50 border-slate-200 max-w-lg mx-auto">
                <div class="w-12 h-12 mx-auto rounded-full flex items-center justify-center mb-2 bg-slate-100 text-slate-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </div>
                <p class="font-bold text-sm text-slate-700">Menunggu Penilaian</p>
                <p class="text-xs mt-1 text-slate-500">
                    Pelamar ini belum dinilai. Silakan input nilai di halaman Data Pelamar terlebih dahulu.
                </p>
            </div>
            @else
            <div class="p-4 rounded-xl text-center border bg-amber-50 border-amber-200 max-w-lg mx-auto">
                <div class="w-12 h-12 mx-auto rounded-full flex items-center justify-center mb-2 bg-amber-100 text-amber-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <p class="font-bold text-sm text-amber-800">Menunggu Finalisasi</p>
                <p class="text-xs mt-1 text-amber-600">
                    Nilai sudah diinput dan ranking sudah dihitung. Status diterima/ditolak akan ditentukan saat admin melakukan finalisasi keputusan di halaman Profile Matching.
                </p>
            </div>
            @endif
        @endif
    </div>
</div>
@endsection