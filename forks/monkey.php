<?php

class MonkeyFork {
  public function get_gym($gymId) {
    //Fill in the blank
    return array();
  }

  public function get_gyms($swLat, $swLng, $neLat, $neLng, $tstamp = 0, $oSwLat = 0, $oSwLng = 0, $oNeLat = 0, $oNeLng = 0) {
    global $db;

    // TODO: Should not be doing it here 
    $json_poke = "static/data/pokemon.json";
    $json_contents = file_get_contents($json_poke);
    $pokemon_data = json_decode($json_contents, TRUE);

    $conds = array();
    $params = array();

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
      r.time_battle raid_start,
      r.time_end raid_end,
      r.cp raid_pokemon_cp,
      r.move_1 raid_pokemon_move_1,
      r.move_2 raid_pokemon_move_2
        FROM
        (SELECT f.id,
         f.lat latitude,
         f.lon longitude,
         f.external_id,
         f.name,
         MAX(fs.id) fort_sighting_id,
         MAX(r.id) raid_id
         FROM forts f
         LEFT JOIN fort_sightings fs ON fs.fort_id = f.id
         LEFT JOIN raids r ON r.fort_id = f.id
         WHERE :conditions
         GROUP BY f.id) f
        LEFT JOIN fort_sightings fs ON fs.id = f.fort_sighting_id
        LEFT JOIN raids r ON r.id = f.raid_id";

    $conds[] = "f.lat > :swLat AND f.lon > :swLng AND f.lat < :neLat AND f.lon < :neLng";
    $params[':swLat'] = $swLat;
    $params[':swLng'] = $swLng;
    $params[':neLat'] = $neLat;
    $params[':neLng'] = $neLng;

    if ($swLat == 0) {
      $conds[] = "1 = 0";
    }

    if ($oSwLat != 0) {
      $conds[] = "NOT (f.lat > :oswLat AND f.lon > :oswLng AND f.lat < :oneLat AND f.lon < :oneLng)";
      $params[':oswLat'] = $oSwLat;
      $params[':oswLng'] = $oSwLng;
      $params[':oneLat'] = $oNeLat;
      $params[':oneLng'] = $oNeLng;
    }

    if ($tstamp != 0) {
      $conds[] = "fs.last_modified > :timestamp";
      $params[':timestamp'] = $tstamp;
    }

    $query = str_replace(":conditions", join(" AND ", $conds), $query); 

    $gyms = $db->query($query, $params)->fetchAll(PDO::FETCH_ASSOC);

    $data = array();
    foreach ($gyms as $gym) {
      $gym["enabled"] = true;
      $gym["pokemon"] = [];
      $gym["raid_pokemon_name"] = i8ln($pokemon_data[$gym["raid_pokemon_id"]]["name"]);
      $gym["latitude"] = floatval($gym["latitude"]);
      $gym["longitude"] = floatval($gym["longitude"]);
      $gym["last_modified"] = $gym["last_modified"] * 1000;
      $gym["raid_start"] = $gym["raid_start"] * 1000; // needs to be banged do that raid_start * 1000
      $gym["raid_end"] = $gym["raid_end"] * 1000; // something has to be changed here so that raid_end is *1000
      $data[$gym["gym_id"]] = $gym;
    }
    return $data;
  }
}
