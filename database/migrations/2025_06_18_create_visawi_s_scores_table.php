<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visawi_s_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('survey_id')->references('id')->on('surveys')->cascadeOnDelete();
            $table->foreignId('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreignId('survey_response_id')->references('id')->on('survey_responses')->cascadeOnDelete();
            
            // 4 dimensions of VisAWI-S (1-7 Likert scale)
            $table->integer('simplicity')->min(1)->max(7);
            $table->integer('diversity')->min(1)->max(7);
            $table->integer('colorfulness')->min(1)->max(7);
            $table->integer('craftsmanship')->min(1)->max(7);
            
            // Final score (average of all dimensions)
            $table->decimal('final_score', 5, 2);
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visawi_s_scores');
    }
};
