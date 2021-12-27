<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAllItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('all_items', function (Blueprint $table) {
            $table->id();
            $table->text('market_hash_name');
            $table->text('image');
            $table->text('exterior');
            $table->text('rarity');
            $table->text('color');
            $table->boolean('is_stattrak')->default(0);
            $table->float('price', 255, 2)->default(0.00);
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
        Schema::dropIfExists('all_items');
    }
}
