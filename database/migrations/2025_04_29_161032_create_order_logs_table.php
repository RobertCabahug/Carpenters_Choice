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
        Schema::create('order_logs', function (Blueprint $table) {
            $table->id('ordlog_id'); // BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY

            $table->unsignedBigInteger('order_id');

            $table->tinyInteger('ordlog_status')->comment('0 - cart, 1 - sent, 2 - processing, 3 - shipped, 4 - delivered, 5 - returning, 6 - returned, 7 - cancelled, 8 -');
            $table->dateTime('ordlog_created_at');
            $table->string('ordlog_seller_remarks', 500)->nullable();
            $table->string('ordlog_user_remarks', 500)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_logs');
    }
};
