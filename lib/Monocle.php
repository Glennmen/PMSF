<?php
namespace Scanner;

class Monocle extends Scanner
{
    function get_active($swLat, $swLng, $neLat, $neLng, $tstamp = 0, $oSwLat = 0, $oSwLng = 0, $oNeLat = 0, $oNeLng = 0)
    {
        global $db;

        $datas = array();
        global $map;
        if ($swLat == 0) {
            $datas = $db->query("SELECT * FROM sightings WHERE expire_timestamp > :time", [':time'=> time()])->fetchAll();
        } elseif ($tstamp > 0) {
            $datas = $db->query("SELECT * 
FROM   sightings 
WHERE  expire_timestamp > :time 
AND    lat > :swLat 
AND    lon > :swLng 
AND    lat < :neLat 
AND    lon < :neLng", [':time' => time(), ':swLat' => $swLat, ':swLng' => $swLng, ':neLat' => $neLat, ':neLng' => $neLng])->fetchAll();
        } elseif ($oSwLat != 0) {
            $datas = $db->query("SELECT * 
FROM   sightings 
WHERE  expire_timestamp > :time 
   AND lat > :swLat
   AND lon > :swLng 
   AND lat < :neLat 
   AND lon < :neLng 
   AND NOT( lat > :oSwLat 
            AND lon > :oSwLng 
            AND lat < :oNeLat 
            AND lon < :oNeLng ) " , [':time' => time(), ':swLat' => $swLat, ':swLng' => $swLng, ':neLat' => $neLat, ':neLng' => $neLng, ':oSwLat' => $oSwLat, ':oSwLng' => $oSwLng, ':oNeLat' => $oNeLat, ':oNeLng' => $oNeLng])->fetchAll();
        } else {

            $datas = $db->query("SELECT * 
FROM   sightings 
WHERE  expire_timestamp > :time 
AND    lat > :swLat 
AND    lon > :swLng 
AND    lat < :neLat 
AND    lon < :neLng", [':time' => time(), ':swLat' => $swLat, ':swLng' => $swLng, ':neLat' => $neLat, ':neLng' => $neLng])->fetchAll();
        }
        return $this->returnPokemon($datas);
    }

    public function get_active_by_id($ids, $swLat, $swLng, $neLat, $neLng)
    {
        global $db;

        $datas = array();
        global $map;
        $pkmn_in = '';
        if (count($ids)) {
            $i=1;
            foreach ($ids as $id) {
                $pkmn_ids[':qry_'.$i] = $id;
                $pkmn_in .= ':'.'qry_'.$i.",";
                $i++;
            }
            $pkmn_in = substr($pkmn_in, 0, -1);
        } else {
            $pkmn_ids = [];
        }

            if ($swLat == 0) {
                $datas = $db->query("SELECT * 
FROM   sightings 
WHERE  `expire_timestamp` > :time
       AND pokemon_id IN ( $pkmn_in ) ", array_merge($pkmn_ids, [':time'=>time()]))->fetchAll();
            } else {
                $datas = $db->query("SELECT * 
FROM   sightings 
WHERE  expire_timestamp > :timeStamp
AND    pokemon_id IN ( $pkmn_in ) 
AND    lat > :swLat 
AND    lon > :swLng
AND    lat < :neLat
AND    lon < :neLng", array_merge($pkmn_ids, [':timeStamp'=> time(), ':swLat' => $swLat, ':swLng' => $swLng, ':neLat' => $neLat, ':neLng' => $neLng]))->fetchAll();
            }

        $pokemons = $this->returnPokemon($datas);

    }


    public function get_stops($swLat, $swLng, $neLat, $neLng, $tstamp = 0, $oSwLat = 0, $oSwLng = 0, $oNeLat = 0, $oNeLng = 0, $lured = false)
    {

        global $db;

        $datas = array();
        global $map;
            if ($swLat == 0) {
                $datas = $db->query("SELECT external_id, lat, lon FROM pokestops")->fetchAll();
            } elseif ($tstamp > 0) {
                $datas = $db->query("SELECT external_id, 
       lat, 
       lon 
FROM   pokestops 
WHERE  lat > :swLat 
AND    lon > :swLng 
AND    lat < :neLat 
AND    lon < :neLng", [':swLat' => $swLat, ':swLng' => $swLng, ':neLat' => $neLat, ':neLng' => $neLng])->fetchAll();
            } elseif ($oSwLat != 0) {
                $datas = $db->query("SELECT external_id, 
       lat, 
       lon 
FROM   pokestops 
WHERE  lat > :swLat
       AND lon > :swLng 
       AND lat < :neLat 
       AND lon < :neLng
       AND NOT( lat > :oSwLat 
                AND lon > :oSwLng 
                AND lat < :oNeLat 
                AND lon < :oNeLng ) ", [':swLat' => $swLat, ':swLng' => $swLng, ':neLat' => $neLat, ':neLng' => $neLng,  ':oSwLat' => $oSwLat, ':oSwLng' => $oSwLng, ':oNeLat' => $oNeLat, ':oNeLng' => $oNeLng])->fetchAll();
            } else {
                $datas = $db->query("SELECT external_id, 
       lat, 
       lon 
FROM   pokestops 
WHERE  lat > :swLat 
AND    lon > :swLng 
AND    lat < :neLat 
AND    lon < :neLng", [':swLat' => $swLat, ':swLng' => $swLng, ':neLat' => $neLat, ':neLng' => $neLng])->fetchAll();
            }

        return $this->returnPokestops($datas);
    }

    public function get_gyms($swLat, $swLng, $neLat, $neLng, $tstamp = 0, $oSwLat = 0, $oSwLng = 0, $oNeLat = 0, $oNeLng = 0)
    {

        global $db;

        $datas = array();

        global $map;
            if ($swLat == 0) {
                $datas = $db->query("SELECT t3.external_id, 
       t3.lat, 
       t3.lon, 
       t1.last_modified, 
       t1.team, 
       t1.slots_available, 
       t1.guard_pokemon_id 
FROM   (SELECT fort_id, 
               Max(last_modified) AS MaxLastModified 
        FROM   fort_sightings 
        GROUP  BY fort_id) t2 
       LEFT JOIN fort_sightings t1 
              ON t2.fort_id = t1.fort_id 
                 AND t2.maxlastmodified = t1.last_modified 
       LEFT JOIN forts t3 
              ON t1.fort_id = t3.id")->fetchAll();
            } elseif ($tstamp > 0) {
                $datas = $db->query("SELECT t3.external_id, 
       t3.lat, 
       t3.lon, 
       t1.last_modified, 
       t1.team, 
       t1.slots_available, 
       t1.guard_pokemon_id 
FROM   (SELECT fort_id, 
               Max(last_modified) AS MaxLastModified 
        FROM   fort_sightings 
        GROUP  BY fort_id) t2 
       LEFT JOIN fort_sightings t1 
              ON t2.fort_id = t1.fort_id 
                 AND t2.maxlastmodified = t1.last_modified 
       LEFT JOIN forts t3 
              ON t1.fort_id = t3.id 
WHERE  t3.lat > :swLat 
       AND t3.lon > :swLng 
       AND t3.lat < :neLat 
       AND t3.lon < :neLng",[':swLat' => $swLat, ':swLng' => $swLng, ':neLat' => $neLat, ':neLng' => $neLng])->fetchAll();
            } elseif ($oSwLat != 0) {
                $datas = $db->query("SELECT t3.external_id, 
       t3.lat, 
       t3.lon, 
       t1.last_modified, 
       t1.team, 
       t1.slots_available, 
       t1.guard_pokemon_id 
FROM   (SELECT fort_id, 
               Max(last_modified) AS MaxLastModified 
        FROM   fort_sightings 
        GROUP  BY fort_id) t2 
       LEFT JOIN fort_sightings t1 
              ON t2.fort_id = t1.fort_id 
                 AND t2.maxlastmodified = t1.last_modified 
       LEFT JOIN forts t3 
              ON t1.fort_id = t3.id 
WHERE  t3.lat > :swLat 
       AND t3.lon > :swLng
       AND t3.lat < :neLat
       AND t3.lon < :neLng
       AND NOT( t3.lat > :oSwLat
                AND t3.lon > :oSwLng
                AND t3.lat < :oNeLat
                AND t3.lon < :oNeLng)", [':swLat' => $swLat, ':swLng' => $swLng, ':neLat' => $neLat, ':neLng' => $neLng,  ':oSwLat' => $oSwLat, ':oSwLng' => $oSwLng, ':oNeLat' => $oNeLat, ':oNeLng' => $oNeLng])->fetchAll();
            } else {
                $datas = $db->query("SELECT    t3.external_id, 
          t3.lat, 
          t3.lon, 
          t1.last_modified, 
          t1.team, 
          t1.slots_available, 
          t1.guard_pokemon_id 
FROM      ( 
                   SELECT   fort_id, 
                            Max(last_modified) AS maxlastmodified 
                   FROM     fort_sightings 
                   GROUP BY fort_id) t2 
LEFT JOIN fort_sightings t1 
ON        t2.fort_id = t1.fort_id 
AND       t2.maxlastmodified = t1.last_modified 
LEFT JOIN forts t3 
ON        t1.fort_id = t3.id 
WHERE     t3.lat > :swLat
AND       t3.lon > :swLng 
AND       t3.lat < :neLat 
AND       t3.lon < :neLng",[':swLat' => $swLat, ':swLng' => $swLng, ':neLat' => $neLat, ':neLng' => $neLng])->fetchAll();
            }



        $gyminfo = $this->returnGyms($datas);
        $gyms = $gyminfo['gyms'];
        $gym_ids = $gyminfo['gym_ids'];

// todo: up to here.

        $j = 0;




        if ($map != "monocle") {
            $gym_in = '';
            if (count($gym_ids)) {
                $i=1;
                foreach ($gym_ids as $id) {
                    $gym_qry_ids[':qry_'.$i] = $id;
                    $gym_in .= ':'.'qry_'.$i.",";
                    $i++;
                }
                $gym_in = substr($gym_in, 0, -1);
            } else {
                $gym_qry_ids = [];
            }
            $pokemons = $db->query("SELECT gymmember.gym_id, 
       pokemon_id, 
       cp, 
       trainer.name, 
       trainer.level 
FROM   gymmember 
       JOIN gympokemon 
         ON gymmember.pokemon_uid = gympokemon.pokemon_uid 
       JOIN trainer 
         ON gympokemon.trainer_name = trainer.name 
       JOIN gym 
         ON gym.gym_id = gymmember.gym_id 
WHERE  gymmember.last_scanned > gym.last_modified 
       AND gymmember.gym_id IN ( $gym_in ) 
GROUP  BY name 
ORDER  BY gymmember.gym_id, 
          gympokemon.cp ", $gym_qry_ids)->fetchAll();

            foreach ($pokemons as $pokemon) {
                $p = array();

                $pid = $pokemon["pokemon_id"];

                $p["pokemon_id"] = $pid;
                $p["pokemon_name"] = $data[$pid]['name'];
                $p["trainer_name"] = $pokemon["name"];
                $p["trainer_level"] = $pokemon["level"];
                $p["pokemon_cp"] = $pokemon["cp"];

                $gyms[$pokemon["gym_id"]]["pokemon"][] = $p;

                unset($pokemons[$j]);

                $j++;
            }
        } else {
            global $fork;
            $gyms_in = '';
            if (count($gym_ids)) {
                $i=1;
                foreach ($gym_ids as $id) {
                    $gym_in_ids[':qry_'.$i] = $id;
                    $gyms_in .= ':'.'qry_'.$i.",";
                    $i++;
                }
                $gyms_in = substr($gyms_in, 0, -1);
            } else {
                $gym_in_ids = [];
            }
            if ($fork != "asner")
                $raids = $db->query("SELECT t1.fort_id, 
       level, 
       pokemon_id, 
       time_battle AS raid_start, 
       time_end    AS raid_end 
FROM   (SELECT fort_id, 
               Max(time_end) AS MaxTimeEnd 
        FROM   raids 
        GROUP  BY fort_id) t1 
       LEFT JOIN raids t2 
              ON t1.fort_id = t2.fort_id 
                 AND maxtimeend = time_end 
WHERE  t1.fort_id IN ( $gyms_in ) ", $gym_in_ids)->fetchAll();
            else
                $raids = $db->query("SELECT t3.external_id, 
       t1.fort_id, 
       raid_level AS level, 
       pokemon_id, 
       cp, 
       move_1, 
       move_2, 
       raid_start, 
       raid_end 
FROM   (SELECT fort_id, 
               Max(raid_end) AS MaxTimeEnd 
        FROM   raid_info 
        GROUP  BY fort_id) t1 
       LEFT JOIN raid_info t2 
              ON t1.fort_id = t2.fort_id 
                 AND maxtimeend = raid_end 
       JOIN forts t3 
         ON t2.fort_id = t3.id 
WHERE  t3.external_id IN ( $gyms_in ) ", $gym_in_ids)->fetchAll();

            foreach ($raids as $raid) {
                if ($fork != "asner")
                    $id = $raid["fort_id"];
                else
                    $id = $raid["external_id"];

                $rpid = intval($raid['pokemon_id']);
                $gyms[$id]['raid_level'] = intval($raid['level']);
                if ($rpid)
                    $gyms[$id]['raid_pokemon_id'] = $rpid;
                if ($rpid)
                    $gyms[$id]['raid_pokemon_name'] = i8ln($data[$rpid]['name']);
                $gyms[$id]['raid_pokemon_cp'] = !empty($raid['cp']) ? intval($raid['cp']) : null;
                $gyms[$id]['raid_pokemon_move_1'] = !empty($raid['move_1']) ? intval($raid['move_1']) : null;
                $gyms[$id]['raid_pokemon_move_2'] = !empty($raid['move_2']) ? intval($raid['move_2']) : null;
                $gyms[$id]['raid_start'] = $raid["raid_start"] * 1000;
                $gyms[$id]['raid_end'] = $raid["raid_end"] * 1000;

                unset($raids[$j]);

                $j++;
            }
        }

        return $gyms;
    }
}