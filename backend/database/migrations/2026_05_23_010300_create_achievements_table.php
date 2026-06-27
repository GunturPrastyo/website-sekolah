<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('student_name');
            $table->string('category');
            $table->string('level');
            $table->integer('year');
            $table->integer('rank')->after('year')->nullable()->comment('Peringkat/Juara, misal: 1, 2, 3');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->foreignId('internal_news_id')->nullable()->constrained('news')->onDelete('set null');
            $table->string('external_news_url', 2048)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('achievements');
    }
};