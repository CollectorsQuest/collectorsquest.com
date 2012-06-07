<?php
/*  
Copyright 2010-2012 Arnan de Gans  (email : adegans@meandmymac.net)
*/

/*-------------------------------------------------------------
 Purpose:   Facilitate outgoing affiliate links
 Receive:   $_GET
 Return:	-None-
 Since:		2.0
-------------------------------------------------------------*/
define('WP_USE_THEMES', false);
require('../../../wp-blog-header.php');

global $wpdb, $adrotate_crawlers, $adrotate_debug;

if(isset($_GET['track']) OR $_GET['track'] != '') {
	$meta 									= base64_decode($_GET['track']);	
//	$meta 									= $_GET['track'];	
	$useragent 								= trim($_SERVER['HTTP_USER_AGENT'], ' \t\r\n\0\x0B');
	$prefix									= $wpdb->prefix;

	if(isset($_GET['preview'])) $preview 	= $_GET['preview'];	
	list($ad, $group, $block, $bannerurl) = explode(",", $meta);

	$remote_ip 	= adrotate_get_remote_ip();
	$now 		= time();
	$today 		= gmmktime(0, 0, 0, gmdate("n"), gmdate("j"), gmdate("Y"));
	if($adrotate_debug['timers'] == true) {
		$tomorrow = $now;
	} else {
		$tomorrow = $now + 86400;
	}
		
	if($bannerurl) {
		if(is_array($adrotate_crawlers)) $crawlers = $adrotate_crawlers;
			else $crawlers = array();
	
		$nocrawler = true;
		foreach ($crawlers as $crawler) {
			if (preg_match("/$crawler/i", $useragent)) $nocrawler = false;
		}

		$ip = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM `".$prefix."adrotate_tracker` WHERE `ipaddress` = '%s' AND `stat` = 'c' AND `timer` < '$tomorrow' AND `bannerid` = '%s' LIMIT 1;", $remote_ip, $ad));
		if($ip < 1 AND $nocrawler == true AND (!isset($preview) OR empty($preview)) AND (strlen($useragent) > 0 OR !empty($useragent))) {
			$wpdb->query($wpdb->prepare("UPDATE `".$prefix."adrotate_stats_tracker` SET `clicks` = `clicks` + 1 WHERE `ad` = '%s' AND `group` = '%s' AND `block` = '%s' AND `thetime` = '$today';", $ad, $group, $block));
			$wpdb->query($wpdb->prepare("INSERT INTO `".$prefix."adrotate_tracker` (`ipaddress`, `timer`, `bannerid`, `stat`, `useragent`) VALUES ('%s', '$now', '%s', 'c', '%s');", $remote_ip, $ad, $useragent));
		}

		header('Location: '.htmlspecialchars_decode($bannerurl));
	} else {
		echo 'There was an error retrieving the ad! Contact an administrator!';
	}
} else {
	echo 'No or invalid Ad ID specified! Contact an administrator!';
}
?>