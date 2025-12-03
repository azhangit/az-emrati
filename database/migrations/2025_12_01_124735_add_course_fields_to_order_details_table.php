<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_details', function (Blueprint $table) {
            if (!Schema::hasColumn('order_details', 'course_id')) {
                $table->unsignedBigInteger('course_id')->nullable()->after('product_id');
            }
            if (!Schema::hasColumn('order_details', 'course_schedule_id')) {
                $table->unsignedBigInteger('course_schedule_id')->nullable()->after('course_id');
            }
            if (!Schema::hasColumn('order_details', 'course_metadata')) {
                $table->json('course_metadata')->nullable()->after('course_schedule_id');
            }
            if (!Schema::hasColumn('order_details', 'item_type')) {
                $table->string('item_type')->default('product')->after('course_metadata');
            }
        });

        // Add foreign keys if they don't exist
        if (Schema::hasTable('courses') && Schema::hasTable('course_schedules')) {
            $foreignKeys = \DB::select("
                SELECT CONSTRAINT_NAME 
                FROM information_schema.KEY_COLUMN_USAGE 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = 'order_details' 
                AND CONSTRAINT_NAME = 'order_details_course_id_foreign'
            ");
            
            if (empty($foreignKeys) && Schema::hasColumn('order_details', 'course_id')) {
                Schema::table('order_details', function (Blueprint $table) {
                    $table->foreign('course_id')->references('id')->on('courses')->onDelete('set null');
                });
            }

            $foreignKeys2 = \DB::select("
                SELECT CONSTRAINT_NAME 
                FROM information_schema.KEY_COLUMN_USAGE 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = 'order_details' 
                AND CONSTRAINT_NAME = 'order_details_course_schedule_id_foreign'
            ");
            
            if (empty($foreignKeys2) && Schema::hasColumn('order_details', 'course_schedule_id')) {
                Schema::table('order_details', function (Blueprint $table) {
                    $table->foreign('course_schedule_id')->references('id')->on('course_schedules')->onDelete('set null');
                });
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
        Schema::table('order_details', function (Blueprint $table) {
            // Drop foreign keys first
            $foreignKeys = \DB::select("
                SELECT CONSTRAINT_NAME 
                FROM information_schema.KEY_COLUMN_USAGE 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = 'order_details' 
                AND REFERENCED_TABLE_NAME IS NOT NULL
                AND (COLUMN_NAME = 'course_id' OR COLUMN_NAME = 'course_schedule_id')
            ");
            
            foreach ($foreignKeys as $fk) {
                try {
                    \DB::statement("ALTER TABLE `order_details` DROP FOREIGN KEY `{$fk->CONSTRAINT_NAME}`");
                } catch (\Throwable $e) {
                    // Ignore if FK doesn't exist
                }
            }
            
            // Drop columns
            if (Schema::hasColumn('order_details', 'item_type')) {
                $table->dropColumn('item_type');
            }
            if (Schema::hasColumn('order_details', 'course_metadata')) {
                $table->dropColumn('course_metadata');
            }
            if (Schema::hasColumn('order_details', 'course_schedule_id')) {
                $table->dropColumn('course_schedule_id');
            }
            if (Schema::hasColumn('order_details', 'course_id')) {
                $table->dropColumn('course_id');
            }
        });
    }
};
