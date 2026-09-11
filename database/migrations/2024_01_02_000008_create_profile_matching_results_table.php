<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profile_matching_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained()->onDelete('cascade');
            $table->foreignId('recruitment_division_id')->constrained('recruitment_divisions')->onDelete('cascade');
            $table->json('detail_per_aspek')->nullable()->comment('Breakdown NCF/NSF/nilai per aspek');
            $table->decimal('total_score', 5, 2)->nullable();
            $table->integer('ranking')->nullable();
            $table->timestamps();
            $table->unique(['application_id', 'recruitment_division_id'], 'pmr_app_div_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profile_matching_results');
    }
};