<?php

/*
-------------------------------------------------
Phoenix CMS | Copyright (c) 2020-2025           
-------------------------------------------------
Author: Phoenix CMS Team
-------------------------------------------------
Phoenix CMS and its corresponding files are all 
licensed under the GPL 3.0 lisence.
-------------------------------------------------
*/

namespace Phoenix\Controllers;

class NotFound extends Controller {

    public function index() {

        header('HTTP/1.0 404 Not Found');

        $view = new \Phoenix\Views\View('notfound/notfound', (array) $this);

        $this->addViewContent('content', $view->run());

    }

}
