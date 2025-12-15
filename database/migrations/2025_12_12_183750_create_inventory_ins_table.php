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
        Schema::create('inventory_ins', function (Blueprint $table) {
            $table->id();

            // --- KOLOM DATA INVENTORY IN (Stok Masuk) ---

            // Foreign Key ke tabel products
            $table->foreignId('product_id')->constrained()->onDelete('cascade');

            $table->integer('quantity');
            $table->date('date');

            // Foreign Key ke tabel users (siapa yang mencatat)
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');

            // ---------------------------------------------

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_ins');
    }
};