<?php
namespace App\Handlers;

use MessagePack\MessagePack;

class EventMedleyNext extends Handler {
    protected function responseInternal(MessagePack $msgpack) {
        if (!$this->checkUserSetting('modify_cards')) return $msgpack;
        
        foreach ($this->gameUser->medleyCards as $card) {
            $idx = $card->pivot->index;
            $json = $card->modifiedJson();
            if (is_null($json)) continue;
            if (isset($json->card_id)) {
                $msgpack->iterate("data.live_unit_member.$idx", $json->card_id);
            }
        }
        return $msgpack;
	}
}
