<?php

namespace Database\Seeders;

use App\Models\Fakultas;
use App\Models\User;
use Illuminate\Database\Seeder;

class PimpinanSeeder extends Seeder
{
    public function run(): void
    {
        // Sample Wakil Rektor (scope: all ormawa)
        User::updateOrCreate(
            ['email' => 'wakilrektor@ormawa-unsoed.test'],
            [
                'name'       => 'Prof. Dr. Wakil Rektor',
                'password'   => bcrypt('password'),
                'role'       => 'wakil_rektor',
                'is_active'  => true,
                'fakultas_id' => null,
            ]
        );

        // Find Fakultas Teknik for Wakil Dekan sample
        $fakultasTeknik = Fakultas::where('nama_fakultas', 'like', '%Teknik%')->first();

        User::updateOrCreate(
            ['email' => 'wakildekan.teknik@ormawa-unsoed.test'],
            [
                'name'       => 'Dr. Wakil Dekan Teknik',
                'password'   => bcrypt('password'),
                'role'       => 'wakil_dekan',
                'is_active'  => true,
                'fakultas_id' => $fakultasTeknik?->id,
            ]
        );

        echo "Pimpinan accounts created:\n";
        echo "  - wakilrektor@ormawa-unsoed.test / password\n";
        echo "  - wakildekan.teknik@ormawa-unsoed.test / password\n";
    }
}
