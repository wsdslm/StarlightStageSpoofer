<?php

namespace App\Http\Controllers\APIv1;

use Auth;

use App\Repositories\GameUserScopedRepository;

class UserObjectController extends ObjectController {
    protected function apiMiddleware() {
        $this->middleware(['api.v1', 'auth']);
    }
    
    protected function createRepository($className) {
        $user = Auth::user();
        return new GameUserScopedRepository($user->gameUser, $className);
    }
}
