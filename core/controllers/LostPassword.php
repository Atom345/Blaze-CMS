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

class LostPassword extends Controller {

    public function index() {

        Authentication::guard('guest');

        /* Default values */
        $values = [
            'email' => ''
        ];

        if(!empty($_POST)) {
            /* Clean the posted variable */
            $_POST['email'] = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $values['email'] = $_POST['email'];


            /* If there are no errors, resend the activation link */
            if(empty($_SESSION['error'])) {

                if($this_account = Database::get(['user_id', 'email', 'name', 'language'], 'users', ['email' => $_POST['email']])) {
                    /* Define some variables */
                    $lost_password_code = md5($_POST['email'] . microtime());

                    /* Update the current activation email */
                    Database::$database->query("UPDATE `users` SET `lost_password_code` = '{$lost_password_code}' WHERE `user_id` = {$this_account->user_id}");



                    /* Send the email */
                    send_mail($this->settings, $this_account->email, $this_account->username, "Lost Password", url('reset-password/' . $_POST['email'] . '/' . $lost_password_code));

                }

                /* Set success message */
                $_SESSION['success'][] = 'Check your email to reset your password.';
            }
        }

        /* Prepare the View */
        $data = [
            'values'    => $values
        ];

        $view = new \Phoenix\Views\View('lost-password/lost-password', (array) $this);

        $this->addViewContent('content', $view->run($data));

    }

}
