<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FakultasJurusanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fakultasNames = [
            'Fakultas Pertanian',
            'Fakultas Biologi',
            'Fakultas Ekonomi dan Bisnis',
            'Fakultas Peternakan',
            'Fakultas Hukum',
            'Fakultas Ilmu Sosial dan Ilmu Politik',
            'Fakultas Kedokteran',
            'Fakultas Teknik',
            'Fakultas Ilmu-Ilmu Kesehatan',
            'Fakultas Ilmu Budaya',
            'Fakultas Matematika dan Ilmu Pengetahuan Alam',
            'Fakultas Perikanan dan Ilmu Kelautan',
        ];

        foreach ($fakultasNames as $namaFakultas) {
            $fakultas = \App\Models\Fakultas::firstOrCreate(['nama_fakultas' => $namaFakultas]);

            // Hanya Fakultas Teknik yang diisi jurusan awalnya
            if ($namaFakultas === 'Fakultas Teknik') {
                $jurusanTeknik = [
                    'Teknik Sipil',
                    'Teknik Elektro',
                    'Teknik Geologi',
                    'Informatika',
                    'Teknik Industri',
                ];

                foreach ($jurusanTeknik as $namaJurusan) {
                    \App\Models\Jurusan::firstOrCreate([
                        'fakultas_id' => $fakultas->id,
                        'nama_jurusan' => $namaJurusan,
                    ]);
                }
            }
        }
    }
}
