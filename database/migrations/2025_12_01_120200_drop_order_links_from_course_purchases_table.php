<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * This will DROP any order-link columns we previously added
     * to the `course_purchases` table.
     */
    public function up()
    {
        // Check and drop foreign keys using raw SQL
        $foreignKeys = DB::select("
            SELECT CONSTRAINT_NAME 
            FROM information_schema.KEY_COLUMN_USAGE 
            WHERE TABLE_SCHEMA = DATABASE() 
            AND TABLE_NAME = 'course_purchases' 
            AND REFERENCED_TABLE_NAME IS NOT NULL
            AND (COLUMN_NAME = 'order_detail_id' OR COLUMN_NAME = 'order_id')
        ");

        foreach ($foreignKeys as $fk) {
            try {
                DB::statement("ALTER TABLE `course_purchases` DROP FOREIGN KEY `{$fk->CONSTRAINT_NAME}`");
            } catch (\Throwable $e) {
                // Ignore if FK doesn't exist
            }
        }

        // Now drop columns
        Schema::table('course_purchases', function (Blueprint $table) {
            if (Schema::hasColumn('course_purchases', 'order_detail_id')) {
                $table->dropColumn('order_detail_id');
            }

            if (Schema::hasColumn('course_purchases', 'order_id')) {
                $table->dropColumn('order_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * We’re not restoring the columns; if you need them again,
     * create a fresh migration that adds them.
     */
    public function down()
    {
        // Intentionally left empty
    }
};


