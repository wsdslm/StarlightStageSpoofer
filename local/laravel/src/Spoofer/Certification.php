<?php
namespace Spoofer;

class Certification {
	private static $udid;
    public static function udid($udid=null) {
    	if (isset($udid)) {
    		static::$udid = $udid;
    	}
    	if (is_null(static::$udid)) {
    		throw new \Exception("UDID is not initialized!");
    	}
        return static::$udid;
    }
}
