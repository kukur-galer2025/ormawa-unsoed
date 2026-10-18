<?php

namespace Tests\Feature\BlackBox;

use App\Models\Fakultas;
use App\Models\Jurusan;
use App\Models\Ormawa;
use App\Models\OrmawaAdmin;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SuperadminMasterTest extends TestCase
{
    use RefreshDatabase;

    private function createSuperadmin(): User
    {
        return User::create([
            'name' => 'Super Administrator',
            'email' => 'superadmin@unsoed.ac.id',
            'password' => Hash::make('Password123!'),
            'role' => 'superadmin',
            'is_active' => true,
        ]);
    }

    /**
     * MST-01: Super Admin menambahkan fakultas baru dengan nama valid
     */
    public function test_MST_01(): void
    {
        $superadmin = $this->createSuperadmin();

        $response = $this->actingAs($superadmin)->post(route('superadmin.fakultas.store'), [
            'nama_fakultas' => 'Fakultas Ilmu Budaya',
        ]);

        $response->assertRedirect(route('superadmin.fakultas.index'));
        $this->assertEquals('Fakultas berhasil ditambahkan.', session('success'));

        $this->assertDatabaseHas('fakultas', [
            'nama_fakultas' => 'Fakultas Ilmu Budaya',
        ]);
    }

    /**
     * MST-02: Super Admin menambahkan fakultas dengan nama yang sudah ada (duplikat)
     */
    public function test_MST_02(): void
    {
        $superadmin = $this->createSuperadmin();
        Fakultas::create(['nama_fakultas' => 'Fakultas Teknik']);

        $response = $this->actingAs($superadmin)
            ->from(route('superadmin.fakultas.create'))
            ->post(route('superadmin.fakultas.store'), [
                'nama_fakultas' => 'Fakultas Teknik',
            ]);

        $response->assertRedirect(route('superadmin.fakultas.create'));
        $response->assertSessionHasErrors('nama_fakultas');
    }

    /**
     * MST-03: Super Admin mencoba menghapus fakultas yang masih memiliki jurusan terhubung
     */
    public function test_MST_03(): void
    {
        $superadmin = $this->createSuperadmin();
        $fakultas = Fakultas::create(['nama_fakultas' => 'Fakultas Teknik']);
        Jurusan::create([
            'fakultas_id' => $fakultas->id,
            'nama_jurusan' => 'Teknik Elektro',
        ]);

        $response = $this->actingAs($superadmin)
            ->from(route('superadmin.fakultas.index'))
            ->delete(route('superadmin.fakultas.destroy', $fakultas));

        $response->assertRedirect(route('superadmin.fakultas.index'));
        $this->assertEquals('Tidak dapat menghapus fakultas karena masih memiliki jurusan terhubung.', session('error'));
        $this->assertDatabaseHas('fakultas', ['id' => $fakultas->id]);
    }

    /**
     * MST-04: Super Admin menambahkan jurusan baru dengan fakultas valid
     */
    public function test_MST_04(): void
    {
        $superadmin = $this->createSuperadmin();
        $fakultas = Fakultas::create(['nama_fakultas' => 'Fakultas Teknik']);

        $response = $this->actingAs($superadmin)->post(route('superadmin.jurusan.store'), [
            'fakultas_id' => $fakultas->id,
            'nama_jurusan' => 'Teknik Sipil',
        ]);

        $response->assertRedirect(route('superadmin.jurusan.index'));
        $this->assertEquals('Jurusan berhasil ditambahkan.', session('success'));

        $this->assertDatabaseHas('jurusan', [
            'fakultas_id' => $fakultas->id,
            'nama_jurusan' => 'Teknik Sipil',
        ]);
    }

    /**
     * MST-05: Super Admin menambahkan jurusan tanpa memilih fakultas
     */
    public function test_MST_05(): void
    {
        $superadmin = $this->createSuperadmin();

        $response = $this->actingAs($superadmin)
            ->from(route('superadmin.jurusan.create'))
            ->post(route('superadmin.jurusan.store'), [
                'fakultas_id' => '',
                'nama_jurusan' => 'Teknik Sipil',
            ]);

        $response->assertRedirect(route('superadmin.jurusan.create'));
        $response->assertSessionHasErrors('fakultas_id');
    }

    /**
     * MST-06: Super Admin membuat ormawa baru tingkat Universitas
     */
    public function test_MST_06(): void
    {
        $superadmin = $this->createSuperadmin();

        $response = $this->actingAs($superadmin)->post(route('superadmin.ormawa.store'), [
            'nama' => 'Unit Kegiatan Mahasiswa Robotika',
            'tingkat' => 'Universitas',
        ]);

        $response->assertRedirect(route('superadmin.ormawa.index'));
        $this->assertEquals('Ormawa berhasil ditambahkan.', session('success'));

        $this->assertDatabaseHas('ormawa', [
            'nama' => 'Unit Kegiatan Mahasiswa Robotika',
            'slug' => 'unit-kegiatan-mahasiswa-robotika',
            'tingkat' => 'Universitas',
            'fakultas_id' => null,
            'jurusan_id' => null,
        ]);
    }

    /**
     * MST-07: Super Admin membuat akun pimpinan (Wakil Rektor) valid
     */
    public function test_MST_07(): void
    {
        $superadmin = $this->createSuperadmin();

        $response = $this->actingAs($superadmin)->post(route('superadmin.pimpinan.store'), [
            'name' => 'Prof. Dr. Ir. Warek Kemahasiswaan',
            'email' => 'warek3@unsoed.ac.id',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'role' => 'wakil_rektor',
        ]);

        $response->assertRedirect(route('superadmin.pimpinan.index'));
        $this->assertDatabaseHas('users', [
            'email' => 'warek3@unsoed.ac.id',
            'role' => 'wakil_rektor',
            'fakultas_id' => null,
        ]);
    }

    /**
     * MST-08: Super Admin membuat akun Wakil Dekan tanpa memilih fakultas
     */
    public function test_MST_08(): void
    {
        $superadmin = $this->createSuperadmin();

        $response = $this->actingAs($superadmin)
            ->from(route('superadmin.pimpinan.create'))
            ->post(route('superadmin.pimpinan.store'), [
                'name' => 'Dr. Wadek Tanpa Fakultas',
                'email' => 'wadek@unsoed.ac.id',
                'password' => 'Password123!',
                'password_confirmation' => 'Password123!',
                'role' => 'wakil_dekan',
                'fakultas_id' => '', // Kosong padahal wadek
            ]);

        $response->assertRedirect(route('superadmin.pimpinan.create'));
        $response->assertSessionHasErrors(['fakultas_id' => 'Fakultas wajib dipilih untuk Wakil Dekan.']);
    }

    /**
     * MST-09: Super Admin membuat akun admin ormawa dan mengaitkannya ke ormawa
     */
    public function test_MST_09(): void
    {
        $superadmin = $this->createSuperadmin();
        $ormawa = Ormawa::create([
            'nama' => 'BEM Universitas Jenderal Soedirman',
            'slug' => 'bem-unsoed',
            'tingkat' => 'Universitas',
            'is_active' => true,
        ]);

        $response = $this->actingAs($superadmin)->post(route('superadmin.admin.store'), [
            'name' => 'Admin BEM Unsoed',
            'email' => 'admin.bemunsoed@unsoed.ac.id',
            'password' => 'Password123!',
            'ormawa_id' => $ormawa->id,
        ]);

        $response->assertRedirect(route('superadmin.admin.index'));
        $this->assertEquals('Akun admin berhasil dibuat.', session('success'));

        $this->assertDatabaseHas('users', [
            'email' => 'admin.bemunsoed@unsoed.ac.id',
            'role' => 'admin',
        ]);
        $createdUser = User::where('email', 'admin.bemunsoed@unsoed.ac.id')->first();
        $this->assertDatabaseHas('admin_ormawa', [
            'user_id' => $createdUser->id,
            'ormawa_id' => $ormawa->id,
        ]);
    }

    /**
     * MST-10: Super Admin mencoba menugaskan admin ke ormawa yang sudah memiliki admin
     */
    public function test_MST_10(): void
    {
        $superadmin = $this->createSuperadmin();
        $ormawa = Ormawa::create([
            'nama' => 'BEM Universitas Jenderal Soedirman',
            'slug' => 'bem-unsoed',
            'tingkat' => 'Universitas',
            'is_active' => true,
        ]);

        $existingAdmin = User::create([
            'name' => 'Admin Lama',
            'email' => 'admin.lama@unsoed.ac.id',
            'password' => Hash::make('Password123!'),
            'role' => 'admin',
        ]);
        OrmawaAdmin::create([
            'user_id' => $existingAdmin->id,
            'ormawa_id' => $ormawa->id,
        ]);

        $response = $this->actingAs($superadmin)
            ->from(route('superadmin.admin.create'))
            ->post(route('superadmin.admin.store'), [
                'name' => 'Admin Baru',
                'email' => 'admin.baru@unsoed.ac.id',
                'password' => 'Password123!',
                'ormawa_id' => $ormawa->id,
            ]);

        $response->assertRedirect(route('superadmin.admin.create'));
        $response->assertSessionHasErrors(['ormawa_id' => 'Organisasi ini sudah memiliki Admin.']);
    }

    /**
     * MST-11: Super Admin menonaktifkan akun admin ormawa (toggleActive)
     */
    public function test_MST_11(): void
    {
        $superadmin = $this->createSuperadmin();
        $admin = User::create([
            'name' => 'Admin Target',
            'email' => 'admin.target@unsoed.ac.id',
            'password' => Hash::make('Password123!'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $response = $this->actingAs($superadmin)
            ->patch(route('superadmin.admin.toggle', $admin));

        $response->assertRedirect();
        $this->assertEquals('Akun admin berhasil dinonaktifkan.', session('success'));
        $this->assertFalse((bool) $admin->fresh()->is_active);

        // Toggle kembali menjadi aktif
        $response2 = $this->actingAs($superadmin)
            ->patch(route('superadmin.admin.toggle', $admin));

        $response2->assertRedirect();
        $this->assertEquals('Akun admin berhasil diaktifkan.', session('success'));
        $this->assertTrue((bool) $admin->fresh()->is_active);
    }
}
