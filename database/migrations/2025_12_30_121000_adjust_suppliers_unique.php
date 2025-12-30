<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('suppliers', function (Blueprint $table) {
            // Pastikan tidak ada duplikat index sebelum membuat yang baru
            try {
                $table->dropUnique('suppliers_company_id_name_unique');
            } catch (\Throwable $e) {
                // ignore if not exists
            }

            // Buat unique per perusahaan
            $table->unique(['company_id', 'name']);
        });
    }

    public function down(): void
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropUnique(['company_id', 'name']);
            $table->unique('name');
        });
    }
};
