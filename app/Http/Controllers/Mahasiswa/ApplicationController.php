<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\ApplicationScore;
use App\Models\Recruitment;
use App\Models\RecruitmentDivision;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    public function store(Request $request, Recruitment $recruitment)
    {
        $user = auth()->user();

        $request->validate([
            'recruitment_division_id' => 'required|exists:divisi_rekrutmen,id',
            'motivasi' => 'required|string|max:2000',
            'berkas_pendukung' => 'nullable|file|max:5120|mimes:pdf,doc,docx',
        ]);

        $division = RecruitmentDivision::where('id', $request->recruitment_division_id)
            ->where('recruitment_id', $recruitment->id)
            ->firstOrFail();

        // Check if already applied to this division
        if ($user->applications()->where('recruitment_division_id', $division->id)->exists()) {
            return back()->with('error', 'Anda sudah mendaftar pada divisi ini.');
        }

        // Check if recruitment is open (menggunakan method di Model, bukan duplikasi logika)
        if (!$recruitment->isOpen()) {
            return back()->with('error', $recruitment->closure_message ?? 'Rekrutmen ini tidak tersedia untuk pendaftaran.');
        }

        // Check if division quota is full
        if ($division->kuota > 0) {
            $acceptedCount = $division->applications()->where('status', 'diterima')->count();
            if ($acceptedCount >= $division->kuota) {
                return back()->with('error', 'Kuota divisi ini sudah penuh.');
            }
        }

        $data = [
            'recruitment_id' => $recruitment->id,
            'recruitment_division_id' => $division->id,
            'user_id' => $user->id,
            'motivasi' => $request->motivasi,
            'status' => 'pending',
        ];

        if ($request->hasFile('berkas_pendukung')) {
            $data['berkas_pendukung'] = $request->file('berkas_pendukung')->store('berkas-pendukung', 'public');
        }

        $application = Application::create($data);

        // Create empty score entries for each criteria in this division
        $criteria = $division->allCriteria()->get();
        foreach ($criteria as $c) {
            ApplicationScore::create([
                'application_id' => $application->id,
                'criteria_id' => $c->id,
            ]);
        }

        return redirect()->route('mahasiswa.applications.history')->with('success', 'Pendaftaran berhasil! Silakan tunggu proses seleksi.');
    }

    public function history()
    {
        $applications = auth()->user()->applications()
            ->with(['recruitment.ormawa', 'division', 'profileMatchingResult'])
            ->latest()
            ->paginate(10);

        return view('mahasiswa.application.history', compact('applications'));
    }
}