<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Aspect;
use App\Models\Recruitment;
use App\Models\RecruitmentDivision;
use Illuminate\Http\Request;

class AspectController extends Controller
{
    /**
     * Show aspects for a recruitment's division.
     */
    public function index(Recruitment $recruitment)
    {
        $recruitment->load(['divisions.aspects.criteria']);
        return view('admin.aspect.index', compact('recruitment'));
    }

    /**
     * Store a new aspect.
     */
    public function store(Request $request, Recruitment $recruitment)
    {
        $request->validate([
            'recruitment_division_id' => 'required|exists:divisi_rekrutmen,id',
            'nama' => 'required|string|max:255',
            'bobot' => 'required|numeric|min:1|max:100',
            'cf_percentage' => 'required|numeric|min:0|max:100',
            'sf_percentage' => 'required|numeric|min:0|max:100',
        ]);

        $cf = (float) $request->cf_percentage;
        $sf = (float) $request->sf_percentage;
        if (abs($cf + $sf - 100) > 0.01) {
            return back()->with('error', 'CF% + SF% harus = 100%.')->withInput();
        }

        $division = RecruitmentDivision::where('id', $request->recruitment_division_id)
            ->where('recruitment_id', $recruitment->id)
            ->firstOrFail();

        $currentTotalBobot = $division->aspects()->sum('bobot');
        $newBobotDecimal = $request->bobot / 100;

        if (($currentTotalBobot + $newBobotDecimal) > 1.001) {
            $sisa = round((1 - $currentTotalBobot) * 100);
            return back()->with('error', 'Total bobot aspek dalam divisi "' . $division->nama . '" tidak boleh melebihi 100%. Sisa bobot yang tersedia: ' . $sisa . '%')->withInput();
        }

        $maxUrutan = $division->aspects()->max('urutan') ?? 0;

        $division->aspects()->create([
            'nama' => $request->nama,
            'bobot' => $newBobotDecimal,
            'cf_percentage' => $cf,
            'sf_percentage' => $sf,
            'urutan' => $maxUrutan + 1,
        ]);

        return redirect()->route('admin.aspect.index', $recruitment)
            ->with('success', 'Aspek berhasil ditambahkan.');
    }

    /**
     * Update an aspect.
     */
    public function update(Request $request, Recruitment $recruitment, Aspect $aspect)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'bobot' => 'required|numeric|min:1|max:100',
            'cf_percentage' => 'required|numeric|min:0|max:100',
            'sf_percentage' => 'required|numeric|min:0|max:100',
        ]);

        $cf = (float) $request->cf_percentage;
        $sf = (float) $request->sf_percentage;
        if (abs($cf + $sf - 100) > 0.01) {
            return back()->with('error', 'CF% + SF% harus = 100%.')->withInput();
        }

        $division = $aspect->division;
        $currentTotalBobot = $division->aspects()->where('id', '!=', $aspect->id)->sum('bobot');
        $newBobotDecimal = $request->bobot / 100;

        if (($currentTotalBobot + $newBobotDecimal) > 1.001) {
            $sisa = round((1 - $currentTotalBobot) * 100);
            return back()->with('error', 'Total bobot aspek dalam divisi "' . $division->nama . '" tidak boleh melebihi 100%. Sisa bobot yang tersedia: ' . $sisa . '%')->withInput();
        }

        $aspect->update([
            'nama' => $request->nama,
            'bobot' => $newBobotDecimal,
            'cf_percentage' => $cf,
            'sf_percentage' => $sf,
        ]);

        return redirect()->route('admin.aspect.index', $recruitment)
            ->with('success', 'Aspek berhasil diperbarui.');
    }

    /**
     * Delete an aspect.
     */
    public function destroy(Recruitment $recruitment, Aspect $aspect)
    {
        $aspect->delete();
        return redirect()->route('admin.aspect.index', $recruitment)
            ->with('success', 'Aspek berhasil dihapus.');
    }
}
