<?php
namespace App\Handlers;

use Log;
use MessagePack\MessagePack;

use App\Repositories\GameUserScopedRepository;

class LiveStart extends Handler {
    protected $unitId;

    protected function requestInternal(MessagePack $msgpack) {
        $this->unitId = $msgpack->iterate('live_info.unit_id')->data;
        foreach ($msgpack->iterate('live_info.mainmember_dress_types')->array as $dress) {
            $dress->value(0);
        }
    }

    protected function responseInternal(MessagePack $msgpack) {
        if (!$this->checkUserSetting('modify_cards')) return $msgpack;
        $unitRepository = new GameUserScopedRepository($this->gameUser, \App\GameUnit::class);
        $unitRepository->with('gameCards');

        $unit = $unitRepository->first('unit_id', $this->unitId);
        $replaceIds = [];
        foreach ($unit->gameCards as $card) {
            $idx = $card->pivot->index;
            $mod = $card->modified_json;
            if (isset($mod)) {
                $mod = json_decode($mod);
                $cardId = isset($mod->card_id) ? $mod->card_id : $card->card_id;
                $replaceIds[$idx] = $cardId;
            }
        }

        foreach ($msgpack->iterate('data.live_unit_member')->array as $idx => $member) {
            if (array_key_exists($idx, $replaceIds)) {
                $member->value($replaceIds[$idx]);
            }
        }

        return $msgpack;
    }
}
