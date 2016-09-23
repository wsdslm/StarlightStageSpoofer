<?php
namespace App\Handlers;

use Log;
use MessagePack\MessagePack;

use App\Repositories\GameUserScopedRepository;
use App\Handlers\LoadIndex\UpdateDatabase;
use App\Handlers\LoadIndex\TamperData;

class UnitEdit extends Handler {
    protected $unitInfoList;

    protected function requestInternal(MessagePack $msgpack) {
        $this->unitInfoList = $msgpack->iterate('unit_info_list')->data;
        foreach ($msgpack->iterate('unit_info_list')->array as $unit) {
            foreach ($unit->iterate('dress_types')->array as $dress) {
                $dress->value(0);
            }
        }
        return $msgpack;
    }

    protected function responseInternal(MessagePack $msgpack) {
        $cardRepository = new GameUserScopedRepository($this->gameUser, \App\GameCard::class);
        $unitRepository = new GameUserScopedRepository($this->gameUser, \App\GameUnit::class);

        $unitList = [];
        foreach ($this->unitInfoList as $unit) {
            $unitList[$unit['unit_id']] = $unit;
        }

        $cards = [];
        $cardRepository->get()->each(function($card, $idx) use (&$cards) {
            $cards[$card->serial_id] = $card;
        });

        $units = [];
        $unitRepository->get(function($query) use ($unitList) {
            return $query->whereIn('unit_id', array_keys($unitList));
        })->each(function($unit, $idx) use (&$units) {
            $units[$unit->unit_id] = $unit;
        });

        foreach ($unitList as $unitId => $unitInfo) {
            $unit = $units[$unitId];
            $unit->gameCards()->detach();
            foreach ($unitInfo['serial_ids'] as $idx => $serialId) {
                if ($serialId === 0) continue;
                $card = $cards[$serialId];
                $dressType = $unitInfo['dress_types'][$idx];
                $unit->gameCards()->attach($card->id, ['index' => $idx, 'dress_type' => $dressType]);
            }
        }

        return $msgpack;
    }
}
