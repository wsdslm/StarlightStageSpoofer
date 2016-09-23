<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GameUser extends Model {
	protected $primaryKey = 'viewer_id';
	protected $table = 'game_users';
	protected $fillable = ['viewer_id'];

    public function __construct() {
        parent::__construct();
        $this->settings_json = "{}";
    }

	public function gameCards() {
		return $this->hasMany(GameCard::class, 'viewer_id', 'viewer_id');
	}

	public function gameUnits() {
		return $this->hasMany(GameUnit::class, 'viewer_id', 'viewer_id');
	}

	public function gameAlbums() {
		return $this->hasMany(GameAlbum::class, 'viewer_id', 'viewer_id');
	}

	public function gameFavorites() {
		return $this->hasOne(GameFavorite::class, 'viewer_id', 'viewer_id');
    }

    public function user() {
        return $this->hasOne(User::class, 'viewer_id', 'viewer_id');
    }

	public function medleyCards() {
		return $this->belongsToMany(GameCard::class, 'medley_unit_cards', 'viewer_id', 'card_id')->withPivot('index');
	}
}
