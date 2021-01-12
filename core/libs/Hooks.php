<?php 

namespace Phoenix\Hooks;

class Hooks
{

    public static $hooks;

	function __construct(){
        global $hooks;
		self::$hooks = array();
	}

	public static function hook($where, $callback){
		if(isset(self::$hooks[$where])){
			foreach(self::$hooks as $hook_name=>$args){
				if($hook_name == $where){
					call_user_func_array($callback,$args);
				}
			}
		}else{
			return false;
		}
	}

	public static function register_action($where, $args=array()){
        self::$hooks[$where]=$args;
    }
	
	public static function remove_hook($where){
		if(isset(self::$hooks[$where])){
			unset(self::$hooks[$where]);
		}
    }
    
    public static function list_actions(){
        var_dump(self::$hooks);
	}

}

?>