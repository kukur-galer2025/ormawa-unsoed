@extends('layouts.guest')
@section('title', 'Lupa Sandi')

@section('left-image')
    <img src="{{ asset('images/login_bg.webp') }}" alt="Campus" class="absolute inset-0 w-full h-full object-cover opacity-50 mix-blend-overlay scale-105 hover:scale-100 transition-transform duration-[10s]">
@endsection

@section('content')
{{-- Lock Icon --}}
<div class="flex justify-center mb-6">
    <div class="w-20 h-20 rounded-2xl flex items-center justify-center shadow-lg" style="background: linear-gradient(135deg, #3b82f6, #6366f1);">
        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
        </svg>
    </div>
</div>

<h2 class="text-xl sm:text-2xl font-black text-slate-900 mb-2 tracking-tight text-center">Lupa Kata Sandi?</h2>
<p class="text-slate-500 text-sm mb-8 font-medium leading-relaxed text-center">Jangan khawatir! Hubungi Admin kami melalui WhatsApp untuk mereset kata sandi akun Anda.</p>

{{-- Steps --}}
<div class="space-y-3 mb-8">
    <div class="flex items-start gap-3 p-3 bg-slate-50 rounded-xl border border-slate-100">
        <div class="w-7 h-7 rounded-lg flex items-center justify-center text-xs font-black text-white shrink-0" style="background: linear-gradient(135deg, #3b82f6, #6366f1);">1</div>
        <div>
            <p class="text-sm font-bold text-slate-700">Klik tombol WhatsApp di bawah</p>
            <p class="text-xs text-slate-400 mt-0.5">Anda akan diarahkan ke WhatsApp Admin</p>
        </div>
    </div>
    <div class="flex items-start gap-3 p-3 bg-slate-50 rounded-xl border border-slate-100">
        <div class="w-7 h-7 rounded-lg flex items-center justify-center text-xs font-black text-white shrink-0" style="background: linear-gradient(135deg, #3b82f6, #6366f1);">2</div>
        <div>
            <p class="text-sm font-bold text-slate-700">Sampaikan email & NIM Anda</p>
            <p class="text-xs text-slate-400 mt-0.5">Admin akan memverifikasi identitas Anda</p>
        </div>
    </div>
    <div class="flex items-start gap-3 p-3 bg-slate-50 rounded-xl border border-slate-100">
        <div class="w-7 h-7 rounded-lg flex items-center justify-center text-xs font-black text-white shrink-0" style="background: linear-gradient(135deg, #3b82f6, #6366f1);">3</div>
        <div>
            <p class="text-sm font-bold text-slate-700">Dapatkan sandi baru</p>
            <p class="text-xs text-slate-400 mt-0.5">Admin akan mereset dan memberikan sandi baru</p>
        </div>
    </div>
</div>

{{-- WhatsApp Button --}}
<a href="https://wa.me/6287864270595?text=Halo%20Admin%2C%0A%0ASaya%20lupa%20kata%20sandi%20akun%20saya.%0AMohon%20bantuannya%20untuk%20mereset%20kata%20sandi%20akun%20saya.%0A%0ATerima%20kasih." target="_blank" style="background-color: #25D366;" class="w-full inline-flex items-center justify-center gap-3 py-4 px-6 text-white font-black text-base rounded-2xl focus:outline-none focus:ring-4 focus:ring-green-500/30 transition-all shadow-xl hover:-translate-y-1 hover:shadow-2xl">
    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
    Hubungi Admin via WhatsApp
</a>

{{-- Tip --}}
<div class="mt-5 p-3 bg-amber-50 border border-amber-200 rounded-xl flex items-start gap-2.5">
    <svg class="w-4 h-4 text-amber-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    <p class="text-xs text-amber-700 font-medium leading-relaxed"><strong>Tips:</strong> Siapkan alamat email dan NIM Anda saat menghubungi admin agar proses reset lebih cepat.</p>
</div>

<div class="mt-8 pt-6 border-t border-slate-100 text-center">
    <p class="text-sm text-slate-500 font-medium">
        Tiba-tiba ingat sandinya? <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800 font-bold transition-colors">Masuk kembali</a>
    </p>
</div>
@endsection
