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
     * to the `carts` table.
     */
    public function up()
    {
        Schema::table('carts', function (Blueprint $table) {
            // Guard each drop so it doesn't fail if the column is already gone
            if (Schema::hasColumn('carts', 'course_id')) {
                $table->dropColumn('course_id');
            }
            if (Schema::hasColumn('carts', 'course_schedule_id')) {
                $table->dropColumn('course_schedule_id');
            }
            if (Schema::hasColumn('carts', 'item_type')) {
                $table->dropColumn('item_type');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * We’re not restoring the columns; if you ever need them again,
     * create a new migration that adds them back explicitly.
     */
    public function down()
    {
        // Intentionally left empty
    }
};


