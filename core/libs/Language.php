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

namespace Phoenix\Language;

use Phoenix\Database\Database;
use Phoenix\Date\Date;
use Phoenix\Hooks\Hooks;

class Language{

	function get_language(){

		$lang = get_settings_from_key("default_language");	

		$lang_path = APP_PATH . 'languages/' . $lang . '.php';
		
		if(file_exists($lang_path)){
			require_once $lang_path;
			Hooks::register_action('lang_loaded');
		}else{
			error("Language Error", "The current website language file is either missing or corrupted. Please contact the Phoenix PHP team.<br>" . $lang_path);
			exit();
		}

	}
	
}