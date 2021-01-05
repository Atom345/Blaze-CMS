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

class AdminUserUpdate extends Controller {

    public function index() {

        Authentication::guard('admin');

        $user_id = (isset($this->params[0])) ? $this->params[0] : false;

        /* Check if user exists */
        if(!$user = Database::get('*', 'users', ['user_id' => $user_id])) {
            $_SESSION['error'][] = "Invalid account. This user is not found in the database.";
            redirect('admin/users');
        }

       

        if(!empty($_POST)) {
            /* Filter some the variables */
            $_POST['name']		= filter_var($_POST['name'], FILTER_SANITIZE_STRING);
            $_POST['status']	= (int) $_POST['status'];
            $_POST['type']	    = (int) $_POST['type'];
    

            /* Check for any errors */
            if(!Csrf::check()) {
                $_SESSION['error'][] = "Invalid token, please login again.";
            }

            if(strlen($_POST['username']) < 3 || strlen($_POST['name']) > 32) {
                $_SESSION['error'][] = "Username must be between 3 and 32 characters.";
            }
            if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) == false) {
                $_SESSION['error'][] = "Invalid Email.";
            }

            if(Database::exists('user_id', 'users', ['email' => $_POST['email']]) && $_POST['email'] !== Database::simple_get('email', 'users', ['user_id' => $user->user_id])) {
                $_SESSION['error'][] = "The provided email already exists.";
            }

            if(!empty($_POST['new_password']) && !empty($_POST['repeat_password'])) {
                if(strlen(trim($_POST['new_password'])) < 6) {
                    $_SESSION['error'][] = "Password must be more than six characters.";
                }
                if($_POST['new_password'] !== $_POST['repeat_password']) {
                    $_SESSION['error'][] = "The provided passwords do not match.";
                }
            }


            if(empty($_SESSION['error'])) {

                /* Update the basic user settings */
                $stmt = Database::$database->prepare("
                    UPDATE
                        `users`
                    SET
                        `username` = ?,
                        `email` = ?,
                        `active` = ?,
                        `type` = ?
                    WHERE
                        `user_id` = ?
                ");
                $stmt->bind_param(
                    'sssss',
                    $_POST['username'],
                    $_POST['email'],
                    $_POST['status'],
                    $_POST['type'],
                    $user->user_id
                );
                $stmt->execute();
                $stmt->close();

                /* Update the password if set */
                if(!empty($_POST['new_password']) && !empty($_POST['repeat_password'])) {
                    $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

                    $stmt = Database::$database->prepare("UPDATE `users` SET `password` = ?  WHERE `user_id` = {$user->user_id}");
                    $stmt->bind_param('s', $new_password);
                    $stmt->execute();
                    $stmt->close();
                }

                $_SESSION['success'][] = "The user has been updated.";

                redirect('admin/user-update/' . $user->user_id);
            }

        }


        /* Main View */
        $data = [
            'user'              => $user,
        ];

        $view = new \Phoenix\Views\View('admin/user-update/user-update', (array) $this);

        $this->addViewContent('content', $view->run($data));

    }

}
