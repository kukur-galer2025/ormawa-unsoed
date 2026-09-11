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
        Schema::table('ormawas', function (Blueprint $table) {
            $table->dropColumn('kategori');
            $table->enum('tingkat', ['Universitas', 'Fakultas', 'Jurusan'])->after('slug')->default('Universitas');
            $table->string('fakultas')->nullable()->after('tingkat');
            $table->string('jurusan')->nullable()->after('fakultas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ormawas', function (Blueprint $table) {
            $table->dropColumn(['tingkat', 'fakultas', 'jurusan']);
            $table->enum('kategori', ['BEM', 'DPM', 'HMPS', 'UKM', 'Lainnya'])->after('slug')->default('Lainnya');
        });
    }
};
