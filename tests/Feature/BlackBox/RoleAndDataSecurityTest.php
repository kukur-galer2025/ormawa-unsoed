<?php

namespace Tests\Feature\BlackBox;

use App\Models\Fakultas;
use App\Models\Ormawa;
use App\Models\OrmawaAdmin;
use App\Models\OrmawaPrestasi;
use App\Models\Recruitment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RoleAndDataSecurityTest extends TestCase
{
    use RefreshDatabase;

    private function createOrmawaWithAdmin(string $nama, string $slug, string $email): array
    {
        $fakultas = Fakultas::create(['nama_fakultas' => 'Fakultas ' . $nama]);
        $ormawa = Ormawa::create([
            'nama' => $nama,
            'slug' => $slug,
            'tingkat' => 'Fakultas',
            'fakultas_id' => $fakultas->id,
            'is_active' => true,
        ]);

        $admin = User::create([
            'name' => 'Admin ' . $nama,
            'email' => $email,
            'password' => Hash::make('Password123!'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        OrmawaAdmin::create([
            'user_id' => $admin->id,
            'ormawa_id' => $ormawa->id,
        ]);

        return [$admin, $ormawa];
    }

    /**
     * SEC-01: Tamu (guest) mengakses dashboard terlindungi dialihkan ke halaman login
     */
    public function test_SEC_01(): void
    {
        $response1 = $this->get('/admin/dashboard');
        $response1->assertRedirect(route('login'));

        $response2 = $this->get('/superadmin/dashboard');
        $response2->assertRedirect(route('login'));

        $response3 = $this->get('/mahasiswa/dashboard');
        $response3->assertRedirect(route('login'));
    }

    /**
     * SEC-02: Mahasiswa mencoba membuka dashboard admin dicegat dengan 403 Forbidden
     */
    public function test_SEC_02(): void
    {
        $mahasiswa = User::create([
            'name' => 'Mahasiswa Biasa',
            'email' => 'mhs@mhs.unsoed.ac.id',
            'password' => Hash::make('Password123!'),
            'role' => 'mahasiswa',
            'is_active' => true,
        ]);

        $response = $this->actingAs($mahasiswa)->get('/admin/dashboard');

        $response->assertStatus(403);
    }

    /**
     * SEC-03: Mahasiswa mencoba membuka dashboard superadmin dicegat dengan 403 Forbidden
     */
    public function test_SEC_03(): void
    {
        $mahasiswa = User::create([
            'name' => 'Mahasiswa Biasa',
            'email' => 'mhs@mhs.unsoed.ac.id',
            'password' => Hash::make('Password123!'),
            'role' => 'mahasiswa',
            'is_active' => true,
        ]);

        $response = $this->actingAs($mahasiswa)->get('/superadmin/dashboard');

        $response->assertStatus(403);
    }

    /**
     * SEC-04: Admin Ormawa A mencoba mengakses rekrutmen milik Ormawa B dicegat dengan 403 Forbidden
     */
    public function test_SEC_04(): void
    {
        [$adminA, $ormawaA] = $this->createOrmawaWithAdmin('BEM FT', 'bem-ft', 'admin.bemft@unsoed.ac.id');
        [$adminB, $ormawaB] = $this->createOrmawaWithAdmin('BEM FEB', 'bem-feb', 'admin.bemfeb@unsoed.ac.id');

        $recruitmentB = Recruitment::create([
            'ormawa_id' => $ormawaB->id,
            'judul' => 'Rekrutmen Internal FEB',
            'tanggal_buka' => Carbon::now()->subDays(2),
            'tanggal_tutup' => Carbon::now()->addDays(5),
            'status' => 'dibuka',
        ]);

        // Admin A mencoba mengakses detail rekrutmen milik Ormawa B
        $response = $this->actingAs($adminA)->get(route('admin.recruitment.show', $recruitmentB));

        $response->assertStatus(403);
    }

    /**
     * SEC-05: Admin Ormawa A mencoba mengedit prestasi milik Ormawa B dicegat dengan 403 Forbidden
     */
    public function test_SEC_05(): void
    {
        [$adminA, $ormawaA] = $this->createOrmawaWithAdmin('BEM FT', 'bem-ft', 'admin.bemft@unsoed.ac.id');
        [$adminB, $ormawaB] = $this->createOrmawaWithAdmin('BEM FEB', 'bem-feb', 'admin.bemfeb@unsoed.ac.id');

        $prestasiB = OrmawaPrestasi::create([
            'ormawa_id' => $ormawaB->id,
            'judul' => 'Juara Debat Nasional FEB',
            'tahun' => 2025,
        ]);

        // Admin A mencoba mengedit prestasi Ormawa B
        $response = $this->actingAs($adminA)->get(route('admin.prestasi.edit', $prestasiB));

        $response->assertStatus(403);
    }

    /**
     * SEC-06: Admin tanpa ormawa assigned mengakses fitur operasional dialihkan ke dashboard
     */
    public function test_SEC_06(): void
    {
        // Admin tanpa entri di admin_ormawa
        $adminTanpaOrmawa = User::create([
            'name' => 'Admin Menggantung',
            'email' => 'admin.gantung@unsoed.ac.id',
            'password' => Hash::make('Password123!'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $response = $this->actingAs($adminTanpaOrmawa)->get(route('admin.recruitment.index'));

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertEquals('Anda belum di-assign ke organisasi manapun.', session('error'));
    }
}
