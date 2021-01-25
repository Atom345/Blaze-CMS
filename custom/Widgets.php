<?php 

return $load_widgets = array(
    'users_widget' => [
        'id' => 'users',
		'title' => 'Website Users',
		'color' => 'bg-purple',
		'desc' => 'All the users registered on your website.',
		'data' => count_users()
    ],

    'dev_widget' => [
        'id' => 'dev',
		'title' => 'Developer Mode',
		'color' => 'bg-danger',
		'desc' => 'The status of developer mode.',
		'data' => is_dev_mode()
    ],

    'bug_widget' => [
        'id' => 'bugs',
		'title' => 'Phoenix Bugs',
		'color' => 'bg-primary',
		'desc' => 'Bugs the community has found.',
		'data' => get_bugs_num()
    ],

    'version_widget' => [
        'id' => 'version',
		'title' => 'Phoenix Version',
		'color' => 'bg-success',
		'desc' => 'The current phoenix version.',
		'data' => get_version()
    ],
);

?>