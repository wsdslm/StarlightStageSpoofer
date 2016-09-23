<?php
namespace App\Helpers;

class Helper {
    public static function traverse($arr, $keys, $value=null, $options=[]) {
        if (is_string($keys)) {
            $keys = explode(".", $keys);
        }

        $current = $arr;
        $parent = null;
        $lastKey = null;

        foreach ($keys as $key) {
            if (is_null($current)) {
                if (!self::checkKey($options, 'parents')) break;
                if (is_array($parent)) {
                    $parent[$lastKey] = $current = [];
                } else {
                    $parent->$lastKey = $current = new \stdClass;
                }
            }

            $parent = $current;
            if (is_array($parent)) {
                $current = $parent[$key];
            } else {
                $current = isset($parent->$key) ? $parent->$key : null;
            }
            $lastKey = $key;
        }

        if (isset($value)) {
            $parent[$lastKey] = $value;
        }

        return $current;
    }

    public static function checkKey($arr, $key) {
        if (!array_key_exists($key, $arr)) return false;
        return $arr[$key];
    }
}
