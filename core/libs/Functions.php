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


function build(){
	$PhoenixPHP = new Phoenix\App();
}

function query_mysql($query){
global $conn;
$result = $conn->query($query);
if (!$result) die("Could not scan database.");
return $result;	
}

function get_user_language($availableLanguages, $default='en'){
	if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
		$langs=explode(',',$_SERVER['HTTP_ACCEPT_LANGUAGE']);

		foreach ($langs as $value){
			$choice=substr($value,0,2);
			if(in_array($choice, $availableLanguages)){
				return $choice;
			}
		}
	} 
	return $default;
}

function seo_optomize($seoText){
	return preg_replace('/[^a-z0-9_-]/i', '', strtolower(str_replace(' ', '-', trim($title))));
}



function basic_clean($var){
	if(get_magic_quotes_gpc())
	$var = stripslashes($var);
	$var = strip_tags($var);
	$var = htmlentities($var);
	return $var;
}


function check_mysqli(){
    if(!extension_loaded('mysqli')) {
       return false;
    }else{
        return true;
    }
}


function check_curl(){
    if(!extension_loaded('curl')) {
        return false;
    }else{
        return true;
    }
}


function check_json_decode(){
    if(!function_exists('json_decode')) {
        return false;
    }else{
        return true;
    }
}


function check_system_mail(){
    if(!function_exists('mail')) {
       return false;
    }else{
        return true;
    }
}

function check_zip(){
    if(!extension_loaded('zip')){
        return false;
    }else{
        return true;
    }
}

function send_server_mail($to, $from, $title, $content) {

    $headers = "From: " . strip_tags($from) . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

    /* Check if receipient is array or not */
    $to_processed = $to;

    if(is_array($to)) {
        $to_processed = '';

        foreach($to as $address) {
            $to_processed .= ',' . $address;
        }

    }

    return mail($to_processed, $title, $content, $headers);
}

function send_mail($settings, $to, $name, $subject, $content){

$mail = new \PHPMailer\PHPMailer\PHPMailer();
$mail->IsSMTP();                                     
$mail->SMTPAuth = $settings->smtp->auth;
$mail->Host = $settings->smtp->host;
$mail->Port = $settings->smtp->port;
$mail->Username = $settings->smtp->username;
$mail->Password = $settings->smtp->password;
if ($settings->smtp->encryption != '0') {
    $mail->SMTPSecure = $settings->smtp->encryption;
}
$mail->From = $settings->smtp->from;
$mail->FromName = $settings->title;
if(is_array($to)) {
    foreach($to as $address) {
        $mail->addAddress($address, $name);
    }
} else {
    $mail->addAddress($to, $name);
} 
$mail->Priority = 1;
$mail->AddCustomHeader("X-MSMail-Priority: High");
$mail->WordWrap = 50;    
$mail->IsHTML(true);  
$mail->Subject = $subject;
$mail->Body  = $content;
if(!$mail->Send()) {
$err = 'Message could not be sent.';
$err .= 'Mailer Error: ' . $mail->ErrorInfo;                        
}

$mail->ClearAddresses();
    
}

function url($append = '') {
    return SITE_URL . $append;
}

function redirect($append = '') {
    header('Location: ' . SITE_URL . $append);

    die();
}

function get_slug($string, $delimiter = '_') {

    /* Replace all non words characters with the specified $delimiter */
    $string = preg_replace('/\W/', $delimiter, $string);

    /* Check for double $delimiters and remove them so it only will be 1 delimiter */
    $string = preg_replace('/_+/', '_', $string);

    /* Remove the $delimiter character from the start and the end of the string */
    $string = trim($string, $delimiter);

    return $string;
}

/* Helper to output proper and nice numbers */
function nr($number, $decimals = 0, $extra = false) {

    if($extra) {

        if(in_array('B', $extra)) {

            if($number > 999999999) {
                return floor($number / 1000000000) . 'B';
            }

        }

        if(in_array('M', $extra)) {

            if($number > 999999) {
                return floor($number / 1000000) . 'M';
            }

        }

        if(in_array('K', $extra)) {

            if($number > 999) {
                return floor($number / 1000) . 'K';
            }

        }

    }

    if($number == 0) {
        return 0;
    }

    return number_format($number, $decimals);
}

function get_ip() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
       $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

function csv_exporter($array, $exclude = []) {

    $result = '';

    /* Get the total amount of columns */
    $columns_count = count((array) reset($array));

    /* Export the header */
    $i = 0;
    foreach(array_keys((array) reset($array)) as $value) {
        /* Check if not excluded */
        if(!in_array($value, $exclude)) {
            $result .= $i++ !== $columns_count - 1 ? $value . ',' : $value;
        }
    }

    foreach($array as $row) {
        $result .= "\n";

        $i = 0;
        foreach($row as $key => $value) {
            /* Check if not excluded */
            if(!in_array($key, $exclude)) {

                $value = addslashes($value);

                $result .= $i++ !== $columns_count - 1 ? '"' . $value . '"' . ',' : '"' . $value . '"';
            }
        }
    }

    return $result;
}

function csv_link_exporter($csv) {
    return 'data:application/csv;charset=utf-8,' . urlencode($csv);
}

function string_truncate($string, $maxchar) {
    $length = strlen($string);
    if($length > $maxchar) {
        $cutsize = -($length-$maxchar);
        $string  = substr($string, 0, $cutsize);
        $string  = $string . '..';
    }
    return $string;
}

function string_filter_alphanumeric($string) {

    $string = preg_replace('/[^a-zA-Z0-9\s]+/', '', $string);

    $string = preg_replace('/\s+/', ' ', $string);

    return $string;
}

function string_generate($length) {
    $characters = str_split('abcdefghijklmnopqrstuvwxyz0123456789');
    $content = '';

    for($i = 1; $i <= $length; $i++) {
        $content .= $characters[array_rand($characters, 1)];
    }

    return $content;
}

function string_ends_with($needle, $haystack) {
    return substr($haystack, -strlen($needle)) === $needle;
}

function error($title = '', $desc = ''){
    if(empty($title)){
        $title = "An error has occured";
    }
    if(empty($desc)){
        $desc = 'We have ran into an unexpected error. Please contact the administrator.';
    }
    
    $display_string = '
	<center>
    <div style = "outline: 2px solid red;margin:60px;padding:20px;">
	<h3>'. $title .'</h3>
	<p>'. $desc .'</p>
	</div>
	</center>
    ';
    
    die($display_string);
    
}

function get_admin_options_button($type, $target_id) {

    switch($type) {

        case 'user' :
            return '
                <div class="dropdown">
                        <button class="btn btn-primary no-shadow dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                            <a class="dropdown-item" href="admin/user-view/' . $target_id . '">View</a>
                            <a class="dropdown-item" href="admin/user-update/' . $target_id . '">Edit</a>
                            <a class="dropdown-item" href="admin/users/delete/' . $target_id . \Phoenix\Middlewares\Csrf::get_url_query() . '">Delete</a>
                        </div>
                    </div>
                ';

            break;

        case 'page' :
            return '
			
				<div class="dropdown">
  				<button class="btn btn-primary no-shadow dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    				 <i class="fas fa-ellipsis-v"></i>
  				</button>
  				<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
    				<a class="dropdown-item" href="admin/page-update/' . $target_id . '"><i class = "fas fa-edit"></i>Edit</a>
    				<a class="dropdown-item" href="admin/pages/delete/' . $target_id . \Phoenix\Middlewares\Csrf::get_url_query() . '"><i class = "fas fa-trash"></i>Delete</a>
  				</div>
				</div>                
                ';

            break;
			
			case 'post' :
            return '	
				<div class="dropdown">
  				<button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    				 <i class="fas fa-ellipsis-v"></i>
  				</button>
  				<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
    				<a class="dropdown-item" href="newsfeed/delete/' . $target_id . \Phoenix\Middlewares\Csrf::get_url_query() . '"><i class = "fas fa-trash"></i>Delete</a>
  				</div>
				</div>                
                ';

            break;
    }
}

function get_file_headers($file, $default_headers) {

    $fp = fopen( $file, 'r' );
    $file_data = fread( $fp, 8192 );
    fclose( $fp );
    $file_data = str_replace( "\r", "\n", $file_data );
    $all_headers = $default_headers;

    foreach ( $all_headers as $field => $regex ) {
        if (preg_match( '/^[ \t\/*#@]*' . preg_quote( $regex, '/' ) . ':(.*)$/mi', $file_data, $match ) 
            && $match[1])
            $all_headers[ $field ] = trim(preg_replace("/\s*(?:\*\/|\?>).*/", '', $match[1]));
        else
            $all_headers[ $field ] = '';
    }

    return $all_headers;
}

function get_plugins() {
    $plugins = glob(CUSTOM_PATH . 'plugins/*', GLOB_ONLYDIR);
	return $plugins;
}

function get_themes() {
    $themes = glob(CUSTOM_PATH . '/themes/*', GLOB_ONLYDIR);
	return $themes;
}

function read_api_output($url, $request_type, $api_key, $timeout = '30')
{
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => $timeout,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => $request_type,
        CURLOPT_HTTPHEADER => ["cache-control: no-cache", "Auth:" . $api_key],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    $response = json_decode($response, true);
    return $response;
}

function lang($key, $return_type = 0)
{
    global $lang;
    if (isset($SESSION['user_language'])) {
        $default_language = $_SESSION['user_language'];
    } else {
        $default_language = 'english';
    }
    $path = LANGUAGE_PATH . $default_language . '.php';
    if (file_exists($path)) {
        include $path;
    } else {
        return false;
    }
    if (array_key_exists($key, $lang)) {
		switch($return_type){
			case '0':
        		echo $lang[$key];
			break;
			case '1':
				return $lang[$key];
			break;
		}
    } else {
        echo '{{Missing key.}}';
    }
}

function activate_user($username){
	$active_sql = Phoenix\Database\Database::$database->query('UPDATE users SET active="1" WHERE username="'. $username .'"');
}

function post($user_id, $time, $title, $post_content, $post_type, $post_visibility = 0, $comments = 1, $settings = ''){
	
$stmt = Phoenix\Database\Database::$database->prepare("INSERT INTO posts (user_id, time_posted, post_title, post_content, post_type, post_visibility, post_comments) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssss", $user_id, $time, $title, $post_content, $post_type, $post_visibility, $comments);
$stmt->execute();
$new_post_id = $stmt->insert_id;
$stmt->close();	
if(!$stmt){
	return false;	
}else{
	return true;
}
}

function get_settings_from_key($key){
$sql = "SELECT `value` FROM `settings` WHERE `key`=?";
$stmt = Phoenix\Database\Database::$database->prepare($sql); 
$stmt->bind_param("s", $key);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    return $row['value'];
}
}

function get_username_from_id($id){
$sql = "SELECT `username` FROM `users` WHERE `user_id`=?";
$stmt = Phoenix\Database\Database::$database->prepare($sql); 
$stmt->bind_param("s", $id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
   return $row['username'];
}	
}

function get_username_from_post($post_id){
$sql = "SELECT `user_id` FROM `posts` WHERE `post_id`=?";
$stmt = Phoenix\Database\Database::$database->prepare($sql); 
$stmt->bind_param("s", $post_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    return get_username_from_id($row['user_id']);
}	
}

function get_username_from_comment($comment_id){
$sql = "SELECT `user_id` FROM `comments` WHERE `comment_id`=?";
$stmt = Phoenix\Database\Database::$database->prepare($sql); 
$stmt->bind_param("s", $comment_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    return get_username_from_id($row['user_id']);
}	
}

function is_localhost(){
	$local_whitelist = array(
    '127.0.0.1',
    '::1',
    'localhost'
);

if(!in_array($_SERVER['REMOTE_ADDR'], $local_whitelist)){
    return false;
}else{
	return true;	
}
}

function folder_format($string) {
    $string = strtolower($string);
    $string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
    $string = preg_replace("/[\s-]+/", " ", $string);
    $string = preg_replace("/[\s_]/", "-", $string);
    return $string;
}

function load_plugins() {
    $query = "select * from plugins where enabled = 1";
    $plugins = 'array of objects from query 1';

    if ($plugins) {
        foreach ($plugins as $plugin) {
            //
            $class = $plug->path;
            include_once "/plugins/$class";

            //  Class is using interface, so you know what methods to call
            $plug = new $class();
            if ($plug->test()) {
                $plug->execute();
            }
        }
    }
}

function get_server_cpu_usage(){

	$load = sys_getloadavg();
	return $load[0];

}

function get_server_memory_usage(){
	
	$free = shell_exec('free');
	$free = (string)trim($free);
	$free_arr = explode("\n", $free);
	$mem = explode(" ", $free_arr[1]);
	$mem = array_filter($mem);
	$mem = array_merge($mem);
	$memory_usage = $mem[2]/$mem[1]*100;

	return $memory_usage;
}

function count_users(){
    $users = Phoenix\Database\Database::$database->query("SELECT COUNT(*) AS users FROM `users`"); 
    $result = $users->fetch_assoc();

   return $result['users'];
}

?>