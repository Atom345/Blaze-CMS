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

use Phoenix\Middlewares\Authentication;
use Phoenix\Hooks\Hooks;

class Logout extends Controller {

    public function index() {

        Hooks::register_action('user_logout');

        Authentication::logout();

    }

}
