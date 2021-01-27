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

class AdminAPI extends Controller
{
    public function index()
    {
        Authentication::guard('admin');

        if(isset($_POST['generate_api_key'])){
                $bytes = $key = implode('-', str_split(substr(strtolower(md5(microtime().rand(1000, 9999))), 0, 30), 6));

                $stmt = Database::$database->prepare("UPDATE `settings` SET `value` = ? WHERE `key` = 'personal_key'");
                $stmt->bind_param('s', $bytes);
                $stmt->execute();
                $stmt->close();

                 /* Set message */
                 $_SESSION['success'][] = "API Key Re-Generated.";

                 /* Refresh the page */
                 redirect('admin/api');
        }

        if(isset($_POST['save'])) {

            if(!Csrf::check()) {
                $_SESSION['error'][] = "Invalid token, please login again.";
            }


            if(empty($_SESSION['error'])) {

                $settings_keys = [   
                    'test_api',
                    'add_user_api',
                    'remove_user_api'
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
                $_SESSION['success'][] = "Settings Saved.";

                /* Refresh the page */
                redirect('admin/api');
				
				
                
            }
        }

        $view = new \Phoenix\Views\View('admin/api/api', (array) $this);

        $this->addViewContent('content', $view->run());
    }
}
