<?php
namespace Spoofer;

class Cryptographer extends StaticHelper {
    protected static $instance;

    protected function generateKeyString() {
        $str = "";
        for ($index = 0; $index < 32; ++$index) {
            $str .= rand(0, 65535);
        }
        return substr(base64_encode($str), 0, 32);
    }

    protected function decode($dat) {
    	if (is_null($dat) || strlen($dat) < 4) {
    		return $dat;
    	}
    	$num1 = hexdec(substr($dat, 0, 4));
    	$str = "";
    	$num2 = 2;

    	$len = strlen($dat);
    	$payload = substr($dat, 4, $len - 4);
    	foreach (str_split($payload) as $ch) {
    		if ($num2 % 4 == 0) {
    			$str .= chr(ord($ch) - 10);
    		}
    		++$num2;
    		if (strlen($str) >= $num1) {
    			break;
    		}
    	}

    	return $str;
    }
}
