<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Use raw SQL to avoid doctrine/dbal dependency
        DB::statement("ALTER TABLE `users` MODIFY `role` VARCHAR(50) NOT NULL DEFAULT 'staf'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // It's hard to revert ENUM reliably here; keep as VARCHAR to be safe.
        DB::statement("ALTER TABLE `users` MODIFY `role` ENUM('admin','staf') NOT NULL DEFAULT 'staf'");
    }
};
