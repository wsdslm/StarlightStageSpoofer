<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GameAlbum extends Model {
	protected $table = 'game_albums';
	protected $fillable = ['viewer_id', 'card_id'];
	
	public function gameUser() {
		return $this->belongsTo(GameUser::class, 'viewer_id', 'viewer_id');
	}

	public function gameCards() {
		return $this->hasMany(GameCard::class, 'card_id', 'card_id');
    }

    public function card() {
        return $this->belongsTo(Card::class, 'card_id', 'id');
    }
}
