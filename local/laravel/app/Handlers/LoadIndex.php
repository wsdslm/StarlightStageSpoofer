<?php
namespace App\Handlers;

use Log;
use MessagePack\MessagePack;

use App\Handlers\LoadIndex\UpdateDatabase;
use App\Handlers\LoadIndex\TamperData;

class LoadIndex extends Handler {
    protected function requestInternal(MessagePack $msgpack) {
        return $msgpack;
    }

	protected function responseInternal(MessagePack $msgpack) {
		$msgpack = (new UpdateDatabase)->response($msgpack);
		$msgpack = (new TamperData)->response($msgpack);
		return (new AlbumIndex("data.album_list"))->response($msgpack);
	}
}
