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
        Schema::create('survey_has_methods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('survey_id')->references('id')->on('surveys')->cascadeOnDelete();
            $table->foreignId('method_id')->references('id')->on('methods')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('survey_has_methods');
    }
};
