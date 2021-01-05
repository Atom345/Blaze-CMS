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

class AdminUserView extends Controller {

    public function index() {

        Authentication::guard('admin');

        $user_id = (isset($this->params[0])) ? $this->params[0] : false;

        /* Check if user exists */
        if(!$user = Database::get('*', 'users', ['user_id' => $user_id])) {
            $_SESSION['error'][] = "Invalid user account.";
            redirect('admin/users');
        }

        /* Get last X logs */
        $user_logs_result = Database::$database->query("SELECT * FROM `users_logs` WHERE `user_id` = {$user_id} ORDER BY `id` DESC LIMIT 15");

        /* Main View */
        $data = [
            'user'                  => $user,
            'user_logs_result'      => $user_logs_result
        ];

        $view = new \Phoenix\Views\View('admin/user-view/user-view', (array) $this);

        $this->addViewContent('content', $view->run($data));

    }

}
