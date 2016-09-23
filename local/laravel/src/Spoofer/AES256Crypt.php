<?php

namespace Spoofer;

class AES256Crypt extends StaticHelper {
    private static $key = 's%5VNQ(H$&Bqb6#3+78h29!Ft4wSg)ex';
    protected static $instance;

    protected function encrypt($message, $iv) {
        $encrypted = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, self::$key, $message, MCRYPT_MODE_CBC, $iv);
        return base64_encode($encrypted);
    }

    protected function decrypt($encstr, $iv) {
        $buffer = base64_decode($encstr);
        return mcrypt_decrypt(MCRYPT_RIJNDAEL_256, self::$key, $buffer, MCRYPT_MODE_CBC, $iv);
    }
}
