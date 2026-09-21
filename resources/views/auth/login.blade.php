@extends('layouts.guest')
@section('title', 'Login')

@section('left-image')
    <img src="{{ asset('images/login_bg.webp') }}" alt="Campus" class="absolute inset-0 w-full h-full object-cover opacity-50 mix-blend-overlay scale-105 hover:scale-100 transition-transform duration-[10s]">
@endsection

@section('content')
<h2 class="text-xl sm:text-2xl font-black text-slate-900 mb-2 tracking-tight">Selamat Datang Kembali</h2>
<p class="text-slate-500 text-sm mb-8 font-medium">Masuk ke akun Anda untuk melanjutkan</p>

@error('throttle')
<div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-xl text-sm text-red-600 font-medium">
    {{ $message }}
</div>
@enderror

<form method="POST" action="{{ route('login') }}" class="space-y-4">
    @csrf
    <div>
        <label for="email" class="block text-sm font-bold text-slate-700 mb-2">Email</label>
        <div class="relative flex items-center">
            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="Masukkan email Anda"
                   class="peer w-full pl-11 focus:pl-4 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition-all duration-300">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 peer-focus:opacity-0 peer-focus:-translate-x-2 transition-all duration-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/></svg>
            </div>
        </div>
        @error('email')<p class="mt-2 text-xs font-medium text-red-500">{{ $message }}</p>@enderror
    </div>
    
    <div x-data="{ show: false }">
        <label for="password" class="block text-sm font-bold text-slate-700 mb-2">Kata Sandi</label>
        <div class="relative flex items-center">
            <input :type="show ? 'text' : 'password'" id="password" name="password" required placeholder="••••••••"
                   class="peer w-full pl-11 focus:pl-4 pr-12 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition-all duration-300">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 peer-focus:opacity-0 peer-focus:-translate-x-2 transition-all duration-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            </div>
            <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-blue-600 focus:outline-none transition-colors">
                <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
            </button>
        </div>
        @error('password')<p class="mt-2 text-xs font-medium text-red-500">{{ $message }}</p>@enderror
    </div>
    
    <div class="flex items-center justify-between pt-1">
        <label class="flex items-center gap-3 cursor-pointer group">
            <input type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500 transition-colors">
            <span class="text-sm font-bold text-slate-600 group-hover:text-slate-900 transition-colors">Ingat saya</span>
        </label>
        
        <a href="{{ route('password.request') }}" class="text-sm font-bold text-blue-600 hover:text-blue-800 transition-colors">
            Lupa sandi?
        </a>
    </div>
    
    <button type="submit" class="w-full py-3 px-4 mt-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-black text-sm rounded-xl hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-4 focus:ring-blue-500/30 transition-all shadow-lg shadow-blue-500/25 hover:-translate-y-0.5">
        Masuk Sekarang
    </button>
</form>

{{-- Divider --}}
<div class="flex items-center gap-4 my-6">
    <div class="flex-1 h-px bg-slate-200"></div>
    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">atau</span>
    <div class="flex-1 h-px bg-slate-200"></div>
</div>

{{-- Google Login Button --}}
<a href="{{ route('auth.google') }}" class="w-full inline-flex items-center justify-center gap-3 py-3 px-4 bg-white border-2 border-slate-200 rounded-xl text-sm font-bold text-slate-700 hover:bg-slate-50 hover:border-slate-300 hover:text-slate-900 transition-all duration-300 shadow-sm hover:shadow-md group">
    <svg class="w-5 h-5 transition-transform group-hover:scale-110" viewBox="0 0 24 24">
        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 01-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z" fill="#4285F4"/>
        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
    </svg>
    Masuk dengan Google
</a>

<div class="mt-6 pt-6 border-t border-slate-100 text-center">
    <p class="text-sm text-slate-500 font-medium">
        Belum punya akun? <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-800 font-bold transition-colors">Daftar di sini</a>
    </p>
</div>
@endsection