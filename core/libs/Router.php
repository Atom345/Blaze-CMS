<?php

/*
-------------------------------------------------
Phoenix CMS | Copyright (c) 2020-2025           
-------------------------------------------------
Author: Phoenix CMS Team
-------------------------------------------------
Phoenix CMS and its corresponding files are all 
licensed under the GPL 3.0 lisence.
--------------------------------------------------
*/

namespace Phoenix\Routing;

use Phoenix\Hooks\Hooks;

class Router {
    public static $params = [];
    public static $path = '';
    public static $controller_key = 'index';
    public static $controller = 'Index';
    public static $controller_settings = [
        'container'               => 'container'
    ];
    public static $method = 'index';

    public static $routes = [
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



    public static function parse_url() {

        $params = self::$params;

        if(isset($_GET['phoenix'])) {
            $params = explode('/', filter_var(rtrim($_GET['phoenix'], '/'), FILTER_SANITIZE_URL));
        }

        self::$params = $params;

        return $params;

    }

    public static function get_params() {

        return self::$params = array_values(self::$params);
    }

    public static function parse_controller() {

        /* Check for potential other paths than the default one (admin panel) */
        if(!empty(self::$params[0])) {

            if (in_array(self::$params[0], ['admin'])) {
                self::$path = self::$params[0];

                unset(self::$params[0]);

                self::$params = array_values(self::$params);
            }

        }

        if(!empty(self::$params[0])) {

            if(array_key_exists(self::$params[0], self::$routes[self::$path]) && file_exists(APP_PATH . 'controllers/' . (self::$path != '' ? self::$path . '/' : null) . self::$routes[self::$path][self::$params[0]]['controller'] . '.php')) {

                self::$controller_key = self::$params[0];

                unset(self::$params[0]);

            } else {

                /* Not found controller */
                self::$path = '';
                self::$controller_key = 'notfound';

            }

        }

        /* Save the current controller */
        self::$controller = self::$routes[self::$path][self::$controller_key]['controller'];

        /* Make sure we also save the controller specific settings */
        if(isset(self::$routes[self::$path][self::$controller_key]['settings'])) {
            self::$controller_settings = array_merge(self::$controller_settings, self::$routes[self::$path][self::$controller_key]['settings']);
        }

        return self::$controller;

    }

    public static function get_controller($controller_name, $path = '') {

        require_once APP_PATH . '/controllers/' . ($path != '' ? $path . '/' : null) . $controller_name . '.php';

        /* Create a new instance of the controller */
        $class = 'Phoenix\\Controllers\\' . $controller_name;

        /* Instantiate the controller class */
        $controller = new $class;

        return $controller;
    }

	public static function which_controller(){
		return self::$controller_key;	
	}
	
    public static function parse_method($controller) {

        $method = self::$method;

        /* Make sure to check the class method if set in the url */
        if(isset(self::get_params()[0]) && method_exists($controller, self::get_params()[0])) {

            /* Make sure the method is not private */
            $reflection = new \ReflectionMethod($controller, self::get_params()[0]);
            if($reflection->isPublic()) {
                $method = self::get_params()[0];

                unset(self::$params[0]);
            }

        }

        return $method;

    }

}
