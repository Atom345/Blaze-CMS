<?php

namespace Phoenix\Controllers;

use Phoenix\Database\Database;
use Phoenix\Middlewares\Authentication;
use Phoenix\Middlewares\Csrf;

class AdminPlugins extends Controller {

    public function index() {

        Authentication::guard('admin');
        
        $values = [
            'plugin_install' => '',
            'generate_plugin_files' => ''
        ];
        
        
        /* Install a plugin */
        if(isset($_POST['plugin_install_start'])){
            $values['plugin_install'] = $_POST['plugin_install'];
            
            $plugin_to_install = $_POST['plugin_install'];
            $plugin_install_path = PLUGIN_PATH . $plugin_to_install . '/install.php';
            if(file_exists($plugin_install_path)){
                require_once $plugin_install_path;
            }else{
                error("Plugin Install Error", "There was an error attemting to install a plugin. You can find the corrupted plugin file at: " . $plugin_install_path);
            }
            
        }
		
		if(isset($_POST['generate_plugin'])){
		    $plugin_name = $_POST['generate_plugin_name'];
		    $plugin_author = $_POST['generate_plugin_author'];
		    $plugin_desc = $_POST['generate_plugin_desc'];
		    $plugin_version = $_POST['generate_plugin_version'];
		  
		    $plugin_folder = strtolower($plugin_name);
		    
		    $plugin_path = PLUGIN_PATH . $plugin_folder;
		    $plugin_directory = mkdir($plugin_path, 0777, true);
		    
$plugin_string = '
		    
<?php
		        
/*

-------------------------------------------------
Phoenix PHP | Copyright (c) 2020-2025           
-------------------------------------------------
Author: Phoenix PHP Team
File: init.php
-------------------------------------------------
Phoenix PHP and its corresponding files are all 
licensed under the MIT lisence.
-------------------------------------------------

*/

/* Your plugin name */
$plugin_name = "'. $plugin_name .'";

/* The URL where your plugin can be found */
$plugin_url = "";

/* The name of the folder where your plugin is found */
$plugin_folder = "'. $plugin_folder .'"             ; 

/* Plugin Author */
$plugin_author = "'. $plugin_author .'";

/* Plugin Desc */
$plugin_desc = "'. $plugin_desc .'";

/* Plugin Version (e.g v1.0.0) */
$plugin_version = "'. $plugin_version .'";
		    
?>	        
';
		        
$file = $plugin_path . '/init.php';
$fp = fopen($file, 'w');
fwrite($fp, $plugin_string);
fclose($fp);
chmod($file, 0777); 
		    
}
		
		if(isset($_POST))
		 $data = [
		     'values' => $values
        ];

		
		 /* Main View */
        $view = new \Phoenix\Views\View('admin/plugins/plugins', (array) $this);

        $this->addViewContent('content', $view->run($data));

	}
}