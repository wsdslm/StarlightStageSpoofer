<?php
namespace App\Http\Controllers;

use Auth;
use App\Repositories\Repository;
use App\Helpers\ModelHelper;

class AppController extends Controller {
    public function index() {
        $with = [
            "gameUser.gameCards" => function($qb) {
                $qb->orderBy('serial_id', 'desc');
            },
            "gameUser.gameCards.gameUnits"
        ];
        $repository = new Repository(\App\User::class);
        $repository->with($with);

        if (Auth::check()) {
            $user = Auth::user();
            $user = $repository->first($user->id);
            $user = ModelHelper::decodeJson($user, $with);
        } else {
            $user = null;
        }

        return view('app', ['user' => $user]);
    }
}
