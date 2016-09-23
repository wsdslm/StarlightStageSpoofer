<?php
namespace App\Helpers;

use Illuminate\Http\Request;
use MessagePack\MessagePack;
use MessagePack\MessagePackFactory;

class Debugger {
	public static function sendToNodeJS($type, Request $request, MessagePack $msgpack=null) {
		$host = env("JS_HOST", "localhost");
		$port = env("JS_PORT", 3100);

		$json = [
			"type" => $type,
			"path" => $request->path(),
			"method" => $request->method(),
		];

		if (isset($msgpack)) {
			$msgpack = MessagePackFactory::unpack($msgpack->pack());
			$json["json"] = json_encode($msgpack->data, JSON_PRETTY_PRINT);
		}

		$json_text = json_encode($json);
		$url = "http://$host:$port";

		$ch = curl_init();
		curl_setopt_array($ch, [
			CURLOPT_URL => $url,
			CURLOPT_POST => 1,
			CURLOPT_POSTFIELDS => $json_text,
			CURLOPT_HTTPHEADER => [
				"Content-Type: application/json"
			],
			CURLOPT_RETURNTRANSFER => true
		]);

		curl_exec($ch);
		curl_close($ch);
	}
}
