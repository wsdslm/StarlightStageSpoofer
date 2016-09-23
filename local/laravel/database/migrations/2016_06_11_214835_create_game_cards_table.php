<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGameCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game_cards', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('viewer_id')->unsigned();
            $table->integer('serial_id')->unsigned();
            $table->integer('card_id')->unsigned();
            $table->integer('album_id')->unsigned();
            $table->text('card_json');
            $table->text('modified_json')->nullable();
            $table->timestamps();

            $table->foreign('viewer_id')->references('viewer_id')->on('game_users')->onUpdate('cascade')->onDelete('cascade');
            // TODO: restore this when internal album_id issue(s) are fixed
            //$table->foreign('album_id')->references('id')->on('game_albums')->onUpdate('cascade')->onDelete('cascade');
            $table->unique(['viewer_id', 'serial_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('game_cards');
    }
}
