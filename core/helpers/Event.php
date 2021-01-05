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

class Event {

    public static $callbacks = [];

    public static $content = [];

    public static function bind($event, Callable $function) {
        if(empty(self::$callbacks[$event]) || !is_array(self::$callbacks[$event])){
            self::$callbacks[$event] = [];
        }

        self::$callbacks[$event][] = $function;
    }

    public static function trigger() {
        $args = func_get_args();
        $event = $args[0];
        unset($args[0]);

        if (isset(self::$callbacks[$event])) {
            foreach(self::$callbacks[$event] as $func) {
                call_user_func_array($func, $args);
            }
        }
    }

    public static function add_content($content, $type) {

        if(isset(self::$content[$type])) {
            self::$content[$type][] = $content;
        } else {
            self::$content[$type] = [ $content ];
        }

    }

    public static function get_content($type) {

        $fullContent = '';

        if(isset(self::$content[$type])) {
            foreach (self::$content[$type] as $content) {

                $fullContent .= $content;

            }
        }

        return $fullContent;

    }
}
