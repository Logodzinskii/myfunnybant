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
        Schema::create('status_price_shop_items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('ozon_id');
            $table->text('status');
            $table->float('price');
            $table->float('action_price');
            $table->bigInteger('fbs');
            $table->bigInteger('fbo');
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
        Schema::dropIfExists('status_price_shop_items');
    }
};
