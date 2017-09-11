<!DOCTYPE html>
<html>
<head>
<style>
table {
    font-family: arial, sans-serif;
    border-collapse: collapse;
    width: 100%;
}

td, th {
    border: 1px solid #dddddd;
    text-align: left;
    padding: 8px;
}

tr:nth-child(even) {
    background-color: #dddddd;
}
</style>
</head>
<body>

<?php

$str = file_get_contents('static/data/pokemon.json');
$json = json_decode($str, true);

$dbIP = "127.0.0.1";
$dbUser = "root";
$dbPwd = "toor";
$dbName = "monocle";

$mysqli = new mysqli($dbIP, $dbUser, $dbPwd, $dbName);
if ($mysqli->connect_errno) {
	echo "Error: Failed to make a MySQL connection, here is why: \n";
	echo "Errno: " . $mysqli->connect_errno . "\n";
	echo "Error: " . $mysqli->connect_error . "\n";
	exit;
}

//Sightings you can find if shadowbanned
$shadowBannedArray = array("16", "19", "23", "27", "41", "43", "46", "52", "54", "60", "69", "72", "74", "77", "81", "98", "118", "120", "129", "161", "165", "167", "177", "183", "187");

//Get all users from sightings table
$sqlUsers = "SELECT DISTINCT(username) FROM `sighting_users`";
$resultUsers = $mysqli->query($sqlUsers);
while ($row = $resultUsers->fetch_assoc()){
	
  //Get last 40 sightings per worker
	$sqlSightings = "SELECT sightings.pokemon_id,
							sightings.expire_timestamp
					FROM `sighting_users`
					LEFT JOIN sightings ON sighting_users.sighting_id=sightings.id
					WHERE username='{$row['username']}' AND sightings.pokemon_id IS NOT NULL
					ORDER BY sightings.id DESC
                    LIMIT 40";

	$resultSightings = $mysqli->query($sqlSightings);
	$count = 0;
	if($resultSightings->num_rows > 39){
		
		while ($row2 = $resultSightings->fetch_assoc()) {
			
			if(in_array($row2['pokemon_id'], $shadowBannedArray)){
				$count++;
			}
			
      //if the last 40 sightings has only been common, mark account as shadowbanned
			if($count > 39){
				$sStatus[$row['username']] = "TRUE";
			} else {
				$sStatus[$row['username']] = "FALSE";
			}
			
      //get last sightings per worker
			$sUserLastSighting[$row['username']] = $row2['pokemon_id'];
			$sUserLastSightingTimestamp[$row['username']] = $row2['expire_timestamp'];
			
		}
		
	} else {
    //the worker has less than 40 known sightings
		$sStatus[$row['username']] = "Not enough data";
	}
}

//Get all users from sightings table
$sqlUsers = "SELECT DISTINCT(username) FROM `sighting_users`";
$resultUsers = $mysqli->query($sqlUsers);
echo "<table>
		<tr>
			<th>Username</th>
			<th>Shadowbanned</th>
			<th>Last sighting pokémon</th>
			<th>Last sighting expires at</th>
		</tr>";
while ($row = $resultUsers->fetch_assoc()){
	echo "<tr>"
		. "<td>" . $row['username'] . "</td>"
		. "<td>" . $sStatus[$row['username']] . "</td>"
		. "<td>" . $json[$sUserLastSighting[$row['username']]]['name'] . "</td>"
		. "<td>" . date("Y-m-d H:i:s", $sUserLastSightingTimestamp[$row['username']]) . "</td>"
		. "</tr>";
}
echo "</table>";
