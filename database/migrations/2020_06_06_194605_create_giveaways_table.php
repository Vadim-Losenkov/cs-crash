<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGiveawaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('giveaways', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('item_id');
            $table->float('min_payment', 255, 2);
            $table->timestamp('end_time');
            $table->unsignedBigInteger('winner_id')->nullable();
            $table->integer('status');
            $table->timestamps();

            $table->foreign('item_id')->references('id')->on('all_items')->onDelete('cascade');
            $table->foreign('winner_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('giveaways');
    }
}
