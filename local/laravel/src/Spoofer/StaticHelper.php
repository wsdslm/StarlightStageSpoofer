<?php

namespace Spoofer;

abstract class StaticHelper {
    public function __call($method, $args) {
        return call_user_func_array($this->$method, $args);
    }

    public static function __callStatic($method, $args) {
        if (is_null(static::$instance)) {
            static::$instance = new static;
        }
        return call_user_func_array([static::$instance, $method], $args);
    }
}
