<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hasil_profile_matching', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained('pendaftaran')->onDelete('cascade');
            $table->foreignId('recruitment_division_id')->constrained('divisi_rekrutmen')->onDelete('cascade');
            $table->json('detail_per_aspek')->nullable()->comment('Breakdown NCF/NSF/nilai per aspek');
            $table->decimal('total_score', 6, 4)->nullable();
            $table->integer('ranking')->nullable();
            $table->timestamps();
            $table->unique(['application_id', 'recruitment_division_id'], 'pmr_app_div_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hasil_profile_matching');
    }
};