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

use Phoenix\Database\Database;
use Phoenix\Title;

class Page extends Controller {

    public function index() {

        $url = isset($this->params[0]) ? Database::clean_string($this->params[0]) : false;

        $page = $url ? Database::get('*', 'pages', ['url' => $url]) : false;

        if(!$page) {
            redirect();
        }
        
         Database::$database->query("UPDATE `pages` SET `total_veiws` = `total_veiws`+1 WHERE `page_id` = {$page->page_id}");

        $data = [
            'page'  => $page
        ];

        $view = new \Phoenix\Views\View('page/page', (array) $this);

        $this->addViewContent('content', $view->run($data));

        Title::set($page->title);

    }

}
