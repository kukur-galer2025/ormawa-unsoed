<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('criteria_value_labels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('criteria_id')->constrained('criteria')->onDelete('cascade');
            $table->integer('value')->comment('1-5');
            $table->string('label')->comment('e.g. Sangat Kurang, Kurang, dst.');
            $table->unique(['criteria_id', 'value'], 'cvl_criteria_value_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('criteria_value_labels');
    }
};
