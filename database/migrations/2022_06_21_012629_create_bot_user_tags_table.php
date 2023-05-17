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
        Schema::create('bot_user_tags', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bot_id');
            $table->unsignedBigInteger('bot_user_id');
            $table->dateTime('first_engage');
            $table->dateTime('last_engage');
            $table->unsignedTinyInteger('reply_status');
            $table->unsignedTinyInteger('kick_status');
            $table->unsignedTinyInteger('status');
            $table->timestamps();

            $table->foreign('bot_id')->references('id')->on('bots');
            $table->foreign('bot_user_id')->references('id')->on('bot_users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bot_user_tags');
    }
};
