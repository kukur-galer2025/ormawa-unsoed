<?php

namespace Tests\Feature\BlackBox;

use App\Models\Application;
use App\Models\Aspect;
use App\Models\Criteria;
use App\Models\Fakultas;
use App\Models\Jurusan;
use App\Models\MahasiswaProfile;
use App\Models\Ormawa;
use App\Models\OrmawaAdmin;
use App\Models\ProfileMatchingResult;
use App\Models\Recruitment;
use App\Models\RecruitmentDivision;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class FinalizationAnnouncementTest extends TestCase
{
    use RefreshDatabase;

    private function createSetup(): array
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
            'is_announced' => false,
        ]);

        $division = RecruitmentDivision::create([
            'recruitment_id' => $recruitment->id,
            'nama' => 'Divisi Kominfo',
            'kuota' => 1,
            'is_finalized' => false,
        ]);

        $mhs1 = User::create([
            'name' => 'Pelamar Satu',
            'email' => 'mhs1@mhs.unsoed.ac.id',
            'password' => Hash::make('Password123!'),
            'role' => 'mahasiswa',
            'is_active' => true,
        ]);
        MahasiswaProfile::create([
            'user_id' => $mhs1->id,
            'nim' => 'H1D022001',
            'fakultas_id' => $fakultas->id,
            'jurusan_id' => $jurusan->id,
            'angkatan' => 2022,
        ]);

        $mhs2 = User::create([
            'name' => 'Pelamar Dua',
            'email' => 'mhs2@mhs.unsoed.ac.id',
            'password' => Hash::make('Password123!'),
            'role' => 'mahasiswa',
            'is_active' => true,
        ]);
        MahasiswaProfile::create([
            'user_id' => $mhs2->id,
            'nim' => 'H1D022002',
            'fakultas_id' => $fakultas->id,
            'jurusan_id' => $jurusan->id,
            'angkatan' => 2022,
        ]);

        $app1 = Application::create([
            'recruitment_id' => $recruitment->id,
            'recruitment_division_id' => $division->id,
            'user_id' => $mhs1->id,
            'motivasi' => 'Motivasi mhs 1',
            'status' => 'diproses',
        ]);

        $app2 = Application::create([
            'recruitment_id' => $recruitment->id,
            'recruitment_division_id' => $division->id,
            'user_id' => $mhs2->id,
            'motivasi' => 'Motivasi mhs 2',
            'status' => 'diproses',
        ]);

        return [$admin, $recruitment, $division, $app1, $app2, $mhs1, $mhs2];
    }

    /**
     * FN-01: Admin memfinalisasi keputusan dengan memilih pelamar <= kuota divisi
     */
    public function test_FN_01(): void
    {
        [$admin, $recruitment, $division, $app1, $app2] = $this->createSetup();

        // Buat hasil profile matching
        ProfileMatchingResult::create([
            'recruitment_division_id' => $division->id,
            'application_id' => $app1->id,
            'total_score' => 4.5,
            'ranking' => 1,
        ]);
        ProfileMatchingResult::create([
            'recruitment_division_id' => $division->id,
            'application_id' => $app2->id,
            'total_score' => 3.8,
            'ranking' => 2,
        ]);

        $response = $this->actingAs($admin)->post(route('admin.profile-matching.finalize', [$recruitment, $division]), [
            'accepted_ids' => [$app1->id], // kuota = 1
        ]);

        $response->assertRedirect(route('admin.profile-matching.result', [$recruitment, $division]));
        $this->assertStringContainsString('telah difinalisasi. 1 pelamar diterima.', session('success'));

        $this->assertEquals('diterima', $app1->fresh()->status);
        $this->assertEquals('ditolak', $app2->fresh()->status);
        $this->assertTrue((bool) $division->fresh()->is_finalized);
    }

    /**
     * FN-02: Admin memfinalisasi keputusan dengan memilih jumlah pelamar melebihi kuota divisi
     */
    public function test_FN_02(): void
    {
        [$admin, $recruitment, $division, $app1, $app2] = $this->createSetup();

        ProfileMatchingResult::create([
            'recruitment_division_id' => $division->id,
            'application_id' => $app1->id,
            'total_score' => 4.5,
            'ranking' => 1,
        ]);
        ProfileMatchingResult::create([
            'recruitment_division_id' => $division->id,
            'application_id' => $app2->id,
            'total_score' => 3.8,
            'ranking' => 2,
        ]);

        // Kuota divisi = 1, namun dipilih 2
        $response = $this->actingAs($admin)
            ->from(route('admin.profile-matching.result', [$recruitment, $division]))
            ->post(route('admin.profile-matching.finalize', [$recruitment, $division]), [
                'accepted_ids' => [$app1->id, $app2->id],
            ]);

        $response->assertRedirect(route('admin.profile-matching.result', [$recruitment, $division]));
        $this->assertEquals('Jumlah yang dipilih melebihi kuota divisi (1).', session('error'));
        $this->assertFalse((bool) $division->fresh()->is_finalized);
    }

    /**
     * FN-03: Admin memfinalisasi keputusan sebelum kalkulasi Profile Matching dijalankan
     */
    public function test_FN_03(): void
    {
        [$admin, $recruitment, $division, $app1] = $this->createSetup();

        // Tidak ada data ProfileMatchingResult
        $response = $this->actingAs($admin)
            ->from(route('admin.profile-matching.result', [$recruitment, $division]))
            ->post(route('admin.profile-matching.finalize', [$recruitment, $division]), [
                'accepted_ids' => [$app1->id],
            ]);

        $response->assertRedirect(route('admin.profile-matching.result', [$recruitment, $division]));
        $this->assertEquals(
            'Hasil Profile Matching belum ada. Silakan jalankan perhitungan ranking terlebih dahulu sebelum melakukan finalisasi.',
            session('error')
        );
        $this->assertFalse((bool) $division->fresh()->is_finalized);
    }

    /**
     * FN-04: Admin mencoba memfinalisasi ulang divisi yang sudah difinalisasi
     */
    public function test_FN_04(): void
    {
        [$admin, $recruitment, $division, $app1] = $this->createSetup();

        $division->update(['is_finalized' => true]);

        $response = $this->actingAs($admin)
            ->from(route('admin.profile-matching.result', [$recruitment, $division]))
            ->post(route('admin.profile-matching.finalize', [$recruitment, $division]), [
                'accepted_ids' => [$app1->id],
            ]);

        $response->assertRedirect(route('admin.profile-matching.result', [$recruitment, $division]));
        $this->assertEquals('Divisi ini sudah difinalisasi sebelumnya.', session('error'));
    }

    /**
     * FN-05: Admin mengumumkan rekrutmen saat masih ada divisi yang belum difinalisasi
     */
    public function test_FN_05(): void
    {
        [$admin, $recruitment, $division] = $this->createSetup();

        // Ada divisi kedua yang belum difinalisasi
        RecruitmentDivision::create([
            'recruitment_id' => $recruitment->id,
            'nama' => 'Divisi Humas',
            'kuota' => 2,
            'is_finalized' => false,
        ]);

        $division->update(['is_finalized' => true]);

        $response = $this->actingAs($admin)
            ->from(route('admin.recruitment.show', $recruitment))
            ->post(route('admin.profile-matching.announce', $recruitment));

        $response->assertRedirect(route('admin.recruitment.show', $recruitment));
        $this->assertStringContainsString('belum difinalisasi. Selesaikan semua divisi terlebih dahulu.', session('error'));
        $this->assertFalse((bool) $recruitment->fresh()->is_announced);
    }

    /**
     * FN-06: Admin mengumumkan rekrutmen setelah SEMUA divisi difinalisasi
     */
    public function test_FN_06(): void
    {
        [$admin, $recruitment, $division] = $this->createSetup();

        $division->update(['is_finalized' => true]);

        $response = $this->actingAs($admin)
            ->from(route('admin.recruitment.show', $recruitment))
            ->post(route('admin.profile-matching.announce', $recruitment));

        $response->assertRedirect(route('admin.recruitment.show', $recruitment));
        $this->assertStringContainsString('telah diumumkan! Mahasiswa sekarang dapat melihat status kelulusannya.', session('success'));

        $freshRecruitment = $recruitment->fresh();
        $this->assertTrue((bool) $freshRecruitment->is_announced);
        $this->assertEquals('selesai', $freshRecruitment->status);
    }

    /**
     * FN-07: Mahasiswa melihat status pendaftaran di riwayat saat divisi sudah difinalisasi namun belum diumumkan
     */
    public function test_FN_07(): void
    {
        [$admin, $recruitment, $division, $app1, $app2, $mhs1] = $this->createSetup();

        // Divisi difinalisasi: app1 diterima, namun rekrutmen belum diumumkan
        $app1->update(['status' => 'diterima']);
        $division->update(['is_finalized' => true]);
        $recruitment->update(['is_announced' => false]);

        $response = $this->actingAs($mhs1)->get(route('mahasiswa.applications.history'));

        $response->assertOk();
        // Karena belum diumumkan, view harus menyamarkan menjadi DIPROSES agar tidak bocor
        $response->assertSee('DIPROSES');
        $response->assertDontSee('DITERIMA');
    }
}
