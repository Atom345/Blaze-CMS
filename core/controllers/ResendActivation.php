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

use Phoenix\Captcha;
use Phoenix\Database\Database;
use Phoenix\Language;
use Phoenix\Middlewares\Authentication;

class ResendActivation extends Controller {

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
                $this_account = Database::get(['user_id', 'active', 'name', 'email', 'language'], 'users', ['email' => $_POST['email']]);

                if($this_account && !(bool) $this_account->active) {
                    /* Generate new email code */
                    $email_code = md5($_POST['email'] . microtime());

                    /* Update the current activation email */
                    Database::$database->query("UPDATE `users` SET `email_activation_code` = '{$email_code}' WHERE `user_id` = {$this_account->user_id}");

                    /* Get the language for the user */
                    $language = Language::get($this_account->language);

                    /* Prepare the email */
                    $email_template = get_email_template(
                        [
                            '{{NAME}}' => $this_account->name,
                        ],
 
                        [
                            '{{ACTIVATION_LINK}}' => url('activate-user/' . md5($this_account->email) . '/' . $email_code),
                            '{{NAME}}' => $this_account->name,
                        ],
                        "Activate your account"
                    );

                    /* Send the email */
                    send_mail($this->settings, $_POST['email'], $email_template->subject, $email_template->body);

                }

                /* Store success message */
                $_SESSION['success'][] = "Check your email to activate your account.";
            }
        }

        /* Prepare the View */
        $data = [
            'values'    => $values
        ];

        $view = new \Phoenix\Views\View('resend-activation/resend-activation', (array) $this);

        $this->addViewContent('content', $view->run($data));

    }

}
