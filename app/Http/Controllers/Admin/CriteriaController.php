<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Traits\ChecksRecruitmentOwnership;
use App\Models\Aspect;
use App\Models\Criteria;
use App\Models\CriteriaValueLabel;
use App\Models\Recruitment;
use Illuminate\Http\Request;

class CriteriaController extends Controller
{
    use ChecksRecruitmentOwnership;
    public function index(Recruitment $recruitment)
    {
        $this->ensureRecruitmentOwnership($recruitment);

        $recruitment->load(['divisions.aspects.criteria.valueLabels']);
        return view('admin.criteria.index', compact('recruitment'));
    }

    public function create(Recruitment $recruitment)
    {
        $this->ensureRecruitmentOwnership($recruitment);

        $recruitment->load('divisions.aspects');
        return view('admin.criteria.create', compact('recruitment'));
    }

    public function store(Request $request, Recruitment $recruitment)
    {
        $this->ensureRecruitmentOwnership($recruitment);

        $request->validate([
            'aspect_id' => 'required|exists:aspek,id',
            'nama_kriteria' => 'required|string|max:255',
            'tipe' => 'required|in:core,secondary',
            'target_value' => 'required|integer|min:1|max:5',
            'keterangan' => 'nullable|string',
            'labels' => 'required|array|size:5',
            'labels.*' => 'required|string|max:255',
        ]);

        // Verify the aspect belongs to one of this recruitment's divisions
        $aspect = Aspect::findOrFail($request->aspect_id);
        $divisionIds = $recruitment->divisions()->pluck('id');
        if (!$divisionIds->contains($aspect->recruitment_division_id)) {
            abort(403, 'Aspek ini tidak termasuk dalam rekrutmen ini.');
        }

        $maxUrutan = $aspect->criteria()->max('urutan') ?? 0;

        $criteria = $aspect->criteria()->create([
            'nama_kriteria' => $request->nama_kriteria,
            'tipe' => $request->tipe,
            'target_value' => $request->target_value,
            'keterangan' => $request->keterangan,
            'urutan' => $maxUrutan + 1,
        ]);

        // Create value labels (1-5)
        foreach ($request->labels as $value => $label) {
            $criteria->valueLabels()->create([
                'value' => $value + 1,
                'label' => $label,
            ]);
        }

        return redirect()->route('admin.criteria.index', $recruitment)
            ->with('success', 'Kriteria berhasil ditambahkan.');
    }

    public function edit(Recruitment $recruitment, Criteria $criterion)
    {
        $this->ensureRecruitmentOwnership($recruitment);
        $this->ensureCriterionBelongsToRecruitment($recruitment, $criterion);

        $recruitment->load('divisions.aspects');
        $criterion->load('valueLabels');
        return view('admin.criteria.edit', compact('recruitment', 'criterion'));
    }

    public function update(Request $request, Recruitment $recruitment, Criteria $criterion)
    {
        $this->ensureRecruitmentOwnership($recruitment);
        $this->ensureCriterionBelongsToRecruitment($recruitment, $criterion);

        $request->validate([
            'aspect_id' => 'required|exists:aspek,id',
            'nama_kriteria' => 'required|string|max:255',
            'tipe' => 'required|in:core,secondary',
            'target_value' => 'required|integer|min:1|max:5',
            'keterangan' => 'nullable|string',
            'labels' => 'required|array|size:5',
            'labels.*' => 'required|string|max:255',
        ]);

        $criterion->update($request->only([
            'aspect_id', 'nama_kriteria', 'tipe', 'target_value', 'keterangan',
        ]));

        // Sync value labels
        $criterion->valueLabels()->delete();
        foreach ($request->labels as $value => $label) {
            $criterion->valueLabels()->create([
                'value' => $value + 1,
                'label' => $label,
            ]);
        }

        return redirect()->route('admin.criteria.index', $recruitment)
            ->with('success', 'Kriteria berhasil diperbarui.');
    }

    public function destroy(Recruitment $recruitment, Criteria $criterion)
    {
        $this->ensureRecruitmentOwnership($recruitment);
        $this->ensureCriterionBelongsToRecruitment($recruitment, $criterion);

        $criterion->delete();
        return redirect()->route('admin.criteria.index', $recruitment)
            ->with('success', 'Kriteria berhasil dihapus.');
    }
}