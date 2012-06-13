<?php
/*  
Copyright 2010-2011 Arnan de Gans  (email : adegans@meandmymac.net)
*/

/*-------------------------------------------------------------
 Name:      adrotate_insert_input

 Purpose:   Prepare input form on saving new or updated banners
 Receive:   -None-
 Return:	-None-
 Since:		0.1 
-------------------------------------------------------------*/
function adrotate_insert_input() {
	global $wpdb, $adrotate_config;

	/* Changelog:
	// Nov 14 2010 - Updated queries for 3.0
	// Mar 7 2010 - Added check if $link has http://
	// Mar 25 2010 - Updated for new image selector
	*/

	$id 				= $_POST['adrotate_id'];
	$author 			= $_POST['adrotate_username'];
	$title	 			= strip_tags(htmlspecialchars(trim($_POST['adrotate_title'], "\t\n "), ENT_QUOTES));
	$bannercode			= htmlspecialchars(trim($_POST['adrotate_bannercode'], "\t\n "), ENT_QUOTES);
	$thetime 			= date('U');
	$active 			= $_POST['adrotate_active'];
	$sortorder			= strip_tags(htmlspecialchars(trim($_POST['adrotate_sortorder'], "\t\n "), ENT_QUOTES));
	$image_field		= strip_tags(htmlspecialchars(trim($_POST['adrotate_image'], "\t\n "), ENT_QUOTES));
	$image_dropdown		= $_POST['adrotate_image_dropdown'];
	$link				= strip_tags(htmlspecialchars(trim($_POST['adrotate_link'], "\t\n "), ENT_QUOTES));
	$tracker			= $_POST['adrotate_tracker'];
	$sday 				= strip_tags(trim($_POST['adrotate_sday'], "\t\n "));
	$smonth 			= strip_tags(trim($_POST['adrotate_smonth'], "\t\n "));
	$syear 				= strip_tags(trim($_POST['adrotate_syear'], "\t\n "));
	$eday 				= strip_tags(trim($_POST['adrotate_eday'], "\t\n "));
	$emonth 			= strip_tags(trim($_POST['adrotate_emonth'], "\t\n "));
	$eyear 				= strip_tags(trim($_POST['adrotate_eyear'], "\t\n "));
	$maxclicks			= strip_tags(trim($_POST['adrotate_maxclicks'], "\t\n "));
	$maxshown			= strip_tags(trim($_POST['adrotate_maxshown'], "\t\n "));
	$targetclicks		= strip_tags(trim($_POST['adrotate_targetclicks'], "\t\n "));
	$targetimpressions	= strip_tags(trim($_POST['adrotate_targetimpressions'], "\t\n "));
	$groups				= $_POST['groupselect'];
	$adtype				= strip_tags(trim($_POST['adrotate_type'], "\t\n "));
	$advertiser			= $_POST['adrotate_advertiser'];
	$weight				= $_POST['adrotate_weight'];

	if(current_user_can('adrotate_ad_manage')) {
		if(strlen($title) < 1) {
			$title = 'Ad '.$id;
		}

		// Sort out dates
		if(strlen($smonth) == 0 OR !is_numeric($smonth)) 	$smonth 	= gmdate('m');
		if(strlen($sday) == 0 OR !is_numeric($sday)) 		$sday 		= gmdate('d');
		if(strlen($syear) == 0 OR !is_numeric($syear)) 		$syear 		= gmdate('Y');
		if(strlen($emonth) == 0 OR !is_numeric($emonth)) 	$emonth 	= $smonth;
		if(strlen($eday) == 0 OR !is_numeric($eday)) 		$eday 		= $sday;
		if(strlen($eyear) == 0 OR !is_numeric($eyear)) 		$eyear 		= $syear+1;
		$startdate 	= gmmktime($shour, $sminute, 0, $smonth, $sday, $syear);
		$enddate 	= gmmktime($ehour, $eminute, 0, $emonth, $eday, $eyear);
		
		// Enddate is too early, reset
		if($enddate <= $startdate) $enddate = $startdate + 345600; // 4 days

		// Validate sort order
		if(strlen($sortorder) < 1 OR !is_numeric($sortorder) AND ($sortorder < 1 OR $sortorder > 99999)) $sortorder = $id;

		// Sort out click and impressions restrictions
		if(strlen($maxclicks) < 1 OR !is_numeric($maxclicks))	$maxclicks	= 0;
		if(strlen($maxshown) < 1 OR !is_numeric($maxshown))		$maxshown	= 0;

		// Format the targets
		if(strlen($targetclicks) < 1 OR !is_numeric($targetclicks))				$targetclicks	= 0;
		if(strlen($targetimpressions) < 1 OR !is_numeric($targetimpressions))	$targetimpressions	= 0;

		// Set tracker value
		if(isset($tracker) AND strlen($tracker) != 0) $tracker = 'Y';
			else $tracker = 'N';

		// Format the URL (assume http://)
		if((strlen($link) > 0 OR $link != "") AND stristr($link, "http://") === false AND stristr($link, "https://") === false) $link = "http://".$link;
		
		// Determine image settings ($image_field has priority!)
		if(strlen($image_field) > 1) {
			$imagetype = "field";
			$image = $image_field;
		} else {
			if($image_dropdown == "") {
				$imagetype = "";
				$image = "";
			} else {
				$imagetype = "dropdown";
				$image = get_option('siteurl')."/wp-content/banners/".$image_dropdown;
			}
		}

		// Save the ad to the DB
		$wpdb->query("UPDATE 
						`".$wpdb->prefix."adrotate` 
					SET 
						`title` = '$title', 
						`bannercode` = '$bannercode', 
						`updated` = '$thetime', 
						`author` = '$author', 
						`active` = '$active', 
						`startshow` = '$startdate', 
						`endshow` = '$enddate', 
						`imagetype` = '$imagetype', 
						`image` = '$image', 
						`link` = '$link', 
						`tracker` = '$tracker', 
						`maxclicks` = '$maxclicks', 
						`maxshown` = '$maxshown', 
						`targetclicks` = '$targetclicks', 
						`targetimpressions` = '$targetimpressions', 
						`weight` = '$weight', 
						`sortorder` = '$sortorder' 
					WHERE 
						`id` = '$id'
					;");


		// Determine status of ad and what to do next
		$adstate = adrotate_evaluate_ad($id);
		if($adstate == true) $adtype = 'error';
			else $adtype = 'manual';

		// Fetch group records for the ad
		$groupmeta = $wpdb->get_results("SELECT `group` FROM `".$wpdb->prefix."adrotate_linkmeta` WHERE `ad` = '$id' AND `block` = 0 AND `user` = 0;");
		foreach($groupmeta as $meta) {
			$group_array[] = $meta->group;
		}
		
		if(!is_array($group_array)) $group_array = array();
		if(!is_array($groups)) 		$groups = array();
		
		// Add new groups to this ad
		$insert = array_diff($groups, $group_array);
		foreach($insert as &$value) {
			$wpdb->query("INSERT INTO `".$wpdb->prefix."adrotate_linkmeta` (`ad`, `group`, `block`, `user`) VALUES ($id, $value, 0, 0);"); 
		}
		unset($value);
		
		// Remove groups from this ad
		$delete = array_diff($group_array, $groups);
		foreach($delete as &$value) {
			$wpdb->query("DELETE FROM `".$wpdb->prefix."adrotate_linkmeta` WHERE `ad` = '$id' AND `group` = '$value' AND `block` = 0 AND `user` = 0;"); 
		}
		unset($value);

		// Fetch records for the ad, see if a publisher is set
		$linkmeta = $wpdb->get_var("SELECT COUNT(*) FROM `".$wpdb->prefix."adrotate_linkmeta` WHERE `ad` = '$id' AND `group` = 0 AND `block` = 0 AND `user` > 0;");

		// Add/update/remove publisher on this ad
		if($linkmeta == 0 AND $advertiser > 0) 		$wpdb->query("INSERT INTO `".$wpdb->prefix."adrotate_linkmeta` (`ad`, `group`, `block`, `user`) VALUES ($id, 0, 0, $advertiser);"); 
		if($linkmeta == 1 AND $advertiser > 0) 		$wpdb->query("UPDATE `".$wpdb->prefix."adrotate_linkmeta` SET `user` = '$advertiser' WHERE `ad` = '$id' AND `group` = '0' AND `block` = '0';");
		if($linkmeta == 1 AND $advertiser == 0) 	$wpdb->query("DELETE FROM `".$wpdb->prefix."adrotate_linkmeta` WHERE `ad` = '$id' AND `group` = 0 AND `block` = 0;"); 
		adrotate_return($action, array($id));
		exit;
	} else {
		adrotate_return('no_access');
	}
}

/*-------------------------------------------------------------
 Name:      adrotate_insert_group

 Purpose:   Save provided data for groups, update linkmeta where required
 Receive:   -None-
 Return:	-None-
 Since:		0.4
-------------------------------------------------------------*/
function adrotate_insert_group() {
	global $wpdb, $adrotate_config;

	/* Changelog:
	// Nov 14 2010 - Rewritten for 3.0
	*/

	$action		= $_POST['adrotate_action'];
	$id 		= $_POST['adrotate_id'];
	$name 		= strip_tags(trim($_POST['adrotate_groupname'], "\t\n "));
	$fallback 	= $_POST['adrotate_fallback'];
	$ads		= $_POST['adselect'];
	$sortorder	= strip_tags(htmlspecialchars(trim($_POST['adrotate_sortorder'], "\t\n "), ENT_QUOTES));

	if(current_user_can('adrotate_group_manage')) {
		if(strlen($name) < 1) $name = 'Group '.$id;

		// Validate sort order
		if(strlen($sortorder) < 1 OR !is_numeric($sortorder) AND ($sortorder < 1 OR $sortorder > 99999)) $sortorder = $id;

		// Fetch records for the group
		$linkmeta = $wpdb->get_results("SELECT `ad` FROM `".$wpdb->prefix."adrotate_linkmeta` WHERE `group` = '$id' AND `block` = 0 AND `user` = 0;");
		foreach($linkmeta as $meta) {
			$meta_array[] = $meta->ad;
		}
		
		if(!is_array($meta_array)) 	$meta_array = array();
		if(!is_array($ads)) 		$ads = array();
		
		// Add new ads to this group
		$insert = array_diff($ads,$meta_array);
		foreach($insert as &$value) {
				$wpdb->query("INSERT INTO `".$wpdb->prefix."adrotate_linkmeta` (`ad`, `group`, `block`, `user`) VALUES ($value, $id, 0, 0);"); 
		}
		unset($value);
		
		// Remove ads from this group
		$delete = array_diff($meta_array,$ads);
		foreach($delete as &$value) {
			$wpdb->query("DELETE FROM `".$wpdb->prefix."adrotate_linkmeta` WHERE `ad` = '$value' AND `group` = '$id' AND `block` = 0 AND `user` = 0;"); 
		}
		unset($value);

		// Update the group itself
		$wpdb->query("UPDATE `".$wpdb->prefix."adrotate_groups` SET `name` = '$name', `fallback` = '$fallback', `sortorder` = '$sortorder' WHERE `id` = '$id';");
		adrotate_return($action, array($id));
		exit;
	} else {
		adrotate_return('no_access');
	}
}

/*-------------------------------------------------------------
 Name:      adrotate_insert_block

 Purpose:   Save provided data for blocks, update linkmeta where required
 Receive:   -None-
 Return:	-None-
 Since:		3.0
-------------------------------------------------------------*/
function adrotate_insert_block() {
	global $wpdb, $adrotate_config;

	$action			= $_POST['adrotate_action'];
	$id 			= $_POST['adrotate_id'];
	$name 			= strip_tags(trim($_POST['adrotate_blockname'], "\t\n "));
	$adcount		= strip_tags(trim($_POST['adrotate_adcount'], "\t\n "));
	$columns 		= strip_tags(trim($_POST['adrotate_columns'], "\t\n "));
	$wrapper_before	= trim($_POST['adrotate_wrapper_before'], "\t\n ");
	$wrapper_after	= trim($_POST['adrotate_wrapper_after'], "\t\n ");
	$groups 		= $_POST['groupselect'];
	$sortorder		= strip_tags(htmlspecialchars(trim($_POST['adrotate_sortorder'], "\t\n "), ENT_QUOTES));

	if(current_user_can('adrotate_block_manage')) {
		if($adcount < 1 OR $adcount == '' OR !is_numeric($adcount)) $adcount = 1;
		if($columns < 1 OR $columns == '' OR !is_numeric($columns)) $columns = 1;
		if(strlen($name) < 1) $name = 'Block '.$id;

		// Validate sort order
		if(strlen($sortorder) < 1 OR !is_numeric($sortorder) AND ($sortorder < 1 OR $sortorder > 99999)) $sortorder = $id;

		// Fetch records for the block
		$linkmeta = $wpdb->get_results("SELECT `group` FROM `".$wpdb->prefix."adrotate_linkmeta` WHERE `block` = '$id' AND `ad` = 0 AND `user` = 0;");
		foreach($linkmeta as $meta) {
			$meta_array[] = $meta->group;
		}
		
		if(!is_array($meta_array)) 	$meta_array = array();
		if(!is_array($groups)) 		$groups = array();
		
		// Add new groups to this block
		$insert = array_diff($groups,$meta_array);
		foreach($insert as &$value) {
			$wpdb->query("INSERT INTO `".$wpdb->prefix."adrotate_linkmeta` (`ad`, `group`, `block`, `user`) VALUES (0, $value, $id, 0);"); 
		}
		unset($value);
		
		// Remove groups from this block
		$delete = array_diff($meta_array,$groups);
		foreach($delete as &$value) {
			$wpdb->query("DELETE FROM `".$wpdb->prefix."adrotate_linkmeta` WHERE `ad` = 0 AND `group` = '$value' AND `block` = '$id' AND `user` = 0;"); 
		}
		unset($value);

		// Update the block itself
		$wpdb->query("UPDATE `".$wpdb->prefix."adrotate_blocks` SET `name` = '$name', `adcount` = '$adcount', `columns` = '$columns', `wrapper_before` = '$wrapper_before', `wrapper_after` = '$wrapper_after', `sortorder` = '$sortorder' WHERE `id` = '$id';");
		adrotate_return($action, array($id));
		exit;
	} else {
		adrotate_return('no_access');
	}
}

/*-------------------------------------------------------------
 Name:      adrotate_request_action

 Purpose:   Prepare action for banner or group from database
 Receive:   -none-
 Return:    -none-
 Since:		2.2
-------------------------------------------------------------*/
function adrotate_request_action() {
	global $wpdb, $adrotate_config;

	/* Changelog:
	// Nov 14 2010 - Removed "move" option, re-inserted access rights
	// Nov 16 2010 - Rebranded 'resetmultiple' and 'renewmultiple' to work like 'reset' and 'renew' original 'reset' and 'renew' are removed, added block support
	// Dec 4 2010 - Fixed bug where adrotate_renew() wasn't called properly
	// Dec 17 2010 - Added support for single ad actions (renew, reset, delete)
	// 5 Jun 2011 - Added, Bulk weight changes, new action for disabled ads listing
	*/

	if(isset($_POST['bannercheck'])) 			$banner_ids = $_POST['bannercheck'];
	if(isset($_POST['disabledbannercheck'])) 	$banner_ids = $_POST['disabledbannercheck'];
	if(isset($_POST['errorbannercheck'])) 		$banner_ids = $_POST['errorbannercheck'];
	if(isset($_POST['groupcheck'])) 			$group_ids = $_POST['groupcheck'];
	if(isset($_POST['blockcheck'])) 			$block_ids = $_POST['blockcheck'];
	
	if(isset($_POST['adrotate_id'])) 			$banner_ids = array($_POST['adrotate_id']);
	
	// Determine which kind of action to use
	if(isset($_POST['adrotate_action'])) {
		// Default action call
		$actions = $_POST['adrotate_action'];
	} else if(isset($_POST['adrotate_disabled_action'])) {
		// Disabled ads listing call
		$actions = $_POST['adrotate_disabled_action'];
	} else if(isset($_POST['adrotate_error_action'])) {
		// Erroneous ads listing call
		$actions = $_POST['adrotate_error_action'];
	} else {
		// If neither, protect user with invalid ID
		$banner_ids = $group_ids = $block_ids = '';
	}
	list($action, $specific) = explode("-", $actions);	

	if($banner_ids != '') {
		foreach($banner_ids as $banner_id) {
			if($action == 'deactivate') {
				if(current_user_can('adrotate_ad_manage')) {
					adrotate_active($banner_id, 'deactivate');
					$result_id = $banner_id;
				} else {
					adrotate_return('no_access');
				}
			}
			if($action == 'activate') {
				if(current_user_can('adrotate_ad_manage')) {
					adrotate_active($banner_id, 'activate');
					$result_id = $banner_id;
				} else {
					adrotate_return('no_access');
				}
			}
			if($action == 'delete') {
				if(current_user_can('adrotate_ad_delete')) {
					adrotate_delete($banner_id, 'banner');
					$result_id = $banner_id;
				} else {
					adrotate_return('no_access');
				}
			}
			if($action == 'reset') {
				if(current_user_can('adrotate_ad_delete')) {
					adrotate_reset($banner_id);
					$result_id = $banner_id;
				} else {
					adrotate_return('no_access');
				}
			}
			if($action == 'renew') {
				if(current_user_can('adrotate_ad_manage')) {
					adrotate_renew($banner_id, $specific);
					$result_id = $banner_id;
				} else {
					adrotate_return('no_access');
				}
			}
			if($action == 'weight') {
				if(current_user_can('adrotate_ad_manage')) {
					adrotate_weight($banner_id, $specific);
					$result_id = $banner_id;
				} else {
					adrotate_return('no_access');
				}
			}
		}
	}
	
	if($group_ids != '') {
		foreach($group_ids as $group_id) {
			if($action == 'group_delete') {
				if(current_user_can('adrotate_group_delete')) {
					adrotate_delete($group_id, 'group');
					$result_id = $group_id;
				} else {
					adrotate_return('no_access');
				}
			}
			if($action == 'group_delete_banners') {
				if(current_user_can('adrotate_group_delete')) {
					adrotate_delete($group_id, 'bannergroup');
					$result_id = $group_id;
				} else {
					adrotate_return('no_access');
				}
			}
		}
	 }

	if($block_ids != '') {
		foreach($block_ids as $block_id) {
			if($action == 'block_delete') {
				if(current_user_can('adrotate_block_delete')) {
					adrotate_delete($block_id, 'block');
					$result_id = $block_id;
				} else {
					adrotate_return('no_access');
				}
			}
		}
	 }
	
	adrotate_return($action, array($result_id));
}

/*-------------------------------------------------------------
 Name:      adrotate_delete

 Purpose:   Remove banner or group from database
 Receive:   $id, $what
 Return:    -none-
 Since:		0.1
-------------------------------------------------------------*/
function adrotate_delete($id, $what) {
	global $wpdb;

	/* Changelog:
	// 14 Nov 2010 - Added and updated queries to work with linkmeta
	// 1 Apr 2011 - Added template support
	*/

	if($id > 0) {
		if($what == 'banner') {
			$wpdb->query("DELETE FROM `".$wpdb->prefix."adrotate` WHERE `id` = $id;");
			$wpdb->query("DELETE FROM `".$wpdb->prefix."adrotate_linkmeta` WHERE `ad` = $id;");
		} else if ($what == 'group') {
			$wpdb->query("DELETE FROM `".$wpdb->prefix."adrotate_groups` WHERE `id` = $id;");
			$wpdb->query("DELETE FROM `".$wpdb->prefix."adrotate_linkmeta` WHERE `group` = $id;");
		} else if ($what == 'block') {
			$wpdb->query("DELETE FROM `".$wpdb->prefix."adrotate_blocks` WHERE `id` = $id;");
			$wpdb->query("DELETE FROM `".$wpdb->prefix."adrotate_linkmeta` WHERE `block` = $id;");
		} else if ($what == 'template') {
			$wpdb->query("DELETE FROM `".$wpdb->prefix."adrotate_templates` WHERE `id` = $id;");
			$wpdb->query("UPDATE `".$wpdb->prefix."adrotate_blocks` SET `template` = '0' WHERE `template` = $id;");
		} else if ($what == 'bannergroup') {
			$linkmeta = $wpdb->get_results("SELECT `ad` FROM `".$wpdb->prefix."adrotate_linkmeta` WHERE `group` = '$id' AND `block` = '0';");
			foreach($linkmeta as $meta) {
				$wpdb->query("DELETE FROM `".$wpdb->prefix."adrotate` WHERE `id` = ".$meta->ad.";");
			}
			$wpdb->query("DELETE FROM `".$wpdb->prefix."adrotate_groups` WHERE `id` = $id;");
			$wpdb->query("DELETE FROM `".$wpdb->prefix."adrotate_linkmeta` WHERE `group` = $id;");
		} else {
			adrotate_return('error');
			exit;
		}
	}
}

/*-------------------------------------------------------------
 Name:      adrotate_active

 Purpose:   Activate or Deactivate a banner
 Receive:   $id, $what
 Return:    -none-
 Since:		0.1
-------------------------------------------------------------*/
function adrotate_active($id, $what) {
	global $wpdb;

	if($id > 0) {
		if($what == 'deactivate') {
			$wpdb->query("UPDATE `".$wpdb->prefix."adrotate` SET `active` = 'no' WHERE `id` = '$id'");
		}
		if ($what == 'activate') {
			$wpdb->query("UPDATE `".$wpdb->prefix."adrotate` SET `active` = 'yes' WHERE `id` = '$id'");
		}
	}
}

/*-------------------------------------------------------------
 Name:      adrotate_reset

 Purpose:   Reset statistics for a banner
 Receive:   $id
 Return:    -none-
 Since:		2.2
-------------------------------------------------------------*/
function adrotate_reset($id) {
	global $wpdb;

	if($id > 0) {
		$wpdb->query("DELETE FROM `".$wpdb->prefix."adrotate_stats_tracker` WHERE `ad` = $id");
		$wpdb->query("DELETE FROM `".$wpdb->prefix."adrotate_tracker` WHERE `bannerid` = $id");
	}
}

/*-------------------------------------------------------------
 Name:      adrotate_renew

 Purpose:   Renew the end date of a banner
 Receive:   $id, $howlong
 Return:    -none-
 Since:		2.2
-------------------------------------------------------------*/
function adrotate_renew($id, $howlong = 2592000) {
	global $wpdb;

	if($id > 0) {
		$wpdb->query("UPDATE `".$wpdb->prefix."adrotate` SET `endshow` = `endshow` + '$howlong' WHERE `id` = '$id'");
	}
}

/*-------------------------------------------------------------
 Name:      adrotate_weight

 Purpose:   Renew the end date of a banner
 Receive:   $id, $weight
 Return:    -none-
 Since:		3.6
-------------------------------------------------------------*/
function adrotate_weight($id, $weight = 6) {
	global $wpdb;

	if($id > 0) {
		$wpdb->query("UPDATE `".$wpdb->prefix."adrotate` SET `weight` = '$weight' WHERE `id` = '$id'");
	}
}

/*-------------------------------------------------------------
 Name:      adrotate_options_submit

 Purpose:   Save options from dashboard
 Receive:   $_POST
 Return:    -none-
 Since:		0.1
-------------------------------------------------------------*/
function adrotate_options_submit() {

	/* Changelog:
	// Jan 3 2011 - Updated crawlers to trim(), removed dashboardwidget, added globalstats setting
	// Jan 16 2011 - Foreach() for crawlers keywords and to support multiple email notifications
	// Jan 20 2011 - Borrowed code from NextGen Gallery plugin for user capabilities
	// Jan 23 2011 - Added option to disable email notifications
	// Jan 24 2011 - Automatic switch for email notifications, added array_unique() to email addresses
	// Feb 15 2011 - Dashboard debugger
	// Jul 11 2011 - Added option for impression timer, enhanced several checks for email validation to be compatible with PHP5.3
	// Aug 10 2011 - Removed sortorder option
	*/

	// Set and save user roles
	adrotate_set_capability($_POST['adrotate_advertiser_report'], "adrotate_advertiser_report");
	adrotate_set_capability($_POST['adrotate_global_report'], "adrotate_global_report");
	adrotate_set_capability($_POST['adrotate_ad_manage'], "adrotate_ad_manage");
	adrotate_set_capability($_POST['adrotate_ad_delete'], "adrotate_ad_delete");
	adrotate_set_capability($_POST['adrotate_group_manage'], "adrotate_group_manage");
	adrotate_set_capability($_POST['adrotate_group_delete'], "adrotate_group_delete");
	adrotate_set_capability($_POST['adrotate_block_manage'], "adrotate_block_manage");
	adrotate_set_capability($_POST['adrotate_block_delete'], "adrotate_block_delete");
	$config['advertiser_report'] 	= $_POST['adrotate_advertiser_report'];
	$config['global_report']	 	= $_POST['adrotate_global_report'];
	$config['ad_manage'] 			= $_POST['adrotate_ad_manage'];
	$config['ad_delete'] 			= $_POST['adrotate_ad_delete'];
	$config['group_manage'] 		= $_POST['adrotate_group_manage'];
	$config['group_delete'] 		= $_POST['adrotate_group_delete'];
	$config['block_manage'] 		= $_POST['adrotate_block_manage'];
	$config['block_delete'] 		= $_POST['adrotate_block_delete'];

	// Filter and validate notification addresses, if not set, turn option off.
	$notification_emails = $_POST['adrotate_notification_email'];
	if(strlen($notification_emails) > 0) {
		$notification_emails = explode(',', trim($_POST['adrotate_notification_email']));
		foreach($notification_emails as $notification_email) {
			$notification_email = trim($notification_email);
			if(strlen($notification_email) > 0) {
  				if(preg_match("/^[a-z0-9]+([_\\.-][a-z0-9]+)*@([a-z0-9]+([\.-][a-z0-9]+)*)+\\.[a-z]{2,}$/i", $notification_email) ) {
					$clean_notification_email[] = $notification_email;
				}
			}
		}
		$config['notification_email_switch'] 	= 'Y';
		$config['notification_email'] = array_unique(array_slice($clean_notification_email, 0, 5));
	} else {
		$config['notification_email_switch'] 	= 'N';
		$config['notification_email'] = array();
	}

	// Filter and validate advertiser addresses
	$advertiser_emails = $_POST['adrotate_advertiser_email'];
	if(strlen($advertiser_emails) > 0) {
		$advertiser_emails = explode(',', trim($_POST['adrotate_advertiser_email']));
		foreach($advertiser_emails as $advertiser_email) {
			$advertiser_email = trim($advertiser_email);
			if(strlen($advertiser_email) > 0) {
  				if(preg_match("/^[a-z0-9]+([_\\.-][a-z0-9]+)*@([a-z0-9]+([\.-][a-z0-9]+)*)+\\.[a-z]{2,}$/i", $advertiser_email) ) {
					$clean_advertiser_email[] = $advertiser_email;
				}
			}
		}
		$config['advertiser_email'] = array_unique(array_slice($clean_advertiser_email, 0, 2));
	} else {
		$config['advertiser_email'] = array(get_option('admin_email'));
	}

	// Set up impression tracker timer
	$impression_timer = trim($_POST['adrotate_impression_timer']);
	if(strlen($impression_timer) > 0 AND (is_numeric($impression_timer) AND $impression_timer >= 0 AND $impression_timer <= 3600)) {
		$config['impression_timer'] = $impression_timer;
	} else {
		$config['impression_timer'] = 10;
	}

	// Miscellaneous Options
	if(isset($_POST['adrotate_credits'])) 					$config['credits'] 		= 'Y';
		else 												$config['credits'] 		= 'N';
	if(isset($_POST['adrotate_widgetalign'])) 				$config['widgetalign'] 	= 'Y';
		else 												$config['widgetalign'] 	= 'N';
	update_option('adrotate_config', $config);

	// Sort out crawlers
	$crawlers						= explode(',', trim($_POST['adrotate_crawlers']));
	foreach($crawlers as $crawler) {
		$crawler = trim($crawler);
		if(strlen($crawler) > 0) $clean_crawler[] = $crawler;
	}
	update_option('adrotate_crawlers', $clean_crawler);

	// Debug option
	if(isset($_POST['adrotate_debug'])) 				$debug['general'] 		= true;
		else 											$debug['general']		= false;
	if(isset($_POST['adrotate_debug_dashboard'])) 		$debug['dashboard'] 	= true;
		else 											$debug['dashboard']		= false;
	if(isset($_POST['adrotate_debug_userroles'])) 		$debug['userroles'] 	= true;
		else 											$debug['userroles']		= false;
	if(isset($_POST['adrotate_debug_userstats'])) 		$debug['userstats'] 	= true;
		else 											$debug['userstats']		= false;
	if(isset($_POST['adrotate_debug_stats'])) 			$debug['stats'] 		= true;
		else 											$debug['stats']			= false;
	if(isset($_POST['adrotate_debug_timers'])) 			$debug['timers'] 		= true;
		else 											$debug['timers']		= false;
	update_option('adrotate_debug', $debug);


	// Return to dashboard
	adrotate_return('settings_saved');
}

/*-------------------------------------------------------------
 Name:      adrotate_prepare_roles

 Purpose:   Prepare user roles for WordPress
 Receive:   -None-
 Return:    $action
 Since:		3.0
-------------------------------------------------------------*/
function adrotate_prepare_roles() {
	
	if(isset($_POST['adrotate_role_add_submit'])) {
		$action = "role_add";
		adrotate_add_roles();		
		update_option('adrotate_roles', '1');
	} 
	if(isset($_POST['adrotate_role_remove_submit'])) {
		$action = "role_remove";
		adrotate_remove_roles();
		update_option('adrotate_roles', '0');
	} 

	adrotate_return($action);
}

/*-------------------------------------------------------------
 Name:      adrotate_add_roles

 Purpose:   Add User roles and capabilities
 Receive:   -None-
 Return:    -None-
 Since:		3.0
-------------------------------------------------------------*/
function adrotate_add_roles() {

	add_role('adrotate_advertiser', 'AdRotate Advertiser', array('read' => 1));

}

/*-------------------------------------------------------------
 Name:      adrotate_remove_roles

 Purpose:   Remove User roles and capabilities
 Receive:   -None-
 Return:    -None-
 Since:		3.0
-------------------------------------------------------------*/
function adrotate_remove_roles() {
	global $wp_roles;
	
	// Current
	remove_role('adrotate_advertiser');

	// Remove in version 4 or so (also remove global!)
	remove_role('adrotate_clientstats'); 
	$wp_roles->remove_cap('administrator','adrotate_clients');
	$wp_roles->remove_cap('editor','adrotate_clients');
	$wp_roles->remove_cap('author','adrotate_clients');
	$wp_roles->remove_cap('contributor','adrotate_clients');
	$wp_roles->remove_cap('subscriber','adrotate_clients');
	$wp_roles->remove_cap('adrotate_advertisers','adrotate_clients');
	$wp_roles->remove_cap('adrotate_clientstats','adrotate_clients');
}
?>