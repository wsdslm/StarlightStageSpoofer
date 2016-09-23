<?php

namespace App\Http\Controllers\APIv1;

use DB;
use Schema;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Repositories\Repository;
use App\Helpers\ModelHelper;

class ObjectController extends APIController {
    public function index(Request $request, $object) {
        $with = $this->with($request);
        $result = $this->query($object, null, $with);
        if (is_null($result)) {
            return response("Unknown object", 404);
        } else {
            return ModelHelper::decodeJson($result, $with);
        }
    }

    public function show(Request $request, $object, $id) {
        $with = $this->with($request);
        $result = $this->query($object, $id, $with);
        if (is_null($result)) {
            return response("Unknown object", 404);
        } else {
            return ModelHelper::decodeJson($result, $with);
        }
    }

    public function update(Request $request, $object, $id) {
        $className = $this->getClassName($object);
        if (!class_exists($className)) {
            return response("Unknown object", 404);
        } else {
            $repository = new Repository($className);
            $queryArray = [];
            $queryArray[$repository->getKeyName()] = $id;
            $repository->insertOrUpdate($queryArray, $request->all());
            return json_encode([
                "status" => [
                    "code" => "200",
                    "text" => "OK"
                ]
            ]);
        }
    }

    protected function with(Request $request) {
        return $request->has('with') ? explode(",", $request->get('with')) : null;
    }

    protected function query($object, $id=null, $with=null, $join=null) {
        if (isset($join)) {
            $className = $this->getClassName($join);
            $baseClassName = $this->getClassName($object);
        } else {
            $className = $this->getClassName($object);
            $baseClassName = null;
        }

        $classInvalid = !class_exists($className);
        $baseClassInvalid = isset($baseClassName) && !class_exists($baseClassName);
        if ($classInvalid || $baseClassInvalid) {
            return null;
        }

        $repository = $this->createRepository($className);
        if (isset($baseClassName)) {
            $indexKey = (new $baseClassName)->getKeyName();
        } else {
            $indexKey = $repository->getKeyName();
        }

        if (isset($with)) $repository->with($with);

        if (isset($join) && isset($id)) {
            return $repository->get($indexKey, $id);
        } else if (isset($id)) {
            return $repository->first($indexKey, $id);
        } else {
            return $repository->get();
        }
    }

    protected function getClassName($object) {
        return "\\App\\$object";
    }

    protected function createRepository($className) {
        return new Repository($className);
    }
}
