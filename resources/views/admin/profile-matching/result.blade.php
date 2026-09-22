@extends("layouts.app")
@section("title","Hasil Profile Matching")@section("page-title","Ranking: " . $division->nama)
@section("sidebar")@include("layouts.partials.sidebar-admin")@endsection
@section("content")
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
    <div>
        <p class="text-sm text-slate-500">Rekrutmen: <span class="font-semibold text-slate-700">{{ $recruitment->judul }}</span></p>
    </div>
    <div class="flex gap-2 flex-wrap">
        @if(!$division->is_finalized)
        <form action="{{ route('admin.profile-matching.calculate', [$recruitment, $division]) }}" method="POST">
            @csrf
            <button type="submit" class="px-4 py-2 bg-gradient-to-r from-purple-600 to-indigo-600 text-white text-sm font-semibold rounded-xl shadow-lg shadow-purple-500/25 hover:shadow-xl transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                Hitung Ulang
            </button>
        </form>
        @endif
        <a href="{{ route('admin.profile-matching.show', $recruitment) }}" class="px-4 py-2 bg-slate-100 text-slate-700 text-sm font-medium rounded-xl hover:bg-slate-200">← Kembali</a>
    </div>
</div>

{{-- Banner Status Finalisasi --}}
@if($division->is_finalized)
<div class="mb-6 p-4 rounded-2xl border flex items-center gap-3 {{ $recruitment->is_announced ? 'bg-green-50 border-green-200' : 'bg-blue-50 border-blue-200' }}">
    <div class="w-10 h-10 rounded-full flex items-center justify-center {{ $recruitment->is_announced ? 'bg-green-100 text-green-600' : 'bg-blue-100 text-blue-600' }}">
        @if($recruitment->is_announced)
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        @else
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
        @endif
    </div>
    <div>
        <p class="font-bold text-sm {{ $recruitment->is_announced ? 'text-green-800' : 'text-blue-800' }}">
            🔒 Divisi ini sudah difinalisasi
            @if($recruitment->is_announced)
                & diumumkan
            @endif
        </p>
        <p class="text-xs {{ $recruitment->is_announced ? 'text-green-600' : 'text-blue-600' }}">
            @if($recruitment->is_announced)
                Hasil sudah terlihat oleh mahasiswa.
            @else
                Keputusan terkunci. Mahasiswa belum bisa melihat hasilnya sampai admin mengumumkan.
            @endif
        </p>
    </div>
</div>
@endif

{{-- Tombol Umumkan Hasil (jika semua divisi sudah finalisasi tapi belum diumumkan) --}}
@php
    $allFinalized = $recruitment->divisions->every(fn($d) => $d->is_finalized);
@endphp
@if($allFinalized && !$recruitment->is_announced && $division->is_finalized)
<div class="mb-6 p-5 bg-gradient-to-r from-emerald-50 to-teal-50 border border-emerald-200 rounded-2xl">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <p class="font-bold text-emerald-800 text-sm">✅ Semua divisi sudah difinalisasi!</p>
            <p class="text-xs text-emerald-600 mt-1">Klik tombol di bawah untuk mengumumkan hasil rekrutmen ke seluruh mahasiswa.</p>
        </div>
        <form action="{{ route('admin.profile-matching.announce', $recruitment) }}" method="POST" onsubmit="return confirmAnnounce(event)">
            @csrf
            <button type="submit" class="px-6 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-bold text-sm rounded-xl shadow-lg shadow-emerald-500/30 hover:shadow-xl hover:-translate-y-0.5 transition-all flex items-center gap-2 whitespace-nowrap">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                Umumkan Hasil Rekrutmen
            </button>
        </form>
    </div>
</div>
@endif

{{-- Dropdown Filter Divisi --}}
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 mb-6">
    <div class="flex items-center gap-3">
        <span class="text-sm font-semibold text-slate-600 whitespace-nowrap">Pilih Divisi:</span>
        <select onchange="window.location.href=this.value" class="flex-1 px-4 py-2.5 border border-slate-300 rounded-xl text-sm font-medium bg-slate-50 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all cursor-pointer">
            @foreach($allDivisions as $div)
                <option value="{{ route('admin.profile-matching.result', [$recruitment, $div]) }}" {{ $div->id === $division->id ? 'selected' : '' }}>
                    {{ $div->nama }} — {{ $div->applications_count }} pelamar, kuota {{ $div->kuota }}
                    @if($div->is_finalized) ✅ @endif
                </option>
            @endforeach
        </select>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
        <p class="text-xs font-semibold text-slate-500 uppercase mb-1">Total Pelamar</p>
        <p class="text-2xl font-black text-slate-800">{{ $division->applications()->count() }}</p>
    </div>
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
        <p class="text-xs font-semibold text-slate-500 uppercase mb-1">Kuota Divisi</p>
        <p class="text-2xl font-black text-blue-600">{{ $division->kuota }}</p>
    </div>
</div>

<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden" x-data="finalizePicker()">
    @if($results->isEmpty())
        <div class="p-12 text-center text-slate-500 flex flex-col items-center">
            <svg class="w-12 h-12 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            <p class="font-medium">Belum ada hasil kalkulasi.</p>
            <p class="text-xs mt-1">Silakan klik "Hitung Ulang" untuk memproses data pelamar.</p>
        </div>
    @else
        {{-- Counter untuk mode seleksi --}}
        @if(!$division->is_finalized)
        <div class="px-6 py-4 bg-indigo-50 border-b border-indigo-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
            <div>
                <p class="text-sm font-bold text-indigo-800">Pilih Pelamar yang Diterima</p>
                <p class="text-xs text-indigo-600 mt-0.5">Centang pelamar yang ingin diterima, lalu klik Finalisasi. Maksimal <span class="font-bold">{{ $division->kuota }}</span> orang.</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-sm font-bold px-3 py-1.5 rounded-lg" :class="selectedCount > {{ $division->kuota }} ? 'bg-red-100 text-red-700' : 'bg-indigo-100 text-indigo-700'">
                    <span x-text="selectedCount"></span> / {{ $division->kuota }} terpilih
                </span>
            </div>
        </div>
        @endif

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-600 font-semibold text-xs uppercase tracking-wider">
                    <tr>
                        @if(!$division->is_finalized)
                        <th class="px-4 py-4 text-center w-12">Pilih</th>
                        @endif
                        <th class="px-6 py-4 text-center w-16">Rank</th>
                        <th class="px-6 py-4">Pelamar</th>
                        <th class="px-6 py-4">Detail Per Aspek</th>
                        <th class="px-6 py-4 text-center text-indigo-700">Total Score</th>
                        <th class="px-6 py-4 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($results as $res)
                        @php 
                            $isTop = $res->ranking <= $division->kuota;
                            $app = $res->application;
                        @endphp
                        <tr class="transition-colors hover:bg-slate-50/50 {{ $isTop && !$division->is_finalized ? 'bg-green-50/30' : '' }}" x-data="{ expanded: false }">
                            @if(!$division->is_finalized)
                            <td class="px-4 py-4 text-center align-top">
                                <input type="checkbox" 
                                    value="{{ $app->id }}" 
                                    x-model="selectedIds"
                                    {{ $isTop ? 'checked' : '' }}
                                    class="w-5 h-5 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer">
                            </td>
                            @endif
                            <td class="px-6 py-4 text-center align-top">
                                @if($res->ranking == 1)
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-yellow-100 text-yellow-700 font-bold border border-yellow-200 shadow-sm">1</span>
                                @elseif($res->ranking == 2)
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-slate-200 text-slate-700 font-bold border border-slate-300 shadow-sm">2</span>
                                @elseif($res->ranking == 3)
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-orange-100 text-orange-700 font-bold border border-orange-200 shadow-sm">3</span>
                                @else
                                    <span class="font-bold text-slate-500">{{ $res->ranking }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 align-top">
                                <p class="font-bold text-slate-800 text-base">{{ $app->user->name }}</p>
                                <p class="text-xs text-slate-500">{{ $app->user->mahasiswaProfile->nim ?? '-' }}</p>
                            </td>
                            <td class="px-6 py-4 align-top">
                                @if($res->detail_per_aspek)
                                    <button @click="expanded = !expanded" class="text-xs font-semibold text-blue-600 hover:text-blue-800 flex items-center gap-1 mb-2">
                                        <svg class="w-4 h-4 transition-transform" :class="expanded ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                        Lihat Rincian
                                    </button>
                                    <div x-show="expanded" x-collapse>
                                        <div class="space-y-2 mt-2 bg-slate-50 p-3 rounded-xl border border-slate-200">
                                            @foreach($res->detail_per_aspek as $aspek)
                                                <div class="flex justify-between items-center text-xs">
                                                    <div>
                                                        <span class="font-bold text-slate-700">{{ $aspek['nama'] }}</span>
                                                        <span class="text-slate-400"> (Bobot {{ round($aspek['bobot'] * 100) }}%)</span>
                                                    </div>
                                                    <div class="font-mono text-slate-600 bg-white px-2 py-1 rounded border border-slate-200">
                                                        <span title="NCF ({{ $aspek['cf_percentage'] }}%)">CF: {{ number_format($aspek['ncf'], 2) }}</span> | 
                                                        <span title="NSF ({{ $aspek['sf_percentage'] }}%)">SF: {{ number_format($aspek['nsf'], 2) }}</span> = 
                                                        <span class="font-bold text-indigo-600">{{ number_format($aspek['nilai_aspek'], 2) }}</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center font-black text-indigo-700 text-lg align-top">
                                {{ $res->total_score }}
                            </td>
                            <td class="px-6 py-4 text-center align-top">
                                @if($division->is_finalized)
                                    {{-- Sudah difinalisasi: tampilkan status final --}}
                                    @if($app->status === 'diterima')
                                        <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-green-100 text-green-700 border border-green-200">✅ Diterima</span>
                                    @elseif($app->status === 'ditolak')
                                        <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-red-100 text-red-700 border border-red-200">❌ Ditolak</span>
                                    @endif
                                @else
                                    {{-- Belum difinalisasi: tampilkan rekomendasi --}}
                                    @if($isTop)
                                        <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-blue-100 text-blue-700 border border-blue-200">Masuk Kuota</span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-slate-100 text-slate-600 border border-slate-200">Luar Kuota</span>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Tombol Finalisasi --}}
        @if(!$division->is_finalized)
        <div class="px-6 py-4 bg-slate-50 border-t border-slate-200">
            <form action="{{ route('admin.profile-matching.finalize', [$recruitment, $division]) }}" method="POST" id="finalizeForm">
                @csrf
                <template x-for="id in selectedIds" :key="id">
                    <input type="hidden" name="accepted_ids[]" :value="id">
                </template>
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <p class="text-xs text-slate-500">
                        ⚠️ Setelah finalisasi, keputusan <strong>tidak bisa diubah</strong>. Pelamar yang tidak dipilih akan otomatis <strong>ditolak</strong>.
                    </p>
                    <button type="button" 
                        @click="submitFinalize()"
                        :disabled="selectedCount === 0 || selectedCount > {{ $division->kuota }}"
                        :class="selectedCount === 0 || selectedCount > {{ $division->kuota }} ? 'opacity-50 cursor-not-allowed' : 'hover:shadow-xl hover:-translate-y-0.5'"
                        class="px-6 py-3 bg-gradient-to-r from-red-600 to-rose-600 text-white font-bold text-sm rounded-xl shadow-lg shadow-red-500/30 transition-all flex items-center gap-2 whitespace-nowrap">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Finalisasi Divisi Ini
                    </button>
                </div>
            </form>
        </div>
        @endif
    @endif
</div>

@push('scripts')
<script>
function finalizePicker() {
    // Pre-select applicants within quota
    const preSelected = [];
    @if(!$division->is_finalized)
        @foreach($results as $res)
            @if($res->ranking <= $division->kuota)
                preSelected.push('{{ $res->application->id }}');
            @endif
        @endforeach
    @endif

    return {
        selectedIds: preSelected,
        get selectedCount() {
            return this.selectedIds.length;
        },
        submitFinalize() {
            if (this.selectedCount === 0) return;
            if (this.selectedCount > {{ $division->kuota }}) return;

            Swal.fire({
                title: 'Finalisasi Keputusan?',
                html: `<p>Anda akan menerima <b>${this.selectedCount}</b> pelamar dan menolak sisanya.</p><p class="text-red-600 font-bold mt-2">Keputusan ini TIDAK BISA DIUBAH!</p>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Finalisasi!',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-lg',
                    cancelButton: 'rounded-lg'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('finalizeForm').submit();
                }
            });
        }
    };
}

function confirmAnnounce(event) {
    event.preventDefault();
    const form = event.target;
    Swal.fire({
        title: 'Umumkan Hasil Rekrutmen?',
        html: '<p>Semua mahasiswa akan dapat melihat status kelulusan mereka (diterima/ditolak).</p><p class="text-amber-600 font-bold mt-2">Pastikan semua keputusan sudah benar!</p>',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#059669',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Ya, Umumkan!',
        cancelButtonText: 'Batal',
        customClass: {
            popup: 'rounded-2xl',
            confirmButton: 'rounded-lg',
            cancelButton: 'rounded-lg'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
    return false;
}
</script>
@endpush
@endsection