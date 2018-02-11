<?php

//======================================================================
// DO NOT EDIT THIS FILE!
//======================================================================

//======================================================================
// PMSF - DEFAULT CONFIG FILE
// https://github.com/Glennmen/PMSF
//======================================================================
session_start();
require_once(__DIR__ . '/../utils.php');

$libs[] = "Scanner.php";
$libs[] = "Monocle.php";
$libs[] = "Monocle_Asner.php";
$libs[] = "Monocle_Alternate.php";
$libs[] = "RocketMap.php";
$libs[] = "RocketMap_Sloppy.php";

// Include libraries
foreach ($libs as $file) {
    include(__DIR__ . '/../lib/' . $file);
}
setSessionCsrfToken();

//-----------------------------------------------------
// MAP SETTINGS
//-----------------------------------------------------

/* Location Settings */

$startingLat = 37.7749295;                                          // Starting latitude
$startingLng = -122.4194155;                                        // Starting longitude

/* Anti scrape Settings */

$maxLatLng = 1;                                                     // Max latitude and longitude size (1 = ~110km, 0 to disable)
$maxZoomOut = 0;                                                    // Max zoom out level (11 ~= $maxLatLng = 1, 0 to disable, lower = the further you can zoom out)
$enableCsrf = true;                                                 // Don't disable this unless you know why you need to :)
$sessionLifetime = 43200;                                           // Session lifetime, in seconds
$blockIframe = true;                                                // Block your map being loaded in an iframe

/* Map Title + Language */

$title = "PMSF Glennmen";                                           // Title to display in title bar
$locale = "en";                                                     // Display language

/* Google Maps Key */

$gmapsKey = "";                                                     // Google Maps API Key

/* Google Analytics */

$gAnalyticsId = "";                                                 // "" for empty, "UA-XXXXX-Y" add your Google Analytics tracking ID

/* Piwik Analytics */

$piwikUrl = "";
$piwikSiteId = "";

/* PayPal */

$paypalUrl = "";                                                    // PayPal donation URL, leave "" for empty

/* Discord */

$discordUrl = "";                                                    // Discord URL, leave "" for empty

/* MOTD */

$motdTitle = "";
$motdContent = "";

//-----------------------------------------------------
// FRONTEND SETTINGS
//-----------------------------------------------------

/* Marker Settings */

$noPokemon = false;                                                 // true/false
$enablePokemon = 'true';                                            // true/false
$noPokemonNumbers = false;                                          // true/false
$noHighLevelData = false;                                           // true/false
$noHidePokemon = false;                                             // true/false
$hidePokemon = '[10, 13, 16, 19, 21, 29, 32, 41, 46, 48, 50, 52, 56, 74, 77, 96, 111, 133,
                  161, 163, 167, 177, 183, 191, 194, 168]';         // [] for empty
$hidePokemonCoords = true;                                          // true/false

$noExcludeMinIV = false;                                            // true/false
$excludeMinIV = '[131, 143, 147, 148, 149, 248]';                   // [] for empty

$noMinIV = false;                                                   // true/false
$minIV = '0';                                                       // "0" for empty or a number

$noMinLevel = false;                                                // true/false
$minLevel = '0';                                                    // "0" for empty or a number

$noBigKarp = false;                                               // true/false
$noTinyRat = false;                                                 // true/false

$noGyms = false;                                                    // true/false
$enableGyms = 'false';                                              // true/false
$noGymSidebar = false;                                              // true/false
$gymSidebar = 'true';                                               // true/false
$noTrainerName = false;                                             // true/false
$noExEligible = false;                                              // true/false
$exEligible = 'true';                                               // true/false

$noRaids = false;                                                   // true/false
$enableRaids = 'false';                                             // true/false
$activeRaids = 'false';                                             // true/false
$minRaidLevel = 1;
$maxRaidLevel = 5;

$noPokestops = false;                                               // true/false
$enablePokestops = 'false';                                         // true/false
$enableLured = 1;                                                   // O: all, 1: lured only

$noScannedLocations = false;                                        // true/false
$enableScannedLocations = 'false';                                  // true/false

$noSpawnPoints = false;                                             // true/false
$enableSpawnPoints = 'false';                                       // true/false

$noRanges = false;                                                  // true/false
$enableRanges = 'false';                                            // true/false

/* Location & Search Settings */

$noSearchLocation = false;                                          // true/false

$noStartMe = false;                                                 // true/false
$enableStartMe = 'false';                                           // true/false

$noStartLast = false;                                               // true/false
$enableStartLast = 'false';                                         // true/false

$noFollowMe = false;                                                // true/false
$enableFollowMe = 'false';                                          // true/false

$noSpawnArea = false;                                               // true/false
$enableSpawnArea = 'false';                                         // true/false

/* Notification Settings */

$noNotifyPokemon = false;                                           // true/false
$notifyPokemon = '[201]';                                           // [] for empty

$noNotifyRarity = false;                                            // true/false
$notifyRarity = '[]';                                               // "Common", "Uncommon", "Rare", "Very Rare", "Ultra Rare"

$noNotifyIv = false;                                                // true/false
$notifyIv = '""';                                                   // "" for empty or a number

$noNotifyLevel = false;                                                // true/false
$notifyLevel = '""';                                                   // "" for empty or a number

$noNotifyRaid = false;                                              // true/false
$notifyRaid = 5;                                                    // O to disable

$noNotifySound = false;                                             // true/false
$notifySound = 'false';                                             // true/false

$noCriesSound = false;                                              // true/false
$criesSound = 'false';                                              // true/false

/* Style Settings */

$copyrightSafe = true;

$noMapStyle = false;                                                // true/false
$mapStyle = 'style_pgo_dynamic';                                    // roadmap, satellite, hybrid, nolabels_style, dark_style, style_light2, style_pgo, dark_style_nl, style_pgo_day, style_pgo_night, style_pgo_dynamic, openstreetmap

$noDirectionProvider = false;                                       // true/false
$directionProvider = 'google';                                      // google, waze, apple

$noIconSize = false;                                                // true/false
$iconSize = 0;                                                      // -8, 0, 10, 20

$noIconNotifySizeModifier = false;                                  // true/false | Increase size of notified Pokemon
$iconNotifySizeModifier = 15;                                       // 0, 15, 30, 45

$noGymStyle = false;                                                // true/false
$gymStyle = 'ingame';                                               // ingame, shield

$noLocationStyle = false;                                           // true/false
$locationStyle = 'none';                                            // none, google, red, red_animated, blue, blue_animated, yellow, yellow_animated, pokesition, pokeball

$osmTileServer = 'tile.openstreetmap.org';                          // osm tile server (no trailing slash)

$triggerGyms = '[]';                                                // Add Gyms that the OSM-Query doesn't take care of like '["gym_id", "gym_id"]'
$onlyTriggerGyms = false;                                           // Only show EX-Gyms that are defined in $triggerGyms
$noExGyms = false;                                                  // Do not display EX-Gyms on the map
$noParkInfo = false;                                                // Do not display Park info on the map

//-----------------------------------------------------
// Raid API
//-----------------------------------------------------

$raidApiKey = '';                                                   // Raid API Key, '' to deny access
$sendRaidData = false;                                              // Send Raid data, false to only send gym data

//-----------------------------------------------------
// Weather Config
//-----------------------------------------------------

$noWeatherOverlay = false;                                          // true/false
$enableWeatherOverlay = 'false';                                    // true/false

$weather = [
    0 => null,
    1 => 'clear',
    2 => 'rain',
    3 => 'partly_cloudy',
    4 => 'cloudy',
    5 => 'windy',
    6 => 'snow',
    7 => 'fog'
];

$weatherColors = [
    'grey',
    '#fdfd96',
    'darkblue',
    'grey',
    'darkgrey',
    'purple',
    'white',
    'black'
];

//-----------------------------------------------------
// DEBUGGING
//-----------------------------------------------------

// Do not enable unless requested

$enableDebug = false;

//-----------------------------------------------------
// DATABASE CONFIG
//-----------------------------------------------------

$fork = "default";                                                  // default/asner/sloppy
