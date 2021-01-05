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

class AdminFeatures extends Controller {

    public function index() {

        Authentication::guard('admin');
        
        
        if(!empty($_POST)) {

           $_POST['cms_and_posting'] = $_POST['cms_and_posting'] ?? $this->settings->cms_and_posting;
           $_POST['google_verify'] = $_POST['google_verify'] ?? $this->settings->google_verify;
           $_POST['recaptcha_public_key'] = $_POST['recaptcha_public_key'] ?? $this->settings->recaptcha_public_key;
           $_POST['recaptcha_private_key'] = $_POST['recaptcha_private_key'] ?? $this->settings->recaptcha_private_key;

            if(!Csrf::check()) {
                $_SESSION['error'][] = "Invalid token.";
            }


            if(empty($_SESSION['error'])) {

                $settings_keys = [

                  'cms_and_posting',
                  'google_verify',
                  'recaptcha_public_key',
                  'recaptcha_private_key'
                  
                ];

                /* Go over each key and make sure to update it accordingly */
                foreach ($settings_keys as $key => $value) {

                    if(is_array($value)) {

                        $values_array = [];

                        foreach ($value as $sub_key) {

                            /* Check if the field needs cleaning */
                            if(!in_array($key . '_' . $sub_key, [])) {
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
                $_SESSION['success'][] = "Your changes have been saved!";
				redirect('admin/features');
			}

		}
        /* Main View */
        $view = new \Phoenix\Views\View('admin/features/features', (array) $this);

        $this->addViewContent('content', $view->run());

    }
}