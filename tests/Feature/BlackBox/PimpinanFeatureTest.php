<?php

namespace Tests\Feature\BlackBox;

use App\Models\Application;
use App\Models\Fakultas;
use App\Models\MahasiswaProfile;
use App\Models\Ormawa;
use App\Models\Recruitment;
use App\Models\RecruitmentDivision;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Mockery;
use Tests\TestCase;

class PimpinanFeatureTest extends TestCase
{
    use RefreshDatabase;

    private function createPimpinanData(): array
    {
        $fakultasTeknik = Fakultas::create(['nama_fakultas' => 'Fakultas Teknik']);
        $fakultasEkonomi = Fakultas::create(['nama_fakultas' => 'Fakultas Ekonomi dan Bisnis']);

        $warek = User::create([
            'name' => 'Prof. Dr. Wakil Rektor',
            'email' => 'warek@unsoed.ac.id',
            'password' => Hash::make('Password123!'),
            'role' => 'wakil_rektor',
            'is_active' => true,
        ]);

        $wadekTeknik = User::create([
            'name' => 'Dr. Wakil Dekan Teknik',
            'email' => 'wadek.ft@unsoed.ac.id',
            'password' => Hash::make('Password123!'),
            'role' => 'wakil_dekan',
            'fakultas_id' => $fakultasTeknik->id,
            'is_active' => true,
        ]);

        $ormawaUniv = Ormawa::create([
            'nama' => 'BEM Unsoed',
            'slug' => 'bem-unsoed',
            'tingkat' => 'Universitas',
            'is_active' => true,
        ]);

        $ormawaFT = Ormawa::create([
            'nama' => 'BEM FT Unsoed',
            'slug' => 'bem-ft',
            'tingkat' => 'Fakultas',
            'fakultas_id' => $fakultasTeknik->id,
            'is_active' => true,
        ]);

        $ormawaFEB = Ormawa::create([
            'nama' => 'BEM FEB Unsoed',
            'slug' => 'bem-feb',
            'tingkat' => 'Fakultas',
            'fakultas_id' => $fakultasEkonomi->id,
            'is_active' => true,
        ]);

        $recruitmentFT = Recruitment::create([
            'ormawa_id' => $ormawaFT->id,
            'judul' => 'Rekrutmen BEM FT 2026',
            'tanggal_buka' => Carbon::now()->subDays(5),
            'tanggal_tutup' => Carbon::now()->addDays(5),
            'status' => 'dibuka',
        ]);

        $recruitmentFEB = Recruitment::create([
            'ormawa_id' => $ormawaFEB->id,
            'judul' => 'Rekrutmen BEM FEB 2026',
            'tanggal_buka' => Carbon::now()->subDays(5),
            'tanggal_tutup' => Carbon::now()->addDays(5),
            'status' => 'dibuka',
        ]);

        return [$warek, $wadekTeknik, $fakultasTeknik, $fakultasEkonomi, $ormawaUniv, $ormawaFT, $ormawaFEB, $recruitmentFT, $recruitmentFEB];
    }

    /**
     * PIM-01: Wakil Rektor login dengan email dan password valid
     */
    public function test_PIM_01(): void
    {
        [$warek] = $this->createPimpinanData();

        $response = $this->post('/login', [
            'email' => 'warek@unsoed.ac.id',
            'password' => 'Password123!',
        ]);

        $response->assertRedirect(route('pimpinan.dashboard'));
        $this->assertAuthenticatedAs($warek);
    }

    /**
     * PIM-02: Wakil Dekan login dengan email dan password valid
     */
    public function test_PIM_02(): void
    {
        [, $wadekTeknik] = $this->createPimpinanData();

        $response = $this->post('/login', [
            'email' => 'wadek.ft@unsoed.ac.id',
            'password' => 'Password123!',
        ]);

        $response->assertRedirect(route('pimpinan.dashboard'));
        $this->assertAuthenticatedAs($wadekTeknik);
    }

    /**
     * PIM-03: Akun pimpinan mencoba login menggunakan Google OAuth callback
     */
    public function test_PIM_03(): void
    {
        [$warek] = $this->createPimpinanData();

        $abstractUser = Mockery::mock('Laravel\Socialite\Two\User');
        $abstractUser->shouldReceive('getId')->andReturn('google-warek-id');
        $abstractUser->shouldReceive('getName')->andReturn('Prof. Dr. Wakil Rektor');
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

    /**
     * PIM-04: Wakil Rektor membuka daftar ormawa menampilkan seluruh tingkat
     */
    public function test_PIM_04(): void
    {
        [$warek, , , , $ormawaUniv, $ormawaFT, $ormawaFEB] = $this->createPimpinanData();

        $response = $this->actingAs($warek)->get(route('pimpinan.ormawa.index'));

        $response->assertOk();
        $response->assertSee($ormawaUniv->nama);
        $response->assertSee($ormawaFT->nama);
        $response->assertSee($ormawaFEB->nama);
    }

    /**
     * PIM-05: Wakil Rektor menerapkan filter tingkat pada daftar ormawa
     */
    public function test_PIM_05(): void
    {
        [$warek, , , , $ormawaUniv, $ormawaFT, $ormawaFEB] = $this->createPimpinanData();

        $response = $this->actingAs($warek)->get(route('pimpinan.ormawa.index', ['tingkat' => 'Fakultas']));

        $response->assertOk();
        $response->assertSee($ormawaFT->nama);
        $response->assertSee($ormawaFEB->nama);
        $response->assertDontSee($ormawaUniv->nama);
    }

    /**
     * PIM-06: Wakil Dekan membuka daftar ormawa hanya melihat fakultasnya
     */
    public function test_PIM_06(): void
    {
        [, $wadekTeknik, , , $ormawaUniv, $ormawaFT, $ormawaFEB] = $this->createPimpinanData();

        $response = $this->actingAs($wadekTeknik)->get(route('pimpinan.ormawa.index'));

        $response->assertOk();
        $response->assertSee($ormawaFT->nama);
        $response->assertDontSee($ormawaFEB->nama);
        $response->assertDontSee($ormawaUniv->nama);
    }

    /**
     * PIM-07: Wakil Dekan membuka detail ormawa fakultas lain dicegat 404
     */
    public function test_PIM_07(): void
    {
        [, $wadekTeknik, , , , , $ormawaFEB] = $this->createPimpinanData();

        $response = $this->actingAs($wadekTeknik)->get(route('pimpinan.ormawa.show', $ormawaFEB));

        $response->assertStatus(404);
    }

    /**
     * PIM-08: Wakil Dekan membuka detail rekrutmen fakultas lain dicegat 404
     */
    public function test_PIM_08(): void
    {
        [, $wadekTeknik, , , , , , , $recruitmentFEB] = $this->createPimpinanData();

        $response = $this->actingAs($wadekTeknik)->get(route('pimpinan.recruitment.show', $recruitmentFEB));

        $response->assertStatus(404);
    }

    /**
     * PIM-09: Wakil Dekan membuka ormawa tingkat Universitas dicegat 404
     */
    public function test_PIM_09(): void
    {
        [, $wadekTeknik, , , $ormawaUniv] = $this->createPimpinanData();

        $response = $this->actingAs($wadekTeknik)->get(route('pimpinan.ormawa.show', $ormawaUniv));

        $response->assertStatus(404);
    }

    /**
     * PIM-10: Pimpinan membuka detail rekrutmen selesai untuk monitoring secara read-only
     */
    public function test_PIM_10(): void
    {
        [$warek, , , , , $ormawaFT, , $recruitmentFT] = $this->createPimpinanData();

        $division = RecruitmentDivision::create([
            'recruitment_id' => $recruitmentFT->id,
            'nama' => 'Divisi Kaderisasi',
            'kuota' => 2,
            'is_finalized' => true,
        ]);

        $recruitmentFT->update(['status' => 'selesai', 'is_announced' => true]);

        $response = $this->actingAs($warek)->get(route('pimpinan.recruitment.show', $recruitmentFT));

        $response->assertOk();
        $response->assertSee('Divisi Kaderisasi');
        $response->assertSee('Rekrutmen BEM FT 2026');
        // Pastikan tidak ada tombol submit/simpan manipulasi form
        $response->assertDontSee('Simpan Nilai');
        $response->assertDontSee('Finalisasi Keputusan');
    }

    /**
     * PIM-11: Pimpinan mencoba mengakses panel operasional admin dicegat 403 Forbidden
     */
    public function test_PIM_11(): void
    {
        [$warek] = $this->createPimpinanData();

        $response = $this->actingAs($warek)->get('/admin/dashboard');

        $response->assertStatus(403);
    }

    /**
     * PIM-12: Pimpinan mencoba mengakses panel mahasiswa dicegat 403 Forbidden
     */
    public function test_PIM_12(): void
    {
        [$warek] = $this->createPimpinanData();

        $response = $this->actingAs($warek)->get('/mahasiswa/dashboard');

        $response->assertStatus(403);
    }

    /**
     * PIM-13: Pimpinan yang dinonaktifkan login ditolak
     */
    public function test_PIM_13(): void
    {
        $pimpinanNonaktif = User::create([
            'name' => 'Wadek Nonaktif',
            'email' => 'wadek.nonaktif@unsoed.ac.id',
            'password' => Hash::make('Password123!'),
            'role' => 'wakil_dekan',
            'is_active' => false,
        ]);

        $response = $this->from('/login')->post('/login', [
            'email' => 'wadek.nonaktif@unsoed.ac.id',
            'password' => 'Password123!',
        ]);

        $response->assertRedirect('/login');
        $this->assertGuest();
        $this->assertEquals('Akun Anda telah dinonaktifkan. Hubungi administrator.', session('error'));
    }
}
