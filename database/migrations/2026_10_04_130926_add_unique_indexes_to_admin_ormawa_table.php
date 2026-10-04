<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Enforce 1:1 relationship between admin and ormawa.
     * - Each ormawa can only have one admin.
     * - Each admin (user) can only be assigned to one ormawa.
     */
    public function up(): void
    {
        Schema::table('admin_ormawa', function (Blueprint $table) {
            $table->unique('ormawa_id', 'admin_ormawa_ormawa_id_unique');
            $table->unique('user_id', 'admin_ormawa_user_id_unique');
        });
    }

    public function down(): void
    {
        Schema::table('admin_ormawa', function (Blueprint $table) {
            $table->dropUnique('admin_ormawa_ormawa_id_unique');
            $table->dropUnique('admin_ormawa_user_id_unique');
        });
    }
};
