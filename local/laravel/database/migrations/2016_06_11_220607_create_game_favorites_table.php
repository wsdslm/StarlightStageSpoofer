<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGameFavoritesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game_favorites', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('viewer_id')->unsigned();
            $table->string('name');
            $table->timestamps();

            $table->foreign('viewer_id')->references('viewer_id')->on('game_users')->onUpdate('cascade')->onDelete('cascade');
        });
        
        Schema::create('game_favorite_cards', function (Blueprint $table) {
            $table->integer('favorite_id')->unsigned();
            $table->integer('card_id')->unsigned();
            $table->integer('change_flag')->unsigned();
            $table->integer('index')->unsigned();
            
            $table->foreign('favorite_id')->references('id')->on('game_favorites')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('card_id')->references('id')->on('game_cards')->onUpdate('cascade')->onDelete('cascade');
            $table->primary(['favorite_id', 'card_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('game_favorite_cards');
        Schema::drop('game_favorites');
    }
}
