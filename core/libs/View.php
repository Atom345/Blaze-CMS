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

namespace Phoenix\Views;

use Phoenix\Traits\Paramsable;
use Phoenix\Hooks\Hooks;

class View {
    use Paramsable;

    public $view;
    public $view_path;


    public function __construct($view, Array $params = []) {

        $this->view = $view;
        $this->view_path = LOADED_THEME . 'layout/' . $view . '.phtml';
        $this->add_params($params);

    }

    public function run($data = []) {

        $data = (object) $data;

        ob_start();

        require $this->view_path;

        return ob_get_clean();
    }

}
