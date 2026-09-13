<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kriteria', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aspect_id')->constrained('aspek')->onDelete('cascade');
            $table->string('nama_kriteria');
            $table->enum('tipe', ['core', 'secondary']);
            $table->integer('target_value')->comment('Nilai target profil ideal 1-5');
            $table->text('keterangan')->nullable();
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kriteria');
    }
};