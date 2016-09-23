<?php

namespace App\Http\Controllers\APIv1;

use Auth;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\GameUserScopedRepository;
use App\Helpers\ModelHelper;

class UserSearchController extends APIController {
    protected function apiMiddleware() {
        $this->middleware(['api.v1', 'auth']);
    }

    public function gameCards(Request $request) {
        $query = $request->get('q');
        if (is_null($query)) {
            return response("Query parameter required", 400);
        }

        $user = Auth::user();
        $repo = new GameUserScopedRepository($user->gameUser, \App\GameCard::class);
        $result = $repo->get(function($qb) use ($query) {
                return $qb->whereIn('serial_id', explode(",", $query))->orderBy('id', 'desc');
            });

        return ModelHelper::decodeJson($result);
    }
}
