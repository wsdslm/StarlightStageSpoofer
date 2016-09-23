<?php
namespace Spoofer;

abstract class Utilities {
    public static function strToHex($str) {
        $hex = "";
        foreach (str_split($str) as $c) {
            $hex .= substr('0'.dechex(ord($c)), -2);
        }
        return $hex;
    }

    public static function compareBinary($bin, $hex) {
        if (is_string($bin)) $bin = ord($bin);
        if (is_string($hex)) $hex = ord($hex);
        return $bin == $hex;
    }
}
