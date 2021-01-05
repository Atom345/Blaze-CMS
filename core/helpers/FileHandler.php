<?php

namespace Phoenix;

class FileHandler{

	public function remote_file_exists($file_url){
    	$handle = @fopen($file_url, 'r');
    	if (!$handle) {
        	return false;
    	} else {
        	return true;
    	}
	}

	public function unzip($location, $new_location){
		$zip = new \ZipArchive;
    	$res = $zip->open($location);
    	if ($res === TRUE) {
    	$zip->extractTo($new_location);
    	$zip->close();
    	} 	
	}
	
	public function download_file($file_url, $location){
		$ch = curl_init($file_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $data = curl_exec($ch);

        curl_close($ch);

        file_put_contents($location, $data);
	}
	
	function delete_file($file){
	if(file_exists($file)){
		if(!unlink($file)) error('File Error', 'Could not delete the requested file:' . $file);
	}
	}
	
	public static function delete_directory($dirPath) {
    if (! is_dir($dirPath)) {
        throw new InvalidArgumentException("$dirPath must be a directory");
    }
    if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
        $dirPath .= '/';
    }
    $files = glob($dirPath . '*', GLOB_MARK);
    foreach ($files as $file) {
        if (is_dir($file)) {
            self::deleteDir($file);
        } else {
            unlink($file);
        }
    }
    rmdir($dirPath);
	}
	
	
}
?>