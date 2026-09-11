@extends("layouts.app")
@section("title","Edit Admin")@section("page-title","Edit Admin")
@section("sidebar")@include("layouts.partials.sidebar-superadmin")@endsection
@section("content")
<div class="max-w-2xl"><div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
<form method="POST" action="{{ route('superadmin.admin.update', $admin) }}" class="space-y-5">@csrf @method('PUT')
<div><label class="block text-sm font-medium text-slate-700 mb-1">Nama</label><input type="text" name="name" value="{{ old('name', $admin->name) }}" required class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"></div>
<div><label class="block text-sm font-medium text-slate-700 mb-1">Email</label><input type="email" name="email" value="{{ old('email', $admin->email) }}" required class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"></div>
<div><label class="block text-sm font-medium text-slate-700 mb-1">Password (kosongkan jika tidak diubah)</label><input type="password" name="password" class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"></div>
<div><label class="block text-sm font-medium text-slate-700 mb-2">Assign ke Ormawa</label><div class="space-y-2 max-h-48 overflow-y-auto border border-slate-200 rounded-xl p-3">@foreach($ormawas as $o)<label class="flex items-center gap-2 cursor-pointer p-2 hover:bg-slate-50 rounded-lg"><input type="checkbox" name="ormawa_ids[]" value="{{ $o->id }}" {{ in_array($o->id, old('ormawa_ids', $assignedOrmawaIds)) ? 'checked' : '' }} class="w-4 h-4 rounded border-slate-300 text-blue-600"><span class="text-sm text-slate-700">{{ $o->nama }}</span></label>@endforeach</div></div>
<div class="flex gap-3 pt-2"><button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-xl shadow-lg shadow-blue-500/25">Update</button><a href="{{ route('superadmin.admin.index') }}" class="px-6 py-2.5 bg-slate-100 text-slate-700 font-medium rounded-xl hover:bg-slate-200">Batal</a></div>
</form></div></div>
@endsection