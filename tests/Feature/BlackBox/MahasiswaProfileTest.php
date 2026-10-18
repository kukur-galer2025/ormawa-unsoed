<?php

namespace Tests\Feature\BlackBox;

use App\Models\Fakultas;
use App\Models\Jurusan;
use App\Models\MahasiswaProfile;
use App\Models\Ormawa;
use App\Models\Recruitment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MahasiswaProfileTest extends TestCase
{
    use RefreshDatabase;

    private function createFakultasDanJurusan(): array
    {
        $fakultas = Fakultas::create(['nama_fakultas' => 'Fakultas Teknik']);
        $jurusan = Jurusan::create([
            'fakultas_id' => $fakultas->id,
            'nama_jurusan' => 'Informatika',
        ]);

        return [$fakultas, $jurusan];
    }

    /**
     * PRF-01: Update profil dengan data lengkap dan valid
     */
    public function test_PRF_01(): void
    {
        Storage::fake('public');
        [$fakultas, $jurusan] = $this->createFakultasDanJurusan();

        $user = User::create([
            'name' => 'Budi Awal',
            'email' => 'budi@mhs.unsoed.ac.id',
            'password' => Hash::make('Password123!'),
            'role' => 'mahasiswa',
            'is_active' => true,
        ]);

        $foto = UploadedFile::fake()->image('profil.jpg', 400, 400)->size(500); // 500 KB

        $response = $this->actingAs($user)->put(route('mahasiswa.profile.update'), [
            'name' => 'Budi Santoso',
            'nim' => 'H1D022001',
            'fakultas_id' => $fakultas->id,
            'jurusan_id' => $jurusan->id,
            'angkatan' => 2022,
            'no_hp' => '081234567890',
            'foto' => $foto,
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Budi Santoso',
        ]);
        $this->assertDatabaseHas('profil_mahasiswa', [
            'user_id' => $user->id,
            'nim' => 'H1D022001',
            'angkatan' => 2022,
        ]);

        $profile = MahasiswaProfile::where('user_id', $user->id)->first();
        $this->assertNotNull($profile->foto);
        Storage::disk('public')->assertExists($profile->foto);
        $response->assertRedirect(route('mahasiswa.dashboard'));
    }

    /**
     * PRF-02: Update profil dengan nama kosong
     */
    public function test_PRF_02(): void
    {
        [$fakultas, $jurusan] = $this->createFakultasDanJurusan();

        $user = User::create([
            'name' => 'Budi Awal',
            'email' => 'budi@mhs.unsoed.ac.id',
            'password' => Hash::make('Password123!'),
            'role' => 'mahasiswa',
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)
            ->from(route('mahasiswa.profile.edit'))
            ->put(route('mahasiswa.profile.update'), [
                'name' => '',
                'nim' => 'H1D022001',
                'fakultas_id' => $fakultas->id,
                'jurusan_id' => $jurusan->id,
                'angkatan' => 2022,
            ]);

        $response->assertRedirect(route('mahasiswa.profile.edit'));
        $response->assertSessionHasErrors(['name' => 'Nama lengkap wajib diisi.']);
    }

    /**
     * PRF-03: Update profil dengan format NIM salah (< 9 karakter)
     */
    public function test_PRF_03(): void
    {
        [$fakultas, $jurusan] = $this->createFakultasDanJurusan();

        $user = User::create([
            'name' => 'Budi Awal',
            'email' => 'budi@mhs.unsoed.ac.id',
            'password' => Hash::make('Password123!'),
            'role' => 'mahasiswa',
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)
            ->from(route('mahasiswa.profile.edit'))
            ->put(route('mahasiswa.profile.update'), [
                'name' => 'Budi Santoso',
                'nim' => 'H1D022', // hanya 6 karakter
                'fakultas_id' => $fakultas->id,
                'jurusan_id' => $jurusan->id,
                'angkatan' => 2022,
            ]);

        $response->assertRedirect(route('mahasiswa.profile.edit'));
        $response->assertSessionHasErrors('nim');
    }

    /**
     * PRF-04: Update profil dengan ukuran file foto melebihi 2MB (> 2048 KB)
     */
    public function test_PRF_04(): void
    {
        Storage::fake('public');
        [$fakultas, $jurusan] = $this->createFakultasDanJurusan();

        $user = User::create([
            'name' => 'Budi Awal',
            'email' => 'budi@mhs.unsoed.ac.id',
            'password' => Hash::make('Password123!'),
            'role' => 'mahasiswa',
            'is_active' => true,
        ]);

        $fotoBesar = UploadedFile::fake()->create('foto_besar.jpg', 2500, 'image/jpeg'); // 2.5 MB

        $response = $this->actingAs($user)
            ->from(route('mahasiswa.profile.edit'))
            ->put(route('mahasiswa.profile.update'), [
                'name' => 'Budi Santoso',
                'nim' => 'H1D022001',
                'fakultas_id' => $fakultas->id,
                'jurusan_id' => $jurusan->id,
                'angkatan' => 2022,
                'foto' => $fotoBesar,
            ]);

        $response->assertRedirect(route('mahasiswa.profile.edit'));
        $response->assertSessionHasErrors(['foto' => 'Ukuran foto maksimal 2MB.']);
    }

    /**
     * PRF-05: Akses fitur pendaftaran saat profil belum lengkap dicegat middleware
     */
    public function test_PRF_05(): void
    {
        [$fakultas, $jurusan] = $this->createFakultasDanJurusan();

        $user = User::create([
            'name' => 'Budi Belum Lengkap',
            'email' => 'budibaru@mhs.unsoed.ac.id',
            'password' => Hash::make('Password123!'),
            'role' => 'mahasiswa',
            'is_active' => true,
        ]);

        // Buat profil tidak lengkap (nim kosong)
        MahasiswaProfile::create([
            'user_id' => $user->id,
            'nim' => null,
            'fakultas_id' => null,
            'jurusan_id' => null,
        ]);

        // Mahasiswa mencoba mengakses history pendaftaran
        $response = $this->actingAs($user)->get(route('mahasiswa.applications.history'));

        $response->assertRedirect(route('mahasiswa.profile.edit'));
        $this->assertEquals(
            'Selamat Datang! Silakan lengkapi profil Anda (NIM, fakultas, jurusan) terlebih dahulu untuk mulai mendaftar ke organisasi pilihan Anda.',
            session('info')
        );
    }
}
