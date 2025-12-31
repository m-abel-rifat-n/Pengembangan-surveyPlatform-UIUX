<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('survey_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreignId('survey_id')->references('id')->on('surveys')->cascadeOnDelete();
            $table->string('first_name');
            $table->string('surname');
            $table->string('email');
            $table->date('birth_date');
            $table->string('gender');
            $table->string('profession');
            $table->string('educational_background');
            $table->json('response_data');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('survey_responses');
    }
};
