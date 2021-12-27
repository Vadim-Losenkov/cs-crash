<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('configs', function (Blueprint $table) {
            $table->id();
            $table->string('title')->default('');
            $table->string('description')->default('');
            $table->string('keywords')->default('');
            $table->float('dollar', 255, 2)->default(73.00);
            $table->integer('max_items')->default(10);
            $table->float('profit', 255, 2)->default(50.00);
            $table->boolean('payment_to_withdraw')->default(0);
            $table->string('market_api_key')->default('');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('configs');
    }
}
