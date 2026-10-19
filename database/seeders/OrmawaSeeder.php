<?php

namespace Database\Seeders;

use App\Models\Fakultas;
use App\Models\Jurusan;
use App\Models\Ormawa;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class OrmawaSeeder extends Seeder
{
    public function run(): void
    {
        $fakultasTeknik = Fakultas::where('nama_fakultas', 'Fakultas Teknik')->first();
        $jurusanInformatika = Jurusan::where('nama_jurusan', 'Informatika')->first();
        $fakultasFEB = Fakultas::where('nama_fakultas', 'Fakultas Ekonomi dan Bisnis')->first();

        $ormawas = [
            [
                'nama' => 'BEM UNSOED',
                'slug' => 'bem-unsoed',
                'tingkat' => 'Universitas',
                'fakultas_id' => null,
                'jurusan_id' => null,
                'deskripsi' => 'Badan Eksekutif Mahasiswa Universitas Jenderal Soedirman.',
                'visi' => 'Mewujudkan mahasiswa UNSOED yang kritis, progresif, dan berdaya saing.',
                'misi' => "1. Meningkatkan kualitas akademik mahasiswa.\n2. Mengembangkan soft skill dan kepemimpinan.",
                'logo' => 'ormawas/bem_unsoed_logo.webp',
                'cover_photo' => 'ormawas/bem_unsoed_cover.webp',
                'kontak_email' => 'bem@unsoed.ac.id',
                'kontak_instagram' => '@bemunsoed',
                'is_active' => true,
            ],
            [
                'nama' => 'UKM Olahraga',
                'slug' => 'ukm-olahraga',
                'tingkat' => 'Universitas',
                'fakultas_id' => null,
                'jurusan_id' => null,
                'deskripsi' => 'Unit Kegiatan Mahasiswa di bidang olahraga dan kebugaran.',
                'visi' => 'Mens sana in corpore sano - Membangun generasi sehat dan berprestasi.',
                'misi' => "1. Membina atlet berprestasi.\n2. Memasyarakatkan olahraga di kampus.",
                'logo' => 'ormawas/ukm_olahraga_logo.webp',
                'cover_photo' => 'ormawas/ukm_olahraga_cover.webp',
                'kontak_email' => 'olahraga@unsoed.ac.id',
                'kontak_instagram' => '@ukmolahraga',
                'is_active' => true,
            ],
            [
                'nama' => 'BEM Fakultas Teknik',
                'slug' => 'bem-fakultas-teknik',
                'tingkat' => 'Fakultas',
                'fakultas_id' => $fakultasTeknik?->id,
                'jurusan_id' => null,
                'deskripsi' => 'Badan Eksekutif Mahasiswa Fakultas Teknik Universitas Jenderal Soedirman.',
                'visi' => 'Teknik Solid, Teknik Jaya!',
                'misi' => "1. Menyatukan mahasiswa teknik.\n2. Mengabdi pada masyarakat melalui inovasi teknologi.",
                'logo' => 'ormawas/bem_ft_logo.webp',
                'cover_photo' => 'ormawas/bem_ft_cover.webp',
                'kontak_email' => 'bemft@unsoed.ac.id',
                'kontak_instagram' => '@bemftunsoed',
                'is_active' => true,
            ],
            [
                'nama' => 'Himpunan Mahasiswa Informatika (HMIF)',
                'slug' => 'hmif-unsoed',
                'tingkat' => 'Jurusan',
                'fakultas_id' => $fakultasTeknik?->id,
                'jurusan_id' => $jurusanInformatika?->id,
                'deskripsi' => 'Himpunan mahasiswa jurusan informatika Fakultas Teknik UNSOED.',
                'visi' => 'Inovatif, adaptif, dan unggul dalam teknologi informasi.',
                'misi' => "1. Menumbuhkan riset dan coding culture.\n2. Membangun jejaring profesional industri IT.",
                'logo' => 'ormawas/hmif_logo.webp',
                'cover_photo' => 'ormawas/hmif_cover.webp',
                'kontak_email' => 'hmif@unsoed.ac.id',
                'kontak_instagram' => '@hmif_unsoed',
                'is_active' => true,
            ],
            [
                'nama' => 'BEM Fakultas Ekonomi dan Bisnis',
                'slug' => 'bem-feb-unsoed',
                'tingkat' => 'Fakultas',
                'fakultas_id' => $fakultasFEB?->id,
                'jurusan_id' => null,
                'deskripsi' => 'Badan Eksekutif Mahasiswa Fakultas Ekonomi dan Bisnis UNSOED.',
                'visi' => 'Membangun sinergi mahasiswa FEB berjiwa sociopreneur.',
                'misi' => "1. Mengembangkan jiwa kewirausahaan mahasiswa.\n2. Mewadahi aspirasi dan advokasi mahasiswa FEB.",
                'logo' => 'ormawas/bem_feb_logo.webp',
                'cover_photo' => 'ormawas/bem_feb_cover.webp',
                'kontak_email' => 'bemfeb@unsoed.ac.id',
                'kontak_instagram' => '@bemfebunsoed',
                'is_active' => true,
            ],
        ];

        foreach ($ormawas as $item) {
            Ormawa::updateOrCreate(['nama' => $item['nama']], $item);
        }

        echo "Organisasi Mahasiswa (Ormawa) berhasil di-seed.\n";
    }
}
