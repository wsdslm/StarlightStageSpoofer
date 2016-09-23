<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMedleyUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*
        Schema::create('medley_units', function (Blueprint $table) {
            $table->integer('viewer_id')->unsigned();
            $table->timestamps();

            $table->primary('viewer_id');
            $table->foreign('viewer_id')->references('viewer_id')->on('game_users')->onDelete('cascade')->onUpdate('cascade');
        });
        */

        Schema::create('medley_unit_cards', function (Blueprint $table) {
            $table->integer('viewer_id')->unsigned();
            $table->integer('card_id')->unsigned();
            $table->integer('index')->unsigned();

            $table->foreign('viewer_id')->references('viewer_id')->on('game_users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('card_id')->references('id')->on('game_cards')->onUpdate('cascade')->onDelete('cascade');
            $table->primary(['viewer_id', 'card_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('medley_unit_cards');
        //Schema::drop('medley_units');
    }
}
