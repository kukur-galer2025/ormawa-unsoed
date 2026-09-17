<?php

namespace Tests\Feature;

use App\Models\Ormawa;
use App\Models\Recruitment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecruitmentScopeTest extends TestCase
{
    use RefreshDatabase;

    public function test_really_closed_scope_precedence_bug()
    {
        // 1. Buat dummy ormawa agar constraint foreign key terpenuhi
        $ormawa = Ormawa::create([
            'nama' => 'BEM Unsoed',
            'tingkat' => 'Universitas',
            'deskripsi' => 'Badan Eksekutif Mahasiswa Universitas Jenderal Soedirman',
            'kategori' => 'BEM',
            'visi_misi' => 'Visi Misi BEM',
            'is_active' => true,
        ]);

        // 2. Rekrutmen BEM Fakultas Teknik (status = ditutup)
        Recruitment::create([
            'ormawa_id' => $ormawa->id,
            'judul' => 'Oprec BEM Fakultas Teknik',
            'deskripsi' => 'Deskripsi BEM FT',
            'persyaratan' => 'Syarat',
            'tanggal_buka' => now()->subDays(10),
            'tanggal_tutup' => now()->subDays(5),
            'status' => 'ditutup',
        ]);

        // 3. Rekrutmen HIMA Kimia (status = ditutup)
        Recruitment::create([
            'ormawa_id' => $ormawa->id,
            'judul' => 'Oprec HIMA Kimia',
            'deskripsi' => 'Deskripsi HIMA',
            'persyaratan' => 'Syarat',
            'tanggal_buka' => now()->subDays(10),
            'tanggal_tutup' => now()->subDays(5),
            'status' => 'ditutup',
        ]);

        // Query reallyClosed di-chain dengan pencarian BEM
        $results = Recruitment::reallyClosed()
            ->where('judul', 'like', '%BEM%')
            ->get();

        // Jika bug precedence masih ada, hasil query akan mengembalikan KEDUA record
        // Karena query menjadi: WHERE status = 'ditutup' OR (status = 'dibuka' AND ... AND judul LIKE '%BEM%')
        // Dengan perbaikan closure, query menjadi: WHERE (status = 'ditutup' OR (status = 'dibuka' AND ...)) AND judul LIKE '%BEM%'
        $this->assertCount(1, $results);
        $this->assertEquals('Oprec BEM Fakultas Teknik', $results->first()->judul);
    }
}
