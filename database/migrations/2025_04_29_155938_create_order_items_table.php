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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id('orditm_id'); // BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY

            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('prod_id');

            $table->tinyInteger('orditm_type')->comment('0 - buy, 1 - rent');
            $table->dateTime('orditm_start_date')->nullable();
            $table->dateTime('orditm_end_date')->nullable();

            $table->decimal('orditm_rent_price', 10, 2)->nullable();
            $table->decimal('orditm_rent_nondiscounted', 10, 2)->nullable();
            $table->unsignedBigInteger('orditm_rent_interval')->nullable()->comment('interval of which the rent is incurred, to get the rent total');

            $table->decimal('orditm_buy_price', 10, 2)->nullable();
            $table->decimal('orditm_buy_nondiscounted', 10, 2)->nullable();

            $table->unsignedInteger('orditm_quantity');

            $table->decimal('orditm_rent_total', 10, 2)->nullable();
            $table->decimal('orditm_buy_total', 10, 2)->nullable();
            $table->string('orditm_address', 500);

            $table->foreign('prod_id')->references('prod_id')->on('products')->onDelete('cascade');
            $table->foreign('order_id')->references('order_id')->on('orders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
