<?php

namespace App\Http\Controllers\APIv1;

use App\Http\Controllers\Controller;

abstract class APIController extends Controller {
    public function __construct() {
        $this->apiMiddleware();
    }

    protected function apiMiddleware() {
        $this->middleware('api.v1');
    }
}
