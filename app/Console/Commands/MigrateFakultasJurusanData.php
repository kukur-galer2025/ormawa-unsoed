<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MigrateFakultasJurusanData extends Command
{
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
    protected $description = 'Migrasi data dari kolom teks fakultas dan jurusan ke master data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $profiles = \App\Models\MahasiswaProfile::whereNotNull('fakultas')
            ->orWhereNotNull('jurusan')
            ->get();

        $this->info("Menemukan {$profiles->count()} profil untuk dimigrasi.");

        $migratedCount = 0;

        foreach ($profiles as $profile) {
            $fakultasName = trim($profile->fakultas);
            $jurusanName = trim($profile->jurusan);

            $fakultasId = null;
            $jurusanId = null;

            if (!empty($fakultasName)) {
                // Normalisasi nama fakultas
                if (!str_contains(strtolower($fakultasName), 'fakultas')) {
                    $fakultasName = 'Fakultas ' . $fakultasName;
                }

                // Cari atau buat Fakultas (case-insensitive di database by default)
                $fakultas = \App\Models\Fakultas::firstOrCreate(['nama_fakultas' => $fakultasName]);
                $fakultasId = $fakultas->id;

                if (!empty($jurusanName)) {
                    // Cari atau buat Jurusan (terikat pada Fakultas ini)
                    $jurusan = \App\Models\Jurusan::firstOrCreate([
                        'fakultas_id' => $fakultasId,
                        'nama_jurusan' => $jurusanName,
                    ]);
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
        $sample = \App\Models\MahasiswaProfile::with(['fakultasRel', 'jurusanRel'])->take(5)->get();
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
