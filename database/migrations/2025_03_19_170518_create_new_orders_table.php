<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('new_orders', function (Blueprint $table) {
            $table->id();

            // Delivery fields
            $table->string('delivery_type'); // 'ship' or 'pickup'
            $table->string('delivery_country');
            $table->string('delivery_phone');
            $table->string('delivery_first_name');
            $table->string('delivery_last_name');
            $table->text('delivery_address');
            $table->string('delivery_apartment')->nullable();
            $table->string('delivery_city');
            
            // Payment fields (store minimal data; never store sensitive card details)
            $table->string('payment_method'); // e.g., 'creditCard' or 'paypal'
            // Instead of raw card details, store a payment token from Stripe (if available)
            $table->string('stripe_token')->nullable();

            // Billing address fields (optional)
            $table->string('billing_country')->nullable();
            $table->string('billing_phone')->nullable();
            $table->string('billing_first_name')->nullable();
            $table->string('billing_last_name')->nullable();
            $table->text('billing_address')->nullable();
            $table->string('billing_apartment')->nullable();
            $table->string('billing_city')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('new_orders');
    }
}
