<?php

namespace App\Console\Commands;

use App\Traits\NormalizesFakultasJurusan;
use Illuminate\Console\Command;

class MigrateOrmawaFakultasJurusan extends Command
{
    use NormalizesFakultasJurusan;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:migrate-ormawa-fakultas-jurusan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrasi data text fakultas & jurusan di ormawa ke tabel master.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Memulai migrasi data fakultas & jurusan untuk Ormawa...");

        $ormawas = \App\Models\Ormawa::where(function($q) {
                $q->whereNotNull('fakultas')->orWhereNotNull('jurusan');
            })
            ->whereNull('fakultas_id')
            ->whereNull('jurusan_id')
            ->get();

        $this->info("Menemukan {$ormawas->count()} ormawa untuk dimigrasi.");

        $migratedCount = 0;

        foreach ($ormawas as $ormawa) {
            $fakultasId = null;
            $jurusanId = null;

            $fakultas = $this->normalizeFakultas($ormawa->fakultas ?? '');
            if ($fakultas) {
                $fakultasId = $fakultas->id;
                
                $jurusan = $this->normalizeJurusan($ormawa->jurusan ?? '', $fakultas);
                if ($jurusan) {
                    $jurusanId = $jurusan->id;
                }
            }

            // Update record ormawa
            $ormawa->update([
                'fakultas_id' => $fakultasId,
                'jurusan_id' => $jurusanId,
            ]);

            $migratedCount++;
        }

        $this->info("Berhasil memigrasi {$migratedCount} ormawa.");

        $this->info("\n--- CONTOH HASIL MIGRASI ---");
        $sample = \App\Models\Ormawa::with(['fakultasRel', 'jurusanRel'])->take(20)->get();
        $headers = ['Nama Ormawa', 'Fakultas (String Lama)', 'Jurusan (String Lama)', 'Fakultas (Relasi Baru)', 'Jurusan (Relasi Baru)'];
        $data = $sample->map(function ($o) {
            return [
                $o->nama,
                $o->fakultas ?: 'NULL',
                $o->jurusan ?: 'NULL',
                $o->fakultasRel ? $o->fakultasRel->nama_fakultas : 'NULL',
                $o->jurusanRel ? $o->jurusanRel->nama_jurusan : 'NULL',
            ];
        })->toArray();

        $this->table($headers, $data);
    }
}
