<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('application_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained()->onDelete('cascade');
            $table->foreignId('criteria_id')->constrained('criteria')->onDelete('cascade');
            $table->integer('actual_value')->nullable()->comment('Nilai aktual yang diinput admin 1-5');
            $table->integer('gap')->nullable()->comment('actual_value - target_value');
            $table->decimal('bobot_gap', 3, 1)->nullable()->comment('Dari tabel konversi gap');
            $table->timestamps();
            $table->unique(['application_id', 'criteria_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('application_scores');
    }
};