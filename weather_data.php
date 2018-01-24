<?php
include('config/config.php');
global $map, $fork;
header('Content-Type: application/json');
// init map
if (strtolower($map) == "monocle") {
    if (strtolower($fork) == "asner") {
        $scanner = new \Scanner\Monocle_Asner();
    } elseif (strtolower($fork) == "alternate") {
        $scanner = new \Scanner\Monocle_Alternate();
    } else {
        $scanner = new \Scanner\Monocle();
    }
} elseif (strtolower($map) == "rm") {
    if (strtolower($fork) == "sloppy") {
        $scanner = new \Scanner\RocketMap_Sloppy();
    } else {
        $scanner = new \Scanner\RocketMap();
    }
}
$timestamp = (isset($_POST['ts']) ? $_POST['ts'] : null);
$weather = $scanner->get_weather($timestamp);
$d['weather'] = $weather;
$d['timestamp'] = time();
$jaysson = json_encode($d);
echo $jaysson;