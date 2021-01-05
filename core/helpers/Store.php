<?php

namespace Phoenix;

use Phoenix\Database\Database;
use Phoenix\Time\Time;

class Store{
	
	public function install_item($source, $data, $type){
		switch($type){
			case 'plugin':
				$item_info = explode(',', $data);
				$item_id = $item_info[0]; //Item ID
				$item_name = $item_info[1]; //Item Name
				$item_type = $item_info[2]; //Item Type (plugin, theme)
				$item_desc = $item_info[3]; //Item Description
				$item = substr($source, strrpos($source, '/' )+1);
				if(\Phoenix\FileHandler::remote_file_exists($source) == true){
					$active = 0;
				    $full_path = PLUGIN_PATH . $item;
					$plugin_folder_name = folder_format($item_name);
					\Phoenix\FileHandler::download_file($source, $full_path);	
					\Phoenix\FileHandler::unzip($full_path, PLUGIN_PATH);
					\Phoenix\FileHandler::delete_file($full_path);
					$stmt = Database::$database->prepare("INSERT INTO `plugins` (`remote_id`, `name`, `description`, `folder`, 							`active`, `time_stamp`) VALUES (?, ?, ?, ?, ?, ?)");
					$stmt->bind_param("ssssss", $item_id, $item_name, $item_desc, $plugin_folder_name, $active, Time::get_time());
					$stmt->execute();
					$stmt->close();
					return true;
				}else{
					return false;	
				}
			break;
		}
	}

	public function item_installed($remote_id = '', $type){
	switch($type){
		case 'plugin':
			if ($result = Database::$database->query('SELECT `active` FROM `plugins` WHERE `remote_id` = '. $remote_id .'')) {
			$row_cnt = $result->num_rows;
			if($row_cnt > 0){
				return true;	
			}else{
				return false;	
			}
			}
		break;
	}
	}

	public function get_plugin_data($remote_id){
	$sql = "SELECT * FROM `plugins` WHERE `remote_id`=?";
	$stmt = Database::$database->prepare($sql); 
	$stmt->bind_param("s", $remote_id);
	$stmt->execute();
	$result = $stmt->get_result();
	while ($row = $result->fetch_assoc()) {
    	return $row;
	}	
	}

	public function unistall_item($remote_id = '', $type){
		switch($type){
		case 'plugin':
			$store = new Store;
			$data = $store->get_plugin_data($remote_id);
			FileHandler::delete_directory(PLUGIN_PATH . $data['folder']);
			$stmt = Database::$database->prepare("DELETE FROM `plugins` WHERE `plugins`.`remote_id` = ?");
			$stmt->bind_param("s", $remote_id);
			$stmt->execute();
			$stmt->close();
		break;
			default:
				error('Uninstall Error', 'Could not uninstall item. Invalid type.');
	}
	}


}

?>