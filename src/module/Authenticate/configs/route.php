<?php

return [
    'index'      => ['as' => 'auth.index', 'uses' => '\App\Http\Modules\Authenticate\AuthenticateActions@index', 'middleware' => ['web', 'guest'], 'url' => "/login", 'method' => 'get'],
    'postLogin'  => ['uses' => '\App\Http\Controllers\Auth\AuthController@postLogin', 'middleware' => ['web'], 'url' => "/postLogin", 'method' => 'post'],
    'postLogout' => ['as' => 'auth.logout', 'uses' => 'App\Http\Controllers\Auth\AuthController@logout', 'middleware' => ['web'], 'url' => "/logout", 'method' => 'get'],
    'edit'       => null,
    'update'     => null,
    'show'       => null,
    'destroy'    => null,
    'filter'     => null,
];
