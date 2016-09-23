<?php
namespace App\Handlers;

use Log;
use MessagePack\MessagePack;

use App\Repositories\GameUserScopedRepository;

class TrainingEvolve extends Handler {
	protected $serialId;

	protected function requestInternal(MessagePack $msgpack) {
		$this->serialId = $msgpack->iterate('base_serial_id')->data;
	}

	protected function responseInternal(MessagePack $msgpack) {
        $cardRepository = new GameUserScopedRepository($this->gameUser, \App\GameCard::class);

		$card = $cardRepository->first('serial_id', $this->serialId);
		if (is_null($card)) return $msgpack;
		$newCard = $msgpack->iterate('data.card_list.0')->data;
		$card->serial_id = $newCard['serial_id'];
		$card->card_json = json_encode($newCard);

		$this->publishEvolveTraining($this->serialId, $newCard['serial_id']);

		$json = json_decode($card->modified_json);
		if (isset($json) && isset($json->card_id)) {
			$json->card_id++;
			$msgpack->iterate('data.card_list.0.card_id', $json->card_id);
			$card->modified_json = json_encode($json);
		}
		$card->save();

		return $msgpack;
	}

	protected function publishEvolveTraining($oldId, $newId) {
		$this->publish("REPLACE_GAME_CARD", ["old" => $oldId, "new" => $newId]);
	}
}
