<?php

$data = array();

include('includes/include.php');

$result = doQuery("SELECT SettingValue FROM " . HC_TblPrefix . "settings WHERE PkID IN (5,6,7,11,14,21) ORDER BY PkID");
if (hasRows($result))
{
  $data['meta_keywords'] = cOut(mysql_result($result,0,0));
  $data['meta_description'] = cOut(mysql_result($result,1,0));

  $browsePast = cOut(mysql_result($result,3,0));
  $dateFormat = mysql_result($result,4,0);
  $defaultState = cOut(mysql_result($result,5,0));
}
else
{
  exit(handleError(0, "Helios Settings Data Missing. You will need to run Helios Setup again."));
} // end if

define("HC_Menu", "components/Menu.php");
define("HC_Core", "components/Core.php");
define("HC_Controls", "components/ControlPanel.php");
define("HC_Billboard", "components/Billboard.php");
define("HC_Popular", "components/Popular.php");


ob_start();
include(HC_Billboard);
$HC_Billboard = ob_get_clean();

ob_start();
include(HC_Controls);
$HC_Controls = ob_get_clean();

ob_start();
include(HC_Popular);
$HC_Popular = ob_get_clean();

ob_start();
include(HC_Core);
$HC_Core = ob_get_clean();

ob_start();
include(HC_Menu);
$HC_Menu = ob_get_clean();

$key = md5(serialize($data));

if (function_exists('xcache_set'))
{
  xcache_set($key, $data, 10);
}
else
{
  zend_shm_cache_store($key, $data, 10);
}

$com = isset($_GET['com']) ? $_GET['com'] : null;
$url = sprintf(
  "http://www.collectorsquest.local/_calendar/index?_session_id=%s&key=%s&com=%s&env=%s",
  $_COOKIE['legacy'], $key, $com, SF_ENV
);

$layout = file_get_contents($url);

echo str_replace(
  array('<!-- HC_Core //-->', '<!-- HC_Menu //-->', '<!-- HC_Popular //-->', '<!-- HC_Controls //-->', '<!-- HC_Billboard //-->'),
  array($HC_Core, $HC_Menu, $HC_Popular, $HC_Controls, $HC_Billboard),
  $layout
);
