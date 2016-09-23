<?php
namespace App\Handlers;

use MessagePack\MessagePack;

class Logger extends Handler {
	public function responseInternal(MessagePack $msgpack) {
		return $msgpack;
	}
}