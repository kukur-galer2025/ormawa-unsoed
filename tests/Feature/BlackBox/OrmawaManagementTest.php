<?php

namespace Tests\Feature\BlackBox;

use App\Models\Fakultas;
use App\Models\Ormawa;
use App\Models\OrmawaAdmin;
use App\Models\OrmawaPrestasi;
use App\Models\OrmawaProgramKerja;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class OrmawaManagementTest extends TestCase
{
    use RefreshDatabase;

    private function createAdminOrmawa(): array
    {
        $fakultas = Fakultas::create(['nama_fakultas' => 'Fakultas Teknik']);
        $ormawa = Ormawa::create([
            'nama' => 'Himpunan Mahasiswa Informatika',
            'slug' => 'hmif-unsoed',
            'tingkat' => 'Jurusan',
            'fakultas_id' => $fakultas->id,
            'is_active' => true,
        ]);

        $admin = User::create([
            'name' => 'Admin HMIF',
            'email' => 'admin.hmif@unsoed.ac.id',
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
     * ORM-01: Admin memperbarui profil ormawa dengan data valid
     */
    public function test_ORM_01(): void
    {
        Storage::fake('public');
        [$admin, $ormawa] = $this->createAdminOrmawa();

        $logo = UploadedFile::fake()->image('logo.png', 200, 200);

        $response = $this->actingAs($admin)->put(route('admin.ormawa-profile.update'), [
            'deskripsi' => 'Organisasi kemahasiswaan jurusan informatika.',
            'visi' => 'Menjadi himpunan yang progresif dan kontributif.',
            'misi' => 'Mengembangkan potensi akademik dan soft skill mahasiswa.',
            'kontak_email' => 'hmif@unsoed.ac.id',
            'kontak_instagram' => '@hmif_unsoed',
            'logo' => $logo,
        ]);

        $response->assertRedirect();
        $this->assertEquals('Profil ormawa berhasil diperbarui.', session('success'));

        $this->assertDatabaseHas('ormawa', [
            'id' => $ormawa->id,
            'deskripsi' => 'Organisasi kemahasiswaan jurusan informatika.',
            'kontak_instagram' => '@hmif_unsoed',
        ]);

        $ormawaFresh = $ormawa->fresh();
        $this->assertNotNull($ormawaFresh->logo);
        Storage::disk('public')->assertExists($ormawaFresh->logo);
    }

    /**
     * ORM-02: Admin menambahkan prestasi ormawa dengan judul dan tahun valid
     */
    public function test_ORM_02(): void
    {
        [$admin, $ormawa] = $this->createAdminOrmawa();

        $response = $this->actingAs($admin)->post(route('admin.prestasi.store'), [
            'judul' => 'Juara 1 Gemastik Divisi UX Design 2025',
            'deskripsi' => 'Meraih medali emas dalam kompetisi nasional.',
            'tahun' => 2025,
        ]);

        $response->assertRedirect(route('admin.prestasi.index'));
        $this->assertEquals('Prestasi berhasil ditambahkan.', session('success'));

        $this->assertDatabaseHas('prestasi_ormawa', [
            'ormawa_id' => $ormawa->id,
            'judul' => 'Juara 1 Gemastik Divisi UX Design 2025',
            'tahun' => 2025,
        ]);
    }

    /**
     * ORM-03: Admin menambahkan prestasi tanpa judul (judul kosong)
     */
    public function test_ORM_03(): void
    {
        [$admin] = $this->createAdminOrmawa();

        $response = $this->actingAs($admin)
            ->from(route('admin.prestasi.create'))
            ->post(route('admin.prestasi.store'), [
                'judul' => '',
                'deskripsi' => 'Deskripsi tanpa judul',
                'tahun' => 2025,
            ]);

        $response->assertRedirect(route('admin.prestasi.create'));
        $response->assertSessionHasErrors('judul');
    }

    /**
     * ORM-04: Admin menambahkan program kerja ormawa dengan nama valid
     */
    public function test_ORM_04(): void
    {
        [$admin, $ormawa] = $this->createAdminOrmawa();

        $response = $this->actingAs($admin)->post(route('admin.proker.store'), [
            'nama' => 'Informatics Championship 2026',
            'deskripsi' => 'Kompetisi tahunan bidang IT se-Jawa Tengah.',
        ]);

        $response->assertRedirect(route('admin.proker.index'));
        $this->assertEquals('Program Kerja berhasil ditambahkan.', session('success'));

        $this->assertDatabaseHas('proker_ormawa', [
            'ormawa_id' => $ormawa->id,
            'nama' => 'Informatics Championship 2026',
        ]);
    }

    /**
     * ORM-05: Admin menambahkan program kerja tanpa nama
     */
    public function test_ORM_05(): void
    {
        [$admin] = $this->createAdminOrmawa();

        $response = $this->actingAs($admin)
            ->from(route('admin.proker.create'))
            ->post(route('admin.proker.store'), [
                'nama' => '',
                'deskripsi' => 'Deskripsi proker tanpa nama',
            ]);

        $response->assertRedirect(route('admin.proker.create'));
        $response->assertSessionHasErrors('nama');
    }
}
