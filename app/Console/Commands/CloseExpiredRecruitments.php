<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Recruitment;

class CloseExpiredRecruitments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'recruitment:close-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Menutup rekrutmen yang sudah melewati tanggal batas akhir (tanggal_tutup)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Cari semua rekrutmen yang masih dibuka tapi tanggalnya sudah lewat
        $expiredRecruitments = Recruitment::where('status', 'dibuka')
            ->whereNotNull('tanggal_tutup')
            ->where('tanggal_tutup', '<', now()->startOfDay())
            ->get();

        $count = 0;
        foreach ($expiredRecruitments as $recruitment) {
            // Lakukan update fisik ke database
            $recruitment->update(['status' => 'ditutup']);
            $count++;
        }

        $this->info("Berhasil menutup {$count} rekrutmen yang sudah expired.");
    }
}
