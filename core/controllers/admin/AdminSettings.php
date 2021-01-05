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

class AdminSettings extends Controller {

    public function index() {

        Authentication::guard('admin');

        if(!empty($_POST)) {
        
            /* Main Tab */
            $_POST['title'] = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
			$_POST['desc'] = filter_var($_POST['desc'], FILTER_SANITIZE_STRING);
            $_POST['time_zone'] = filter_var($_POST['time_zone'], FILTER_SANITIZE_STRING);
            $_POST['email_confirmation'] = (bool) $_POST['email_confirmation'];
            $_POST['register_is_enabled'] = (bool) $_POST['register_is_enabled'];
            $_POST['terms_and_conditions_url'] = filter_var($_POST['terms_and_conditions_url'], FILTER_SANITIZE_STRING);
            $_POST['privacy_policy_url'] = filter_var($_POST['privacy_policy_url'], FILTER_SANITIZE_STRING);


            if(!Csrf::check()) {
                $_SESSION['error'][] = "Invalid token, please login again.";
            }


            if(empty($_SESSION['error'])) {

                $settings_keys = [

                    /* Main */
                    'title',
					'desc',
                    'default_language',
                    'time_zone',
                    'email_confirmation',
                    'register_is_enabled',
                    'index_url',
                    'terms_and_conditions_url',
                    'privacy_policy_url',
                ];

                /* Go over each key and make sure to update it accordingly */
                foreach ($settings_keys as $key => $value) {

                    if(is_array($value)) {

                        $values_array = [];

                        foreach ($value as $sub_key) {

                            /* Check if the field needs cleaning */
                            if(!in_array($key . '_' . $sub_key, ['custom_head_css', 'custom_head_js', 'ads_header', 'ads_footer'])) {
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

                /* Set message */
                $_SESSION['success'][] = "Settings have been saved.";

                /* Refresh the page */
                redirect('admin/settings');
				
				
                
            }
        }

        /* Main View */
        $view = new \Phoenix\Views\View('admin/settings/settings', (array) $this);

        $this->addViewContent('content', $view->run());

    }


}