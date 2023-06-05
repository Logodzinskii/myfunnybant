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
        Schema::create('Visitors', function (Blueprint $table){
            $table->id();
            $table->string('ip')->nullable();
            $table->string('path')->nullable();
            $table->string('fullUrl')->nullable();
            $table->text('header')->nullable();
            $table->text('userAgent')->nullable();
            $table->timestamp('last_used_at')->nullable();
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
        Schema::drop('Visitors');
    }
};
