<?php
ini_set('max_execution_time', 0);
set_time_limit(0);


if (!`which convert`) {
    throw new Exception("It would appear ImageMagick is not installed on your server.\nPlease install it using your favourite package manager, or compiling from source.");
}

if (!file_exists('config/config.php')) {
    die("\033[31mCowardly refusing to create static icons. You need a config file to create them.\033[0m");
}

include('config/config.php');
if (!$copyrightSafe) {
    $iconsDir = 'static/icons-pokemon/';
} else {
    $iconsDir = 'static/icons-safe/';
}

if (!is_dir($iconsDir)) {
    throw new Exception("Couldn't locate your icons directory. If using non-copyright safe icons, please ensure you have also extracted the icons-pokemon folder to static");
}
/* Sprite config */
$iconWidth = 80;

$baseDir = 'static/spawns/0/';
$radius = 15;
$x = $iconWidth - $radius - 2;
$y = $radius + 1;
$y2 = 1;

$imagick = "";

echo "Building static spawn icons. This will take a few seconds...\n";

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
        echo "Building icons: \033[32m".$v. "\033[0m\n";
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
