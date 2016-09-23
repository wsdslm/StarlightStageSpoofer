<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GameFavorite extends Model {
	protected $table = 'game_favorites';
	protected $fillable = ['viewer_id'];

	public function gameUser() {
		return $this->belongsTo(GameUser::class, 'viewer_id', 'viewer_id');
	}

	public function gameCards() {
		return $this->belongsToMany(GameCard::class, 'game_favorite_cards', 'favorite_id', 'card_id');
	}
}
