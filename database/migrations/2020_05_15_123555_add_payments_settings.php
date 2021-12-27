<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentsSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('configs', function (Blueprint $table) {
            $table->string('freekassa_id')->default('');
            $table->string('freekassa_secret_1')->default('');
            $table->string('freekassa_secret_2')->default('');
            $table->float('freekassa_sum', 255, 2)->default(0.00);
            $table->float('skinpay_sum', 255, 2)->default(0.00);
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
