<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGameAlbumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game_albums', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('viewer_id')->unsigned();
            $table->integer('card_id')->unsigned();
            $table->integer('love')->unsigned();
            $table->integer('max_love_flag')->unsigned();
            $table->timestamps();
            
            $table->foreign('viewer_id')->references('viewer_id')->on('game_users')->onUpdate('cascade')->onDelete('cascade');
            //$table->foreign('card_id')->references('id')->on('cards')->onUpdate('cascade')->onDelete('cascade');
            $table->unique(['viewer_id', 'card_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('game_albums');
    }
}
