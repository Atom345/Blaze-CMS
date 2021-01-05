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

namespace Phoenix\Traits;

trait Paramsable {

    /* Function used by the base model, controller and view */
    public function add_params(Array $params = []) {

        /* Make the params available to the Controller */
        foreach($params as $key => $value) {
            $this->{$key} = $value;
        }

    }

}
