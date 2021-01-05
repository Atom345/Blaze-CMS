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

namespace Phoenix\Models;

use Phoenix\Database\Database;

class User extends Model {

    public function get($user_id) {

        $data = Database::get('*', 'users', ['user_id' => $user_id]);

        return $data;
    }

    public function delete($user_id) {

        /* Delete the record from the database */
        Database::$database->query("DELETE FROM `users` WHERE `user_id` = {$user_id}");

    }

    public function update_last_activity($user_id) {

        Database::update('users', ['last_activity' => \Phoenix\Date::get()], ['user_id' => $user_id]);

    }

}
