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
use Phoenix\Middlewares\Authentication;
use Phoenix\Middlewares\Csrf;
use Phoenix\Models\User;

class Account extends Controller {

    public function index() {

        Authentication::guard();

        if(!empty($_POST)) {

            /* Clean some posted variables */
            $_POST['email']		= filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $_POST['name']		= string_filter_alphanumeric(filter_var($_POST['name'], FILTER_SANITIZE_STRING));

            /* Check for any errors */
            if(!Csrf::check()) {
                $_SESSION['error'][] = "Please refresh your page and login again.";
            }
            if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) == false) {
                $_SESSION['error'][] = "The email provided is invalid.";
            }
            if(Database::exists('user_id', 'users', ['email' => $_POST['email']]) && $_POST['email'] !== $this->user->email) {
                $_SESSION['error'][] = "The provided email already exists.";
            }

            if(strlen($_POST['name']) < 3 || strlen($_POST['name'] > 32)) {
                $_SESSION['error'][] = "Your username must not be less that 3 characters or more than 32.";
            }

            if(!empty($_POST['old_password']) && !empty($_POST['new_password'])) {
                if(!password_verify($_POST['old_password'], $this->user->password)) {
                    $_SESSION['error'][] = "Your current password is invalid.";
                }
                if(strlen(trim($_POST['new_password'])) < 6) {
                    $_SESSION['error'][] = "Your password must be more than 6 characters.";
                }
                if($_POST['new_password'] !== $_POST['repeat_password']) {
                    $_SESSION['error'][] = "Your passwords do not match.";
                }
            }

            if(empty($_SESSION['error'])) {

                /* Prepare the statement and execute query */
                $stmt = Database::$database->prepare("UPDATE `users` SET `email` = ?, `username` = ? WHERE `user_id` = {$this->user->user_id}");
                $stmt->bind_param('ss', $_POST['email'], $_POST['name']);
                $stmt->execute();
                $stmt->close();

                $_SESSION['success'][] = "Account Updated!";

                if(!empty($_POST['old_password']) && !empty($_POST['new_password'])) {
                    $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

                    Database::update('users', ['password' => $new_password], ['user_id' => $this->user->user_id]);

                    /* Set a success message and log out the user */
                    Authentication::logout();
                }

                redirect('account');
            }

        }

        /* Prepare the View */
        $view = new \Phoenix\Views\View('account/account', (array) $this);

        $this->addViewContent('content', $view->run());

    }

    public function delete() {

        Authentication::guard();

        if(!Csrf::check()) {
            $_SESSION['error'][] = "Please refresh your page and login again.";
        }

        if(empty($_SESSION['error'])) {

            /* Delete the user */
            (new User(['settings' => $this->settings]))->delete($this->user->user_id);
            Authentication::logout();

        }

    }


    }





