<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Traits\NormalizesFakultasJurusan;

class MigrateFakultasJurusanData extends Command
{
    use NormalizesFakultasJurusan;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:migrate-fakultas-jurusan-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrasi data text fakultas & jurusan di profil mahasiswa ke tabel master (tahap transisi).';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Memulai migrasi data fakultas & jurusan...");

        // Ambil semua profil yang fakultas/jurusannya belum null (masih pakai string lama)
        // dan id relasi-nya masih kosong
        $profiles = \App\Models\MahasiswaProfile::where(function($q) {
                $q->whereNotNull('fakultas')->orWhereNotNull('jurusan');
            })
            ->whereNull('fakultas_id')
            ->whereNull('jurusan_id')
            ->get();

        $this->info("Menemukan {$profiles->count()} profil untuk dimigrasi.");

        $migratedCount = 0;

        foreach ($profiles as $profile) {
            $fakultasId = null;
            $jurusanId = null;

            $fakultas = $this->normalizeFakultas($profile->fakultas ?? '');
            if ($fakultas) {
                $fakultasId = $fakultas->id;
                
                $jurusan = $this->normalizeJurusan($profile->jurusan ?? '', $fakultas);
                if ($jurusan) {
                    $jurusanId = $jurusan->id;
                }
            }

            // Update profile
            $profile->update([
                'fakultas_id' => $fakultasId,
                'jurusan_id' => $jurusanId,
            ]);

            $migratedCount++;
        }

        $this->info("Berhasil memigrasi {$migratedCount} profil.");

        $this->info("\n--- CONTOH HASIL MIGRASI ---");
        $sample = \App\Models\MahasiswaProfile::with(['fakultasRel', 'jurusanRel'])->take(20)->get();
        $headers = ['NIM', 'Fakultas (String Lama)', 'Jurusan (String Lama)', 'Fakultas (Relasi Baru)', 'Jurusan (Relasi Baru)'];
        $data = $sample->map(function ($p) {
            return [
                $p->nim,
                $p->getRawOriginal('fakultas'),
                $p->getRawOriginal('jurusan'),
                $p->fakultasRel ? $p->fakultasRel->nama_fakultas : 'NULL',
                $p->jurusanRel ? $p->jurusanRel->nama_jurusan : 'NULL',
            ];
        })->toArray();
        $this->table($headers, $data);
    }
}
