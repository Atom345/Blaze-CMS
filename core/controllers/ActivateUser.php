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
use Phoenix\Logger;
use Phoenix\Middlewares\Authentication;

class ActivateUser extends Controller {

    public function index() {

        Authentication::guard('guest');

        $md5email = (isset($this->params[0])) ? $this->params[0] : false;
        $email_activation_code = (isset($this->params[1])) ? $this->params[1] : false;

        $redirect = 'dashboard';
        if(isset($_GET['redirect']) && $redirect = $_GET['redirect']) {
            $redirect = Database::clean_string($redirect);
        }

        if(!$md5email || !$email_activation_code) redirect();

        /* Check if the activation code is correct */
        if(!$profile_account = Database::get(['user_id', 'email'], 'users', ['email_activation_code' => $email_activation_code])) redirect();

        if(md5($profile_account->email) != $md5email) redirect();

        $user_agent = Database::clean_string($_SERVER['HTTP_USER_AGENT']);

        /* Activate the account and reset the email_activation_code */
        $stmt = Database::$database->prepare("UPDATE `users` SET `active` = 1, `email_activation_code` = '', `last_user_agent` = ?, `total_logins` = `total_logins` + 1 WHERE `user_id` = ?");
        $stmt->bind_param('ss', $user_agent, $profile_account->user_id);
        $stmt->execute();
        $stmt->close();

        Logger::users($profile_account->user_id, 'activate.success');

        /* Login and set a successful message */
        $_SESSION['user_id'] = $profile_account->user_id;
        $_SESSION['success'][] = lang('activate_success', 1);

        Logger::users($profile_account->user_id, 'login.success');

        redirect($redirect);

    }

}
