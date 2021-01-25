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
Feature in Progress!
--------------------------------------------------
*/

namespace Phoenix\Widgets;

class Widgets {

    public static $widget_array;

    /* Load array in order to modify it. */
    public static function fetch_widgets(){
        require CUSTOM_PATH . 'Widgets.php';
        self::$widget_array = $load_widgets;
    }

    /* Modify the included array */
    public static function store_widget($array){
        array_push(self::$widget_array, $array);
    }

    /* Output the modified array */
    public static function load_widgets(){
        var_dump(self::$widget_array);
    }

}

?>