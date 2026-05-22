<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pancasila_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->json('dimensions');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pancasila_profiles');
    }
};