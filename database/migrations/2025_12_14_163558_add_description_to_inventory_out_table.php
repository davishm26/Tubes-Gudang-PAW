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
        Schema::table('inventory_outs', function (Blueprint $table) {
            if (!Schema::hasColumn('inventory_outs', 'description')) {
                $table->text('description')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('inventory_outs', function (Blueprint $table) {
            if (Schema::hasColumn('inventory_outs', 'description')) {
                $table->dropColumn('description');
            }
        });
    }
};
