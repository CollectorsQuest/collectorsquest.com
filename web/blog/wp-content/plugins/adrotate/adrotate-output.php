<?php
/*  
Copyright 2010-2012 Arnan de Gans - AJdG Solutions (email : info@ajdg.net)
*/

/*-------------------------------------------------------------
 Name:      adrotate_ad

 Purpose:   Show requested ad
 Receive:   $banner_id, $individual, $group, $block
 Return:    $output
 Since:		3.0
-------------------------------------------------------------*/
function adrotate_ad($banner_id, $individual = true, $group = 0, $block = 0) {
	global $wpdb, $adrotate_config, $adrotate_crawlers, $adrotate_debug;

	$now 				= current_time('timestamp');
	$today 				= gmmktime(0, 0, 0, gmdate("n"), gmdate("j"), gmdate("Y"));
	$useragent 			= $_SERVER['HTTP_USER_AGENT'];
	$useragent_trim 	= trim($useragent, ' \t\r\n\0\x0B');


	if($banner_id) {
		$banner = $wpdb->get_row("SELECT 
									`id`, 
									`bannercode`, 
									`tracker`, 
									`link`, 
									`image`, 
									`timeframe`, 
									`timeframelength`, 
									`timeframeclicks`, 
									`timeframeimpressions` 
								FROM 
									`".$wpdb->prefix."adrotate` 
								WHERE 
									`id` = '$banner_id' 
									AND `type` = 'active'
								;");
		$selected[$banner->id] = 0;			

		if($individual == true) {
			// Individual ad, check schedules and timeframe
			// For groups and blocks these are checked elsewhere
			$selected = adrotate_filter_schedule($selected, $banner);

			if($banner->timeframe == 'hour' OR $banner->timeframe == 'day' OR $banner->timeframe == 'week' OR $banner->timeframe == 'month') {
				$selected = adrotate_filter_timeframe($selected, $banner);
			}
		}
		
		if($adrotate_debug['general'] == true) {
			if($banner->timeframe == '') $banner->timeframe = "not set";
			echo "<p><strong>[DEBUG][adrotate_ad()] Selected Ad, specs</strong><pre>";
			print_r($banner); 
			echo "</pre></p>"; 
			echo "<p><strong>[DEBUG][adrotate_ad()] Ad to display (ID => (fake) weight)</strong><pre>";
			print_r($selected); 
			echo "</pre></p>"; 
		}
			
		if($selected) {
			$output = adrotate_ad_output($banner->id, $group, $block, $banner->bannercode, $banner->tracker, $banner->link, $banner->image);

			$remote_ip 	= adrotate_get_remote_ip();
			if(is_array($adrotate_crawlers)) $crawlers = $adrotate_crawlers;
				else $crawlers = array();

			if($adrotate_debug['timers'] == true) {
				$impression_timer = $now;
			} else {
				$impression_timer = $now - $adrotate_config['impression_timer'];
			}
		
			$nocrawler = true;
			foreach($crawlers as $crawler) {
				if(preg_match("/$crawler/i", $useragent)) $nocrawler = false;
			}
			$ip = $wpdb->get_var("SELECT `timer` FROM `".$wpdb->prefix."adrotate_tracker` WHERE `ipaddress` = '$remote_ip' AND `stat` = 'i' AND `bannerid` = '$banner_id' ORDER BY `timer` DESC LIMIT 1;");
			if($ip < $impression_timer AND $nocrawler == true AND (strlen($useragent_trim) > 0 OR !empty($useragent))) {
				if($group > 0) $grouporblock = " AND `group` = '$group'";
				if($block > 0) $grouporblock = " AND `block` = '$block'";
				$stats = $wpdb->get_var("SELECT `id` FROM `".$wpdb->prefix."adrotate_stats_tracker` WHERE `ad` = '$banner_id'$grouporblock AND `thetime` = '$today';");
				if($stats > 0) {
					$wpdb->query("UPDATE `".$wpdb->prefix."adrotate_stats_tracker` SET `impressions` = `impressions` + 1 WHERE `id` = '$stats';");
				} else {
					$wpdb->insert($wpdb->prefix."adrotate_stats_tracker", array('ad' => $banner_id, 'group' => $group, 'block' => $block, 'thetime' => $today, 'clicks' => 0, 'impressions' => 1));
				}
				$wpdb->insert($wpdb->prefix."adrotate_tracker", array('ipaddress' => $remote_ip, 'timer' => $now, 'bannerid' => $banner_id, 'stat' => 'i', 'useragent' => ''));
			}
		} else {
			$output = adrotate_error('ad_expired', array($banner_id));
		}
		unset($banner, $schedules);
		
	} else {
		$output = adrotate_error('ad_no_id', array($banner_id));
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

	if($group_ids) {
		$now = current_time('timestamp');
		if(!is_array($group_ids)) {
			$group_array = explode(",", $group_ids);
		} else {
			$group_array = $group_ids;
		}
		
		$group_choice = array_rand($group_array, 1);
		$prefix = $wpdb->prefix;

		if($fallback == 0 OR $fallback == '') {
			$fallback = $wpdb->get_var("SELECT `fallback` FROM `".$prefix."adrotate_groups` WHERE `id` = '$group_array[$group_choice]';");
		}
		
		if($weight > 0) {
			$weightoverride = "	AND `".$prefix."adrotate`.`weight` >= '$weight'";
		}

		if($adrotate_debug['general'] == true) {
			echo "<p><strong>[DEBUG][adrotate_group()] Group array</strong><pre>"; 
			print_r($group_array); 
			print_r("Group choice: ".$group_array[$group_choice]); 
			echo "</pre></p>"; 
		}			

		$results = $wpdb->get_results("
			SELECT 
				`".$prefix."adrotate`.`id`, 
				`".$prefix."adrotate`.`tracker`, 
				`".$prefix."adrotate`.`weight`, 
				`".$prefix."adrotate`.`timeframe`, 
				`".$prefix."adrotate`.`timeframelength`, 
				`".$prefix."adrotate`.`timeframeclicks`, 
				`".$prefix."adrotate`.`timeframeimpressions` 
			FROM 
				`".$prefix."adrotate`, 
				`".$prefix."adrotate_linkmeta`
			WHERE 
				`".$prefix."adrotate_linkmeta`.`group` = '$group_array[$group_choice]' 
				AND `".$prefix."adrotate_linkmeta`.`block` = 0 
				AND `".$prefix."adrotate_linkmeta`.`user` = 0
				AND `".$prefix."adrotate`.`id` = `".$prefix."adrotate_linkmeta`.`ad`
				AND `".$prefix."adrotate`.`type` = 'active'
				".$weightoverride."
			;");

		if($adrotate_debug['general'] == true) {
			echo "<p><strong>[DEBUG][adrotate_group()] All ads in group</strong><pre>"; 
			print_r($results); 
			echo "</pre></p>"; 
		}			

		if($results) {
			foreach($results as $result) {
				$selected[$result->id] = $result->weight;
				$selected = adrotate_filter_schedule($selected, $result);

				if ($result->timeframe == 'hour' OR $result->timeframe == 'day' OR $result->timeframe == 'week' OR $result->timeframe == 'month') {
					$selected = adrotate_filter_timeframe($selected, $result);
				}
			}
			
			if($adrotate_debug['general'] == true) {
				echo "<p><strong>[DEBUG][adrotate_group()] Initial selection (filtered down by clicks, impressions, timeframes and schedules)</strong><pre>"; 
				print_r($selected); 
				echo "</pre></p>"; 
			}			

			if(count($selected) > 0) {
				$banner_id = adrotate_pick_weight($selected);
				
				if($adrotate_debug['general'] == true) {
					echo "<p><strong>[DEBUG][adrotate_group()] Selected ad based on weight</strong><pre>"; 
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
		
		unset($results, $selected, $schedules);
		
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

	if($block_id) {
		$now = current_time('timestamp');
		$prefix = $wpdb->prefix;
		
		// Get block specs
		$block = $wpdb->get_row("SELECT * FROM `".$prefix."adrotate_blocks` WHERE `id` = '$block_id';");
		if($block) {
			if($adrotate_debug['general'] == true) {
				echo "<p><strong>[DEBUG][adrotate_block()] Selected block</strong><pre>"; 
				print_r($block); 
				echo "</pre></p>"; 
			}			

			// Get groups in block
			$groups = $wpdb->get_results("SELECT `group` FROM `".$prefix."adrotate_linkmeta` WHERE `ad` = 0 AND `block` = '$block->id' AND `user` = 0;");
			if($groups) {
				if($weight > 0) {
					$weightoverride = "	AND `".$prefix."adrotate`.`weight` >= '$weight'";
				}

				// Get all ads in all groups and process them in an array
				$results = array();
				foreach($groups as $group) {
					$ads = $wpdb->get_results(
						"SELECT 
							`".$prefix."adrotate`.`id`, 
							`".$prefix."adrotate`.`tracker`, 
							`".$prefix."adrotate`.`weight`,
							`".$prefix."adrotate`.`timeframe`, 
							`".$prefix."adrotate`.`timeframelength`, 
							`".$prefix."adrotate`.`timeframeclicks`, 
							`".$prefix."adrotate`.`timeframeimpressions` 
						FROM 
							`".$prefix."adrotate`, 
							`".$prefix."adrotate_linkmeta` 
						WHERE 
							`".$prefix."adrotate_linkmeta`.`group` = '$group->group' 
							AND `".$prefix."adrotate_linkmeta`.`block` = 0 
							AND `".$prefix."adrotate_linkmeta`.`user` = 0 
							AND `".$prefix."adrotate`.`id` = `".$prefix."adrotate_linkmeta`.`ad` 
							AND `".$prefix."adrotate`.`type` = 'active' 
							".$weightoverride."
						;");
					$results = array_merge($ads, $results);
					unset($ads);
				}

				if($adrotate_debug['general'] == true) {
					echo "<p><strong>[DEBUG][adrotate_block()] Groups in block</strong><pre>"; 
					print_r($groups); 
					echo "</pre></p>"; 
					echo "<p><strong>[DEBUG][adrotate_block()] All ads in block</strong><pre>"; 
					print_r($results); 
					echo "</pre></p>"; 
				}			

				if($results) {
					foreach($results as $result) {
						$selected[$result->id] = $result->weight;
						$selected = adrotate_filter_schedule($selected, $result);

						if ($result->timeframe == 'hour' OR $result->timeframe == 'day' OR $result->timeframe == 'week' OR $result->timeframe == 'month') {
							$selected = adrotate_filter_timeframe($selected, $result);
						}
					}
				}
				
				if($adrotate_debug['general'] == true) {
					echo "<p><strong>[DEBUG][adrotate_block()] Reduced array based on schedule and timeframe restrictions</strong><pre>"; 
					print_r($selected); 
					echo "</pre></p>"; 
				}			

				$array_count = count($selected);

				if($array_count > 0) {
					// determine grid size based on amount of ads
					$block_count = $block->columns * $block->rows;
					if($array_count < $block_count) $block_count = $array_count;
					
					// resize block in height
					if($block->rows > ($block_count / $block->rows)) $block->rows = $block_count / $block->columns;
					
					// resize block in width
					if($block->columns > ($block_count / $block->columns)) $block->columns = $block_count / $block->rows;
					
					// grab border width in px
					list($adborder, $rest) = explode (" ", $block->adborder, 2);
					$adborder = rtrim($adborder, "px");

					// set definitive block size
					$widthmargin = (($block->admargin * 2) * $block->columns) + (($block->adpadding * 2) * $block->columns) + (($adborder * 2) * $block->columns);
					$heightmargin = (($block->admargin * 2) * $block->rows) + (($block->adpadding * 2) * $block->rows) + (($adborder * 2) * $block->rows);
					$gridwidth = ($block->columns * $block->adwidth) + $widthmargin.'px';
					$adwidth = $block->adwidth.'px';
					if($block->adheight == 'auto') {
						$gridheight = $adheight = 'auto';
					} else {
						$gridheight = ($block->rows * $block->adheight) + $heightmargin.'px';
						$adheight = $block->adheight.'px';
					}
					
					//Set float
					if($block->gridfloat == 'none') $gridfloat = 'float:none;';
					if($block->gridfloat == 'left') $gridfloat = 'float:left;';
					if($block->gridfloat == 'right') $gridfloat = 'float:right;';
					if($block->gridfloat == 'inherit') $gridfloat = 'float:inherit;';
					
					$output = '';
					$output .='<div style="'.$gridfloat.'margin:0;padding:'.$block->gridpadding.'px;clear:none;width:'.$gridwidth.';height:'.$gridheight.';border:'.$block->gridborder.';">';
					for($i=0;$i<$block_count;$i++) {
						$banner_id = adrotate_pick_weight($selected);

						$output .='<div style="margin:'.$block->admargin.'px;padding:'.$block->adpadding.'px;clear:none;float:left;width:'.$adwidth.';height:'.$adheight.';border:'.$block->adborder.';">';
						if($block->wrapper_before != '') {$output .= stripslashes(html_entity_decode($block->wrapper_before, ENT_QUOTES)); }
						$output .= adrotate_ad($banner_id, false, 0, $block_id);
						if($block->wrapper_after != '') { $output .= stripslashes(html_entity_decode($block->wrapper_after, ENT_QUOTES)); }
						$output .= '</div>';
	
						$selected = array_diff_key($selected, array($banner_id => 0));

						if($adrotate_debug['general'] == true) {
							echo "<p><strong>[DEBUG][adrotate_block()] Selected ad (Cycle ".$i.")</strong><pre>"; 
							echo "Selected ad: ".$banner_id."<br />";
							echo "</pre></p>"; 
						}			
					}
					$output .= '</div>';
				} else {
					$output = adrotate_error('ad_unqualified');
				}
			}
			
			// Destroy data
			unset($groups, $results, $selected, $block);
			
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

	if($banner_id) {
		$now = current_time('timestamp');
		
		$banner = $wpdb->get_row("SELECT * FROM `".$wpdb->prefix."adrotate` WHERE `id` = '$banner_id';");

		if($adrotate_debug['general'] == true) {
			echo "<p><strong>[DEBUG][adrotate_preview()] Ad information</strong><pre>"; 
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
	global $adrotate_debug;

	if($adrotate_debug['track'] == true) {
		$meta = "$id,$group,$block,$link";
	} else {
		$meta = base64_encode("$id,$group,$block,$link");
	}

	$now = time();

	$banner_output = $bannercode;
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
 Name:      adrotate_fallback

 Purpose:   Add an advert to a single post
 Receive:   $post_content
 Return:    $post_content
 Added:		3.7
-------------------------------------------------------------*/
function adrotate_inject_posts($post_content) { 
	global $wpdb, $post, $adrotate_debug;

	// Inject ads into page
	if(is_page()) {
		$ids = $wpdb->get_results("SELECT `id`, `page`, `page_loc` FROM `".$wpdb->prefix."adrotate_groups` WHERE `page_loc` > 0;");
		
		$page_array = array();
		foreach($ids as $id) {
			$pages = explode(",", $id->page);
			// Build array of groups for pages
			if(in_array($post->ID, $pages)) {
				$page_array[] = array('id' => $id->id, 'loc' => $id->page_loc, 'pages' => $pages);
			}
		}

		if($adrotate_debug['general'] == true) {
			echo "<p><strong>[DEBUG][adrotate_inject_posts()] Arrays</strong><pre>"; 
			echo "Group ids (unsorted)<br />";
			print_r($ids); 
			echo "page_array<br />";
			print_r($page_array); 
			echo "</pre></p>"; 
		}			
		unset($ids, $pages);

		if(count($page_array) > 0) {
			if(count($page_array) > 1) {
				$page_choice = array_rand($page_array, 2);
			} else {
				$page_choice = array(0,0);
			}

			if($adrotate_debug['general'] == true) {
				echo "<p><strong>[DEBUG][adrotate_inject_posts()] Arrays</strong><pre>"; 
				echo "Group choices<br />";
				print_r($page_choice); 
				echo "</pre></p>"; 
			}			

			if($page_array[$page_choice[0]]['loc'] == 1 OR $page_array[$page_choice[0]]['loc'] == 3) {
				if(is_page($page_array[$page_choice[0]]['pages'])) {
					$advert_before = adrotate_group($page_array[$page_choice[0]]['id']);
			   		$post_content = $advert_before.$post_content;
				}
			}
		
			if($page_array[$page_choice[1]]['loc'] == 2 OR $page_array[$page_choice[1]]['loc'] == 3) {
				if(is_page($page_array[$page_choice[1]]['pages'])) {
					$advert_after = adrotate_group($page_array[$page_choice[1]]['id']);
			   		$post_content = $post_content.$advert_after;
				}
			}
		}
		unset($page_choice, $page_array);
	}
	
	// Inject ads into posts in specified category
	if(is_single()) {
		$ids = $wpdb->get_results("SELECT `id`, `cat`, `cat_loc` FROM `".$wpdb->prefix."adrotate_groups` WHERE `cat_loc` > 0;");
		$category = get_the_category();
		
		$cat_array = array();
		foreach($ids as $id) {
			$cats = explode(",", $id->cat);
			// Build array of groups for categories
			if(in_array($category[0]->cat_ID, $cats)) {
				$cat_array[] = array('id' => $id->id, 'loc' => $id->cat_loc, 'categories' => $cats);
			}
		}
	
		if($adrotate_debug['general'] == true) {
			echo "<p><strong>[DEBUG][adrotate_inject_posts()] Arrays</strong><pre>"; 
			echo "Group ids (unsorted)<br />";
			print_r($ids); 
			echo "cat_array<br />";
			print_r($cat_array); 
			echo "</pre></p>"; 
		}			
		unset($ids, $cats);

		if(count($cat_array) > 0) {
			if(count($cat_array) > 1) {
				$cat_choice = array_rand($cat_array, 2);
			} else {
				$cat_choice = array(0,0);
			}


			if($adrotate_debug['general'] == true) {
				echo "<p><strong>[DEBUG][adrotate_inject_posts()] Arrays</strong><pre>"; 
				echo "Group choices<br />";
				print_r($cat_choice); 
				echo "</pre></p>"; 
			}			

			if($cat_array[$cat_choice[0]]['loc'] == 1 OR $cat_array[$cat_choice[0]]['loc'] == 3) {
				if(in_category($cat_array[$cat_choice[0]]['categories'])) {
					$advert_before = adrotate_group($cat_array[$cat_choice[0]]['id']);
					$post_content = $advert_before.$post_content;
				}
			}
			
			if($cat_array[$cat_choice[1]]['loc'] == 2 OR $cat_array[$cat_choice[1]]['loc'] == 3) {
				if(in_category($cat_array[$cat_choice[1]]['categories'])) {
					$advert_after = adrotate_group($cat_array[$cat_choice[1]]['id']);
			   		$post_content = $post_content.$advert_after;
				}
			}
		}
		unset($cat_choice, $cat_array);
	}

	return $post_content;
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

	if($adrotate_config['credits'] == "Y") {
		echo "<li>". __('I\'m using', 'adrotate') ." <a href=\"http://www.adrotateplugin.com/\" target=\"_blank\" title=\"AdRotate\">AdRotate</a></li>\n";
	}
}

/*-------------------------------------------------------------
 Name:      adrotate_error

 Purpose:   Show errors for problems in using AdRotate, should they occur
 Receive:   $action, $arg
 Return:    -none-
 Since:		3.0
-------------------------------------------------------------*/
function adrotate_error($action, $arg = null) {
	global $adrotate_debug;

	switch($action) {
		// Ads
		case "ad_expired" :
			$ad_expired = __('is not available at this time due to schedule restrictions or does not exist!', 'adrotate');
			if($adrotate_debug['general'] == true) {
				$result = '<span style="font-weight: bold; color: #f00;">'.__('Error, Ad', 'adrotate').' (ID: '.$arg[0].') '.$ad_expired.'</span>';
			} else {
				$result = '<!-- '.__('Error, Ad', 'adrotate').' (ID: '.$arg[0].') '.$ad_expired.' -->';
			}
			return $result;
		break;
		
		case "ad_unqualified" :
			$ad_unqualified = __('Either there are no banners, they are disabled or none qualified for this location!', 'adrotate');
			if($adrotate_debug['general'] == true) {
				$result = '<span style="font-weight: bold; color: #f00;">'.$ad_unqualified.'</span>';
			} else {
				$result = '<!-- '.$ad_unqualified.' -->';
			}
			return $result;
		break;
		
		case "ad_no_id" :
			$result = '<span style="font-weight: bold; color: #f00;">'.__('Error, no or no valid AD ID set! Check your syntax!', 'adrotate').'</span>';
			return $result;
		break;

		case "ad_not_found" :
			$result = '<span style="font-weight: bold; color: #f00;">'.__('Error, ad could not be found! Make sure it exists or that you set the right ID.', 'adrotate').'</span>';
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
		case "no_id" :
			$result = '<span style="font-weight: bold; color: #f00;">'.__('Error, no ID set! Check your syntax!', 'adrotate').'</span>';
			return $result;
		break;

		default:
			$default = '<span style="font-weight: bold; color: #f00;">'.__('An unknown error occured.', 'adrotate').'</span>';
			return $default;
		break;

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
	global $adrotate_advert_status;
	if(current_user_can('adrotate_ad_manage')) {
		$data = $adrotate_advert_status;

		if($data['total'] > 0) {
			if($data['expired'] > 0 AND $data['expiressoon'] == 0 AND $data['error'] == 0) {
				echo '<div class="error"><p>'.$data['expired'].' '.__('active ad(s) expired.', 'adrotate').' <a href="admin.php?page=adrotate">'.__('Take action now', 'adrotate').'</a>!</p></div>';
			} else if($data['expired'] == 0 AND $data['expiressoon'] > 0 AND $data['error'] == 0) {
				echo '<div class="error"><p>'.$data['expiressoon'].' '.__('active ad(s) are about to expire.', 'adrotate').' <a href="admin.php?page=adrotate">'.__('Check it out', 'adrotate').'</a>!</p></div>';
			} else if($data['expired'] == 0 AND $data['expiressoon'] == 0 AND $data['error'] > 0) {
				echo '<div class="error"><p>There are '.$data['error'].' '.__('active ad(s) with configuration errors.', 'adrotate').' <a href="admin.php?page=adrotate">'.__('Solve this', 'adrotate').'</a>!</p></div>';
			} else {
				echo '<div class="error"><p>'.$data['expired'].' '.__('ad(s) expired.', 'adrotate').' '.$data['expiressoon'].' '.__('ad(s) are about to expire.', 'adrotate').' There are '.$data['error'].' '.__('ad(s) with configuration errors.', 'adrotate').' <a href="admin.php?page=adrotate">'.__('Fix this as soon as possible', 'adrotate').'</a>!</p></div>';
			}
		}

		$adrotate_version = get_option("adrotate_version");
		$adrotate_db_version = get_option("adrotate_db_version");
	
		if($adrotate_db_version['current'] < ADROTATE_DB_VERSION OR $adrotate_version['current'] < ADROTATE_VERSION) {
			echo '<div class="error"><p>SEVERE! Current AdRotate Build: '.$adrotate_version['current'].', requires version: '.ADROTATE_VERSION.'. AdRotate Database: '.$adrotate_db_version['current'].', requires version: '.ADROTATE_DB_VERSION.'.<br />Go to settings and click "Upgrade Database and Migrate Data" or contact support to try and resolve this issue!</p></div>';
		}
	}
}

/*-------------------------------------------------------------
 Name:      adrotate_beta_notifications_dashboard

 Purpose:   Remind users that they're using a beta and should provide feedback
 Receive:   -none-
 Return:    -none-
 Since:		3.7
-------------------------------------------------------------*/
function adrotate_beta_notifications_dashboard() {
	if(current_user_can('adrotate_ad_manage'))
		echo '<div id="message" class="updated fade"><p>You are using AdRotate beta version <strong>'.ADROTATE_BETA.'</strong>. Please provide <a href="'.admin_url('/admin.php?page=adrotate-beta').'">feedback</a> on your experience.</p></div>';
}

/*-------------------------------------------------------------
 Name:      adrotate_credits

 Purpose:   Promotional stuff shown throughout the plugin
 Receive:   -none-
 Return:    -none-
 Since:		3.7
-------------------------------------------------------------*/
function adrotate_credits() {

	echo '<table class="widefat" style="margin-top: .5em">';

	echo '<thead>';
	echo '<tr valign="top">';
	echo '	<th width="27%">AdRotate '.__('Useful Links', 'adrotate').'</th>';
	echo '	<th>'.__('News and Promotions', 'adrotate').'</th>';
	echo '	<th width="35%">'.__('Brought to you by', 'adrotate').'</th>';
	echo '</tr>';
	echo '</thead>';

	echo '<tbody>';
	echo '<tr><td><ul>';
	echo '	<li>'.__('Need help setting things up? Take a look at the', 'adrotate').' <a href="http://www.adrotateplugin.com/page/services.php" target="_blank">'.__('services page', 'adrotate').'</a>!';
	echo '	<li>'.__('Get free support on the', 'adrotate').' <a href="http://forum.adrotateplugin.com" target="_blank">'.__('forum', 'adrotate').'</a>!</li>';
	echo '	<li>'.__('Subscribe to news and updates on the', 'adrotate').' <a href="http://blog.adrotateplugin.com" target="_blank">'.__('AdRotate Blog','adrotate').'</a>!</li>';
	echo '	<li>'.__('Check out the', 'adrotate').' <a href="http://www.adrotateplugin.com/page/support.php" target="_blank">'.__('manuals', 'adrotate').'</a> '.__('where most features are explained.', 'adrotate').'</li>';
	echo '	<li>'.__('Make a small donation to show that you', 'adrotate').' <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=paypal%40ajdg%2enet&item_name=AdRotate%20Donation&item_number=Dashboard%20single%20donation&no_shipping=0&no_note=1&tax=0&currency_code=USD&lc=GB&bn=PP%2dDonationsBF&charset=UTF%2d8" target="_blank">'.__('like AdRotate', 'adrotate').'</a>. '.__('Thanks!', 'adrotate').'</li>';
	echo '</ul></td>';

	echo '<td style="border-left:1px #ddd solid;">';
		wp_widget_rss_output(array(
			'url' => array('http://www.ajdg.net/other/adrotate-news.xml'),
			'title' => 'AdRotate News and Promotions',
			'items' => 7,
			'show_summary' => 0, 
			'show_author' => 0,
			'show_date' => 0
		));
	echo '</td>';

	echo '<td style="border-left:1px #ddd solid;"><ul>';
	echo '	<li><a href="http://www.ajdg.net" title="AJdG Solutions"><img src="'.WP_CONTENT_URL.'/plugins/adrotate/images/ajdg-logo-100x60.png" alt="ajdg-logo-100x60" width="100" height="60" align="left" style="padding: 0 5px 0 0;" /></a>';
	echo '	'.__('Your one stop for Webdevelopment, consultancy and anything WordPress! If you need a custom plugin. Theme customizations or have your site moved/migrated entirely. Visit my website for details!', 'adrotate').' <a href="http://www.ajdg.net" title="AJdG Solutions">'.__('Find out more today', 'adrotate').'</a>!</li>';
	echo '	<li>'.__('Find my website at', 'adrotate').' <a href="http://meandmymac.net" target="_blank">meandmymac.net</a>.</li>';
	echo '	<li>'.__('The plugin homepage is at', 'adrotate').' <a href="http://www.adrotateplugin.com" target="_blank">www.adrotateplugin.com</a>!</li>';
	echo '</ul></td></tr>';
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

	echo '<table class="widefat" style="margin-top: .5em">';

	echo '<thead>';
	echo '<tr valign="top">';
	echo '	<th>'.__('AdRotate Notice', 'adrotate').'</th>';
	echo '</tr>';
	echo '</thead>';

	echo '<tbody>';
	echo '<tr><td>';
	echo '	'.__('The overall stats do not take ads from other advertisers into account.', 'adrotate').'<br />';
	echo '	'.__('All statistics are indicative. They do not nessesarily reflect results counted by other parties.', 'adrotate').'<br />';
	echo '	'.__('Your ads are published with', 'adrotate').' <a href="http://www.adrotateplugin.com" target="_blank">AdRotate</a> '.__('for WordPress. Created by', 'adrotate').' <a href="http://www.ajdg.net" target="_blank">AJdG Solutions</a>.';
	echo '</td></tr>';
	echo '</tbody>';

	echo '</table>';
}
?>