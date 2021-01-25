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

namespace Phoenix\Controllers;

use Phoenix\Models\Page;
use Phoenix\Routing\Router;
use Phoenix\Traits\Paramsable;
use Phoenix\Hooks\Hooks;

class Controller {
    use Paramsable;

    public $views = [];

    public function __construct(Array $params = []) {

        $this->add_params($params);

    }

    public function addViewContent($name, $data) {

        $this->views[$name] = $data;

    }

    public function run() {

        //var_dump(Router::$path);

        if(empty(Router::$path)) {

            /* Get the top menu custom pages */
            $pages = (new Page(['database' => $this->database]))->get_pages('top');
            Hooks::register_action('top_pages_loaded');
            
            /* Establish the menu view */
            $menu = new \Phoenix\Views\View('includes/header', (array) $this);
            $this->addViewContent('header', $menu->run([ 'pages' => $pages ]));
            Hooks::register_action('header_loaded');

            /* Get the footer */
            $pages = (new Page(['database' => $this->database]))->get_pages('bottom');
            Hooks::register_action('bottom_pages_loaded');

            /* Establish the footer view */
            $footer = new \Phoenix\Views\View('includes/footer', (array) $this);
            $this->addViewContent('footer', $footer->run([ 'pages' => $pages ]));
            Hooks::register_action('footer_loaded');

            $wrapper = new \Phoenix\Views\View(Router::$controller_settings['container'], (array) $this);
            Hooks::register_action('container_loaded');

        }

        if(Router::$path == 'admin') {
           
             /* Establish the side menu view */
            $sidebar = new \Phoenix\Views\View('admin/includes/admin_sidebar', (array) $this);
            $this->addViewContent('admin_sidebar', $sidebar->run());
            Hooks::register_action('admin_sidebar_loaded');
            
            $wrapper = new \Phoenix\Views\View('admin/container', (array) $this);
            Hooks::register_action('admin_container_loaded');
        }

        if(Router::$path !== 'api'){
            echo $wrapper->run();
        }
    }


}
