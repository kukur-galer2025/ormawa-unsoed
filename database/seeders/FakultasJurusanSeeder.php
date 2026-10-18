<?php

namespace Database\Seeders;

use App\Models\Fakultas;
use App\Models\Jurusan;
use Illuminate\Database\Seeder;

class FakultasJurusanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fakultasDanJurusan = [
            'Fakultas Pertanian' => [
                'D3 Agribisnis',
                'D3 Perencanaan Sumber Daya Lahan',
                'Agroteknologi',
                'S1 Agribisnis',
                'Teknologi Pangan',
                'Teknik Pertanian',
                'Proteksi Tanaman',
            ],
            'Fakultas Biologi' => [
                'Biologi (Reguler & Internasional)',
                'Mikrobiologi',
                'Biologi Terapan',
            ],
            'Fakultas Ekonomi dan Bisnis' => [
                'D3 Akuntansi',
                'D3 Administrasi Bisnis',
                'D3 Administrasi Perkantoran',
                'D3 Bisnis Internasional',
                'Manajemen (Reguler & Internasional)',
                'S1 Akuntansi (Reguler & Internasional)',
                'Ekonomi Pembangunan (Reguler & Internasional)',
                'Pendidikan Ekonomi',
            ],
            'Fakultas Peternakan' => [
                'D3 Budi Daya Ternak',
                'Peternakan',
            ],
            'Fakultas Hukum' => [
                'Hukum (Reguler & Internasional)',
            ],
            'Fakultas Ilmu Sosial dan Ilmu Politik' => [
                'Administrasi Publik',
                'Ilmu Komunikasi',
                'Sosiologi',
                'Ilmu Politik',
                'Hubungan Internasional (Reguler & Internasional)',
            ],
            'Fakultas Kedokteran' => [
                'Pendidikan Dokter',
                'Kedokteran Gigi',
            ],
            'Fakultas Teknik' => [
                'Teknik Sipil',
                'Teknik Elektro',
                'Teknik Geologi',
                'Informatika',
                'Teknik Industri',
                'Teknik Mesin',
                'Teknik Komputer',
                'Arsitektur',
            ],
            'Fakultas Ilmu-Ilmu Kesehatan' => [
                'Kesehatan Masyarakat',
                'Keperawatan (Reguler & Internasional)',
                'Farmasi',
                'Ilmu Gizi',
                'Pendidikan Jasmani',
            ],
            'Fakultas Ilmu Budaya' => [
                'D3 Bahasa Inggris',
                'D3 Bahasa Mandarin',
                'Sastra Indonesia',
                'Sastra Inggris',
                'Sastra Jepang',
                'Pendidikan Bahasa Indonesia',
                'Pendidikan Bahasa Inggris',
                'Pendidikan Bahasa Jepang',
            ],
            'Fakultas Matematika dan Ilmu Pengetahuan Alam' => [
                'Matematika',
                'Fisika',
                'Kimia',
                'Statistika',
            ],
            'Fakultas Perikanan dan Ilmu Kelautan' => [
                'D3 Budi Daya Ikan',
                'Manajemen Sumber Daya Perairan',
                'Akuakultur (Budi Daya Perairan)',
                'Ilmu Kelautan',
            ],
        ];

        foreach ($fakultasDanJurusan as $namaFakultas => $daftarJurusan) {
            $fakultas = Fakultas::firstOrCreate(['nama_fakultas' => $namaFakultas]);

            foreach ($daftarJurusan as $namaJurusan) {
                Jurusan::firstOrCreate([
                    'fakultas_id' => $fakultas->id,
                    'nama_jurusan' => $namaJurusan,
                ]);
            }
        }

        echo "12 Fakultas dan seluruh Program Diploma (D3) & Sarjana (S1) berhasil di-seed.\n";
    }
}
