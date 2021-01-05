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
use Phoenix\Middlewares\Csrf;
use Phoenix\Time\Time;

class AdminStore extends Controller
{

    public function index()
    {
        Authentication::guard('admin');
		
		if(isset($this->settings->api_key)){
		if(isset($_POST['search_store']) and !empty($_POST['search_content'])){
	
		$search_term = $_POST['search_content'];
			$store = read_api_output('https://phoenix.ltda/api/store/' . $search_term, 'GET', $this->settings->api_key);
			
			if(isset($store['status']) and $store['status'] == 401){
				$store_error = true;	
			}else{
				$store_error = false;	
			}
			
			if(isset($store['status']) and $store['status'] == 404){
				$store_not_found = true;	
			}else{
				$store_not_found = false;	
			}
			
		}else{
			$store = read_api_output('https://phoenix.ltda/api/store/pinned', 'GET', $this->settings->api_key);
			$store_not_found = false;
		}
			
			if(isset($_POST['install_item'])){
				$source = $_POST['install_item'];
				$data = $_POST['item_info'];
				$install = \Phoenix\Store::install_item($source, $data, 'plugin');
				if($install == true){
					$_SESSION['success'][] = 'Plugin installed.';
				}else{
					$_SESSION['error'][] = 'Could not install plugin.';
				}
				
			}
			
			if(isset($_POST['uninstall_item'])){
				$id = $_POST['uninstall_item'];
				\Phoenix\Store::unistall_item($id, 'plugin');
				$_SESSION['success'][] = 'Item Uninstalled!';
			}
			
		}
		
		$data = [
			'store' => $store,
			'store_not_found' => $store_not_found
		];
		
        $view = new \Phoenix\Views\View('admin/store/store', (array) $this);

        $this->addViewContent('content', $view->run($data));
    }
}
?>