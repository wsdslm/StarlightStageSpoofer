<?php
namespace App\Handlers;

use Log;
use MessagePack\MessagePack;

use App\Repositories\GameUserScopedRepository;

class EventMedleySetUnit extends Handler {
    private $serialIds;

    protected function requestInternal(MessagePack $msgpack) {
        $this->serialIds = $msgpack->iterate('mainmember_serial_ids')->data;
        return $msgpack;
    }

    protected function responseInternal(MessagePack $msgpack) {
        if (!$this->checkUserSetting('modify_cards')) return $msgpack;
        
        $cards = [];
        $serialIds = $this->serialIds;
        $user = $this->gameUser;

        $gameCardRepository = new GameUserScopedRepository($user, \App\GameCard::class);
        $gameCards = $gameCardRepository->get(function($qb) use ($serialIds) {
            $qb->whereIn('serial_id', $serialIds);
        })->each(function($gameCard, $idx) use (&$cards) {
            $cards[$gameCard->card_id] = $gameCard;
        });

        $user->medleyCards()->detach();
        foreach ($msgpack->iterate('data.live_unit_member')->array as $idx => $memberPack) {
            $card_id = $memberPack->data;
            if (!array_key_exists($card_id, $cards)) continue;

            $gameCard = $cards[$card_id];
            $json = $gameCard->modified_json;
            if (is_null($json) || $json == "null") continue;

            $json = json_decode($json);
            if (isset($json->card_id)) {
                $memberPack->value($json->card_id);
                $user->medleyCards()->attach($gameCard->id, ['index' => $idx]);
            }
        }

        return $msgpack;
	}
}
