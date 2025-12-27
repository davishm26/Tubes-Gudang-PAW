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
        Schema::table('users', function (Blueprint $table) {
            // Kita menggunakan ENUM untuk memastikan nilainya hanya 'admin' atau 'staf'.
            // Nilai default-nya adalah 'staf' agar user yang daftar otomatis jadi staf.
            // Kolom ditambahkan setelah kolom 'email'.
            $table->enum('role', ['admin', 'staf'])->default('staf')->after('email');
        });
    }

    /**
     * Reverse the migrations (Rollback).
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Pastikan kolom 'role' dihapus jika migrasi di-rollback.
            $table->dropColumn('role');
        });
    }
};
