<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->foreignId('company_id')->nullable()->after('id')->constrained('companies')->onDelete('cascade');
            $table->dropUnique(['name']);
            $table->unique(['company_id', 'name']);
            $table->index('company_id');
        });
    }

    public function down(): void
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropUnique(['company_id', 'name']);
            $table->unique(['name']);
            $table->dropForeignKeyIfExists(['company_id']);
            $table->dropColumn('company_id');
        });
    }
};
