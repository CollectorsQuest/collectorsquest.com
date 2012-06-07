<?php
/*-------------------------------------------------------------
 Name:      adrotate_receiver

 Purpose:   Prepare and process input from AdNetwork posted 
 			to http://example.com/wp-content/plugins/adrotate/adrotate.php
 Receive:   $_POST
 Return:	-None-
-------------------------------------------------------------*/
function adrotate_receiver() {
	global $wpdb;

	$headers 			= $_POST['headers'];			// Array()
	$body 				= $_POST['body']; 				// Array() of values, typically: array( 'id' 			=> 0, 
														//										'title' 		=> 'Ad Title', 
														//										'bannercode' 	=> '&lt;strong&gt;This is an Ad&lt;/strong&gt;', 
														//										'active' 		=> 'yes', 
														//										'image' 		=> 'http://example.com/path/to/ad.jpg', 
														//										'link' 			=> 'http://url.to/track', 
														//										'tracker'		=> 'Y', 
														//										'start' 		=> 1269190163, 
														//										'end' 			=> 1332201600, 
														//										'maxclicks' 	=> 0, 
														//										'maxshown' 		=> 0, );

	if(preg_match("/WordPress/i", $headers['user-agent'])) {
		$banner_id 			= $body['id']; 				// INT	 				Ad unique ID (used when editing/updating ads, set to 0 for new ads)
		$author 			= 'AdNetwork'; 				// CHAR					Static name of ad creator
		$title	 			= $body['title']; 			// LONGTEXT				Static title for reference
		$bannercode			= $body['bannercode']; 		// LONGTEXT				The actual ad (may contain HTML/JS)
		$thetime 			= date('U'); 				// INT					Publish date (now)
		$active 			= $body['active']; 			// VARCHAR		yes|no	Is the banner active? Should default to yes
		$image				= $body['image']; 			// VARCHAR				Ad image url
		$link				= $body['link']; 			// LONGTEXT				Link for click tracking
		$tracker			= $body['tracker']; 		// VARCHAR		Y|N 	value enables or disables click tracking
		$startdate			= $body['start'];			// INT					Date when ad STARTS showing, UNIXTIME
		$enddate 			= $body['end'];				// INT					Date then ad ENDS, Auto disabled, UNIXTIME
		$maxclicks			= $body['maxclicks'];		// INT					Ad disables after this many clicks
		$maxshown			= $body['maxshown'];		// INT					Ad disables after this many views
	
		/* Check if you need to update or insert a new record */
		if($banner_id > 0) {
			/* Update */
			$postquery = "UPDATE `".$wpdb->prefix."adrotate`	SET `title` = '$title', `bannercode` = '$bannercode', `updated` = '$thetime', `author` = '$author', `active` = '$active', `startshow` = '$startdate', `endshow` = '$enddate', `group` = '$group', `image` = '$image', `link` = '$link', `tracker` = '$tracker', `maxclicks` = '$maxclicks', `maxshown` = '$maxshown' WHERE `id` = '$banner_id'";
			$action = "update";
		} else {
			/* New */
			$postquery = "INSERT INTO `".$wpdb->prefix."adrotate` (
					`title`, `bannercode`, `thetime`, `updated`, `author`, `active`, `startshow`, `endshow`, `group`, `image`, `link`, `tracker`, `clicks`, `maxclicks`, `shown`, `maxshown` ,`magic`) 
			VALUES ('$title', '$bannercode', '$thetime', '$thetime', '$author', '$active', '$startdate', '$enddate', '$group', '$image', '$link', '$tracker', 0, '$maxclicks', 0, '$maxshown', 0)";
			$action = "new";
		}
		if($wpdb->query($postquery) !== FALSE) {
			exit;
		} else {
			die('[MySQL error] '.mysql_error());
		}
	} else {
		die('Invalid user-agent!');
	 	exit;
	}	 

}

/*-------------------------------------------------------------
 Name:      adrotate_transmit

 Purpose:   Send various data to AdNetwork
 Receive:   -None-
 Return:    -None-
-------------------------------------------------------------*/
function adrotate_transmit($key, $blogurl) {
	//$url = 'http://tracker.adrotateplugin.com/receiver.php';
	$url = 'http://www.kudde.net/receiver.php';
	$post_data = array (
		'headers'	=> null,
		'body'		=> array(
			'active'	=> 'yes',
			'blogurl'	=> base64_encode($blogurl),
			'date'		=> date('U'),
		),
/*
		'response' => Array(
			'code' => 200
            'message' => OK
        ),
		'cookies' => Array(),
*/
	);

	wp_remote_post($url, $post_data);
}


?>