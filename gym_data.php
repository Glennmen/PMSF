<?php
include('config/config.php');
include('utils.php');

if (!isset($_GET['id'])) {
    http_response_code(400);
    die();
}

$id = $_GET['id'];

global $db;

global $map;
if ($map == "monocle") {
    $row = $db->query("select t3.external_id, t3.lat, t3.lon, t1.last_modified, t1.team, t1.slots_available, t1.guard_pokemon_id from (select fort_id, MAX(last_modified) AS MaxLastModified from fort_sightings group by fort_id) t2 left join fort_sightings t1 on t2.fort_id = t1.fort_id and t2.MaxLastModified = t1.last_modified left join forts t3 on t1.fort_id = t3.id where t3.external_id = '" . $id . "'")->fetch();
} else {
    $row = $db->query("select gym.gym_id as external_id, latitude as lat, longitude as lon, guard_pokemon_id, slots_available, UNIX_TIMESTAMP(CONVERT_TZ(last_modified, '+00:00', @@global.time_zone)) as last_modified, UNIX_TIMESTAMP(CONVERT_TZ(gym.last_scanned, '+00:00', @@global.time_zone)) as last_scanned, team_id as team, enabled, name, level, pokemon_id, cp, move_1, move_2, UNIX_TIMESTAMP(CONVERT_TZ(start, '+00:00', @@global.time_zone)) as raid_start, UNIX_TIMESTAMP(CONVERT_TZ(end, '+00:00', @@global.time_zone)) as raid_end from gym left join gymdetails on gym.gym_id = gymdetails.gym_id left join raid on gym.gym_id = raid.gym_id where gym.gym_id = '" . $id . "'")->fetch();
}

$json_poke = "static/data/pokemon.json";
$json_contents = file_get_contents($json_poke);
$data = json_decode($json_contents, TRUE);

$p = array();

$lat = floatval($row["lat"]);
$lon = floatval($row["lon"]);
$gpid = intval($row["guard_pokemon_id"]);
$sa = intval($row["slots_available"]);
$lm = $row["last_modified"] * 1000;
$ls = isset($row["last_scanned"]) ? $row["last_scanned"] * 1000 : null;
$ti = isset($row["team"]) ? intval($row["team"]) : null;

$p["enabled"] = isset($row["enabled"]) ? boolval($row["enabled"]) : true;
$p["guard_pokemon_id"] = $gpid;
$p["gym_id"] = $row["external_id"];
$p["slots_available"] = $sa;
$p["last_modified"] = $lm;
$p["last_scanned"] = $ls;
$p["latitude"] = $lat;
$p["longitude"] = $lon;
$p["name"] = isset($row["name"]) ? $row["name"] : null;
$p["team_id"] = $ti;
if ($gpid)
    $p["guard_pokemon_name"] = i8ln($data[$gpid]['name']);

if ($map != "monocle") {
    $rpid = intval($row['pokemon_id']);
    $p['raid_level'] = intval($row['level']);
    if ($rpid)
        $p['raid_pokemon_id'] = $rpid;
    if ($rpid)
        $p['raid_pokemon_name'] = i8ln($data[$rpid]['name']);
    $p['raid_pokemon_cp'] = isset($row['cp']) ? intval($row['cp']) : null;
    $p['raid_pokemon_move_1'] = isset($row['move_1']) ? intval($row['move_1']) : null;
    $p['raid_pokemon_move_2'] = isset($row['move_2']) ? intval($row['move_2']) : null;
    $p['raid_start'] = $row["raid_start"] * 1000;
    $p['raid_end'] = $row["raid_end"] * 1000;
}

unset($row);

$j = 0;
if ($map != "monocle") {
    $json_moves = "static/data/moves.json";
    $json_contents = file_get_contents($json_moves);
    $moves = json_decode($json_contents, TRUE);

    $pokemons = $db->query("select gymmember.gym_id, pokemon_id, cp, trainer.name, trainer.level, move_1, move_2, iv_attack, iv_defense, iv_stamina from gymmember join gympokemon on gymmember.pokemon_uid = gympokemon.pokemon_uid join trainer on gympokemon.trainer_name = trainer.name join gym on gym.gym_id = gymmember.gym_id where gymmember.last_scanned > gym.last_modified and gymmember.gym_id in ('" . $id . "') group by name order by gympokemon.cp desc")->fetchAll();

    foreach ($pokemons as $pokemon) {
        $pid = $pokemon["pokemon_id"];

        $p1 = array();

        $p1["pokemon_id"] = $pid;
        $p1["pokemon_name"] = i8ln($data[$pid]['name']);
        $p1["trainer_name"] = $pokemon["name"];
        $p1["trainer_level"] = $pokemon["level"];
        $p1["pokemon_cp"] = $pokemon["cp"];

        $p1["iv_attack"] = intval($pokemon["iv_attack"]);
        $p1["iv_defense"] = intval($pokemon["iv_defense"]);
        $p1["iv_stamina"] = intval($pokemon["iv_stamina"]);

        $p1['move_1_name'] = i8ln($moves[$pokemon['move_1']]['name']);
        $p1['move_1_damage'] = $moves[$pokemon['move_1']]['damage'];
        $p1['move_1_energy'] = $moves[$pokemon['move_1']]['energy'];
        $p1['move_1_type']['type'] = i8ln($moves[$pokemon['move_1']]['type']);
        $p1['move_1_type']['type_en'] = $moves[$pokemon['move_1']]['type'];

        $p1['move_2_name'] = i8ln($moves[$pokemon['move_2']]['name']);
        $p1['move_2_damage'] = $moves[$pokemon['move_2']]['damage'];
        $p1['move_2_energy'] = $moves[$pokemon['move_2']]['energy'];
        $p1['move_2_type']['type'] = i8ln($moves[$pokemon['move_2']]['type']);
        $p1['move_2_type']['type_en'] = $moves[$pokemon['move_2']]['type'];

        $p['pokemon'][] = $p1;

        unset($pokemons[$j]);

        $j++;
    }
} else {
    global $fork;
    if ($fork != "asner")
        $raid = $db->query("select t1.fort_id, level, pokemon_id, time_battle as raid_start, time_end as raid_end from (select fort_id, MAX(time_end) AS MaxTimeEnd from raids group by fort_id) t1 left join raids t2 on t1.fort_id = t2.fort_id and MaxTimeEnd = time_end where t1.fort_id in ('" . $id . "')")->fetch();
    else
        $raid = $db->query("select t3.external_id, t1.fort_id, raid_level as level, pokemon_id, cp, move_1, move_2, raid_start, raid_end from (select fort_id, MAX(raid_end) AS MaxTimeEnd from raid_info group by fort_id) t1 left join raid_info t2 on t1.fort_id = t2.fort_id and MaxTimeEnd = raid_end join forts t3 on t2.fort_id = t3.id where t3.external_id in ('" . $id . "')")->fetch();

    $rpid = intval($raid['pokemon_id']);
    $p['raid_level'] = intval($raid['level']);
    if ($rpid)
        $p['raid_pokemon_id'] = $rpid;
    if ($rpid)
        $p['raid_pokemon_name'] = i8ln($data[$rpid]['name']);
    $p['raid_pokemon_cp'] = isset($raid['cp']) ? intval($raid['cp']) : null;
    $p['raid_pokemon_move_1'] = isset($raid['move_1']) ? intval($raid['move_1']) : null;
    $p['raid_pokemon_move_2'] = isset($raid['move_2']) ? intval($raid['move_2']) : null;
    $p['raid_start'] = $raid["raid_start"] * 1000;
    $p['raid_end'] = $raid["raid_end"] * 1000;

    unset($raid);
}

echo json_encode($p);