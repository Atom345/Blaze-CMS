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
use Phoenix\Language;
use Phoenix\Logger;
use Phoenix\Middlewares\Authentication;
use Phoenix\Routing\Router;
use Phoenix\Hooks\Hooks;

class Login extends Controller {

    public function index() {

        Authentication::guard('guest');

        $method	= (isset($this->params[0])) ? $this->params[0] : false;

        if(isset($_GET['redirect']) && $redirect = $_GET['redirect']) {
            // Nothing for now.
        }

        /* Default values */
        $values = [
            'email' => ''
        ];


        if(!empty($_POST)) {
            /* Clean email and encrypt the password */
            $_POST['email'] = Database::clean_string($_POST['email']);
            $values['email'] = $_POST['email'];

            /* Check for any errors */
            if(empty($_POST['email']) || empty($_POST['password'])) {
                $_SESSION['error'][] = lang('err_login_fields', 1);
            }

            /* Try to get the user from the database */
            $result = Database::$database->query("SELECT `user_id`, `email`, `active`, `password`, `token_code`, `total_logins` FROM `users` WHERE `email` = '{$_POST['email']}'");
            $login_account = $result->num_rows ? $result->fetch_object() : false;

            if(!$login_account) {
                $_SESSION['error'][] = lang('err_invalid_login', 1);
            } else {

                if(!$login_account->active) {
                    $_SESSION['error'][] = lang('err_account_inactive', 1);
                } else

                    if(!password_verify($_POST['password'], $login_account->password)) {
                        Logger::users($login_account->user_id, 'login.wrong_password');

                        $_SESSION['error'][] = lang('err_invalid_login', 1);
                    }

            }

            if(empty($_SESSION['error'])) {
                /* If remember me is checked, log the user with cookies for 30 days else, remember just with a session */
                if(isset($_POST['rememberme'])) {
                    $token_code = $login_account->token_code;

                    /* Generate a new token */
                    if(empty($login_account->token_code)) {
                        $token_code = md5($login_account->email . microtime());

                        Database::update('users', ['token_code' => $token_code], ['user_id' => $login_account->user_id]);
                    }

                    setcookie('email', $login_account->email, time()+60*60*24*30);
                    setcookie('token_code', $token_code, time()+60*60*24*30);

                } else {
                    $_SESSION['user_id'] = $login_account->user_id;
                }

                $user_agent = Database::clean_string($_SERVER['HTTP_USER_AGENT']);
                Database::update('users', [
                    'last_user_agent'   => $user_agent,
                    'total_logins'      => $login_account->total_logins + 1
                ], ['user_id' => $login_account->user_id]);

                Logger::users($login_account->user_id, 'login.success');

                $_SESSION['success'][] = lang('login_success', 1);
                
				
				redirect('dashboard');
				
				
            }
        }

        /* Prepare the View */
        $data = [
            'values' => $values
        ];

        $view = new \Phoenix\Views\View('login/login', (array) $this);

        $this->addViewContent('content', $view->run($data));

    }

}
