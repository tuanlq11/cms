<?php

return [
    'listInGroup'         => ['as' => 'user.listInGroup', 'uses' => '\App\Http\Modules\User\UserActions@listInGroup', 'middleware' => ['web'], 'url' => "/user/list", 'method' => 'get'],
    'addUserToGroup'      => ['as' => 'user.addUserToGroup', 'uses' => '\App\Http\Modules\User\UserActions@addUserToGroup', 'middleware' => null, 'url' => "/user/assign-user", 'method' => 'get'],
    'storeUserToGroup'    => ['uses' => '\App\Http\Modules\User\UserActions@storeUserToGroup', 'middleware' => null, 'url' => '/user/assign-user/{user}/{group}', 'method' => 'post'],
    'removeUserFromGroup' => ['uses' => '\App\Http\Modules\User\UserActions@removeUserFromGroup', 'middleware' => null, 'url' => '/user/remove-user-assign/{user}/{group}', 'method' => 'post'],
];
