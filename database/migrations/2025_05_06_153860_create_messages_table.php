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
        Schema::create('messages', function (Blueprint $table) {
            $table->id('msg_id');
            $table->unsignedBigInteger('conv_id');
            $table->unsignedBigInteger('msg_sender');
            $table->text('msg_content');
            $table->dateTime('msg_sent_at');
        });

        Schema::table('conversations', function (Blueprint $table){
            $table->foreign('conv_user_a')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('conv_user_b')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('conv_user_a_last_read')->references('msg_id')->on('messages')->onDelete('cascade');
            $table->foreign('conv_user_b_last_read')->references('msg_id')->on('messages')->onDelete('cascade');
        });

        Schema::table('messages', function (Blueprint $table) {
            $table->foreign('conv_id')->references('conv_id')->on('conversations')->onDelete('cascade');
            $table->foreign('msg_sender')->references('user_id')->on('users')->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
