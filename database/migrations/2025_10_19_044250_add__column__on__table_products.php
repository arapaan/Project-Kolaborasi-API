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
        Schema::table('products', function (Blueprint $table) {
            // Menambahkan kolom url untuk menyimpan asset
            $table->string('url')->nullable(); 
            // Gunakan after('name') jika kamu ingin kolom ini muncul setelah kolom 'name'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Menghapus kolom url jika di-rollback
            $table->dropColumn('url');
        });
    }
};
