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
        Schema::table('offer_users', function (Blueprint $table) {
            $table->text('email');
            $table->text('name');
            $table->text('tel');
            $table->text('status');
            $table->text('confirm');
            $table->text('session_user');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('offer_users', function (Blueprint $table) {
            $table->dropColumn('email');
            $table->dropColumn('name');
            $table->dropColumn('tel');
            $table->dropColumn('status');
            $table->dropColumn('confirm');
            $table->dropColumn('session_user');
        });
    }
};
