<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Modify the role ENUM to include two new roles
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('superadmin','admin','mahasiswa','wakil_rektor','wakil_dekan') NOT NULL DEFAULT 'mahasiswa'");

        // 2. Add fakultas_id column for wakil_dekan scope
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('fakultas_id')->nullable()->after('role');
            $table->foreign('fakultas_id')->references('id')->on('fakultas')->nullOnDelete();
        });
    }

    public function down(): void
    {
        // Remove the foreign key and column
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['fakultas_id']);
            $table->dropColumn('fakultas_id');
        });

        // Revert the ENUM back to original values
        // First ensure no wakil_* rows exist to avoid data truncation error
        DB::statement("UPDATE users SET role = 'mahasiswa' WHERE role IN ('wakil_rektor', 'wakil_dekan')");
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('superadmin','admin','mahasiswa') NOT NULL DEFAULT 'mahasiswa'");
    }
};
