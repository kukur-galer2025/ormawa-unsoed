<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pendaftaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recruitment_id')->constrained('rekrutmen')->onDelete('cascade');
            $table->foreignId('recruitment_division_id')->constrained('divisi_rekrutmen')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('motivasi')->nullable();
            $table->string('berkas_pendukung')->nullable();
            $table->enum('status', ['terkirim', 'diproses', 'diterima', 'ditolak'])->default('terkirim');
            $table->timestamps();
            $table->unique(['recruitment_division_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pendaftaran');
    }
};