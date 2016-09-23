<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGameUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game_units', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('viewer_id')->unsigned();
            $table->integer('unit_id')->unsigned();
            $table->string('name');
            $table->timestamps();

            $table->foreign('viewer_id')->references('viewer_id')->on('game_users')->onUpdate('cascade')->onDelete('cascade');
            $table->unique(['viewer_id', 'unit_id']);
        });

        Schema::create('game_unit_cards', function (Blueprint $table) {
            $table->integer('unit_id')->unsigned();
            $table->integer('card_id')->unsigned();
            $table->integer('index')->unsigned();
            $table->integer('dress_type')->default(0);

            $table->foreign('unit_id')->references('id')->on('game_units')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('card_id')->references('id')->on('game_cards')->onUpdate('cascade')->onDelete('cascade');
            $table->primary(['unit_id', 'card_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('game_unit_cards');
        Schema::drop('game_units');
    }
}
