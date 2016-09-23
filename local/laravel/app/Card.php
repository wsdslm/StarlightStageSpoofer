<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Card extends Model {
    public $timestamps = false;

    public function character() {
        return $this->belongsTo(Character::class, 'chara_id', 'id');
    }

    public function gameCards() {
        return $this->hasMany(GameCard::class, 'card_id', 'id');
    }

    public function gameAlbums() {
        return $this->hasMany(GameAlbum::class, 'card_id', 'id');
    }
}
