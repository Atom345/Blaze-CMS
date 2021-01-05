<?php

$free = shell_exec('free');
$free = (string)trim($free);
$free_arr = explode("\n", $free);
$mem = explode(" ", $free_arr[1]);
$mem = array_filter($mem);
$mem = array_merge($mem);
$memory_usage = $mem[2]/$mem[1]*100;


$load = sys_getloadavg();

$payload = array(
	"cpu" => $load[0], 
	"memory" => round($memory_usage, 1)
);
echo json_encode($payload, true);

?>