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
        Schema::create('ozon_shop_items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('ozon_id');
            $table->text('name');
            $table->text('images');
            $table->text('category')->nullable();
            $table->text('type')->nullable();
            $table->text('header')->nullable();
            $table->text('description')->nullable();
            $table->text('colors')->nullable();
            $table->integer('width')->nullable();
            $table->integer('height')->nullable();
            $table->integer('depth')->nullable();
            $table->text('material')->nullable();
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
        Schema::dropIfExists('ozon_shop_items');
    }
};
