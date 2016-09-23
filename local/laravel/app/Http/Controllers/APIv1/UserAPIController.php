<?php

namespace App\Http\Controllers\APIv1;

use App\Http\Controllers\Controller;

abstract class APIController extends Controller {
    protected function apiMiddleware() {
        $this->middleware(['api.v1', 'auth']);
    }
}
