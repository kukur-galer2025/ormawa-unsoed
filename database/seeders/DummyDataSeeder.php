<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\MahasiswaProfile;
use App\Models\Ormawa;
use App\Models\OrmawaAdmin;
use App\Models\OrmawaPrestasi;
use App\Models\OrmawaProgramKerja;
use App\Models\Recruitment;
use App\Models\RecruitmentDivision;
use App\Models\Aspect;
use App\Models\Criteria;
use App\Models\CriteriaValueLabel;
use App\Models\Application;
use App\Models\ApplicationScore;
use Illuminate\Database\Seeder;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. CREATE 5 ADMIN ACCOUNTS (1 for each Ormawa)
        $ormawas = Ormawa::all();
        $adminNames = [
            'Admin BEM UNSOED',
            'Admin UKM Olahraga',
            'Admin BEM FT',
            'Admin HMIF',
            'Admin BEM FEB',
        ];

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
                ['ormawa_id' => $ormawa->id],
                ['user_id' => $admin->id]
            );
        }
        echo "5 Admin accounts created & linked to 5 Ormawas.\n";

        // 2. CREATE 10 MAHASISWA ACCOUNTS
        $mahasiswaData = [
            ['name' => 'Andi Pratama', 'nim' => 'H1D021001', 'fakultas' => 'Teknik', 'jurusan' => 'Informatika', 'angkatan' => '2021'],
            ['name' => 'Budi Santoso', 'nim' => 'H1D021002', 'fakultas' => 'Teknik', 'jurusan' => 'Informatika', 'angkatan' => '2021'],
            ['name' => 'Citra Dewi', 'nim' => 'H1D022003', 'fakultas' => 'Teknik', 'jurusan' => 'Informatika', 'angkatan' => '2022'],
            ['name' => 'Dian Kusuma', 'nim' => 'H1D022004', 'fakultas' => 'Teknik', 'jurusan' => 'Informatika', 'angkatan' => '2022'],
            ['name' => 'Eka Putri', 'nim' => 'H1A021005', 'fakultas' => 'Ekonomi dan Bisnis', 'jurusan' => 'Manajemen', 'angkatan' => '2021'],
            ['name' => 'Fajar Hidayat', 'nim' => 'H1A022006', 'fakultas' => 'Ekonomi dan Bisnis', 'jurusan' => 'Akuntansi', 'angkatan' => '2022'],
            ['name' => 'Gita Lestari', 'nim' => 'H2A021007', 'fakultas' => 'Hukum', 'jurusan' => 'Hukum', 'angkatan' => '2021'],
            ['name' => 'Hadi Nugroho', 'nim' => 'H2A022008', 'fakultas' => 'Hukum', 'jurusan' => 'Hukum', 'angkatan' => '2022'],
            ['name' => 'Indah Sari', 'nim' => 'H3A021009', 'fakultas' => 'Ilmu Sosial dan Ilmu Politik', 'jurusan' => 'Ilmu Komunikasi', 'angkatan' => '2021'],
            ['name' => 'Joko Widodo', 'nim' => 'H3A022010', 'fakultas' => 'Ilmu Sosial dan Ilmu Politik', 'jurusan' => 'Administrasi Publik', 'angkatan' => '2022'],
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

        // 3. LOGOS, COVERS, PRESTASI & PROKER FOR ALL 5 ORMAWAS
        $prestasiMap = [
            'BEM UNSOED' => ['judul' => 'Juara 1 Debat Nasional 2023', 'foto' => 'ormawas/prestasi_univ.jpg'],
            'BEM Fakultas Teknik' => ['judul' => 'Juara 1 Kontes Robotika Nasional 2023', 'foto' => 'ormawas/prestasi_ft.jpg'],
            'UKM Olahraga' => ['judul' => 'Juara Umum Liga Futsal Mahasiswa 2023', 'foto' => 'ormawas/prestasi_olahraga.jpg'],
            'Himpunan Mahasiswa Informatika (HMIF)' => ['judul' => 'Juara 1 Gemastik UX Design 2023', 'foto' => 'ormawas/prestasi_ft.jpg'],
            'BEM Fakultas Ekonomi dan Bisnis' => ['judul' => 'Juara 1 Business Plan Competition 2023', 'foto' => 'ormawas/prestasi_univ.jpg'],
        ];

        $prokerMap = [
            'BEM UNSOED' => ['nama' => 'Seminar Nasional & Town Hall Mahasiswa', 'foto' => 'ormawas/proker_univ.jpg'],
            'BEM Fakultas Teknik' => ['nama' => 'Workshop & Pembuatan Prototipe Robot', 'foto' => 'ormawas/proker_ft.jpg'],
            'UKM Olahraga' => ['nama' => 'Turnamen Olahraga Rektor Cup UNSOED', 'foto' => 'ormawas/proker_olahraga.jpg'],
            'Himpunan Mahasiswa Informatika (HMIF)' => ['nama' => 'Informatics Championship & Hackathon', 'foto' => 'ormawas/proker_ft.jpg'],
            'BEM Fakultas Ekonomi dan Bisnis' => ['nama' => 'FEB Entrepreneur Expo & Business Talk', 'foto' => 'ormawas/proker_univ.jpg'],
        ];

        foreach ($ormawas as $ormawa) {
            $ormawa->update([
                'logo' => 'ormawas/logo_dummy.jpg',
                'cover_photo' => 'ormawas/cover_dummy.jpg',
            ]);

            if (isset($prestasiMap[$ormawa->nama])) {
                OrmawaPrestasi::updateOrCreate(
                    ['ormawa_id' => $ormawa->id, 'judul' => $prestasiMap[$ormawa->nama]['judul']],
                    ['deskripsi' => 'Prestasi membanggakan dari ' . $ormawa->nama, 'tahun' => 2023, 'foto' => $prestasiMap[$ormawa->nama]['foto']]
                );
            }

            if (isset($prokerMap[$ormawa->nama])) {
                OrmawaProgramKerja::updateOrCreate(
                    ['ormawa_id' => $ormawa->id, 'nama' => $prokerMap[$ormawa->nama]['nama']],
                    ['deskripsi' => 'Program kerja unggulan dari ' . $ormawa->nama, 'foto' => $prokerMap[$ormawa->nama]['foto']]
                );
            }
        }

        // 4. CREATE RECRUITMENTS FOR ALL 5 ORMAWAS
        $recruitmentData = [
            [
                'ormawa' => 'BEM UNSOED',
                'judul' => 'Open Recruitment BEM UNSOED 2026',
                'deskripsi' => 'Mari bergabung dan berkontribusi untuk kampus dan bangsa melalui BEM UNSOED.',
                'divisi' => ['Kementerian Sosial', 'Kementerian Komunikasi'],
            ],
            [
                'ormawa' => 'UKM Olahraga',
                'judul' => 'Rekrutmen Atlet & Official UKM Olahraga 2026',
                'deskripsi' => 'Pendaftaran untuk atlet dan pengurus official unit kegiatan olahraga UNSOED.',
                'divisi' => ['Official Futsal', 'Official Basket'],
            ],
            [
                'ormawa' => 'BEM Fakultas Teknik',
                'judul' => 'Oprec Pengurus BEM FT 2026',
                'deskripsi' => 'Saatnya Teknik Bergerak! Bergabunglah menjadi pengurus BEM Fakultas Teknik.',
                'divisi' => ['Divisi Pengajian', 'Divisi Minat Bakat'],
            ],
            [
                'ormawa' => 'Himpunan Mahasiswa Informatika (HMIF)',
                'judul' => 'Open Recruitment Pengurus HMIF UNSOED 2026',
                'deskripsi' => 'Wadah pengembangan keilmuan teknologi informasi, coding culture, dan kepemimpinan.',
                'divisi' => ['Divisi Keilmuan & Riset', 'Divisi Media Kreatif'],
            ],
            [
                'ormawa' => 'BEM Fakultas Ekonomi dan Bisnis',
                'judul' => 'Open Recruitment Pengurus BEM FEB 2026',
                'deskripsi' => 'Membangun ekosistem mahasiswa ekonomi dan bisnis yang berjiwa profesional dan inovatif.',
                'divisi' => ['Divisi Kewirausahaan', 'Divisi Advokasi'],
            ],
        ];

        $mahasiswas = User::where('role', 'mahasiswa')->get();
        $totalApps = 0;
        $defaultLabels = ['Sangat Kurang', 'Kurang', 'Cukup', 'Baik', 'Sangat Baik'];

        foreach ($recruitmentData as $rData) {
            $ormawa = Ormawa::where('nama', $rData['ormawa'])->first();
            if (!$ormawa) continue;

            // Semua rekrutmen diset "ditutup" agar siap langsung dihitung di Profile Matching
            $recruitment = Recruitment::updateOrCreate(
                ['ormawa_id' => $ormawa->id, 'judul' => $rData['judul']],
                [
                    'deskripsi' => $rData['deskripsi'],
                    'persyaratan' => "1. Mahasiswa aktif\n2. Berkomitmen tinggi\n3. Lulus wawancara",
                    'tanggal_buka' => now()->subDays(10)->format('Y-m-d'),
                    'tanggal_tutup' => now()->subDays(1)->format('Y-m-d'),
                    'status' => 'ditutup',
                ]
            );

            foreach ($rData['divisi'] as $divName) {
                $division = RecruitmentDivision::updateOrCreate(
                    ['recruitment_id' => $recruitment->id, 'nama' => $divName],
                    ['kuota' => 2, 'deskripsi' => 'Fokus pada pengembangan ' . strtolower($divName)]
                );

                // Khusus Divisi Pengajian di BEM FT: Gunakan data Anton & Budi sesuai contoh Excel
                if ($divName === 'Divisi Pengajian') {
                    $aspek1 = Aspect::updateOrCreate(
                        ['recruitment_division_id' => $division->id, 'nama' => 'Kecerdasan'],
                        ['bobot' => 40.50, 'cf_percentage' => 60, 'sf_percentage' => 40, 'urutan' => 1]
                    );
                    $aspek2 = Aspect::updateOrCreate(
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

                    $kObjects = [];
                    foreach ($kriteria as $idx => $k) {
                        $crit = Criteria::updateOrCreate(
                            ['aspect_id' => $k['aspek']->id, 'nama_kriteria' => $k['nama']],
                            ['tipe' => $k['tipe'], 'target_value' => $k['target'], 'urutan' => $idx + 1]
                        );
                        $kObjects[] = $crit;
                        foreach ($defaultLabels as $lIdx => $label) {
                            CriteriaValueLabel::updateOrCreate(
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
                    MahasiswaProfile::firstOrCreate(['user_id' => $anton->id], ['nim' => 'A10001']);

                    $budi = User::firstOrCreate(
                        ['email' => 'budi@ormawa-unsoed.test'],
                        ['name' => 'Budi', 'password' => bcrypt('password'), 'role' => 'mahasiswa', 'is_active' => true]
                    );
                    MahasiswaProfile::firstOrCreate(['user_id' => $budi->id], ['nim' => 'A10002']);

                    $appAnton = Application::updateOrCreate(
                        ['recruitment_id' => $recruitment->id, 'recruitment_division_id' => $division->id, 'user_id' => $anton->id],
                        ['status' => 'diproses']
                    );
                    $appBudi = Application::updateOrCreate(
                        ['recruitment_id' => $recruitment->id, 'recruitment_division_id' => $division->id, 'user_id' => $budi->id],
                        ['status' => 'diproses']
                    );

                    $antonScores = [2, 5, 3, 5, 4];
                    $budiScores = [4, 5, 4, 3, 1];

                    foreach ($kObjects as $i => $crit) {
                        $gapA = $antonScores[$i] - $crit->target_value;
                        ApplicationScore::updateOrCreate(
                            ['application_id' => $appAnton->id, 'criteria_id' => $crit->id],
                            ['actual_value' => $antonScores[$i], 'gap' => $gapA, 'bobot_gap' => $this->getBobotGap($gapA)]
                        );
                        $gapB = $budiScores[$i] - $crit->target_value;
                        ApplicationScore::updateOrCreate(
                            ['application_id' => $appBudi->id, 'criteria_id' => $crit->id],
                            ['actual_value' => $budiScores[$i], 'gap' => $gapB, 'bobot_gap' => $this->getBobotGap($gapB)]
                        );
                    }
                    $totalApps += 2;
                    continue;
                }

                // Untuk seluruh divisi lainnya di 5 Ormawa:
                // Buat 2 Aspek (Total Bobot 100%)
                $aspek1 = Aspect::updateOrCreate(
                    ['recruitment_division_id' => $division->id, 'nama' => 'Kemampuan (Skill)'],
                    ['bobot' => 50, 'cf_percentage' => 60, 'sf_percentage' => 40, 'urutan' => 1]
                );
                $aspek2 = Aspect::updateOrCreate(
                    ['recruitment_division_id' => $division->id, 'nama' => 'Kepribadian & Wawancara'],
                    ['bobot' => 50, 'cf_percentage' => 60, 'sf_percentage' => 40, 'urutan' => 2]
                );

                $kriteria = [
                    ['aspek' => $aspek1, 'nama' => 'Pengalaman & Portofolio', 'tipe' => 'core', 'target' => 4],
                    ['aspek' => $aspek1, 'nama' => 'Pemahaman Bidang', 'tipe' => 'secondary', 'target' => 3],
                    ['aspek' => $aspek2, 'nama' => 'Komunikasi & Kerjasama', 'tipe' => 'core', 'target' => 4],
                    ['aspek' => $aspek2, 'nama' => 'Komitmen & Integritas', 'tipe' => 'secondary', 'target' => 4],
                ];

                $critObjects = [];
                foreach ($kriteria as $idx => $k) {
                    $crit = Criteria::updateOrCreate(
                        ['aspect_id' => $k['aspek']->id, 'nama_kriteria' => $k['nama']],
                        ['tipe' => $k['tipe'], 'target_value' => $k['target'], 'urutan' => $idx + 1]
                    );
                    $critObjects[] = $crit;
                    foreach ($defaultLabels as $lIdx => $label) {
                        CriteriaValueLabel::updateOrCreate(
                            ['criteria_id' => $crit->id, 'value' => $lIdx + 1],
                            ['label' => $label]
                        );
                    }
                }

                // Masukkan 2 pendaftar dan berikan nilai lengkap (skala 3-5)
                $pendaftarAcak = $mahasiswas->random(2);
                foreach ($pendaftarAcak as $mhs) {
                    $app = Application::updateOrCreate(
                        ['recruitment_id' => $recruitment->id, 'recruitment_division_id' => $division->id, 'user_id' => $mhs->id],
                        ['motivasi' => 'Motivasi saya mendaftar di divisi ' . $divName, 'status' => 'diproses']
                    );

                    foreach ($critObjects as $c) {
                        $actualScore = rand(3, 5);
                        $gap = $actualScore - $c->target_value;
                        ApplicationScore::updateOrCreate(
                            ['application_id' => $app->id, 'criteria_id' => $c->id],
                            [
                                'actual_value' => $actualScore,
                                'gap' => $gap,
                                'bobot_gap' => $this->getBobotGap($gap),
                            ]
                        );
                    }
                    $totalApps++;
                }
            }
        }

        echo "5 Rekrutmen untuk seluruh 5 Ormawa berhasil dibuat lengkap dengan Aspek, Kriteria, dan Nilai Pelamar Siap Hitung ({$totalApps} pendaftar).\n";
    }

    private function getBobotGap($gap)
    {
        $konversi = [0 => 5, 1 => 4.5, -1 => 4, 2 => 3.5, -2 => 3, 3 => 2.5, -3 => 2, 4 => 1.5, -4 => 1];
        return $konversi[$gap] ?? 0;
    }
}