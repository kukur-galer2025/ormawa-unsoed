<?php

namespace Tests\Feature\BlackBox;

use App\Models\Fakultas;
use App\Models\Jurusan;
use App\Models\MahasiswaProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Mockery;
use Tests\TestCase;

class AuthenticationTest extends TestCase
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
     * AUT-01: Login dengan email dan password valid
     */
    public function test_AUT_01(): void
    {
        $user = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@mhs.unsoed.ac.id',
            'password' => Hash::make('Password123!'),
            'role' => 'mahasiswa',
            'is_active' => true,
        ]);

        $response = $this->post('/login', [
            'email' => 'budi@mhs.unsoed.ac.id',
            'password' => 'Password123!',
        ]);

        $response->assertRedirect(route('mahasiswa.dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    /**
     * AUT-02: Login dengan password salah
     */
    public function test_AUT_02(): void
    {
        User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@mhs.unsoed.ac.id',
            'password' => Hash::make('Password123!'),
            'role' => 'mahasiswa',
            'is_active' => true,
        ]);

        $response = $this->from('/login')->post('/login', [
            'email' => 'budi@mhs.unsoed.ac.id',
            'password' => 'SalahPassword123',
        ]);

        $response->assertRedirect('/login');
        $this->assertGuest();
        $this->assertEquals('Password yang Anda masukkan salah.', session('error'));
    }

    /**
     * AUT-03: Login dengan email tidak terdaftar
     */
    public function test_AUT_03(): void
    {
        $response = $this->from('/login')->post('/login', [
            'email' => 'tidakada@mhs.unsoed.ac.id',
            'password' => 'Password123!',
        ]);

        $response->assertRedirect('/login');
        $this->assertGuest();
        $this->assertEquals('Email tidak terdaftar di sistem.', session('error'));
    }

    /**
     * AUT-04: Login dengan format email tidak valid
     */
    public function test_AUT_04(): void
    {
        $response = $this->from('/login')->post('/login', [
            'email' => 'bukan-format-email',
            'password' => 'Password123!',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /**
     * AUT-05: Login dengan password kosong
     */
    public function test_AUT_05(): void
    {
        $response = $this->from('/login')->post('/login', [
            'email' => 'budi@mhs.unsoed.ac.id',
            'password' => '',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('password');
        $this->assertGuest();
    }

    /**
     * AUT-06: Login akun nonaktif
     */
    public function test_AUT_06(): void
    {
        User::create([
            'name' => 'User Nonaktif',
            'email' => 'nonaktif@mhs.unsoed.ac.id',
            'password' => Hash::make('Password123!'),
            'role' => 'mahasiswa',
            'is_active' => false,
        ]);

        $response = $this->from('/login')->post('/login', [
            'email' => 'nonaktif@mhs.unsoed.ac.id',
            'password' => 'Password123!',
        ]);

        $response->assertRedirect('/login');
        $this->assertGuest();
        $this->assertEquals('Akun Anda telah dinonaktifkan. Hubungi administrator.', session('error'));
    }

    /**
     * AUT-07: Registrasi mahasiswa dengan data valid
     */
    public function test_AUT_07(): void
    {
        [$fakultas, $jurusan] = $this->createFakultasDanJurusan();

        $response = $this->post('/register', [
            'name' => 'Ahmad Dahlan',
            'email' => 'ahmad@mhs.unsoed.ac.id',
            'nim' => 'H1D022099',
            'fakultas_id' => $fakultas->id,
            'jurusan_id' => $jurusan->id,
            'angkatan' => 2022,
            'no_hp' => '081234567890',
            'password' => 'Rahasia123!',
            'password_confirmation' => 'Rahasia123!',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'ahmad@mhs.unsoed.ac.id',
            'role' => 'mahasiswa',
        ]);
        $this->assertDatabaseHas('profil_mahasiswa', [
            'nim' => 'H1D022099',
            'angkatan' => 2022,
        ]);

        $response->assertRedirect(route('mahasiswa.dashboard'));
        $this->assertAuthenticated();
    }

    /**
     * AUT-08: Registrasi mahasiswa dengan email duplikat
     */
    public function test_AUT_08(): void
    {
        [$fakultas, $jurusan] = $this->createFakultasDanJurusan();

        User::create([
            'name' => 'User Lama',
            'email' => 'lama@mhs.unsoed.ac.id',
            'password' => Hash::make('Password123!'),
            'role' => 'mahasiswa',
        ]);

        $response = $this->from('/register')->post('/register', [
            'name' => 'User Baru',
            'email' => 'lama@mhs.unsoed.ac.id',
            'nim' => 'H1D022001',
            'fakultas_id' => $fakultas->id,
            'jurusan_id' => $jurusan->id,
            'angkatan' => 2022,
            'no_hp' => '081234567890',
            'password' => 'Rahasia123!',
            'password_confirmation' => 'Rahasia123!',
        ]);

        $response->assertRedirect('/register');
        $response->assertSessionHasErrors(['email' => 'Email ini sudah terdaftar.']);
    }

    /**
     * AUT-09: Registrasi mahasiswa dengan NIM duplikat
     */
    public function test_AUT_09(): void
    {
        [$fakultas, $jurusan] = $this->createFakultasDanJurusan();

        $existingUser = User::create([
            'name' => 'User Lama',
            'email' => 'lama@mhs.unsoed.ac.id',
            'password' => Hash::make('Password123!'),
            'role' => 'mahasiswa',
        ]);

        MahasiswaProfile::create([
            'user_id' => $existingUser->id,
            'nim' => 'H1D022099',
            'fakultas_id' => $fakultas->id,
            'jurusan_id' => $jurusan->id,
            'angkatan' => 2022,
        ]);

        $response = $this->from('/register')->post('/register', [
            'name' => 'User Baru',
            'email' => 'baru@mhs.unsoed.ac.id',
            'nim' => 'H1D022099',
            'fakultas_id' => $fakultas->id,
            'jurusan_id' => $jurusan->id,
            'angkatan' => 2022,
            'no_hp' => '081234567890',
            'password' => 'Rahasia123!',
            'password_confirmation' => 'Rahasia123!',
        ]);

        $response->assertRedirect('/register');
        $response->assertSessionHasErrors(['nim' => 'NIM ini sudah terdaftar.']);
    }

    /**
     * AUT-10: Registrasi mahasiswa dengan konfirmasi password tidak cocok
     */
    public function test_AUT_10(): void
    {
        [$fakultas, $jurusan] = $this->createFakultasDanJurusan();

        $response = $this->from('/register')->post('/register', [
            'name' => 'Ahmad Dahlan',
            'email' => 'ahmad@mhs.unsoed.ac.id',
            'nim' => 'H1D022099',
            'fakultas_id' => $fakultas->id,
            'jurusan_id' => $jurusan->id,
            'angkatan' => 2022,
            'password' => 'Rahasia123!',
            'password_confirmation' => 'BedaPassword123!',
        ]);

        $response->assertRedirect('/register');
        $response->assertSessionHasErrors(['password' => 'Konfirmasi kata sandi tidak cocok.']);
    }

    /**
     * AUT-11: Login Google akun mahasiswa
     */
    public function test_AUT_11(): void
    {
        $abstractUser = Mockery::mock('Laravel\Socialite\Two\User');
        $abstractUser->shouldReceive('getId')->andReturn('google-mhs-123');
        $abstractUser->shouldReceive('getName')->andReturn('Mahasiswa Google');
        $abstractUser->shouldReceive('getEmail')->andReturn('mhsgoogle@mhs.unsoed.ac.id');
        $abstractUser->shouldReceive('getAvatar')->andReturn('https://avatar.google/photo.jpg');

        $provider = Mockery::mock('Laravel\Socialite\Contracts\Provider');
        $provider->shouldReceive('user')->andReturn($abstractUser);

        Socialite::shouldReceive('driver')->with('google')->andReturn($provider);

        $response = $this->get('/auth/google/callback');

        $response->assertRedirect(route('mahasiswa.dashboard'));
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'mhsgoogle@mhs.unsoed.ac.id',
            'google_id' => 'google-mhs-123',
            'role' => 'mahasiswa',
        ]);
    }

    /**
     * AUT-12: Login Google akun pimpinan ditolak
     */
    public function test_AUT_12(): void
    {
        User::create([
            'name' => 'Dr. Pimpinan Warek',
            'email' => 'warek@unsoed.ac.id',
            'password' => Hash::make('Password123!'),
            'role' => 'wakil_rektor',
            'is_active' => true,
        ]);

        $abstractUser = Mockery::mock('Laravel\Socialite\Two\User');
        $abstractUser->shouldReceive('getId')->andReturn('google-warek-999');
        $abstractUser->shouldReceive('getName')->andReturn('Dr. Pimpinan Warek');
        $abstractUser->shouldReceive('getEmail')->andReturn('warek@unsoed.ac.id');
        $abstractUser->shouldReceive('getAvatar')->andReturn('https://avatar.google/warek.jpg');

        $provider = Mockery::mock('Laravel\Socialite\Contracts\Provider');
        $provider->shouldReceive('user')->andReturn($abstractUser);

        Socialite::shouldReceive('driver')->with('google')->andReturn($provider);

        $response = $this->get('/auth/google/callback');

        $response->assertRedirect(route('login'));
        $this->assertGuest();
        $this->assertEquals(
            'Akun pimpinan tidak dapat masuk menggunakan Google. Silakan gunakan email dan kata sandi.',
            session('error')
        );
    }
}
