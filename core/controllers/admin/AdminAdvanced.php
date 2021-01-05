<?php

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

namespace Phoenix\Controllers;

use Phoenix\Database\Database;
use Phoenix\Middlewares\Authentication;
use Phoenix\Middlewares\Csrf;

class AdminAdvanced extends Controller {

    public function index() {

        Authentication::guard('admin');
        
        
        if(!empty($_POST)) {

            $_POST['smtp_auth'] = (bool) isset($_POST['smtp_auth']);
            $_POST['smtp_username'] = filter_var($_POST['smtp_username'] ?? '', FILTER_SANITIZE_STRING);
            $_POST['smtp_password'] = $_POST['smtp_password'] ?? '';
            $_POST['email_notifications_emails'] = str_replace(' ', '', $_POST['email_notifications_emails']);
            $_POST['email_notifications_new_user'] = (bool) isset($_POST['email_notifications_new_user']);


            if(!Csrf::check()) {
                $_SESSION['error'][] = "Invalid token.";
            }


            if(empty($_SESSION['error'])) {

                $settings_keys = [

                    'smtp' => [
                        'host',
                        'from',
                        'encryption',
                        'port',
                        'auth',
                        'username',
                        'password'
                    ],
                    
                     'email_notifications' => [
                        'emails',
                        'new_user'
                    ],

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
				
                /* Refresh the page */
                redirect('admin/advanced');
				
            }
        }

        /* Main View */
        $view = new \Phoenix\Views\View('admin/advanced/advanced', (array) $this);

        $this->addViewContent('content', $view->run());

    }
    
     public function testemail() {

        Authentication::guard('admin');

        if(!Csrf::check()) {
            redirect('admin/advanced');
        }

        $result = send_mail($this->settings, $this->settings->smtp->from, $this->settings->title . ' - Test Email', 'This is just a test email to confirm the smtp email settings!');

        if($result->ErrorInfo == '') {
            $_SESSION['success'][] = "Successfully sent the test STMP email!";
        } else {
            $_SESSION['error'][] = "The test STMP email could not be sent!" . $result->ErrorInfo;
        }

        redirect('admin/advanced');
    }
}