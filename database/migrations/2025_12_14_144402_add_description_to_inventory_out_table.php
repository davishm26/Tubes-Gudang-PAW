<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Menambahkan kolom 'description' ke tabel 'inventory_out'.
     */
    public function up(): void
    {
        Schema::table('inventory_outs', function (Blueprint $table) {
            // Tambahkan kolom keterangan setelah kolom 'date' jika belum ada
            if (!Schema::hasColumn('inventory_outs', 'description')) {
                $table->text('description')->nullable()->after('date');
            }
        });
    }

    /**
     * Reverse the migrations.
     * Menghapus kolom 'description' dari tabel 'inventory_out'.
     */
    public function down(): void
    {
        Schema::table('inventory_outs', function (Blueprint $table) {
            // Hapus kolom description jika migrasi di-rollback dan kolom ada
            if (Schema::hasColumn('inventory_outs', 'description')) {
                $table->dropColumn('description');
            }
        });
    }
};
