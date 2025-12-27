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
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            // --- KOLOM DATA PRODUK ---
            $table->string('name');
            $table->string('sku')->unique(); // SKU (Wajib ada dan unik)
            $table->text('description')->nullable();
            $table->string('image')->nullable(); // Untuk path file gambar
            $table->integer('stock')->default(0); // Stok awal

            // --- FOREIGN KEYS (Hubungan) ---
            // Harus menunjuk ke tabel 'categories' yang sudah ada
            $table->foreignId('category_id')->constrained()->onDelete('cascade');

            // Harus menunjuk ke tabel 'suppliers' yang sudah ada
            $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
            // --------------------------------

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
