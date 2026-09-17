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
            $table->dropColumn(['fakultas', 'jurusan']);
        });

        Schema::table('ormawa', function (Blueprint $table) {
            $table->dropColumn(['fakultas', 'jurusan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profil_mahasiswa', function (Blueprint $table) {
            $table->string('fakultas')->nullable();
            $table->string('jurusan')->nullable();
        });

        Schema::table('ormawa', function (Blueprint $table) {
            $table->string('fakultas')->nullable();
            $table->string('jurusan')->nullable();
        });
    }
};
