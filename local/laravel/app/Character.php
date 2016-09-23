<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Character extends Model {
	public $timestamps = false;

    public function cards() {
        return $this->hasMany(Card::class, 'chara_id', 'id');
    }
}
