<?php

namespace App\Http\Controllers\Admin\Traits;

use App\Models\Application;
use App\Models\Aspect;
use App\Models\Criteria;
use App\Models\Recruitment;
use App\Models\RecruitmentDivision;

/**
 * Trait ChecksRecruitmentOwnership
 *
 * Menyediakan method reusable untuk memverifikasi bahwa objek yang diakses
 * (Recruitment, Application, dll.) benar-benar milik ormawa admin yang login.
 * Mencegah celah IDOR (Insecure Direct Object Reference).
 */
trait ChecksRecruitmentOwnership
{
    /**
     * Ambil ormawa milik admin yang sedang login.
     */
    protected function getAdminOrmawa()
    {
        return auth()->user()->ormawas()->first();
    }

    /**
     * Pastikan rekrutmen ini milik ormawa admin yang login.
     * Jika tidak, langsung abort 403.
     */
    protected function ensureRecruitmentOwnership(Recruitment $recruitment): void
    {
        $ormawa = $this->getAdminOrmawa();

        if (!$ormawa || $recruitment->ormawa_id !== $ormawa->id) {
            abort(403, 'Anda tidak memiliki akses ke rekrutmen ini.');
        }
    }

    /**
     * Pastikan application ini benar-benar terkait dengan recruitment yang diberikan.
     * Mencegah kombinasi ID yang tidak nyambung (cross-entity tampering).
     */
    protected function ensureApplicationBelongsToRecruitment(
        Recruitment $recruitment,
        Application $application
    ): void {
        if ($application->recruitment_id !== $recruitment->id) {
            abort(404, 'Data pendaftaran tidak ditemukan dalam rekrutmen ini.');
        }
    }

    /**
     * Pastikan divisi ini benar-benar anak dari recruitment yang diberikan.
     */
    protected function ensureDivisionBelongsToRecruitment(
        Recruitment $recruitment,
        RecruitmentDivision $division
    ): void {
        if ($division->recruitment_id !== $recruitment->id) {
            abort(404, 'Divisi tidak ditemukan dalam rekrutmen ini.');
        }
    }

    /**
     * Pastikan aspek ini benar-benar anak dari salah satu divisi di recruitment yang diberikan.
     */
    protected function ensureAspectBelongsToRecruitment(
        Recruitment $recruitment,
        Aspect $aspect
    ): void {
        $divisionIds = $recruitment->divisions()->pluck('id');
        if (!$divisionIds->contains($aspect->recruitment_division_id)) {
            abort(404, 'Aspek tidak ditemukan dalam rekrutmen ini.');
        }
    }

    /**
     * Pastikan kriteria ini benar-benar anak dari salah satu aspek di recruitment yang diberikan.
     */
    protected function ensureCriterionBelongsToRecruitment(
        Recruitment $recruitment,
        Criteria $criterion
    ): void {
        $divisionIds = $recruitment->divisions()->pluck('id');
        $aspectIds = Aspect::whereIn('recruitment_division_id', $divisionIds)->pluck('id');
        if (!$aspectIds->contains($criterion->aspect_id)) {
            abort(404, 'Kriteria tidak ditemukan dalam rekrutmen ini.');
        }
    }

    /**
     * Shortcut: cek ownership recruitment + cek application milik recruitment tsb.
     */
    protected function ensureFullOwnership(
        Recruitment $recruitment,
        Application $application
    ): void {
        $this->ensureRecruitmentOwnership($recruitment);
        $this->ensureApplicationBelongsToRecruitment($recruitment, $application);
    }
}

