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

namespace Phoenix;

use Phoenix\Middlewares\Authentication;
use Phoenix\Middlewares\Csrf;
use Phoenix\Models\User;
use \Phoenix\Routing\Router;
use \Phoenix\Models\Settings;
use \Phoenix\Hooks\Hooks;

class App {

    protected $database;

    public function __construct() {

        /* Parse the URL parameters */
        Router::parse_url();

        /* Handle the controller */
        Router::parse_controller();

        /* Create a new instance of the controller */
        $controller = Router::get_controller(Router::$controller, Router::$path);

        /* Process the method and get it */
        $method = Router::parse_method($controller);

        /* Get the remaining params */
        $params = Router::get_params();

        /* Connect to the database */
        $this->database = Database\Database::initialize();
		
        /* Start the language system */
        $language = new Language\Language;
		$language->get_language();
		
        /* Get the website settings */
        $settings = (new Settings())->get();

        /* Initiate the Title system */
        Title::initialize($settings->title);
		
        /* Set the date and timezone */
		$date_time = new Time\Time;
        $global_timezone = date_default_timezone_set($settings->time_zone);

        /* Fetch and load set theme, resources, and configs */
        $theme = $settings->theme;
        define('BASIC_THEME_PATH', CUSTOM_PATH . 'themes/');
        define('LOADED_THEME', BASIC_THEME_PATH . $theme . '/');
        define('ASSETS_PATH', LOADED_THEME . 'assets/');
        define('ASSETS_URL_PATH', LOADED_THEME . 'assets/');
        define('STYLES_CONFIG', LOADED_THEME. 'layout/styles.phtml');
		define('THEME_CONFIG', LOADED_THEME . 'init.php');
        if(file_exists(STYLES_CONFIG)){
            require_once STYLES_CONFIG;
        }else{
			error("Corrupted Theme", "The current theme does not have the required `styles.phtml` file. Please contact the theme author or the Phoenix PHP team.");
		}
        
		$file_handler = new FileHandler; 
		
        /* Check for a potential logged in account and do some extra checks */
        if(Authentication::check()) {

            $user = (new User())->get(Authentication::$user_id);

            if(!$user) {
                Authentication::logout();
            }

            $user_id = Authentication::$user_id;

            /* Update last activity */
            if((new \DateTime())->modify('+5 minutes') < (new \DateTime($user->last_activity))) {
                (new User())->update_last_activity(Authentication::$user_id);
            }

            /* Store all the details of the user in the Authentication static class as well */
            Authentication::$user = $user;
        }
		
        /* Set a CSRF Token */
        Csrf::set('token');
        Csrf::set('global_token');

        /* Add main vars inside of the controller */
        $controller->add_params([
            'database'  => $this->database,
            'params'    => $params,
            'settings'  => $settings,

            /* Potential logged in user */
            'user'      => Authentication::$user
        ]);

        /* Call the controller method */
        call_user_func_array([ $controller, $method ], []);

        /* Render and output everything */
        $controller->run();

        /* Get all plugins */
        foreach (get_plugins() as $plugin_path) {
            if (file_exists($plugin_path . '/init.php')) {
            require_once($plugin_path . '/init.php');
            $plugin = str_replace(PLUGIN_PATH, '', $plugin_path);
            }
        }

    }

}
