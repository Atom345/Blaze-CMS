<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

define('PHOENIX_VERSION', '1.0.1');
define('ROOT_PATH', realpath(__DIR__ . '/..') . '/');


define('ABSPATH',dirname(__FILE__).'/');
define('BASEPATH',dirname($_SERVER['PHP_SELF']));


error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

function delete_file($deleteFilename){
	if(file_exists($deleteFilename)){
		if(!unlink($deleteFilename)) echo "Could not delete $deleteFilename .";
	}
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

$mysqli = check_mysqli();
$curl = check_curl();
$json =  check_json_decode();
$mail = check_system_mail();
$zip = check_zip();

if(isset($_POST['submit'])) {
    
    $db = new mysqli($_POST['db_host'], $_POST['db_user'], $_POST['db_pass'], $_POST['db_name']);
    if(mysqli_connect_error()) {
       die("There was an error trying to connect to the database, please check your connection info and try again.");
    }
	
$structure = "
-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 31, 2020 at 08:47 PM
-- Server version: 5.7.32-cll-lve
-- PHP Version: 7.3.25
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
START TRANSACTION;
SET time_zone = '+00:00';
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
--
-- Database: `phoenix_db`
--
-- --------------------------------------------------------
--
-- Table structure for table `comments`
--
CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL,
  `post_comment_id` int(11) DEFAULT NULL,
  `time_posted` varchar(20) DEFAULT NULL,
  `user_id` varchar(10) DEFAULT NULL,
  `comment_content` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
-- --------------------------------------------------------
--
-- Table structure for table `pages`
--
CREATE TABLE `pages` (
  `page_id` int(11) NOT NULL,
  `title` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `url` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `description` text COLLATE utf8mb4_unicode_ci,
  `type` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `position` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_veiws` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
--
-- Dumping data for table `pages`
--
INSERT INTO `pages` (`page_id`, `title`, `url`, `description`, `type`, `position`, `total_veiws`) VALUES
(9, 'Welcome', 'welcome', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n</head>\r\n<body>\r\n<h3>Welcome to Phoenix PHP! We hope you will enjoy using our software!</h3>\r\n<p><strong>Phoenix has quite a few out of the box features to help your website get started.</strong></p>\r\n<ul>\r\n<li>Powerful Admin Panel</li>\r\n<li>CRSF and SQL injection protection</li>\r\n<li>Good security</li>\r\n<li>Easy ORM to navigate the database</li>\r\n<li>Lots of Useful Functions</li>\r\n<li>A fully featured user management system</li>\r\n<li>A simple CMS module</li>\r\n<li>A plugin and theme system</li>\r\n</ul>\r\n<p><span style=\"color: #ffaf00;\">To access the Phoenix Dashboard, login to your admin account with the creditials you gave during installation.&nbsp;</span></p>\r\n<p><span style=\"color: #e74c3c;\"><strong>We also strongly recommend you change Phoenix\'s default theme. If you would like to help us improve the Phoenix default theme, you can join our Discord.</strong></span></p>\r\n<p>If you need any help or support, you can check out <span style=\"color: #e67e23;\"><a style=\"color: #e67e23;\" href=\"../../../docs\" target=\"_blank\" rel=\"noopener\">our docs&nbsp;</a>&nbsp;<span style=\"color: #000000;\">or use our live support chat.</span></span></p>\r\n<p>&nbsp;</p>\r\n<h5>Happy Building,</h5>\r\n<pre>- The Phoenix PHP Team</pre>\r\n</body>\r\n</html>', 'internal', 'top', '0');
-- --------------------------------------------------------
--
-- Table structure for table `plugins`
--
CREATE TABLE `plugins` (
  `id` int(11) NOT NULL,
  `remote_id` varchar(20) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `folder` varchar(50) DEFAULT NULL,
  `active` varchar(255) DEFAULT NULL,
  `time_stamp` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
-- --------------------------------------------------------
--
-- Table structure for table `posts`
--
CREATE TABLE `posts` (
  `post_id` int(11) NOT NULL,
  `user_id` varchar(10) DEFAULT NULL,
  `time_posted` varchar(40) DEFAULT NULL,
  `post_title` varchar(32) DEFAULT NULL,
  `post_content` text NOT NULL,
  `post_type` varchar(3) DEFAULT NULL,
  `post_visibility` varchar(3) DEFAULT NULL,
  `post_comments` varchar(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
-- --------------------------------------------------------
--
-- Table structure for table `settings`
--
CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `key` varchar(64) NOT NULL DEFAULT '',
  `value` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
--
-- Dumping data for table `settings`
--
INSERT INTO `settings` (`id`, `key`, `value`) VALUES
(1, 'ads', '{\"header\":null,\"footer\":null}'),
(2, 'default_language', 'english'),
(3, 'email_confirmation', ''),
(4, 'register_is_enabled', '1'),
(5, 'email_notifications', '{\"emails\":\"\",\"new_user\":\"0\"}'),
(6, 'favicon', ''),
(7, 'logo', ''),
(8, 'smtp', '{\"host\":\"\",\"from\":\"\",\"encryption\":\"ssl\",\"port\":\"465\",\"auth\":\"1\",\"username\":\"\",\"password\":\"\"}'),
(9, 'custom', '{\"head_js\":null,\"head_css\":null}'),
(10, 'socials', '{\"facebook\":\"\",\"instagram\":\"\",\"twitter\":\"\",\"youtube\":\"\"}'),
(11, 'time_zone', 'America/Denver'),
(12, 'title', 'Phoenix CMS'),
(13, 'privacy_policy_url', ''),
(14, 'terms_and_conditions_url', ''),
(15, 'index_url', ''),
(16, 'desc', 'Meet Phoenix, the next generation CMS platform for creating websites.'),
(17, 'cms_and_posting', '1'),
(18, 'theme', 'phoenix'),
(19, 'version', '1.1'),
(20, 'primary_color', '#FFB74D'),
(21, 'secondary_color', '#FF9800'),
(22, 'google_verify', '0'),
(23, 'recaptcha_public_key', ''),
(24, 'recaptcha_private_key', ''),
(25, 'api_key', ''),
(26, 'personal_key', 'Click the button below to generate a new key.'),
(27, 'test_api', '1'),
(28, 'add_user_api', '1'),
(29, 'remove_user_api', '1'),
(30, 'website_details_api', '1');

-- --------------------------------------------------------
--
-- Table structure for table `users`
--
CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `email` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token_code` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `email_activation_code` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `lost_password_code` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `facebook_id` bigint(20) DEFAULT NULL,
  `type` int(11) NOT NULL DEFAULT '0',
  `active` int(11) NOT NULL DEFAULT '0',
  `language` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT 'english',
  `date` datetime DEFAULT NULL,
  `ip` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_activity` datetime DEFAULT NULL,
  `last_user_agent` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `total_logins` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
-- --------------------------------------------------------
--
-- Table structure for table `users_logs`
--
CREATE TABLE `users_logs` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `type` varchar(64) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `ip` varchar(64) DEFAULT NULL,
  `public` int(11) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
--
-- Indexes for dumped tables
--
--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`);
--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`page_id`);
--
-- Indexes for table `plugins`
--
ALTER TABLE `plugins`
  ADD PRIMARY KEY (`id`);
--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`post_id`);
--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `key` (`key`);
--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);
--
-- Indexes for table `users_logs`
--
ALTER TABLE `users_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `users_logs_user_id` (`user_id`);
--
-- AUTO_INCREMENT for dumped tables
--
--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;
--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `page_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `plugins`
--
ALTER TABLE `plugins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;
--
-- AUTO_INCREMENT for table `users_logs`
--
ALTER TABLE `users_logs`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=408;
--
-- Constraints for dumped tables
--
--
-- Constraints for table `users_logs`
--
ALTER TABLE `users_logs`
  ADD CONSTRAINT `users_logs_users_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

";

 $db->multi_query($structure) or die("An error has occured when trying to add database tables. Please contact the Phoenix Team.");
    do{} while(mysqli_more_results($db) && mysqli_next_result($db));
	
$config_string = '<?php

/*
-------------------------------------------------
Phoenix CMS | Copyright (c) 2020-2025           
-------------------------------------------------
Author: Phoenix CMS Team
-------------------------------------------------
Phoenix CMS and its corresponding files are all 
licensed under the GPL 3.0 lisence.
-------------------------------------------------
*/

define("DB_HOST", "' . $_POST['db_host'] . '");
define("DB_USER", "' . $_POST['db_user'] . '");
define("DB_PASS", "' . $_POST['db_pass'] . '");
define("DB_NAME", "' . $_POST['db_name'] . '");
define("DB_PORT", 3306);
define("SITE_URL", "' . $_POST['site_url'] . '");

/* To activate Phoenix Dev mode, set the below varible to true. This is useful for theme and plugin developers */
define("DEV_MODE", false);

?>
';
mkdir("../core/config", 0755);

$config_file =  '../core/config/config.php';
$handle = fopen($config_file, 'w') or die("The Phoenix CMS installer failed to create the config file.");
fwrite($handle, $config_string);
fclose($handle);

/* Add API key */
$stmt = $db->prepare("UPDATE `settings` SET `value` = ? WHERE `key` = 'api_key'");
$stmt->bind_param("s", $api_key_post);

$api_key_post = $_POST['api_key'];
$stmt->execute();
$stmt->close();

/* Add admin account */
$empty = '';
$one = 1;
$language = 'english';
$stmt = $db->prepare("INSERT INTO `users` (`user_id`, `email`, `password`, `username`, `token_code`, `email_activation_code`, `lost_password_code`, `facebook_id`, `type`, `active`, `language`, `date`, `ip`, `last_activity`, `last_user_agent`, `total_logins`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssssssssssssss", $one, $admin_email, $admin_password, $admin_username, $empty, $empty, $empty, $empty, $one, $one, $language, $empty, $empty, $empty, $empty, $empty);

$admin_email = $_POST['admin_email'];
$admin_username = $_POST['admin_username'];
$admin_password = password_hash($_POST['admin_password'], PASSWORD_DEFAULT);
$stmt->execute();
$stmt->close();
    
header("Location: ../");

}

?>

<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

	<title>Install Phoenix PHP v(<?php echo PHOENIX_VERSION ?>)</title>

	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />
	<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />

	<link href="../custom/themes/phoenix/assets/css/bootstrap.min.css" rel="stylesheet" />
	<link href="../custom/themes/phoenix/assets/css/style.css" rel="stylesheet" />
	<link href="../custom/themes/phoenix/assets/css/components.css" rel="stylesheet" />
</head>

<body>
	<div class = "container">
		<form method = "post" action = "index.php">
		<div class = "row">
			<div class = "col-md-12">
				<div class = "card card-primary m-5">
					<div class = "card-header">
						<div class = "card-title"><h4>Install Phoenix <?php echo PHOENIX_VERSION ?></h4></div>
					</div>
					<div class = "card-body">
						<div class = "row">
							<div class = "col-md-6">
								<div class = "card-text m-3"><h4>Website Details</h4></div>
								<div class = "form-group">
									<input type = "text" class = "form-control m-2" name = "db_host" placeholder = "Database Host" required>
									<small class = "text-muted">This is you database hostname. You can get this from your hosting provider.</small>
                  <br>
									<input type = "text" class = "form-control m-2" name = "db_user" placeholder = "Database User" required>
									<small class = "text-muted">This is the name of the user that can access your database.</small>
                  <br>
									<input type = "text" class = "form-control m-2" name = "db_pass" placeholder = "Database Password" required>
									<small class = "text-muted">This is the password used to connect to your database.</small>
                  <br>
									<input type = "text" class = "form-control m-2" name = "db_name" placeholder = "Database Name" required>
									<small class = "text-muted">This is your database name.</small>
                  <br>
									<input type = "text" class = "form-control m-2" name = "site_url" placeholder = "Website URL" required>
									<small class = "text-muted">This is your website URL. Make sure to add a trailing '/'.</small>
                  <br>
                  <input type = "text" class = "form-control m-2" name = "api_key" placeholder = "Phoenix API Key" required>
									<small class = "text-muted">Claim your free API key from the Phoenix website.</small>
								</div>
							</div>
							<div class = "col-md-6">
								<div class = "card-text m-3"><h4>Admin Account</h4></div>
								<div class = "form-group">
									<input type = "text" class = "form-control m-2" name = "admin_email" placeholder = "Email" required>
									<small class = "text-muted">This is the email that you wish to use for your admin account.</small>
									<input type = "text" class = "form-control m-2" name = "admin_username" placeholder = "Username" required>
									<small class = "text-muted">This will be your admin username.</small>
									<input type = "password" class = "form-control m-2" name = "admin_password" placeholder = "Password" required>
									<small class = "text-muted">This is the password used to login to your account.</small>
								</div>
							</div>
							<button class = "btn btn-primary m-3" type = "submit" name = "submit">Blast Off!</button>
						</div>
					</div>
				</div>
			</div>
		</div>
		</form>
	</div>
	
<script src='https://code.jquery.com/jquery-3.4.1.min.js'></script>
<script src='https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/zxcvbn/4.2.0/zxcvbn.js'></script>
</body>
</html>
