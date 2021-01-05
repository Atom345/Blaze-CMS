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
use Phoenix\Time\Time;

class AdminCMS extends Controller
{
    public function index()
    {
        Authentication::guard('admin');
		
		
   		if(!empty($_POST)){
			$user_id = $this->user->user_id;
			$title = basic_clean($_POST['post_title']);
			$post_content = $_POST['post_content'];
			$type = $_POST['post_type'];
			$visibility = $_POST['post_visibility'];
			
			if(isset($_POST['post_comments'])){
			$comments = $_POST['post_comments'];
			}
			
			$time = Time::get_time();
			
			if(empty($_POST['post_content']) or empty($_POST['post_title'])){
			$_SESSION['error'][] = 'Please fill in all the fields.';
			}

			if(empty($_SESSION['error'])){
			$create_post = post($user_id, $time, $title, $post_content, $type, $visibility, $comments);
			$_SESSION['success'][] = 'Post created!';
			redirect('admin/cms');
			}
			
		}

        $view = new \Phoenix\Views\View('admin/cms/cms', (array) $this);

        $this->addViewContent('content', $view->run());
    }
}