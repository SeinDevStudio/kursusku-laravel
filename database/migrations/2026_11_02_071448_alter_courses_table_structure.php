<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE courses MODIFY code VARCHAR(20) NOT NULL");
            DB::statement("ALTER TABLE courses MODIFY name VARCHAR(120) NOT NULL");
            DB::statement("ALTER TABLE courses MODIFY fee INT UNSIGNED NOT NULL DEFAULT 0");
            DB::statement("ALTER TABLE courses MODIFY quota SMALLINT UNSIGNED NOT NULL DEFAULT 20");
            DB::statement("ALTER TABLE courses MODIFY status ENUM('draft', 'open', 'closed') NOT NULL DEFAULT 'draft'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE courses MODIFY code VARCHAR(255) NOT NULL");
            DB::statement("ALTER TABLE courses MODIFY name VARCHAR(255) NOT NULL");
            DB::statement("ALTER TABLE courses MODIFY fee DECIMAL(12, 2) NOT NULL DEFAULT 0.00");
            DB::statement("ALTER TABLE courses MODIFY quota INT UNSIGNED NOT NULL DEFAULT 0");
            DB::statement("ALTER TABLE courses MODIFY status VARCHAR(255) NOT NULL DEFAULT 'open'");
        }
    }
};
