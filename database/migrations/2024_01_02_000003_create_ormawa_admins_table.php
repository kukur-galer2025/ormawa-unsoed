<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ormawa_admins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('ormawa_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            $table->unique(['user_id', 'ormawa_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ormawa_admins');
    }
};