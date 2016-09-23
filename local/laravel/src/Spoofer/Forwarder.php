<?php
namespace Spoofer;

class Forwarder {
    const SERVER_DOMAIN = "game.starlight-stage.jp";
    const MASKED_SERVER_DOMAIN = "real-game.starlight-stage.jp";

    public $body;
    public $contentType;

    public static function forward($path, $method='GET', $body=null, $customHeaders=null) {
        $forwarder = new self;

        if (preg_match('/^\//', $path)) {
            $path = substr($path, 1);
        }

        $clientHeaders = getallheaders();
        if (is_array($customHeaders)) {
            foreach ($customHeaders as $key => $val) {
                $clientHeaders[$key] = $val;
            }
        }
        foreach ($clientHeaders as $key => $val) {
            $headers[] = "$key: $val";
        }

        if ($clientHeaders["Host"] == static::SERVER_DOMAIN) {
            $url = static::MASKED_SERVER_DOMAIN;
        }
        $url .= "/$path";

        $ch = curl_init("$url");
        if ($method == 'POST') {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $forwarder->body = curl_exec($ch);
        $forwarder->contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);

        curl_close($ch);

        return $forwarder;
    }

    private function __construct() { }
}
