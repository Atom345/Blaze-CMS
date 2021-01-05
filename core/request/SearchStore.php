<?php
if($_SERVER['HTTP_X_REQUESTED_WITH'] == "XMLHttpRequest"){
require_once '../libs/Router.php';
$search = \Phoenix\Controllers\AdminStore::search_store("REEEEE");

if(!$search){
	error("Search AJAX Error", "Could not make the search request. Please contact the Phoenix PHP team.");	
}
}

?>