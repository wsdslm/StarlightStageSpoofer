<?php

Route::group(['prefix' => '/api/v1', 'namespace' => 'APIv1'], function() {
    Route::group(['prefix' => '/objects/{object}'], function() {
        Route::get('/', 'ObjectController@index');
        Route::get('/{id}', 'ObjectController@show');
        Route::put('/{id}', 'ObjectController@update');
    });

    Route::group(['prefix' => '/user_objects/{object}'], function() {
        Route::get('/', 'UserObjectController@index');
        Route::get('/{id}', 'UserObjectController@show');
        Route::put('/{id}', 'UserObjectController@update');
    });

    Route::group(['prefix' => '/search'], function() {
        Route::get('/cards', 'SearchController@cards');
    });

    Route::group(['prefix' => '/user_search'], function() {
        Route::get('/game_cards', 'UserSearchController@gameCards');
    });
});
