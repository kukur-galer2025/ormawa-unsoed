<?php

namespace Tests\Feature\BlackBox;

use App\Models\Aspect;
use App\Models\Fakultas;
use App\Models\Ormawa;
use App\Models\OrmawaAdmin;
use App\Models\Recruitment;
use App\Models\RecruitmentDivision;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RecruitmentManagementTest extends TestCase
{
    use RefreshDatabase;

    private function createAdminOrmawa(): array
    {
        $fakultas = Fakultas::create(['nama_fakultas' => 'Fakultas Teknik']);
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

        return [$admin, $ormawa];
    }

    /**
     * RK-01: Admin membuat rekrutmen baru dengan data valid
     */
    public function test_RK_01(): void
    {
        [$admin, $ormawa] = $this->createAdminOrmawa();

        $response = $this->actingAs($admin)->post(route('admin.recruitment.store'), [
            'judul' => 'Open Recruitment Pengurus 2026',
            'deskripsi' => 'Rekrutmen pengurus baru BEM FT 2026',
            'persyaratan' => 'Mahasiswa aktif fakultas teknik',
            'pesan_setelah_mendaftar' => 'Silakan bergabung ke grup WhatsApp panitia.',
            'tanggal_buka' => Carbon::now()->format('Y-m-d'),
            'tanggal_tutup' => Carbon::now()->addDays(14)->format('Y-m-d'),
            'divisions' => [
                [
                    'nama' => 'Divisi Kominfo',
                    'deskripsi' => 'Pengelolaan media dan informasi',
                    'kuota' => 5,
                ],
                [
                    'nama' => 'Divisi PSDM',
                    'deskripsi' => 'Pengembangan sumber daya mahasiswa',
                    'kuota' => 3,
                ],
            ],
        ]);

        $response->assertRedirect(route('admin.recruitment.index'));
        $this->assertEquals('Rekrutmen berhasil dibuat.', session('success'));

        $this->assertDatabaseHas('rekrutmen', [
            'ormawa_id' => $ormawa->id,
            'judul' => 'Open Recruitment Pengurus 2026',
        ]);
        $this->assertDatabaseHas('divisi_rekrutmen', [
            'nama' => 'Divisi Kominfo',
            'kuota' => 5,
        ]);
    }

    /**
     * RK-02: Admin membuat rekrutmen dengan judul kosong
     */
    public function test_RK_02(): void
    {
        [$admin] = $this->createAdminOrmawa();

        $response = $this->actingAs($admin)
            ->from(route('admin.recruitment.create'))
            ->post(route('admin.recruitment.store'), [
                'judul' => '',
                'tanggal_buka' => Carbon::now()->format('Y-m-d'),
                'tanggal_tutup' => Carbon::now()->addDays(14)->format('Y-m-d'),
                'divisions' => [
                    ['nama' => 'Divisi Acara', 'kuota' => 5],
                ],
            ]);

        $response->assertRedirect(route('admin.recruitment.create'));
        $response->assertSessionHasErrors(['judul' => 'Judul rekrutmen wajib diisi.']);
    }

    /**
     * RK-03: Admin membuat rekrutmen dengan tanggal tutup <= tanggal buka
     */
    public function test_RK_03(): void
    {
        [$admin] = $this->createAdminOrmawa();

        $response = $this->actingAs($admin)
            ->from(route('admin.recruitment.create'))
            ->post(route('admin.recruitment.store'), [
                'judul' => 'Rekrutmen Cacat Tanggal',
                'tanggal_buka' => '2026-05-10',
                'tanggal_tutup' => '2026-05-08',
                'divisions' => [
                    ['nama' => 'Divisi Acara', 'kuota' => 5],
                ],
            ]);

        $response->assertRedirect(route('admin.recruitment.create'));
        $response->assertSessionHasErrors(['tanggal_tutup' => 'Tanggal tutup harus setelah tanggal buka.']);
    }

    /**
     * RK-04: Admin membuat rekrutmen tanpa divisi
     */
    public function test_RK_04(): void
    {
        [$admin] = $this->createAdminOrmawa();

        $response = $this->actingAs($admin)
            ->from(route('admin.recruitment.create'))
            ->post(route('admin.recruitment.store'), [
                'judul' => 'Rekrutmen Tanpa Divisi',
                'tanggal_buka' => Carbon::now()->format('Y-m-d'),
                'tanggal_tutup' => Carbon::now()->addDays(14)->format('Y-m-d'),
                'divisions' => [],
            ]);

        $response->assertRedirect(route('admin.recruitment.create'));
        $response->assertSessionHasErrors('divisions');
    }

    /**
     * RK-05: Admin membuat rekrutmen dengan kuota divisi bernilai 0
     */
    public function test_RK_05(): void
    {
        [$admin] = $this->createAdminOrmawa();

        $response = $this->actingAs($admin)
            ->from(route('admin.recruitment.create'))
            ->post(route('admin.recruitment.store'), [
                'judul' => 'Rekrutmen Kuota Nol',
                'tanggal_buka' => Carbon::now()->format('Y-m-d'),
                'tanggal_tutup' => Carbon::now()->addDays(14)->format('Y-m-d'),
                'divisions' => [
                    ['nama' => 'Divisi Nol', 'kuota' => 0],
                ],
            ]);

        $response->assertRedirect(route('admin.recruitment.create'));
        $response->assertSessionHasErrors(['divisions.0.kuota' => 'Kuota divisi minimal bernilai 1.']);
    }

    /**
     * RK-06: Admin menambahkan aspek penilaian dengan bobot valid dan CF+SF = 100%
     */
    public function test_RK_06(): void
    {
        [$admin, $ormawa] = $this->createAdminOrmawa();

        $recruitment = Recruitment::create([
            'ormawa_id' => $ormawa->id,
            'judul' => 'Rekrutmen Aspek',
            'tanggal_buka' => Carbon::now()->subDays(2),
            'tanggal_tutup' => Carbon::now()->addDays(5),
            'status' => 'draft',
        ]);

        $division = RecruitmentDivision::create([
            'recruitment_id' => $recruitment->id,
            'nama' => 'Divisi Desain',
            'kuota' => 3,
        ]);

        $response = $this->actingAs($admin)->post(route('admin.aspect.store', $recruitment), [
            'recruitment_division_id' => $division->id,
            'nama' => 'Sikap Kerja',
            'bobot' => 40,
            'cf_percentage' => 60,
            'sf_percentage' => 40,
        ]);

        $response->assertRedirect(route('admin.aspect.index', $recruitment));
        $this->assertEquals('Aspek berhasil ditambahkan.', session('success'));

        $this->assertDatabaseHas('aspek', [
            'recruitment_division_id' => $division->id,
            'nama' => 'Sikap Kerja',
            'bobot' => 40,
            'cf_percentage' => 60,
            'sf_percentage' => 40,
        ]);
    }

    /**
     * RK-07: Admin menambahkan aspek dengan CF% + SF% != 100%
     */
    public function test_RK_07(): void
    {
        [$admin, $ormawa] = $this->createAdminOrmawa();

        $recruitment = Recruitment::create([
            'ormawa_id' => $ormawa->id,
            'judul' => 'Rekrutmen Aspek',
            'tanggal_buka' => Carbon::now()->subDays(2),
            'tanggal_tutup' => Carbon::now()->addDays(5),
            'status' => 'draft',
        ]);

        $division = RecruitmentDivision::create([
            'recruitment_id' => $recruitment->id,
            'nama' => 'Divisi Desain',
            'kuota' => 3,
        ]);

        $response = $this->actingAs($admin)
            ->from(route('admin.aspect.index', $recruitment))
            ->post(route('admin.aspect.store', $recruitment), [
                'recruitment_division_id' => $division->id,
                'nama' => 'Sikap Kerja',
                'bobot' => 40,
                'cf_percentage' => 60,
                'sf_percentage' => 50, // total 110%
            ]);

        $response->assertRedirect(route('admin.aspect.index', $recruitment));
        $this->assertEquals('CF% + SF% harus = 100%.', session('error'));
    }

    /**
     * RK-08: Admin menambahkan aspek dengan total bobot melebihi 100%
     */
    public function test_RK_08(): void
    {
        [$admin, $ormawa] = $this->createAdminOrmawa();

        $recruitment = Recruitment::create([
            'ormawa_id' => $ormawa->id,
            'judul' => 'Rekrutmen Aspek',
            'tanggal_buka' => Carbon::now()->subDays(2),
            'tanggal_tutup' => Carbon::now()->addDays(5),
            'status' => 'draft',
        ]);

        $division = RecruitmentDivision::create([
            'recruitment_id' => $recruitment->id,
            'nama' => 'Divisi Desain',
            'kuota' => 3,
        ]);

        // Sudah ada aspek dengan bobot 80%
        Aspect::create([
            'recruitment_division_id' => $division->id,
            'nama' => 'Aspek Pertama',
            'bobot' => 80,
            'cf_percentage' => 60,
            'sf_percentage' => 40,
        ]);

        // Tambah aspek 30% (total 110%)
        $response = $this->actingAs($admin)
            ->from(route('admin.aspect.index', $recruitment))
            ->post(route('admin.aspect.store', $recruitment), [
                'recruitment_division_id' => $division->id,
                'nama' => 'Aspek Kedua',
                'bobot' => 30,
                'cf_percentage' => 50,
                'sf_percentage' => 50,
            ]);

        $response->assertRedirect(route('admin.aspect.index', $recruitment));
        $this->assertStringContainsString('tidak boleh melebihi 100%', session('error'));
    }

    /**
     * RK-09: Admin menambahkan kriteria penilaian valid (target 1-5, 5 label nilai)
     */
    public function test_RK_09(): void
    {
        [$admin, $ormawa] = $this->createAdminOrmawa();

        $recruitment = Recruitment::create([
            'ormawa_id' => $ormawa->id,
            'judul' => 'Rekrutmen Kriteria',
            'tanggal_buka' => Carbon::now()->subDays(2),
            'tanggal_tutup' => Carbon::now()->addDays(5),
            'status' => 'draft',
        ]);

        $division = RecruitmentDivision::create([
            'recruitment_id' => $recruitment->id,
            'nama' => 'Divisi IT',
            'kuota' => 2,
        ]);

        $aspect = Aspect::create([
            'recruitment_division_id' => $division->id,
            'nama' => 'Kemampuan Teknis',
            'bobot' => 50,
            'cf_percentage' => 60,
            'sf_percentage' => 40,
        ]);

        $response = $this->actingAs($admin)->post(route('admin.criteria.store', $recruitment), [
            'aspect_id' => $aspect->id,
            'nama_kriteria' => 'Penguasaan Laravel',
            'tipe' => 'core',
            'target_value' => 4,
            'keterangan' => 'Standar penguasaan framework',
            'labels' => [
                'Sangat Kurang',
                'Kurang',
                'Cukup',
                'Baik',
                'Sangat Baik',
            ],
        ]);

        $response->assertRedirect(route('admin.criteria.index', $recruitment));
        $this->assertEquals('Kriteria berhasil ditambahkan.', session('success'));

        $this->assertDatabaseHas('kriteria', [
            'aspect_id' => $aspect->id,
            'nama_kriteria' => 'Penguasaan Laravel',
            'tipe' => 'core',
            'target_value' => 4,
        ]);
        $this->assertDatabaseHas('label_nilai_kriteria', [
            'value' => 4,
            'label' => 'Baik',
        ]);
    }

    /**
     * RK-10: Admin menambahkan kriteria dengan target value di luar batas (target_value = 6)
     */
    public function test_RK_10(): void
    {
        [$admin, $ormawa] = $this->createAdminOrmawa();

        $recruitment = Recruitment::create([
            'ormawa_id' => $ormawa->id,
            'judul' => 'Rekrutmen Kriteria',
            'tanggal_buka' => Carbon::now()->subDays(2),
            'tanggal_tutup' => Carbon::now()->addDays(5),
            'status' => 'draft',
        ]);

        $division = RecruitmentDivision::create([
            'recruitment_id' => $recruitment->id,
            'nama' => 'Divisi IT',
            'kuota' => 2,
        ]);

        $aspect = Aspect::create([
            'recruitment_division_id' => $division->id,
            'nama' => 'Kemampuan Teknis',
            'bobot' => 50,
            'cf_percentage' => 60,
            'sf_percentage' => 40,
        ]);

        $response = $this->actingAs($admin)
            ->from(route('admin.criteria.create', $recruitment))
            ->post(route('admin.criteria.store', $recruitment), [
                'aspect_id' => $aspect->id,
                'nama_kriteria' => 'Target Ekstrem',
                'tipe' => 'core',
                'target_value' => 6, // Di luar range 1-5
                'labels' => [
                    1 => 'L1',
                    2 => 'L2',
                    3 => 'L3',
                    4 => 'L4',
                    5 => 'L5',
                ],
            ]);

        $response->assertRedirect(route('admin.criteria.create', $recruitment));
        $response->assertSessionHasErrors('target_value');
    }

    /**
     * RK-11: Admin mencoba menambah aspek pada divisi yang sudah difinalisasi
     */
    public function test_RK_11(): void
    {
        [$admin, $ormawa] = $this->createAdminOrmawa();

        $recruitment = Recruitment::create([
            'ormawa_id' => $ormawa->id,
            'judul' => 'Rekrutmen Selesai',
            'tanggal_buka' => Carbon::now()->subDays(10),
            'tanggal_tutup' => Carbon::now()->subDays(2),
            'status' => 'ditutup',
        ]);

        $division = RecruitmentDivision::create([
            'recruitment_id' => $recruitment->id,
            'nama' => 'Divisi Terkunci',
            'kuota' => 2,
            'is_finalized' => true,
        ]);

        $response = $this->actingAs($admin)
            ->from(route('admin.aspect.index', $recruitment))
            ->post(route('admin.aspect.store', $recruitment), [
                'recruitment_division_id' => $division->id,
                'nama' => 'Aspek Tambahan',
                'bobot' => 20,
                'cf_percentage' => 50,
                'sf_percentage' => 50,
            ]);

        $response->assertRedirect(route('admin.aspect.index', $recruitment));
        $this->assertEquals('Divisi sudah difinalisasi dan aspek tidak dapat ditambah.', session('error'));
    }
}
