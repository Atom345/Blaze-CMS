<?php

function generate($name, $author, $desc, $version, $type, $installer = false){

	switch($type){
		case 'theme':
			
			/* Set base folders */
			$base_folders = array(
				"layout",
				"assets"
			);
			
			/* Set the layout folders */
			$layout_folders = array(
				"account",
				"account-details",
				"admin",
				"dashboard",
				"emails",
				"index",
				"login",
				"lost-password",
				"newsfeed",
				"notfound",
				"page",
				"includes",
				"register",
				"resend-activation",
				"reset-password"
				
			);
			
			/* Set the structure */
			$structure = BASIC_THEME_PATH . $name;

			/* Create main theme folder */
			if (!mkdir($structure, 0777, true)) {
    			error('Generation Error', 'Could not generate theme files. Failed to create starting folder.');
			}
			
			/* Create theme init file */
				$content = '<?php
/*
Theme Name: "'. $name .'"
Theme URI: 
Theme Folder: phoenix
Author: "'. $author .'"
Author URI: 
Description: "'. $desc .'".
Version: "'. $version .'"
*/

$styles = array(); //Add your styleheets here.

?>
';
			$init_create = fopen($structure . '/init.php',"wb");
			fwrite($init_create,$content);
			fclose($init_create);
			
			/* Create base folders */
			foreach($base_folders as $base_folder){
				if (!file_exists($base_folder)) {
   			 		mkdir($structure . '/' . $base_folder, 0777, true);
				}
			}
			
			/* Create layout folders */
			foreach($layout_folders as $layout_folder){
				if (!file_exists($layout_folder)) {
   			 		mkdir($structure . '/layout/' . $layout_folder, 0777, true);
				}
			}
			
			
			
			
			
		break;
	}
	
}

?>