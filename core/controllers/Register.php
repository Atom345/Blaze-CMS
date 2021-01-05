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
use Phoenix\Logger;
use Phoenix\Middlewares\Authentication;

class Register extends Controller {

    public function index() {

        /* Check if Registration is enabled first */
        if(!$this->settings->register_is_enabled) {
            redirect();
        }

        Authentication::guard('guest');

        $redirect = 'dashboard';
        if(isset($_GET['redirect']) && $redirect = $_GET['redirect']) {
            $redirect = Database::clean_string($redirect);
        }

        /* Default variables */
        $values = [
            'username' => '',
            'email' => '',
            'password' => ''
        ];

        if(!empty($_POST)) {
            /* Clean some posted variables */
            $_POST['username']		= string_filter_alphanumeric(filter_var($_POST['username'], FILTER_SANITIZE_STRING));
            $_POST['email']		= filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

            /* Default variables */
            $values['username'] = $_POST['username'];
            $values['email'] = $_POST['email'];
            $values['password'] = $_POST['password'];

            /* Define some variables */
            $fields = ['username', 'email' ,'password'];

            /* Check for any errors */
            foreach($_POST as $key=>$value) {
                if(empty($value) && in_array($key, $fields) == true) {
                    $_SESSION['error'][] = lang('err_register_fields', 1);
                    break 1;
                }
            }
            if(strlen($_POST['username']) < 3 || strlen($_POST['username']) > 32) {
                $_SESSION['error'][] = lang('err_register_username_length', 1);
            }
            if(Database::exists('user_id', 'users', ['email' => $_POST['email']])) {
                $_SESSION['error'][] = lang('err_register_email_exists', 1);
            }
            if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                $_SESSION['error'][] = lang('err_register_email_invalid', 1);
            }
            if(strlen(trim($_POST['password'])) < 6) {
                $_SESSION['error'][] = lang('err_register_password_length', 1);
            }
            
            if($this->settings->google_verify == 1){
            if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])){
                $secret = $this->settings->recaptcha_private_key;
                $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
                $responseData = json_decode($verifyResponse);
                if($responseData->success)
                {
                    $is_bot = false;
                }
                else
                {
                    $_SESSION['error'][] = lang('err_register_bot', 1);
                }
                }else{
					 $_SESSION['error'][] = lang('err_register_captacha', 1);
				}
            }
            
            /* If there are no errors continue the registering process */
            if(empty($_SESSION['error'])) {
                /* Define some needed variables */
                $password                   = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $active 	                = 0;
                $email_code                 = md5($_POST['email'] . microtime());
                $last_user_agent            = Database::clean_string($_SERVER['HTTP_USER_AGENT']);
                $total_logins               = $active == '1' ? 1 : 0;
                $ip                         = get_ip();
                $date                       = \Phoenix\Time\Time::get_time();


                /* Add the user to the database */
				$empty = '';
                $stmt = Database::$database->prepare("INSERT INTO `users` (`password`, `email`, `email_activation_code`, `username`, `language`, `active`, `date`, `ip`, `last_user_agent`, `total_logins`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param('ssssssssss', $password, $_POST['email'], $email_code, $_POST['username'], $empty,  $active, $date, $ip, $last_user_agent, $total_logins);
                $stmt->execute();
                $registered_user_id = $stmt->insert_id;
                $stmt->close();

                 /* Log the action */
                Logger::users($registered_user_id, 'register.register');

                /* Send notification to admin if needed */
                if($this->settings->email_notifications->new_user && !empty($this->settings->email_notifications->emails)) {

                    send_mail($this->settings, $this->settings->email_notifications->emails, lang('new_user_to', 1), lang('new_user_subject', 1), lang('new_user_content', 1) .$this->settings->title, 'notify');
                    
                }

               if($this->settings->email_confirmation == 1){
                if($active == '1') {
                    $_SESSION['user_id'] = $registered_user_id;
                    $_SESSION['success'] = lang('register_account_created', 1);

                    Logger::users($registered_user_id, 'login.success');

                    redirect($redirect);
                } else {

                    $link = url('activate-user/' . md5($_POST['email']) . '/' . $email_code . '?redirect=' . $redirect);
                    send_mail($this->settings, $_POST['email'], $_POST['username'], lang('activate_subject', 1), lang('activate_content', 1), 'verify', $link);

                    $_SESSION['success'][] = lang('register_check_email', 1);
                }
			   }else{
				  activate_user($_POST['username']);
				  $_SESSION['success'] = lang('register_account_created_login', 1);
			   }

            }
        }

        /* Main View */
        $data = [
            'values' => $values,
        ];

        $view = new \Phoenix\Views\View('register/register', (array) $this);

        $this->addViewContent('content', $view->run($data));

    }

}
