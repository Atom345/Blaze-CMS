<?php

/*
-------------------------------------------------
Phoenix CMS | Copyright (c) 2020-2025           
-------------------------------------------------
Author: Phoenix CMS Team
File: Authentication.php
-------------------------------------------------
Phoenix CMS and its corresponding files are all 
licensed under the GPL 3.0 lisence.
-------------------------------------------------
*/

namespace Phoenix\Middlewares;

use Phoenix\Database\Database;

class Authentication {

    public static $is_logged_in = null;
    public static $user_id = null;
    public static $user = null;

    public static function check() {

        /* Already logged in from previous checks */
        if(self::$is_logged_in) {
            return self::$user_id;
        }

        /* Check the cookies first */
        if(isset($_COOKIE['email']) && isset($_COOKIE['token_code']) && strlen($_COOKIE['token_code']) > 0 && $user_id = Database::simple_get('user_id', 'users', ['email' => $_COOKIE['email'], 'token_code' => $_COOKIE['token_code']])) {
            self::$is_logged_in = true;
            self::$user_id = $user_id;

            return true;
        }

        /* Check the Session */
        if(isset($_SESSION['user_id']) && !empty($_SESSION['user_id']) && $user_id = Database::simple_get('user_id', 'users', ['user_id' => $_SESSION['user_id']])) {
            self::$is_logged_in = true;
            self::$user_id = $user_id;


            return true;
        }

        return false;
    }


    public static function is_admin() {

        if(!self::check()) {
            return false;
        }

        return self::$user->type > 0;
    }


    public static function guard($permission = 'user') {

        switch ($permission) {
            case 'guest':

                if(self::check()) {
                    redirect('dashboard');
                }

                break;

            case 'user':

                if(!self::check() || (self::check() && !self::$user->active)) {
                    redirect();
                }

                break;

            case 'admin':

                if(!self::check() || (self::check() && (!self::$user->active || self::$user->type != '1'))) {
                    redirect();
                }

                break;
        }

    }


    public static function logout($page = '') {

        if(self::check()) {
            Database::update('users', ['token_code' => ''], ['user_id' => self::$user_id]);
        }

        session_destroy();
        setcookie('username', '', time()-30);
        setcookie('token_code', '', time()-30);

        redirect($page);
    }
}
