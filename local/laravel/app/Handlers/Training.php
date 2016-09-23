<?php
namespace App\Handlers;

use Log;
use MessagePack\MessagePack;
use App\Repositories\GameUserScopedRepository;

abstract class Training extends Handler {
    protected $serialId;
    protected $serialIds;

    protected function requestInternal(MessagePack $msgpack) {
        $this->serialId = $msgpack->iterate('base_serial_id')->data;
        $this->serialIds = $msgpack->iterate('material_serial_ids')->data;
    }

    protected function responseInternal(MessagePack $msgpack) {
        $serialIds = $this->serialIds;
        $cardRepository = new GameUserScopedRepository($this->gameUser, \App\GameCard::class);
        $cardRepository->delete(function($qb) use ($serialIds) {
            $qb->whereIn('serial_id', $serialIds);
        });

        $card = $cardRepository->first('serial_id', $this->serialId);
        if (is_null($card)) return $msgpack;

        $data = $msgpack->iterate('data')->data;
        $json = json_decode($card->card_json);
        $json = $this->updateData($json, $data);
        $card->card_json = json_encode($json);
        $card->save();

        $this->publishTraining($card, $serialIds);
        
        return $msgpack;
    }

    protected abstract function updateData($json, $data);

	protected function publishTraining($card, $serialIds) {
		if (!is_array($serialIds)) $serialIds = [ $serialIds ];
		$this->publish("TRAINING_UPDATE", [
            "updated_card" => $card,
            "removed_card_ids" => $serialIds
        ]);
	}
}
