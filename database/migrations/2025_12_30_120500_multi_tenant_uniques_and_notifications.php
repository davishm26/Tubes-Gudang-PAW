<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Notifications: add company_id for tenant scoping
        if (!Schema::hasColumn('notifications', 'company_id')) {
            Schema::table('notifications', function (Blueprint $table) {
                $table->foreignId('company_id')->nullable()->after('id')->constrained('companies')->onDelete('cascade');
            });
        }

        // Products: make SKU unique per company
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'sku')) {
                try {
                    $table->dropUnique('products_sku_unique');
                } catch (\Throwable $e) {
                    // index may already be dropped
                }
                $table->unique(['company_id', 'sku']);
            }
        });

        // Users: allow same email across companies but unique within company
        Schema::table('users', function (Blueprint $table) {
            try {
                $table->dropUnique('users_email_unique');
            } catch (\Throwable $e) {
                // index may already be dropped
            }
            $table->unique(['company_id', 'email']);
        });
    }

    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropConstrainedForeignId('company_id');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropUnique(['company_id', 'sku']);
            $table->unique('sku');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['company_id', 'email']);
            $table->unique('email');
        });
    }
};
