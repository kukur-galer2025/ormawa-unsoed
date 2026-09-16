<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Drop unique index yang lama
        Schema::table('profil_mahasiswa', function (Blueprint $table) {
            $table->dropUnique('profil_mahasiswa_nim_unique');
        });

        // 2. Ubah kolom NIM menjadi nullable dan tambahkan unique kembali
        Schema::table('profil_mahasiswa', function (Blueprint $table) {
            $table->string('nim')->nullable()->change();
            $table->unique('nim');
        });

        // 3. Bersihkan NIM kosong ('') menjadi NULL
        DB::table('profil_mahasiswa')->where('nim', '')->update(['nim' => null]);
    }

    public function down(): void
    {
        Schema::table('profil_mahasiswa', function (Blueprint $table) {
            $table->string('nim')->nullable(false)->unique()->change();
        });
    }
};
