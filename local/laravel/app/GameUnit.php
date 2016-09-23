<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GameUnit extends Model {
	protected $table = 'game_units';
	protected $fillable = ['viewer_id', 'unit_id'];

	public function gameUser() {
		return $this->belongsTo(GameUser::class, 'viewer_id', 'viewer_id');
	}

	public function gameCards() {
		return $this->belongsToMany(GameCard::class, 'game_unit_cards', 'unit_id', 'card_id')->withPivot('index');
	}
}
