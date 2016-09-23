<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cards', function (Blueprint $table) {
            $table->integer('id')->unsigned();
            $table->integer('chara_id')->unsigned();
            $table->integer('rarity')->unsigned();
            $table->integer('max_level')->unsigned();
            $table->integer('max_love')->unsigned();
            $table->string('image');
            $table->text('card_json');
            $table->text('rarity_json');

            $table->primary('id');
            $table->foreign('chara_id')->references('id')->on('characters')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('cards');
    }
}
