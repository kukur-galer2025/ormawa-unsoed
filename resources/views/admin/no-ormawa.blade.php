@extends("layouts.app")
@section("title","Tidak Ada Ormawa")@section("page-title","Dashboard")
@section("sidebar")@include("layouts.partials.sidebar-admin")@endsection
@section("content")
<div class="text-center py-16"><div class="w-16 h-16 bg-amber-50 rounded-full flex items-center justify-center mx-auto mb-4"><svg class="w-8 h-8 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg></div><h3 class="text-lg font-semibold text-slate-800">Anda belum di-assign ke ormawa</h3><p class="text-slate-500 mt-2">Hubungi Superadmin untuk mendapatkan akses.</p></div>
@endsection