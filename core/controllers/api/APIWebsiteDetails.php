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

class APIWebsiteDetails extends Controller {

    public function index() {

        //gaurd_api();

        if(endpoint_active('website_details_api') == true){
           $payload = array(
               'title' => $this->settings->title,
               'desc' => $this->settings->desc
            );

           response(200, "Website Data has been fetched.", json_encode($payload, true));
        }else{
            response(200, "Endpoint not found.", false);
        }
    }
}