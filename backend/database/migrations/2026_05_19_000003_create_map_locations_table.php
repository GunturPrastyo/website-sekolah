<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('map_locations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('top');
            $table->string('left');
            $table->json('institutions')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('map_locations');
    }
};