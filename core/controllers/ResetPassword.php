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

class ResetPassword extends Controller {

    public function index() {

        Authentication::guard('guest');

        $email = (isset($this->params[0])) ? $this->params[0] : false;
        $lost_password_code = (isset($this->params[1])) ? $this->params[1] : false;

        if(!$email || !$lost_password_code) redirect();

        /* Check if the lost password code is correct */
        $user_id = Database::simple_get('user_id', 'users', ['email' => $email, 'lost_password_code' => $lost_password_code]);

        if($user_id < 1 || strlen($lost_password_code) < 1) redirect();

        if(!empty($_POST)) {
            /* Check for any errors */
            if(strlen(trim($_POST['new_password'])) < 6) {
                $_SESSION['error'][] = "Your password must be more than 6 characters.";
            }
            if($_POST['new_password'] !== $_POST['repeat_password']) {
                $_SESSION['error'][] = "Your passwords do not match.";
            }

            if(empty($_SESSION['error'])) {
                /* Encrypt the new password */
                $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

                /* Update the password & empty the reset code from the database */
                $stmt = Database::$database->prepare("UPDATE `users` SET `password` = ?, `lost_password_code` = 0  WHERE `user_id` = ?");
                $stmt->bind_param('ss', $new_password, $user_id);
                $stmt->execute();
                $stmt->close();

                /* Store success message */
                $_SESSION['success'][] = "Your password has been updated!";

                redirect('login');
            }
        }

        /* Prepare the View */
        $data = [
            'values' => [
                'email' => $email
            ]
        ];

        $view = new \Phoenix\Views\View('reset-password/reset-password', (array) $this);

        $this->addViewContent('content', $view->run($data));

    }

}
