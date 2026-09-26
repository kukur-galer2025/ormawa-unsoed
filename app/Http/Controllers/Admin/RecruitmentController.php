<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Recruitment;
use App\Models\RecruitmentDivision;
use Illuminate\Http\Request;

use App\Http\Controllers\Admin\Traits\ChecksRecruitmentOwnership;

class RecruitmentController extends Controller
{
    use ChecksRecruitmentOwnership;


    public function index()
    {
        $ormawa = $this->getAdminOrmawa();
        $recruitments = $ormawa->recruitments()
            ->withCount(['applications', 'divisions'])
            ->latest()
            ->paginate(10);
        return view('admin.recruitment.index', compact('recruitments', 'ormawa'));
    }

    public function create()
    {
        return view('admin.recruitment.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'persyaratan' => 'nullable|string',
            'pesan_setelah_mendaftar' => 'nullable|string',
            'tanggal_buka' => 'required|date',
            'tanggal_tutup' => 'required|date|after:tanggal_buka',
            'divisions' => 'required|array|min:1',
            'divisions.*.nama' => 'required|string|max:255',
            'divisions.*.deskripsi' => 'nullable|string',
            'divisions.*.kuota' => 'required|integer|min:1',
        ]);

        $ormawa = $this->getAdminOrmawa();
        $recruitment = $ormawa->recruitments()->create($request->only([
            'judul', 'deskripsi', 'persyaratan', 'pesan_setelah_mendaftar', 'tanggal_buka', 'tanggal_tutup',
        ]));

        foreach ($request->divisions as $div) {
            $recruitment->divisions()->create([
                'nama' => $div['nama'],
                'deskripsi' => $div['deskripsi'] ?? null,
                'kuota' => $div['kuota'],
            ]);
        }

        return redirect()->route('admin.recruitment.index')->with('success', 'Rekrutmen berhasil dibuat.');
    }

    public function show(Recruitment $recruitment)
    {
        $this->ensureRecruitmentOwnership($recruitment);
        $recruitment->load(['divisions.aspects.criteria', 'divisions.applications', 'applications.user.mahasiswaProfile']);
        return view('admin.recruitment.show', compact('recruitment'));
    }

    public function edit(Recruitment $recruitment)
    {
        $this->ensureRecruitmentOwnership($recruitment);
        $recruitment->load('divisions');
        return view('admin.recruitment.edit', compact('recruitment'));
    }

    public function update(Request $request, Recruitment $recruitment)
    {
        $this->ensureRecruitmentOwnership($recruitment);

        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'persyaratan' => 'nullable|string',
            'pesan_setelah_mendaftar' => 'nullable|string',
            'tanggal_buka' => 'required|date',
            'tanggal_tutup' => 'required|date|after:tanggal_buka',
            'status' => 'required|in:draft,dibuka,ditutup,selesai',
            'divisions' => 'required|array|min:1',
            'divisions.*.id' => 'nullable|integer',
            'divisions.*.nama' => 'required|string|max:255',
            'divisions.*.deskripsi' => 'nullable|string',
            'divisions.*.kuota' => 'required|integer|min:1',
        ]);

        $recruitment->update($request->only([
            'judul', 'deskripsi', 'persyaratan', 'pesan_setelah_mendaftar', 'tanggal_buka', 'tanggal_tutup', 'status',
        ]));

        $existingIds = [];
        foreach ($request->divisions as $div) {
            if (!empty($div['id'])) {
                $recruitment->divisions()->where('id', $div['id'])->update([
                    'nama' => $div['nama'],
                    'deskripsi' => $div['deskripsi'] ?? null,
                    'kuota' => $div['kuota'],
                ]);
                $existingIds[] = $div['id'];
            } else {
                $newDiv = $recruitment->divisions()->create([
                    'nama' => $div['nama'],
                    'deskripsi' => $div['deskripsi'] ?? null,
                    'kuota' => $div['kuota'],
                ]);
                $existingIds[] = $newDiv->id;
            }
        }
        $recruitment->divisions()
            ->whereNotIn('id', $existingIds)
            ->doesntHave('applications')
            ->delete();

        return redirect()->route('admin.recruitment.index')->with('success', 'Rekrutmen berhasil diperbarui.');
    }

    public function destroy(Recruitment $recruitment)
    {
        $this->ensureRecruitmentOwnership($recruitment);
        $recruitment->delete();
        return redirect()->route('admin.recruitment.index')->with('success', 'Rekrutmen berhasil dihapus.');
    }
}