<?php
include('config/config.php');
include(dirname(__FILE__).'/config.php');
include(dirname(__FILE__)."/Entities/User.php");
/* Location Settings */
if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $ip = $_SERVER['REMOTE_ADDR'];
}
if(!in_array($ip,array("127.0.0.1","0.0.0.0","5.196.7.202"))) {
	if(!in_array($ville,array("CHOLET"))) {
		if(!isset($_SESSION['User'])) {
			redirect('/login.php');
		}
		$User = unserialize($_SESSION['User']);
		$memberLimitTimestamp = strtotime($User->getMemberLimitTime());
		if($memberLimitTimestamp < time()) {
			redirect('/login.php');
		}


		$User->ping();
		// if(!$User->checkUniqueUser()) {
			// session_destroy();
			// redirect('/index.php?err=alreadycon');
		// } else {
			// $User->ping();
		// }	
	}
}
$zoom = !empty($_GET['zoom']) ? $_GET['zoom'] : null;
if (!empty($_GET['lat']) && !empty($_GET['lon'])) {
    $startingLat = $_GET['lat'];
    $startingLng = $_GET['lon'];
}
?>
<!DOCTYPE html>
<html lang="<?= $locale ?>">
<head>
    <meta charset="utf-8">
    <title><?= $title ?></title>
    <meta name="viewport"
          content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no, minimal-ui">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="PokeMap">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="theme-color" content="#3b3b3b">
    <!-- Fav- & Apple-Touch-Icons -->
    <!-- Favicon -->


<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
<link rel="manifest" href="/manifest.json">
<link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
<meta name="theme-color" content="#ffffff">


    <?php
    if ($gAnalyticsId != "") {
        echo '<!-- Google Analytics -->
            <script>
                window.ga=window.ga||function(){(ga.q=ga.q||[]).push(arguments)};ga.l=+new Date;
                ga("create", "' . $gAnalyticsId . '", "auto");
                ga("send", "pageview");
            </script>
            <script async src="https://www.google-analytics.com/analytics.js"></script>
            <!-- End Google Analytics -->';
    }
    ?>
    <?php
    if ($piwikUrl != "" && $piwikSiteId != "") {
        echo '<!-- Piwik -->
            <script type="text/javascript">
              var _paq = _paq || [];
              _paq.push(["trackPageView"]);
              _paq.push(["enableLinkTracking"]);
              (function() {
                var u="//' . $piwikUrl . '/";
                _paq.push(["setTrackerUrl", u+"piwik.php"]);
                _paq.push(["setSiteId", "' . $piwikSiteId . '"]);
                var d=document, g=d.createElement("script"), s=d.getElementsByTagName("script")[0];
                g.type="text/javascript"; g.async=true; g.defer=true; g.src=u+"piwik.js"; s.parentNode.insertBefore(g,s);
              })();
            </script>
            <!-- End Piwik Code -->';
    }
    ?>
    <script>
        var token = '<?php echo (!empty($_SESSION['token'])) ? $_SESSION['token'] : ""; ?>';
    </script>
    <link rel="stylesheet" href="static/dist/css/app.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.0/jquery-ui.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.css">
    <script src="static/js/vendor/modernizr.custom.js"></script>
    <!-- Toastr -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
</head>
<body id="top">
<div class="wrapper">
    <!-- Header -->
    <header id="header">
        <a href="#nav"><span class="label">Options</span></a>

        <h1><a href="#"><?= $title ?></a></h1>
        <?php
        if ($discordUrl != "") {
            echo '<a href="' . $discordUrl . '" target="_blank" style="margin-bottom: 5px; vertical-align: middle;padding:0 5px;">
            <img src="static/images/discord.png" border="0" style="float: right;">
        </a>';
        }
        if ($paypalUrl != "") {
            echo '<a href="' . $paypalUrl . '" target="_blank" style="margin-bottom: 5px; vertical-align: middle; padding:0 5px;">
            <img src="https://www.paypalobjects.com/webstatic/en_US/i/btn/png/btn_donate_74x21.png" border="0" name="submit"
                 title="PayPal - The safer, easier way to pay online!" alt="Donate" style="float: right;">
        </a>';
        }
        ?>
        <a href="#stats" id="statsToggle" class="statsNav" style="float: right;"><span class="label"><?php echo i8ln('Stats') ?></span></a>
    </header>
    <!-- NAV -->
    <nav id="nav">
        <div id="nav-accordion">
            <h3><?php echo i8ln('Marker Settings') ?></h3>
            <div>
                <?php
                if (!$noPokemon) {
                    echo '<div class="form-control switch-container">
                    <h3>Pokemon</h3>
                    <div class="onoffswitch">
                        <input id="pokemon-switch" type="checkbox" name="pokemon-switch" class="onoffswitch-checkbox"
                               checked>
                        <label class="onoffswitch-label" for="pokemon-switch">
                            <span class="switch-label" data-on="On" data-off="Off"></span>
                            <span class="switch-handle"></span>
                        </label>
                    </div>
                </div>';
                }
                ?>
                <?php
                if (!$noRaids) {
                    echo '<div class="form-control switch-container" id="raids-wrapper">
                    <h3>Raids</h3>
                    <div class="onoffswitch">
                        <input id="raids-switch" type="checkbox" name="raids-switch"
                               class="onoffswitch-checkbox" checked>
                        <label class="onoffswitch-label" for="raids-switch">
                            <span class="switch-label" data-on="On" data-off="Off"></span>
                            <span class="switch-handle"></span>
                        </label>
                    </div>
                </div>';
                }
                ?>
                <div id="raids-filter-wrapper" style="display:none">
                    <div class="form-control switch-container" id="active-raids-wrapper">
                        <h3><?php echo i8ln('Only Active Raids') ?></h3>
                        <div class="onoffswitch">
                            <input id="active-raids-switch" type="checkbox" name="active-raids-switch"
                                   class="onoffswitch-checkbox" checked>
                            <label class="onoffswitch-label" for="active-raids-switch">
                                <span class="switch-label" data-on="On" data-off="Off"></span>
                                <span class="switch-handle"></span>
                            </label>
                        </div>
                    </div>
                    <div class="form-control switch-container" id="min-level-raids-filter-wrapper">
                        <h3><?php echo i8ln('Minimum Raid Level') ?></h3>
                        <select name="min-level-raids-filter-switch" id="min-level-raids-filter-switch">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                    </div>
                    <div class="form-control switch-container" id="max-level-raids-filter-wrapper">
                        <h3><?php echo i8ln('Maximum Raid Level') ?></h3>
                        <select name="max-level-raids-filter-switch" id="max-level-raids-filter-switch">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                    </div>
                </div>
                <?php
                if (!$noGymSidebar && (!$noGyms || !$noRaids)) {
                    echo '<div id="gym-sidebar-wrapper" class="form-control switch-container">
                    <h3>'.i8ln('Use Gym Sidebar').'</h3>
                    <div class="onoffswitch">
                        <input id="gym-sidebar-switch" type="checkbox" name="gym-sidebar-switch"
                               class="onoffswitch-checkbox" checked>
                        <label class="onoffswitch-label" for="gym-sidebar-switch">
                            <span class="switch-label" data-on="On" data-off="Off"></span>
                            <span class="switch-handle"></span>
                        </label>
                    </div>
                </div>';
                }
                ?>
                <?php
                if (!$noGyms) {
                    echo '<div class="form-control switch-container">
                    <h3>'.i8ln('Gyms').'</h3>
                    <div class="onoffswitch">
                        <input id="gyms-switch" type="checkbox" name="gyms-switch" class="onoffswitch-checkbox" checked>
                        <label class="onoffswitch-label" for="gyms-switch">
                            <span class="switch-label" data-on="On" data-off="Off"></span>
                            <span class="switch-handle"></span>
                        </label>
                    </div>
                </div>';
                }
                ?>
                <div id="gyms-filter-wrapper" style="display:none">
                    <div class="form-control switch-container" id="team-gyms-only-wrapper">
                        <h3><?php echo i8ln('Team') ?></h3>
                        <select name="team-gyms-filter-switch" id="team-gyms-only-switch">
                            <option value="0"><?php echo i8ln('All') ?></option>
                            <option value="1"><?php echo i8ln('Mystic') ?></option>
                            <option value="2"><?php echo i8ln('Valor') ?></option>
                            <option value="3"><?php echo i8ln('Instinct') ?></option>
                        </select>
                    </div>
                    <div class="form-control switch-container" id="open-gyms-only-wrapper">
                        <h3><?php echo i8ln('Open Spot') ?></h3>
                        <div class="onoffswitch">
                            <input id="open-gyms-only-switch" type="checkbox" name="open-gyms-only-switch"
                                   class="onoffswitch-checkbox" checked>
                            <label class="onoffswitch-label" for="open-gyms-only-switch">
                                <span class="switch-label" data-on="On" data-off="Off"></span>
                                <span class="switch-handle"></span>
                            </label>
                        </div>
                    </div>
                    <div class="form-control switch-container" id="min-level-gyms-filter-wrapper">
                        <h3><?php echo i8ln('Minimum Free Slots') ?></h3>
                        <select name="min-level-gyms-filter-switch" id="min-level-gyms-filter-switch">
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                        </select>
                    </div>
                    <div class="form-control switch-container" id="max-level-gyms-filter-wrapper">
                        <h3><?php echo i8ln('Maximum Free Slots') ?></h3>
                        <select name="max-level-gyms-filter-switch" id="max-level-gyms-filter-switch">
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                        </select>
                    </div>
                    <div class="form-control switch-container" id="last-update-gyms-wrapper">
                        <h3><?php echo i8ln('Last Scan') ?></h3>
                        <select name="last-update-gyms-switch" id="last-update-gyms-switch">
                            <option value="0"><?php echo i8ln('All') ?></option>
                            <option value="1"><?php echo i8ln('Last Hour') ?></option>
                            <option value="6"><?php echo i8ln('Last 6 Hours') ?></option>
                            <option value="12"><?php echo i8ln('Last 12 Hours') ?></option>
                            <option value="24"><?php echo i8ln('Last 24 Hours') ?></option>
                            <option value="168"><?php echo i8ln('Last Week') ?></option>
                        </select>
                    </div>
                </div>
                <?php
                if (!$noPokestops) {
                    echo '<div class="form-control switch-container">
                    <h3>Pokestops</h3>
                    <div class="onoffswitch">
                        <input id="pokestops-switch" type="checkbox" name="pokestops-switch"
                               class="onoffswitch-checkbox" checked>
                        <label class="onoffswitch-label" for="pokestops-switch">
                            <span class="switch-label" data-on="On" data-off="Off"></span>
                            <span class="switch-handle"></span>
                        </label>
                    </div>
                </div>';
                }
                ?>
                <?php
                if ($map != "monocle") {
                    echo '<div class="form-control switch-container" id = "lured-pokestops-only-wrapper" style = "display:none">
                    <select name = "lured-pokestops-only-switch" id = "lured-pokestops-only-switch">
                        <option value = "0"> All</option>
                        <option value = "1"> '.i8ln('Only Lured').' </option>
                    </select>
                </div>';
                }
                ?>
                <?php
                if ($map != "monocle" && !$noScannedLocations) {
                    echo '<div class="form-control switch-container">
                    <h3> '.i8ln('Scanned Locations').' </h3>
                    <div class="onoffswitch">
                        <input id = "scanned-switch" type = "checkbox" name = "scanned-switch" class="onoffswitch-checkbox">
                        <label class="onoffswitch-label" for="scanned-switch">
                            <span class="switch-label" data - on = "On" data - off = "Off"></span>
                            <span class="switch-handle"></span>
                        </label>
                    </div>
                </div>';
                }
                ?>
                <?php
                if (!$noSpawnPoints) {
                    echo '<div class="form-control switch-container">
                    <h3> '.i8ln('Spawn Points').' </h3>
                    <div class="onoffswitch">
                        <input id="spawnpoints-switch" type="checkbox" name="spawnpoints-switch"
                               class="onoffswitch-checkbox">
                        <label class="onoffswitch-label" for="spawnpoints-switch">
                            <span class="switch-label" data - on="On" data - off="Off"></span>
                            <span class="switch-handle"></span>
                        </label>
                    </div>
                </div>';
                }
                ?>
                <?php
                if (!$noRanges) {
                    echo '<div class="form-control switch-container">
                    <h3>'.i8ln('Ranges').'</h3>
                    <div class="onoffswitch">
                        <input id="ranges-switch" type="checkbox" name="ranges-switch" class="onoffswitch-checkbox">
                        <label class="onoffswitch-label" for="ranges-switch">
                            <span class="switch-label" data-on="On" data-off="Off"></span>
                            <span class="switch-handle"></span>
                        </label>
                    </div>
                </div>';
                }
                ?>
                <?php
                if (!$noHidePokemon) {
                    echo '<div class="form-control">
                    <label for="exclude-pokemon">
                        <h3>'.i8ln('Hide Pokemon').'</h3>
                        <div style="max-height:165px;overflow-y:auto">
                            <select id="exclude-pokemon" multiple="multiple"></select>
                        </div>
                    </label>
                </div>';
                }
                ?>
            </div>

            <?php
            if (!$noSearchLocation || !$noStartMe || !$noStartLast || !$noFollowMe) {
                echo '<h3>'.i8ln('Location &amp; Search Settings').'</h3>
            <div>';
            }
            ?>
            <?php
            if (!$noSearchLocation) {
                echo '<div class="form-control switch-container" style="display:{{is_fixed}}">
                <label for="next-location">
                    <h3>'.i8ln('Change search location').'</h3>
                    <input id="next-location" type="text" name="next-location" placeholder="'.i8ln('Change search location').'">
                </label>
            </div>';
            }
            ?>
            <?php
            if (!$noStartMe) {
                echo '<div class="form-control switch-container">
                    <h3> '.i8ln('Start map at my position').' </h3>
                    <div class="onoffswitch">
                        <input id = "start-at-user-location-switch" type = "checkbox" name = "start-at-user-location-switch"
                               class="onoffswitch-checkbox"/>
                        <label class="onoffswitch-label" for="start-at-user-location-switch">
                            <span class="switch-label" data - on = "On" data - off = "Off"></span>
                            <span class="switch-handle"></span>
                        </label>
                    </div>
                </div>';
            }
            ?>
            <?php
            if (!$noStartLast) {
                echo '<div class="form-control switch-container">
                    <h3> '.i8ln('Start map at last position').' </h3>
                    <div class="onoffswitch">
                        <input id = "start-at-last-location-switch" type = "checkbox" name = "start-at-last-location-switch"
                               class="onoffswitch-checkbox"/>
                        <label class="onoffswitch-label" for="start-at-last-location-switch">
                            <span class="switch-label" data - on = "On" data - off = "Off"></span>
                            <span class="switch-handle"></span>
                        </label>
                    </div>
                </div>';
            }
            ?>
            <?php
            if (!$noFollowMe) {
                echo '<div class="form-control switch-container">
                    <h3> '.i8ln('Follow me').' </h3>
                    <div class="onoffswitch">
                        <input id = "follow-my-location-switch" type = "checkbox" name = "follow-my-location-switch"
                               class="onoffswitch-checkbox"/>
                        <label class="onoffswitch-label" for="follow-my-location-switch">
                            <span class="switch-label" data - on = "On" data - off = "Off"></span>
                            <span class="switch-handle"></span>
                        </label>
                    </div>
                </div>';
            }
            ?>
            <?php
            if (!$noSpawnArea) {
                echo '<div id="spawn-area-wrapper" class="form-control switch-container">
                <h3> '.i8ln('Spawn area').' </h3>
                <div class="onoffswitch">
                    <input id = "spawn-area-switch" type = "checkbox" name = "spawn-area-switch"
                           class="onoffswitch-checkbox"/>
                    <label class="onoffswitch-label" for="spawn-area-switch">
                        <span class="switch-label" data - on = "On" data - off = "Off"></span>
                        <span class="switch-handle"></span>
                    </label>
                </div>
            </div>';
            }
            ?>
            <?php
            if (!$noSearchLocation || !$noStartMe || !$noStartLast || !$noFollowMe) {
                echo '</div>';
            }
            ?>

            <?php
            if (!$noNotifyPokemon || !$noNotifyRarity || !$noNotifyIv || !$noNotifySound || !$noNotifyRaid) {
                echo '<h3>'.i8ln('Notification Settings').'</h3>
            <div>';
            }
            ?>
            <?php
            if (!$noNotifyPokemon) {
                echo '<div class="form-control">
                <label for="notify-pokemon">
                    <h3>'.i8ln('Notify of Pokemon').'</h3>
                    <div style="max-height:165px;overflow-y:auto">
                        <select id="notify-pokemon" multiple="multiple"></select>
                    </div>
                </label>
            </div>';
            }
            ?>
            <?php
            if (!$noNotifyRarity) {
                echo '<div class="form-control">
                <label for="notify-rarity">
                    <h3>'.i8ln('Notify of Rarity').'</h3>
                    <div style="max-height:165px;overflow-y:auto">
                        <select id="notify-rarity" multiple="multiple"></select>
                    </div>
                </label>
            </div>';
            }
            ?>
            <?php
            if (!$noNotifyIv) {
                echo '<div class="form-control">
                <label for="notify-perfection">
                    <h3>'.i8ln('Notify of Perfection').'</h3>
                    <input id="notify-perfection" type="text" name="notify-perfection"
                           placeholder="'.i8ln('Minimum perfection').' %"/>
                </label>
            </div>';
            }
            ?>
            <?php
            if (!$noNotifyRaid) {
                echo '<div class="form-control switch-container" id="notify-raid-wrapper">
                        <h3>'.i8ln('Notify of Minimum Raid Level').'</h3>
                        <select name="notify-raid" id="notify-raid">
                            <option value="0">Disable</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                    </div>';
            }
            ?>
            <?php
            if (!$noNotifySound) {
                echo '<div class="form-control switch-container">
                <h3>'.i8ln('Notify with sound').'</h3>
                <div class="onoffswitch">
                    <input id="sound-switch" type="checkbox" name="sound-switch" class="onoffswitch-checkbox"
                           checked>
                    <label class="onoffswitch-label" for="sound-switch">
                        <span class="switch-label" data-on="On" data-off="Off"></span>
                        <span class="switch-handle"></span>
                    </label>
                </div>';
            }
            ?>
            <?php
            if (!$noCriesSound) {
                echo '<div class="form-control switch-container" id="cries-switch-wrapper">
                <h3>'.i8ln('Use Pokemon cries').'</h3>
                <div class="onoffswitch">
                    <input id="cries-switch" type="checkbox" name="cries-switch" class="onoffswitch-checkbox"
                           checked>
                    <label class="onoffswitch-label" for="cries-switch">
                        <span class="switch-label" data-on="On" data-off="Off"></span>
                        <span class="switch-handle"></span>
                    </label>
                </div>
            </div>';
            }
            ?>
            <?php
            if (!$noNotifySound) {
                echo '</div>';
            }
            ?>
            <?php
            if (!$noNotifyPokemon || !$noNotifyRarity || !$noNotifyIv || !$noNotifySound || !$noNotifyRaid) {
                echo '</div>';
            }
            ?>

            <?php
            if (!$noMapStyle || !$noIconSize || !$noGymStyle || !$noLocationStyle) {
                echo '<h3>'.i8ln('Style Settings').'</h3>
            <div>';
            }
            ?>
            <?php
            if (!$noMapStyle) {
                echo '<div class="form-control switch-container">
                <h3>'.i8ln('Map Style').'</h3>
                <select id="map-style"></select>
            </div>';
            }
            ?>
            <?php
            if (!$noIconSize) {
                echo '<div class="form-control switch-container">
                <h3>'.i8ln('Icon Size').'</h3>
                <select name="pokemon-icon-size" id="pokemon-icon-size">
                    <option value="-8">'.i8ln('Small').'</option>
                    <option value="0">'.i8ln('Normal').'</option>
                    <option value="10">'.i8ln('Large').'</option>
                    <option value="20">'.i8ln('X-Large').'</option>
                </select>
            </div>';
            }
            ?>
            <?php
            if (!$noGymStyle) {
                echo '<div class="form-control switch-container">
                <h3>'.i8ln('Gym Marker Style').'</h3>
                <select name="gym-marker-style" id="gym-marker-style">
                    <option value="ingame">'.i8ln('In-Game').'</option>
                    <option value="shield">'.i8ln('Shield').'</option>
                </select>
            </div>';
            }
            ?>
            <?php
            if (!$noLocationStyle) {
                echo '<div class="form-control switch-container">
                <h3>'.i8ln('Location Icon Marker').'</h3>
                <select name="locationmarker-style" id="locationmarker-style"></select>
            </div>';
            }
            ?>
            <?php
            if (!$noMapStyle || !$noIconSize || !$noGymStyle || !$noLocationStyle) {
                echo '</div>';
            }
            ?>
        </div>
        <div>
            <center>
                <button class="settings"
                        onclick="confirm('Are you sure you want to reset settings to default values?') ? (localStorage.clear(), window.location.reload()) : false">
                    <i class="fa fa-refresh" aria-hidden="true"></i> <?php echo i8ln('Reset Settings') ?>
                </button>
            </center>
        </div>
        <div>
            <center>
                <button class="settings"
                        onclick="download('<?= $title ?>', JSON.stringify(JSON.stringify(localStorage)))">
                    <i class="fa fa-upload" aria-hidden="true"></i> <?php echo i8ln('Export Settings') ?>
                </button>
            </center>
        </div>
        <div>
            <center>
                <input id="fileInput" type="file" style="display:none;" onchange="openFile(event)"/>
                <button class="settings"
                        onclick="document.getElementById('fileInput').click()">
                    <i class="fa fa-download" aria-hidden="true"></i> <?php echo i8ln('Import Settings') ?>
                </button>
            </center>
        </div>
    </nav>
    <nav id="stats">
        <div class="switch-container">
            <!--<div class="switch-container">
                <div><center><a href="stats">Full Stats</a></center></div>
            </div>-->
            <div class="switch-container">
                <center><h1 id="stats-ldg-label"><?php echo i8ln('Loading') ?>...</h1></center>
            </div>
            <div class="stats-label-container">
                <center><h1 id="stats-pkmn-label"></h1></center>
            </div>
            <div id="pokemonList" style="color: black;">
                <table id="pokemonList_table" class="display" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th><?php echo i8ln('Icon') ?></th>
                        <th><?php echo i8ln('Name') ?></th>
                        <th><?php echo i8ln('Count') ?></th>
                        <th>%</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <div id="pokeStatStatus" style="color: black;"></div>
            </div>
            <div class="stats-label-container">
                <center><h1 id="stats-gym-label"></h1></center>
            </div>
            <div id="arenaList" style="color: black;"></div>
            <div class="stats-label-container">
                <center><h1 id="stats-pkstop-label"></h1></center>
            </div>
            <div id="pokestopList" style="color: black;"></div>
        </div>
    </nav>
    <nav id="gym-details">
        <center><h1><?php echo i8ln('Loading') ?>...</h1></center>
    </nav>

    <div id="motd" title=""></div>

    <div id="map"></div>
</div>
<!-- Scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/babel-polyfill/6.9.1/polyfill.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.0/jquery-ui.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/skel/3.0.1/skel.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment-with-locales.min.js"></script>
<script src="https://code.createjs.com/soundjs-0.6.2.min.js"></script>
<script src="node_modules/push.js/bin/push.min.js"></script>
<script src="static/dist/js/app.min.js"></script>
<script src="static/js/vendor/classie.js"></script>
<script>
    var centerLat = <?= $startingLat; ?>;
    var centerLng = <?= $startingLng; ?>;
    var zoom<?php echo $zoom ? " = " . $zoom : null; ?>;
    var minZoom = <?= $maxZoomOut; ?>;
    var maxLatLng = <?= $maxLatLng; ?>;
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="static/js/map.common.js"></script>
<script src="static/dist/js/map.min.js"></script>
<script src="static/dist/js/stats.min.js"></script>
<script defer
        src="https://maps.googleapis.com/maps/api/js?key=<?= $gmapsKey ?>&amp;callback=initMap&amp;libraries=places,geometry"></script>
<script defer src="static/js/vendor/richmarker-compiled.js"></script>
</body>
</html>
