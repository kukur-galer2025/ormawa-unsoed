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

        // 3. LOGOS, COVERS, PRESTASI & PROKER FOR ALL 5 ORMAWAS (WEBP COMPRESSED & COMPLETE)
        $ormawaAssetMap = [
            'BEM UNSOED' => [
                'logo' => 'ormawas/bem_unsoed_logo.webp',
                'cover_photo' => 'ormawas/bem_unsoed_cover.webp',
                'prestasis' => [
                    [
                        'judul' => 'Juara 1 Debat Konstitusi Mahasiswa Nasional 2024',
                        'deskripsi' => 'Meraih Juara 1 pada Kompetisi Debat Hukum & Konstitusi Mahasiswa Tingkat Nasional Piala Mahkamah Konstitusi RI.',
                        'tahun' => 2024,
                        'foto' => 'ormawas/bem_unsoed_prestasi_1.webp',
                    ],
                    [
                        'judul' => 'Penghargaan BEM Terbaik Kategori Tata Kelola Aspiratif',
                        'deskripsi' => 'Dianugerahi penghargaan tata kelola advokasi dan transparansi organisasi terbaik tingkat perguruan tinggi se-Indonesia.',
                        'tahun' => 2024,
                        'foto' => 'ormawas/bem_unsoed_prestasi_2.webp',
                    ],
                ],
                'prokers' => [
                    [
                        'nama' => 'Sidang Umum & Town Hall Aspirasi Mahasiswa',
                        'deskripsi' => 'Forum dialog terbuka dan penyerapan aspirasi seluruh mahasiswa UNSOED bersama jajaran pimpinan universitas untuk evaluasi fasilitas dan kebijakan kampus.',
                        'foto' => 'ormawas/bem_unsoed_proker_1.webp',
                    ],
                    [
                        'nama' => 'Soedirman Art & Culture Festival 2026',
                        'deskripsi' => 'Pagelaran akbar seni budaya nusantara, parade ormawa, kompetisi karya cipta, dan konser musik kolaborasi antarfakultas.',
                        'foto' => 'ormawas/bem_unsoed_proker_2.webp',
                    ],
                    [
                        'nama' => 'Soedirman Mengabdi: Desa Binaan Banyumas',
                        'deskripsi' => 'Program pengabdian masyarakat berkelanjutan yang mencakup revitalisasi literasi sekolah dasar, pelatihan UMKM desa, dan pemeriksaan kesehatan gratis.',
                        'foto' => 'ormawas/bem_unsoed_proker_3.webp',
                    ],
                ],
            ],
            'UKM Olahraga' => [
                'logo' => 'ormawas/ukm_olahraga_logo.webp',
                'cover_photo' => 'ormawas/ukm_olahraga_cover.webp',
                'prestasis' => [
                    [
                        'judul' => 'Juara Umum Pekan Olahraga Mahasiswa Daerah (POMDA) 2024',
                        'deskripsi' => 'Membawa kontingen atlet UNSOED menorehkan gelar Juara Umum dengan perolehan 12 medali emas, 8 perak, dan 5 perunggu.',
                        'tahun' => 2024,
                        'foto' => 'ormawas/ukm_olahraga_prestasi_1.webp',
                    ],
                    [
                        'judul' => 'Medali Emas Cabang Futsal Antarmahasiswa Nasional 2023',
                        'deskripsi' => 'Meraih medali emas divisi utama pada kejuaraan futsal antarmahasiswa se-Indonesia setelah mengalahkan tim universitas terkemuka.',
                        'tahun' => 2023,
                        'foto' => 'ormawas/ukm_olahraga_prestasi_2.webp',
                    ],
                ],
                'prokers' => [
                    [
                        'nama' => 'Turnamen Akbar Rektor Cup UNSOED 2026',
                        'deskripsi' => 'Pesta olahraga tahunan mempertandingkan 8 cabang olahraga (futsal, basket, bulutangkis, voli, catur, atletik, tenis meja, taekwondo) antarfakultas.',
                        'foto' => 'ormawas/ukm_olahraga_proker_1.webp',
                    ],
                    [
                        'nama' => 'Pemusatan Latihan & Coaching Clinic Atlet Kampus',
                        'deskripsi' => 'Program pelatihan intensif periodik yang menghadirkan atlet pelatnas dan instruktur profesional berlisensi.',
                        'foto' => 'ormawas/ukm_olahraga_proker_2.webp',
                    ],
                    [
                        'nama' => 'Soedirman Fun Run 10K & Kampanye Bugar',
                        'deskripsi' => 'Ajang lari massal yang diikuti ribuan mahasiswa, dosen, dan masyarakat umum untuk mengampanyekan gaya hidup sehat dan sportivitas.',
                        'foto' => 'ormawas/ukm_olahraga_proker_3.webp',
                    ],
                ],
            ],
            'BEM Fakultas Teknik' => [
                'logo' => 'ormawas/bem_ft_logo.webp',
                'cover_photo' => 'ormawas/bem_ft_cover.webp',
                'prestasis' => [
                    [
                        'judul' => 'Juara 1 Kontes Robot Indonesia (KRI) Nasional 2024',
                        'deskripsi' => 'Menjuarai divisi robot sepak bola humanoid tingkat nasional yang diselenggarakan Puspresnas Kemendikbudristek.',
                        'tahun' => 2024,
                        'foto' => 'ormawas/bem_ft_prestasi_1.webp',
                    ],
                    [
                        'judul' => 'Juara 2 National Bridge Design & Structural Competition 2024',
                        'deskripsi' => 'Meraih peringkat kedua nasional dalam perancangan jembatan dengan efisiensi beban struktural tertinggi.',
                        'tahun' => 2024,
                        'foto' => 'ormawas/bem_ft_prestasi_2.webp',
                    ],
                ],
                'prokers' => [
                    [
                        'nama' => 'Engineering Innovation Expo & Pameran Riset',
                        'deskripsi' => 'Ekshibisi karya teknologi terapan mahasiswa teknik mulai dari otomasi industri, rancang bangun sipil, energi terbarukan, hingga sistem embedded.',
                        'foto' => 'ormawas/bem_ft_proker_1.webp',
                    ],
                    [
                        'nama' => 'Workshop IoT & Prototyping Robotika Cerdas',
                        'deskripsi' => 'Pelatihan perancangan perangkat Internet of Things berbasis mikroprosesor dengan pengujian sensor langsung di laboratorium.',
                        'foto' => 'ormawas/bem_ft_proker_2.webp',
                    ],
                    [
                        'nama' => 'Bakti Teknik: Listrik Tenaga Surya Pedesaan',
                        'deskripsi' => 'Aksi nyata pengabdian mahasiswa teknik merancang dan memasang instalasi panel surya untuk fasilitas umum desa terpencil di Banyumas.',
                        'foto' => 'ormawas/bem_ft_proker_3.webp',
                    ],
                ],
            ],
            'Himpunan Mahasiswa Informatika (HMIF)' => [
                'logo' => 'ormawas/hmif_logo.webp',
                'cover_photo' => 'ormawas/hmif_cover.webp',
                'prestasis' => [
                    [
                        'judul' => 'Juara 1 Gemastik Divisi UX & Software Design 2024',
                        'deskripsi' => 'Mendapatkan Medali Emas Gemastik XVII kategori User Experience Design dengan aplikasi inklusif ramah disabilitas.',
                        'tahun' => 2024,
                        'foto' => 'ormawas/hmif_prestasi_1.webp',
                    ],
                    [
                        'judul' => 'Juara 1 National Capture The Flag (Cybersecurity) 2023',
                        'deskripsi' => 'Menjuarai kompetisi keamanan siber, reverse engineering, dan web penetration testing tingkat nasional.',
                        'tahun' => 2023,
                        'foto' => 'ormawas/hmif_prestasi_2.webp',
                    ],
                ],
                'prokers' => [
                    [
                        'nama' => 'Informatics Championship & 24H Hackathon',
                        'deskripsi' => 'Kompetisi coding 24 jam nonstop tingkat nasional untuk membangun solusi kecerdasan buatan dan aplikasi web berdampak sosial tinggi.',
                        'foto' => 'ormawas/hmif_proker_1.webp',
                    ],
                    [
                        'nama' => 'Intensive Bootcamp Fullstack & AI Engineering',
                        'deskripsi' => 'Pelatihan terstruktur selama 8 pekan yang membimbing mahasiswa menguasai arsitektur backend modern, frontend framework, dan model machine learning.',
                        'foto' => 'ormawas/hmif_proker_2.webp',
                    ],
                    [
                        'nama' => 'HMIF Tech Talk bersama Software Engineer Unicorn',
                        'deskripsi' => 'Seminar industri menghadirkan Tech Lead dari startup ternama untuk berbagi best practice microservices dan tips karir tech engineer.',
                        'foto' => 'ormawas/hmif_proker_3.webp',
                    ],
                ],
            ],
            'BEM Fakultas Ekonomi dan Bisnis' => [
                'logo' => 'ormawas/bem_feb_logo.webp',
                'cover_photo' => 'ormawas/bem_feb_cover.webp',
                'prestasis' => [
                    [
                        'judul' => 'Juara 1 National Business Plan Competition 2024',
                        'deskripsi' => 'Meraih juara 1 dalam kompetisi rancang bisnis startup hijau berskala nasional dengan investor funding readiness tinggi.',
                        'tahun' => 2024,
                        'foto' => 'ormawas/bem_feb_prestasi_1.webp',
                    ],
                    [
                        'judul' => 'Best Paper Award International Economic Research Forum 2023',
                        'deskripsi' => 'Karya tulis riset mahasiswa FEB dinobatkan sebagai Best Paper dalam forum riset ekonomi pembangunan Asia Tenggara.',
                        'tahun' => 2023,
                        'foto' => 'ormawas/bem_feb_prestasi_2.webp',
                    ],
                ],
                'prokers' => [
                    [
                        'nama' => 'FEB Entrepreneurship Expo & Bazaar UMKM 2026',
                        'deskripsi' => 'Ajang gelar produk startup mahasiswa FEB, mentoring model bisnis bersama venture capitalist, serta bazaar produk UMKM lokal.',
                        'foto' => 'ormawas/bem_feb_proker_1.webp',
                    ],
                    [
                        'nama' => 'National Capital Market Summit & Sekolah Pasar Modal',
                        'deskripsi' => 'Pendidikan literasi finansial, simulasi trading saham, dan seminar pasar modal berkolaborasi dengan Bursa Efek Indonesia (BEI).',
                        'foto' => 'ormawas/bem_feb_proker_2.webp',
                    ],
                    [
                        'nama' => 'Youth Economic Leadership & Policy Forum 2026',
                        'deskripsi' => 'Konferensi kepemimpinan muda yang membahas prospek ekonomi makro dan rekomendasi kebijakan pembangunan berkelanjutan.',
                        'foto' => 'ormawas/bem_feb_proker_3.webp',
                    ],
                ],
            ],
        ];

        foreach ($ormawas as $ormawa) {
            if (isset($ormawaAssetMap[$ormawa->nama])) {
                $data = $ormawaAssetMap[$ormawa->nama];
                $ormawa->update([
                    'logo' => $data['logo'],
                    'cover_photo' => $data['cover_photo'],
                ]);

                foreach ($data['prestasis'] as $prestasi) {
                    OrmawaPrestasi::updateOrCreate(
                        ['ormawa_id' => $ormawa->id, 'judul' => $prestasi['judul']],
                        [
                            'deskripsi' => $prestasi['deskripsi'],
                            'tahun' => $prestasi['tahun'],
                            'foto' => $prestasi['foto'],
                        ]
                    );
                }

                foreach ($data['prokers'] as $proker) {
                    OrmawaProgramKerja::updateOrCreate(
                        ['ormawa_id' => $ormawa->id, 'nama' => $proker['nama']],
                        [
                            'deskripsi' => $proker['deskripsi'],
                            'foto' => $proker['foto'],
                        ]
                    );
                }
            }
        }
        echo "Logos, Covers, Proker & Prestasi (WebP) berhasil di-seed untuk 5 ormawa.\n";

        // 4. CREATE 2 RECRUITMENTS PER ORMAWA (1 DITUTUP/SIAP HITUNG + 1 DIBUKA/AKTIF PENDAFTARAN)
        $recruitmentData = [
            // 1. BEM UNSOED
            [
                'ormawa' => 'BEM UNSOED',
                'judul' => 'Seleksi Pengurus Inti BEM UNSOED 2026',
                'deskripsi' => 'Seleksi pengurus inti BEM UNSOED yang telah menyelesaikan tahap wawancara dan siap dilakukan penetapan ranking.',
                'status' => 'ditutup',
                'tanggal_buka' => now()->subDays(12)->format('Y-m-d'),
                'tanggal_tutup' => now()->subDays(1)->format('Y-m-d'),
                'divisi' => ['Biro Administrasi & Keuangan', 'Kementerian Komunikasi & Informasi'],
            ],
            [
                'ormawa' => 'BEM UNSOED',
                'judul' => 'Open Recruitment Staf Muda BEM UNSOED 2026',
                'deskripsi' => 'Pendaftaran terbuka untuk mahasiswa baru dan aktif UNSOED yang ingin berkontribusi di kementerian sosial dan PSDM.',
                'status' => 'dibuka',
                'tanggal_buka' => now()->subDays(2)->format('Y-m-d'),
                'tanggal_tutup' => now()->addDays(14)->format('Y-m-d'),
                'divisi' => ['Kementerian Sosial Masyarakat', 'Kementerian PSDM'],
            ],

            // 2. UKM Olahraga
            [
                'ormawa' => 'UKM Olahraga',
                'judul' => 'Seleksi Atlet Tim Utama Pekan Olahraga Mahasiswa',
                'deskripsi' => 'Penilaian dan perankingan atlet cabang futsal dan basket untuk delegasi kejuaraan antarkampus.',
                'status' => 'ditutup',
                'tanggal_buka' => now()->subDays(10)->format('Y-m-d'),
                'tanggal_tutup' => now()->subDays(1)->format('Y-m-d'),
                'divisi' => ['Atlet Futsal', 'Atlet Basket'],
            ],
            [
                'ormawa' => 'UKM Olahraga',
                'judul' => 'Open Recruitment Official & Panitia Rektor Cup 2026',
                'deskripsi' => 'Pendaftaran terbuka bagi mahasiswa yang berminat menjadi official lapangan dan tim dokumentasi turnamen.',
                'status' => 'dibuka',
                'tanggal_buka' => now()->subDays(1)->format('Y-m-d'),
                'tanggal_tutup' => now()->addDays(15)->format('Y-m-d'),
                'divisi' => ['Official Pertandingan', 'Divisi Publikasi & Dokumentasi'],
            ],

            // 3. BEM Fakultas Teknik
            [
                'ormawa' => 'BEM Fakultas Teknik',
                'judul' => 'Oprec Pengurus BEM FT 2026',
                'deskripsi' => 'Saatnya Teknik Bergerak! Seleksi pengurus BEM Fakultas Teknik yang telah memasuki tahap perangkingan.',
                'status' => 'ditutup',
                'tanggal_buka' => now()->subDays(10)->format('Y-m-d'),
                'tanggal_tutup' => now()->subDays(1)->format('Y-m-d'),
                'divisi' => ['Divisi Medkominfo', 'Divisi Minat Bakat'],
            ],
            [
                'ormawa' => 'BEM Fakultas Teknik',
                'judul' => 'Open Recruitment Volunteer Dies Natalis FT UNSOED',
                'deskripsi' => 'Pendaftaran terbuka relawan mahasiswa teknik untuk perayaan Dies Natalis Fakultas Teknik UNSOED.',
                'status' => 'dibuka',
                'tanggal_buka' => now()->subDays(3)->format('Y-m-d'),
                'tanggal_tutup' => now()->addDays(12)->format('Y-m-d'),
                'divisi' => ['Divisi Acara & Panggung', 'Divisi Sponsorship & Humas'],
            ],

            // 4. HMIF (Himpunan Mahasiswa Informatika)
            [
                'ormawa' => 'Himpunan Mahasiswa Informatika (HMIF)',
                'judul' => 'Seleksi Panitia Informatics Championship (IC) 2026',
                'deskripsi' => 'Seleksi kepanitiaan lomba IT nasional tingkat mahasiswa yang telah menyelesaikan proses wawancara.',
                'status' => 'ditutup',
                'tanggal_buka' => now()->subDays(10)->format('Y-m-d'),
                'tanggal_tutup' => now()->subDays(1)->format('Y-m-d'),
                'divisi' => ['Divisi Kompetisi & Soal', 'Divisi Hubungan Eksternal'],
            ],
            [
                'ormawa' => 'Himpunan Mahasiswa Informatika (HMIF)',
                'judul' => 'Open Recruitment Pengurus HMIF UNSOED Periode 2026',
                'deskripsi' => 'Pendaftaran calon pengurus baru HMIF UNSOED untuk divisi riset teknologi dan media kreatif.',
                'status' => 'dibuka',
                'tanggal_buka' => now()->subDays(2)->format('Y-m-d'),
                'tanggal_tutup' => now()->addDays(14)->format('Y-m-d'),
                'divisi' => ['Divisi Keilmuan & Riset IT', 'Divisi Media Kreatif'],
            ],

            // 5. BEM Fakultas Ekonomi dan Bisnis
            [
                'ormawa' => 'BEM Fakultas Ekonomi dan Bisnis',
                'judul' => 'Seleksi Delegasi Business Plan & Innovation FEB 2026',
                'deskripsi' => 'Seleksi calon delegasi kompetisi bisnis mahasiswa FEB yang siap ditentukan rankingnya.',
                'status' => 'ditutup',
                'tanggal_buka' => now()->subDays(10)->format('Y-m-d'),
                'tanggal_tutup' => now()->subDays(1)->format('Y-m-d'),
                'divisi' => ['Tim Business Plan', 'Tim Marketing Communication'],
            ],
            [
                'ormawa' => 'BEM Fakultas Ekonomi dan Bisnis',
                'judul' => 'Open Recruitment Pengurus BEM FEB UNSOED Periode 2026',
                'deskripsi' => 'Pendaftaran calon pengurus BEM FEB untuk mewadahi minat kewirausahaan dan advokasi mahasiswa.',
                'status' => 'dibuka',
                'tanggal_buka' => now()->subDays(2)->format('Y-m-d'),
                'tanggal_tutup' => now()->addDays(14)->format('Y-m-d'),
                'divisi' => ['Divisi Kewirausahaan Mahasiswa', 'Divisi Advokasi Kesejahteraan'],
            ],
        ];

        $mahasiswas = User::where('role', 'mahasiswa')->get();
        $totalApps = 0;
        $defaultLabels = ['Sangat Kurang', 'Kurang', 'Cukup', 'Baik', 'Sangat Baik'];

        foreach ($recruitmentData as $rData) {
            $ormawa = Ormawa::where('nama', $rData['ormawa'])->first();
            if (!$ormawa) continue;

            $recruitment = Recruitment::updateOrCreate(
                ['ormawa_id' => $ormawa->id, 'judul' => $rData['judul']],
                [
                    'deskripsi' => $rData['deskripsi'],
                    'persyaratan' => "1. Mahasiswa aktif\n2. Berkomitmen tinggi\n3. Lulus wawancara",
                    'tanggal_buka' => $rData['tanggal_buka'],
                    'tanggal_tutup' => $rData['tanggal_tutup'],
                    'status' => $rData['status'],
                ]
            );

            foreach ($rData['divisi'] as $divName) {
                $division = RecruitmentDivision::updateOrCreate(
                    ['recruitment_id' => $recruitment->id, 'nama' => $divName],
                    ['kuota' => 2, 'deskripsi' => 'Fokus pada pengembangan bidang ' . strtolower($divName)]
                );

                // Khusus Divisi Medkominfo di BEM FT: Gunakan data Anton & Budi sesuai contoh Excel
                if ($divName === 'Divisi Medkominfo') {
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

                    // Helper closure untuk resolve fakultas & jurusan
                    $resolveFakultas = fn($nama) => \App\Models\Fakultas::where('nama_fakultas', 'like', '%' . $nama . '%')->first()?->id;
                    $resolveJurusan  = fn($nama) => \App\Models\Jurusan::where('nama_jurusan',   'like', '%' . $nama . '%')->first()?->id;

                    // Pendaftar khusus: Anton & Budi (profil lengkap)
                    $anton = User::firstOrCreate(
                        ['email' => 'anton@ormawa-unsoed.test'],
                        ['name' => 'Anton Firmansyah', 'password' => bcrypt('password'), 'role' => 'mahasiswa', 'is_active' => true]
                    );
                    MahasiswaProfile::updateOrCreate(['user_id' => $anton->id], [
                        'nim'         => 'H1D021011',
                        'fakultas_id' => $resolveFakultas('Teknik'),
                        'jurusan_id'  => $resolveJurusan('Informatika'),
                        'angkatan'    => '2021',
                        'no_hp'       => '081234567801',
                    ]);

                    $budi = User::firstOrCreate(
                        ['email' => 'budi@ormawa-unsoed.test'],
                        ['name' => 'Budi Setiawan', 'password' => bcrypt('password'), 'role' => 'mahasiswa', 'is_active' => true]
                    );
                    MahasiswaProfile::updateOrCreate(['user_id' => $budi->id], [
                        'nim'         => 'H1D021012',
                        'fakultas_id' => $resolveFakultas('Teknik'),
                        'jurusan_id'  => $resolveJurusan('Informatika'),
                        'angkatan'    => '2021',
                        'no_hp'       => '081234567802',
                    ]);

                    $appAnton = Application::updateOrCreate(
                        ['recruitment_id' => $recruitment->id, 'recruitment_division_id' => $division->id, 'user_id' => $anton->id],
                        ['status' => 'diproses']
                    );
                    $appBudi = Application::updateOrCreate(
                        ['recruitment_id' => $recruitment->id, 'recruitment_division_id' => $division->id, 'user_id' => $budi->id],
                        ['status' => 'diproses']
                    );

                    $antonScores = [2, 5, 3, 5, 4];
                    $budiScores  = [4, 5, 4, 3, 1];

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

                    // 8 pendaftar tambahan dengan nilai lebih rendah dari Anton & Budi
                    $extraApplicants = [
                        [
                            'email' => 'candra@ormawa-unsoed.test', 'name' => 'Candra Wijaya',
                            'nim' => 'H1D022013', 'fakultas' => 'Teknik', 'jurusan' => 'Informatika', 'angkatan' => '2022', 'no_hp' => '081234567803',
                            'scores' => [1, 3, 2, 3, 2],
                        ],
                        [
                            'email' => 'dewi@ormawa-unsoed.test', 'name' => 'Dewi Rahayu',
                            'nim' => 'H1D022014', 'fakultas' => 'Teknik', 'jurusan' => 'Informatika', 'angkatan' => '2022', 'no_hp' => '081234567804',
                            'scores' => [2, 2, 1, 2, 3],
                        ],
                        [
                            'email' => 'eko@ormawa-unsoed.test', 'name' => 'Eko Prasetyo',
                            'nim' => 'H1D023015', 'fakultas' => 'Teknik', 'jurusan' => 'Informatika', 'angkatan' => '2023', 'no_hp' => '081234567805',
                            'scores' => [3, 1, 2, 2, 1],
                        ],
                        [
                            'email' => 'fitri@ormawa-unsoed.test', 'name' => 'Fitri Handayani',
                            'nim' => 'H1A022016', 'fakultas' => 'Ekonomi dan Bisnis', 'jurusan' => 'Manajemen', 'angkatan' => '2022', 'no_hp' => '081234567806',
                            'scores' => [1, 2, 3, 1, 2],
                        ],
                        [
                            'email' => 'galih@ormawa-unsoed.test', 'name' => 'Galih Saputra',
                            'nim' => 'H1A022017', 'fakultas' => 'Ekonomi dan Bisnis', 'jurusan' => 'Akuntansi', 'angkatan' => '2022', 'no_hp' => '081234567807',
                            'scores' => [2, 3, 1, 3, 1],
                        ],
                        [
                            'email' => 'hana@ormawa-unsoed.test', 'name' => 'Hana Pertiwi',
                            'nim' => 'H2A022018', 'fakultas' => 'Hukum', 'jurusan' => 'Hukum', 'angkatan' => '2022', 'no_hp' => '081234567808',
                            'scores' => [1, 1, 2, 2, 3],
                        ],
                        [
                            'email' => 'irvan@ormawa-unsoed.test', 'name' => 'Irvan Maulana',
                            'nim' => 'H3A021019', 'fakultas' => 'Ilmu Sosial dan Ilmu Politik', 'jurusan' => 'Ilmu Komunikasi', 'angkatan' => '2021', 'no_hp' => '081234567809',
                            'scores' => [3, 2, 1, 1, 2],
                        ],
                        [
                            'email' => 'julia@ormawa-unsoed.test', 'name' => 'Julia Sari',
                            'nim' => 'H3A023020', 'fakultas' => 'Ilmu Sosial dan Ilmu Politik', 'jurusan' => 'Administrasi Publik', 'angkatan' => '2023', 'no_hp' => '081234567810',
                            'scores' => [2, 1, 3, 2, 1],
                        ],
                    ];

                    foreach ($extraApplicants as $extra) {
                        $extraUser = User::firstOrCreate(
                            ['email' => $extra['email']],
                            ['name' => $extra['name'], 'password' => bcrypt('password'), 'role' => 'mahasiswa', 'is_active' => true]
                        );
                        MahasiswaProfile::updateOrCreate(['user_id' => $extraUser->id], [
                            'nim'         => $extra['nim'],
                            'fakultas_id' => $resolveFakultas($extra['fakultas']),
                            'jurusan_id'  => $resolveJurusan($extra['jurusan']),
                            'angkatan'    => $extra['angkatan'],
                            'no_hp'       => $extra['no_hp'],
                        ]);

                        $extraApp = Application::updateOrCreate(
                            ['recruitment_id' => $recruitment->id, 'recruitment_division_id' => $division->id, 'user_id' => $extraUser->id],
                            ['status' => 'diproses']
                        );

                        foreach ($kObjects as $i => $crit) {
                            $val = $extra['scores'][$i];
                            $gap = $val - $crit->target_value;
                            ApplicationScore::updateOrCreate(
                                ['application_id' => $extraApp->id, 'criteria_id' => $crit->id],
                                ['actual_value' => $val, 'gap' => $gap, 'bobot_gap' => $this->getBobotGap($gap)]
                            );
                        }
                        $totalApps++;
                    }

                    continue;
                }

                // Untuk divisi lainnya:
                // Buat 2 Aspek (Total Bobot 100%)
                $aspek1 = Aspect::updateOrCreate(
                    ['recruitment_division_id' => $division->id, 'nama' => 'Kompetensi Bidang'],
                    ['bobot' => 50, 'cf_percentage' => 60, 'sf_percentage' => 40, 'urutan' => 1]
                );
                $aspek2 = Aspect::updateOrCreate(
                    ['recruitment_division_id' => $division->id, 'nama' => 'Soft Skill & Wawancara'],
                    ['bobot' => 50, 'cf_percentage' => 60, 'sf_percentage' => 40, 'urutan' => 2]
                );

                $kriteria = [
                    ['aspek' => $aspek1, 'nama' => 'Pengalaman & Portofolio', 'tipe' => 'core', 'target' => 4],
                    ['aspek' => $aspek1, 'nama' => 'Pemahaman Peran', 'tipe' => 'secondary', 'target' => 3],
                    ['aspek' => $aspek2, 'nama' => 'Komunikasi & Kerjasama Tim', 'tipe' => 'core', 'target' => 4],
                    ['aspek' => $aspek2, 'nama' => 'Komitmen & Motivasi', 'tipe' => 'secondary', 'target' => 4],
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

                // Jika rekrutmen ditutup: masukkan 2 pelamar dengan nilai LENGKAP siap hitung
                if ($rData['status'] === 'ditutup') {
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
                } else {
                    // Jika rekrutmen dibuka: masukkan 1 pelamar baru (status 'terkirim', belum dinilai)
                    // Sehingga admin bisa mencoba menguji alur input nilai setelah menutup rekrutmen
                    $mhsAcak = $mahasiswas->random(1)->first();
                    $app = Application::updateOrCreate(
                        ['recruitment_id' => $recruitment->id, 'recruitment_division_id' => $division->id, 'user_id' => $mhsAcak->id],
                        ['motivasi' => 'Saya sangat tertarik bergabung di divisi ' . $divName . ' untuk mengembangkan potensi diri.', 'status' => 'terkirim']
                    );

                    foreach ($critObjects as $c) {
                        ApplicationScore::updateOrCreate(
                            ['application_id' => $app->id, 'criteria_id' => $c->id],
                            ['actual_value' => null, 'gap' => null, 'bobot_gap' => null]
                        );
                    }
                    $totalApps++;
                }
            }
        }

        echo "10 Rekrutmen (5 Ditutup/Siap Hitung + 5 Dibuka/Aktif Pendaftaran) untuk seluruh 5 Ormawa berhasil dibuat ({$totalApps} pendaftar).\n";
    }

    private function getBobotGap($gap)
    {
        $konversi = [0 => 5, 1 => 4.5, -1 => 4, 2 => 3.5, -2 => 3, 3 => 2.5, -3 => 2, 4 => 1.5, -4 => 1];
        return $konversi[$gap] ?? 0;
    }
}