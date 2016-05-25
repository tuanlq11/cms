<?php

return [
    /** Environment: all | prod | dev | test */
    'all' => [
        /**
         * With true => User must login to access
         * @var boolean
         */
        'is_secure'   => true,
        /**
         * Credentials for access to all action
         * Format:
         *  + [A, B] #User must have the credential A and the credential B
         *  + [[A,B]] #User must have credential the A or the credential B
         */
        'credentials' => [],
        /**
         * Config view for module. Override form CORE\CONFIGS\VIEW
         *  + 'stylesheet' => [],
         *  + 'javascript' => [],
         *  + 'meta' => []
         */
        'view'        => [],
        /**
         * Field configuration
         * column_name => [
         *      label => ''
         * ]
         */
        'field'       => [],
        'cache'       => [
            'configuration' => [
                'enabled'  => false,
                'lifetime' => 3600,
            ],
        ],
        /**
         * Action in controller.
         * Ex: index, edit
         * Detect in controller with suffix "Action"
         */
        'index'       => [
            /**
             * Filter form class. Default use {ModuleName}Filter.php
             * Detect two namespace:
             *  + App\Forms\{ModuleName}Filter
             *  + App\Http\Modules\{ModuleName}\{ModuleName}Filter
             * If not found. System auto generate Form
             */
            'filter_class'    => null,
            /**
             * Filter config only in index
             * Override func: filter{FilterName} function
             * Format:
             *  + ~column_name # Use custom partial instead generate
             *  + column_name => [
             *      'label' => string,
             *      'default_value' => string,
             *      'validation' => '',
             *      'message' => array,
             *      'class' => string, #Css Class
             *      'template' => string #Custom HTML instead HTML created by system auto generate
             *      'params' => [] #ExtraParam for Form field type
             *  ]
             */
            'filter'          => [],
            /** Action to process filtering */
            'filter_action'   => 'filter',
            /**  After filter, redirect to {ACTION} */
            'filter_buttons'  => [
                'wrapper' => ['class' => 'form-group col-lg-12'],
                'items'   => [
                    'Filter' => ['type' => 'submit', 'options' => ['attr' => ['class' => 'btn btn-primary', 'name' => '_btnFilter']]],
                    'Reset'  => ['type' => 'submit', 'options' => ['attr' => ['class' => 'btn btn-default', 'name' => '_btnReset']]],
                ],
            ],
            /**
             * With true => User must login to access
             * @var boolean
             */
            'is_secure'       => true,
            /**
             * Credentials for access to action
             * Format:
             *  + [A, B] #User must have the credential A and the credential B
             *  + [[A,B]] #User must have credential the A or the credential B
             */
            'credentials'     => [],
            /** Model of controller. Default is name of controller */
            'model'           => null,
            /**
             * Override from field config
             * Use only in index action. List column display in index page
             * Default: ['*'] #List all column can display (Model allow)
             * Format:
             *  + column_name #Get value of column in model
             *  + column_name => [
             *      - label => string #Label of column
             *      - class => string #CSS class for this column
             *  + ~column_name #Using custom partial to render. Pass value of column to view if exists
             *  ]
             */
            'list'            => [],
            /**
             * Use only in index action. List column hidden in index page
             */
            'hidden'          => [],
            /**
             * Action for this item of object.
             * Detect in controller with suffix "ObjectAction"
             * Default include delete, show, edit
             */
            'object_action'   => [
                'delete' => ['label' => 'Delete', 'class' => 'btn btn-danger'],
                'show'   => ['label' => 'Show', 'class' => 'btn btn-primary'],
                'edit'   => ['label' => 'Edit', 'class' => 'btn btn-success'],
            ],
            /**
             * Batch action.
             * Detect in controller with suffix "BatchAction"
             */
            'batch_action'    => [
                'delete' => ['label' => 'Delete', 'credential' => [], 'class' => 'btn btn-danger'],
            ],
            /**
             * Action in index page.
             * Detect in controller with suffix "CommonAction"
             */
            'action'          => [
                'add' => ['label' => 'Add', 'class' => 'btn btn-success'],
            ],
            /**
             * Limit record in page
             * Default: 5
             */
            'max_per_page'    => 5,
            /**
             * Order by field
             * Format: ['created_at:asc', 'updated_at:desc]
             */
            'order_by'        => [],
            /**
             * Default view name for render
             * Format:
             *  + name: #Name of view file
             *  + namespace: #Namespace of view file. With null => use module name
             *  + stylesheet => [],
             *  + javascript => [],
             *  + meta => []
             */
            'view'            => [
                'name'      => 'index',
                'namespace' => null,
            ],
            /**
             * Default layout for render
             * Format:
             *  + name: #Name of layout view file
             *  + namespace: #Namespace of layout view file.
             *      - With null => use module name
             *      - With '' => use global layout
             */
            'layout'          => [
                'name'      => 'layout',
                'namespace' => null,
            ],
            /**
             * Add extension iframe(s) to this view
             * Example:
             * [
             *    [
             *      'source'    => 'action:'|'url:',
             *      'param_type' => ['static'|'object']
             *      'params' => [] : If static, push all params to url else use params ~ field of object
             *    ]
             * ]
             */
            'iframes'         => [

            ],
        ],
        /**
         * EditAction
         */
        'edit'        => [
            /**
             * With true => User must login to access
             * @var boolean
             */
            'is_secure'   => true,
            /**
             * Credentials for access to action
             * Format:
             *  + [A, B] #User must have the credential A and the credential B
             *  + [[A,B]] #User must have credential the A or the credential B
             */
            'credentials' => [],
            /** Model of controller. Default is name of controller */
            'model'       => null,
            /**
             * Use only in index action. List column hidden in index page
             */
            'hidden'      => [],
            /**
             * Action in index page.
             * Detect in controller with suffix "CommonAction"
             */
            'action'      => [
                'toList' => ['label' => 'Back to list', 'class' => 'btn btn-primary'],
                'save'   => ['label' => 'Save', 'class' => 'btn btn-success'],
                'delete' => ['label' => 'Delete', 'class' => 'btn btn-danger'],
            ],
            /**
             * Default view name for render
             * Format:
             *  + name: #Name of view file
             *  + namespace: #Namespace of view file. With null => use module name
             *  + stylesheet => [],
             *  + javascript => [],
             *  + meta => []
             */
            'view'        => [
                'name'      => 'edit',
                'namespace' => null,
            ],
            /**
             * Default layout for render
             * Format:
             *  + name: #Name of layout view file
             *  + namespace: #Namespace of layout view file.
             *      - With null => use module name
             *      - With '' => use global layout
             */
            'layout'      => [
                'name'      => 'layout',
                'namespace' => null,
            ],
        ],
        'show'        => [
            /**
             * Sub Action in action show
             */
            'action' => [
                'toList' => [
                    'label' => 'Back To List',
                    'class' => 'btn btn-primary',
                ],
            ],
            /**
             * Default view name for render
             * Format:
             *  + name: #Name of view file
             *  + namespace: #Namespace of view file. With null => use module name
             *  + stylesheet => [],
             *  + javascript => [],
             *  + meta => []
             */
            'view'   => [
                'name'      => 'show',
                'namespace' => null,
            ],
            /**
             * Default layout for render
             * Format:
             *  + name: #Name of layout view file
             *  + namespace: #Namespace of layout view file.
             *      - With null => use module name
             *      - With '' => use global layout
             */
            'layout' => [
                'name'      => 'layout',
                'namespace' => '',
            ],
        ],
        'create'      => [
            'label'  => 'Create New',
            /**
             * Sub Action in action show
             */
            'action' => [
                'toList'        => [
                    'label' => 'Back To List',
                    'class' => 'btn btn-primary',
                ],
                'save'          => ['label' => 'Save', 'class' => 'btn btn-success'],
                'saveAndCreate' => ['label' => 'Save And Create', 'class' => 'btn btn-warning'],
            ],
            /**
             * Default view name for render
             * Format:
             *  + name: #Name of view file
             *  + namespace: #Namespace of view file. With null => use module name
             *  + stylesheet => [],
             *  + javascript => [],
             *  + meta => []
             */
            'view'   => [
                'name'      => 'new',
                'namespace' => null,
            ],
            /**
             * Default layout for render
             * Format:
             *  + name: #Name of layout view file
             *  + namespace: #Namespace of layout view file.
             *      - With null => use module name
             *      - With '' => use global layout
             */
            'layout' => [
                'name'      => 'layout',
                'namespace' => '',
            ],

        ],
    ],
];