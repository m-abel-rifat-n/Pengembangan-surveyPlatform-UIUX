<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nasa_tlx_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('survey_id')->references('id')->on('surveys')->cascadeOnDelete();
            $table->foreignId('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreignId('survey_response_id')->references('id')->on('survey_responses')->cascadeOnDelete();
            
            // 6 dimensions of NASA-TLX (0-100 scale)
            $table->integer('mental_demand')->min(0)->max(100);
            $table->integer('physical_demand')->min(0)->max(100);
            $table->integer('temporal_demand')->min(0)->max(100);
            $table->integer('performance')->min(0)->max(100);
            $table->integer('effort')->min(0)->max(100);
            $table->integer('frustration')->min(0)->max(100);
            
            // Final score (average of all dimensions)
            $table->decimal('final_score', 5, 2);
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nasa_tlx_scores');
    }
};
