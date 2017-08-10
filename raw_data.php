<?php

include('config/config.php');


$now = new DateTime();

$d = array();

$d["timestamp"] = $now->getTimestamp();

$swLat = !empty($_POST['swLat']) ? $_POST['swLat'] : 0;
$neLng = !empty($_POST['neLng']) ? $_POST['neLng'] : 0;
$swLng = !empty($_POST['swLng']) ? $_POST['swLng'] : 0;
$neLat = !empty($_POST['neLat']) ? $_POST['neLat'] : 0;
$oSwLat = !empty($_POST['oSwLat']) ? $_POST['oSwLat'] : 0;
$oSwLng = !empty($_POST['oSwLng']) ? $_POST['oSwLng'] : 0;
$oNeLat = !empty($_POST['oNeLat']) ? $_POST['oNeLat'] : 0;
$oNeLng = !empty($_POST['oNeLng']) ? $_POST['oNeLng'] : 0;
$luredonly = !empty($_POST['luredonly']) ? $_POST['luredonly'] : false;
$lastpokemon = !empty($_POST['lastpokemon']) ? $_POST['lastpokemon'] : false;
$lastgyms = !empty($_POST['lastgyms']) ? $_POST['lastgyms'] : false;
$lastpokestops = !empty($_POST['lastpokestops']) ? $_POST['lastpokestops'] : false;
$lastlocs = !empty($_POST['lastslocs']) ? $_POST['lastslocs'] : false;
$lastspawns = !empty($_POST['lastspawns']) ? $_POST['lastspawns'] : false;
$d["lastpokestops"] = !empty($_POST['pokestops']) ? $_POST['pokestops'] : false;
$d["lastgyms"] = !empty($_POST['gyms']) ? $_POST['gyms'] : false;
$d["lastslocs"] = !empty($_POST['scanned']) ? $_POST['scanned'] : false;
$d["lastspawns"] = !empty($_POST['spawnpoints']) ? $_POST['spawnpoints'] : false;
$d["lastpokemon"] = !empty($_POST['pokemon']) ? $_POST['pokemon'] : false;

$timestamp = !empty($_POST['timestamp']) ? $_POST['timestamp'] : 0;

$useragent = $_SERVER['HTTP_USER_AGENT'];
if (empty($swLat) || empty($swLng) || empty($neLat) || empty($neLng) || preg_match("/curl|libcurl/", $useragent)) {
    http_response_code(400);
    die();
}
if ($maxLatLng > 0 && ((($neLat - $swLat) > $maxLatLng) || (($neLng - $swLng) > $maxLatLng))) {
    http_response_code(400);
    die();
}

if (!validateToken($_POST['token'])) {
    http_response_code(400);
    die();
}

$newarea = false;

if (($oSwLng < $swLng) && ($oSwLat < $swLat) && ($oNeLat > $neLat) && ($oNeLng > $neLng)) {
    $newarea = false;
} elseif (($oSwLat != $swLat) && ($oSwLng != $swLng) && ($oNeLat != $neLat) && ($oNeLng != $neLng)) {
    $newarea = true;
} else {
    $newarea = false;
}

$d["oSwLat"] = $swLat;
$d["oSwLng"] = $swLng;
$d["oNeLat"] = $neLat;
$d["oNeLng"] = $neLng;

$ids = array();
$eids = array();
$reids = array();

global $noPokemon;
if (!$noPokemon) {
    if ($d["lastpokemon"] == "true") {
        if ($lastpokemon != 'true') {
            $d["pokemons"] = get_active($swLat, $swLng, $neLat, $neLng);
        } else {
            if ($newarea) {
                $d["pokemons"] = get_active($swLat, $swLng, $neLat, $neLng, 0, $oSwLat, $oSwLng, $oNeLat, $oNeLng);
            } else {
                $d["pokemons"] = get_active($swLat, $swLng, $neLat, $neLng, $timestamp);
            }
        }

        if (!empty($_POST['eids'])) {
            $eids = explode(",", $_POST['eids']);

            foreach ($d['pokemons'] as $elementKey => $element) {
                foreach ($element as $valueKey => $value) {
                    if ($valueKey == 'pokemon_id') {
                        if (in_array($value, $eids)) {
                            //delete this particular object from the $array
                            unset($d['pokemons'][$elementKey]);
                        }
                    }
                }
            }
        }

        if (!empty($_POST['reids'])) {
            $reids = explode(",", $_POST['reids']);

            $d["pokemons"] = $d["pokemons"] + (get_active_by_id($reids, $swLat, $swLng, $neLat, $neLng));

            $d["reids"] = !empty($_POST['reids']) ? $reids : null;
        }
    }
}

global $noPokestops;
if (!$noPokestops) {
    if ($d["lastpokestops"] == "true") {
        if ($lastpokestops != "true") {
            $d["pokestops"] = get_stops($swLat, $swLng, $neLat, $neLng, 0, 0, 0, 0, 0, $luredonly);
        } else {
            if ($newarea) {
                $d["pokestops"] = get_stops($swLat, $swLng, $neLat, $neLng, 0, $oSwLat, $oSwLng, $oNeLat, $oNeLng, $luredonly);
            } else {
                $d["pokestops"] = get_stops($swLat, $swLng, $neLat, $neLng, $timestamp, 0, 0, 0, 0, $luredonly);
            }
        }
    }
}
global $noGyms, $noRaids;
if (!$noGyms || !$noRaids) {
    if ($d["lastgyms"] == "true") {
        if ($lastgyms != "true") {
            $d["gyms"] = get_gyms($swLat, $swLng, $neLat, $neLng);
        } else {
            if ($newarea) {
                $d["gyms"] = get_gyms($swLat, $swLng, $neLat, $neLng, 0, $oSwLat, $oSwLng, $oNeLat, $oNeLng);
            } else {
                $d["gyms"] = get_gyms($swLat, $swLng, $neLat, $neLng, $timestamp);
            }
        }
    }
}

global $noSpawnPoints;
if (!$noSpawnPoints) {
    if ($d["lastspawns"] == "true") {
        if ($lastspawns != "true") {
            $d["spawnpoints"] = get_spawnpoints($swLat, $swLng, $neLat, $neLng);
        } else {
            if ($newarea) {
                $d["spawnpoints"] = get_spawnpoints($swLat, $swLng, $neLat, $neLng, 0, $oSwLat, $oSwLng, $oNeLat, $oNeLng);
            } else {
                $d["spawnpoints"] = get_spawnpoints($swLat, $swLng, $neLat, $neLng, $timestamp);
            }
        }
    }
}

global $noScannedLocations;
if (!$noScannedLocations) {
    if ($d["lastslocs"] == "true") {
        if ($lastlocs != "true") {
            $d["scanned"] = get_recent($swLat, $swLng, $neLat, $neLng);
        } else {
            if ($newarea) {
                $d["scanned"] = get_recent($swLat, $swLng, $neLat, $neLng, 0, $oSwLat, $oSwLng, $oNeLat, $oNeLng);
            } else {
                $d["scanned"] = get_recent($swLat, $swLng, $neLat, $neLng, $timestamp);
            }
        }
    }
}

$d['token'] = refreshCsrfToken();

$jaysson = json_encode($d);
echo $jaysson;




// todo: HERE

function get_spawnpoints($swLat, $swLng, $neLat, $neLng, $tstamp = 0, $oSwLat = 0, $oSwLng = 0, $oNeLat = 0, $oNeLng = 0)
{
    global $db;

    $datas = array();

    global $map;
    if ($map == "monocle") {
        if ($swLat == 0) {
            $datas = $db->query("SELECT lat, lon, spawn_id, despawn_time FROM spawnpoints WHERE updated > 0")->fetchAll();
        } elseif ($tstamp > 0) {
            $datas = $db->query("SELECT lat, 
       lon, 
       spawn_id, 
       despawn_time 
FROM   spawnpoints 
WHERE  updated > :updated
AND    lat > :swLat 
AND    lon > :swLng
AND    lat < :neLat 
AND    lon < :neLng", ['updated'=> $tstamp,':swLat' => $swLat, ':swLng' => $swLng, ':neLat' => $neLat, ':neLng' => $neLng])->fetchAll();
        } elseif ($oSwLat != 0) {
            $datas = $db->query("SELECT lat, 
       lon, 
       spawn_id, 
       despawn_time 
FROM   spawnpoints 
WHERE  updated > 0 
       AND lat > :swLat  
       AND lon > :swLng 
       AND lat < :neLat 
       AND lon <  :neLng  
       AND NOT( lat >  :oSwLat 
                AND lon >  :oSwLng
                AND lat <  :oNeLat
                AND lon <  :oNeLng ) ", [':swLat' => $swLat, ':swLng' => $swLng, ':neLat' => $neLat, ':neLng' => $neLng,  ':oSwLat' => $oSwLat, ':oSwLng' => $oSwLng, ':oNeLat' => $oNeLat, ':oNeLng' => $oNeLng])->fetchAll();
        } else {
            $datas = $db->query("SELECT lat, 
       lon, 
       spawn_id, 
       despawn_time 
FROM   spawnpoints 
WHERE  updated > 0 
AND    lat >  :swLat  
AND    lon >  :swLng 
AND    lat < :neLat 
AND    lon < :neLng",[':swLat' => $swLat, ':swLng' => $swLng, ':neLat' => $neLat, ':neLng' => $neLng])->fetchAll();
        }

        $spawnpoints = array();
        $i = 0;

        foreach ($datas as $row) {
            $p = array();

            $p["latitude"] = floatval($row["lat"]);
            $p["longitude"] = floatval($row["lon"]);
            $p["spawnpoint_id"] = $row["spawn_id"];
            $p["time"] = intval($row["despawn_time"]);

            $spawnpoints[] = $p;

            unset($row[$i]);

            $i++;
        }

        return $spawnpoints;
    } else {
        if ($swLat == 0) {
            $datas = $db->query("SELECT latitude 
       AS lat, 
       longitude 
       AS lon, 
       spawnpoint_id 
       AS spawn_id, 
       Unix_timestamp(Convert_tz(disappear_time, '+00:00', @@global.time_zone)) 
       AS time, 
       Count(spawnpoint_id) 
       AS count 
FROM   pokemon 
GROUP  BY latitude, 
          longitude, 
          spawnpoint_id, 
          time ")->fetchAll();
        } elseif ($tstamp > 0) {
            $date = new DateTime();
            $date->setTimezone(new DateTimeZone('UTC'));
            $date->setTimestamp($tstamp);
            $datas = $db->query("SELECT   latitude                                                                 AS lat, 
         longitude                                                                AS lon, 
         spawnpoint_id                                                            AS spawn_id, 
         Unix_timestamp(Convert_tz(disappear_time, '+00:00', @@global.time_zone)) AS time, 
         Count(spawnpoint_id)                                                     AS count 
FROM     pokemon 
WHERE    last_modified > :lastModified
AND      latitude > :swLat  
AND      longitude > :swLng  
AND      latitude < :neLat  
AND      longitude < :neLng 
GROUP BY latitude, 
         longitude, 
         spawnpoint_id, 
         time", [':lastModified' => date_format($date, 'y-m-d H:I:s'), ':swLat' => $swLat, ':swLng' => $swLng, ':neLat' => $neLat, ':neLng' => $neLng])->fetchAll();
        } elseif ($oSwLat != 0) {
            $datas = $db->query("SELECT latitude 
       AS lat, 
       longitude 
       AS lon, 
       spawnpoint_id 
       AS spawn_id, 
       Unix_timestamp(Convert_tz(disappear_time, '+00:00', @@global.time_zone)) 
       AS time, 
       Count(spawnpoint_id) 
       AS count 
FROM   pokemon 
WHERE  latitude > :swLat  
AND      longitude > :swLng  
AND      latitude < :neLat  
AND      longitude < :neLng 
       AND NOT( latitude >  :oSwLat 
                AND longitude >  :oSwLng
                AND latitude <  :oNeLat
                AND longitude <  :oNeLng ) 
GROUP  BY latitude, 
          longitude, 
          spawnpoint_id, 
          time ", [':swLat' => $swLat, ':swLng' => $swLng, ':neLat' => $neLat, ':neLng' => $neLng,  ':oSwLat' => $oSwLat, ':oSwLng' => $oSwLng, ':oNeLat' => $oNeLat, ':oNeLng' => $oNeLng])->fetchAll();
        } else {
            $datas = $db->query("SELECT latitude 
       AS lat, 
       longitude 
       AS lon, 
       spawnpoint_id 
       AS spawn_id, 
       Unix_timestamp(Convert_tz(disappear_time, '+00:00', @@global.time_zone)) 
       AS time, 
       Count(spawnpoint_id) 
       AS count 
FROM   pokemon 
WHERE  latitude > :swLat  
AND      longitude > :swLng  
AND      latitude < :neLat  
AND      longitude < :neLng 
GROUP  BY latitude, 
          longitude, 
          spawnpoint_id, 
          time ", [ ':swLat' => $swLat, ':swLng' => $swLng, ':neLat' => $neLat, ':neLng' => $neLng])->fetchAll();
        }

        $spawnpoints = array();
        $spawnpoint_values = array();
        $i = 0;

        foreach ($datas as $row) {
            $key = $row["spawn_id"];
            $count = intval($row["count"]);
            $time = ($row["time"] + 2700) % 3600;

            $p = array();

            if (!array_key_exists($key, $spawnpoints)) {
                $p[$key]["spawnpoint_id"] = $key;
                $p[$key]["latitude"] = floatval($row["lat"]);
                $p[$key]["longitude"] = floatval($row["lon"]);
            } else {
                $p[$key]["special"] = true;
            }

            if (!array_key_exists("time", $p[$key]) || $count >= $p[$key]["count"]) {
                $p[$key]["time"] = $time;
                $p[$key]["count"] = $count;
            }

            $spawnpoints[] = $p;
            $spawnpoint_values[] = $p[$key];

            unset($datas[$i]);

            $i++;
        }

        foreach ($spawnpoint_values as $key => $subArr) {
            unset($subArr['count']);
            $spawnpoint_values[$key] = $subArr;
        }

        return $spawnpoint_values;
    }
}

function get_recent($swLat, $swLng, $neLat, $neLng, $tstamp = 0, $oSwLat = 0, $oSwLng = 0, $oNeLat = 0, $oNeLng = 0)
{
    global $db;

    $datas = array();

    global $map;
    if ($map == "monocle") {

    } else {
        if ($swLat == 0) {
            $datas = $db->query("SELECT latitude, 
       longitude, 
       Unix_timestamp(Convert_tz(last_modified, '+00:00', @@global.time_zone)) 
       AS 
       last_modified 
FROM   scannedlocation 
WHERE  last_modified >= '2017-06-16 15:57:32' 
ORDER  BY last_modified ASC ")->fetchAll();
        } elseif ($tstamp > 0) {
            $date = new DateTime();
            $date->setTimezone(new DateTimeZone('UTC'));
            $date->setTimestamp($tstamp);
            $datas = $db->query("SELECT   latitude, 
         longitude, 
         Unix_timestamp(Convert_tz(last_modified, '+00:00', @@global.time_zone)) AS last_modified
FROM     scannedlocation 
WHERE    last_modified >= :lastModified
AND      latitude > :swLat 
AND      longitude > :swLng
AND      latitude < :neLat 
AND      longitude < :neLng 
ORDER BY last_modified ASC", [':lastModified' => date_format($date, 'y-m-d H:I:s'), ':swLat' => $swLat, ':swLng' => $swLng, ':neLat' => $neLat, ':neLng' => $neLng])->fetchAll();
        } elseif ($oSwLat != 0) {
            $date = new DateTime();
            $date->setTimezone(new DateTimeZone('UTC'));
            $date->sub(new DateInterval('PT15M'));
            $datas = $db->query("SELECT   latitude, 
         longitude, 
         Unix_timestamp(Convert_tz(last_modified, '+00:00', @@global.time_zone)) AS last_modified
FROM     scannedlocation 
WHERE    last_modified >= :lastModified
AND      latitude > :swLat 
AND      longitude > :swLng
AND      latitude < :neLat 
AND      longitude < :neLng 
AND      NOT( latitude >  :oSwLat 
                AND longitude >  :oSwLng
                AND latitude <  :oNeLat
                AND longitude <  :oNeLng ) 
AND      last_modified >= :lastModified
ORDER BY last_modified ASC", [':lastModified' => date_format($date, 'y-m-d H:I:s'), ':swLat' => $swLat, ':swLng' => $swLng, ':neLat' => $neLat, ':neLng' => $neLng,  ':oSwLat' => $oSwLat, ':oSwLng' => $oSwLng, ':oNeLat' => $oNeLat, ':oNeLng' => $oNeLng])->fetchAll();
        } else {
            $date = new DateTime();
            $date->setTimezone(new DateTimeZone('UTC'));
            $date->sub(new DateInterval('PT15M'));
            $datas = $db->query("SELECT   latitude, 
         longitude, 
         Unix_timestamp(Convert_tz(last_modified, '+00:00', @@global.time_zone)) AS last_modified
FROM     scannedlocation 
WHERE    last_modified >= :lastModified
AND      latitude > :swLat 
AND      longitude > :swLng
AND      latitude < :neLat 
AND      longitude < :neLng 
ORDER BY last_modified ASC", [':lastModified' => date_format($date, 'y-m-d H:I:s'), ':swLat' => $swLat, ':swLng' => $swLng, ':neLat' => $neLat, ':neLng' => $neLng])->fetchAll();
        }
    }

    $recent = array();
    $i = 0;

    foreach ($datas as $row) {
        $p = array();

        $p["latitude"] = floatval($row["latitude"]);
        $p["longitude"] = floatval($row["longitude"]);

        $lm = $row["last_modified"] * 1000;
        $p["last_modified"] = $lm;

        $recent[] = $p;

        unset($datas[$i]);

        $i++;
    }

    return $recent;
}