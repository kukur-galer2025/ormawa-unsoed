<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Fakultas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class PimpinanManagementController extends Controller
{
    private const VALID_ROLES = ['wakil_rektor', 'wakil_dekan'];

    public function index()
    {
        $pimpinans = User::whereIn('role', self::VALID_ROLES)
            ->with('fakultas')
            ->latest()
            ->paginate(15);

        return view('superadmin.pimpinan-management.index', compact('pimpinans'));
    }

    public function create()
    {
        $fakultas = Fakultas::orderBy('nama_fakultas')->get();
        return view('superadmin.pimpinan-management.create', compact('fakultas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email',
            'password'    => 'required|string|min:8|confirmed',
            'role'        => ['required', Rule::in(self::VALID_ROLES)],
            'fakultas_id' => [
                Rule::requiredIf($request->role === 'wakil_dekan'),
                'nullable',
                'exists:fakultas,id',
            ],
        ], [
            'fakultas_id.required' => 'Fakultas wajib dipilih untuk Wakil Dekan.',
        ]);

        DB::transaction(function () use ($request) {
            User::create([
                'name'        => $request->name,
                'email'       => $request->email,
                'password'    => Hash::make($request->password),
                'role'        => $request->role,
                'is_active'   => true,
                'fakultas_id' => $request->role === 'wakil_dekan' ? $request->fakultas_id : null,
            ]);
        });

        return redirect()->route('superadmin.pimpinan.index')
            ->with('success', 'Akun pimpinan berhasil dibuat.');
    }

    public function edit(User $pimpinan)
    {
        abort_if(!in_array($pimpinan->role, self::VALID_ROLES), 404);

        $fakultas = Fakultas::orderBy('nama_fakultas')->get();
        return view('superadmin.pimpinan-management.edit', compact('pimpinan', 'fakultas'));
    }

    public function update(Request $request, User $pimpinan)
    {
        abort_if(!in_array($pimpinan->role, self::VALID_ROLES), 404);

        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => ['required', 'email', Rule::unique('users', 'email')->ignore($pimpinan->id)],
            'password'    => 'nullable|string|min:8|confirmed',
            'role'        => ['required', Rule::in(self::VALID_ROLES)],
            'fakultas_id' => [
                Rule::requiredIf($request->role === 'wakil_dekan'),
                'nullable',
                'exists:fakultas,id',
            ],
        ], [
            'fakultas_id.required' => 'Fakultas wajib dipilih untuk Wakil Dekan.',
        ]);

        DB::transaction(function () use ($request, $pimpinan) {
            $data = [
                'name'        => $request->name,
                'email'       => $request->email,
                'role'        => $request->role,
                'fakultas_id' => $request->role === 'wakil_dekan' ? $request->fakultas_id : null,
            ];

            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            $pimpinan->update($data);
        });

        return redirect()->route('superadmin.pimpinan.index')
            ->with('success', 'Akun pimpinan berhasil diperbarui.');
    }

    public function toggleActive(User $pimpinan)
    {
        abort_if(!in_array($pimpinan->role, self::VALID_ROLES), 404);
        $pimpinan->update(['is_active' => !$pimpinan->is_active]);
        $status = $pimpinan->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Akun pimpinan berhasil $status.");
    }

    public function destroy(User $pimpinan)
    {
        abort_if(!in_array($pimpinan->role, self::VALID_ROLES), 404);
        $pimpinan->delete();
        return redirect()->route('superadmin.pimpinan.index')
            ->with('success', 'Akun pimpinan berhasil dihapus.');
    }
}
