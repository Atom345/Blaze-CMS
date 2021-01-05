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

namespace Phoenix;

use Phoenix\Database\Database;
use Phoenix\Hooks\Hooks;

class Logger {

    public static function users($user_id, $type, $public = 1) {

        $user_agent = Database::clean_string($_SERVER['HTTP_USER_AGENT']);
        $ip         = get_ip();

        Database::insert('users_logs', [
            'user_id'   => $user_id,
            'type'      => $type,
            'ip'        => $ip,
            'public'    => $public
        ]);
    }

}
