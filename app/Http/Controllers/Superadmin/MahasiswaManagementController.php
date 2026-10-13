<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Fakultas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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

        if ($request->filled('fakultas_id')) {
            $query->whereHas('mahasiswaProfile', function ($q) use ($request) {
                $q->where('fakultas_id', $request->fakultas_id);
            });
        }

        if ($request->filled('jurusan_id')) {
            $query->whereHas('mahasiswaProfile', function ($q) use ($request) {
                $q->where('jurusan_id', $request->jurusan_id);
            });
        }

        $mahasiswas = $query->latest()->paginate(10)->withQueryString();
        $fakultas = Fakultas::with('jurusans')->get();
        return view('superadmin.mahasiswa-management.index', compact('mahasiswas', 'fakultas'));
    }

    public function show(User $mahasiswa)
    {
        abort_if($mahasiswa->role !== 'mahasiswa', 404);
        $mahasiswa->load([
            'mahasiswaProfile.fakultasRel',
            'mahasiswaProfile.jurusanRel',
            'applications.recruitment.ormawa',
            'applications.division',
        ]);
        return view('superadmin.mahasiswa-management.show', compact('mahasiswa'));
    }

    public function toggle(User $mahasiswa)
    {
        abort_if($mahasiswa->role !== 'mahasiswa', 404);
        
        $newStatus = !$mahasiswa->is_active;
        $mahasiswa->update(['is_active' => $newStatus]);
        
        $status = $newStatus ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Akun mahasiswa berhasil $status.");
    }

    public function resetPassword(Request $request, User $mahasiswa)
    {
        abort_if($mahasiswa->role !== 'mahasiswa', 404);
        
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $mahasiswa->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password mahasiswa berhasil direset.');
    }
}