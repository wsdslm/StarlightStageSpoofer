<?php

namespace App\Http\Controllers\APIv1;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Repository;
use App\Helpers\ModelHelper;

class SearchController extends APIController {
    public function cards(Request $request) {
        $query = $request->get('q');
        if (is_null($query)) {
            return response("Query parameter required", 400);
        }

        $repo = new Repository(\App\Card::class);
        $result = $repo->with("character")
            ->get(function($qb) use ($query) {
                return $qb->whereHas("character", function($qb) use ($query) {
                    $qb->where('name', 'like', '%'.$query.'%');
                })->orderBy('id', 'desc')->take(50);
            });

        return ModelHelper::decodeJson($result, "character");
    }
}
