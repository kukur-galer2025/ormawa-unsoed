<?php

namespace App\Http\Controllers\Pimpinan;

use App\Http\Controllers\Controller;
use App\Models\Recruitment;
use Illuminate\Support\Facades\Auth;

class RecruitmentController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $recruitments = Recruitment::visibleTo($user)
            ->with(['ormawa', 'applications', 'divisions'])
            ->latest()
            ->paginate(12);

        return view('pimpinan.recruitment.index', compact('recruitments', 'user'));
    }

    public function show(Recruitment $recruitment)
    {
        $user = Auth::user();

        // Pastikan rekrutmen ini termasuk dalam scope user
        abort_unless(
            Recruitment::visibleTo($user)->where('id', $recruitment->id)->exists(),
            404
        );

        $recruitment->load([
            'ormawa',
            'divisions.profileMatchingResults.application.user.mahasiswaProfile',
            'divisions.applications.user.mahasiswaProfile',
            'applications',
        ]);

        return view('pimpinan.recruitment.show', compact('recruitment', 'user'));
    }
}
