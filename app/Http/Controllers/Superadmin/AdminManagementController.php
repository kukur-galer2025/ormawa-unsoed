<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Ormawa;
use App\Models\OrmawaAdmin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminManagementController extends Controller
{
    public function index()
    {
        $admins = User::where('role', 'admin')
            ->with('ormawas')
            ->latest()
            ->paginate(10);
        return view('superadmin.admin-management.index', compact('admins'));
    }

    public function create()
    {
        $ormawas = Ormawa::where('is_active', true)->get();
        return view('superadmin.admin-management.create', compact('ormawas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'ormawa_ids' => 'required|array|min:1',
            'ormawa_ids.*' => 'exists:ormawas,id',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin',
        ]);

        foreach ($request->ormawa_ids as $ormawaId) {
            OrmawaAdmin::create([
                'user_id' => $user->id,
                'ormawa_id' => $ormawaId,
            ]);
        }

        return redirect()->route('superadmin.admin.index')->with('success', 'Akun admin berhasil dibuat.');
    }

    public function edit(User $admin)
    {
        $ormawas = Ormawa::where('is_active', true)->get();
        $assignedOrmawaIds = $admin->ormawas->pluck('id')->toArray();
        return view('superadmin.admin-management.edit', compact('admin', 'ormawas', 'assignedOrmawaIds'));
    }

    public function update(Request $request, User $admin)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $admin->id,
            'password' => 'nullable|string|min:8',
            'ormawa_ids' => 'required|array|min:1',
            'ormawa_ids.*' => 'exists:ormawas,id',
        ]);

        $admin->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password ? Hash::make($request->password) : $admin->password,
        ]);

        // Sync ormawa assignments
        OrmawaAdmin::where('user_id', $admin->id)->delete();
        foreach ($request->ormawa_ids as $ormawaId) {
            OrmawaAdmin::create(['user_id' => $admin->id, 'ormawa_id' => $ormawaId]);
        }

        return redirect()->route('superadmin.admin.index')->with('success', 'Akun admin berhasil diperbarui.');
    }

    public function toggleActive(User $admin)
    {
        $admin->update(['is_active' => !$admin->is_active]);
        $status = $admin->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Akun admin berhasil $status.");
    }
}