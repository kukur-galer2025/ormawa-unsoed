<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class MahasiswaManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'mahasiswa')->with('mahasiswaProfile');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
                  ->orWhereHas('mahasiswaProfile', function ($q2) use ($search) {
                      $q2->where('nim', 'like', "%$search%");
                  });
            });
        }

        $mahasiswas = $query->latest()->paginate(10)->withQueryString();
        return view('superadmin.mahasiswa-management.index', compact('mahasiswas'));
    }

    public function toggle(User $mahasiswa)
    {
        $mahasiswa->update(['is_active' => !$mahasiswa->is_active]);
        $status = $mahasiswa->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Akun mahasiswa berhasil $status.");
    }
}