<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hasil_profile_matching', function (Blueprint $table) {
            $table->decimal('total_score', 8, 5)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('hasil_profile_matching', function (Blueprint $table) {
            $table->decimal('total_score', 6, 4)->nullable()->change();
        });
    }
};
