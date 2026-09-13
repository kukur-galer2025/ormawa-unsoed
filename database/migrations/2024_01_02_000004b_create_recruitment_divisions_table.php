<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('divisi_rekrutmen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recruitment_id')->constrained('rekrutmen')->onDelete('cascade');
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            $table->integer('kuota')->default(0)->comment('Max jumlah yang diterima di divisi ini');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('divisi_rekrutmen');
    }
};
