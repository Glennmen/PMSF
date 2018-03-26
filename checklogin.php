<?php
header('Content-Type: application/json');
session_start();
$info = array();

if (isset($_SESSION['user'])){
    $info['isLoggedIn'] = true;
	$info['current_timestamp'] = time();
    $info['user'] = $_SESSION['user'];
} else {
    $info['isLoggedIn'] = false;
}

print json_encode($info);