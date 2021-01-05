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
use Phoenix\Middlewares\Csrf;
use Phoenix\Models\User;
use Phoenix\Routing\Router;

class AccountDetails extends Controller {

    public function index() {

        Authentication::guard();

        /* Get last X logs */
        $logs_result = Database::$database->query("SELECT * FROM `users_logs` WHERE `user_id` = {$this->user->user_id} ORDER BY `id` DESC LIMIT 15");

        /* Prepare the View */
        $data = [
            'logs_result'        => $logs_result
        ];

        $view = new \Phoenix\Views\View('account-details/account-details', (array) $this);

        $this->addViewContent('content', $view->run($data));

    }


}
