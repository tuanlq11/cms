<?php

return [
    'dashboard'=> [
        'class' =>  '',
        'label' =>  'Dashboard',
        'route' =>  'dashboard.index',
        'icon'  =>  'fa-dashboard',
    ],
    'user' => [
        'class' =>  '',
        'label' =>  'User',
        'route' =>  '',
        'icon'  =>  'fa-user',
        'sub_items' => [
            [
                'class' => '',
                'label' => 'Manage Users',
                'route' => 'user.index',
                'icon'  =>  'fa-list',
            ],
            [
                'class' => '',
                'label' => 'Create New User',
                'route' => 'user.create',
                'icon'  =>  'fa-user-plus'
            ],
        ]
    ],
    'group' =>  [
        'class' =>  '',
        'label' =>  'Group',
        'route' =>  '',
        'icon'  =>  'fa-group',
        'sub_items' => [
            [
                'class' => '',
                'label' => 'Manage Groups',
                'route' => 'group.index',
                'icon'  =>  'fa-list',
            ],
            [
                'class' =>  '',
                'label' => 'Create New Group',
                'route' =>  'group.create',
                'icon'  =>  'fa-plus'
            ]
        ]
    ],
    'role'  =>  [
        'class' =>  '',
        'label' =>  'Role',
        'route' =>  '',
        'icon'  =>  'fa-user',
        'sub_items' => [
            [
                'class' => '',
                'label' =>  'Manage Roles',
                'route' =>  'role.index',
                'icon'  =>  'fa-list',
            ],
            [
                'class' =>  '',
                'label' =>  'Create New Role',
                'route' =>  'role.create',
                'icon'  =>  'fa-user-plus'
            ]
        ]
    ],
    'permission'    => [
        'class' =>  '',
        'label' =>  'Permisions',
        'route' =>  'permission.index',
        'icon'  =>  'fa-check',
    ],
    'audit' => [
        'class' =>  '',
        'label' =>  'Auditing',
        'route'  =>  'audit.index',
        'icon'  =>  'fa fa-pencil-square-o',
    ]
];