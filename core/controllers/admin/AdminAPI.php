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

class AdminAPI extends Controller
{
    public function index()
    {
        Authentication::guard('admin');    

        $view = new \Phoenix\Views\View('admin/api/api', (array) $this);

        $this->addViewContent('content', $view->run());
    }
}
