<?php
namespace App\Handlers;

use Log;

use Pubnub\Pubnub;
use MessagePack\MessagePack;
use App\Helpers\Helper;
use App\GameUser;

class Handler {
    protected $gameUser;
    protected $user;
    private $pubnub;

    public function __construct() {
        $this->pubnub = new Pubnub(
            env('PUBNUB_PUBLISH_KEY'),
            env('PUBNUB_SUBSCRIBE_KEY'),
            env('PUBNUB_SECRET_KEY', ""),
            env('PUBNUB_SSL', false)
        );
    }

    public final function request(MessagePack $msgpack) {
        $tampered = $this->requestInternal($msgpack);
        return is_null($tampered) ? $msgpack : $tampered;
    }

    public final function response(MessagePack $msgpack) {
        $viewerId = $msgpack->iterate('data_headers.viewer_id')->data;
        $this->gameUser = GameUser::where("viewer_id", $viewerId)->first();
        $this->user = isset($this->gameUser) ? $this->gameUser->user : null;
        $tampered = $this->responseInternal($msgpack);
        return is_null($tampered) ? $msgpack : $tampered;
    }

    protected function requestInternal(MessagePack $msgpack) {
        return $msgpack;
    }

    protected function responseInternal(MessagePack $msgpack) {
        return $msgpack;
    }

    protected function checkUserSetting($key) {
        if (!$this->check($this->gameUser, 'settings_json')) return false;
        $settings = json_decode($this->gameUser->settings_json);
        return $this->check($settings, $key);
    }

    protected function check($obj, $key) {
        return Helper::traverse($obj, $key);
    }

    protected function publish($type, $data) {
        return isset($this->user) ? $this->pubnub->publish("channel-user-{$this->user->id}", json_encode(["type" => $type, "data" => $data])) : null;
    }
}
