<?php

namespace App\Http\Controllers;

use Log;

use Illuminate\Http\Request;

use Spoofer\Forwarder;
use Spoofer\Cryptographer;
use Spoofer\Certification;
use Spoofer\Encryption;
use Spoofer\AES256Crypt;

use MessagePack\MessagePack;
use MessagePack\MessagePackFactory;

use App\Helpers\Debugger;

class SpooferController extends Controller {
    private $registeredHandlers;

    public function __construct() {
        $this->registeredHandlers = config('handlers');
    }

    protected function isHandled($path) {
        return array_key_exists($path, $this->registeredHandlers);
    }

    protected function getHandler($path) {
        $handlerClass = \App\Handlers\Handler::class;
        if ($this->isHandled($path)) {
            $handlerClass = $this->registeredHandlers[$path];
        }
        return new $handlerClass;
    }

    public function forward(Request $request) {
        $path = $request->path();
        $method = $request->method();
        $body = $request->getContent();
        $shouldHandle = $this->isHandled($path);
        //$shouldHandle = $method == "POST";
        $handler = $this->getHandler($path);

        $headers = null;
        if ($shouldHandle) {
            $this->fetchUDID($request);
            $msgpack = $this->decodeBody($body);
            $msgpack = $handler->request($msgpack);
            $body = $this->encodeMsgpack($msgpack);
            $headers = $this->tamperHeaders($request, $msgpack);
            Debugger::sendToNodeJS("request", $request, $msgpack);
        } else {
            Debugger::sendToNodeJS("forward", $request);
        }

        $forwarder = Forwarder::forward($path, $method, $body, $headers);
        $body = $forwarder->body;

        if ($shouldHandle) {
            $msgpack = $this->decodeBody($body);
            $msgpack = $handler->response($msgpack);
            $body = $this->encodeMsgpack($msgpack);
            Debugger::sendToNodeJS("response", $request, $msgpack);
        }

        return response($body)->header('Content-Type', $forwarder->contentType);
    }

    private function decodeBody($body) {
        $decrypted = Encryption::decrypt($body);
        $decoded = base64_decode($decrypted);
        return MessagePackFactory::unpack($decoded);
    }

    private function encodeMsgpack(MessagePack $msgpack) {
        $packed = $msgpack->pack();
        $encoded = base64_encode($packed);
        return Encryption::encrypt($encoded);
    }

    private function fetchUDID(Request $request) {
        $encoded = $request->header("UDID");
        $udid = Cryptographer::decode($encoded);
        return Certification::udid($udid);
    }

    private function tamperHeaders(Request $request, MessagePack $msgpack) {
        $headers = [];
        $this->tamperHeaderParam($headers, $request, $msgpack);
        return $headers;
    }

    private function tamperHeaderParam(&$headers, Request $request, MessagePack $msgpack) {
        $param = $request->header("PARAM");
        if (is_null($param)) return;

        $encrypted = $msgpack->iterate('viewer_id')->data;
        $iv = substr($encrypted, 0, 32);
        $str = substr($encrypted, 32);

        $viewerId = trim(AES256Crypt::decrypt($str, $iv));
        $udid = Certification::udid();
        $str2 = base64_encode($msgpack->pack());
        $path = '/'.$request->path();

        $newParam = sha1($udid.$viewerId.$path.$str2);
        $headers["PARAM"] = $newParam;
    }
}
