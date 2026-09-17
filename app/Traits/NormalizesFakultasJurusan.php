<?php

namespace App\Traits;

use App\Models\Fakultas;
use App\Models\Jurusan;

trait NormalizesFakultasJurusan
{
    /**
     * Peta alias/singkatan nama fakultas ke nama lengkap standar.
     */
    protected function getAliasMap(): array
    {
        return [
            'fisip' => 'Fakultas Ilmu Sosial dan Ilmu Politik',
            'feb' => 'Fakultas Ekonomi dan Bisnis',
            'ft' => 'Fakultas Teknik',
            'fikes' => 'Fakultas Ilmu-Ilmu Kesehatan',
            'fk' => 'Fakultas Kedokteran',
            'fh' => 'Fakultas Hukum',
            'fapet' => 'Fakultas Peternakan',
            'faperta' => 'Fakultas Pertanian',
            'fib' => 'Fakultas Ilmu Budaya',
            'fmipa' => 'Fakultas Matematika dan Ilmu Pengetahuan Alam',
            'fpik' => 'Fakultas Perikanan dan Ilmu Kelautan',
            'bio' => 'Fakultas Biologi',
        ];
    }

    /**
     * Normalisasi nama fakultas dan kembalikan Fakultas model (firstOrCreate).
     */
    protected function normalizeFakultas(string $fakultasName): ?Fakultas
    {
        $fakultasName = trim($fakultasName);
        if (empty($fakultasName)) {
            return null;
        }

        $aliasMap = $this->getAliasMap();
        $lowerName = strtolower($fakultasName);

        if (array_key_exists($lowerName, $aliasMap)) {
            $fakultasName = $aliasMap[$lowerName];
        } else {
            if (!str_contains($lowerName, 'fakultas')) {
                $fakultasName = 'Fakultas ' . $fakultasName;
            }
        }

        return Fakultas::firstOrCreate(['nama_fakultas' => $fakultasName]);
    }

    /**
     * Normalisasi nama jurusan dan kembalikan Jurusan model (firstOrCreate).
     */
    protected function normalizeJurusan(string $jurusanName, Fakultas $fakultas): ?Jurusan
    {
        $jurusanName = trim($jurusanName);
        if (empty($jurusanName)) {
            return null;
        }

        return Jurusan::firstOrCreate([
            'fakultas_id' => $fakultas->id,
            'nama_jurusan' => $jurusanName,
        ]);
    }
}
