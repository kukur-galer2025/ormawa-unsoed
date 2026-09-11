<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Recruitment;
use Illuminate\Http\Request;

class RecruitmentMonitoringController extends Controller
{
    public function index(Request $request)
    {
        $query = Recruitment::with(['ormawa', 'applications'])
            ->withCount('applications');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search by judul or ormawa name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%$search%")
                  ->orWhereHas('ormawa', fn($q2) => $q2->where('nama', 'like', "%$search%"));
            });
        }

        $recruitments = $query->latest()->paginate(15)->withQueryString();

        $stats = [
            'total' => Recruitment::count(),
            'dibuka' => Recruitment::where('status', 'dibuka')->count(),
            'ditutup' => Recruitment::where('status', 'ditutup')->count(),
            'selesai' => Recruitment::where('status', 'selesai')->count(),
        ];

        return view('superadmin.recruitment-monitoring.index', compact('recruitments', 'stats'));
    }
}
