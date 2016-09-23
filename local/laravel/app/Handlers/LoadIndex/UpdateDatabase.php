<?php
namespace App\Handlers\LoadIndex;

use App\Repositories\Repository;
use App\Repositories\GameUserScopedRepository;
use App\Handlers\Handler;
use MessagePack\MessagePack;
use Spoofer\Certification;

class UpdateDatabase extends Handler {
	protected function responseInternal(MessagePack $msgpack) {
		$udid = Certification::udid();
		$viewerId = $msgpack->iterate('data_headers.viewer_id')->data;
		$userId = $msgpack->iterate('data_headers.user_id')->data;

		$userInfo = $msgpack->iterate('data.user_info');
		if (is_null($userInfo)) return $msgpack;

		$userRepository = new Repository(\App\GameUser::class);
		$user = $userRepository->insertOrUpdate(['viewer_id' => $viewerId], [
			"viewer_id" => $viewerId,
			"user_id" => $userId,
			"udid" => $udid,
			"user_json" => json_encode($userInfo->data)
        ]);
        $user->viewer_id = $viewerId;

		$albums = [];
		$albumRepository = new GameUserScopedRepository($user, \App\GameAlbum::class);
		$albumList = $msgpack->iterate('data.album_list');
		if (isset($albumList)) {
			foreach ($albumList->data as $album) {
				$cardId = $album['card_id'];
				$albums[$cardId] = $albumRepository->insertOrUpdate(['card_id' => $cardId], $album);
			}
		}

		$cards = [];
		$cardRepository = new GameUserScopedRepository($user, \App\GameCard::class);
		$cardList = $msgpack->iterate('data.user_card_list');
		$serialIds = [];
		if (isset($cardList)) {
			foreach ($cardList->data as $card) {
				$serialId = $card['serial_id'];
				$cardId = $card['card_id'];
				$cards[$serialId] = $cardRepository->insertOrUpdate(['serial_id' => $serialId], $card, [
					"album_id" => $albums[$cardId]->id,
					"card_json" => json_encode($card)
				]);
				$serialIds[] = $serialId;
			}

			$cardRepository->delete(function($query) use ($serialIds) {
				return $query->whereNotIn('serial_id', $serialIds);
			});
		}

        $unitRepository = new GameUserScopedRepository($user, \App\GameUnit::class);
        $unitRepository->with('gameCards');
		$unitList = $msgpack->iterate('data.user_unit_list');
		if (isset($unitList)) {
			foreach ($unitList->data as $unit) {
				$unitId = $unit['unit_id'];
				$cardId = $card['card_id'];
				$serialIds = [];
				for ($i = 0; $i < 5; $i++) {
					$serialIds[$i] = $unit["serial_id_$i"];
				}

				$unit = $unitRepository->insertOrUpdate(['unit_id' => $unitId], $unit);
				$unit->gameCards()->detach();
				foreach ($serialIds as $idx => $serialId) {
					if ($serialId == 0) continue;
					$unit->gameCards()->attach($cards[$serialId]->id, [
						"index" => $idx
					]);
				}
			}
		}

		$favoriteRepository = new GameUserScopedRepository($user, \App\GameFavorite::class);
		$favoritePack = $msgpack->iterate('data.user_favorite');
		if (isset($favoritePack)) {
			$favorite = $favoritePack->data;
			$idols = [];
			for ($i = 0; $i < 5; $i++) {
				$idols[$i] = [
					"serial_id" => $unit["serial_id_$i"],
					"change_flag" => $unit["change_flag_$i"]
				];
			}

			$favorite = $favoriteRepository->insertOrUpdate([], $favorite);
			$favorite->gameCards()->detach();
			foreach ($idols as $idx => $idol) {
				$serialId = $idol['serial_id'];
				if ($serialId == 0) continue;

				$card = $cards[$serialId];
				$favorite->gameCards()->attach($card->id, [
					"index" => $idx,
					"change_flag" => $idol['change_flag']
				]);
			}
		}

		return $msgpack;
	}
}
