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


class Index extends Controller {

    public function index() {

        /* Custom index redirect if set */
        if(!empty($this->settings->index_url)) {
            header('Location: ' . $this->settings->index_url);
            die();
        }
		
        /* Main View */
        $data = [
            
        ];

        $view = new \Phoenix\Views\View('index/index', (array) $this);

        $this->addViewContent('content', $view->run($data));

    }

}
