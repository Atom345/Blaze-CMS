<?php

/* Preset hook namespace to $hook */
$hooks = "Phoenix\Hooks\Hooks";
$hooks::list_actions();

/* Your plugin code */
$hooks::hook('admin_container_loaded', 'on_admin');



?>