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

        // Create a sample recruitment for BEM
        $bem = Ormawa::where('nama', 'BEM UNSOED')->first();
        if ($bem) {
            $recruitment = Recruitment::updateOrCreate(
                ['ormawa_id' => $bem->id, 'judul' => 'Rekrutmen Pengurus BEM UNSOED 2024/2025'],
                [
                    'deskripsi' => 'Pendaftaran calon pengurus Badan Eksekutif Mahasiswa UNSOED periode 2024/2025. Terbuka untuk seluruh mahasiswa aktif UNSOED.',
                    'persyaratan' => "1. Mahasiswa aktif UNSOED minimal semester 3\n2. IPK minimal 3.00\n3. Tidak sedang menjabat di organisasi lain\n4. Bersedia aktif selama periode kepengurusan",
                    'tanggal_buka' => now()->format('Y-m-d'),
                    'tanggal_tutup' => now()->addDays(30)->format('Y-m-d'),
                    'status' => 'dibuka',
                ]
            );

            // Create Divisions
            $divisionsData = [
                ['nama' => 'Kementerian Dalam Negeri', 'kuota' => 2],
                ['nama' => 'Kementerian Luar Negeri', 'kuota' => 2],
                ['nama' => 'Kementerian Komunikasi dan Informasi', 'kuota' => 1]
            ];

            foreach ($divisionsData as $div) {
                $division = RecruitmentDivision::updateOrCreate(
                    ['recruitment_id' => $recruitment->id, 'nama' => $div['nama']],
                    ['kuota' => $div['kuota']]
                );

                // Create aspects for division
                $aspekKecerdasan = \App\Models\Aspect::updateOrCreate(
                    ['recruitment_division_id' => $division->id, 'nama' => 'Kecerdasan'],
                    ['bobot' => 40, 'cf_percentage' => 60, 'sf_percentage' => 40, 'urutan' => 1]
                );
                $aspekKepribadian = \App\Models\Aspect::updateOrCreate(
                    ['recruitment_division_id' => $division->id, 'nama' => 'Kepribadian'],
                    ['bobot' => 60, 'cf_percentage' => 70, 'sf_percentage' => 30, 'urutan' => 2]
                );

                // Create criteria per aspect
                $criteriaKecerdasan = [
                    ['nama_kriteria' => 'Intelektual', 'tipe' => 'core', 'target_value' => 4, 'keterangan' => 'Pengetahuan umum dan organisasi', 'urutan' => 1],
                    ['nama_kriteria' => 'Problem Solving', 'tipe' => 'secondary', 'target_value' => 4, 'keterangan' => 'Kemampuan menyelesaikan masalah', 'urutan' => 2],
                ];
                
                $criteriaKepribadian = [
                    ['nama_kriteria' => 'Sikap', 'tipe' => 'core', 'target_value' => 4, 'keterangan' => 'Sikap keseharian dan tata krama', 'urutan' => 1],
                    ['nama_kriteria' => 'Bijaksana', 'tipe' => 'secondary', 'target_value' => 5, 'keterangan' => 'Kedewasaan dalam berpikir', 'urutan' => 2],
                    ['nama_kriteria' => 'Tanggung Jawab', 'tipe' => 'core', 'target_value' => 4, 'keterangan' => 'Rasa tanggung jawab terhadap tugas', 'urutan' => 3],
                ];

                $defaultLabels = ['Sangat Kurang', 'Kurang', 'Cukup', 'Baik', 'Sangat Baik'];

                foreach ($criteriaKecerdasan as $cData) {
                    $c = Criteria::updateOrCreate(
                        ['aspect_id' => $aspekKecerdasan->id, 'nama_kriteria' => $cData['nama_kriteria']],
                        $cData
                    );
                    foreach ($defaultLabels as $idx => $label) {
                        \App\Models\CriteriaValueLabel::updateOrCreate(
                            ['criteria_id' => $c->id, 'value' => $idx + 1],
                            ['label' => $label]
                        );
                    }
                }

                foreach ($criteriaKepribadian as $cData) {
                    $c = Criteria::updateOrCreate(
                        ['aspect_id' => $aspekKepribadian->id, 'nama_kriteria' => $cData['nama_kriteria']],
                        $cData
                    );
                    foreach ($defaultLabels as $idx => $label) {
                        \App\Models\CriteriaValueLabel::updateOrCreate(
                            ['criteria_id' => $c->id, 'value' => $idx + 1],
                            ['label' => $label]
                        );
                    }
                }
            }

            echo "1 Recruitment with 3 Divisions created for BEM.\n";

            // Create some applications (spread them among divisions)
            $mahasiswas = User::where('role', 'mahasiswa')->take(6)->get();
            $divisions = $recruitment->divisions;
            
            $divIndex = 0;
            foreach ($mahasiswas as $mhs) {
                $div = $divisions[$divIndex % count($divisions)];
                $app = Application::updateOrCreate(
                    [
                        'recruitment_id' => $recruitment->id, 
                        'recruitment_division_id' => $div->id, 
                        'user_id' => $mhs->id
                    ],
                    [
                        'motivasi' => 'Saya ingin berkontribusi di ' . $div->nama . ' untuk mengembangkan potensi diri.',
                        'status' => 'terkirim',
                    ]
                );

                // Create empty score entries for each criteria
                foreach ($div->allCriteria()->get() as $c) {
                    ApplicationScore::updateOrCreate(
                        ['application_id' => $app->id, 'criteria_id' => $c->id],
                        ['actual_value' => null, 'gap' => null, 'bobot_gap' => null]
                    );
                }
                
                $divIndex++;
            }
            echo "6 Applications created with empty scores.\n";
        }

        echo "\nDummy data seeding complete!\n";
        echo "Login credentials (all passwords: 'password'):\n";
        echo "  Superadmin: superadmin@ormawa-unsoed.test\n";
        echo "  Admin BEM:  admin1@ormawa-unsoed.test\n";
        echo "  Admin DPM:  admin2@ormawa-unsoed.test\n";
        echo "  Admin HMPS: admin3@ormawa-unsoed.test\n";
        echo "  Mahasiswa:  mahasiswa1@ormawa-unsoed.test s/d mahasiswa10@ormawa-unsoed.test\n";
    }
}