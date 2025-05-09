<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id('order_id');
            $table->unsignedBigInteger('order_seller_id')->comment('user id that ordered')->nullable();
            $table->unsignedBigInteger('order_cust_id')->comment('user id that receives the order');
            $table->string('order_address', 500)->nullable();
            $table->tinyInteger('order_status')->comment('0 - cart, 1 - sent, 2 - processing, 3 - shipped, 4 - delivered, 5 - returning, 6 - returned, 7 - cancelled');
            $table->dateTime('order_created_at');

            $table->foreign('order_seller_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('order_cust_id')->references('user_id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
