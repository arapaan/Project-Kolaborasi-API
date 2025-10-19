<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('page_sections', function (Blueprint $table) {
            $table->dropColumn('section_name');
            $table->dropColumn('file_name');
            $table->dropColumn('file_path');
             $table->string('file_name')->nullable();
            $table->string('file_path')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('page_sections', function (Blueprint $table) {
            $table->string('section_name');
            $table->dropColumn('file_name');
            $table->dropColumn('file_path');
             $table->string('file_name');
            $table->string('file_path');
        });
    }
};
