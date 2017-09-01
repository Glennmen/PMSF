<?php

if ($map == "monocle" && $fork == "monkey") {
  include('forks/monkey.php');
  $adapter = new MonkeyFork;
} else {
  $adapter = NULL;
}
