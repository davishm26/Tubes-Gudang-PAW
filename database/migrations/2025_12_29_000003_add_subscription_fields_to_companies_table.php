<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            if (!Schema::hasColumn('companies', 'subscription_end_date')) {
                $table->date('subscription_end_date')->nullable()->after('subscription_status');
            }
            if (!Schema::hasColumn('companies', 'subscription_price')) {
                $table->unsignedBigInteger('subscription_price')->default(0)->after('subscription_end_date');
            }
            if (!Schema::hasColumn('companies', 'subscription_paid_at')) {
                $table->timestamp('subscription_paid_at')->nullable()->after('subscription_price');
            }
        });
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            if (Schema::hasColumn('companies', 'subscription_paid_at')) {
                $table->dropColumn('subscription_paid_at');
            }
            if (Schema::hasColumn('companies', 'subscription_price')) {
                $table->dropColumn('subscription_price');
            }
            if (Schema::hasColumn('companies', 'subscription_end_date')) {
                $table->dropColumn('subscription_end_date');
            }
        });
    }
};
