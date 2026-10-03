<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;
use App\Models\MahasiswaProfile;
use App\Models\Ormawa;
use App\Models\Recruitment;
use App\Models\RecruitmentDivision;
use App\Models\Aspect;
use App\Models\Criteria;
use App\Models\Application;
use App\Models\ApplicationScore;

class ProfileMatchingTestSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat User Mahasiswa: Anton dan Budi
        $anton = User::firstOrCreate(['email' => 'anton@ormawa-unsoed.test'], [
            'name' => 'Anton',
            'password' => bcrypt('password'),
            'role' => 'mahasiswa',
            'is_active' => true,
        ]);
        MahasiswaProfile::firstOrCreate(['user_id' => $anton->id], [
            'nim' => 'A10001',
        ]);

        $budi = User::firstOrCreate(['email' => 'budi@ormawa-unsoed.test'], [
            'name' => 'Budi',
            'password' => bcrypt('password'),
            'role' => 'mahasiswa',
            'is_active' => true,
        ]);
        MahasiswaProfile::firstOrCreate(['user_id' => $budi->id], [
            'nim' => 'A10002',
        ]);

        // 2. Ambil Ormawa sembarang (atau BEM)
        $ormawa = Ormawa::first();

        // 3. Buat Rekrutmen
        $recruitment = Recruitment::create([
            'ormawa_id' => $ormawa->id,
            'judul' => 'Rekrutmen Pengurus Baru',
            'tanggal_buka' => now()->subDays(10),
            'tanggal_tutup' => now()->addDays(10),
            'status' => 'dibuka',
        ]);

        // 4. Buat Divisi Pengajian
        $divisi = RecruitmentDivision::create([
            'recruitment_id' => $recruitment->id,
            'nama' => 'Divisi Pengajian',
            'kuota' => 1,
        ]);

        // 5. Buat Aspek
        $aspek1 = Aspect::create([
            'recruitment_division_id' => $divisi->id,
            'nama' => 'Kecerdasan',
            'bobot' => 40.50,
            'cf_percentage' => 60.00,
            'sf_percentage' => 40.00,
            'urutan' => 1,
        ]);

        $aspek2 = Aspect::create([
            'recruitment_division_id' => $divisi->id,
            'nama' => 'Kepribadian',
            'bobot' => 59.50,
            'cf_percentage' => 70.00,
            'sf_percentage' => 30.00,
            'urutan' => 2,
        ]);

        // 6. Buat Kriteria
        $k1 = Criteria::create([
            'aspect_id' => $aspek1->id,
            'nama_kriteria' => 'Intelektual',
            'tipe' => 'core',
            'target_value' => 4,
            'urutan' => 1,
        ]);
        $k2 = Criteria::create([
            'aspect_id' => $aspek1->id,
            'nama_kriteria' => 'Problem Solving',
            'tipe' => 'secondary',
            'target_value' => 4,
            'urutan' => 2,
        ]);

        $k3 = Criteria::create([
            'aspect_id' => $aspek2->id,
            'nama_kriteria' => 'Sikap',
            'tipe' => 'core',
            'target_value' => 4,
            'urutan' => 1,
        ]);
        $k4 = Criteria::create([
            'aspect_id' => $aspek2->id,
            'nama_kriteria' => 'Bijaksana',
            'tipe' => 'secondary',
            'target_value' => 5,
            'urutan' => 2,
        ]);
        $k5 = Criteria::create([
            'aspect_id' => $aspek2->id,
            'nama_kriteria' => 'Tanggung Jawab',
            'tipe' => 'core',
            'target_value' => 4,
            'urutan' => 3,
        ]);

        // 7. Buat Pendaftaran (Application)
        $appAnton = Application::create([
            'recruitment_id' => $recruitment->id,
            'recruitment_division_id' => $divisi->id,
            'user_id' => $anton->id,
            'status' => 'terkirim',
        ]);

        $appBudi = Application::create([
            'recruitment_id' => $recruitment->id,
            'recruitment_division_id' => $divisi->id,
            'user_id' => $budi->id,
            'status' => 'terkirim',
        ]);

        // 8. Buat Nilai Pendaftaran
        // Anton (K1: 2, K2: 5, K3: 3, K4: 5, K5: 4)
        $this->insertScore($appAnton, $k1, 2);
        $this->insertScore($appAnton, $k2, 5);
        $this->insertScore($appAnton, $k3, 3);
        $this->insertScore($appAnton, $k4, 5);
        $this->insertScore($appAnton, $k5, 4);

        // Budi (K1: 4, K2: 5, K3: 4, K4: 3, K5: 1)
        $this->insertScore($appBudi, $k1, 4);
        $this->insertScore($appBudi, $k2, 5);
        $this->insertScore($appBudi, $k3, 4);
        $this->insertScore($appBudi, $k4, 3);
        $this->insertScore($appBudi, $k5, 1);
    }

    private function insertScore($application, $criteria, $actualValue)
    {
        $gap = $actualValue - $criteria->target_value;
        $bobotGap = $this->getBobotGap($gap);

        ApplicationScore::create([
            'application_id' => $application->id,
            'criteria_id' => $criteria->id,
            'actual_value' => $actualValue,
            'gap' => $gap,
            'bobot_gap' => $bobotGap,
        ]);
    }

    private function getBobotGap($gap)
    {
        $konversi = [
            0 => 5,
            1 => 4.5,
            -1 => 4,
            2 => 3.5,
            -2 => 3,
            3 => 2.5,
            -3 => 2,
            4 => 1.5,
            -4 => 1
        ];
        return $konversi[$gap] ?? 0;
    }
}
