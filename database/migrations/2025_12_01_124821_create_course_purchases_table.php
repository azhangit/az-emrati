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
        Schema::dropIfExists('course_purchases');
        
        Schema::create('course_purchases', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id'); // users.id is unsigned integer
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('course_schedule_id')->nullable();
            // Check actual type of orders.id - try both
            $table->unsignedBigInteger('order_id')->nullable();
            $table->unsignedBigInteger('order_detail_id')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('payment_status')->default('pending'); // pending, completed, failed
            $table->decimal('amount', 10, 2);
            $table->text('payment_details')->nullable(); // JSON for payment gateway response
            $table->string('transaction_id')->nullable();
            $table->date('selected_date')->nullable();
            $table->time('selected_time')->nullable();
            $table->string('selected_level')->nullable();
            $table->string('code')->unique()->nullable(); // Unique purchase code
            $table->timestamps();
        });
        
        // Add foreign keys separately with error handling
        if (Schema::hasTable('users')) {
            try {
                Schema::table('course_purchases', function (Blueprint $table) {
                    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                });
            } catch (\Exception $e) {
                // Ignore if FK already exists or can't be created
            }
        }
        
        if (Schema::hasTable('courses')) {
            try {
                Schema::table('course_purchases', function (Blueprint $table) {
                    $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
                });
            } catch (\Exception $e) {
                // Ignore if FK already exists or can't be created
            }
        }
        
        if (Schema::hasTable('course_schedules')) {
            try {
                Schema::table('course_purchases', function (Blueprint $table) {
                    $table->foreign('course_schedule_id')->references('id')->on('course_schedules')->onDelete('set null');
                });
            } catch (\Exception $e) {
                // Ignore if FK already exists or can't be created
            }
        }
        
        if (Schema::hasTable('orders')) {
            try {
                // Check if orders.id is bigint or int
                $orderIdType = DB::select("SELECT DATA_TYPE FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'orders' AND COLUMN_NAME = 'id'");
                if (!empty($orderIdType)) {
                    Schema::table('course_purchases', function (Blueprint $table) use ($orderIdType) {
                        // Drop and recreate with correct type if needed
                        if (stripos($orderIdType[0]->DATA_TYPE, 'bigint') !== false) {
                            // Already unsignedBigInteger, just add FK
                            $table->foreign('order_id')->references('id')->on('orders')->onDelete('set null');
                        } else {
                            // Change to unsignedInteger and add FK
                            $table->unsignedInteger('order_id')->nullable()->change();
                            $table->foreign('order_id')->references('id')->on('orders')->onDelete('set null');
                        }
                    });
                }
            } catch (\Exception $e) {
                // If FK creation fails, just continue without it
            }
        }
        
        if (Schema::hasTable('order_details')) {
            try {
                Schema::table('course_purchases', function (Blueprint $table) {
                    $table->foreign('order_detail_id')->references('id')->on('order_details')->onDelete('set null');
                });
            } catch (\Exception $e) {
                // Ignore if FK already exists or can't be created
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
        Schema::dropIfExists('course_purchases');
    }
};
