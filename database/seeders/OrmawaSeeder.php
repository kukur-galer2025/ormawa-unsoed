<?php

namespace Database\Seeders;

use App\Models\Ormawa;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class OrmawaSeeder extends Seeder
{
    public function run(): void
    {
        $ormawas = [
            [
                'nama' => 'BEM UNSOED',
                'slug' => Str::slug('BEM UNSOED'),
                'tingkat' => 'Universitas',
                'deskripsi' => 'Badan Eksekutif Mahasiswa Universitas Jenderal Soedirman.',
                'visi' => 'Mewujudkan mahasiswa UNSOED yang kritis, progresif, dan berdaya saing.',
                'misi' => "1. Meningkatkan kualitas akademik.\n2. Mengembangkan soft skill.",
                'kontak_email' => 'bem@unsoed.ac.id',
                'kontak_instagram' => '@bemunsoed',
                'is_active' => true,
            ],
            [
                'nama' => 'BEM Fakultas Teknik',
                'slug' => Str::slug('BEM Fakultas Teknik'),
                'tingkat' => 'Fakultas',
                'fakultas' => 'Teknik',
                'deskripsi' => 'Badan Eksekutif Mahasiswa Fakultas Teknik Universitas Jenderal Soedirman.',
                'visi' => 'Teknik Solid, Teknik Jaya!',
                'misi' => "1. Menyatukan mahasiswa teknik.\n2. Mengabdi pada masyarakat.",
                'kontak_email' => 'bemft@unsoed.ac.id',
                'kontak_instagram' => '@bemftunsoed',
                'is_active' => true,
            ],
            [
                'nama' => 'UKM Olahraga',
                'slug' => Str::slug('UKM Olahraga'),
                'tingkat' => 'Universitas',
                'deskripsi' => 'Unit Kegiatan Mahasiswa di bidang olahraga.',
                'visi' => 'Mens sana in corpore sano.',
                'misi' => "1. Membina atlet berprestasi.\n2. Memasyarakatkan olahraga.",
                'kontak_email' => 'olahraga@unsoed.ac.id',
                'kontak_instagram' => '@ukmolahraga',
                'is_active' => true,
            ]
        ];

        foreach ($ormawas as $ormawa) {
            Ormawa::create($ormawa);
        }
    }
}