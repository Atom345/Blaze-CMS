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

class APIRemoveUser extends Controller {

    public function index() {

        gaurd_api();

        if(endpoint_active('remove_user_api') == true){
            if(isset($_GET['username'])){
 
                $username = filter_var($_GET['username'], FILTER_SANITIZE_STRING);
                
                $stmt = Database::$database->prepare("DELETE FROM `users` WHERE `username` = ?");
                $stmt->bind_param("s", $username);
                $stmt->execute();
                $stmt->close();

                response(200, $username . " has been deleted.", true);
            }else{
                response(400, "Please provide all parameters.", false);
            }
        }
    }
}