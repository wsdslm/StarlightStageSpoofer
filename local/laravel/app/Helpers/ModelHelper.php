<?php
namespace App\Helpers;

class ModelHelper {
    public static function decodeJson($model, $withs=null) {
        if (self::isCollection($model)) {
            $model->each(function($model, $key) use ($withs) {
                self::decodeJson($model, $withs);
            });
        } else if (isset($model)) {
            foreach ($model->getAttributes() as $key => $value) {
                if (!preg_match('/_json$/', $key)) continue;
                if (is_string($value)) {
                    $model->$key = json_decode($value);
                }
            }

            if (is_string($withs)) $withs = explode(",", $withs);
            if (is_array($withs)) {
                foreach ($withs as $key => $with) {
                    if (is_string($key)) $with = $key;
                    $joins = is_array($with) ? $with : explode(".", $with);
                    $key = array_shift($joins);

                    if (count($joins) > 0) {
                        self::decodeJson($model->$key, [$joins]);
                    } else {
                        self::decodeJson($model->$key);
                    }
                }
            }
        }
        return $model;
    }

    public static function isCollection($coll) {
        return $coll instanceof \Illuminate\Support\Collection;
    }
}
