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

class AdminTheme extends Controller {

    public function index() {
        Authentication::guard('admin');
        
        
        if(!empty($_POST)) {
            /* Define some variables */
            $image_allowed_extensions = ['jpg', 'jpeg', 'png', 'svg', 'ico'];

            $_POST['primary_color'] = filter_var($_POST['primary_color'], FILTER_SANITIZE_STRING) ?? $this->settings->primary_color;
            $_POST['secondary_color'] = filter_var($_POST['secondary_color'], FILTER_SANITIZE_STRING) ?? $this->settings->secondary_color;
            $logo = (!empty($_FILES['logo']['name'])) ?? $this->settings->logo;
            $logo_name = $logo ? '' : $this->settings->logo;
            $favicon = (!empty($_FILES['favicon']['name'])) ?? $this->settings->favicon;
            $favicon_name = $favicon ? '' : $this->settings->favicon;
            
            if($logo) {
                $logo_file_name = $_FILES['logo']['name'];
                $logo_file_extension = explode('.', $logo_file_name);
                $logo_file_extension = strtolower(end($logo_file_extension));
                $logo_file_temp = $_FILES['logo']['tmp_name'];
                $logo_file_size = $_FILES['logo']['size'];
                list($logo_width, $logo_height) = getimagesize($logo_file_temp);

                if(!in_array($logo_file_extension, $image_allowed_extensions)) {
                    $_SESSION['error'][] = "Invalid logo file type.";
                }

                if(!is_writable(UPLOADS_PATH . 'logo/')) {
                    $_SESSION['error'][] = 'The '. UPLOADS_PATH . 'logo direcotry is not writeable.';
                }

                if(empty($_SESSION['error'])) {

                    /* Delete current logo */
                    if(!empty($this->settings->logo) && file_exists(UPLOADS_PATH . 'logo/' . $this->settings->logo)) {
                        unlink(UPLOADS_PATH . 'logo/' . $this->settings->logo);
                    }

                    /* Generate new name for logo */
                    $logo_new_name = md5(time() . rand()) . '.' . $logo_file_extension;

                    /* Upload the original */
                    move_uploaded_file($logo_file_temp, UPLOADS_PATH . 'logo/' . $logo_new_name);

                    /* Execute query */
                    Database::$database->query("UPDATE `settings` SET `value` = '{$logo_new_name}' WHERE `key` = 'logo'");

                }
            }

            /* Check for any errors on the logo image */
            if($favicon) {
                $favicon_file_name = $_FILES['favicon']['name'];
                $favicon_file_extension = explode('.', $favicon_file_name);
                $favicon_file_extension = strtolower(end($favicon_file_extension));
                $favicon_file_temp = $_FILES['favicon']['tmp_name'];
                $favicon_file_size = $_FILES['favicon']['size'];
                list($favicon_width, $favicon_height) = getimagesize($favicon_file_temp);

                if(!in_array($favicon_file_extension, $image_allowed_extensions)) {
                    $_SESSION['error'][] = "Invalid favicon file type.";
                }

                if(!is_writable(UPLOADS_PATH . 'favicon/')) {
                    $_SESSION['error'][] = 'The '. UPLOADS_PATH . 'favicon directory is not writeable.';
                }

                if(empty($_SESSION['error'])) {

                    /* Delete current favicon */
                    if(!empty($this->settings->favicon) && file_exists(UPLOADS_PATH . 'favicon/' . $this->settings->favicon)) {
                        unlink(UPLOADS_PATH . 'favicon/' . $this->settings->favicon);
                    }

                    /* Generate new name for favicon */
                    $favicon_new_name = md5(time() . rand()) . '.' . $favicon_file_extension;

                    /* Upload the original */
                    move_uploaded_file($favicon_file_temp, UPLOADS_PATH . 'favicon/' . $favicon_new_name);

                    /* Execute query */
                    Database::$database->query("UPDATE `settings` SET `value` = '{$favicon_new_name}' WHERE `key` = 'favicon'");

                }
            }

            if(isset($_POST['theme'])){
                $stmt = Database::$database->prepare("UPDATE `settings` SET `value` = ? WHERE `key` = 'theme'");
                $stmt->bind_param('s', $_POST['theme']);
                $stmt->execute();
                $stmt->close();
            }

            if(!Csrf::check()) {
                $_SESSION['error'][] = "Invalid token.";
            }


            if(empty($_SESSION['error'])) {

                $settings_keys = [

                    /* Main */
                    'primary_color',
                    'secondary_color',
                    
                    ];

                /* Go over each key and make sure to update it accordingly */
                foreach ($settings_keys as $key => $value) {

                    if(is_array($value)) {

                        $values_array = [];

                        foreach ($value as $sub_key) {

                            /* Check if the field needs cleaning */
                            if(!in_array($key . '_' . $sub_key, ['custom_head_css', 'custom_head_js'])) {
                                $values_array[$sub_key] = Database::clean_string($_POST[$key . '_' . $sub_key]);
                            } else {
                                $values_array[$sub_key] = $_POST[$key . '_' . $sub_key];
                            }
                        }

                        $value = json_encode($values_array);

                    } else {
                        $key = $value;
                        $value = $_POST[$key];
                    }
					
                    $stmt = Database::$database->prepare("UPDATE `settings` SET `value` = ? WHERE `key` = ?");
                    $stmt->bind_param('ss', $value, $key);
                    $stmt->execute();
                    $stmt->close();

                }
			
				$_SESSION['success'][] = "Your changes have been saved!";
				redirect('admin/theme');
				
            }
		}
		
        $view = new \Phoenix\Views\View('admin/theme/theme', (array) $this);

        $this->addViewContent('content', $view->run());

    }
    
 public function removelogo() {

        Authentication::guard('admin');

        if(!Csrf::check()) {
            redirect('admin/theme');
        }

        /* Delete the current logo */
        if(file_exists(UPLOADS_PATH . 'logo/' . $this->settings->logo)) {
            unlink(UPLOADS_PATH . 'logo/' . $this->settings->logo);
        }

        /* Remove it from db */
        Database::$database->query("UPDATE `settings` SET `value` = '' WHERE `key` = 'logo'");

        /* Set message & Redirect */
        $_SESSION['success'][] = "Logo has been removed.";
        redirect('admin/theme');

    }

    public function removefavicon() {

        Authentication::guard('admin');

        if(!Csrf::check()) {
            redirect('admin/theme');
        }

        /* Delete the current logo */
        if(file_exists(UPLOADS_PATH . 'favicon/' . $this->settings->favicon)) {
            unlink(UPLOADS_PATH . 'favicon/' . $this->settings->favicon);
        }

        /* Remove it from db */
        Database::$database->query("UPDATE `settings` SET `value` = '' WHERE `key` = 'favicon'");

        /* Set message & Redirect */
        $_SESSION['success'][] = "Favicon has been removed.";

    }
}