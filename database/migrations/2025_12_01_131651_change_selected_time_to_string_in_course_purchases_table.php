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
        Schema::table('course_purchases', function (Blueprint $table) {
            // Change selected_time from time to string to store time range
            $table->string('selected_time', 50)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('course_purchases', function (Blueprint $table) {
            // Revert back to time type (but this might cause data loss)
            $table->time('selected_time')->nullable()->change();
        });
    }
};
