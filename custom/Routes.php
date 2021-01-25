<?php
return $routes = [
    '' => [
    
        'index' => [
            'controller' => 'Index'
        ],

        'login' => [
            'controller' => 'Login',
            
        ],

        'register' => [
            'controller' => 'Register'
        ],

        'logout' => [
            'controller' => 'Logout'
        ],

        'notfound' => [
            'controller' => 'NotFound'

        ],
        
        'newsfeed' => [
            'controller' => 'Newsfeed'

        ],
        
        'account' => [
            'controller' => 'Account'

        ],
        
        'account-details' => [
            'controller' => 'AccountDetails'

        ],
        
         'activate-user' => [
            'controller' => 'ActivateUser'
        ],

        'lost-password' => [
            'controller' => 'LostPassword'
        ],
        
        'resend-activation' => [
            'controller' => 'ResendActivation'
        ],
        
         'page' => [
            'controller' => 'Page'
        ],
        
        'dashboard' => [
            'controller' => 'Dashboard',
        ],
        
    ],

    /* API Routing */
    'api' => [
        'connect' => [
            'controller' => 'APIConnect'
        ],

        'adduser' => [
            'controller' => 'APIAddUser'
        ],
    ],

    /* Admin Panel */
    'admin' => [
        'index' => [
            'controller' => 'AdminIndex'
            
        ],

        'users' => [
            'controller' => 'AdminUsers'
        ],

        'user-create' => [
            'controller' => 'AdminUserCreate'
        ],

        'user-view' => [
            'controller' => 'AdminUserView'
        ],

        'user-update' => [
            'controller' => 'AdminUserUpdate'
        ],


        'pages' => [
            'controller' => 'AdminPages'
        ],

        'page-create' => [
            'controller' => 'AdminPageCreate'
        ],

        'page-update' => [
            'controller' => 'AdminPageUpdate'
        ],

        'settings' => [
            'controller' => 'AdminSettings',
            
        ],
        
        'plugins' => [
            'controller' => 'AdminPlugins',
            
        ],
        
        'theme' => [
            'controller' => 'AdminTheme',
            
        ],
        
        'advanced' => [
            'controller' => 'AdminAdvanced',
            
        ],
        
        'features' => [
            'controller' => 'AdminFeatures',
            
        ],
        
        'changelog' => [
            'controller' => 'AdminChangelog',
            
        ],
        
        'cms' => [
            'controller' => 'AdminCMS',
            
        ],
        
        'store' => [
            'controller' => 'AdminStore',
            
        ],
        
        'api' => [
            'controller' => 'AdminAPI',
            
        ],
    ]
];
?>