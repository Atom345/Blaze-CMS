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

namespace Phoenix\Controllers;

use Phoenix\Database\Database;
use Phoenix\Middlewares\Authentication;
use Phoenix\Widgets\Widgets;

function fetch_widgets(){
	$widgets_include = require CUSTOM_PATH . 'Widgets.php';
    return $widgets_include;
}

class AdminIndex extends Controller
{
    public function index()
    {
        Authentication::guard('admin');

        $values = [
            'update' => 'update',
        ];
		
		/* Check for installer */
		if(is_dir(ROOT_PATH . 'install')){
			$installer = true;
		}else{
			$installer = false;
		}
		
		/* Check if client is on localhost */
		$is_localhost = is_localhost();
		
		/* Check for API key */
		if(!isset($this->settings->api_key)){
			$active_api_key = false;
		}else{
			$active_api_key = true;	
		}
		
		if(isset($_POST['generate_theme'])){
			$theme_name = $_POST['theme_name'];	
			$theme_author = $_POST['theme_author'];	
			$theme_desc = $_POST['theme_desc'];	
			$theme_version = $_POST['theme_version'];
			
			$generate_theme = generate($theme_name, $theme_author, $theme_desc, $theme_version, 'theme');
			if(!$generate_theme){
				$_SESSION['error'][] = "Failed to generate theme.";	
			}else{
				$_SESSION['success'][] = "Theme created.";	
			}
		}
		
		 /* Fetch the current version of Phoenix from the database. */
		 if($active_api_key == true){
            $phoenix_version = $this->settings->version;
            $update_package = 0;
            $rc = @fsockopen("www.phoenix.ltda", 80, $errno, $errstr, 1);
            if (is_resource($rc)) {
                
                $latest_version = file_get_contents("https://phoenix.ltda/updates/version.txt");
                $url = "https://phoenix.ltda/api/update/" . $phoenix_version;
                
                $update = read_api_output($url, 'GET', get_settings_from_key('api_key'));
                
                if($update['status'] == 401){
                    $update_error = true;	
                }else{
                    $update_error = false;	
                }
                
                /* Check to see if there is a new version */
                if ($latest_version > $phoenix_version and $update_error == false) {
                    
                    $new_version = true;
                    
                    $clean_next_version = preg_replace('/[.,]/', '', $update['data']);
    
                    /* Fetch update ZIP based on the valid next version */
                    $source = 'https://phoenix.ltda/updates/phoenix-update' . $clean_next_version . '.zip';
                    $package = 'phoenix-update' . $clean_next_version . '.zip';
    
                    if(Phoenix\FileHandler::remote_file_exists($source) == true){
    
                        $update_package = true;
                       
                        if(isset($_POST['update_now'])){
                        
                        Phoenix\FileHandler::download_file($source, ROOT_PATH . $package);
    
                        /* Unzip the update pack */
                        Phoenix\FileHandler::unzip(ROOT_PATH . $package, ROOT_PATH);
                        
                        /* Proceed with update */
                        if (file_exists(ROOT_PATH . 'update.php')) {
                            require_once ROOT_PATH . 'update.php';
                        }
                        }
                    } else {
                        $update_package = false;
                    }
                    
                } else {
                    $new_version = false;
                }
            }

		}
	
		$widgets = fetch_widgets();

        /* Main View */
        $data = [
            'values' => $values,
			'is_localhost' => $is_localhost,
			'update_error' => $update_error,
			'active_api_key' => $active_api_key,
			'installer' => $installer,
            'new_version' => $new_version,
            'latest_version' => $latest_version,
			'update_package' => $update_package,
			'widgets' => $widgets
        ];

        $view = new \Phoenix\Views\View('admin/index/index', (array) $this);

        $this->addViewContent('content', $view->run($data));
    }
}
