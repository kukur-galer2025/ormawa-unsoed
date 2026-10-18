<?php

namespace Tests\Feature\BlackBox;

use App\Models\Application;
use App\Models\ApplicationScore;
use App\Models\Aspect;
use App\Models\Criteria;
use App\Models\Fakultas;
use App\Models\Jurusan;
use App\Models\MahasiswaProfile;
use App\Models\Ormawa;
use App\Models\OrmawaAdmin;
use App\Models\Recruitment;
use App\Models\RecruitmentDivision;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ScoringProfileMatchingTest extends TestCase
{
    use RefreshDatabase;

    private function createFullSetup(): array
    {
        $fakultas = Fakultas::create(['nama_fakultas' => 'Fakultas Teknik']);
        $jurusan = Jurusan::create([
            'fakultas_id' => $fakultas->id,
            'nama_jurusan' => 'Informatika',
        ]);

        $ormawa = Ormawa::create([
            'nama' => 'BEM FT Unsoed',
            'slug' => 'bem-ft-unsoed',
            'tingkat' => 'Fakultas',
            'fakultas_id' => $fakultas->id,
            'is_active' => true,
        ]);

        $admin = User::create([
            'name' => 'Admin BEM',
            'email' => 'admin.bem@unsoed.ac.id',
            'password' => Hash::make('Password123!'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        OrmawaAdmin::create([
            'user_id' => $admin->id,
            'ormawa_id' => $ormawa->id,
        ]);

        $recruitment = Recruitment::create([
            'ormawa_id' => $ormawa->id,
            'judul' => 'Open Recruitment Staff 2026',
            'tanggal_buka' => Carbon::now()->subDays(10),
            'tanggal_tutup' => Carbon::now()->subDays(1),
            'status' => 'ditutup',
        ]);

        $division = RecruitmentDivision::create([
            'recruitment_id' => $recruitment->id,
            'nama' => 'Divisi Kominfo',
            'kuota' => 2,
        ]);

        $aspect = Aspect::create([
            'recruitment_division_id' => $division->id,
            'nama' => 'Kompetensi Teknis',
            'bobot' => 100,
            'cf_percentage' => 60,
            'sf_percentage' => 40,
        ]);

        $criteriaCore = Criteria::create([
            'aspect_id' => $aspect->id,
            'nama_kriteria' => 'Skill Desain & Web',
            'tipe' => 'core',
            'target_value' => 4,
        ]);

        $criteriaSec = Criteria::create([
            'aspect_id' => $aspect->id,
            'nama_kriteria' => 'Komunikasi & Kerjasama',
            'tipe' => 'secondary',
            'target_value' => 3,
        ]);

        $mahasiswa = User::create([
            'name' => 'Budi Pelamar',
            'email' => 'budi@mhs.unsoed.ac.id',
            'password' => Hash::make('Password123!'),
            'role' => 'mahasiswa',
            'is_active' => true,
        ]);

        MahasiswaProfile::create([
            'user_id' => $mahasiswa->id,
            'nim' => 'H1D022001',
            'fakultas_id' => $fakultas->id,
            'jurusan_id' => $jurusan->id,
            'angkatan' => 2022,
        ]);

        $application = Application::create([
            'recruitment_id' => $recruitment->id,
            'recruitment_division_id' => $division->id,
            'user_id' => $mahasiswa->id,
            'motivasi' => 'Motivasi mendaftar kominfo',
            'status' => 'terkirim',
        ]);

        return [$admin, $recruitment, $division, $aspect, $criteriaCore, $criteriaSec, $application];
    }

    /**
     * PM-01: Admin menginput nilai pelamar dengan nilai integer 1-5 pada semua kriteria saat rekrutmen ditutup
     */
    public function test_PM_01(): void
    {
        [$admin, $recruitment, $division, $aspect, $c1, $c2, $application] = $this->createFullSetup();

        $response = $this->actingAs($admin)->post(route('admin.scoring.store', [$recruitment, $application]), [
            'scores' => [
                $c1->id => 4,
                $c2->id => 3,
            ],
            'catatan_wawancara' => 'Kandidat memiliki portofolio yang sangat baik.',
        ]);

        $response->assertRedirect();
        $this->assertEquals('Nilai pelamar berhasil disimpan.', session('success'));

        $this->assertDatabaseHas('nilai_pendaftaran', [
            'application_id' => $application->id,
            'criteria_id' => $c1->id,
            'actual_value' => 4,
        ]);
        $this->assertDatabaseHas('pendaftaran', [
            'id' => $application->id,
            'status' => 'diproses',
        ]);
    }

    /**
     * PM-02: Admin menginput nilai pelamar dengan mengosongkan salah satu kriteria
     */
    public function test_PM_02(): void
    {
        [$admin, $recruitment, $division, $aspect, $c1, $c2, $application] = $this->createFullSetup();

        $response = $this->actingAs($admin)
            ->from(route('admin.scoring.index', $recruitment))
            ->post(route('admin.scoring.store', [$recruitment, $application]), [
                'scores' => [
                    $c1->id => 4,
                    $c2->id => '', // Kosong
                ],
            ]);

        $response->assertRedirect(route('admin.scoring.index', $recruitment));
        $response->assertSessionHasErrors("scores.{$c2->id}");
    }

    /**
     * PM-03: Admin mencoba menginput nilai saat status rekrutmen masih "dibuka"
     */
    public function test_PM_03(): void
    {
        [$admin, $recruitment, $division, $aspect, $c1, $c2, $application] = $this->createFullSetup();

        // Rekrutmen masih dibuka dengan tanggal tutup di masa depan
        $recruitment->update([
            'status' => 'dibuka',
            'tanggal_tutup' => Carbon::now()->addDays(5),
        ]);

        $response = $this->actingAs($admin)
            ->from(route('admin.scoring.index', $recruitment))
            ->post(route('admin.scoring.store', [$recruitment, $application]), [
                'scores' => [
                    $c1->id => 4,
                    $c2->id => 3,
                ],
            ]);

        $response->assertRedirect(route('admin.scoring.index', $recruitment));
        $this->assertEquals('Input nilai hanya bisa dilakukan saat status rekrutmen sedang "ditutup".', session('error'));
    }

    /**
     * PM-04: Admin mencoba menginput nilai pada divisi yang sudah difinalisasi
     */
    public function test_PM_04(): void
    {
        [$admin, $recruitment, $division, $aspect, $c1, $c2, $application] = $this->createFullSetup();

        $division->update(['is_finalized' => true]);

        $response = $this->actingAs($admin)
            ->from(route('admin.scoring.index', $recruitment))
            ->post(route('admin.scoring.store', [$recruitment, $application]), [
                'scores' => [
                    $c1->id => 4,
                    $c2->id => 3,
                ],
            ]);

        $response->assertRedirect(route('admin.scoring.index', $recruitment));
        $this->assertEquals('Divisi ini sudah difinalisasi. Nilai tidak dapat diubah lagi.', session('error'));
    }

    /**
     * PM-05: Admin menjalankan kalkulasi Profile Matching pada divisi yang sudah siap
     */
    public function test_PM_05(): void
    {
        [$admin, $recruitment, $division, $aspect, $c1, $c2, $application] = $this->createFullSetup();

        // Isi nilai lengkap untuk pelamar
        ApplicationScore::create([
            'application_id' => $application->id,
            'criteria_id' => $c1->id,
            'actual_value' => 4,
        ]);
        ApplicationScore::create([
            'application_id' => $application->id,
            'criteria_id' => $c2->id,
            'actual_value' => 3,
        ]);

        $response = $this->actingAs($admin)
            ->post(route('admin.profile-matching.calculate', [$recruitment, $division]));

        $response->assertRedirect(route('admin.profile-matching.result', [$recruitment, $division]));
        $this->assertEquals('Kalkulasi Profile Matching untuk divisi "' . $division->nama . '" berhasil.', session('success'));

        $this->assertDatabaseHas('hasil_profile_matching', [
            'recruitment_division_id' => $division->id,
            'application_id' => $application->id,
            'ranking' => 1,
        ]);
    }

    /**
     * PM-06: Admin menjalankan kalkulasi PM saat ada pelamar yang belum dinilai lengkap
     */
    public function test_PM_06(): void
    {
        [$admin, $recruitment, $division] = $this->createFullSetup();

        // Belum ada nilai yang diinputkan untuk pelamar
        $response = $this->actingAs($admin)
            ->from(route('admin.profile-matching.show', $recruitment))
            ->post(route('admin.profile-matching.calculate', [$recruitment, $division]));

        $response->assertRedirect(route('admin.profile-matching.show', $recruitment));
        $this->assertStringContainsString('Perhitungan ditolak. Pelamar berikut belum dinilai', session('error'));
    }

    /**
     * PM-07: Admin menjalankan kalkulasi PM pada divisi dengan total bobot aspek != 100%
     */
    public function test_PM_07(): void
    {
        [$admin, $recruitment, $division, $aspect, $c1, $c2, $application] = $this->createFullSetup();

        // Ubah bobot aspek menjadi 80%
        $aspect->update(['bobot' => 80]);

        ApplicationScore::create([
            'application_id' => $application->id,
            'criteria_id' => $c1->id,
            'actual_value' => 4,
        ]);
        ApplicationScore::create([
            'application_id' => $application->id,
            'criteria_id' => $c2->id,
            'actual_value' => 3,
        ]);

        $response = $this->actingAs($admin)
            ->from(route('admin.profile-matching.show', $recruitment))
            ->post(route('admin.profile-matching.calculate', [$recruitment, $division]));

        $response->assertRedirect(route('admin.profile-matching.show', $recruitment));
        $this->assertStringContainsString('Total bobot semua aspek harus = 100%', session('error'));
    }

    /**
     * PM-08: Admin menjalankan kalkulasi PM pada aspek yang belum memiliki CF atau SF lengkap
     */
    public function test_PM_08(): void
    {
        [$admin, $recruitment, $division, $aspect, $c1, $c2, $application] = $this->createFullSetup();

        // Ubah kriteria secondary menjadi core, sehingga tidak ada secondary
        $c2->update(['tipe' => 'core']);

        ApplicationScore::create([
            'application_id' => $application->id,
            'criteria_id' => $c1->id,
            'actual_value' => 4,
        ]);
        ApplicationScore::create([
            'application_id' => $application->id,
            'criteria_id' => $c2->id,
            'actual_value' => 3,
        ]);

        $response = $this->actingAs($admin)
            ->from(route('admin.profile-matching.show', $recruitment))
            ->post(route('admin.profile-matching.calculate', [$recruitment, $division]));

        $response->assertRedirect(route('admin.profile-matching.show', $recruitment));
        $this->assertStringContainsString('belum lengkap. Setiap aspek wajib memiliki minimal 1 kriteria Core Factor (CF) dan 1 kriteria Secondary Factor (SF)', session('error'));
    }

    /**
     * PM-09: Admin menjalankan kalkulasi PM saat status rekrutmen masih "dibuka"
     */
    public function test_PM_09(): void
    {
        [$admin, $recruitment, $division, $aspect, $c1, $c2, $application] = $this->createFullSetup();

        // Rekrutmen masih dibuka dengan tanggal tutup di masa depan
        $recruitment->update([
            'status' => 'dibuka',
            'tanggal_tutup' => Carbon::now()->addDays(5),
        ]);

        ApplicationScore::create([
            'application_id' => $application->id,
            'criteria_id' => $c1->id,
            'actual_value' => 4,
        ]);
        ApplicationScore::create([
            'application_id' => $application->id,
            'criteria_id' => $c2->id,
            'actual_value' => 3,
        ]);

        $response = $this->actingAs($admin)
            ->from(route('admin.profile-matching.show', $recruitment))
            ->post(route('admin.profile-matching.calculate', [$recruitment, $division]));

        $response->assertRedirect(route('admin.profile-matching.show', $recruitment));
        $this->assertEquals(
            'Perhitungan ditolak. Rekrutmen masih berstatus "dibuka", perhitungan ranking hanya dapat dilakukan setelah status rekrutmen ditutup.',
            session('error')
        );
    }
}
