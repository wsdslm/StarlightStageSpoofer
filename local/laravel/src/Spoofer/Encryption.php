<?php
namespace Spoofer;

use Spoofer\Cryptographer;
use Spoofer\Certification;

class Encryption {
    private static $lastKey;

    public static function getLastKey() {
        if (self::$lastKey != null) {
            return self::$lastKey;
        } else {
            return Cryptographer::generateKeyString();
        }
    }

    public static function encrypt($text, $key=null) {
        if ($key == null) {
            $key = Cryptographer::generateKeyString();
        }
        self::$lastKey = $key;
        $iv = str_replace("-", "", Certification::udid());
        $encrypted = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $text, MCRYPT_MODE_CBC, $iv);
        $encrypted .= $key;
        return base64_encode($encrypted);
    }

    public static function decrypt($encrypted) {
        $encrypted = base64_decode($encrypted);
        $bufferLength = strlen($encrypted) - 32;
        $buffer = substr($encrypted, 0, $bufferLength);
        self::$lastKey = $key = substr($encrypted, $bufferLength);
        $iv = str_replace("-", "", Certification::udid());
        return mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $buffer, MCRYPT_MODE_CBC, $iv);
    }
}
