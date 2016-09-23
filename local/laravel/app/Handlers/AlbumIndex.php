<?php
namespace App\Handlers;

use App\Repositories\Repository;
use App\Repositories\GameUserScopedRepository;
use MessagePack\MessagePack;
use MessagePack\MessagePackFactory;
use Log;

class AlbumIndex extends Handler {
    protected $key;

    public function __construct($key="data.album") {
        $this->key = $key;
    }

    protected function responseInternal(MessagePack $msgpack) {
        if (!$this->checkUserSetting('modify_cards')) return $msgpack;
        $cardRepository = new GameUserScopedRepository($this->gameUser, \App\GameCard::class);

        $albumList = $msgpack->iterate($this->key);
        if (is_null($albumList)) return $msgpack;

        $albumPacks = [];
        foreach ($albumList->array as $albumPack) {
            $cardId = $albumPack->iterate('card_id')->data;
            $albumPacks[$cardId] = $albumPack;
        }

        $cards = $cardRepository->get(function($query) {
            return $query->whereNotNull('modified_json')
            ->where('modified_json', '!=', 'null');
        });

        foreach ($cards as $card) {
            $json = json_decode($card->modified_json);
            if (is_null($json->card_id)) continue;
            $cardId = $json->card_id;
            $this->pushNewAlbum($albumList, $albumPacks, $cardId);
            if ($cardId % 2 == 0) {
                $this->pushNewAlbum($albumList, $albumPacks, --$cardId);
            }
            $card->modified = $json;
        }

        $msgpack = $this->handleCharaList($msgpack, $cards);

        return $msgpack;
    }

    protected function pushNewAlbum($albumList, &$albumPacks, $cardId) {
        if (array_key_exists($cardId, $albumPacks)) return;
        $pack = MessagePackFactory::pack([
            'card_id'   => $cardId,
            'love'      => 13,
            'max_love_flag' => 0
        ]);
        $pack->packData();
        $albumList->add($pack);
        $albumPacks[$cardId] = $pack;
    }

    protected function handleCharaList(MessagePack $msgpack, $gameCards) {
        $charaList = $msgpack->iterate('data.user_chara_list');
        if (is_null($charaList)) return $msgpack;

        $charaPacks = [];
        foreach ($charaList->array as $charaPack) {
            $charaId = $charaPack->iterate('chara_id')->data;
            $charaPacks[$charaId] = $charaPack;
        }

        $cardIds = [];
        foreach ($gameCards as $gameCard) {
            if (is_null($gameCard->modified)) continue;
            $cardIds[] = $gameCard->modified->card_id;
        }

        $repo = new Repository(\App\Card::class);
        $cards = $repo->with('character')->get(function($query) use ($cardIds) {
            $query->whereIn('id', $cardIds);
        });

        foreach ($cards as $card) {
            $charaId = $card->character->id;
            $this->pushNewChara($charaList, $charaPacks, $charaId);
        }

        return $msgpack;
    }

    protected function pushNewChara($charaList, &$charaPacks, $charaId) {
        if (array_key_exists($charaId, $charaPacks)) return;
        $pack = MessagePackFactory::pack([
            'chara_id'  => $charaId,
            'fan'       => 13
        ]);
        $pack->packData();
        $charaList->add($pack);
        $charaPacks[$charaId] = $pack;
    }
}
