<?php
namespace Scanner;

class RocketMap extends Scanner
{
    public function get_active($swLat, $swLng, $neLat, $neLng, $tstamp = 0, $oSwLat = 0, $oSwLng = 0, $oNeLat = 0, $oNeLng = 0)
    {
        global $db;

        $datas = array();
        global $map;
        $time = new DateTime();
        $time->setTimeZone(new DateTimeZone('UTC'));
        $time->setTimestamp(time());
        if ($swLat == 0) {
            $datas = $db->query("SELECT *, 
       Unix_timestamp(Convert_tz(disappear_time, '+00:00', @@global.time_zone)) AS expire_timestamp,
       latitude                                                                 AS lat, 
       longitude                                                                AS lon, 
       individual_attack                                                        AS atk_iv, 
       individual_defense                                                       AS def_iv, 
       individual_stamina                                                       AS sta_iv, 
       spawnpoint_id                                                            AS spawn_id 
FROM   pokemon 
WHERE  disappear_time > :disappearTime", [':disappearTime' => date_format($time, 'y-m-d H:I:s')])->fetchAll();
        } elseif ($tstamp > 0) {
            $date = new DateTime();
            $date->setTimezone(new DateTimeZone('UTC'));
            $date->setTimestamp($tstamp);
            $datas = $db->query("SELECT *, 
       Unix_timestamp(Convert_tz(disappear_time, '+00:00', @@global.time_zone)) AS expire_timestamp,
       latitude                                                                 AS lat, 
       longitude                                                                AS lon, 
       individual_attack                                                        AS atk_iv, 
       individual_defense                                                       AS def_iv, 
       individual_stamina                                                       AS sta_iv, 
       spawnpoint_id                                                            AS spawn_id 
FROM   pokemon 
WHERE  disappear_time > :disappearTime
AND    last_modified > :lastModified
AND    latitude > :swLat 
AND    longitude > :swLng
AND    latitude < :neLat
AND    longitude < :neLng", [':disappearTime' => date_format($time, 'y-m-d H:I:s'), ':lastModified' => date_format($date, 'y -m-d H:I:s'), ':swLat' => $swLat, ':swLng' => $swLng, ':neLat' => $neLat, ':neLng' => $neLng])->fetchAll();
        } elseif ($oSwLat != 0) {
            $datas = $db->query("SELECT *, 
       Unix_timestamp(Convert_tz(disappear_time, '+00:00', @@global.time_zone)) AS expire_timestamp,
       latitude                                                                 AS lat, 
       longitude                                                                AS lon, 
       individual_attack                                                        AS atk_iv, 
       individual_defense                                                       AS def_iv, 
       individual_stamina                                                       AS sta_iv, 
       spawnpoint_id                                                            AS spawn_id 
FROM   pokemon 
WHERE  disappear_time > :disappearTime
AND    latitude > :swLat
AND    longitude > :swLng 
AND    latitude < :neLat 
AND    longitude < :neLng 
AND    NOT( 
              latitude > :oSwLat 
       AND    longitude > :oSwLng 
       AND    latitude < :oNeLat 
       AND    longitude < :oNeLng)", [':disappearTime' => date_format($time, 'y-m-d H:I:s'), ':swLat' => $swLat, ':swLng' => $swLng, ':neLat' => $neLat, ':neLng' => $neLng, ':oSwLat' => $oSwLat, ':oSwLng' => $oSwLng, ':oNeLat' => $oNeLat, ':oNeLng' => $oNeLng])->fetchAll();
        } else {
            $datas = $db->query("SELECT *, 
       Unix_timestamp(Convert_tz(disappear_time, '+00:00', @@global.time_zone)) AS expire_timestamp,
       latitude                                                                 AS lat, 
       longitude                                                                AS lon, 
       individual_attack                                                        AS atk_iv, 
       individual_defense                                                       AS def_iv, 
       individual_stamina                                                       AS sta_iv, 
       spawnpoint_id                                                            AS spawn_id 
FROM   pokemon 
WHERE  disappear_time > :disappearTime
AND    latitude > :swLat
AND    longitude > :swLng 
AND    latitude < :neLat 
AND    longitude < :neLng", [':disappearTime' => date_format($time, 'y-m-d H:I:s'), ':swLat' => $swLat, ':swLng' => $swLng, ':neLat' => $neLat, ':neLng' => $neLng])->fetchAll();
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

            $time = new DateTime();
            $time->setTimeZone(new DateTimeZone('UTC'));
            $time->setTimestamp(time());
            if ($swLat == 0) {
                $datas = $db->query("SELECT *, 
       Unix_timestamp(Convert_tz(disappear_time, '+00:00', @@global.time_zone)) AS expire_timestamp,
       latitude                                                                 AS lat, 
       longitude                                                                AS lon, 
       individual_attack                                                        AS atk_iv, 
       individual_defense                                                       AS def_iv, 
       individual_stamina                                                       AS sta_iv, 
       spawnpoint_id                                                            AS spawn_id 
FROM   pokemon 
WHERE  disappear_time > :disappearTime
AND    pokemon_id  IN ( $pkmn_in )", array_merge($pkmn_ids, [':disappearTime' => date_format($time, 'y-m-d H:I:s')]))->fetchAll();
            } else {
                $datas = $db->query("SELECT *, 
       Unix_timestamp(Convert_tz(disappear_time, '+00:00', @@global.time_zone)) AS expire_timestamp,
       latitude                                                                 AS lat, 
       longitude                                                                AS lon, 
       individual_attack                                                        AS atk_iv, 
       individual_defense                                                       AS def_iv, 
       individual_stamina                                                       AS sta_iv, 
       spawnpoint_id                                                            AS spawn_id 
FROM   pokemon 
WHERE  disappear_time > :disappearTime
AND    pokemon_id  IN ( $pkmn_in )
AND    latitude > :swLat
AND    longitude > :swLng 
AND    latitude < :neLat 
AND    longitude < :neLng", array_merge($pkmn_ids, [':disappearTime' => date_format($time, 'y-m-d H:I:s'), ':swLat' => $swLat, ':swLng' => $swLng, ':neLat' => $neLat, ':neLng' => $neLng]))->fetchAll();
            }
        $pokemons = $this->returnPokemon($datas);
    }


    public function get_stops($swLat, $swLng, $neLat, $neLng, $tstamp = 0, $oSwLat = 0, $oSwLng = 0, $oNeLat = 0, $oNeLng = 0, $lured = false)
    {

        global $db;

        $datas = array();
            if ($swLat == 0) {
                $datas = $db->query("SELECT active_fort_modifier, 
       enabled, 
       Unix_timestamp(Convert_tz(last_modified, '+00:00', @@global.time_zone)) 
       AS 
       last_modified, 
       Unix_timestamp(Convert_tz(lure_expiration, '+00:00', @@global.time_zone)) 
       AS 
       lure_expiration, 
       pokestop_id 
       AS external_id, 
       latitude 
       AS lat, 
       longitude 
       AS lon 
FROM   pokestop ")->fetchAll();
            } elseif ($tstamp > 0 && $lured == "true") {
                $date = new DateTime();
                $date->setTimezone(new DateTimeZone('UTC'));
                $date->setTimestamp($tstamp);
                $datas = $db->query("SELECT active_fort_modifier, 
       enabled, 
       Unix_timestamp(Convert_tz(last_modified, '+00:00', @@global.time_zone))   AS last_modified,
       Unix_timestamp(Convert_tz(lure_expiration, '+00:00', @@global.time_zone)) AS lure_expiration,
       pokestop_id                                                               AS external_id,
       latitude                                                                  AS lat, 
       longitude                                                                 AS lon 
FROM   pokestop 
WHERE  last_updated > :lastUpdated
AND    active_fort_modifier IS NOT NULL 
AND    latitude > :swLat 
AND    longitude > :swLng 
AND    latitude < :neLat
AND    longitude < :neLng", [':lastUpdated' => date_format($date, 'y-m-d H:I:s'), ':swLat' => $swLat, ':swLng' => $swLng, ':neLat' => $neLat, ':neLng' => $neLng])->fetchAll();
            } elseif ($tstamp > 0) {
                $date = new DateTime();
                $date->setTimezone(new DateTimeZone('UTC'));
                $date->setTimestamp($tstamp);
                $datas = $db->query("SELECT active_fort_modifier, 
       enabled, 
       Unix_timestamp(Convert_tz(last_modified, '+00:00', @@global.time_zone))   AS last_modified,
       Unix_timestamp(Convert_tz(lure_expiration, '+00:00', @@global.time_zone)) AS lure_expiration,
       pokestop_id                                                               AS external_id,
       latitude                                                                  AS lat, 
       longitude                                                                 AS lon 
FROM   pokestop 
WHERE  last_updated > :lastUpdated
AND    latitude > :swLat
AND    longitude > :swLng 
AND    latitude < :neLat  
AND    longitude < :neLng", [':lastUpdated' => date_format($date, 'y-m-d H:I:s'), ':swLat' => $swLat, ':swLng' => $swLng, ':neLat' => $neLat, ':neLng' => $neLng])->fetchAll();
            } elseif ($oSwLat != 0 && $lured == "true") {
                $datas = $db->query("SELECT active_fort_modifier, 
       enabled, 
       Unix_timestamp(Convert_tz(last_modified, '+00:00', @@global.time_zone)) 
       AS 
       last_modified, 
       Unix_timestamp(Convert_tz(lure_expiration, '+00:00', @@global.time_zone)) 
       AS 
       lure_expiration, 
       pokestop_id 
       AS external_id, 
       latitude 
       AS lat, 
       longitude 
       AS lon 
FROM   pokestop 
WHERE  active_fort_modifier IS NOT NULL 
       AND ( latitude > :swLat
             AND longitude > :swLng
             AND latitude < :neLat 
             AND longitude < :neLng ) 
       AND NOT( latitude > :oSwLat
                AND longitude > :oSwLng 
                AND latitude < :oNeLat 
                AND longitude < :oNeLng ) 
       AND active_fort_modifier IS NOT NULL", [':swLat' => $swLat, ':swLng' => $swLng, ':neLat' => $neLat, ':neLng' => $neLng,  ':oSwLat' => $oSwLat, ':oSwLng' => $oSwLng, ':oNeLat' => $oNeLat, ':oNeLng' => $oNeLng])->fetchAll();
            } elseif ($oSwLat != 0) {
                $datas = $db->query("SELECT active_fort_modifier, 
       enabled, 
       Unix_timestamp(Convert_tz(last_modified, '+00:00', @@global.time_zone)) 
       AS 
       last_modified, 
       Unix_timestamp(Convert_tz(lure_expiration, '+00:00', @@global.time_zone)) 
       AS 
       lure_expiration, 
       pokestop_id 
       AS external_id, 
       latitude 
       AS lat, 
       longitude 
       AS lon 
FROM   pokestop 
WHERE  latitude > :swLat
       AND longitude > :swLng 
       AND latitude < :neLat 
       AND longitude < :neLng 
       AND NOT( latitude > :oSwLat 
                AND longitude > :oSwLng 
                AND latitude < :oNeLat 
                AND longitude < :oNeLng ) ", [':swLat' => $swLat, ':swLng' => $swLng, ':neLat' => $neLat, ':neLng' => $neLng,  ':oSwLat' => $oSwLat, ':oSwLng' => $oSwLng, ':oNeLat' => $oNeLat, ':oNeLng' => $oNeLng])->fetchAll();
            } elseif ($lured == "true") {
                $datas = $db->query("SELECT active_fort_modifier, 
       enabled, 
       Unix_timestamp(Convert_tz(last_modified, '+00:00', @@global.time_zone))   AS last_modified,
       Unix_timestamp(Convert_tz(lure_expiration, '+00:00', @@global.time_zone)) AS lure_expiration,
       pokestop_id                                                               AS external_id,
       latitude                                                                  AS lat, 
       longitude                                                                 AS lon 
FROM   pokestop 
WHERE  active_fort_modifier IS NOT NULL 
AND    latitude > :swLat 
AND    longitude > :swLng
AND    latitude < :neLat
AND    longitude < :neLng", [':swLat' => $swLat, ':swLng' => $swLng, ':neLat' => $neLat, ':neLng' => $neLng])->fetchAll();
            } else {
                $datas = $db->query("SELECT active_fort_modifier, 
       enabled, 
       Unix_timestamp(Convert_tz(last_modified, '+00:00', @@global.time_zone))   AS last_modified,
       Unix_timestamp(Convert_tz(lure_expiration, '+00:00', @@global.time_zone)) AS lure_expiration,
       pokestop_id                                                               AS external_id,
       latitude                                                                  AS lat, 
       longitude                                                                 AS lon 
FROM   pokestop 
WHERE  latitude > :swLat
AND    longitude > :swLng
AND    latitude < :neLat
AND    longitude < :neLng", [':swLat' => $swLat, ':swLng' => $swLng, ':neLat' => $neLat, ':neLng' => $neLng])->fetchAll();
            }

        $i = 0;

        return $this->returnPokestops($datas);
    }


    public function get_gyms($swLat, $swLng, $neLat, $neLng, $tstamp = 0, $oSwLat = 0, $oSwLng = 0, $oNeLat = 0, $oNeLng = 0)
    {

        global $db;

        $datas = array();

        global $map;
            global $fork;
                if ($swLat == 0) {
                    $datas = $db->query("SELECT gym.gym_id 
       AS 
       external_id, 
       latitude 
       AS lat, 
       longitude 
       AS lon, 
       guard_pokemon_id, 
       slots_available, 
       total_cp, 
       Unix_timestamp(Convert_tz(last_modified, '+00:00', @@global.time_zone)) 
       AS 
       last_modified, 
       Unix_timestamp(Convert_tz(gym.last_scanned, '+00:00', 
       @@global.time_zone)) AS 
       last_scanned, 
       team_id 
       AS team, 
       enabled, 
       name, 
       level, 
       pokemon_id, 
       cp, 
       move_1, 
       move_2, 
       Unix_timestamp(Convert_tz(start, '+00:00', @@global.time_zone)) 
       AS raid_start, 
       Unix_timestamp(Convert_tz(end, '+00:00', @@global.time_zone)) 
       AS raid_end 
FROM   gym 
       LEFT JOIN gymdetails 
              ON gym.gym_id = gymdetails.gym_id 
       LEFT JOIN raid 
              ON gym.gym_id = raid.gym_id ")->fetchAll();
                } elseif ($tstamp > 0) {
                    $date = new DateTime();
                    $date->setTimezone(new DateTimeZone('UTC'));
                    $date->setTimestamp($tstamp);
                    $datas = $db->query("SELECT    gym.gym_id AS external_id, 
          latitude   AS lat, 
          longitude  AS lon, 
          guard_pokemon_id, 
          slots_available, 
          total_cp, 
          Unix_timestamp(Convert_tz(last_modified, '+00:00', @@global.time_zone))    AS last_modified,
          Unix_timestamp(Convert_tz(gym.last_scanned, '+00:00', @@global.time_zone)) AS last_scanned,
          team_id                                                                    AS team,
          enabled, 
          name, 
          level, 
          pokemon_id, 
          cp, 
          move_1, 
          move_2, 
          Unix_timestamp(Convert_tz(start, '+00:00', @@global.time_zone)) AS raid_start, 
          Unix_timestamp(Convert_tz(end, '+00:00', @@global.time_zone)) AS raid_end 
FROM      gym 
LEFT JOIN gymdetails 
ON        gym.gym_id = gymdetails.gym_id 
LEFT JOIN raid 
ON        gym.gym_id = raid.gym_id 
WHERE     gym.last_scanned > '" . date_format($date, 'y-m-d H:I:s') . "' 
AND       latitude > :swLat
AND       longitude > :swLat
AND       latitude < :neLat
AND       longitude < :neLng",[':swLat' => $swLat, ':swLng' => $swLng, ':neLat' => $neLat, ':neLng' => $neLng])->fetchAll();
                } elseif ($oSwLat != 0) {
                    $datas = $db->query("SELECT gym.gym_id 
       AS 
       external_id, 
       latitude 
       AS lat, 
       longitude 
       AS lon, 
       guard_pokemon_id, 
       slots_available, 
       total_cp, 
       Unix_timestamp(Convert_tz(last_modified, '+00:00', @@global.time_zone)) 
       AS 
       last_modified, 
       Unix_timestamp(Convert_tz(gym.last_scanned, '+00:00', 
       @@global.time_zone)) AS 
       last_scanned, 
       team_id 
       AS team, 
       enabled, 
       name, 
       level, 
       pokemon_id, 
       cp, 
       move_1, 
       move_2, 
       Unix_timestamp(Convert_tz(start, '+00:00', @@global.time_zone)) 
       AS raid_start, 
       Unix_timestamp(Convert_tz(end, '+00:00', @@global.time_zone)) 
       AS raid_end 
FROM   gym 
       LEFT JOIN gymdetails 
              ON gym.gym_id = gymdetails.gym_id 
       LEFT JOIN raid 
              ON gym.gym_id = raid.gym_id 
WHERE  latitude > :swLat
       AND longitude > :swLng
       AND latitude < :neLat 
       AND longitude < :neLng 
       AND NOT( latitude > :oSwLat 
                AND longitude > :oSwLng 
                AND latitude < :oNeLat 
                AND longitude < :oNeLng )", [':swLat' => $swLat, ':swLng' => $swLng, ':neLat' => $neLat, ':neLng' => $neLng,  ':oSwLat' => $oSwLat, ':oSwLng' => $oSwLng, ':oNeLat' => $oNeLat, ':oNeLng' => $oNeLng])->fetchAll();
                } else {
                    $datas = $db->query("SELECT    gym.gym_id AS external_id, 
          latitude   AS lat, 
          longitude  AS lon, 
          guard_pokemon_id, 
          slots_available, 
          total_cp, 
          Unix_timestamp(Convert_tz(last_modified, '+00:00', @@global.time_zone))    AS last_modified,
          Unix_timestamp(Convert_tz(gym.last_scanned, '+00:00', @@global.time_zone)) AS last_scanned,
          team_id                                                                    AS team,
          enabled, 
          name, 
          level, 
          pokemon_id, 
          cp, 
          move_1, 
          move_2, 
          Unix_timestamp(Convert_tz(start, '+00:00', @@global.time_zone)) AS raid_start, 
          Unix_timestamp(Convert_tz( 
end, '+00:00', @@global.time_zone)) AS raid_end 
FROM      gym 
LEFT JOIN gymdetails 
ON        gym.gym_id = gymdetails.gym_id 
LEFT JOIN raid 
ON        gym.gym_id = raid.gym_id 
WHERE     latitude > :swLat 
AND       longitude > :swLng 
AND       latitude < :neLat 
AND       longitude < :neLng",[':swLat' => $swLat, ':swLng' => $swLng, ':neLat' => $neLat, ':neLng' => $neLng])->fetchAll();
                }

        $gyminfo = $this->returnGyms($datas);
        $gyms = $gyminfo['gyms'];
        $gym_ids = $gyminfo['gym_ids'];

        $j = 0;
// todo: up to here.
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


        return $gyms;
    }
}