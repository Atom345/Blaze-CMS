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

namespace Phoenix;

class Response {

    public static function json($message, $status = 'success', $details = []) {
        if(!is_array($message) && $message) $message = [$message];

        echo json_encode(
            [
                'message' 	=> $message,
                'status' 	=> $status,
                'details'	=> $details,
            ]
        );

        die();
    }

    public static function simple_json($response) {

        echo json_encode($response);

        die();

    }

}
