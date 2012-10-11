<?php
/*  
Copyright 2010-2012 Arnan de Gans - AJdG Solutions (email : info@ajdg.net)
*/

/*-------------------------------------------------------------
 Name:      adrotate_shortcode

 Purpose:   Prepare function requests for calls on shortcodes
 Receive:   $atts, $content
 Return:    Function()
 Since:		0.7
-------------------------------------------------------------*/
function adrotate_shortcode($atts, $content = null) {
	global $adrotate_debug;

	$banner_id = $group_ids = $block_id = $fallback = $weight = $columns = 0;
	if(!empty($atts['banner'])) 	$banner_id 	= trim($atts['banner'], "\r\t ");
	if(!empty($atts['group'])) 		$group_ids 	= trim($atts['group'], "\r\t ");
	if(!empty($atts['block']))		$block_id	= trim($atts['block'], "\r\t ");
	if(!empty($atts['fallback']))	$fallback	= trim($atts['fallback'], "\r\t "); // Optional for groups (override)
	if(!empty($atts['weight']))		$weight		= trim($atts['weight'], "\r\t "); // Optional for groups (override)
	if(!empty($atts['column']))		$columns	= trim($atts['column'], "\r\t "); // OBSOLETE/UNUSED

	if($adrotate_debug['general'] == true) {
		echo "<p><strong>[DEBUG][adrotate_shortcode()] Attributes</strong><pre>";
		echo "Banner ID: ".$banner_id."</br>"; 
		echo "Group ID: ".$group_ids."</br>"; 
		echo "Block ID: ".$block_id."</br>"; 
		echo "Fallback: ".$fallback."</br>"; 
		echo "Weight: ".$weight."</br>"; 
		echo "Columns (Obsolete): ".$columns."</br>"; 
		echo "</pre></p>";
	}
	
	if($banner_id > 0 AND ($group_ids == 0 OR $group_ids > 0) AND $block_id == 0) // Show one Ad
		return adrotate_ad($banner_id);

	if($banner_id == 0 AND $group_ids > 0 AND $block_id == 0) // Show group 
		return adrotate_group($group_ids, $fallback, $weight);

	if($banner_id == 0 AND $group_ids == 0 AND $block_id > 0) // Show block 
		return adrotate_block($block_id, $weight);

	if($banner_id == 0 AND $group_ids == 0 AND $block_id == 0) // Show error 
		return adrotate_error('no_id');
}

/*-------------------------------------------------------------
 Name:      adrotate_banner DEPRECATED

 Purpose:   Compatibility layer for old setups 
 Receive:   $group_ids, $banner_id, $block_id, $column
 Return:    Function()
 Added: 	0.1
-------------------------------------------------------------*/
function adrotate_banner($group_ids = 0, $banner_id = 0, $block_id = 0, $column = 0) {

	/*
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

    $rnd = mt_rand(0, array_sum($selected)-1);
    
    foreach($selected as $key => $var) { 
        if($rnd < $var) return $key; 
        $rnd -= $var; 
    } 
} 

/*-------------------------------------------------------------
 Name:      adrotate_ctr

 Purpose:   Calculate Click-Through-Rate
 Receive:   $clicks, $impressions, $round
 Return:    $ctr
 Since:		3.7
-------------------------------------------------------------*/
function adrotate_ctr($clicks = 0, $impressions = 0, $round = 2) { 

	if($impressions > 0 AND $clicks > 0) {
		$ctr = round($clicks/$impressions*100, $round);
	} else {
		$ctr = 0;
	}
	
	return $ctr;
} 

/*-------------------------------------------------------------
 Name:      adrotate_filter_schedule

 Purpose:   Weed out ads that are over the limit of their schedule
 Receive:   $selected, $banner
 Return:    $selected
 Since:		3.7
-------------------------------------------------------------*/
function adrotate_filter_schedule($selected, $banner) { 
	global $wpdb, $adrotate_debug;

	$now = current_time('timestamp');
	$prefix = $wpdb->prefix;
	$current = array();

	if($adrotate_debug['general'] == true) {
		echo "<p><strong>[DEBUG][adrotate_filter_schedule()] Filtering banner</strong><pre>";
		print_r($banner->id); 
		echo "</pre></p>"; 
	}
	
	// Get schedules for advert
	$schedules = $wpdb->get_results("
		SELECT 
			`starttime`, 
			`stoptime`, 
			`maxclicks`, 
			`maximpressions`
		FROM 
			`".$prefix."adrotate_schedule` 
		WHERE 
			`ad` = '".$banner->id."'
		;");

	foreach($schedules as $schedule) {
		$stat = $wpdb->get_row("
			SELECT
				SUM(`clicks`) as `clicks`,
				SUM(`impressions`) as `impressions` 
			FROM
				`".$prefix."adrotate_stats_tracker`
			WHERE 
				`ad` = ".$banner->id."
				AND `thetime` >= ".$schedule->starttime."
				AND `thetime` <= ".$schedule->stoptime."
			;");
		
		if($schedule->maxclicks == null) $schedule->maxclicks = '0';
		if($schedule->maximpressions == null) $schedule->maximpressions = '0';
		if($stat->clicks == null) $stat->clicks = '0';
		if($stat->impressions == null) $stat->impressions = '0';

		if($adrotate_debug['general'] == true) {
			echo "<p><strong>[DEBUG][adrotate_filter_schedule()] Schedule and limits</strong><pre>";
			echo 'Now: '.date("j M Y G:i",$now).' (According to server)<br>';
			echo 'Saved start time: '.date("j M Y G:i",$schedule->starttime).'<br>';
			echo 'Saved stop time: '.date("j M Y G:i",$schedule->stoptime).'<br>';
			echo '<br>';
			echo 'Max. allowed clicks: '.$schedule->maxclicks.'<br>';
			echo 'Recorded clicks for this period: '.$stat->clicks.'<br>';
			echo '<br>';
			echo 'Max. allowed impressions: '.$schedule->maximpressions.'<br>';
			echo 'Recorded impressions for this period: '.$stat->impressions.'<br>';
			echo "</pre></p>"; 
		}
	
		// Ad exceeded max clicks?
		if($stat->clicks >= $schedule->maxclicks AND $schedule->maxclicks > 0 AND $banner->tracker == "Y") {
			$selected = array_diff_key($selected, array($banner->id => 0));
		}
	
		// Ad exceeded max impressions?
		if($stat->impressions >= $schedule->maximpressions AND $schedule->maximpressions > 0) {
			$selected = array_diff_key($selected, array($banner->id => 0));
		}

		// Check if ad falls within time limits
		if($schedule->starttime > $now OR $schedule->stoptime < $now) {
			$current[] = 0;
		} else {
			$current[] = 1;
		}
		
		unset($schedule, $stat);
	}
	
	if($adrotate_debug['general'] == true) {
		echo "<p><strong>[DEBUG][adrotate_filter_schedule()] Current</strong><pre>";
		print_r($current); 
		echo "</pre></p>"; 
	}
	
	// Remove advert from array if all schedules are false (0)
	if(!in_array(1, $current)) {
		$selected = array_diff_key($selected, array($banner->id => 0));
	}
	unset($current);
	
	return $selected;
} 

/*-------------------------------------------------------------
 Name:      adrotate_filter_timeframe

 Purpose:   Determine the active time and its limits and filter out expired ads
 Receive:  	$selected, $ad
 Return:    $selected
 Since:		3.6.11
-------------------------------------------------------------*/
function adrotate_filter_timeframe($selected, $banner) { 
	global $wpdb, $adrotate_debug;

	// Determine timeframe limits
	if($banner->timeframe == 'hour') {
		$impression_start	= gmmktime(gmdate('H'), 0, 0); // Start of hour
		$impression_end		= gmmktime(gmdate('H'), 59, 59); // End of hour
		$multiplier = 3600 * ($banner->timeframelength - 1);
		$impression_end = $impression_end + $multiplier;
	} else if($banner->timeframe == 'day') {
		$impression_start	= gmmktime(0, 0, 0, gmdate("m")  , gmdate("d")); // Start of day
		$impression_end		= gmmktime(23, 59, 59, gmdate('m'), gmdate("d")); // End of day
		$multiplier = 86400 * ($banner->timeframelength - 1);
		$impression_end = $impression_end + $multiplier;
	} else if($banner->timeframe == 'week') {
		$impression_start	= strtotime('Last Monday', time()); // Start of week
		$impression_end		= strtotime('Next Sunday', time()); // End of week
		$multiplier = 604800 * ($banner->timeframelength - 1);
		$impression_end = $impression_end + $multiplier;
	} else if($banner->timeframe == 'month') {
		$impression_start	= gmmktime(0, 0, 0, date('m'), 01); // Start of month
		$impression_end		= gmmktime(23, 59, 59, date('m')+$banner->timeframelength, 00); // End of month
	}

	// Set addition to query
	$timeframe_stat = $wpdb->get_row("
		SELECT 
			SUM(`clicks`) as `clicks`, 
			SUM(`impressions`) as `impressions` 
		FROM 
			`".$wpdb->prefix."adrotate_stats_tracker` 
		WHERE 
			`ad` = '$banner->id' 
			AND `thetime` >= '$impression_start' 
			AND `thetime` <= '$impression_end'
		;");

	if($timeframe_stat) {
		if($timeframe_stat->clicks == null) $timeframe_stat->clicks = '0';
		if($timeframe_stat->impressions == null) $timeframe_stat->impressions = '0';
	
		if($adrotate_debug['general'] == true) {
			echo "<p><strong>[DEBUG][adrotate_filter_timeframe()] Ad (id: ".$banner->id.") Timeframe</strong><pre>";
			echo "Timeframe: ".$banner->timeframe;
			echo "<br />Start: ".$impression_start." (".gmdate("F j, Y, g:i a", $impression_start).")";
			echo "<br />End: ".$impression_end." (".gmdate("F j, Y, g:i a", $impression_end).")";
			echo "<br />Clicks this period: ".$timeframe_stat->clicks;
			echo "<br />Impressions this period: ".$timeframe_stat->impressions;
			echo "</pre></p>";
		}
	
		if($timeframe_stat->clicks > $banner->timeframeclicks AND $banner->timeframeclicks > 0) {
			$selected = array_diff_key($selected, array($banner->id => 0));
		}
		if($timeframe_stat->impressions > $banner->timeframeimpressions AND $banner->timeframeimpressions > 0) {
			$selected = array_diff_key($selected, array($banner->id => 0));
		}
	}

	return $selected;
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
 Name:      adrotate_dropdown_categories

 Purpose:   Create dropdown menu of all categories.
 Receive:   $savedcats, $count, $child_of, $parent
 Return:    $output
 Since:		3.7rc8
-------------------------------------------------------------*/
function adrotate_dropdown_categories($savedcats, $count = 2, $child_of = 0, $parent = 0) {
	if(!is_array($savedcats)) $savedcats = explode(',', $savedcats);
	$categories = get_categories(array('child_of' => $parent, 'parent' => $parent,  'orderby' => 'id', 'order' => 'asc', 'hide_empty' => 0));

	if(!empty($categories)) {
		$output = '';
		foreach($categories as $category) {
			if($category->parent > 0) {
				if($category->parent != $child_of) { 
					$count = $count + 1;
				}
				$indent = '&nbsp;'.str_repeat('-', $count * 2).'&nbsp;';
			} else {
				$indent = '';
			}
			$output .= '<option value="'.$category->cat_ID.'"';
			if(in_array($category->cat_ID, $savedcats)) {
				$output .= ' selected';
			}
			$output .= '>'.$indent.$category->name.' ('.$category->category_count.')</option>';
			$output .= adrotate_dropdown_categories($savedcats, $count, $category->parent, $category->cat_ID);
			$child_of = $parent;
		}
	}

	return $output;
}

/*-------------------------------------------------------------
 Name:      adrotate_dropdown_pages

 Purpose:   Create dropdown menu of all pages.
 Receive:   $savedcats, $count, $child_of, $parent
 Return:    $output
 Since:		3.7rc8
-------------------------------------------------------------*/
function adrotate_dropdown_pages($savedpages, $count = 2, $child_of = 0, $parent = 0) {
	if(!is_array($savedpages)) $savedpages = explode(',', $savedpages);
	$pages = get_pages(array('child_of' => $parent, 'parent' => $parent, 'sort_column' => 'ID', 'sort_order' => 'asc'));

	if(!empty($pages)) {
		$output = '';
		foreach($pages as $page) {
			if($page->post_parent > 0) {
				if($page->post_parent != $child_of) {
					$count = $count + 1;
				}
				$indent = '&nbsp;'.str_repeat('-', $count * 2).'&nbsp;';
			} else {
				$indent = '';
			}
			$output .= '<option value="'.$page->ID.'"';
			if(in_array($page->ID, $savedpages)) {
				$output .= ' selected';
			}
			$output .= '>'.$indent.$page->post_title.'</option>';
			$output .= adrotate_dropdown_pages($savedpages, $count, $page->post_parent, $page->ID);
			$child_of = $parent;
		}
	}

	return $output;
}

/*-------------------------------------------------------------
 Name:      adrotate_prepare_evaluate_ads

 Purpose:   Initiate evaluations for errors and determine the ad status
 Receive:   -None-
 Return:    -None-
 Since:		3.6.5
-------------------------------------------------------------*/
function adrotate_prepare_evaluate_ads() {
	global $wpdb;
	
	// Fetch ads
	$ads = $wpdb->get_results("SELECT `id`, `type` FROM `".$wpdb->prefix."adrotate`WHERE `type` != 'disabled' AND `type` != 'empty' ORDER BY `id` ASC;");

	// Determine error states
	$error = $expired = $expiressoon = 0;
	foreach($ads as $ad) {
		$result = adrotate_evaluate_ad($ad->id);
		if($result == 'error' OR $result == 'expired') {
			if($result == 'expired')
				$expired++;
			if($result == 'error')
				$error++;
			$wpdb->update($wpdb->prefix."adrotate", array('type' => 'error'), array('id' => $ad->id));
		} else if($result == 'expires2days' OR $result == 'expires7days' OR $result == 'normal') {
			if($result == 'expires2days' OR $result == 'expires7days')
				$expiressoon++;
			if($result == 'normal')
				$normal++;
			$wpdb->update($wpdb->prefix."adrotate", array('type' => 'active'), array('id' => $ad->id));
		} else {
			continue;	
		}
	}

	$count = $expired + $expiressoon + $error;
	$result = array('error' => $error,
					'expired' => $expired,
					'expiressoon' => $expiressoon,
					'normal' => $normal,
					'total' => $count
					);

	update_option('adrotate_advert_status', $result);
}

/*-------------------------------------------------------------
 Name:      adrotate_evaluate_ad

 Purpose:   Evaluates ads for errors
 Receive:   $ad_id
 Return:    boolean
 Since:		3.6.5
-------------------------------------------------------------*/
function adrotate_evaluate_ad($ad_id) {
	global $wpdb;
	
	$now = current_time('timestamp');
	$in2days = $now + 172800;
	$in7days = $now + 604800;

	// Fetch ad
	$ad = $wpdb->get_row("SELECT `bannercode`, `tracker`, `link`, `imagetype`, `image` FROM `".$wpdb->prefix."adrotate` WHERE `id` = '$ad_id';");
	$advertiser = $wpdb->get_var("SELECT `user` FROM `".$wpdb->prefix."adrotate_linkmeta` WHERE `ad` = '$ad_id' AND `group` = 0 AND `block` = 0 AND `user` > 0;");
	$schedules = $wpdb->get_var("SELECT COUNT(*) FROM `".$wpdb->prefix."adrotate_schedule` WHERE `ad` = '$ad_id';");
	$stoptime = $wpdb->get_var("SELECT `stoptime` FROM `".$wpdb->prefix."adrotate_schedule` WHERE `ad` = '$ad_id' ORDER BY `stoptime` DESC LIMIT 1;");

	// Determine error states
	if(
		strlen($ad->bannercode) < 1 																	// AdCode empty
		OR ($ad->tracker == 'N' AND strlen($ad->link) < 1 AND $advertiser > 0) 							// Didn't enable click-tracking, didn't provide a link, DID set a advertiser
		OR ($ad->tracker == 'Y' AND strlen($ad->link) < 1) 												// Enabled clicktracking but provided no url (link)
		OR ($ad->tracker == 'N' AND strlen($ad->link) > 0) 												// Didn't enable click-tracking but did provide an url (link)
		OR (!preg_match("/%link%/i", $ad->bannercode) AND $ad->tracker == 'Y')							// Didn't use %link% but enabled clicktracking
		OR (preg_match("/%link%/i", $ad->bannercode) AND $ad->tracker == 'N')							// Did use %link% but didn't enable clicktracking
		OR (!preg_match("/%image%/i", $ad->bannercode) AND $ad->image != '' AND $ad->imagetype != '')	// Didn't use %image% but selected an image
		OR (preg_match("/%image%/i", $ad->bannercode) AND $ad->image == '' AND $ad->imagetype == '')	// Did use %image% but didn't select an image
		OR ($ad->image == '' AND $ad->imagetype != '')													// Image and Imagetype mismatch
		OR ($ad->image != '' AND $ad->imagetype == '')													// Image and Imagetype mismatch
		OR $schedules < 1																				// No Schedules
	) {
		return 'error';
	} else if($stoptime <= $now){
		return 'expired';
	} else if($stoptime <= $in2days AND $stoptime >= $now){
		return 'expires2days';
	} else if($stoptime <= $in7days AND $stoptime >= $now){
		return 'expires7days';
	} else {
		return 'normal';
	}
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
	$wpdb->query("DELETE FROM `".$wpdb->prefix."adrotate_tracker` WHERE `timer` < ".$removeme.";");
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
	$stats['banners'] 				= $wpdb->get_var("SELECT COUNT(*) FROM `".$wpdb->prefix."adrotate` WHERE `type` = 'active';");
	$stats['tracker']				= $wpdb->get_var("SELECT COUNT(*) FROM `".$wpdb->prefix."adrotate` WHERE `tracker` = 'Y' AND `type` = 'active';");
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
												AND `".$prefix."adrotate`.`type` = 'active' 
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
												AND `".$prefix."adrotate`.`type` = 'active' 
												AND `".$prefix."adrotate_linkmeta`.`user` = '$user' 
											ORDER BY 
												`".$prefix."adrotate_stats_tracker`.`clicks` ASC 
											LIMIT 1;"
											, ARRAY_A);
		$stats['ad_amount']	= count($ads);

		$x = 0;
		foreach($ads as $ad) {
			// Fetch data
			$meta = $wpdb->get_row("SELECT * FROM `".$prefix."adrotate` WHERE `id` = '$ad->ad' GROUP BY `id`;");
			$startshow = $wpdb->get_var("SELECT `starttime` FROM `".$wpdb->prefix."adrotate_schedule` WHERE `ad` = '".$ad->ad."' ORDER BY `starttime` ASC LIMIT 1;");
			$endshow = $wpdb->get_var("SELECT `stoptime` FROM `".$wpdb->prefix."adrotate_schedule` WHERE `ad` = '".$ad->ad."' ORDER BY `stoptime` DESC LIMIT 1;");
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
			$adstats[$x]['startshow']				= $startshow;
			$adstats[$x]['endshow']					= $endshow;
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
 Name:      adrotate_head

 Purpose:   Add jQuery/JS code to <head>
 Receive:   -none-
 Return:    -none-
 Since:		3.6.9
-------------------------------------------------------------*/
function adrotate_head() {
	//wp_enqueue_script('AdRotate-Clicktracker', WP_CONTENT_URL.'/plugins/adrotate/library/clicktracker.js', 'jQuery');
}   

/*-------------------------------------------------------------
 Name:      adrotate_colorpicker

 Purpose:   Load scripts for the colorpicker
 Receive:   -none-
 Return:    -none-
 Since:		3.7rc6
-------------------------------------------------------------*/
function adrotate_colorpicker() {
  	wp_enqueue_style( 'farbtastic' );
  	wp_enqueue_script( 'farbtastic' );
}

/*-------------------------------------------------------------
 Name:      adrotate_check_config

 Purpose:   Update the options
 Receive:   -none-
 Return:    -none-
 Since:		0.1
-------------------------------------------------------------*/
function adrotate_check_config() {
	
	$config 	= get_option('adrotate_config');
	$crawlers 	= get_option('adrotate_crawlers');
	$debug 		= get_option('adrotate_debug');

	if($config['advertiser'] == '' OR !isset($config['advertiser'])) 				$config['advertiser']			= 'switch_themes'; 	// Admin
	if($config['global_report'] == '' OR !isset($config['global_report'])) 			$config['global_report']		= 'switch_themes'; 	// Admin
	if($config['ad_manage'] == '' OR !isset($config['ad_manage'])) 					$config['ad_manage'] 			= 'switch_themes'; 	// Admin
	if($config['ad_delete'] == '' OR !isset($config['ad_delete'])) 					$config['ad_delete']			= 'switch_themes'; 	// Admin
	if($config['group_manage'] == '' OR !isset($config['group_manage'])) 			$config['group_manage']			= 'switch_themes'; 	// Admin
	if($config['group_delete'] == '' OR !isset($config['group_delete'])) 			$config['group_delete']			= 'switch_themes'; 	// Admin
	if($config['block_manage'] == '' OR !isset($config['block_manage'])) 			$config['block_manage']			= 'switch_themes'; 	// Admin
	if($config['block_delete'] == '' OR !isset($config['block_delete'])) 			$config['block_delete']			= 'switch_themes'; 	// Admin
	if($config['moderate'] == '' OR !isset($config['moderate'])) 					$config['moderate']				= 'switch_themes'; 	// Admin
	if($config['moderate_approve'] == '' OR !isset($config['moderate_approve'])) 	$config['moderate_approve']		= 'switch_themes'; 	// Admin

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
	if($debug['track'] == '' OR !isset($debug['track'])) 							$debug['track'] 				= false;
	if($debug['upgrade'] == '' OR !isset($debug['upgrade'])) 						$debug['upgrade'] 				= false;
	update_option('adrotate_debug', $debug);

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
 Name:      adrotate_get_sorted_roles

 Purpose:   Returns all roles and capabilities, sorted by user level. Lowest to highest. (Code based on NextGen Gallery)
 Receive:   -none-
 Return:    $sorted
 Since:		3.2
-------------------------------------------------------------*/
function adrotate_get_sorted_roles() {	
	global $wp_roles;

	$editable_roles = apply_filters('editable_roles', $wp_roles->roles);
	$sorted = array();
	
	foreach($editable_roles as $role => $details) {
		$sorted[$details['name']] = get_role($role);
	}

	$sorted = array_reverse($sorted);

	return $sorted;
}

/*-------------------------------------------------------------
 Name:      adrotate_set_capability

 Purpose:   Grant or revoke capabilities to a role (Code borrowed from NextGen Gallery)
 Receive:   $lowest_role, $capability
 Return:    -None-
 Since:		3.2
-------------------------------------------------------------*/
function adrotate_set_capability($lowest_role, $capability){

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

	$check_order = adrotate_get_sorted_roles();

	foreach($check_order as $role) {
		$role = get_role($role->name);
		$role->remove_cap($capability);
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
	global $adrotate_config, $adrotate_advert_status;
	
	$emails = $adrotate_config['notification_email'];
	$x = count($emails);
	if($x == 0) $emails = array(get_option('admin_email'));
	
	$blogname 		= get_option('blogname');
	$siteurl 		= get_option('siteurl');
	$dashboardurl	= $siteurl."/wp-admin/admin.php?page=adrotate";
	$pluginurl		= "http://www.adrotateplugin.com";

	$data = $adrotate_advert_status;
	for($i=0;$i<$x;$i++) {
		if($data['total'] > 0) {
		    $headers = "MIME-Version: 1.0" . "\r\n" .
		      		  "Content-Type: text/html; charset=iso-8859-1" . "\r\n" .
		      		  "From: $author <".$emails[$i].">" . "\r\n";

			$subject = __('[AdRotate Alert] Your ads need your help!', 'adrotate');
			
			$message = "<p>".__('Hello', 'adrotate').",</p>";
			$message .= "<p>".__('This notification is send to you from your website', 'adrotate')." '$blogname'.</p>";
			$message .= "<p>".__('You will receive a notification approximately every 24 hours until the issues are resolved.', 'adrotate')."</p>";
			$message .= "<p>".__('Current issues:', 'adrotate')."<br />";
			if($data['error'] > 0) $message .= $data['error']." ".__('ad(s) have configuration errors. This needs your immediate attention!', 'adrotate')."<br />";
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
	    $headers 		= "MIME-Version: 1.0" . "\r\n" .
	      				  "Content-Type: text/html; charset=iso-8859-1" . "\r\n" .
	      				  "From: $author <$useremail>" . "\r\n";
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
 Name:      adrotate_mail_beta

 Purpose:   Send beta feedback
 Receive:   -None-
 Return:    -None-
 Since:		3.6.11
-------------------------------------------------------------*/
function adrotate_mail_beta() {
	global $wpdb, $adrotate_config;

	$author 		= $_POST['adrotate_username'];
	$useremail 		= $_POST['adrotate_email'];
	$version 		= $_POST['adrotate_version'];
	$text	 		= strip_tags(stripslashes(trim($_POST['adrotate_message'], "\t\n ")));

	if(strlen($text) < 1) {
		adrotate_return('beta_mail_empty');
	} else {
		$wpurl			= get_bloginfo('wpurl');
		$wpversion		= get_bloginfo('version');
		$wpcharset		= get_bloginfo('charset');
		$wplang			= get_bloginfo('language');
		$pluginurl		= "http://www.adrotateplugin.com";
	
	    $headers 		= "MIME-Version: 1.0" . "\r\n" .
	      				  "Content-Type: text/html; charset=iso-8859-1" . "\r\n" .
	      				  "From: $author <$useremail>" . "\r\n" .
						  "Cc: $useremail" . "\r\n";

		$subject = "[AdRotate Beta] Feedback from $author!";
	
		$message = "<p>Hello,</p>";
		$message .= "<p>From: $author<br />Website: $wpurl<br />WordPress Version: $wpversion<br />WordPress Language: $wplang<br />WordPress Charset: $wpcharset<br />AdRotate Version: $version</p>";	
		$message .= "<p>Attached message: $text</p>";
		$message .= "<p>You can reply to this message to contact $author.<br />";
		$message .= "</p>";

		wp_mail("feedback@adrotateplugin.com", $subject, $message, $headers);
		adrotate_return('beta_mail_sent');
	}
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
function adrotate_reccurences($schedules) {
	$schedules['1day'] = array(
		'interval' => 86400,
		'display' => __('Daily', 'adrotate')
	);
	$schedules['3hour'] = array(
		'interval' => 10800,
		'display' => __('Every 3 hours', 'adrotate')
	);
	return $schedules;
}

/*-------------------------------------------------------------
 Name:      adrotate_export_csv

 Purpose:   Export CSV data of given month
 Receive:   -- None --
 Return:    -- None --
 Since:		3.6.11
-------------------------------------------------------------*/
function adrotate_export_csv() {
	
	global $wpdb;
	
	$id	 				= strip_tags(htmlspecialchars(trim($_POST['adrotate_export_id'], "\t\n "), ENT_QUOTES));
	$type	 			= strip_tags(htmlspecialchars(trim($_POST['adrotate_export_type'], "\t\n "), ENT_QUOTES));
	$month	 			= strip_tags(htmlspecialchars(trim($_POST['adrotate_export_month'], "\t\n "), ENT_QUOTES));
	$year	 			= strip_tags(htmlspecialchars(trim($_POST['adrotate_export_year'], "\t\n "), ENT_QUOTES));

	$csv_emails = trim($_POST['adrotate_export_addresses']);
	if(strlen($csv_emails) > 0) {
		$csv_emails = explode(',', trim($csv_emails));
		foreach($csv_emails as $csv_email) {
			$csv_email = strip_tags(htmlspecialchars(trim($csv_email), ENT_QUOTES));
			if(strlen($csv_email) > 0) {
					if(preg_match("/^[a-z0-9]+([_\\.-][a-z0-9]+)*@([a-z0-9]+([\.-][a-z0-9]+)*)+\\.[a-z]{2,}$/i", $csv_email) ) {
					$clean_advertiser_email[] = $csv_email;
				}
			}
		}
		$emails = array_unique(array_slice($clean_advertiser_email, 0, 3));
	} else {
		$emails = array();
	}
	
	$emailcount = count($emails);

	if($month == 0) {
		$from = gmmktime(0,0,0,1,1,$year);
		$until = gmmktime(0,0,0,12,31,$year);
	} else {
		$from = gmmktime(0,0,0,$month,1,$year);
		$until = gmmktime(0,0,0,$month+1,0,$year);
	}
	$now = time();
	$from_name = date_i18n("M-d-Y", $from);
	$until_name = date_i18n("M-d-Y", $until);

	$generated = array("Generated on ".date_i18n("M d Y, H:i"));

	if($type == "single" OR $type == "group" OR $type == "block" OR $type == "global") {
		if($type == "single") {
			$ads = $wpdb->get_results("SELECT `thetime`, SUM(`clicks`) as `clicks`, SUM(`impressions`) as `impressions` FROM `".$wpdb->prefix."adrotate_stats_tracker` WHERE (`thetime` >= '".$from."' AND `thetime` <= '".$until."') AND `ad` = '".$id."' GROUP BY `thetime` ASC;");
			$title = $wpdb->get_var("SELECT `title` FROM `".$wpdb->prefix."adrotate` WHERE `id` = '".$id."';");
	
			$filename = "Single-ad ID".$id." - ".$from_name." to ".$until_name." - exported ".$now.".csv";
			$topic = array("Report for ad '".$title."'");
			$period = array("Period - From: ".$from_name." Until: ".$until_name);
			$keys = array("Day", "Clicks", "Impressions");
		}
	
		if($type == "group") {
			$ads = $wpdb->get_results("SELECT `thetime`, SUM(`clicks`) as `clicks`, SUM(`impressions`) as `impressions` FROM `".$wpdb->prefix."adrotate_stats_tracker` WHERE (`thetime` >= '".$from."' AND `thetime` <= '".$until."') AND  `group` = '".$id."' GROUP BY `thetime` ASC;");
			$title = $wpdb->get_var("SELECT `name` FROM `".$wpdb->prefix."adrotate_groups` WHERE `id` = '".$id."';");
	
			$filename = "Ad Group ID".$id." - ".$from_name." to ".$until_name." - exported ".$now.".csv";
			$topic = array("Report for group '".$title."'");
			$period = array("Period - From: ".$from_name." Until: ".$until_name);
			$keys = array("Day", "Clicks", "Impressions");
		}
	
		if($type == "block") {
			$ads = $wpdb->get_results("SELECT `thetime`, SUM(`clicks`) as `clicks`, SUM(`impressions`) as `impressions` FROM `".$wpdb->prefix."adrotate_stats_tracker` WHERE (`thetime` >= '".$from."' AND `thetime` <= '".$until."') AND  `block` = '".$id."' GROUP BY `thetime` ASC;");
			$title = $wpdb->get_var("SELECT `name` FROM `".$wpdb->prefix."adrotate_blocks` WHERE `id` = '".$id."';");
	
			$filename = "Ad Block ID".$id." - ".$from_name." to ".$until_name." - exported ".$now.".csv";
			$topic = array("Report for ad '".$title."'");
			$period = array("Period - From: ".$from_name." Until: ".$until_name);
			$keys = array("Day", "Clicks", "Impressions");
		}
		
		if($type == "global") {
			$ads = $wpdb->get_results("SELECT `thetime`, SUM(`clicks`) as `clicks`, SUM(`impressions`) as `impressions` FROM `".$wpdb->prefix."adrotate_stats_tracker` WHERE `thetime` >= '".$from."' AND `thetime` <= '".$until."' GROUP BY `thetime` ASC;");
	
			$filename = "Global report - ".$from_name." to ".$until_name." - exported ".$now.".csv";
			$topic = array("Global report");
			$period = array("Period - From: ".$from_name." Until: ".$until_name);
			$keys = array("Day", "Clicks", "Impressions");
		}

		$x=0;
		foreach($ads as $ad) {
			// Prevent gaps in display
			if($ad->impressions == 0) 		$ad->impressions 			= 0;
			if($ad->clicks == 0) 			$ad->clicks 				= 0;
	
			// Build array
			$adstats[$x]['day']						= date_i18n("M d Y", $ad->thetime);			
			$adstats[$x]['clicks']					= $ad->clicks;
			$adstats[$x]['impressions']				= $ad->impressions;
			$x++;
		}
	}

	if($type == "advertiser") {
		$ads = $wpdb->get_results("SELECT `ad` FROM `".$wpdb->prefix."adrotate_linkmeta` WHERE `group` = 0 AND `block` = 0 AND `user` = '".$id."' ORDER BY `ad` ASC;");

		$x=0;
		foreach($ads as $ad) {
			$title = $wpdb->get_var("SELECT `title` FROM `".$wpdb->prefix."adrotate` WHERE `id` = '".$ad->ad."';");
			$startshow = $wpdb->get_var("SELECT `starttime` FROM `".$wpdb->prefix."adrotate_schedule` WHERE `ad` = '".$ad->ad."' ORDER BY `starttime` ASC LIMIT 1;");
			$endshow = $wpdb->get_var("SELECT `stoptime` FROM `".$wpdb->prefix."adrotate_schedule` WHERE `ad` = '".$ad->ad."' ORDER BY `stoptime` DESC LIMIT 1;");
			$username = $wpdb->get_var("SELECT `display_name` FROM `$wpdb->users` WHERE `ID` = '".$id."' ORDER BY `user_nicename` ASC;");
			$stat = $wpdb->get_row("SELECT SUM(`clicks`) as `clicks`, SUM(`impressions`) as `impressions` FROM `".$wpdb->prefix."adrotate_stats_tracker` WHERE `ad` = '$ad->ad';");
			
			// Prevent gaps in display
			if($stat->impressions == 0) 		$stat->impressions 			= 0;
			if($stat->clicks == 0) 				$stat->clicks 				= 0;
			if($stat->impressions == 0 AND $stat->clicks == 0) {
				$ctr = "0";
			} else {
				$ctr = round((100/$stat->impressions) * $stat->clicks,2);
			}

			// Build array
			$adstats[$x]['title']					= $title;			
			$adstats[$x]['id']						= $ad->ad;			
			$adstats[$x]['startshow']				= date_i18n("M d Y", $startshow);
			$adstats[$x]['endshow']					= date_i18n("M d Y", $endshow);
			$adstats[$x]['clicks']					= $stat->clicks;
			$adstats[$x]['impressions']				= $stat->impressions;
			$adstats[$x]['ctr']						= $ctr;
			$x++;
		}
		
		$filename = "Advertiser - ".$username." - export.csv";
		$topic = array("Advertiser report for ".$username);
		$period = array("Period - Not Applicable");
		$keys = array("Title", "Ad ID", "First visibility", "Last visibility", "Clicks", "Impressions", "CTR (%)");
	}

 	if($adstats) {
		if($emailcount > 0) {
			$fp = fopen(WP_CONTENT_DIR . '/reports/'.$filename, 'w');
		} else {
			header('Content-Type: text/csv');
			header('Content-Disposition: attachment;filename='.$filename);
			$fp = fopen('php://output', 'w');
		}
		
		fputcsv($fp, $topic);
		fputcsv($fp, $period);
		fputcsv($fp, $generated);
		fputcsv($fp, $keys);
		foreach($adstats as $stat) {
			fputcsv($fp, $stat);
		}
		
		fclose($fp);
		
		if($emailcount > 0) {
			$y = count($emails);

			$attachments = array(WP_CONTENT_DIR . '/reports/'.$filename);
		 	$siteurl 	= get_option('siteurl');
			$pluginurl	= "http://www.adrotateplugin.com";
			$email 		= get_option('admin_email');

		    $headers = "MIME-Version: 1.0\n" .
      				 	"From: AdRotate Plugin <".$email.">\r\n\n" . 
      				  	"Content-Type: text/html; charset=\"" . get_settings('blog_charset') . "\"\n";

			$subject = __('[AdRotate] CSV Report!', 'adrotate');
			
			$message = 	"<p>".__('Hello', 'adrotate').",</p>";
			$message .= "<p>".__('Attached in this email you will find the exported CSV file you generated on ', 'adrotate')." $siteurl.</p>";
			$message .= "<p>".__('Have a nice day!', 'adrotate')."<br />";
			$message .= __('Your AdRotate Notifier', 'adrotate')."<br />";
			$message .= "$pluginurl</p>";
	
			for($i=0;$i<$emailcount;$i++) {
	 			wp_mail($emails[$i], $subject, $message, $headers, $attachments);
	 		}	
			adrotate_return('csv_success', array('adrotate', 'csvmail'));
		} else {
			// for some reason, downloading an attachment requires this exit;
			exit;
		}
	} else {
		if($type == "single")
			adrotate_return('csv_error', array('adrotate', 'nodata', '&view=report', '&ad='.$id));
		if($type == "group")
			adrotate_return('csv_error', array('adrotate-groups', 'nodata', '&view=report', '&group='.$id));
		if($type == "block")
			adrotate_return('csv_error', array('adrotate-blocks', 'nodata', '&view=report', '&block='.$id));
		if($type == "global")
			adrotate_return('csv_error', array('adrotate-global-report', 'nodata'));
		if($type == "advertiser")
			adrotate_return('csv_error', array('adrotate-advertiser-report', 'nodata'));
	}
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
 Receive:   $action, $arg (array)
 Return:    -none-
 Since:		0.2
 Usage:		array('wp_page', 'message', 'arguments', 'id')
-------------------------------------------------------------*/
function adrotate_return($action, $arg = null) {

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

		// Reports
		case "csv_error" :
			wp_redirect('admin.php?page='.$arg[0].'&message='.$arg[1].$arg[2].$arg[3]);
		break;

		case "csv_success" :
			wp_redirect('admin.php?page='.$arg[0].'&message='.$arg[1]);
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

		// Misc plugin events
		case "mail_sent" :
			wp_redirect('admin.php?page=adrotate-advertiser-report&message=mail_sent');
		break;

		case "beta_mail_sent" :
			wp_redirect('admin.php?page=adrotate-beta&message=sent');
		break;

		case "beta_mail_empty" :
			wp_redirect('admin.php?page=adrotate-beta&message=empty');
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
?>