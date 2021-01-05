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

class AdminUsers extends Controller {

    public function index() {

        Authentication::guard('admin');

        /* Main View */
        $view = new \Phoenix\Views\View('admin/users/users', (array) $this);

        $this->addViewContent('content', $view->run());
		

    }

    public function delete() {

        Authentication::guard();

        $user_id = (isset($this->params[0])) ? $this->params[0] : false;
		die($user_id);
        if(!Csrf::check()) {
            $_SESSION['error'][] = "Invalid Token";
        }

        if($user_id == $this->user->user_id) {
            $_SESSION['error'][] = "You cannot delete yourself.";
        }

        if(empty($_SESSION['error'])) {

            /* Delete the user */
            (new User(['settings' => $this->settings]))->delete($user_id);
            redirect('admin/users');

        }

        die();
    }

}
