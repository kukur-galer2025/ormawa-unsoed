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
        Schema::table('rekrutmen', function (Blueprint $table) {
            $table->text('pesan_setelah_mendaftar')->nullable()->after('persyaratan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rekrutmen', function (Blueprint $table) {
            $table->dropColumn('pesan_setelah_mendaftar');
        });
    }
};
