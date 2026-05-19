<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('school_classes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('grade');
            $table->string('major');
            
            // Menambahkan kolom homeroom_id yang bisa bernilai NULL
            $table->unsignedBigInteger('homeroom_id')->nullable();
            $table->foreign('homeroom_id')->references('id')->on('staff')->nullOnDelete();
            
            $table->integer('capacity');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('school_classes');
    }
};