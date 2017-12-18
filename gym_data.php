<?php
$_GET['ville'] = $_POST['ville'];
$ville = $_POST['ville'];

include('config/config.php');
include(dirname(__FILE__).'/config.php');
include(dirname(__FILE__)."/Entities/User.php");

if(!in_array($ville,array("CHOLET"))) {
	if(!isset($_SESSION['User'])) {
		die();
	}
	$User = unserialize($_SESSION['User']);
	$memberLimitTimestamp = strtotime($User->getMemberLimitTime());
	if($memberLimitTimestamp < time()) {
		die();
	}
	if(!$User->checkUniqueUser()) {
		die();
	} else {
		$User->ping();
	}
}
// init map
if ($map == "monocle") {
    if ($fork == "asner") {
        $scanner = new \Scanner\Monocle_Asner();
    } elseif ($fork == "monkey") {
        $scanner = new \Scanner\Monocle_Monkey();
    } else {
        $scanner = new \Scanner\Monocle();
    }
} elseif ($map == "rm") {
    if ($fork == "sloppy") {
        $scanner = new \Scanner\RocketMap_Sloppy();
    } else {
        $scanner = new \Scanner\RocketMap();
    }
}

if (empty($_POST['id'])) {
    http_response_code(400);
    die();
}
if (!validateToken($_POST['token'])) {
    http_response_code(400);
    die();
}


$id = $_POST['id'];

$p = $scanner->get_gym($id);

$p['token'] = refreshCsrfToken();

echo json_encode($p);
