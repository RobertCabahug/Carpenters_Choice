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
        Schema::create('product_feedbacks', function (Blueprint $table) {
            $table->id('prodfeed_id');
            $table->unsignedBigInteger('prod_id')->comment('product id addressed by feedback');
            $table->unsignedBigInteger('user_id')->comment('commenter user id');
            $table->tinyInteger('prodfeed_rating')->comment('1 to 5');
            $table->string('prodfeed_comment', 500)->nullable();
            $table->dateTime('prodfeed_added_at');

            $table->foreign('prod_id')->references('prod_id')->on('products')->onDelete('cascade');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_feedbacks');
    }
};
