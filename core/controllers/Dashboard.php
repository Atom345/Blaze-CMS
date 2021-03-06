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

use Phoenix\Database\Database;
use Phoenix\Middlewares\Authentication;
use Phoenix\Routing\Router;

class Dashboard extends Controller {

    public function index() {

        Authentication::guard();

        /* Prepare the View */
        $data = [
            
        ];

        $view = new \Phoenix\Views\View('dashboard/dashboard', (array) $this);

        $this->addViewContent('content', $view->run($data));

    }

}
