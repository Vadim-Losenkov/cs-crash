<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGiveawayBetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('giveaway_bets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('giveaway_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('giveaway_id')->references('id')->on('giveaways')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('giveaway_bets');
    }
}
