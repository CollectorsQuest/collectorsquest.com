<?php
/*  
Copyright 2010-2011 Arnan de Gans  (email : adegans@meandmymac.net)
*/

/*-------------------------------------------------------------
 Name:      adrotate_ad

 Purpose:   Show requested ad
 Receive:   $banner_id, $individual , $group , $block
 Return:    $output
 Since:		3.0
-------------------------------------------------------------*/
function adrotate_ad($banner_id, $individual = true, $group = 0, $block = 0) {
	global $wpdb, $adrotate_config, $adrotate_crawlers, $adrotate_debug;

	/* Changelog:
	// Nov 15 2010 - Moved ad formatting to new function adrotate_ad_output()
	// Dec 10 2010 - Added check for single ad or not. Query updates for 3.0.1.
	// Dec 11 2010 - Check for single ad now works.
	// Dec 13 2010 - Exired/Non-existant error now as a comment
	// Jan 21 2011 - Added debug routine
	// Feb 22 2011 - Updated debug routine with Memory usage
	// Feb 28 2011 - Updated for new statistics system
	// Mar 12 2011 - Added new receiving values $group and $block for stats
	// Mar 25 2011 - Added crawler filter to prevent impressions for bots
	// Jul 6 2011 - Expanded impression filter to not count every page load
	// Jul 11 2011 - Added call to change the impression timer
	*/

	$now 				= date('U');
	$today 				= gmmktime(0, 0, 0, gmdate("n"), gmdate("j"), gmdate("Y"));
	$useragent 			= $_SERVER['HTTP_USER_AGENT'];
	$useragent_trim 	= trim($useragent, ' \t\r\n\0\x0B');

	if($group > 0) $grouporblock = " AND `group` = '$group'";
	if($block > 0) $grouporblock = " AND `block` = '$block'";

	if($banner_id) {
		if($individual == false) { 
			// Coming from a group or block, no checks just load the ad
			$banner = $wpdb->get_row("SELECT `id`, `bannercode`, `tracker`, `link`, `image` FROM `".$wpdb->prefix."adrotate` WHERE `id` = '$banner_id';");
		} else { 
			// Single ad, check if it's ok
			$banner = $wpdb->get_row("SELECT `id`, `bannercode`, `tracker`, `link`, `image` FROM `".$wpdb->prefix."adrotate` WHERE `active` = 'yes' AND `startshow` <= '$now' AND `endshow` >= '$now' AND `id` = '$banner_id';");
		}
		
		if($adrotate_debug['general'] == true) {
			echo "<p><strong>[DEBUG] Selected Ad, specs</strong><pre>";
			$memory = (memory_get_usage() / 1024 / 1024);
			echo "Memory usage: " . round($memory, 2) ." MB <br />"; 
			$peakmemory = (memory_get_peak_usage() / 1024 / 1024);
			echo "Peak memory usage: " . round($peakmemory, 2) ." MB <br />"; 
			print_r($banner); 
			echo "</pre></p>"; 
		}
			
		if($adrotate_debug['timers'] == true) {
			$impression_timer = $now;
		} else {
			$impression_timer = $now - $adrotate_config['impression_timer'];
		}
		
		if($banner) {
			$output = adrotate_ad_output($banner->id, $group, $block, $banner->bannercode, $banner->tracker, $banner->link, $banner->image);

			$remote_ip 	= adrotate_get_remote_ip();
			if(is_array($adrotate_crawlers)) $crawlers = $adrotate_crawlers;
				else $crawlers = array();

			$nocrawler = true;
			foreach($crawlers as $crawler) {
				if(preg_match("/$crawler/i", $useragent)) $nocrawler = false;
			}
			$ip = $wpdb->get_var("SELECT `timer` FROM `".$wpdb->prefix."adrotate_tracker` WHERE `ipaddress` = '$remote_ip' AND `stat` = 'i' AND `bannerid` = '$banner_id' ORDER BY `timer` DESC LIMIT 1;");
			if($ip < $impression_timer AND $nocrawler == true AND (strlen($useragent_trim) > 0 OR !empty($useragent))) {
				$stats = $wpdb->get_var("SELECT `id` FROM `".$wpdb->prefix."adrotate_stats_tracker` WHERE `ad` = '$banner_id'$grouporblock AND `thetime` = '$today';");
				if($stats > 0) {
					$wpdb->query("UPDATE `".$wpdb->prefix."adrotate_stats_tracker` SET `impressions` = `impressions` + 1 WHERE `id` = '$stats';");
				} else {
					$wpdb->query("INSERT INTO `".$wpdb->prefix."adrotate_stats_tracker` (`ad`, `group`, `block`, `thetime`, `clicks`, `impressions`) VALUES ('$banner_id', '$group', '$block', '$today', '0', '1');");
				}
				$wpdb->query("INSERT INTO `".$wpdb->prefix."adrotate_tracker` (`ipaddress`, `timer`, `bannerid`, `stat`, `useragent`) VALUES ('$remote_ip', '$now', '$banner_id', 'i', '');");
			}
		} else {
			$output = adrotate_error('ad_expired', array($banner_id));
		}
	} else {
		$output = adrotate_error('ad_no_id');
	}
	return $output;
}

/*-------------------------------------------------------------
 Name:      adrotate_group

 Purpose:   Fetch ads in specified group(s) and show a random ad
 Receive:   $group_ids, $fallback, $weight
 Return:    $output
 Since:		3.0
-------------------------------------------------------------*/
function adrotate_group($group_ids, $fallback = 0, $weight = 0) {
	global $wpdb, $adrotate_debug;

	/* Changelog:
	// Dec 10 2010 - $fallback now works. Query updated.
	// Jan 05 2011 - Added support for weight system
	// Jan 16 2011 - Added support for weight override
	// Jan 21 2011 - Added debug routine
	// Feb 22 2011 - Updated debug routine with Memory usage
	// Feb 28 2011 - Updated ad selection for new statistics system
	// Mar 12 2011 - Added use of $group for adrotate_ad()
	// Mar 29 2011 - Renamed $fallbackoverride to $fallback
	// Jul 11 2011 - Removed impression and stats counts AFTER last ad update so the max impressions and clicks works again
	*/

	if($group_ids) {
		$now = current_time('timestamp');
		$group_array = explode(",", $group_ids);
		$group_choice = array_rand($group_array, 1);
		$prefix = $wpdb->prefix;

		if($fallback == 0 OR $fallback == '') {
			$fallback = $wpdb->get_var("SELECT `fallback` FROM `".$prefix."adrotate_groups` WHERE `id` = '$group_array[$group_choice]';");
		}
		
		if($weight > 0) {
			$weightoverride = "	AND `".$prefix."adrotate`.`weight` >= '$weight'";
		}
		
		if($adrotate_debug['general'] == true) {
			echo "<p><strong>[DEBUG] Group array</strong><pre>"; 
			$memory = (memory_get_usage() / 1024 / 1024);
			echo "Memory usage: " . round($memory, 2) ." MB <br />"; 
			$peakmemory = (memory_get_peak_usage() / 1024 / 1024);
			echo "Peak memory usage: " . round($peakmemory, 2) ." MB <br />"; 
			print_r($group_array); 
			echo "</pre></p>"; 
		}			

		$results = $wpdb->get_results("
			SELECT 
				`".$prefix."adrotate`.`id`, 
				`".$prefix."adrotate`.`maxclicks`, 
				`".$prefix."adrotate`.`maxshown`, 
				`".$prefix."adrotate`.`tracker`, 
				`".$prefix."adrotate`.`weight`, 
				`".$prefix."adrotate`.`updated`
			FROM 
				`".$prefix."adrotate`, 
				`".$prefix."adrotate_linkmeta` 
			WHERE 
				`".$prefix."adrotate_linkmeta`.`group` = '$group_array[$group_choice]' 
				AND `".$prefix."adrotate_linkmeta`.`block` = 0 
				AND `".$prefix."adrotate_linkmeta`.`user` = 0
				AND `".$prefix."adrotate`.`id` = `".$prefix."adrotate_linkmeta`.`ad`
				AND `".$prefix."adrotate`.`active` = 'yes'
				AND `".$prefix."adrotate`.`type` = 'manual'
				AND `".$prefix."adrotate`.`startshow` <= '$now' 
				AND `".$prefix."adrotate`.`endshow` >= '$now'
				".$weightoverride."
			;");

		if($results) {
			if($adrotate_debug['general'] == true) {
				echo "<p><strong>[DEBUG] Initial selection</strong><pre>"; 
				$memory = (memory_get_usage() / 1024 / 1024);
				echo "Memory usage: " . round($memory, 2) ." MB <br />"; 
				$peakmemory = (memory_get_peak_usage() / 1024 / 1024);
				echo "Peak memory usage: " . round($peakmemory, 2) ." MB <br />"; 
				print_r($results); 
				echo "</pre></p>"; 
			}			

			foreach($results as $result) {
				$stats = $wpdb->get_row("SELECT SUM(`clicks`) as `clicks`, SUM(`impressions`) as `impressions` FROM `".$wpdb->prefix."adrotate_stats_tracker` WHERE `ad` = '$result->id';");

				if($stats->clicks == null) $stats->clicks = '0';
				if($stats->impressions == null) $stats->impressions = '0';

				if($adrotate_debug['general'] == true) {
					echo "<p><strong>[DEBUG] Stats for ad $result->id since ".gmdate("d-M-Y", $result->updated)."</strong><pre>"; 
					$memory = (memory_get_usage() / 1024 / 1024);
					echo "Memory usage: " . round($memory, 2) ." MB <br />"; 
					$peakmemory = (memory_get_peak_usage() / 1024 / 1024);
					echo "Peak memory usage: " . round($peakmemory, 2) ." MB <br />"; 
					print_r($stats); 
					echo "</pre></p>"; 
				}			

				$selected[$result->id] = $result->weight;

				if($stats->clicks >= $result->maxclicks AND $result->maxclicks > 0 AND $result->tracker == "Y") {
					$selected = array_diff_key($selected, array($result->id => $result->maxclicks));
				}
				if($stats->impressions >= $result->maxshown AND $result->maxshown > 0) {
					$selected = array_diff_key($selected, array($result->id => $result->maxshown));
				}
			}

			if(count($selected) > 0) {
				$banner_id = adrotate_pick_weight($selected);
				
				if($adrotate_debug['general']['userroles'] == true) {
					echo "<p><strong>[DEBUG] Selected ad based on weight</strong><pre>"; 
					$memory = (memory_get_usage() / 1024 / 1024);
					echo "Memory usage: " . round($memory, 2) ." MB <br />"; 
					$peakmemory = (memory_get_peak_usage() / 1024 / 1024);
					echo "Peak memory usage: " . round($peakmemory, 2) ." MB <br />"; 
					print_r($banner_id); 
					echo "</pre></p>"; 
				}			

				$output = adrotate_ad($banner_id, false, $group_array[$group_choice], 0);

			} else {
				$output = adrotate_fallback($fallback, 'expired');
			}
		} else {
			$output = adrotate_fallback($fallback, 'unqualified');
		}
		
		unset($stats);
		unset($results);
		unset($selected);
		
	} else {
		$output = adrotate_error('group_no_id');
	}

	return $output;
}

/*-------------------------------------------------------------
 Name:      adrotate_block

 Purpose:   Fetch all ads in specified groups within block. Show set amount of ads randomly
 Receive:   $block_id, $weight
 Return:    $output
 Since:		3.0
-------------------------------------------------------------*/
function adrotate_block($block_id, $weight = 0) {
	global $wpdb, $adrotate_debug;

	/* Changelog:
	// Dec 10 2010 - Query updates for 3.0.1.
	// Jan 05 2011 - Added support for weight system
	// Jan 15 2011 - Fixed array being made for one group only
	// Jan 21 2011 - Added debug routine
	// Feb 22 2011 - Updated debug routine with Memory usage
	// Feb 28 2011 - Updated ad selection for new statistics system
	// Mar 12 2011 - Added use of $block for adrotate_ad()
	// Apr 2 2011 - Added fallback support
	// Jul 11 2011 - Removed impression and stats counts AFTER last ad update so the max impressions and clicks works again
	*/
	
	if($block_id) {
		$now = current_time('timestamp');
		$prefix = $wpdb->prefix;
		
		// Get block specs
		$block = $wpdb->get_row("SELECT * FROM `".$wpdb->prefix."adrotate_blocks` WHERE `id` = '$block_id';");
		if($block) {
			if($adrotate_debug['general'] == true) {
				echo "<p><strong>[DEBUG] Selected block</strong><pre>"; 
				$memory = (memory_get_usage() / 1024 / 1024);
				echo "Memory usage: " . round($memory, 2) ." MB <br />"; 
				$peakmemory = (memory_get_peak_usage() / 1024 / 1024);
				echo "Peak memory usage: " . round($peakmemory, 2) ." MB <br />"; 
				print_r($block); 
				echo "</pre></p>"; 
			}			

			// Get groups in block
			$groups = $wpdb->get_results("SELECT `group` FROM `".$wpdb->prefix."adrotate_linkmeta` WHERE `ad` = 0 AND `block` = '$block->id' AND `user` = 0;");
			if($groups) {				
				if($adrotate_debug['general'] == true) {
					echo "<p><strong>[DEBUG] Groups in block</strong><pre>"; 
					$memory = (memory_get_usage() / 1024 / 1024);
					echo "Memory usage: " . round($memory, 2) ." MB <br />"; 
					$peakmemory = (memory_get_peak_usage() / 1024 / 1024);
					echo "Peak memory usage: " . round($peakmemory, 2) ." MB <br />"; 
					print_r($groups); 
					echo "</pre></p>"; 
				}			

				if($weight > 0) {
					$weightoverride = "	AND `".$prefix."adrotate`.`weight` >= '$weight'";
				}

				// Get all ads in all groups and process them in an array
				$results = array();
				foreach($groups as $group) {
					$ads = $wpdb->get_results(
						"SELECT 
							`".$prefix."adrotate`.`id`, 
							`".$prefix."adrotate`.`maxclicks`, 
							`".$prefix."adrotate`.`maxshown`, 
							`".$prefix."adrotate`.`tracker`, 
							`".$prefix."adrotate`.`weight`,
							`".$prefix."adrotate`.`updated`
						FROM 
							`".$prefix."adrotate`, 
							`".$prefix."adrotate_linkmeta` 
						WHERE 
							`".$prefix."adrotate_linkmeta`.`group` = '$group->group' 
							AND `".$prefix."adrotate_linkmeta`.`block` = 0 
							AND `".$prefix."adrotate_linkmeta`.`user` = 0 
							AND `".$prefix."adrotate`.`id` = `".$prefix."adrotate_linkmeta`.`ad` 
							AND `".$prefix."adrotate`.`active` = 'yes' 
							AND '$now' >= `".$prefix."adrotate`.`startshow` 
							AND '$now' <= `".$prefix."adrotate`.`endshow` 
							".$weightoverride."
						;");
					$results = array_merge($ads, $results);

					// Build array of fallback groups
					$fallback_array[] = $group->group;
				}

				if($results) {
					// Filter results, make an array of ads
					if($adrotate_debug['general'] == true) {
						echo "<p><strong>[DEBUG] All ads from all groups</strong><pre>"; 
						$memory = (memory_get_usage() / 1024 / 1024);
						echo "Memory usage: " . round($memory, 2) ." MB <br />"; 
						$peakmemory = (memory_get_peak_usage() / 1024 / 1024);
						echo "Peak memory usage: " . round($peakmemory, 2) ." MB <br />"; 
						print_r($results); 
						echo "</pre></p>"; 
					}			

					foreach($results as $result) {
						$stats = $wpdb->get_row("SELECT SUM(`clicks`) as `clicks`, SUM(`impressions`) as `impressions` FROM `".$wpdb->prefix."adrotate_stats_tracker` WHERE `ad` = '$result->id';");

						if($stats->clicks == null) $stats->clicks = '0';
						if($stats->impressions == null) $stats->impressions = '0';

						if($adrotate_debug['general'] == true) {
							echo "<p><strong>[DEBUG] Stats for ad $result->id since ".gmdate("d-M-Y", $result->updated)."</strong><pre>"; 
							$memory = (memory_get_usage() / 1024 / 1024);
							echo "Memory usage: " . round($memory, 2) ." MB <br />"; 
							$peakmemory = (memory_get_peak_usage() / 1024 / 1024);
							echo "Peak memory usage: " . round($peakmemory, 2) ." MB <br />"; 
							print_r($stats); 
							echo "</pre></p>"; 
						}			
	
						$selected[$result->id] = $result->weight;

						if($stats->clicks >= $result->maxclicks AND $result->maxclicks > 0 AND $result->tracker == "Y") {
							$selected = array_diff_key($selected, array($result->id => $result->maxclicks));
						}
						if($stats->impressions >= $result->maxshown AND $result->maxshown > 0) {
							$selected = array_diff_key($selected, array($result->id => $result->maxshown));
						}
					}

					if($adrotate_debug['general'] == true) {
						echo "<p><strong>[DEBUG] Pre-selected ads based on impressions and clicks. (ad_id => weight)</strong><pre>"; 
						$memory = (memory_get_usage() / 1024 / 1024);
						echo "Memory usage: " . round($memory, 2) ." MB <br />"; 
						$peakmemory = (memory_get_peak_usage() / 1024 / 1024);
						echo "Peak memory usage: " . round($peakmemory, 2) ." MB <br />"; 
						print_r($selected); 
						echo "</pre></p>"; 
					}			

				} else {
					// Set fallback group (if any)
					$group_choice = array_rand($fallback_array, 1);
					$fallback = $wpdb->get_var("SELECT `fallback` FROM `".$prefix."adrotate_groups` WHERE `id` = '$fallback_array[$group_choice]';");
					
					if($adrotate_debug['general'] == true) {
						echo "<p><strong>[DEBUG] Selected Fallback Group.</strong><pre>"; 
						$memory = (memory_get_usage() / 1024 / 1024);
						echo "Memory usage: " . round($memory, 2) ." MB <br />"; 
						$peakmemory = (memory_get_peak_usage() / 1024 / 1024);
						echo "Peak memory usage: " . round($peakmemory, 2) ." MB";
						echo "<br />fallback_array "; 
						print_r($fallback_array); 
						echo "<br />group_choice "; 
						print_r($group_choice); 
						echo "<br />fallback_array[group_choice] "; 
						print_r($fallback_array[$group_choice]); 
						echo "<br />Fallback group "; 
						print_r($fallback); 
						echo "</pre></p>"; 
					}			

				}
				
				$array_count = count($selected);

				if($array_count > 0) {
					if($array_count < $block->adcount) { 
						$block_count = $array_count;
					} else { 
						$block_count = $block->adcount;
					}

					$output = '';
					$break = 1;
					for($i=0;$i<$block_count;$i++) {
						$banner_id = adrotate_pick_weight($selected);

						if($adrotate_debug['general'] == true) {
							echo "<p><strong>[DEBUG] Reduced array (Cycle ".$i.")</strong><pre>"; 
							$memory = (memory_get_usage() / 1024 / 1024);
							echo "Memory usage: " . round($memory, 2) ." MB <br />"; 
							print_r($selected); 
							echo "</pre></p>"; 
						}			
	
						if($block->wrapper_before != '')
							$output .= stripslashes(html_entity_decode($block->wrapper_before, ENT_QUOTES));
						$output .= adrotate_ad($banner_id, false, 0, $block_id);
						if($block->wrapper_after != '')
							$output .= stripslashes(html_entity_decode($block->wrapper_after, ENT_QUOTES));
	
						$selected = array_diff_key($selected, array($banner_id => 0));

						if($block->columns > 0 AND $break == $block->columns) {
							$output .= '<br style="height:none; width:none;" />';
							$break = 1;
						} else {
							$break++;
						}

					}
				} else {
					$output = adrotate_error('ad_unqualified');
				}
			}
			
			// Destroy data
			unset($groups);
			unset($results);
			unset($stats);
			unset($selected);
			unset($block);
			unset($fallback_array);
			
		} else {
			$output = adrotate_error('block_not_found', array($block_id));
		}
	} else {
		$output = adrotate_error('block_no_id');
	}

	return $output;
}

/*-------------------------------------------------------------
 Name:      adrotate_preview

 Purpose:   Show preview of selected ad (Dashboard)
 Receive:   $banner_id
 Return:    $output
 Since:		3.0
-------------------------------------------------------------*/
function adrotate_preview($banner_id) {
	global $wpdb, $adrotate_debug;

	/* Changelog:
	// Nov 15 2010 - Moved ad formatting to new function adrotate_ad_output()
	// Jan 21 2011 - Added debug routine
	// Feb 22 2011 - Updated debug routine with Memory usage
	*/
	
	if($banner_id) {
		$now = current_time('timestamp');
		
		$banner = $wpdb->get_row("SELECT * FROM `".$wpdb->prefix."adrotate` WHERE `id` = '$banner_id';");

		if($adrotate_debug['general'] == true) {
			echo "<p><strong>[DEBUG] Ad information</strong><pre>"; 
			$memory = (memory_get_usage() / 1024 / 1024);
			echo "Memory usage: " . round($memory, 2) ." MB <br />"; 
			$peakmemory = (memory_get_peak_usage() / 1024 / 1024);
			echo "Peak memory usage: " . round($peakmemory, 2) ." MB <br />"; 
			print_r($banner); 
			echo "</pre></p>"; 
		}			

		if($banner) {
			$output = adrotate_ad_output($banner->id, 0, 0, $banner->bannercode, $banner->tracker, $banner->link, $banner->image, true);
		} else {
			$output = adrotate_error('ad_not_found');
		}
	} else {
		$output = adrotate_error('ad_no_id');
	}

	return $output;
}

/*-------------------------------------------------------------
 Name:      adrotate_ad_output

 Purpose:   Prepare the output for viewing
 Receive:   $id, $bannercode, $tracker, $link, $image
 Return:    $banner_output
 Since:		3.0
-------------------------------------------------------------*/
function adrotate_ad_output($id, $group = 0, $block = 0, $bannercode, $tracker, $link, $image, $preview = false) {

	$meta = base64_encode("$id,$group,$block,$link");
//	$meta = "$id,$group,$block,$link";
	$now = time();

	$banner_output = $bannercode;
/*
	if($tracker == "Y") {
		if($preview == true) {
			$banner_output = str_replace('%link%', get_option('siteurl').'/wp-content/plugins/adrotate/adrotate-out.php?track='.$meta.'&preview=true', $banner_output);		
		} else {
			$banner_output = str_replace('%link%', get_option('siteurl').'/wp-content/plugins/adrotate/adrotate-out.php?track='.$meta, $banner_output);
		}
	} else {
		$banner_output = str_replace('%link%', $link, $banner_output);
	}
*/
	if($tracker == "Y") {
		$url = add_query_arg('track', $meta, WP_CONTENT_URL."/plugins/adrotate/adrotate-out.php");
		if($preview == true) {
			$url = add_query_arg('preview', 'true', $url);
		}		
		$banner_output = str_replace('%link%', $url, $banner_output);
	} else {
		$banner_output = str_replace('%link%', $link, $banner_output);
	}
	$banner_output = str_replace('%random%', rand(100000,999999), $banner_output);
	$banner_output = str_replace('%image%', $image, $banner_output);
	$banner_output = str_replace('%id%', $id, $banner_output);
	$banner_output = stripslashes(htmlspecialchars_decode($banner_output, ENT_QUOTES));

	return $banner_output;
}

/*-------------------------------------------------------------
 Name:      adrotate_fallback

 Purpose:   Fall back to the set group or show an error if no fallback is set
 Receive:   $group, $case
 Return:    $fallback_output
 Added:		2.6
-------------------------------------------------------------*/
function adrotate_fallback($group, $case) {

	/* Changelog:
	// Dec 10 2010 - No longer double checks for $fallback values
	// Apr 02 2010 - Now also used for blocks
	*/

	if($group > 0) {
		$fallback_output = adrotate_group($group);
	} else {
		if($case == 'expired') {
			$fallback_output = adrotate_error('ad_expired');
		}
		
		if($case == 'unqualified') {
			$fallback_output = adrotate_error('ad_unqualified');
		}
	}
	
	return $fallback_output;
}

/*-------------------------------------------------------------
 Name:      adrotate_meta

 Purpose:   Sidebar meta
 Receive:   -none-
 Return:    -none-
 Since:		0.1
-------------------------------------------------------------*/
function adrotate_meta() {
	global $adrotate_config;

	/* Changelog:
	// Mar 29 2011 - Internationalization support
	*/

	if($adrotate_config['credits'] == "Y") {
		echo "<li>". __('I\'m using', 'adrotate') ." <a href=\"http://www.adrotateplugin.com/\" target=\"_blank\" title=\"AdRotate\">AdRotate</a></li>\n";
	}
}

/*-------------------------------------------------------------
 Name:      adrotate_credits

 Purpose:   Credits shown throughout the plugin
 Receive:   -none-
 Return:    -none-
 Since:		2.?
-------------------------------------------------------------*/
function adrotate_credits() {

	/* Changelog:
	// Mar 29 2011 - Internationalization support
	*/

	echo '<table class="widefat" style="margin-top: .5em">';

	echo '<thead>';
	echo '<tr valign="top">';
	echo '	<th width="40%">AdRotate '.__('Credits', 'adrotate').'</th>';
	echo '	<th>AdRotate '.__('Updates', 'adrotate').'</th>';
	echo '</tr>';
	echo '</thead>';

	echo '<tbody>';
	echo '<tr><td><ul>';
	echo '	<li>'.__('Find my website at', 'adrotate').' <a href="http://meandmymac.net" target="_blank">meandmymac.net</a>.</li>';
	echo '	<li>'.__('Give me your money to', 'adrotate').' <a href="http://meandmymac.net/donate/" target="_blank">'.__('show your appreciation', 'adrotate').'</a>. '.__('Thanks!', 'adrotate').'</li>';
	echo '	<li>'.__('The plugin homepage is at', 'adrotate').' <a href="http://www.adrotateplugin.com/" target="_blank">www.adrotateplugin.com</a>!</li>';
	echo '	<li>'.__('Check out the', 'adrotate').' <a href="http://www.adrotateplugin.com/page/support.php" target="_blank">'.__('knowledgebase', 'adrotate').'</a> '.__('for manuals, general information!', 'adrotate').'</li>';
	echo '	<li>'.__('Premium support and setup assistance!', 'adrotate').' <a href="http://www.adrotateplugin.com/page/services.php" target="_blank">'.__('Hire us', 'adrotate').'</a> '.__(', and see how we can help you!', 'adrotate').'</li>';
	echo '	<li>'.__('Need more help?', 'adrotate').' <a href="http://www.adrotateplugin.com/page/support.php" target="_blank">'.__('Forum support', 'adrotate').'</a> '.__('is available!', 'adrotate').'</li>';
	echo '</ul></td>';
	echo '<td style="border-left:1px #ddd solid;">';
	meandmymac_rss_widget(6);
	echo '</td></tr>';
	echo '</tbody>';

	echo '</table>';
}

/*-------------------------------------------------------------
 Name:      adrotate_user_notice
 
 Purpose:   Credits shown on user statistics
 Receive:   -none-
 Return:    -none-
 Since:		3.0
-------------------------------------------------------------*/
function adrotate_user_notice() {

	/* Changelog:
	// Mar 29 2011 - Internationalization support
	*/

	echo '<table class="widefat" style="margin-top: .5em">';

	echo '<thead>';
	echo '<tr valign="top">';
	echo '	<th>'.__('AdRotate Notice').'</th>';
	echo '</tr>';
	echo '</thead>';

	echo '<tbody>';
	echo '<tr><td>';
	echo '	'.__('The overall stats do not take ads from other advertisers into account.', 'adrotate').'<br />';
	echo '	'.__('All statistics are indicative. They do not nessesarily reflect results counted by other parties.', 'adrotate').'<br />';
	echo '	'.__('Your ads are published with', 'adrotate').' <a href="http://www.adrotateplugin.com/" target="_blank">AdRotate</a> '.__('for WordPress.', 'adrotate');
	echo '</td></tr>';
	echo '</tbody>';

	echo '</table>';
}
?>