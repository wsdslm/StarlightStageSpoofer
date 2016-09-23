<?php
namespace App\Handlers;

use MessagePack\MessagePack;

class LiveStartView extends LiveStart {
	protected function requestInternal(MessagePack $msgpack) {
		$this->unitId = $msgpack->iterate('unit_id')->data;
	}
}