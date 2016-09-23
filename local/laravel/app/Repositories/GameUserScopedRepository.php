<?php
namespace App\Repositories;

use Schema;
use App\GameUser;

class GameUserScopedRepository extends Repository {
	protected $gameUser;

	public function __construct(GameUser $gameUser, $className) {
		parent::__construct($className);
		$this->gameUser = $gameUser;
	}

	public function query($arg=null, $opt=null) {
		return parent::query($arg, $opt)
				->where('viewer_id', $this->gameUser->viewer_id);
	}

	protected function applyAttributes($model, $data) {
		$model = parent::applyAttributes($model, $data);
		$model->viewer_id = $this->gameUser->viewer_id;
		return $model;
	}
}
