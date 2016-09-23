<?php
namespace App\Handlers;

use Log;
use MessagePack\MessagePack;

use App\Repositories\GameUserScopedRepository;
use App\Helpers\Helper;

class GachaExec extends Handler {
    protected $gameCardRepository;

    protected function requestInternal(MessagePack $msgpack) {
        return $msgpack;
    }

    protected function responseInternal(MessagePack $msgpack) {
        $msgpack = $this->saveCardsToDB($msgpack);

        if (!$this->checkUserSetting('gacha.enabled')) {
            $this->publishGacha($msgpack);
            return $msgpack;
        }

        $cardIds = $this->checkUserSetting("gacha.card_id");
        if (isset($cardIds)) {
            $parts = explode(":", $cardIds);
            if (count($parts) < 2) {
                $startIndex = 0;
                $cardIds = array_map("intval", explode(",", $parts[0]));
            } else {
                $startIndex = intval($parts[0]);
                $cardIds = array_map("intval", explode(",", $parts[1]));
            }

            $gachaPacks = $msgpack->iterate('data.gacha_result')->array;
            $cardPacks = $msgpack->iterate('data.card_list');
            $cardIdx = 0;

            if (is_null($cardPacks)) return $msgpack;
            $cardPacks = $cardPacks->array;

            foreach ($gachaPacks as $idx => $gachaPack) {
                // break when no more card ids
                if ($cardIdx >= count($cardIds)) break;
                // don't tamper below start index
                if ($idx < $startIndex) continue;
                // don't tamper non-card reward
                if ($gachaPack->iterate('reward_type')->data != 6) continue;
                $card_id = $cardIds[$cardIdx];
                $cardPack = $cardPacks[$cardIdx];
                $cardIdx++;

                $gachaPack->iterate('reward_id', $card_id);
                if ($this->checkUserSetting('gacha.new')) {
                    $gachaPack->iterate('is_new', 1);
                } else {
                    $gachaPack->iterate('is_new', 0);
                }
                $cardPack->iterate('card_id', $card_id);

                $card = $cardPack->data;
                $this->gameCardRepository->insertOrUpdate(['serial_id' => $card['serial_id']], [
                    "modified_json" => json_encode([ 'card_id' => $card_id ])
                ]);
            }
        }

        $this->publishGacha($msgpack);
        return $msgpack;
    }

    protected function saveCardsToDB(MessagePack $msgpack) {
        $this->gameCardRepository = new GameUserScopedRepository($this->gameUser, \App\GameCard::class);
        $cardList = $msgpack->iterate('data.card_list');
        if (is_null($cardList)) return $msgpack;
        foreach ($cardList->data as $newCard) {
            $this->gameCardRepository->insertOrUpdate(['serial_id' => $newCard['serial_id']], $newCard, [
                "card_json" => json_encode($newCard)
            ]);
        }
        return $msgpack;
    }

    protected function publishGacha($msgpack) {
        $newCardSerialIds = [];
        $cardList = $msgpack->iterate('data.card_list');
        if (is_null($cardList)) return;

        foreach ($cardList->data as $card) {
            $newCardSerialIds[] = $card['serial_id'];
        }
        $this->publish("ADD_GAME_CARDS", $newCardSerialIds);
    }
}
