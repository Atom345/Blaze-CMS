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

namespace Phoenix\Models;

use Phoenix\Traits\Paramsable;
use Phoenix\Hooks\Hooks;

class Model {
    use Paramsable;

    public $model;

    public function __construct(Array $params = []) {

        $this->add_params($params);

    }

}
