<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('profil_mahasiswa', function (Blueprint $table) {
            $table->foreignId('fakultas_id')->nullable()->constrained('fakultas')->nullOnDelete();
            $table->foreignId('jurusan_id')->nullable()->constrained('jurusan')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profil_mahasiswa', function (Blueprint $table) {
            $table->dropForeign(['fakultas_id']);
            $table->dropForeign(['jurusan_id']);
            $table->dropColumn(['fakultas_id', 'jurusan_id']);
        });
    }
};
