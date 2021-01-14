<?php

namespace Phoenix\Widgets;

class Widgets {

    public static $widgets;

	function __construct(){
        global $widgets;
		self::$widgets = array();
    }
    
    public static function store_widget($array){
        self::$widgets[] = $array;
    }

    public static function get_widgets(){
       return self::$widgets;
    }

}

?>