@extends("layouts.app")
@section("title","Kelola Mahasiswa")@section("page-title","Kelola Mahasiswa")
@section("sidebar")@include("layouts.partials.sidebar-superadmin")@endsection
@section("content")
<div class="mb-6"><form method="GET" class="flex flex-col sm:flex-row gap-3"><input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, email, atau NIM..." class="flex-1 w-full sm:max-w-md px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"><button type="submit" class="w-full sm:w-auto px-4 py-2.5 bg-slate-800 text-white text-sm font-medium rounded-xl hover:bg-slate-700">Cari</button></form></div>
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden"><div class="overflow-x-auto"><table class="w-full"><thead><tr class="bg-slate-50"><th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Nama</th><th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">NIM</th><th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Fakultas</th><th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Jurusan</th><th class="px-6 py-3 text-center text-xs font-semibold text-slate-500 uppercase">Status</th><th class="px-6 py-3 text-center text-xs font-semibold text-slate-500 uppercase">Aksi</th></tr></thead>
<tbody class="divide-y divide-slate-100">@forelse($mahasiswas as $m)<tr class="hover:bg-slate-50"><td class="px-6 py-4 font-medium text-slate-800">{{ $m->name }}</td><td class="px-6 py-4 text-sm text-slate-600">{{ $m->mahasiswaProfile->nim ?? '-' }}</td><td class="px-6 py-4 text-sm text-slate-600">{{ $m->mahasiswaProfile->fakultasRel->nama_fakultas ?? '-' }}</td><td class="px-6 py-4 text-sm text-slate-600">{{ $m->mahasiswaProfile->jurusanRel->nama_jurusan ?? '-' }}</td><td class="px-6 py-4 text-center"><span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $m->is_active ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700' }}">{{ $m->is_active ? 'Aktif' : 'Nonaktif' }}</span></td><td class="px-6 py-4 text-center"><div class="flex items-center justify-center gap-2"><form method="POST" action="{{ route('superadmin.mahasiswa.toggle', $m) }}">@csrf @method('PATCH')<button class="text-xs font-medium px-3 py-1.5 rounded-lg {{ $m->is_active ? 'bg-red-50 text-red-600 hover:bg-red-100' : 'bg-green-50 text-green-600 hover:bg-green-100' }}">{{ $m->is_active ? 'Nonaktifkan' : 'Aktifkan' }}</button></form><button type="button" onclick="resetPasswordModal({{ $m->id }}, '{{ addslashes($m->name) }}')" class="text-xs font-medium px-3 py-1.5 rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-100" title="Reset Password"><svg class="w-3.5 h-3.5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>Reset</button></div></td></tr>@empty<tr><td colspan="6" class="px-6 py-8 text-center text-slate-400">Tidak ada data.</td></tr>@endforelse</tbody></table></div>
@if($mahasiswas->hasPages())<div class="px-6 py-4 border-t border-slate-200">{{ $mahasiswas->links() }}</div>@endif</div>

@push('scripts')
<script>
function resetPasswordModal(userId, userName) {
    Swal.fire({
        title: 'Reset Password',
        html: `
            <p class="text-sm text-slate-500 mb-4">Reset password untuk <strong>${userName}</strong></p>
            <div class="text-left space-y-3">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Password Baru</label>
                    <input type="password" id="swal-password" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-sm" placeholder="Minimal 8 karakter" minlength="8">
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
        customClass: {
            popup: 'rounded-2xl',
            confirmButton: 'rounded-lg',
            cancelButton: 'rounded-lg'
        },
        preConfirm: () => {
            const password = document.getElementById('swal-password').value;
            const confirm = document.getElementById('swal-password-confirm').value;

            if (!password || password.length < 8) {
                Swal.showValidationMessage('Password minimal 8 karakter');
                return false;
            }
            if (password !== confirm) {
                Swal.showValidationMessage('Konfirmasi password tidak cocok');
                return false;
            }
            return { password, password_confirmation: confirm };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Create and submit a form
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/superadmin/mahasiswa/${userId}/reset-password`;

            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = '{{ csrf_token() }}';
            form.appendChild(csrf);

            const method = document.createElement('input');
            method.type = 'hidden';
            method.name = '_method';
            method.value = 'PUT';
            form.appendChild(method);

            const pw = document.createElement('input');
            pw.type = 'hidden';
            pw.name = 'password';
            pw.value = result.value.password;
            form.appendChild(pw);

            const pwConfirm = document.createElement('input');
            pwConfirm.type = 'hidden';
            pwConfirm.name = 'password_confirmation';
            pwConfirm.value = result.value.password_confirmation;
            form.appendChild(pwConfirm);

            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>
@endpush
@endsection