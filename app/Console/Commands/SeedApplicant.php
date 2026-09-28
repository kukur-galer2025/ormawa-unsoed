<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:seed-applicant')]
#[Description('Command description')]
class SeedApplicant extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $recruitment = \App\Models\Recruitment::where('judul', 'Rekrutmen Batch 2 Panitia Unsoed')->first();
        if (!$recruitment) { $this->error("Recruitment not found"); return; }

        $division = $recruitment->divisions()->where('nama', 'Humas')->first();
        if (!$division) { $this->error("Division not found"); return; }

        $appliedUserIds = $division->applications()->pluck('user_id');
        $user = \App\Models\User::where('role', 'mahasiswa')
            ->whereNotIn('id', $appliedUserIds)
            ->whereHas('mahasiswaProfile')
            ->first();

        if (!$user) { 
            $user = \App\Models\User::where('role', 'mahasiswa')->whereNotIn('id', $appliedUserIds)->first();
            if (!$user) {
                // create a new dummy user
                $user = \App\Models\User::create([
                    'role' => 'mahasiswa',
                    'name' => 'Dummy Tester',
                    'email' => 'dummy' . rand(100,999) . '@test.com',
                    'password' => bcrypt('password'),
                ]);
                \App\Models\MahasiswaProfile::create([
                    'user_id' => $user->id,
                    'nim' => 'H1D' . rand(100000,999999),
                    'angkatan' => 2023,
                    'fakultas_id' => 1,
                    'jurusan_id' => 1,
                ]);
            }
        }

        $app = \App\Models\Application::create([
            'user_id' => $user->id,
            'recruitment_id' => $recruitment->id,
            'recruitment_division_id' => $division->id,
            'status' => 'terkirim',
            'motivasi' => 'Ingin mengecek badge warning system.',
        ]);

        $criteria = \App\Models\Criteria::whereIn('aspect_id', $division->aspects->pluck('id'))->get();
        foreach($criteria as $c) {
            \App\Models\ApplicationScore::create([
                'application_id' => $app->id,
                'criteria_id' => $c->id,
                'nilai' => rand(3, 5),
            ]);
        }

        $this->info("Successfully created application for user " . $user->name);
    }
}
