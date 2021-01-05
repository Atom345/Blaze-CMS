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

class Posts extends Model {

    public function get($post_id) {

        $data = Database::get('*', 'posts', ['post_id' => $post_id]);

        return $data;
    }

    public function delete($post_id) {
        /* Delete the record from the database */
        Database::$database->query("DELETE FROM `posts` WHERE `post_id` = {$post_id}");

    }

}
