@extends("layouts.app")
@section("title","Daftar Rekrutmen")@section("page-title","Form Pendaftaran")
@section("sidebar")@include("layouts.partials.sidebar-mahasiswa")@endsection
@section("content")
<div class="mb-6"><a href="{{ route('mahasiswa.recruitment.show', $recruitment) }}" class="text-sm text-slate-500 hover:text-slate-700">&larr; Kembali ke Detail Rekrutmen</a></div>
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 mb-6">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center text-white text-lg font-bold">{{ strtoupper(substr($recruitment->ormawa->nama, 0, 1)) }}</div>
            <div>
                <h3 class="font-semibold text-slate-800">{{ $recruitment->judul }}</h3>
                <p class="text-sm text-slate-500">{{ $recruitment->ormawa->nama }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
        <h3 class="text-lg font-semibold text-slate-800 mb-4">Form Pendaftaran</h3>
        <form method="POST" action="{{ route('mahasiswa.recruitment.apply', $recruitment) }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Motivasi <span class="text-red-500">*</span></label>
                <textarea name="motivasi" rows="5" required placeholder="Tuliskan motivasi Anda mengikuti rekrutmen ini..." class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors">{{ old('motivasi') }}</textarea>
                @error('motivasi')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Berkas Pendukung <span class="text-slate-400">(PDF/DOC, max 5MB)</span></label>
                <input type="file" name="berkas_pendukung" accept=".pdf,.doc,.docx" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                @error('berkas_pendukung')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>
            <button type="submit" class="w-full py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-xl shadow-lg shadow-blue-500/25 hover:from-blue-500 hover:to-indigo-500 transition-all" onclick="return confirm('Yakin ingin mendaftar pada rekrutmen ini?')">Kirim Pendaftaran</button>
        </form>
    </div>
</div>
@endsection
