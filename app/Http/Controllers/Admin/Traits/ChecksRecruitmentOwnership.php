<?php

namespace App\Http\Controllers\Admin\Traits;

use App\Models\Application;
use App\Models\Recruitment;

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
