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
     * @return void
     */
    public function up()
    {
        // Check if product_id column exists and is not nullable
        if (Schema::hasColumn('order_details', 'product_id')) {
            Schema::table('order_details', function (Blueprint $table) {
                // Drop foreign key if it exists
                $foreignKeys = DB::select("
                    SELECT CONSTRAINT_NAME 
                    FROM information_schema.KEY_COLUMN_USAGE 
                    WHERE TABLE_SCHEMA = DATABASE() 
                    AND TABLE_NAME = 'order_details' 
                    AND COLUMN_NAME = 'product_id'
                    AND REFERENCED_TABLE_NAME IS NOT NULL
                ");
                
                foreach ($foreignKeys as $fk) {
                    try {
                        DB::statement("ALTER TABLE `order_details` DROP FOREIGN KEY `{$fk->CONSTRAINT_NAME}`");
                    } catch (\Exception $e) {
                        // Ignore if FK doesn't exist
                    }
                }
                
                // Make product_id nullable
                $table->unsignedInteger('product_id')->nullable()->change();
            });
            
            // Re-add foreign key if products table exists
            if (Schema::hasTable('products')) {
                try {
                    Schema::table('order_details', function (Blueprint $table) {
                        $table->foreign('product_id')->references('id')->on('products')->onDelete('set null');
                    });
                } catch (\Exception $e) {
                    // Ignore if FK already exists or can't be created
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Note: This is a one-way migration as making product_id non-nullable
        // could cause data loss if there are null values
        // If you need to reverse, you should handle existing null values first
    }
};
