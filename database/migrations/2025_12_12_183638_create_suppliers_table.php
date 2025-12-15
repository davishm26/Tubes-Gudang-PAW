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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();

            // --- KOLOM DATA WAJIB ---
            $table->string('name')->unique(); // Nama Pemasok (Wajib dan Harus Unik)
            $table->string('contact')->nullable(); // Kontak (Opsional/Boleh Kosong)
            // ------------------------

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};