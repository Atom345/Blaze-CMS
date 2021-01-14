<?php

/*
-------------------------------------------------
Phoenix CMS | Copyright (c) 2020-2025           
-------------------------------------------------
Author: Phoenix CMS Team
File: init.php
-------------------------------------------------
Phoenix CMS and its corresponding files are all 
licensed under the GPL 3.0 lisence.
-------------------------------------------------
*/

/* Set up file structure paths */
define('ROOT_PATH', realpath(__DIR__ . '/..') . '/');
define('APP_PATH', 'core/');
define('CUSTOM_PATH',  APP_PATH . '../custom/');
define('PLUGIN_PATH', CUSTOM_PATH . 'plugins/');
define('UPLOADS_PATH', CUSTOM_PATH . 'media/');
define('UPLOADS_URL_PATH', CUSTOM_PATH . 'media/');
define('LANGUAGE_PATH', CUSTOM_PATH . 'languages/');
define('CONTROLLER_PATH', APP_PATH . 'controllers/');
define('HELPER_PATH', APP_PATH . 'helpers/');
define('REQUEST_PATH', APP_PATH . 'request/');

/* Get the config files */
if(file_exists(APP_PATH . 'config/config.php')){
require_once APP_PATH . 'config/config.php';
}else{
	header("Location: ./install/index.php");	
}

/* Set cookie to root site path */
define('COOKIE_PATH', preg_replace('|https?://[^/]+|i', '', SITE_URL));
session_set_cookie_params(null, COOKIE_PATH);
session_start();

/* Erorr Reporting */
if(DEV_MODE == true){
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    ini_set("log_errors", 1);
    ini_set("error_log",ROOT_PATH . "errors.txt");
}

/* Fetch these files in order */
require_once APP_PATH . 'libs/App.php';
require_once APP_PATH . 'libs/Hooks.php';
require_once APP_PATH . 'libs/Widgets.php';
require_once APP_PATH . 'libs/Parameters.php';
require_once APP_PATH . 'libs/Time.php';
require_once APP_PATH . 'libs/Controller.php';
require_once APP_PATH . 'libs/Database.php';
require_once APP_PATH . 'libs/Functions.php';
require_once APP_PATH . 'libs/Logger.php';
require_once APP_PATH . 'libs/Language.php';
require_once APP_PATH . 'libs/Model.php';
require_once APP_PATH . 'libs/Router.php';
require_once APP_PATH . 'libs/Title.php';
require_once APP_PATH . 'libs/View.php';

foreach (glob(APP_PATH . "auth/*.php") as $auth)
{
    require_once $auth;
}

foreach (glob(APP_PATH . "models/*.php") as $models)
{
    require_once $models;
}

foreach (glob(APP_PATH . "helpers/*.php") as $helpers)
{
    require_once $helpers;
}

/* Get third-party libraries */
if(is_dir('vendor')){
    require_once 'vendor/autoload.php';
    define("IS_VENDOR", true);
}else{
    define("IS_VENDOR", false);
}
