<?php
namespace App\Handlers;

use Log;
use MessagePack\MessagePack;

use App\Repositories\Repository;
use App\Repositories\GameUserScopedRepository;
use App\Handlers\LoadIndex\UpdateDatabase;
use App\Handlers\LoadIndex\TamperData;

class LiveEnd extends GachaExec {
	protected function requestInternal(MessagePack $msgpack) {
		return $msgpack;
	}

	protected function responseInternal(MessagePack $msgpack) {
        $msgpack = $this->saveCardsToDB($msgpack);
		$this->publishGacha($msgpack);

		$loveListPack = $msgpack->iterate('data.love_list');
		if (is_null($loveListPack)) return $msgpack;

        $loveList = [];
		foreach ($loveListPack->array as $love) {
			$serialId = $love->value('serial_id')->data;
			$loveList[$serialId] = $love;
		}

		$gameCards = [];
		$this->gameCardRepository->with('card')->get(function($query) use ($loveList) {
			return $query->whereIn('serial_id', array_keys($loveList));
        })->each(function($gameCard, $idx) use ($loveList, &$gameCards) {
            $lovePack = $loveList[$gameCard->serial_id];
			$love = $lovePack->data;

			$json = json_decode($gameCard->card_json);
			$json->love = $love['after_love'];

			$gameCard->card_json = json_encode($json);
			$gameCard->save();

			$gameCards[$gameCard->serial_id] = $gameCard;
		});

		if ($this->checkUserSetting('modify_cards')) {
			$msgpack = $this->modifyLove($msgpack, $loveList, $gameCards);
		}

		return $msgpack;
	}

	protected function modifyLove(MessagePack $msgpack, $loveList, $gameCards) {
		$modifiedCards = [];
        foreach ($gameCards as $serialId => $gameCard) {
            $json = $gameCard->modified_json;
            if (is_null($json) || $json == "null") continue;
            $json = json_decode($json);
            if (is_null($json->card_id)) continue;
			$gameCard->modified_json = $json;
			$modifiedCards[$serialId] = $gameCard;
		}

		$cards = [];
		$cardRepository = new Repository(\App\Card::class);
		$cardRepository->get(function($qb) use ($modifiedCards) {
			return $qb->whereIn('id', array_map(function($gameCard) {
				return $gameCard->modified_json->card_id;
			}, $modifiedCards));
		})->each(function($card, $idx) use (&$cards) {
			$cards[$card->id] = $card;
		});

		foreach ($modifiedCards as $serialId => $gameCard) {
			$maxLoveOriginal = $this->getMaxLove($gameCard->card);
			$maxLoveModified = $this->getMaxLove($cards[$gameCard->modified_json->card_id]);
			$ratio = $maxLoveModified / $maxLoveOriginal;

			$lovePack = $loveList[$serialId];
			$love = $lovePack->data;

			$before = $love['before_love'];
			$lovePack->value('before_love', round($before * $ratio));

			if ($love['max_love_flag']) {
				$lovePack->value('after_love', $maxLoveModified);
			} else {
				$after = $love['after_love'];
				$lovePack->value('after_love', round($after * $ratio));
			}
		}

		return $msgpack;
	}

	private function getMaxLove($card) {
		$rarity = json_decode($card->rarity_json);
		return $rarity->max_love;
	}
}
