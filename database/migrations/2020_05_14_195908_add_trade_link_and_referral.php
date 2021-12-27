<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTradeLinkAndReferral extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('trade_link')->after('is_ban_chat')->default('');
            $table->string('referral_code')->after('trade_link');
            $table->string('referral_use')->after('referral_code')->nullable();
            $table->float('referral_sum', 255, 2)->after('referral_use');
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
