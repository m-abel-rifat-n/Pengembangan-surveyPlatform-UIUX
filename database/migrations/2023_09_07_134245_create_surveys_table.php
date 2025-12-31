<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('surveys', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->string('title');
            $table->string('slug');
            $table->string('image');
            $table->string('theme');
            $table->longText('description');
            $table->text('url_website')->nullable();
            $table->text('embed_design')->nullable();
            $table->text('embed_prototype')->nullable();
            $table->string('status');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('surveys');
    }
};
