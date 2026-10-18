<?php

namespace Tests\Feature\BlackBox;

use App\Models\Application;
use App\Models\Fakultas;
use App\Models\Jurusan;
use App\Models\MahasiswaProfile;
use App\Models\Ormawa;
use App\Models\Recruitment;
use App\Models\RecruitmentDivision;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class RegistrationApplicationTest extends TestCase
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

        $recruitment = Recruitment::create([
            'ormawa_id' => $ormawa->id,
            'judul' => 'Open Recruitment Staff 2026',
            'tanggal_buka' => Carbon::now()->subDays(2),
            'tanggal_tutup' => Carbon::now()->addDays(5),
            'status' => 'dibuka',
        ]);

        $division = RecruitmentDivision::create([
            'recruitment_id' => $recruitment->id,
            'nama' => 'Divisi Kominfo',
            'kuota' => 5,
        ]);

        $mahasiswa = User::create([
            'name' => 'Budi Santoso',
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

        return [$recruitment, $division, $mahasiswa, $ormawa];
    }

    /**
     * PD-01: Pendaftaran dengan motivasi valid dan berkas PDF valid (<= 5MB)
     */
    public function test_PD_01(): void
    {
        Storage::fake('public');
        [$recruitment, $division, $mahasiswa, $ormawa] = $this->createSetup();

        $pdf = UploadedFile::fake()->create('cv.pdf', 1024, 'application/pdf');

        $response = $this->actingAs($mahasiswa)->post(route('mahasiswa.recruitment.apply', $recruitment), [
            'recruitment_division_id' => $division->id,
            'motivasi' => 'Saya bersemangat untuk berkontribusi memajukan divisi kominfo dengan kemampuan desain dan coding.',
            'berkas_pendukung' => $pdf,
        ]);

        $response->assertRedirect(route('mahasiswa.applications.history'));
        $this->assertEquals('Pendaftaran berhasil! Silakan tunggu proses seleksi.', session('success'));

        $this->assertDatabaseHas('pendaftaran', [
            'recruitment_id' => $recruitment->id,
            'recruitment_division_id' => $division->id,
            'user_id' => $mahasiswa->id,
            'status' => 'terkirim',
        ]);

        $app = Application::where('user_id', $mahasiswa->id)->first();
        $this->assertNotNull($app->berkas_pendukung);
        Storage::disk('public')->assertExists($app->berkas_pendukung);
    }

    /**
     * PD-02: Pendaftaran dengan motivasi valid tanpa unggah berkas (opsional)
     */
    public function test_PD_02(): void
    {
        [$recruitment, $division, $mahasiswa, $ormawa] = $this->createSetup();

        $response = $this->actingAs($mahasiswa)->post(route('mahasiswa.recruitment.apply', $recruitment), [
            'recruitment_division_id' => $division->id,
            'motivasi' => 'Saya ingin belajar berorganisasi dan mengasah kepemimpinan.',
        ]);

        $response->assertRedirect(route('mahasiswa.applications.history'));
        $this->assertEquals('Pendaftaran berhasil! Silakan tunggu proses seleksi.', session('success'));

        $this->assertDatabaseHas('pendaftaran', [
            'recruitment_id' => $recruitment->id,
            'recruitment_division_id' => $division->id,
            'user_id' => $mahasiswa->id,
            'berkas_pendukung' => null,
            'status' => 'terkirim',
        ]);
    }

    /**
     * PD-03: Pendaftaran dengan motivasi kosong
     */
    public function test_PD_03(): void
    {
        [$recruitment, $division, $mahasiswa, $ormawa] = $this->createSetup();

        $response = $this->actingAs($mahasiswa)
            ->from(route('mahasiswa.recruitment.show', $ormawa))
            ->post(route('mahasiswa.recruitment.apply', $recruitment), [
                'recruitment_division_id' => $division->id,
                'motivasi' => '',
            ]);

        $response->assertRedirect(route('mahasiswa.recruitment.show', $ormawa));
        $response->assertSessionHasErrors('motivasi');
    }

    /**
     * PD-04: Pendaftaran dengan format berkas bukan dokumen yang diizinkan (.exe)
     */
    public function test_PD_04(): void
    {
        [$recruitment, $division, $mahasiswa, $ormawa] = $this->createSetup();

        $invalidFile = UploadedFile::fake()->create('program.exe', 500, 'application/octet-stream');

        $response = $this->actingAs($mahasiswa)
            ->from(route('mahasiswa.recruitment.show', $ormawa))
            ->post(route('mahasiswa.recruitment.apply', $recruitment), [
                'recruitment_division_id' => $division->id,
                'motivasi' => 'Motivasi cukup panjang untuk mendaftar.',
                'berkas_pendukung' => $invalidFile,
            ]);

        $response->assertRedirect(route('mahasiswa.recruitment.show', $ormawa));
        $response->assertSessionHasErrors('berkas_pendukung');
    }

    /**
     * PD-05: Pendaftaran dengan ukuran berkas melebihi 5MB (> 5120 KB)
     */
    public function test_PD_05(): void
    {
        [$recruitment, $division, $mahasiswa, $ormawa] = $this->createSetup();

        $largeFile = UploadedFile::fake()->create('dokumen_besar.pdf', 6000, 'application/pdf'); // 6MB

        $response = $this->actingAs($mahasiswa)
            ->from(route('mahasiswa.recruitment.show', $ormawa))
            ->post(route('mahasiswa.recruitment.apply', $recruitment), [
                'recruitment_division_id' => $division->id,
                'motivasi' => 'Motivasi cukup panjang untuk mendaftar.',
                'berkas_pendukung' => $largeFile,
            ]);

        $response->assertRedirect(route('mahasiswa.recruitment.show', $ormawa));
        $response->assertSessionHasErrors('berkas_pendukung');
    }

    /**
     * PD-06: Pendaftaran ganda pada divisi yang sama
     */
    public function test_PD_06(): void
    {
        [$recruitment, $division, $mahasiswa, $ormawa] = $this->createSetup();

        // Pendaftaran pertama sudah ada
        Application::create([
            'recruitment_id' => $recruitment->id,
            'recruitment_division_id' => $division->id,
            'user_id' => $mahasiswa->id,
            'motivasi' => 'Pendaftaran pertama',
            'status' => 'terkirim',
        ]);

        $response = $this->actingAs($mahasiswa)
            ->from(route('mahasiswa.recruitment.show', $ormawa))
            ->post(route('mahasiswa.recruitment.apply', $recruitment), [
                'recruitment_division_id' => $division->id,
                'motivasi' => 'Mencoba mendaftar kedua kali',
            ]);

        $response->assertRedirect(route('mahasiswa.recruitment.show', $ormawa));
        $this->assertEquals('Anda sudah mendaftar pada divisi ini.', session('error'));
    }

    /**
     * PD-07: Pendaftaran pada rekrutmen yang sudah ditutup
     */
    public function test_PD_07(): void
    {
        [$recruitment, $division, $mahasiswa, $ormawa] = $this->createSetup();

        // Ubah rekrutmen menjadi ditutup
        $recruitment->update([
            'status' => 'ditutup',
            'tanggal_tutup' => Carbon::now()->subDay(),
        ]);

        $response = $this->actingAs($mahasiswa)
            ->from(route('mahasiswa.recruitment.show', $ormawa))
            ->post(route('mahasiswa.recruitment.apply', $recruitment), [
                'recruitment_division_id' => $division->id,
                'motivasi' => 'Mendaftar saat rekrutmen sudah ditutup',
            ]);

        $response->assertRedirect(route('mahasiswa.recruitment.show', $ormawa));
        $this->assertEquals('Pendaftaran untuk rekrutmen ini sudah ditutup.', session('error'));
    }
}
