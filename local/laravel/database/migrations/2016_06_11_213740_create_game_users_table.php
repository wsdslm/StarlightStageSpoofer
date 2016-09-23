<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGameUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game_users', function (Blueprint $table) {
            $table->integer('viewer_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->uuid('udid');
            $table->text('user_json');
            $table->text('settings_json');
            $table->timestamps();

            $table->primary('viewer_id');
            $table->unique('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('game_users');
    }
}
