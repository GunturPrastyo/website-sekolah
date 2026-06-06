<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('school_classes', function (Blueprint $table) {
            $table->unsignedBigInteger('program_id')->nullable()->after('grade');
            $table->foreign('program_id')->references('id')->on('programs')->nullOnDelete();
            
            // Menghapus kolom major yang lama
            $table->dropColumn('major');
        });
    }

    public function down(): void
    {
        Schema::table('school_classes', function (Blueprint $table) {
            $table->string('major')->nullable()->after('grade');
            $table->dropForeign(['school_classes_program_id_foreign']);
            $table->dropColumn('program_id');
        });
    }
};