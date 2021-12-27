<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BotsSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('configs', function (Blueprint $table) {
            $table->integer('bots_bets')->default(5);
            $table->float('bots_min', 255, 2)->default(1.00);
            $table->float('bots_max', 255, 2)->default(200.00);
            $table->float('bots_chat', 255, 2)->default(1);
        });

        Schema::table('bets', function (Blueprint $table) {
            $table->integer('is_fake')->after('is_win')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
