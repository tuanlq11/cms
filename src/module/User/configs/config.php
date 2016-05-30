<?php
/**
 * Created by Mr.Tuan.
 * User: tuanlq
 * Date: 1/14/16
 * Time: 10:53 AM
 */

return [
    'dev' => [
        'is_secure' => true,
        'cache'     => [
            'configuration' => [
                'enabled' => true,
            ],
            'filter' => [
                'enabled' => true,
            ],
        ],
    ],
    'all' => [
        'is_secure'      => true,
        'credentials'    => ['admin'],
        'create'         => [
            'label'  => 'Create New User',
            'field'  => [
                'email'           => ['label' => 'Email', 'validation' => 'required|email'],
                'first_name'      => ['label' => 'First Name', 'validation' => 'required|string|between:1,30'],
                'last_name'       => ['label' => 'Last Name', 'validation' => 'required|string|between:1,30'],
                'password'        => ['label' => 'Password', 'type' => 'password', 'validation' => 'required|string|between:6,32'],
                'password_retype' => [
                    'label'      => 'Password Confirmation',
                    'type'       => 'password',
                    'validation' => 'required|string|between:6,32|equal_field:create.password',
                    'message'    => [
                        'equal_field' => 'das',
                    ],
                ],
                'role'            => [
                    'label'      => 'Role',
                    'type'       => 'relationTwoSelectBox',
                    'params'     => [
                        'model'    => '\tuanlq11\cms\model\Role',
                        'primary'  => 'id',
                        'show'     => 'name',
                        'extend'   => true,
                        'multiple' => true,
                    ],
                    'validation' => 'arr_exists:roles,id',
                ],
                'group'           => [
                    'label'      => 'Group',
                    'type'       => 'relationTwoSelectBox',
                    'params'     => [
                        'model'    => '\tuanlq11\cms\model\Group',
                        'primary'  => 'id',
                        'show'     => 'name',
                        'extend'   => true,
                        'multiple' => true,
                    ],
                    'validation' => 'arr_exists:groups,id',
                ],
                'is_active'       => [
                    'label'  => 'Is Active',
                    'type'   => 'yesNoCheckBox',
                    'params' => [
                        'extend'   => false,
                        'multiple' => false,
                    ],
                ],
            ],
            /**
             * Sub Action in action show
             */
            'action' => [
                'toList'          => ['label' => 'Back To List', 'class' => 'btn btn-default',],
                'saveAndRedirect' => ['label' => 'Save', 'class' => 'btn btn-success'],
                'save'   => '{DISABLED}',
                'saveAndCreate'   => '{DISABLED}',
            ],
        ],
        'edit'           => [
            'label'  => 'Edit User',
            'field'  => [
                'email'      => ['label' => 'Email', 'validation' => 'required|email|unique:users,email,{IGNORE}'],
                'first_name' => ['label' => 'First Name', 'validation' => 'required|string|between:1,30'],
                'last_name'  => ['label' => 'Last Name', 'validation' => 'required|string|between:1,30'],
                'role'       => [
                    'label'      => 'Role',
                    'type'       => 'relationTwoSelectBox',
                    'params'     => [
                        'model'    => '\tuanlq11\cms\model\Role',
                        'primary'  => 'id',
                        'show'     => 'name',
                        'extend'   => true,
                        'multiple' => true,
                    ],
                    'validation' => 'arr_exists:roles,id',
                ],
                'group'      => [
                    'label'      => 'Group',
                    'type'       => 'relationTwoSelectBox',
                    'params'     => [
                        'model'    => '\tuanlq11\cms\model\Group',
                        'primary'  => 'id',
                        'show'     => 'name',
                        'extend'   => true,
                        'multiple' => true,
                    ],
                    'validation' => 'arr_exists:groups,id',
                ],
                'is_active'  => [
                    'label'  => 'Is Active',
                    'type'   => 'yesNoCheckBox',
                    'params' => [
                        'extend'   => false,
                        'multiple' => false,
                    ],
                ],
            ],
            'action' => [
                'toList' => ['label' => 'Back to list', 'class' => 'btn btn-default'],
                'save'   => ['label' => 'Save', 'class' => 'btn btn-success'],
                'delete' => '{DISABLED}',
            ],
        ],
        'show'           => [
            'label'  => 'View User Detail',
            'field'  => [
                'email'      => ['label' => 'Email'],
                'first_name' => ['label' => 'First Name'],
                'last_name'  => ['label' => 'Last Name'],
                'role'       => [
                    'label'  => 'Role',
                    'type'   => 'relationTwoSelectBox',
                    'params' => [
                        'model'    => '\App\Models\Role',
                        'primary'  => 'id',
                        'show'     => 'name',
                        'extend'   => true,
                        'multiple' => true,
                    ],
                ],
                'group'      => [
                    'label'      => 'Group',
                    'type'       => 'relationTwoSelectBox',
                    'params'     => [
                        'model'    => '\App\Models\Group',
                        'primary'  => 'id',
                        'show'     => 'name',
                        'extend'   => true,
                        'multiple' => true,
                    ],
                    'validation' => 'arr_exists:groups,id',
                ],
                'is_active'  => [
                    'label'  => 'Is Active',
                    'type'   => 'yesNoCheckBox',
                    'params' => [
                        'extend'   => false,
                        'multiple' => false,
                    ],
                ],
            ],
            'action' => [
                'toList' => ['label' => 'Back To List', 'class' => 'btn btn-default',],

                'saveAndCreate' => '{DISABLED}',
            ],
            'view'   => [

            ],
        ],
        'index'          => [
            'label'         => 'Manage Users',
            'max_per_page'  => 3,
            'order_by'      => ['created_at:desc'],
            'credentials'   => ['admin', 'test'],
            'list'          => [
                'id'         => ['label' => 'ID'],
                'email'      => ['label' => 'Email'],
                'first_name' => ['label' => 'First Name'],
                'last_name'  => ['label' => 'Last Name'],
                'role_id'    => ['label' => 'Role'],
                'group_id'   => ['label' => 'Group'],
                'created_at' => ['label' => 'Date Added', 'template' => '#User::date'],
                'updated_at' => ['label' => 'Last Updated'],
                'is_active'  => ['label' => 'Status'],
            ],
            'filter'        => [
                'first_name' => [
                    'label'      => 'First Name',
                    'validation' => 'string|between:3,30',
                    'message'    => [
                        'between'  => 'Input must is string',
                        'required' => 'Data is not null',
                    ],
                    'params'     => [
                        'wrapper' => ['class' => 'form-group col-lg-4'],
                    ],
                ],
                'last_name'  => [
                    'label'      => 'Last Name',
                    'validation' => 'string|between:3,30',
                    'message'    => [
                        'between'  => 'Input must is string',
                        'required' => 'Data is not null',
                    ],
                    'params'     => [
                        'wrapper' => ['class' => 'form-group col-lg-4'],
                    ],
                ],
                'email'      => [
                    'label'      => 'Email',
                    'validation' => 'email|between:3,50',
                    'message'    => [
                        'email'    => 'Must is email format',
                        'required' => 'Data is not null',
                    ],
                    'params'     => [
                        'wrapper' => ['class' => 'form-group col-lg-4'],
                    ],
                ],
                'role_id'    => [
                    'label'  => 'Role',
                    'type'   => 'relationSelectBox',
                    'params' => [
                        'model'    => '\tuanlq11\cms\model\Role',
                        'primary'  => 'id',
                        'show'     => 'name',
                        'extend'   => true,
                        'multiple' => false,
                        'wrapper'  => ['class' => 'form-group col-lg-4'],
                    ],
                ],

                'is_active' => [
                    'label'  => 'Status',
                    'type'   => 'selectBox',
                    'params' => [
                        'choices'  => [
                            ''  => '',
                            '1' => 'Active',
                            '0' => 'Inactive',
                        ],
                        'extend'   => false,
                        'multiple' => false,
                        'wrapper'  => ['class' => 'form-group col-lg-4'],
                    ],
                ],
            ],
            'object_action' => [
                'edit'   => ['label' => 'Edit', 'class' => 'btn btn-success'],
                'show'   => ['label' => 'View Detail', 'class' => 'btn btn-primary'],
                'delete' => ['label' => 'Delete', 'class' => 'btn btn-danger'],
            ],
        ],
        'listInGroup'    => [
            'label'         => 'Users In Group',
            'max_per_page'  => 3,
            'order_by'      => ['created_at:desc'],
            'credentials'   => ['admin', 'test'],
            'list'          => [
                'id'         => ['label' => 'ID'],
                'email'      => ['label' => 'Email'],
                'first_name' => ['label' => 'First Name'],
                'last_name'  => ['label' => 'Last Name'],
                'is_active'  => ['label' => 'Status'],
            ],
            'filter'        => [],
            'object_action' => [
                'edit'            => '{DISABLED}',
                'show'            => '{DISABLED}',
                'delete'          => '{DISABLED}',
                'removeFromGroup' => ['label' => 'Remove From Group', 'class' => 'btn btn-success'],
            ],
            'batch_action'  => [
                'delete' => '{DISABLED}',
            ],
            'layout'        => [
                'name'      => 'layout',
                'namespace' => '',
            ],
        ],
        'addUserToGroup' => [
            'label'           => 'Add Users In Group',
            'max_per_page'    => 3,
            'order_by'        => ['created_at:desc'],
            'credentials'     => ['admin', 'test'],
            'list'            => [
                'id'         => ['label' => 'ID'],
                'email'      => ['label' => 'Email'],
                'first_name' => ['label' => 'First Name'],
                'last_name'  => ['label' => 'Last Name'],
            ],
            'filter'          => [
                'first_name' => [
                    'label'      => 'First Name',
                    'validation' => 'string|between:3,10',
                    'message'    => [
                        'between'  => 'Input must is string',
                        'required' => 'Data is not null',
                    ],
                    'params'     => [
                        'wrapper' => ['class' => 'form-group col-lg-6'],
                    ],
                ],
                'last_name'  => [
                    'label'      => 'Last Name',
                    'validation' => 'string|between:3,10',
                    'message'    => [
                        'between'  => 'Input must is string',
                        'required' => 'Data is not null',
                    ],
                    'params'     => [
                        'wrapper' => ['class' => 'form-group col-lg-6'],
                    ],
                ],
                'email'      => [
                    'label'      => 'Email',
                    'validation' => 'email|between:5,50',
                    'message'    => [
                        'email'    => 'Must is email format',
                        'required' => 'Data is not null',
                    ],
                    'params'     => [
                        'wrapper' => ['class' => 'form-group col-lg-6'],
                    ],
                ],
            ],
            'filter_buttons'  => [
                'wrapper' => ['class' => 'form-group col-lg-12'],
                'items'   => [
                    'Filter' => ['type' => 'submit', 'options' => ['attr' => ['class' => 'btn btn-primary', 'name' => '_btnFilter']]],
                    'Reset'  => ['type' => 'submit', 'options' => ['attr' => ['class' => 'btn btn-default', 'name' => '_btnReset']]],
                ],
            ],
            'filter_action'   => 'filter',
            'filter_redirect' => 'addUserInGroup',
            'object_action'   => [
                'edit'       => '{DISABLED}',
                'show'       => '{DISABLED}',
                'delete'     => '{DISABLED}',
                'addToGroup' => ['label' => 'Add To Group', 'class' => 'btn btn-success'],
            ],
            'batch_action'    => [
                'delete' => '{DISABLED}',
            ],
            'layout'          => [
                'name'      => 'layout',
                'namespace' => '',
            ],
        ],
    ],
];