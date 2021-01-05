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
use Phoenix\Middlewares\Authentication;

class AdminPageUpdate extends Controller {

    public function index() {

        Authentication::guard('admin');

        $page_id = (isset($this->params[0])) ? $this->params[0] : false;

        /* Check if user exists */
        if(!$page = Database::get('*', 'pages', ['page_id' => $page_id])) {
            redirect('admin/pages');
        }

        if(!empty($_POST)) {
            /* Filter some the variables */
            $_POST['title'] = Database::clean_string($_POST['title']);
            $_POST['type'] = in_array($_POST['type'], ['internal', 'external']) ? Database::clean_string($_POST['type']) : 'internal';
            $_POST['position'] = in_array($_POST['position'], ['hidden', 'top', 'bottom']) ? $_POST['position'] : 'top';

            switch($_POST['type']) {
                case 'internal':
                    $_POST['url'] = get_slug(Database::clean_string($_POST['url']), '-');
                    break;


                case 'external':
                    $_POST['url'] = Database::clean_string($_POST['url']);
                    break;
            }

            if(!Csrf::check()) {
                $_SESSION['error'][] = $this->language->global->error_message->invalid_csrf_token;
            }

            if(empty($_SESSION['error'])) {
                /* Update the database */
                $stmt = Database::$database->prepare("UPDATE `pages` SET `title` = ?, `url` = ?, `description` = ?, `type` = ?, `position` = ? WHERE `page_id` = ?");
                $stmt->bind_param('ssssss', $_POST['title'], $_POST['url'], $_POST['description'], $_POST['type'], $_POST['position'], $page_id);
                $stmt->execute();
                $stmt->close();

                /* Set a nice success message */
                $_SESSION['success'][] = $this->language->global->success_message->basic;
                redirect('admin/page-update/' . $page_id);

            }
        }

        /* Main View */
        $data = [
            'page' => $page
        ];

        $view = new \Phoenix\Views\View('admin/page-update/page-update', (array) $this);

        $this->addViewContent('content', $view->run($data));

    }

}
