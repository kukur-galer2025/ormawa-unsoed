<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\MahasiswaProfile;
use App\Models\Ormawa;
use App\Models\OrmawaAdmin;
use App\Models\Recruitment;
use App\Models\RecruitmentDivision;
use App\Models\Criteria;
use App\Models\Application;
use App\Models\ApplicationScore;
use Illuminate\Database\Seeder;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin accounts
        $ormawas = Ormawa::all();
        $adminNames = ['Admin BEM', 'Admin DPM', 'Admin HMPS IF'];

        foreach ($ormawas as $index => $ormawa) {
            $admin = User::updateOrCreate(
                ['email' => 'admin' . ($index + 1) . '@ormawa-unsoed.test'],
                [
                    'name' => $adminNames[$index] ?? 'Admin ' . $ormawa->nama,
                    'password' => bcrypt('password'),
                    'role' => 'admin',
                    'is_active' => true,
                ]
            );

            OrmawaAdmin::updateOrCreate(
                ['user_id' => $admin->id, 'ormawa_id' => $ormawa->id]
            );
        }
        echo "3 Admin accounts created.\n";

        // Create mahasiswa accounts
        $mahasiswaData = [
            ['name' => 'Andi Pratama', 'nim' => 'H1D021001', 'fakultas' => 'Teknik', 'jurusan' => 'Informatika', 'angkatan' => '2021'],
            ['name' => 'Budi Santoso', 'nim' => 'H1D021002', 'fakultas' => 'Teknik', 'jurusan' => 'Informatika', 'angkatan' => '2021'],
            ['name' => 'Citra Dewi', 'nim' => 'H1D022003', 'fakultas' => 'Teknik', 'jurusan' => 'Informatika', 'angkatan' => '2022'],
            ['name' => 'Dian Kusuma', 'nim' => 'H1D022004', 'fakultas' => 'Teknik', 'jurusan' => 'Informatika', 'angkatan' => '2022'],
            ['name' => 'Eka Putri', 'nim' => 'H1A021005', 'fakultas' => 'Ekonomi dan Bisnis', 'jurusan' => 'Manajemen', 'angkatan' => '2021'],
            ['name' => 'Fajar Hidayat', 'nim' => 'H1A022006', 'fakultas' => 'Ekonomi dan Bisnis', 'jurusan' => 'Akuntansi', 'angkatan' => '2022'],
            ['name' => 'Gita Lestari', 'nim' => 'H2A021007', 'fakultas' => 'Hukum', 'jurusan' => 'Ilmu Hukum', 'angkatan' => '2021'],
            ['name' => 'Hadi Nugroho', 'nim' => 'H2A022008', 'fakultas' => 'Hukum', 'jurusan' => 'Ilmu Hukum', 'angkatan' => '2022'],
            ['name' => 'Indah Sari', 'nim' => 'H3A021009', 'fakultas' => 'FISIP', 'jurusan' => 'Ilmu Komunikasi', 'angkatan' => '2021'],
            ['name' => 'Joko Widodo', 'nim' => 'H3A022010', 'fakultas' => 'FISIP', 'jurusan' => 'Administrasi Negara', 'angkatan' => '2022'],
        ];

        foreach ($mahasiswaData as $i => $data) {
            $user = User::updateOrCreate(
                ['email' => 'mahasiswa' . ($i + 1) . '@ormawa-unsoed.test'],
                [
                    'name' => $data['name'],
                    'password' => bcrypt('password'),
                    'role' => 'mahasiswa',
                    'is_active' => true,
                ]
            );

            $fakultasId = \App\Models\Fakultas::where('nama_fakultas', 'like', '%' . $data['fakultas'] . '%')->first()?->id;
            $jurusanId = \App\Models\Jurusan::where('nama_jurusan', 'like', '%' . $data['jurusan'] . '%')->first()?->id;

            MahasiswaProfile::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'nim' => $data['nim'],
                    'fakultas_id' => $fakultasId,
                    'jurusan_id' => $jurusanId,
                    'angkatan' => $data['angkatan'],
                    'no_hp' => '08' . rand(1000000000, 9999999999),
                ]
            );
        }
        echo "10 Mahasiswa accounts created.\n";

        // ADD IMAGES TO EXISTING ORMAWAS (Using AI Generated, Locally stored images)
        foreach ($ormawas as $idx => $ormawa) {
            $ormawa->update([
                'logo' => 'ormawas/logo_dummy.jpg',
                'cover_photo' => 'ormawas/cover_dummy.jpg',
            ]);
        }

        // CREATE 3 RECRUITMENTS (1 for each Ormawa)
        $recruitmentData = [
            [
                'ormawa' => 'BEM UNSOED',
                'judul' => 'Open Recruitment BEM UNSOED 2024',
                'deskripsi' => 'Mari bergabung dan berkontribusi untuk kampus dan bangsa melalui BEM UNSOED.',
                'divisi' => ['Kementerian Sosial', 'Kementerian Komunikasi', 'Kementerian PSDM'],
            ],
            [
                'ormawa' => 'BEM Fakultas Teknik',
                'judul' => 'Oprec Pengurus BEM FT 2024',
                'deskripsi' => 'Saatnya Teknik Bergerak! Bergabunglah menjadi pengurus BEM Fakultas Teknik.',
                'divisi' => ['Divisi Pengajian', 'Divisi Minat Bakat', 'Divisi Humas'],
            ],
            [
                'ormawa' => 'UKM Olahraga',
                'judul' => 'Rekrutmen Atlet & Official UKM Olahraga',
                'deskripsi' => 'Pendaftaran untuk atlet dan pengurus official unit kegiatan olahraga.',
                'divisi' => ['Official Futsal', 'Official Voli', 'Official Basket'],
            ]
        ];

        $mahasiswas = User::where('role', 'mahasiswa')->get();
        $totalApps = 0;

        foreach ($recruitmentData as $rData) {
            $ormawa = Ormawa::where('nama', $rData['ormawa'])->first();
            if (!$ormawa) continue;

            $recruitment = Recruitment::updateOrCreate(
                ['ormawa_id' => $ormawa->id, 'judul' => $rData['judul']],
                [
                    'deskripsi' => $rData['deskripsi'],
                    'persyaratan' => "1. Mahasiswa aktif\n2. Berkomitmen tinggi\n3. Lulus wawancara",
                    'tanggal_buka' => now()->subDays(5)->format('Y-m-d'),
                    'tanggal_tutup' => now()->addDays(20)->format('Y-m-d'),
                    'status' => 'dibuka',
                ]
            );

            foreach ($rData['divisi'] as $divName) {
                $division = RecruitmentDivision::updateOrCreate(
                    ['recruitment_id' => $recruitment->id, 'nama' => $divName],
                    ['kuota' => 2, 'deskripsi' => 'Fokus pada pengembangan ' . strtolower($divName)]
                );

                // Jika divisi ini adalah "Divisi Pengajian", gunakan data sesuai tes (Excel)
                if ($divName === 'Divisi Pengajian') {
                    $aspek1 = \App\Models\Aspect::updateOrCreate(
                        ['recruitment_division_id' => $division->id, 'nama' => 'Kecerdasan'],
                        ['bobot' => 40.50, 'cf_percentage' => 60, 'sf_percentage' => 40, 'urutan' => 1]
                    );
                    $aspek2 = \App\Models\Aspect::updateOrCreate(
                        ['recruitment_division_id' => $division->id, 'nama' => 'Kepribadian'],
                        ['bobot' => 59.50, 'cf_percentage' => 70, 'sf_percentage' => 30, 'urutan' => 2]
                    );

                    $kriteria = [
                        ['aspek' => $aspek1, 'nama' => 'Intelektual', 'tipe' => 'core', 'target' => 4],
                        ['aspek' => $aspek1, 'nama' => 'Problem Solving', 'tipe' => 'secondary', 'target' => 4],
                        ['aspek' => $aspek2, 'nama' => 'Sikap', 'tipe' => 'core', 'target' => 4],
                        ['aspek' => $aspek2, 'nama' => 'Bijaksana', 'tipe' => 'secondary', 'target' => 5],
                        ['aspek' => $aspek2, 'nama' => 'Tanggung Jawab', 'tipe' => 'core', 'target' => 4],
                    ];

                    $defaultLabels = ['Sangat Kurang', 'Kurang', 'Cukup', 'Baik', 'Sangat Baik'];
                    $kObjects = [];
                    foreach ($kriteria as $idx => $k) {
                        $crit = Criteria::updateOrCreate(
                            ['aspect_id' => $k['aspek']->id, 'nama_kriteria' => $k['nama']],
                            ['tipe' => $k['tipe'], 'target_value' => $k['target'], 'urutan' => $idx + 1]
                        );
                        $kObjects[] = $crit;
                        foreach ($defaultLabels as $lIdx => $label) {
                            \App\Models\CriteriaValueLabel::updateOrCreate(
                                ['criteria_id' => $crit->id, 'value' => $lIdx + 1],
                                ['label' => $label]
                            );
                        }
                    }

                    // Pendaftar khusus: Anton & Budi
                    $anton = User::firstOrCreate(
                        ['email' => 'anton@ormawa-unsoed.test'],
                        ['name' => 'Anton', 'password' => bcrypt('password'), 'role' => 'mahasiswa', 'is_active' => true]
                    );
                    \App\Models\MahasiswaProfile::firstOrCreate(['user_id' => $anton->id], ['nim' => 'A10001']);

                    $budi = User::firstOrCreate(
                        ['email' => 'budi@ormawa-unsoed.test'],
                        ['name' => 'Budi', 'password' => bcrypt('password'), 'role' => 'mahasiswa', 'is_active' => true]
                    );
                    \App\Models\MahasiswaProfile::firstOrCreate(['user_id' => $budi->id], ['nim' => 'A10002']);

                    $appAnton = Application::updateOrCreate(
                        ['recruitment_id' => $recruitment->id, 'recruitment_division_id' => $division->id, 'user_id' => $anton->id],
                        ['status' => 'terkirim']
                    );
                    $appBudi = Application::updateOrCreate(
                        ['recruitment_id' => $recruitment->id, 'recruitment_division_id' => $division->id, 'user_id' => $budi->id],
                        ['status' => 'terkirim']
                    );

                    $antonScores = [2, 5, 3, 5, 4];
                    $budiScores = [4, 5, 4, 3, 1];

                    foreach ($kObjects as $i => $crit) {
                        // Anton
                        $gapA = $antonScores[$i] - $crit->target_value;
                        ApplicationScore::updateOrCreate(
                            ['application_id' => $appAnton->id, 'criteria_id' => $crit->id],
                            ['actual_value' => $antonScores[$i], 'gap' => $gapA, 'bobot_gap' => $this->getBobotGap($gapA)]
                        );
                        // Budi
                        $gapB = $budiScores[$i] - $crit->target_value;
                        ApplicationScore::updateOrCreate(
                            ['application_id' => $appBudi->id, 'criteria_id' => $crit->id],
                            ['actual_value' => $budiScores[$i], 'gap' => $gapB, 'bobot_gap' => $this->getBobotGap($gapB)]
                        );
                    }
                    $totalApps += 2;
                    continue; // Skip the default dummy data insertion for this division
                }

                // Aspek & Kriteria Standar untuk divisi lainnya
                $aspek1 = \App\Models\Aspect::updateOrCreate(
                    ['recruitment_division_id' => $division->id, 'nama' => 'Kemampuan (Skill)'],
                    ['bobot' => 50, 'cf_percentage' => 60, 'sf_percentage' => 40, 'urutan' => 1]
                );
                $aspek2 = \App\Models\Aspect::updateOrCreate(
                    ['recruitment_division_id' => $division->id, 'nama' => 'Wawancara'],
                    ['bobot' => 50, 'cf_percentage' => 60, 'sf_percentage' => 40, 'urutan' => 2]
                );

                $kriteria = [
                    ['aspek' => $aspek1, 'nama' => 'Pengalaman', 'tipe' => 'core'],
                    ['aspek' => $aspek1, 'nama' => 'Pengetahuan', 'tipe' => 'secondary'],
                    ['aspek' => $aspek2, 'nama' => 'Komunikasi', 'tipe' => 'core'],
                    ['aspek' => $aspek2, 'nama' => 'Attitude', 'tipe' => 'secondary'],
                ];

                $defaultLabels = ['Sangat Kurang', 'Kurang', 'Cukup', 'Baik', 'Sangat Baik'];
                foreach ($kriteria as $idx => $k) {
                    $crit = Criteria::updateOrCreate(
                        ['aspect_id' => $k['aspek']->id, 'nama_kriteria' => $k['nama']],
                        ['tipe' => $k['tipe'], 'target_value' => 4, 'urutan' => $idx + 1]
                    );
                    foreach ($defaultLabels as $lIdx => $label) {
                        \App\Models\CriteriaValueLabel::updateOrCreate(
                            ['criteria_id' => $crit->id, 'value' => $lIdx + 1],
                            ['label' => $label]
                        );
                    }
                }

                // Masukkan 2 pendaftar acak ke tiap divisi ini
                $pendaftarAcak = $mahasiswas->random(2);
                foreach ($pendaftarAcak as $mhs) {
                    $app = Application::updateOrCreate(
                        ['recruitment_id' => $recruitment->id, 'recruitment_division_id' => $division->id, 'user_id' => $mhs->id],
                        ['motivasi' => 'Motivasi saya untuk divisi ' . $divName, 'status' => 'terkirim']
                    );
                    
                    // Beri nilai dummy
                    foreach ($division->allCriteria()->get() as $c) {
                        ApplicationScore::updateOrCreate(
                            ['application_id' => $app->id, 'criteria_id' => $c->id],
                            ['actual_value' => null, 'gap' => null, 'bobot_gap' => null]
                        );
                    }
                    $totalApps++;
                }
            }
        }
        echo "3 Recruitments and {$totalApps} Applications created.\n";

        echo "\nDummy data seeding complete!\n";
        echo "Login credentials (all passwords: 'password'):\n";
        echo "  Superadmin: superadmin@ormawa-unsoed.test\n";
        echo "  Admin BEM:  admin1@ormawa-unsoed.test\n";
        echo "  Admin DPM:  admin2@ormawa-unsoed.test\n";
        echo "  Admin HMPS: admin3@ormawa-unsoed.test\n";
        echo "  Mahasiswa (Anton): anton@ormawa-unsoed.test\n";
        echo "  Mahasiswa (Budi):  budi@ormawa-unsoed.test\n";
    }

    private function getBobotGap($gap)
    {
        $konversi = [0 => 5, 1 => 4.5, -1 => 4, 2 => 3.5, -2 => 3, 3 => 2.5, -3 => 2, 4 => 1.5, -4 => 1];
        return $konversi[$gap] ?? 0;
    }
}