<?php
namespace App\Handlers\LoadIndex;

use App\Repositories\GameUserScopedRepository;
use App\Handlers\Handler;
use MessagePack\MessagePack;
use Log;

class TamperData extends Handler {
    protected $shouldTamper = false;

    protected function responseInternal(MessagePack $msgpack) {
        if (!$this->checkUserSetting('modify_cards')) return $msgpack;
        $cardRepository = new GameUserScopedRepository($this->gameUser, \App\GameCard::class);

        $cardList = $msgpack->iterate('data.user_card_list');
        if (is_null($cardList)) return $msgpack;

        $cardPacks = [];
        foreach ($cardList->array as $card) {
            $serialId = $card->iterate('serial_id')->data;
            $cardPacks[$serialId] = $card;
        }

        $cards = $cardRepository->get(function($query) use ($cardPacks) {
            return $query->whereIn('serial_id', array_keys($cardPacks));
        });

        foreach ($cards as $card) {
            $modified = json_decode($card->modified_json, true);
            if (is_null($modified)) continue;
            $cardPack = $cardPacks[$card->serial_id];
            foreach ($modified as $key => $val) {
                $cardPack->iterate($key, $val);
            }
        }

        return $msgpack;
    }
}
