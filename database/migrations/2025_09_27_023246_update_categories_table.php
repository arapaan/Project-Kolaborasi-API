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
        Schema::table('categories', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn('parent_id');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->unsignedBigInteger('business_id');

            $table->foreign('parent_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('business_id')->references('id')->on('businesses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropForeign(['business_id']);
            $table->dropColumn(['parent_id', 'business_id']);
            $table->unsignedBigInteger('parent_id');     
            $table->foreign('parent_id')->references('id')->on('categories')->onDelete('cascade');                           
        });
    }
};
