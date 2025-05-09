<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id('prod_id');

            $table->unsignedBigInteger('user_id')->comment('Seller user id');
            $table->string('prod_name', 50);
            $table->string('prod_image', 100);
            $table->string('prod_description', 500);

            $table->decimal('prod_rent_price', 10, 2)->nullable();
            $table->decimal('prod_rent_nondiscounted', 10, 2)->nullable();
            $table->unsignedBigInteger('prod_rent_interval')->nullable()->comment('Interval of which the rent is incurred');

            $table->decimal('prod_buy_price', 10, 2)->nullable();
            $table->decimal('prod_buy_nondiscounted', 10, 2)->nullable();

            $table->foreign('user_id')->references('user_id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
