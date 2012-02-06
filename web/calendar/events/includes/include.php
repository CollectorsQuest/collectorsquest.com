<?php

session_start();

if(isset($_COOKIE["hc_favorites"]))
{
  $_SESSION['hc_favorites'] = $_COOKIE["hc_favorites"];
}

define("HC_Version", "1.0.5");

include('globals.php');
include('code.php');
