<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Traits\ChecksRecruitmentOwnership;
use App\Models\Aspect;
use App\Models\Recruitment;
use App\Models\RecruitmentDivision;
use Illuminate\Http\Request;

class AspectController extends Controller
{
    use ChecksRecruitmentOwnership;
    /**
     * Show aspects for a recruitment's division.
     */
    public function index(Recruitment $recruitment)
    {
        $this->ensureRecruitmentOwnership($recruitment);

        $recruitment->load(['divisions.aspects.criteria']);
        return view('admin.aspect.index', compact('recruitment'));
    }

    /**
     * Store a new aspect.
     */
    public function store(Request $request, Recruitment $recruitment)
    {
        $this->ensureRecruitmentOwnership($recruitment);

        $request->validate([
            'recruitment_division_id' => 'required|exists:divisi_rekrutmen,id',
            'nama' => 'required|string|max:255',
            'bobot' => 'required|numeric|min:0.01|max:100',
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

        // Bobot sekarang disimpan langsung sebagai persen (30.5 = 30.5%)
        $currentTotalBobot = $division->aspects()->sum('bobot');
        $newBobot = (float) $request->bobot;

        if (($currentTotalBobot + $newBobot) > 100.1) {
            $sisa = rtrim(rtrim(number_format(100 - $currentTotalBobot, 2), '0'), '.');
            return back()->with('error', 'Total bobot aspek dalam divisi "' . $division->nama . '" tidak boleh melebihi 100%. Sisa bobot yang tersedia: ' . $sisa . '%')->withInput();
        }

        $maxUrutan = $division->aspects()->max('urutan') ?? 0;

        $division->aspects()->create([
            'nama' => $request->nama,
            'bobot' => $newBobot,
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
        $this->ensureRecruitmentOwnership($recruitment);
        $this->ensureAspectBelongsToRecruitment($recruitment, $aspect);

        $request->validate([
            'nama' => 'required|string|max:255',
            'bobot' => 'required|numeric|min:0.01|max:100',
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
        $newBobot = (float) $request->bobot;

        if (($currentTotalBobot + $newBobot) > 100.1) {
            $sisa = rtrim(rtrim(number_format(100 - $currentTotalBobot, 2), '0'), '.');
            return back()->with('error', 'Total bobot aspek dalam divisi "' . $division->nama . '" tidak boleh melebihi 100%. Sisa bobot yang tersedia: ' . $sisa . '%')->withInput();
        }

        $aspect->update([
            'nama' => $request->nama,
            'bobot' => $newBobot,
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
        $this->ensureRecruitmentOwnership($recruitment);
        $this->ensureAspectBelongsToRecruitment($recruitment, $aspect);

        $aspect->delete();
        return redirect()->route('admin.aspect.index', $recruitment)
            ->with('success', 'Aspek berhasil dihapus.');
    }
}
