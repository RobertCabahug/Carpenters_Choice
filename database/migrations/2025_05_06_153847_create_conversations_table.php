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
        Schema::create('conversations', function (Blueprint $table) {
            $table->id('conv_id');
            $table->unsignedBigInteger('conv_user_a');
            $table->unsignedBigInteger('conv_user_b');
            $table->unsignedBigInteger('conv_user_a_last_read')->nullable();
            $table->unsignedBigInteger('conv_user_b_last_read')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};
