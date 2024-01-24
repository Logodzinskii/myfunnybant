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
        Schema::create('sale_items', function (Blueprint $table) {
            $table->id();
            $table->integer('ind')->nullable();
            $table->text('sale_to_chatID')->nullable();
            $table->text('date_sale')->nullable();
            $table->integer('count_items')->nullable();
            $table->float('sale_price')->nullable();
            $table->text('sale_file')->nullable();
            $table->text('category')->nullable();
            $table->integer('id_shop')->nullable();
            $table->text('place')->nullable();
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
        Schema::dropIfExists('sale_items');
    }
};
