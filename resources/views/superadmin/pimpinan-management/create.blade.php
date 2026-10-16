@extends("layouts.app")
@section("title","Tambah Pimpinan")@section("page-title","Tambah Pimpinan")
@section("sidebar")@include("layouts.partials.sidebar-superadmin")@endsection
@section("content")
<div class="max-w-2xl mx-auto">
<div class="mb-6">
    <a href="{{ route('superadmin.pimpinan.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-slate-700 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Kembali ke daftar pimpinan
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="p-6 border-b border-slate-100">
        <h2 class="text-lg font-semibold text-slate-800">Tambah Akun Pimpinan</h2>
        <p class="text-sm text-slate-500 mt-1">Buat akun baru untuk Wakil Rektor atau Wakil Dekan.</p>
    </div>
    <form method="POST" action="{{ route('superadmin.pimpinan.store') }}" class="p-6 space-y-5" id="create-pimpinan-form">
        @csrf

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1.5">Nama Lengkap</label>
            <input type="text" name="name" value="{{ old('name') }}" required
                   class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm transition-all"
                   placeholder="Nama lengkap pimpinan">
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1.5">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required
                   class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm transition-all"
                   placeholder="email@unsoed.ac.id">
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1.5">Password</label>
            <input type="password" name="password" required
                   class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm transition-all"
                   placeholder="Minimal 8 karakter">
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1.5">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" required
                   class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm transition-all"
                   placeholder="Ulangi password">
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1.5">Jabatan / Peran</label>
            <select name="role" id="role-select" required
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm transition-all"
                    onchange="toggleFakultasField(this.value)">
                <option value="">-- Pilih Jabatan --</option>
                <option value="wakil_rektor" {{ old('role') === 'wakil_rektor' ? 'selected' : '' }}>Wakil Rektor</option>
                <option value="wakil_dekan" {{ old('role') === 'wakil_dekan' ? 'selected' : '' }}>Wakil Dekan</option>
            </select>
        </div>

        <div id="fakultas-field" class="{{ old('role') !== 'wakil_dekan' ? 'hidden' : '' }}">
            <label class="block text-sm font-medium text-slate-700 mb-1.5">Fakultas <span class="text-red-500">*</span></label>
            <select name="fakultas_id"
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm transition-all">
                <option value="">-- Pilih Fakultas --</option>
                @foreach($fakultas as $f)
                <option value="{{ $f->id }}" {{ old('fakultas_id') == $f->id ? 'selected' : '' }}>
                    {{ $f->nama_fakultas }}
                </option>
                @endforeach
            </select>
            <p class="mt-1 text-xs text-slate-400">Wakil Dekan hanya melihat ormawa dalam fakultas ini.</p>
        </div>

        <div class="flex items-center gap-3 pt-2">
            <button type="submit"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-sm font-semibold rounded-xl shadow-lg shadow-blue-500/25 hover:from-blue-700 hover:to-indigo-700 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Simpan Akun
            </button>
            <a href="{{ route('superadmin.pimpinan.index') }}" class="px-5 py-2.5 text-sm text-slate-600 hover:text-slate-800 hover:bg-slate-100 rounded-xl transition-all">
                Batal
            </a>
        </div>
    </form>
</div>
</div>

@push('scripts')
<script>
function toggleFakultasField(role) {
    const field = document.getElementById('fakultas-field');
    const select = field.querySelector('select');
    if (role === 'wakil_dekan') {
        field.classList.remove('hidden');
        select.required = true;
    } else {
        field.classList.add('hidden');
        select.required = false;
    }
}
// Init on page load for old() value
document.addEventListener('DOMContentLoaded', () => {
    const roleSelect = document.getElementById('role-select');
    if (roleSelect) toggleFakultasField(roleSelect.value);
});
</script>
@endpush
@endsection
