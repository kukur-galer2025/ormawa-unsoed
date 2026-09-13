<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rekrutmen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ormawa_id')->constrained('ormawa')->onDelete('cascade');
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->text('persyaratan')->nullable();
            $table->date('tanggal_buka');
            $table->date('tanggal_tutup');
            $table->enum('status', ['draft', 'dibuka', 'ditutup', 'selesai'])->default('draft');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rekrutmen');
    }
};