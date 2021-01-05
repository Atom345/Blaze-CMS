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

class Page extends Model {

    public function get_pages($position) {

        $result = Database::$database->query("SELECT `url`, `title`, `type` FROM `pages` WHERE `position` = '{$position}'");
        $data = [];

        while($row = $result->fetch_object()) {

            if($row->type == 'internal') {

                $row->target = '_self';
                $row->url = url('page/' . $row->url);

            } else {

                $row->target = '_blank';

            }

            $data[] = $row;
        }

        return $data;
    }

}
