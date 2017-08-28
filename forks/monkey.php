<?php

class MonkeyFork {
  public function get_gyms($swLat, $swLng, $neLat, $neLng, $tstamp = 0, $oSwLat = 0, $oSwLng = 0, $oNeLat = 0, $oNeLng = 0) {
    global $db;

	$query = "SELECT f.external_id as gym_id,
       fs.last_modified last_modified,
       f.latitude,
        f.longitude,
        f.name,
       fs.team team_id,
       fs.guard_pokemon_id,
       fs.slots_available,
       r.level raid_level,
       r.pokemon_id raid_pokemon_id,
       r.time_spawn,
       r.time_battle,
       r.time_end,
       r.cp raid_cp,
       r.move_1 raid_move_1,
       r.move_2 raid_move_2
FROM
  (SELECT f.id,
          f.lat latitude,
          f.lon longitude,
   		f.external_id,
   	    f.name,
          MAX(fs.id) fort_sighting_id
   FROM forts f
LEFT JOIN fort_sightings fs ON fs.fort_id = f.id
   WHERE f.lat > 38.59554697220179
     AND f.lon > -9.5130000327149
     AND f.lat < 38.92002611234014
     AND f.lon < -8.5901484702149
   GROUP BY f.id) f
LEFT JOIN fort_sightings fs ON fs.id = f.fort_sighting_id
LEFT JOIN raids r ON r.fort_id = f.id";

    $gyms = $db->query($query, [])->fetchAll(PDO::FETCH_ASSOC);

$data = array();
    foreach ($gyms as $gym) {
	$gym["enabled"] = true;
	$gym["pokemon"] = [];
	$gym["latitude"] = floatval($gym["latitude"]);
	$gym["longitude"] = floatval($gym["longitude"]);
	$gym["last_modified"] = $gym["last_modified"] * 1000;
	$data[$gym["gym_id"]] = $gym;
    }
	
    return $data;
  }
}
