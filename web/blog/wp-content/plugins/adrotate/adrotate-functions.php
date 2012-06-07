<?php
/*  
Copyright 2010-2011 Arnan de Gans  (email : adegans@meandmymac.net)
*/

/*-------------------------------------------------------------
 Name:      adrotate_shortcode

 Purpose:   Prepare function requests for calls on shortcodes
 Receive:   $atts, $content
 Return:    Function()
 Since:		0.7
-------------------------------------------------------------*/
function adrotate_shortcode($atts, $content = null) {

	/* Changelog:
	// Nov 9 2010 - Rewritten for 3.0, param 'column' obsolete, added 'fallback' override for groups
	// Nov 17 2010 - Added filters for empty values
	// Dec 13 2010 - Improved backward compatibility for single ads and blocks
	// Jan 16 2011 - Added $weight as an override for groups
	// Jan 24 2011 - Added $weight as an override for blocks
	// Mar 9 2011 - Added check for http:// on $link
	*/

	if(!empty($atts['banner'])) 	$banner_id 	= trim($atts['banner'], "\r\t ");
	if(!empty($atts['group'])) 		$group_ids 	= trim($atts['group'], "\r\t ");
	if(!empty($atts['block']))		$block_id	= trim($atts['block'], "\r\t ");
	if(!empty($atts['fallback']))	$fallback	= trim($atts['fallback'], "\r\t "); // Optional for groups (override)
	if(!empty($atts['weight']))		$weight		= trim($atts['weight'], "\r\t "); // Optional for groups (override)
	if(!empty($atts['column']))		$columns	= trim($atts['column'], "\r\t "); // OBSOLETE

	if($banner_id > 0 AND ($group_ids == 0 OR $group_ids > 0) AND $block_id == 0) // Show one Ad
		return adrotate_ad($banner_id);

	if($banner_id == 0 AND $group_ids > 0 AND $block_id == 0) // Show group 
		return adrotate_group($group_ids, $fallback, $weight);

	if($banner_id == 0 AND $group_ids == 0 AND $block_id > 0) // Show block 
		return adrotate_block($block_id, $weight);
}

/*-------------------------------------------------------------
 Name:      adrotate_banner DEPRECATED

 Purpose:   Compatibility layer for old setups 
 Receive:   $group_ids, $banner_id, $block_id, $column
 Return:    Function()
 Added: 	0.1
-------------------------------------------------------------*/
function adrotate_banner($group_ids = 0, $banner_id = 0, $block_id = 0, $column = 0) {

	/* Changelog:
	// Nov 6 2010 - Changed function to form a compatibility layer for old setups, for ad output see adrotate_ad()
	// Nov 9 2010 - $block, Now accepts Block ID's only. $column OBSOLETE, no longer in use
	// Dec 6 2010 - Function DEPRECATED, maintained for backward compatibility
	*/
	
	if(($banner_id > 0 AND ($group_ids == 0 OR $group_ids == '')) OR ($banner_id > 0 AND $group_ids > 0 AND ($block_id == 0 OR $block_id == ''))) // Show one Ad
		return adrotate_ad($banner_id);

	if($group_ids != 0 AND ($banner_id == 0 OR $banner_id == '')) // Show group 
		return adrotate_group($group_ids);

	if($block_id > 0 AND ($banner_id == 0 OR $banner_id == '') AND ($group_ids == 0 OR $group_ids == '')) // Show block
		return adrotate_block($block_id);
}

/*-------------------------------------------------------------
 Name:      adrotate_pick_weight

 Purpose:   Sort out and pick a random ad based on weight
 Receive:   $selected
 Return:    $key
 Since:		3.1
-------------------------------------------------------------*/
function adrotate_pick_weight($selected) { 

	/* Changelog:
	// Jun 5 2010 - Func renamed from adrotate_weight()
	*/

    $rnd = mt_rand(0, array_sum($selected)-1);
    
    foreach($selected as $key => $var) { 
        if($rnd < $var) return $key; 
        $rnd -= $var; 
    } 
} 

/*-------------------------------------------------------------
 Name:      adrotate_array_unique

 Purpose:   Filter out duplicate records in multidimensional arrays
 Receive:   $array
 Return:    $array|$return
 Since:		3.0
-------------------------------------------------------------*/
function adrotate_array_unique($array) {
	if(count($array) > 0) {
		if(is_array($array[0])) {
			$return = array();
			// multidimensional
			foreach($array as $row) {
				if(!in_array($row, $return)) {
					$return[] = $row;
				}
			}
			return $return;
		} else {
			// not multidimensional
			return array_unique($array);
		}
	} else {
		return $array;
	}
}

/*-------------------------------------------------------------
 Name:      adrotate_prepare_evaluate_ads

 Purpose:   Initiate evaluations for errors
 Receive:   -None-
 Return:    -None-
 Since:		3.6.5
-------------------------------------------------------------*/
function adrotate_prepare_evaluate_ads() {
	global $wpdb;
	
	// Clean Ad table
	$wpdb->query("DELETE FROM `".$wpdb->prefix."adrotate` WHERE `type` = 'empty';");

	// Fetch ads
	$ads = $wpdb->get_results("SELECT `id` FROM `".$wpdb->prefix."adrotate` ORDER BY `id` ASC;");

	// Determine error states
	$corrected = 0;
	foreach($ads as $ad) {
		$result = adrotate_evaluate_ad($ad->id);
		if($result == true) $corrected++;
	}

	adrotate_return('eval_complete', array($corrected));
}

/*-------------------------------------------------------------
 Name:      adrotate_evaluate_ad

 Purpose:   Evaluates ads for errors
 Receive:   $ad_id
 Return:    String
 Since:		3.6.5
-------------------------------------------------------------*/
function adrotate_evaluate_ad($ad_id) {
	global $wpdb;
	
	// Fetch ad
	$ad = $wpdb->get_row("SELECT `bannercode`, `tracker`, `link`, `imagetype`, `image` FROM `".$wpdb->prefix."adrotate` WHERE `id` = '$ad_id';");
	$advertiser = $wpdb->get_var("SELECT `user` FROM `".$wpdb->prefix."adrotate_linkmeta` WHERE `ad` = '$ad_id' AND `group` = 0 AND `block` = 0 AND `user` > 0;");

	// Determine error states
	if(
		strlen($ad->bannercode) < 1 
		OR ($ad->tracker == 'N' AND strlen($ad->link) < 1 AND $advertiser > 0) 							// Didn't enable click-tracking, didn't provide a link, DID set a advertiser
		OR ($ad->tracker == 'Y' AND strlen($ad->link) < 1) 												// Enabled clicktracking but provided no url (link)
		OR ($ad->tracker == 'N' AND strlen($ad->link) > 0) 												// Didn't enable click-tracking but did provide an url (link)
		OR (!preg_match("/%link%/i", $ad->bannercode) AND $ad->tracker == 'Y')							// Didn't use %link% but enabled clicktracking
		OR (preg_match("/%link%/i", $ad->bannercode) AND $ad->tracker == 'N')							// Did use %link% but didn't enable clicktracking
		OR (!preg_match("/%image%/i", $ad->bannercode) AND $ad->image != '' AND $ad->imagetype != '')	// Didn't use %image% but selected an image
		OR (preg_match("/%image%/i", $ad->bannercode) AND $ad->image == '' AND $ad->imagetype == '')	// Did use %image% but didn't select an image
		OR ($ad->image == '' AND $ad->imagetype != '')													// Critical Image and Imagetype mismatch
		OR ($ad->image != '' AND $ad->imagetype == '')													// Critical Image and Imagetype mismatch
	) {
		$wpdb->query("UPDATE `".$wpdb->prefix."adrotate` SET `type` = 'error' WHERE `id` = '$ad_id';");
		return true;
	} else {
		$wpdb->query("UPDATE `".$wpdb->prefix."adrotate` SET `type` = 'manual' WHERE `id` = '$ad_id';");
		return false;
	}
}

/*-------------------------------------------------------------
 Name:      adrotate_prepare_global_report

 Purpose:   Generate live stats for admins
 Receive:   -None-
 Return:    -None-
 Since:		3.5
-------------------------------------------------------------*/
function adrotate_prepare_global_report() {
	global $wpdb;
	
	$today = gmmktime(0, 0, 0, gmdate("n"), gmdate("j"), gmdate("Y"));

	$stats['lastclicks']			= adrotate_array_unique($wpdb->get_results("SELECT `timer`, `bannerid`, `useragent` FROM `".$wpdb->prefix."adrotate_tracker` WHERE `stat` = 'c' AND `ipaddress` != 0 ORDER BY `timer` DESC LIMIT 50;", ARRAY_A));
	$stats['banners'] 				= $wpdb->get_var("SELECT COUNT(*) FROM `".$wpdb->prefix."adrotate` WHERE `type` = 'manual';");
	$stats['tracker']				= $wpdb->get_var("SELECT COUNT(*) FROM `".$wpdb->prefix."adrotate` WHERE `tracker` = 'Y' AND `type` = 'manual';");
	$stats['clicks']				= $wpdb->get_var("SELECT SUM(`clicks`) as `clicks` FROM `".$wpdb->prefix."adrotate_stats_tracker`;");
	$stats['impressions']			= $wpdb->get_var("SELECT SUM(`impressions`) as `impressions` FROM `".$wpdb->prefix."adrotate_stats_tracker`;");
	
	if(!$stats['lastclicks']) 			array();
	if(!$stats['banners']) 				$stats['banners'] = 0;
	if(!$stats['tracker']) 				$stats['tracker'] = 0;
	if(!$stats['clicks']) 				$stats['clicks'] = 0;
	if(!$stats['impressions']) 			$stats['impressions'] = 0;

	return $stats;
}

/*-------------------------------------------------------------
 Name:      adrotate_prepare_advertiser_report

 Purpose:   Generate live stats for advertisers
 Receive:   $user
 Return:    -None-
 Since:		3.5
-------------------------------------------------------------*/
function adrotate_prepare_advertiser_report($user) {
	global $wpdb;

	$now = current_time('timestamp');
	$today = gmmktime(0, 0, 0, gmdate("n"), gmdate("j"), gmdate("Y"));
	$prefix = $wpdb->prefix;

	$ads = $wpdb->get_results("SELECT `ad` FROM `".$prefix."adrotate_linkmeta` WHERE `group` = 0 AND `block` = 0 AND `user` = '$user' ORDER BY `ad` ASC;");
	
	if($ads) {		
		$stats['thebest']	= $wpdb->get_row("
											SELECT 
												`".$prefix."adrotate`.`title`, 
												SUM(`".$prefix."adrotate_stats_tracker`.`clicks`) as `clicks` 
											FROM 
												`".$prefix."adrotate`, 
												`".$prefix."adrotate_linkmeta`, 
												`".$prefix."adrotate_stats_tracker` 
											WHERE 
												`".$prefix."adrotate`.`id` = `".$prefix."adrotate_linkmeta`.`ad` 
												AND `".$prefix."adrotate_linkmeta`.`ad` = `".$prefix."adrotate_stats_tracker`.`ad` 
												AND `".$prefix."adrotate`.`tracker` = 'Y' 
												AND `".$prefix."adrotate`.`active` = 'yes' 
												AND `".$prefix."adrotate`.`type` = 'manual' 
												AND `".$prefix."adrotate_linkmeta`.`user` = '$user' 
											ORDER BY 
												`".$prefix."adrotate_stats_tracker`.`clicks` DESC 
											LIMIT 1;"
											, ARRAY_A);
		$stats['theworst']	= $wpdb->get_row("
											SELECT 
												`".$prefix."adrotate`.`title`, 
												SUM(`".$prefix."adrotate_stats_tracker`.`clicks`) as `clicks` 
											FROM 
												`".$prefix."adrotate`, 
												`".$prefix."adrotate_linkmeta`, 
												`".$prefix."adrotate_stats_tracker` 
											WHERE 
												`".$prefix."adrotate`.`id` = `".$prefix."adrotate_linkmeta`.`ad` 
												AND `".$prefix."adrotate_linkmeta`.`ad` = `".$prefix."adrotate_stats_tracker`.`ad` 
												AND `".$prefix."adrotate`.`tracker` = 'Y' 
												AND `".$prefix."adrotate`.`active` = 'yes' 
												AND `".$prefix."adrotate`.`type` = 'manual' 
												AND `".$prefix."adrotate_linkmeta`.`user` = '$user' 
											ORDER BY 
												`".$prefix."adrotate_stats_tracker`.`clicks` ASC 
											LIMIT 1;"
											, ARRAY_A);
		$stats['ad_amount']	= count($ads);

		$x = 0;
		foreach($ads as $ad) {
			// Fetch data
			$meta = $wpdb->get_row("SELECT * FROM `".$prefix."adrotate` WHERE `id` = '$ad->ad' AND `type` = 'manual' GROUP BY `id`;");
			$stat = $wpdb->get_row("SELECT SUM(`clicks`) as `clicks`, SUM(`impressions`) as `impressions` FROM `".$prefix."adrotate_stats_tracker` WHERE `ad` = '$ad->ad';");
			$stat_today = $wpdb->get_row("SELECT `clicks`, `impressions` FROM `".$prefix."adrotate_stats_tracker` WHERE `ad` = '$ad->ad' AND `thetime` = '$today';");
			
			// Prevent gaps in display
			if($stat->impressions == 0) 		$stat->impressions 			= 0;
			if($stat->clicks == 0) 				$stat->clicks 				= 0;
			if($stat_today->impressions == 0) 	$stat_today->impressions 	= 0;
			if($stat_today->clicks == 0) 		$stat_today->clicks 		= 0;

			// Build array
			$adstats[$x]['id']						= $meta->id;			
			$adstats[$x]['title']					= $meta->title;			
			$adstats[$x]['startshow']				= $meta->startshow;
			$adstats[$x]['endshow']					= $meta->endshow;
			$adstats[$x]['clicks']					= $stat->clicks;
			$adstats[$x]['clicks_today']			= $stat_today->clicks;
			$adstats[$x]['maxclicks']				= $meta->maxclicks;
			$adstats[$x]['impressions']				= $stat->impressions;
			$adstats[$x]['impressions_today']		= $stat_today->impressions;
			$adstats[$x]['maximpressions']			= $meta->maxshown;
	
			$stats['total_clicks']			= $stats['total_clicks'] + $stat->clicks;
			$stats['total_impressions']		= $stats['total_impressions'] + $stat->impressions;

			$x++;
		}
			
		$lastclicks	= adrotate_array_unique($wpdb->get_results("SELECT 
																	`".$prefix."adrotate_tracker`.`timer`, 
																	`".$prefix."adrotate_tracker`.`bannerid` 
																FROM 
																	`".$prefix."adrotate`, 
																	`".$prefix."adrotate_tracker`, 
																	`".$prefix."adrotate_linkmeta` 
																WHERE 
																	`".$prefix."adrotate_linkmeta`.`user` = '$user' 
																	AND `".$prefix."adrotate_linkmeta`.`group` = 0 
																	AND `".$prefix."adrotate_linkmeta`.`block` = 0 
																	AND `".$prefix."adrotate_tracker`.`ipaddress` != 0 
																	AND `".$prefix."adrotate_tracker`.`bannerid` = `".$prefix."adrotate_linkmeta`.`ad` 
																	AND `".$prefix."adrotate`.`tracker` = 'Y' 
																ORDER BY 
																	`".$prefix."adrotate_tracker`.`timer` DESC 
																LIMIT 8;"
																, ARRAY_A));
		
		$stats['ads'] 					= $adstats;
		$stats['last_clicks']			= $lastclicks;
	
		if(!$stats['thebest']) 			$stats['thebest']		= array('title' => 0, 'clicks' => 0);
		if(!$stats['theworst']) 		$stats['theworst']		= array('title' => 0, 'clicks' => 0);
		if(!$stats['ads']) 				$stats['ads'] 			= array();
		if(!$stats['last_clicks']) 		$stats['last_clicks'] 	= array();
		
		return $stats;
	}
}

/*-------------------------------------------------------------
 Name:      adrotate_prepare_color

 Purpose:   Check if ads are expired and set a color for its end date
 Receive:   $banner_id
 Return:    $result
 Since:		3.0
-------------------------------------------------------------*/
function adrotate_prepare_color($enddate) {
	$now = current_time('timestamp');
	$in2days = $now + 172800;
	$in7days = $now + 604800;
	
	if($enddate <= $now) {
		return '#CC2900'; // red
	} else if($enddate <= $in2days AND $enddate >= $now) {
		return '#F90'; // orange
	} else if($enddate <= $in7days AND $enddate >= $now) {
		return '#E6B800'; // yellow
	} else {
		return '#009900'; // green
	}
}

/*-------------------------------------------------------------
 Name:      adrotate_group_is_in_blocks

 Purpose:   Build list of blocks the group is in (editing)
 Receive:   $id
 Return:    $output
 Since:		3.0
-------------------------------------------------------------*/
function adrotate_group_is_in_blocks($id) {
	global $wpdb;
	
	/* Changelog:
	// Mar 29 2011 - Internationalization support
	*/

	$output = '';
	$linkmeta = $wpdb->get_results("SELECT `block` FROM `".$wpdb->prefix."adrotate_linkmeta` WHERE `ad` = 0 AND `group` = '$id' AND `block` > 0 AND `user` = 0 ORDER BY `block` ASC;");
	if($linkmeta) {
		foreach($linkmeta as $meta) {
			$blockname = $wpdb->get_var("SELECT `name` FROM `".$wpdb->prefix."adrotate_blocks` WHERE `id` = '".$meta->block."';");
			$output .= '<a href="'.get_option('siteurl').'/wp-admin/admin.php?page=adrotate-blocks&view=edit&edit_block='.$meta->block.'" title="'.__('Edit Block', 'adrotate').'">'.$blockname.'</a>, ';
		}
	} else {
		$output .= __('This group is not in a block!', 'adrotate');
	}
	$output = rtrim($output, " ,");
	
	return $output;
}

/*-------------------------------------------------------------
 Name:      adrotate_clean_trackerdata

 Purpose:   Removes old trackerdata
 Receive:   -none-
 Return:    -none-
 Since:		2.0
-------------------------------------------------------------*/
function adrotate_clean_trackerdata() {
	global $wpdb;

	$removeme = current_time('timestamp') - 86400;
	$wpdb->query("DELETE FROM `".$wpdb->prefix."adrotate_tracker` WHERE `timer` < ".$removeme." AND `ipaddress` > 0;");
}

/*-------------------------------------------------------------
 Name:      adrotate_check_banners

 Purpose:   Check if ads are expired, or are about to
 Receive:   -none-
 Return:    $result
 Since:		3.0
-------------------------------------------------------------*/
function adrotate_check_banners() {
	global $wpdb;

	$now = current_time('timestamp');
	$in2days = $now + 172800;
	
	$alreadyexpired = $wpdb->get_var("SELECT COUNT(*) FROM `".$wpdb->prefix."adrotate` WHERE `active` = 'yes' AND `endshow` <= $now;");
	$expiressoon = $wpdb->get_var("SELECT COUNT(*) FROM `".$wpdb->prefix."adrotate` WHERE `active` = 'yes' AND `endshow` <= $in2days AND `endshow` >= $now;");
	$error = $wpdb->get_var("SELECT COUNT(*) FROM `".$wpdb->prefix."adrotate` WHERE `active` = 'yes' AND `type` = 'error';");

	$count = $alreadyexpired + $expiressoon + $error;
	
	$result = array('expired' => $alreadyexpired,
					'expiressoon' => $expiressoon,
					'error' => $error,
					'total' => $count);

	return $result;	
}

/*-------------------------------------------------------------
 Name:      adrotate_head

 Purpose:   Add clicktracking code to <head>
 Receive:   -none-
 Return:    -none-
 Since:		3.6.9
-------------------------------------------------------------*/
function adrotate_head() {
	wp_enqueue_script('AdRotate-Clicktracker', WP_CONTENT_URL.'/plugins/adrotate/library/clicktracker.js', 'jQuery');
}   

/*-------------------------------------------------------------
 Name:      adrotate_check_config

 Purpose:   Update the options
 Receive:   -none-
 Return:    -none-
 Since:		0.1
-------------------------------------------------------------*/
function adrotate_check_config() {

	/* Changelog:
	// Jan 3 2011 - Changed to a per setting model
	// Jan 16 2011 - Added notification email conversion to array
	// Jan 20 2011 - Updated user capabilities to work with new access rights system
	// Jan 20 2011 - Added debug switch (defaults to false)
	// Jan 23 2011 - Added option to disable email notifications
	// Jan 24 2011 - Renamed $crawlers to $debug for debugger if()
	// Feb 15 2011 - Added dashboard debug option
	// Feb 28 2011 - Revamped debug option with array()
	// Jul 6 2011 - Renewed crawlers
	// Jul 11 2011 - Added option for impression timer
	// Aug 10 2011 - Removed sortorder option
	*/
	
	$config 	= get_option('adrotate_config');
	$crawlers 	= get_option('adrotate_crawlers');
	$debug 		= get_option('adrotate_debug');

	if($config['advertiser_report'] == '' OR !isset($config['advertiser_report'])) 	$config['advertiser_report']	= 'switch_themes'; 	// Admin
	if($config['global_report'] == '' OR !isset($config['global_report'])) 			$config['global_report']		= 'switch_themes'; 	// Admin
	if($config['ad_manage'] == '' OR !isset($config['ad_manage'])) 					$config['ad_manage'] 			= 'switch_themes'; 	// Admin
	if($config['ad_delete'] == '' OR !isset($config['ad_delete'])) 					$config['ad_delete']			= 'switch_themes'; 	// Admin
	if($config['group_manage'] == '' OR !isset($config['group_manage'])) 			$config['group_manage']			= 'switch_themes'; 	// Admin
	if($config['group_delete'] == '' OR !isset($config['group_delete'])) 			$config['group_delete']			= 'switch_themes'; 	// Admin
	if($config['block_manage'] == '' OR !isset($config['block_manage'])) 			$config['block_manage']			= 'switch_themes'; 	// Admin
	if($config['block_delete'] == '' OR !isset($config['block_delete'])) 			$config['block_delete']			= 'switch_themes'; 	// Admin
	if($config['notification_email_switch'] == '' OR !isset($config['notification_email_switch']))	$config['notification_email_switch']	= 'Y';
	if(($config['notification_email'] == '' OR !isset($config['notification_email']) OR !is_array($config['notification_email'])) AND $config['notification_email_switch'] == 'Y')	$config['notification_email']	= array(get_option('admin_email'));
	if($config['advertiser_email'] == '' OR !isset($config['advertiser_email']) OR !is_array($config['advertiser_email']))	$config['advertiser_email']	= array(get_option('admin_email'));
	if($config['credits'] == '' OR !isset($config['credits']))						$config['credits'] 				= 'Y';
	if($config['widgetalign'] == '' OR !isset($config['widgetalign']))				$config['widgetalign'] 			= 'N';
	if($config['impression_timer'] == '' OR !isset($config['impression_timer']))	$config['impression_timer'] 	= '10';
	update_option('adrotate_config', $config);

	if($crawlers == '' OR !isset($crawlers)) 										$crawlers 						= array("Teoma", "alexa", "froogle", "Gigabot", "inktomi","looksmart", "URL_Spider_SQL", "Firefly", "NationalDirectory","Ask Jeeves", "TECNOSEEK", "InfoSeek", "WebFindBot", "girafabot","www.galaxy.com", "Googlebot", "Scooter", "Slurp","msnbot", "appie", "FAST", "WebBug", "Spade", "ZyBorg", "rabaz","Baiduspider", "Feedfetcher-Google", "TechnoratiSnoop", "Rankivabot","Mediapartners-Google", "Sogou web spider", "WebAlta Crawler","bot", "crawler", "yahoo", "msn", "ask", "ia_archiver");
	update_option('adrotate_crawlers', $crawlers);

	if($debug['general'] == '' OR !isset($debug['general'])) 						$debug['general'] 				= false;
	if($debug['dashboard'] == '' OR !isset($debug['dashboard'])) 					$debug['dashboard'] 			= false;
	if($debug['userroles'] == '' OR !isset($debug['userroles'])) 					$debug['userroles'] 			= false;
	if($debug['userstats'] == '' OR !isset($debug['userstats'])) 					$debug['userstats'] 			= false;
	if($debug['stats'] == '' OR !isset($debug['stats'])) 							$debug['stats'] 				= false;
	if($debug['timers'] == '' OR !isset($debug['timers'])) 							$debug['timers'] 				= false;
	update_option('adrotate_debug', $debug);

}

/*-------------------------------------------------------------
 Name:      adrotate_get_sorted_roles

 Purpose:   Returns all roles and capabilities, sorted by user level. Lowest to highest. (Code based on NextGen Gallery)
 Receive:   -none-
 Return:    $sorted
 Since:		3.2
-------------------------------------------------------------*/
function adrotate_get_sorted_roles() {	
	global $wp_roles;

	/* Changelog:
	// Jan 22 2011 - Dropped get_editable_roles(), function is broken in pre-WP 3.1 versions.
	*/
	
	$editable_roles = apply_filters('editable_roles', $wp_roles->roles);
	$sorted = array();
	
	foreach($editable_roles as $role => $details) {
		$sorted[$details['name']] = get_role($role);
	}

	$sorted = array_reverse($sorted);

	return $sorted;
}

/*-------------------------------------------------------------
 Name:      adrotate_get_role

 Purpose:   Return the lowest roles which has the capabilities (Code borrowed from NextGen Gallery)
 Receive:   $capability
 Return:    Boolean|$check_role->name
 Since:		3.2
-------------------------------------------------------------*/
function adrotate_get_role($capability){
	$check_order = adrotate_get_sorted_roles();
	$args = array_slice(func_get_args(), 1);
	$args = array_merge(array($capability), $args);

	foreach($check_order as $check_role) {
		if(empty($check_role)) return false;
		if(call_user_func_array(array(&$check_role, 'has_cap'), $args)) return $check_role->name;
	}
	return false;
}

/*-------------------------------------------------------------
 Name:      adrotate_get_remote_ip

 Purpose:   Get the remote IP from the visitor
 Receive:   -None-
 Return:    $buffer[0]
 Since:		3.6.2
-------------------------------------------------------------*/
function adrotate_get_remote_ip(){
	if(empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
		$remote_ip = $_SERVER["REMOTE_ADDR"];
	} else {
		$remote_ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
	}
	$buffer = explode(',', $remote_ip, 2);

	return $buffer[0];
}

/*-------------------------------------------------------------
 Name:      adrotate_set_capability

 Purpose:   Grant or revoke capabilities to a role (Code borrowed from NextGen Gallery)
 Receive:   $lowest_role, $capability
 Return:    -None-
 Since:		3.2
-------------------------------------------------------------*/
function adrotate_set_capability($lowest_role, $capability){

	/* Changelog:
	// Jan 21 2011 - Fixed $the_role to $role
	*/
	
	$check_order = adrotate_get_sorted_roles();
	$add_capability = false;
	
	foreach($check_order as $role) {
		if($lowest_role == $role->name) 
			$add_capability = true;
			
		if(empty($role)) 
			continue;
			
		$add_capability ? $role->add_cap($capability) : $role->remove_cap($capability) ;
	}
}

/*-------------------------------------------------------------
 Name:      adrotate_remove_capability

 Purpose:   Remove the $capability from the all roles (Based on NextGen Gallery)
 Receive:   $capability
 Return:    -None-
 Since:		3.2
-------------------------------------------------------------*/
function adrotate_remove_capability($capability){

	/* Changelog:
	// Jan 21 2011 - Fixed $role to $role->name
	*/
	
	$check_order = adrotate_get_sorted_roles();

	foreach($check_order as $role) {
		$role = get_role($role->name);
		$role->remove_cap($capability);
	}

}

/*-------------------------------------------------------------
 Name:      adrotate_notifications_dashboard

 Purpose:   Notify user of expired banners in the dashboard
 Receive:   -none-
 Return:    -none-
 Since:		3.0
-------------------------------------------------------------*/
function adrotate_notifications_dashboard() {

	/* Changelog:
	// Mar 3 2011 - Messages now only show for ad managers (user level)
	// Mar 29 2011 - Internationalization support
	*/
	
	if(current_user_can('adrotate_ad_manage')) {
		$data = adrotate_check_banners();
	
		if($data['total'] > 0) {
			if($data['expired'] > 0 AND $data['expiressoon'] == 0 AND $data['error'] == 0) {
				echo '<div class="error"><p>'.$data['expired'].' '.__('ad(s) expired.', 'adrotate').' <a href="admin.php?page=adrotate">'.__('Take action now', 'adrotate').'</a>!</p></div>';
			} else if($data['expired'] == 0 AND $data['expiressoon'] > 0 AND $data['error'] == 0) {
				echo '<div class="error"><p>'.$data['expiressoon'].' '.__('ad(s) are about to expire.', 'adrotate').' <a href="admin.php?page=adrotate">'.__('Check it out', 'adrotate').'</a>!</p></div>';
			} else if($data['expired'] == 0 AND $data['expiressoon'] == 0 AND $data['error'] > 0) {
				echo '<div class="error"><p>There are '.$data['error'].' '.__('ad(s) with configuration errors.', 'adrotate').' <a href="admin.php?page=adrotate">'.__('Solve this', 'adrotate').'</a>!</p></div>';
			} else {
				echo '<div class="error"><p>'.$data['expired'].' '.__('ad(s) expired.', 'adrotate').' '.$data['expiressoon'].' '.__('ad(s) are about to expire.', 'adrotate').' There are '.$data['error'].' '.__('ad(s) with configuration errors.', 'adrotate').' <a href="admin.php?page=adrotate">'.__('Fix this as soon as possible', 'adrotate').'</a>!</p></div>';
			}
		}
	}
}

/*-------------------------------------------------------------
 Name:      adrotate_mail_notifications

 Purpose:   Email the manager that his ads need help
 Receive:   -None-
 Return:    -None-
 Since:		3.0
-------------------------------------------------------------*/
function adrotate_mail_notifications() {
	global $adrotate_config;
	
	/* Changelog:
	// Jan 3 2011 - Changed to a per setting model
	// Jan 16 2011 - Added support for multiple email addresses
	// Jan 24 2011 - Removed test notification (obsolete), cleaned up code
	// Feb 22 2011 - $data array updated to new standard
	// Mar 29 2011 - Internationalization support
	*/
	
	$emails = $adrotate_config['notification_email'];
	$x = count($emails);
	if($x == 0) $emails = array(get_option('admin_email'));
	
	$blogname 		= get_option('blogname');
	$siteurl 		= get_option('siteurl');
	$dashboardurl	= $siteurl."/wp-admin/admin.php?page=adrotate";
	$pluginurl		= "http://www.adrotateplugin.com";

	$data = adrotate_check_banners();
	for($i=0;$i<$x;$i++) {
		if($data['total'] > 0) {
		    $headers = "MIME-Version: 1.0\n" .
      				 	"From: AdRotate Plugin <".$emails[$i].">\r\n\n" . 
      				  	"Content-Type: text/html; charset=\"" . get_settings('blog_charset') . "\"\n";

			$subject = __('[AdRotate Alert] Your ads need your help!', 'adrotate');
			
			$message = "<p>".__('Hello', 'adrotate').",</p>";
			$message .= "<p>".__('This notification is send to you from your website', 'adrotate')." '$blogname'.</p>";
			$message .= "<p>".__('You will receive a notification approximately every 24 hours until the issues are resolved.', 'adrotate')."</p>";
			$message .= "<p>".__('Current issues:', 'adrotate')."<br />";
			if($data['expired'] > 0) $message .= $data['expired']." ".__('ad(s) expired. This needs your immediate attention!', 'adrotate')."<br />";
			if($data['expiressoon'] > 0) $message .= $data['expiressoon']." ".__('ad(s) will expire in less than 2 days.', 'adrotate')."<br />";
			$message .= "</p>";
			$message .= "<p>".__('A total of', 'adrotate')." ".$data['total']." ".__('ad(s) are in need of your care!', 'adrotate')."</p>";
			$message .= "<p>".__('Access your dashboard here:', 'adrotate')." $dashboardurl</p>";
			$message .= "<p>".__('Have a nice day!', 'adrotate')."</p>";
			$message .= "<p>".__('Your AdRotate Notifier', 'adrotate')."<br />";
			$message .= "$pluginurl</p>";

			wp_mail($emails[$i], $subject, $message, $headers);
		}
	}
}

/*-------------------------------------------------------------
 Name:      adrotate_mail_message

 Purpose:   Send advertiser messages
 Receive:   -None-
 Return:    -None-
 Since:		3.1
-------------------------------------------------------------*/
function adrotate_mail_message() {
	global $wpdb, $adrotate_config;

	/* Changelog:
	// Jan 3 2011 - Changed to a per setting model
	// Jan 16 2011 - Added new message type 'issue', array() support for multiple emails
	// Jan 24 2011 - Change to its own email array, updated layout and formatting
	// Mar 29 2011 - Internationalization support
	*/
	
	$id 			= $_POST['adrotate_id'];
	$request 		= $_POST['adrotate_request'];
	$author 		= $_POST['adrotate_username'];
	$useremail 		= $_POST['adrotate_email'];
	$text	 		= strip_tags(stripslashes(trim($_POST['adrotate_message'], "\t\n ")));

	if(strlen($text) < 1) $text = "";
	
	$emails = $adrotate_config['advertiser_email'];
	$x = count($emails);
	if($x == 0) $emails = array(get_option('admin_email'));
	
	$siteurl 		= get_option('siteurl');
	$adurl			= $siteurl."/wp-admin/admin.php?page=adrotate&view=edit&edit_ad=".$id;
	$pluginurl		= "http://www.adrotateplugin.com";

	for($i=0;$i<$x;$i++) {
	    $headers 		= "MIME-Version: 1.0\n" .
	      				  "From: $author <".$useremail.">\r\n\n" . 
	      				  "Content-Type: text/html; charset=\"" . get_settings('blog_charset') . "\"\n";
		$now 			= current_time('timestamp');
		
		if($request == "renew") $subject = __('[AdRotate] An advertiser has put in a request for renewal!', 'adrotate');
		if($request == "remove") $subject = __('[AdRotate] An advertiser wants his ad removed.', 'adrotate');
		if($request == "other") $subject = __('[AdRotate] An advertiser wrote a comment on his ad!', 'adrotate');
		if($request == "issue") $subject = __('[AdRotate] An advertiser has a problem!', 'adrotate');
		
		$message = "<p>Hello,</p>";
	
		if($request == "renew") $message .= "<p>$author ".__('requests ad', 'adrotate')." <strong>$id</strong> ".__('renewed!', 'adrotate')."</p>";
		if($request == "remove") $message .= "<p>$author ".__('requests ad', 'adrotate')." <strong>$id</strong> ".__('removed.', 'adrotate')."</p>";
		if($request == "other") $message .= "<p>$author ".__('has something to say about ad', 'adrotate')." <strong>$id</strong>.</p>";
		if($request == "issue") $message .= "<p>$author ".__('has a problem with AdRotate.', 'adrotate')."</p>";
		
		$message .= "<p>".__('Attached message:', 'adrotate')." $text</p>";
		
		$message .= "<p>".__('You can reply to this message to contact', 'adrotate')." $author.<br />";
		if($request != "issue") $message .= __('Review the ad here:', 'adrotate')." $adurl";
		$message .= "</p>";
		
		$message .= "<p>".__('Have a nice day!', 'adrotate')."<br />";
		$message .= __('Your AdRotate Notifier', 'adrotate')."<br />";
		$message .= "$pluginurl</p>";
	
		wp_mail($emails[$i], $subject, $message, $headers);
	}

	adrotate_return('mail_sent');
}

/*-------------------------------------------------------------
 Name:      adrotate_mail_test

 Purpose:   Send test messages
 Receive:   -None-
 Return:    -None-
 Since:		3.5
-------------------------------------------------------------*/
function adrotate_mail_test() {
	global $wpdb, $adrotate_config;

	/* Changelog:
	// Mar 29 2011 - Internationalization support
	*/
	
	if(isset($_POST['adrotate_notification_test_submit'])) {
		$type = "notification";
		$emails = $adrotate_config['notification_email'];
	}
	
	if(isset($_POST['adrotate_advertiser_test_submit'])) {
		$type = "advertiser";
		$emails = $adrotate_config['advertiser_email'];
	}
	
	$x = count($emails);
	if($x == 0) $emails = array(get_option('admin_email'));
	
	$siteurl 		= get_option('siteurl');
	$pluginurl		= "http://www.adrotateplugin.com";
	$email 			= get_option('admin_email');
	
	for($i=0;$i<$x;$i++) {
		$headers =	"MIME-Version: 1.0\n" .
	      			"From: AdRotate Plugin <".$email.">\r\n\n" . 
	      			"Content-Type: text/html; charset=\"" . get_settings('blog_charset') . "\"\n";
		
		if($type == "notification") $subject = __('[AdRotate] This is a test notification!', 'adrotate');
		if($type == "advertiser") $subject = __('[AdRotate] This is a test email.', 'adrotate');
		
		$message = 	"<p>".__('Hello', 'adrotate').",</p>";
	
		$message .= "<p>".__('The administrator of', 'adrotate')." $siteurl ".__('has set your email address to receive', 'adrotate');
		if($type == "notification") $message .= " ".__('notifications from AdRotate. These are to alert you of the state of advertisements posted on this website.', 'adrotate');
		if($type == "advertiser") $message .= " ".__('messages from Advertisers using AdRotate. Your email is not shown to them until you reply to their messages.', 'adrotate');
		$message .= "</p>";

		$message .= "<p>".__('If you believe this message to be in error, reply to this email with your complaint!', 'adrotate')."</p>";
				
		$message .= "<p>".__('Have a nice day!', 'adrotate')."<br />";
		$message .= __('Your AdRotate Notifier', 'adrotate')."<br />";
		$message .= "$pluginurl</p>";
	
		wp_mail($emails[$i], $subject, $message, $headers);
	}

	if($type == "notification") adrotate_return('mail_test_notification_sent');
	if($type == "advertiser") adrotate_return('mail_test_advertiser_sent');
}

/*-------------------------------------------------------------
 Name:      adrotate_reccurences

 Purpose:   Add more reccurances to the wp_cron feature
 Receive:   -none-
 Return:    -none-
 Since:		3.0
-------------------------------------------------------------*/
function adrotate_reccurences() {
	return array(
		'1day' => array(
			'interval' => 86400, 
			'display' => 'Every day'
		),
		'6hour' => array(
			'interval' => 21600, 
			'display' => 'Every 6 hours'
		),
		'weekly' => array(
			'interval' => 604800, 
			'display' => 'Once Weekly'
		),
	);
}

/*-------------------------------------------------------------
 Name:      adrotate_filemanager_admin_scripts

 Purpose:   Load file uploaded popup
 Receive:   -None-
 Return:	-None-
 Since:		3.6
-------------------------------------------------------------*/
function adrotate_filemanager_admin_scripts() {
	wp_enqueue_script('media-upload');
	wp_enqueue_script('thickbox');
	wp_enqueue_script('jquery');
}

/*-------------------------------------------------------------
 Name:      adrotate_filemanager_admin_styles

 Purpose:   Load file uploaded popup style
 Receive:   -None-
 Return:	-None-
 Since:		3.6
-------------------------------------------------------------*/
function adrotate_filemanager_admin_styles() {
	wp_enqueue_style('thickbox');
}

/*-------------------------------------------------------------
 Name:      adrotate_folder_contents

 Purpose:   List folder contents of /wp-content/banners and /wp-content/uploads
 Receive:   $current
 Return:	$output
 Since:		0.4
-------------------------------------------------------------*/
function adrotate_folder_contents($current) {
	global $wpdb, $adrotate_config;

	/* Changelog:
	// Mar 9 2011 - Updated folder reading with better error handling
	// Mar 25 2011 - Removed Media listing (commented out)
	// Mar 29 2011 - Internationalization support
	*/
	
	$output = '';

	// Read /wp-content/banners/
	$files = array();
	$i = 0;
	if($handle = opendir(ABSPATH.'/wp-content/banners/')) {
	    while (false !== ($file = readdir($handle))) {
	        if ($file != "." AND $file != ".." AND $file != "index.php") {
	            $files[] = $file;
	        	$i++;
	        }
	    }
	    closedir($handle);

/* 		$output .= "<option disabled>-- ".__('Banners folder', 'adrotate')." --</option>"; */
	    if($i > 0) {
			sort($files);
			foreach($files as $file) {
				$fileinfo = pathinfo($file);
		
				if((strtolower($fileinfo['extension']) == "jpg" OR strtolower($fileinfo['extension']) == "gif" OR strtolower($fileinfo['extension']) == "png" 
				OR strtolower($fileinfo['extension']) == "jpeg" OR strtolower($fileinfo['extension']) == "swf" OR strtolower($fileinfo['extension']) == "flv")) {
				    $output .= "<option value='".$file."'";
				    if($current == get_option('siteurl').'/wp-content/banners/'.$file) { $output .= "selected"; }
				    $output .= ">".$file."</option>";
				}
			}
		} else {
	    	$output .= "<option disabled>&nbsp;&nbsp;&nbsp;".__('No files found', 'adrotate')."</option>";
		}
	} else {
    	$output .= "<option disabled>&nbsp;&nbsp;&nbsp;".__('Folder not found or not accessible', 'adrotate')."</option>";
	}

/* OBSOLETE IN 3.6 - REMOVE IN FUTURE VERSION?
	// Read /wp-content/uploads/ from the WP database
	if($adrotate_config['browser'] == 'Y') {
		$uploadedmedia = $wpdb->get_results("SELECT `guid` FROM ".$wpdb->prefix."posts 
			WHERE `post_type` = 'attachment' 
			AND (`post_mime_type` = 'image/jpeg' 
				OR `post_mime_type` = 'image/gif' 
				OR `post_mime_type` = 'image/png'
				OR `post_mime_type` = 'application/x-shockwave-flash')
			ORDER BY `post_title` ASC");
		
		$output .= "<option disabled>-- ".__('Uploaded Media', 'adrotate')." --</option>";
		if($uploadedmedia) {
			foreach($uploadedmedia as $media) {
		        $output .= "<option value='media|".basename($media->guid)."'";
		        if($current == $media->guid) { $output .= "selected"; }
		        $output .= ">".basename($media->guid)."</option>";
			}
		} else {
			$output .= "<option disabled>&nbsp;&nbsp;&nbsp;".__('No media found', 'adrotate')."</option>";
		}
	}
*/
	
	return $output;
}

/*-------------------------------------------------------------
 Name:      adrotate_return

 Purpose:   Internal redirects
 Receive:   $action, $arg
 Return:    -none-
 Since:		0.2
-------------------------------------------------------------*/
function adrotate_return($action, $arg = null) {

	/* Changelog:
	// Nov ? 2010 - Added block support
	// Jan 24 2011 - Added default action, removed email_timer, mail_sent, renamed mail_request_sent to mail_sent
	// Feb 15 2011 - Added actions for test mails and db cleanup
	*/

	switch($action) {
		// Manage Ads
		case "new" :
			wp_redirect('admin.php?page=adrotate&message=created');
		break;

		case "update" :
			wp_redirect('admin.php?page=adrotate&view=edit&message=updated&ad='.$arg[0]);
		break;

		case "update_manage" :
			wp_redirect('admin.php?page=adrotate&message=updated');
		break;

		case "delete" :
			wp_redirect('admin.php?page=adrotate&message=deleted');
		break;

		case "reset" :
			wp_redirect('admin.php?page=adrotate&message=reset');
		break;

		case "renew" :
			wp_redirect('admin.php?page=adrotate&message=renew');
		break;

		case "deactivate" :
			wp_redirect('admin.php?page=adrotate&message=deactivate');
		break;

		case "activate" :
			wp_redirect('admin.php?page=adrotate&message=activate');
		break;

		case "field_error" :
			wp_redirect('admin.php?page=adrotate&message=field_error');
		break;

		// Groups
		case "group_new" :
			wp_redirect('admin.php?page=adrotate-groups&message=created');
		break;

		case "group_edit" :
			wp_redirect('admin.php?page=adrotate-groups&view=edit&message=updated&group='.$arg[0]);
		break;

		case "group_delete" :
			wp_redirect('admin.php?page=adrotate-groups&message=deleted');
		break;

		case "group_delete_banners" :
			wp_redirect('admin.php?page=adrotate-groups&message=deleted_banners');
		break;

		// Blocks
		case "block_new" :
			wp_redirect('admin.php?page=adrotate-blocks&message=created');
		break;

		case "block_edit" :
			wp_redirect('admin.php?page=adrotate-blocks&view=edit&message=updated&block='.$arg[0]);
		break;

		case "block_delete" :
			wp_redirect('admin.php?page=adrotate-blocks&message=deleted');
		break;

		case "block_template_new" :
			wp_redirect('admin.php?page=adrotate-blocks&view=templates&message=created_template');
		break;

		case "block_template_edit" :
			wp_redirect('admin.php?page=adrotate-blocks&view=templates&message=edit_template');
		break;

		case "block_template_delete" :
			wp_redirect('admin.php?page=adrotate-blocks&view=templates&message=deleted_template');
		break;

		// Settings
		case "settings_saved" :
			wp_redirect('admin.php?page=adrotate-settings&message=updated');
		break;

		case "role_add" :
			wp_redirect('admin.php?page=adrotate-settings&message=role_add');
		break;

		case "role_remove" :
			wp_redirect('admin.php?page=adrotate-settings&message=role_remove');
		break;

		case "mail_test_notification_sent" :
			wp_redirect('admin.php?page=adrotate-settings&message=mail_notification_sent');
		break;

		case "mail_test_advertiser_sent" :
			wp_redirect('admin.php?page=adrotate-settings&message=mail_advertiser_sent');
		break;

		// Maintenance
		case "db_optimized" :
			wp_redirect('admin.php?page=adrotate-settings&message=db_optimized');
		break;

		case "db_converted" :
			wp_redirect('admin.php?page=adrotate-settings&message=db_converted');
		break;

		case "db_repaired" :
			wp_redirect('admin.php?page=adrotate-settings&message=db_optimized');
		break;

		case "db_cleaned" :
			wp_redirect('admin.php?page=adrotate-settings&message=db_cleaned');
		break;

		case "db_timer" :
			wp_redirect('admin.php?page=adrotate-settings&message=db_timer');
		break;

		case "eval_complete" :
			wp_redirect('admin.php?page=adrotate-settings&message=eval_complete&corrected='.$arg[0]);
		break;

		// Misc plugin events
		case "mail_sent" :
			wp_redirect('admin.php?page=adrotate-userstatistics&message=mail_sent');
		break;

		case "no_access" :
			wp_redirect('admin.php?page=adrotate&message=no_access');
		break;

		case "error" :
			wp_redirect('admin.php?page=adrotate&message=error');
		break;

		default:
			wp_redirect('admin.php?page=adrotate');
		break;

	}
}

/*-------------------------------------------------------------
 Name:      adrotate_error

 Purpose:   Show errors for problems in using AdRotate, should they occur
 Receive:   $action
 Return:    -none-
 Since:		3.0
-------------------------------------------------------------*/
function adrotate_error($action, $arg = null) {
	global $adrotate_debug;

	/* Changelog:
	// Dec 26 2010 - Added more errors from other functions
	// Mar 8 2011 - Added debug switch for commented errors
	// Mar 29 2011 - Internationalization support
	*/

	switch($action) {
		// Ads
		case "ad_expired" :
			if($adrotate_debug['general'] == true) {
				$result = '<span style="font-weight: bold; color: #f00;">'.__('Error, Ad', 'adrotate').' (ID: '.$arg[0].') '.__('is expired or does not exist!', 'adrotate').'</span>';
			} else {
				$result = '<!-- '.__('Error, Ad', 'adrotate').' (ID: '.$arg[0].') '.__('is expired or does not exist!', 'adrotate').' -->';
			}
			return $result;
		break;
		
		case "ad_unqualified" :
			if($adrotate_debug['general'] == true) {
				$result = '<span style="font-weight: bold; color: #f00;">'.__('Either there are no banners, they are disabled or none qualified for this location!', 'adrotate').'</span>';
			} else {
				$result = '<!-- '.__('Either there are no banners, they are disabled or none qualified for this location!', 'adrotate').' -->';
			}
			return $result;
		break;
		
		case "ad_no_id" :
			$result = '<span style="font-weight: bold; color: #f00;">'.__('Error, no Ad ID set! Check your syntax!', 'adrotate').'</span>';
			return $result;
		break;

		case "ad_not_found" :
			$result = '<span style="font-weight: bold; color: #f00;">'.__('Error, ad could not be found! Make sure it exists.', 'adrotate').'</span>';
			return $result;
		break;

		// Groups
		case "group_no_id" :
			$result = '<span style="font-weight: bold; color: #f00;">'.__('Error, no group set! Check your syntax!', 'adrotate').'</span>';
			return $result;
		break;

		// Blocks
		case "block_not_found" :
			$result = '<span style="font-weight: bold; color: #f00;">'.__('Error, Block', 'adrotate').' (ID: '.$arg[0].') '.__('does not exist! Check your syntax!', 'adrotate').'</span>';
			return $result;
		break;

		case "block_no_id" :
			$result = '<span style="font-weight: bold; color: #f00;">'.__('Error, no Block ID set! Check your syntax!', 'adrotate').'</span>';
			return $result;
		break;

		// Database
		case "db_error" :
			$result = '<span style="font-weight: bold; color: #f00;">'.__('There was an error locating the database tables for AdRotate. Please deactivate and re-activate AdRotate from the plugin page!!', 'adrotate').'<br />'.__('If this does not solve the issue please seek support at', 'adrotate').' <a href="http://www.adrotateplugin.com/page/support.php">www.adrotateplugin.com/page/support.php</a></span>';
			return $result;
		break;

		// Misc
		default:
			$default = __('An unknown error occured.', 'adrotate');
			return $default;
		break;

	}
}
?>