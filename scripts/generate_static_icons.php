<?php
ini_set('max_execution_time', 0);
set_time_limit(0);

include('config/config.php');
if (!$copyrightSafe) {
    $iconsDir = 'static/icons-pokemon/';
} else {
    $iconsDir = 'static/icons-safe/';
}
/* Sprite config */
$iconWidth = 80;

$baseDir = 'static/spawns/0/';
$radius = 15;
$x = $iconWidth - $radius - 2;
$y = $radius + 1;
$y2 = 1;

$imagick = "";
foreach ($weather as $k => $v) {
    $weatherIcon = 'static/weather/'.$v.'.png';
    if ($k == 0) {
        // move icons to spawn folder for spawns
        for ($i = 1; $i <= 386; $i++) {
            $id = $i - 1;
            $imagick = "";
            copy($iconsDir.$i.".png", $baseDir.$i.".png");
        }
    } else {
        for ($i = 1; $i <= 386; $i++) {
            if ($v !== null) {
                $imagick .= ' -gravity northeast -fill "#FFFD" -stroke black -draw "circle ' . $x . ',' . $y . ' ' . $x . ',' . $y2 . '" -draw "image over 1,1 30,30 \'' . $weatherIcon . '\'"';
            }
            $outfile = 'static/spawns/' . $k . '/' . $i . '.png';
            $cmd = 'convert ' . $baseDir . '/'.$i.'.png ' . $imagick . ' ' . $outfile;
            $imagick = "";
            exec($cmd);
        }
    }
}
