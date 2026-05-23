<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ppdb_infos', function (Blueprint $table) {
            $table->id();
            $table->dateTime('opening_date')->nullable();
            $table->json('syarat')->nullable();
            $table->json('alur')->nullable();
            $table->json('jalur')->nullable();
            $table->json('faqs')->nullable();
            $table->string('brosur_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ppdb_infos');
    }
};