<?php

return [
    'dashboard' => [
        'class' => '',
        'label' => 'Dashboard',
        'route' => 'dashboard.index',
        'icon'  => 'fa-dashboard',
    ],
    'user'      => [
        'class'     => '',
        'label'     => 'User',
        'route'     => '',
        'icon'      => 'fa-user',
        'sub_items' => [
            [
                'class' => '',
                'label' => 'Manage Users',
                'route' => 'user.index',
                'icon'  => 'fa-list',
            ],
            [
                'class' => '',
                'label' => 'Create New User',
                'route' => 'user.create',
                'icon'  => 'fa-user-plus',
            ],
        ],
    ],
];