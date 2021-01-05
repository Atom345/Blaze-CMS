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
use Phoenix\Middlewares\Csrf;
use Phoenix\Models\User;
use Phoenix\Middlewares\Authentication;
use Phoenix\Response;
use Phoenix\Routing\Router;

class AdminPages extends Controller {

    public function index() {

        Authentication::guard('admin');

        $pages_result = Database::$database->query("SELECT * FROM `pages` ORDER BY `page_id` ASC");

        /* Main View */
        $data = [
            'pages_result' => $pages_result
        ];

        $view = new \Phoenix\Views\View('admin/pages/pages', (array) $this);

        $this->addViewContent('content', $view->run($data));

    }

    public function delete() {

        Authentication::guard();

        $page_id = (isset($this->params[0])) ? $this->params[0] : false;

        if(!Csrf::check()) {
            $_SESSION['error'][] = "Invalid token, please login again.";
        }

        if(empty($_SESSION['error'])) {

            /* Delete the page */
            Database::$database->query("DELETE FROM `pages` WHERE `page_id` = {$page_id}");

            redirect('admin/pages');

        }

        die();
    }

}
