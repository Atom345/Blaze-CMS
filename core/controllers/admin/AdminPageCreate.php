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

class AdminPageCreate extends Controller {

    public function index() {

        Authentication::guard('admin');

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

            $required_fields = ['title', 'url'];

            /* Check for the required fields */
            foreach($_POST as $key => $value) {
                if(empty($value) && in_array($key, $required_fields)) {
                    $_SESSION['error'][] = "Please fill in all fields.";
                    break 1;
                }
            }

            if(!Csrf::check()) {
                $_SESSION['error'][] = "Invalid Token, please login again.";
            }

            /* If there are no errors continue the updating process */
            if(empty($_SESSION['error'])) {
                $stmt = Database::$database->prepare("INSERT INTO `pages` (`title`, `url`, `description`, `type`, `position`) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param('sssss', $_POST['title'], $_POST['url'], $_POST['description'], $_POST['type'], $_POST['position']);
                $stmt->execute();
                $stmt->close();


                $_SESSION['success'][] = "Page Created!";
                redirect('admin/pages');
            }

        }

        /* Main View */
        $view = new \Phoenix\Views\View('admin/page-create/page-create', (array) $this);

        $this->addViewContent('content', $view->run());

    }

}
