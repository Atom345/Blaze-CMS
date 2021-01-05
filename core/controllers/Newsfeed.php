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
use Phoenix\Middlewares\Csrf;
use Phoenix\Models\Posts;
use Phoenix\Middlewares\Authentication;
use Phoenix\Response;
use Phoenix\Routing\Router;
use Phoenix\Time\Time;

class NewsFeed extends Controller {

    public function index() {

		Authentication::guard('user');

        if($this->settings->cms_and_posting == 0){
			redirect();	
		}
		
		
		if(isset($_POST['submit_comment']) and isset($_POST['comment_content'])){
			$comment_attach = $_POST['submit_comment'];
			$comment = basic_clean($_POST['comment_content']);
			$time = Time::get_time();
			
			Database::clean_string($comment);
			$stmt = Database::$database->prepare("INSERT INTO comments (post_comment_id, user_id, time_posted, comment_content) VALUES (?, ?, ?, ?)");
			$stmt->bind_param("ssss", $comment_attach, $this->user->user_id, $time, $comment);
			$stmt->execute();
			$stmt->close();
			
			$_SESSION['success'][] = "Comment added.";
			redirect('newsfeed');
			
		}
		
		  /* Main View */
        $data = [
           
        ];
		
        $view = new \Phoenix\Views\View('newsfeed/newsfeed', (array) $this);

        $this->addViewContent('content', $view->run($data));

    }
	
	public function delete() {

        Authentication::guard();

        $post_id = (isset($this->params[0])) ? $this->params[0] : false;
		
        if(!Csrf::check()) {
            $_SESSION['error'][] = "Please Login";
        }

        if(empty($_SESSION['error'])) {

          (new Posts())->delete($post_id);
		  redirect('newsfeed');

        }

        die();
    }


}