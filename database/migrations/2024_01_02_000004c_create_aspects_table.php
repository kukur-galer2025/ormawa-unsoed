<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aspek', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recruitment_division_id')
                  ->constrained('divisi_rekrutmen')->onDelete('cascade');
            $table->string('nama');
            $table->decimal('bobot', 5, 2)->comment('Bobot aspek, e.g. 0.30 = 30%');
            $table->decimal('cf_percentage', 5, 2)->default(60.00);
            $table->decimal('sf_percentage', 5, 2)->default(40.00);
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aspek');
    }
};
