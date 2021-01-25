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

class APIAddUser extends Controller {

    public function index() {

        gaurd_api();

        if(endpoint_active('add_user_api') == true){
            if(isset($_GET['username'], $_GET['email'], $_GET['password'])){
                $username = filter_var($_GET['username'], FILTER_SANITIZE_STRING);
                $email = filter_var($_GET['email'], FILTER_SANITIZE_EMAIL);

                if(base64_encode(base64_decode($_GET['password'], true)) === $_GET['password']){

                    $password = password_hash(base64_decode($_GET['password'], true), PASSWORD_DEFAULT);

                    $empty = '';
                    $one = '1';
                    $ip = get_ip();
                    $date = \Phoenix\Time\Time::get_time();
                    
                    if(Database::exists('user_id', 'users', ['email' => $email])) {
                        response(500, "Unable to add user, email already exists.", false);
                        exit();
                    }
    
                    $stmt = Database::$database->prepare("INSERT INTO `users` (`email`, `password`, `username`, `token_code`, `email_activation_code`, `lost_password_code`, `facebook_id`, `type`, `active`, `language`, `date`, `ip`, `last_activity`, `last_user_agent`, `total_logins`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param('sssssssssssssss', $email, $password, $username, $empty, $empty, $empty, $empty, $empty, $one, $empty, $date, $ip, $empty, $empty, $one);
                    $stmt->execute();
                    $stmt->close();
    
                    response(200, "User: " . $username . " has been created.", true);

                }else{
                    response(500, "Invalid starting password encoding.", false);
                    exit();
                }

            }else{
                response(400, "Please provide all parameters.", false);
            }
        }else{
            response(401, "Endpoint not found.", false);
        }

    }

}
