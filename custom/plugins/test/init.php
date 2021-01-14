<?php

/* Preset hook namespace to $hook */
$hooks = "Phoenix\Hooks\Hooks";

//$hooks::list_actions();

/* Your plugin code */
$hooks::hook('admin_container_loaded', 'add_widget_stats');

function add_widget_stats(){
    
$stats = array(
    'id' => 'stats',
    'title' => 'Latest Stats',
    'color' => 'primary',
    'desc' => 'Get the latest stats from your website.',
    'data' => 'some data...'
);

Phoenix\Widgets\Widgets::store_widget($stats);
var_dump(Phoenix\Widgets\Widgets::get_widgets());
}

?>