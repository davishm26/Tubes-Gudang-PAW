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
        Schema::create('inventory_outs', function (Blueprint $table) {
            $table->id();

            // === PENAMBAHAN KOLOM YANG HILANG ===
            // Foreign Key ke tabel 'products'
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();

            $table->integer('quantity'); // Jumlah stok keluar
            $table->date('date');         // Tanggal transaksi

            // Foreign Key ke tabel 'users' (staf/admin yang mencatat)
            $table->foreignId('user_id')->constrained('users');

            $table->string('description')->nullable(); // Keterangan atau tujuan barang keluar
            // ===================================

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_outs');
    }
};
