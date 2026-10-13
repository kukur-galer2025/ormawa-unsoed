@extends("layouts.app")
@section("title", "Detail Mahasiswa — " . $mahasiswa->name)
@section("page-title", "Detail Mahasiswa")
@section("sidebar")@include("layouts.partials.sidebar-superadmin")@endsection
@section("content")

<div class="max-w-4xl space-y-6">

    {{-- Back Button --}}
    <a href="{{ route('superadmin.mahasiswa.index') }}"
        class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-slate-800 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali ke Kelola Mahasiswa
    </a>

    {{-- Header Card --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-8">
            <div class="flex items-center gap-5">
                {{-- Avatar --}}
                <div class="w-20 h-20 rounded-2xl bg-white/20 border-2 border-white/30 flex items-center justify-center text-white text-3xl font-black shrink-0 shadow-lg">
                    {{ strtoupper(substr($mahasiswa->name, 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <h1 class="text-2xl font-black text-white truncate">{{ $mahasiswa->name }}</h1>
                    <p class="text-blue-200 text-sm mt-1">{{ $mahasiswa->email }}</p>
                    <div class="flex items-center gap-2 mt-2">
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold {{ $mahasiswa->is_active ? 'bg-green-400/20 text-green-100 border border-green-400/30' : 'bg-red-400/20 text-red-100 border border-red-400/30' }}">
                            {{ $mahasiswa->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-white/10 text-white/80 border border-white/20">Mahasiswa</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Aksi cepat --}}
        <div class="px-6 py-3 bg-slate-50 border-t border-slate-100 flex flex-wrap gap-2">
            <form method="POST" action="{{ route('superadmin.mahasiswa.toggle', $mahasiswa) }}" class="inline" id="toggleForm">
                @csrf @method('PATCH')
                <button type="button"
                    onclick="confirmToggle({{ $mahasiswa->is_active ? 'true' : 'false' }}, '{{ addslashes($mahasiswa->name) }}')"
                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold transition-colors
                        {{ $mahasiswa->is_active
                            ? 'bg-red-50 text-red-600 border border-red-200 hover:bg-red-100'
                            : 'bg-green-50 text-green-600 border border-green-200 hover:bg-green-100' }}">
                    @if($mahasiswa->is_active)
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                        Nonaktifkan Akun
                    @else
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Aktifkan Akun
                    @endif
                </button>
            </form>

            <button type="button" onclick="resetPasswordModal({{ $mahasiswa->id }}, '{{ addslashes($mahasiswa->name) }}')"
                class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold bg-amber-50 text-amber-600 border border-amber-200 hover:bg-amber-100 transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                Reset Password
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Info Profil --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
            <h2 class="text-sm font-bold text-slate-800 mb-4 flex items-center gap-2">
                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                Informasi Profil
            </h2>
            @if($mahasiswa->mahasiswaProfile)
            <dl class="space-y-3">
                <div class="flex justify-between text-sm py-2 border-b border-slate-50">
                    <dt class="text-slate-500 font-medium">NIM</dt>
                    <dd class="text-slate-800 font-bold font-mono">{{ $mahasiswa->mahasiswaProfile->nim ?? '-' }}</dd>
                </div>
                <div class="flex justify-between text-sm py-2 border-b border-slate-50">
                    <dt class="text-slate-500 font-medium">Angkatan</dt>
                    <dd class="text-slate-800 font-semibold">{{ $mahasiswa->mahasiswaProfile->angkatan ?? '-' }}</dd>
                </div>
                <div class="flex justify-between text-sm py-2 border-b border-slate-50">
                    <dt class="text-slate-500 font-medium">Fakultas</dt>
                    <dd class="text-slate-800 font-semibold text-right">{{ $mahasiswa->mahasiswaProfile->fakultasRel->nama_fakultas ?? '-' }}</dd>
                </div>
                <div class="flex justify-between text-sm py-2 border-b border-slate-50">
                    <dt class="text-slate-500 font-medium">Jurusan</dt>
                    <dd class="text-slate-800 font-semibold text-right">{{ $mahasiswa->mahasiswaProfile->jurusanRel->nama_jurusan ?? '-' }}</dd>
                </div>
                <div class="flex justify-between text-sm py-2">
                    <dt class="text-slate-500 font-medium">No. HP</dt>
                    <dd class="text-slate-800 font-semibold">{{ $mahasiswa->mahasiswaProfile->no_hp ?? '-' }}</dd>
                </div>
            </dl>
            @else
            <div class="py-6 text-center">
                <svg class="w-8 h-8 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
                <p class="text-sm text-slate-400">Profil belum dilengkapi</p>
            </div>
            @endif
        </div>

        {{-- Info Akun --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
            <h2 class="text-sm font-bold text-slate-800 mb-4 flex items-center gap-2">
                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                Informasi Akun
            </h2>
            <dl class="space-y-3">
                <div class="flex justify-between text-sm py-2 border-b border-slate-50">
                    <dt class="text-slate-500 font-medium">Email</dt>
                    <dd class="text-slate-800 font-semibold text-right break-all">{{ $mahasiswa->email }}</dd>
                </div>
                <div class="flex justify-between text-sm py-2 border-b border-slate-50">
                    <dt class="text-slate-500 font-medium">Login via</dt>
                    <dd class="text-slate-800 font-semibold">
                        @if($mahasiswa->google_id)
                            <span class="inline-flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 01-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>
                                Google
                            </span>
                        @else
                            Email & Password
                        @endif
                    </dd>
                </div>
                <div class="flex justify-between text-sm py-2 border-b border-slate-50">
                    <dt class="text-slate-500 font-medium">Terdaftar</dt>
                    <dd class="text-slate-800 font-semibold">{{ $mahasiswa->created_at->format('d M Y') }}</dd>
                </div>
                <div class="flex justify-between text-sm py-2">
                    <dt class="text-slate-500 font-medium">Total Pendaftaran</dt>
                    <dd class="font-bold text-blue-600">{{ $mahasiswa->applications->count() }}</dd>
                </div>
            </dl>
        </div>
    </div>

    {{-- Riwayat Pendaftaran --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h2 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                Riwayat Pendaftaran Ormawa
            </h2>
            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-600">
                {{ $mahasiswa->applications->count() }} pendaftaran
            </span>
        </div>

        @if($mahasiswa->applications->isEmpty())
        <div class="px-6 py-10 text-center">
            <svg class="w-10 h-10 text-slate-200 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            <p class="text-sm text-slate-400 font-medium">Belum ada pendaftaran</p>
        </div>
        @else
        <div class="divide-y divide-slate-100">
            @foreach($mahasiswa->applications->sortByDesc('created_at') as $app)
            <div class="px-6 py-4 hover:bg-slate-50 transition-colors">
                <div class="flex items-start justify-between gap-4">
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-bold text-slate-800 truncate">
                            {{ $app->recruitment->judul ?? '-' }}
                        </p>
                        <p class="text-xs text-slate-500 mt-0.5">
                            {{ $app->recruitment->ormawa->nama ?? '-' }}
                            @if($app->division)
                                <span class="mx-1 text-slate-300">•</span>
                                <span class="text-indigo-600 font-medium">{{ $app->division->nama }}</span>
                            @endif
                        </p>
                        <p class="text-xs text-slate-400 mt-1">{{ $app->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    <div class="shrink-0">
                        @php
                            $statusMap = [
                                'menunggu'  => ['label' => 'Menunggu',  'class' => 'bg-yellow-50 text-yellow-700'],
                                'diterima'  => ['label' => 'Diterima',  'class' => 'bg-green-50 text-green-700'],
                                'ditolak'   => ['label' => 'Ditolak',   'class' => 'bg-red-50 text-red-700'],
                            ];
                            $st = $statusMap[$app->status] ?? ['label' => ucfirst($app->status), 'class' => 'bg-slate-100 text-slate-600'];
                        @endphp
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold {{ $st['class'] }}">{{ $st['label'] }}</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

</div>

@push('scripts')
<script>
function confirmToggle(isActive, userName) {
    const action = isActive ? 'menonaktifkan' : 'mengaktifkan';
    Swal.fire({
        title: 'Konfirmasi',
        text: `Apakah Anda yakin ingin ${action} akun ${userName}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: isActive ? '#ef4444' : '#22c55e',
        cancelButtonColor: '#64748b',
        confirmButtonText: isActive ? 'Ya, Nonaktifkan' : 'Ya, Aktifkan',
        cancelButtonText: 'Batal',
        customClass: { popup: 'rounded-2xl', confirmButton: 'rounded-lg', cancelButton: 'rounded-lg' }
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('toggleForm').submit();
        }
    });
}

function resetPasswordModal(userId, userName) {
    Swal.fire({
        title: 'Reset Password',
        html: `
            <p class="text-sm text-slate-500 mb-4">Reset password untuk <strong>${userName}</strong></p>
            <div class="text-left space-y-3">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Password Baru</label>
                    <input type="password" id="swal-password" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-sm" placeholder="Minimal 8 karakter">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Konfirmasi Password</label>
                    <input type="password" id="swal-password-confirm" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-sm" placeholder="Ulangi password baru">
                </div>
            </div>
        `,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#2563eb',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Reset Password',
        cancelButtonText: 'Batal',
        customClass: { popup: 'rounded-2xl', confirmButton: 'rounded-lg', cancelButton: 'rounded-lg' },
        preConfirm: () => {
            const password = document.getElementById('swal-password').value;
            const confirm = document.getElementById('swal-password-confirm').value;
            if (!password || password.length < 8) { Swal.showValidationMessage('Password minimal 8 karakter'); return false; }
            if (password !== confirm) { Swal.showValidationMessage('Konfirmasi password tidak cocok'); return false; }
            return { password, password_confirmation: confirm };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/superadmin/mahasiswa/${userId}/reset-password`;
            form.innerHTML = `<input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="hidden" name="_method" value="PUT"><input type="hidden" name="password" value="${result.value.password}"><input type="hidden" name="password_confirmation" value="${result.value.password_confirmation}">`;
            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>
@endpush
@endsection
