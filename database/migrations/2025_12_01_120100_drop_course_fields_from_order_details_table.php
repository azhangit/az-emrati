<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * This will DROP any course-related columns we previously added
     * to the `order_details` table.
     */
    public function up()
    {
        Schema::table('order_details', function (Blueprint $table) {
            if (Schema::hasColumn('order_details', 'course_id')) {
                $table->dropColumn('course_id');
            }
            if (Schema::hasColumn('order_details', 'course_schedule_id')) {
                $table->dropColumn('course_schedule_id');
            }
            if (Schema::hasColumn('order_details', 'course_metadata')) {
                $table->dropColumn('course_metadata');
            }
            if (Schema::hasColumn('order_details', 'item_type')) {
                $table->dropColumn('item_type');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * We’re not restoring the columns in down(); add them explicitly
     * in a new migration if you ever need them again.
     */
    public function down()
    {
        // Intentionally left empty
    }
};


