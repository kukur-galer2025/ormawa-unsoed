@extends("layouts.app")
@section("title","Input Nilai")@section("page-title","Input Nilai — " . $application->user->name)
@section("sidebar")@include("layouts.partials.sidebar-admin")@endsection
@section("content")
<div class="mb-6"><a href="{{ route('admin.scoring.index', $recruitment) }}" class="text-sm text-slate-500 hover:text-slate-700">&larr; Kembali ke Daftar Scoring</a></div>

<div class="max-w-3xl mx-auto">
    {{-- Applicant Info Card --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 mb-6">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center text-white text-xl font-bold">
                {{ strtoupper(substr($application->user->name, 0, 1)) }}
            </div>
            <div>
                <h3 class="text-lg font-semibold text-slate-800">{{ $application->user->name }}</h3>
                <p class="text-sm text-slate-500">{{ $application->user->mahasiswaProfile->nim ?? '-' }} — {{ $application->user->mahasiswaProfile->fakultas ?? '-' }} / {{ $application->user->mahasiswaProfile->jurusan ?? '-' }}</p>
                <span class="inline-flex items-center mt-1 px-2.5 py-0.5 rounded-full text-xs font-medium {{ $application->status === 'diproses' ? 'bg-blue-50 text-blue-700' : ($application->status === 'pending' ? 'bg-amber-50 text-amber-700' : 'bg-slate-100 text-slate-600') }}">
                    {{ ucfirst($application->status) }}
                </span>
            </div>
        </div>
    </div>

    {{-- Scoring Form --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
        <h3 class="text-lg font-semibold text-slate-800 mb-1">Input Nilai Kriteria</h3>
        <p class="text-sm text-slate-500 mb-6">Berikan nilai 1-5 untuk setiap kriteria penilaian.</p>

        <form method="POST" action="{{ route('admin.scoring.store', [$recruitment, $application]) }}">
            @csrf
            <div class="space-y-4 mb-6">
                @foreach($criteria as $c)
                @php $existingScore = $application->scores->firstWhere('criteria_id', $c->id); @endphp
                <div class="border border-slate-200 rounded-xl p-4 hover:border-slate-300 transition-colors">
                    <div class="flex justify-between items-center mb-3">
                        <div>
                            <span class="text-sm font-medium text-slate-700">{{ $c->nama_kriteria }}</span>
                            @if($c->keterangan)
                                <p class="text-xs text-slate-400 mt-0.5">{{ $c->keterangan }}</p>
                            @endif
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="px-1.5 py-0.5 rounded text-xs {{ $c->tipe === 'core' ? 'bg-blue-50 text-blue-600' : 'bg-purple-50 text-purple-600' }}">{{ strtoupper($c->tipe) }}</span>
                            <span class="text-xs text-slate-400">Target: {{ $c->target_value }}</span>
                        </div>
                    </div>
                    <select name="scores[{{ $c->id }}]" required class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors">
                        <option value="">— Pilih Nilai —</option>
                        @for($i = 1; $i <= 5; $i++)
                            <option value="{{ $i }}" {{ ($existingScore && $existingScore->actual_value == $i) ? 'selected' : '' }}>{{ $i }} {{ $i == $c->target_value ? '(Target)' : '' }}</option>
                        @endfor
                    </select>
                    @error("scores.{$c->id}")<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
                @endforeach
            </div>

            <button type="submit" class="w-full py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-xl shadow-lg shadow-blue-500/25 hover:from-blue-500 hover:to-indigo-500 transition-all">
                Simpan Nilai
            </button>
        </form>
    </div>
</div>
@endsection
