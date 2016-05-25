<?php

return [
    'index'      => ['as' => 'auth.index', 'uses' => '\App\Http\Modules\Authenticate\AuthenticateActions@index', 'middleware' => null, 'url' => "/login", 'method' => 'get'],
    'postLogin'  => ['uses' => '\App\Http\Controllers\Auth\AuthController@postLogin', 'middleware' => null, 'url' => "/postLogin", 'method' => 'post'],
    'postLogout' => ['as' => 'auth.logout', 'uses' => '\App\Http\Controllers\Auth\AuthController@getLogout', 'middleware' => null, 'url' => "/auth/logout", 'method' => 'get'],
    'edit'       => null,
    'update'     => null,
    'show'       => null,
    'destroy'    => null,
    'filter'     => null,
];
