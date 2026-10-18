<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            FakultasJurusanSeeder::class,
            SuperadminSeeder::class,
            PimpinanSeeder::class,
            OrmawaSeeder::class,
            DummyDataSeeder::class,
        ]);
    }
}