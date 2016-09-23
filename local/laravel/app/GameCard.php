<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GameCard extends Model {
    protected $table = 'game_cards';
    protected $fillable = ['viewer_id', 'serial_id'];

    public function gameUser() {
        return $this->belongsTo(GameUser::class, 'viewer_id', 'viewer_id');
    }

    public function gameUnits() {
        return $this->belongsToMany(GameUnit::class, 'game_unit_cards', 'card_id', 'unit_id')->withPivot('index');;
    }

    public function gameAlbum() {
        return $this->belongsTo(GameAlbum::class, 'album_id', 'id');
    }

    public function card() {
        return $this->belongsTo(Card::class, 'card_id', 'id');
    }

	public function medleyUser() {
		return $this->belongsToMany(GameUser::class, 'medley_unit_cards', 'card_id', 'viewer_id')->withPivot('index');
	}

    public function modifiedJson() {
        if (is_null($this->modified_json)) return null;
        return json_decode($this->modified_json);
    }
}
