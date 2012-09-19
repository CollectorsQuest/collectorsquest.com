<?php
/*  
Copyright 2010-2012 Arnan de Gans - AJdG Solutions (email : info@ajdg.net)
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

	// Mandatory
	$id 				= $_POST['adrotate_id'];
	$author 			= $_POST['adrotate_username'];
	$title	 			= strip_tags(htmlspecialchars(trim($_POST['adrotate_title'], "\t\n "), ENT_QUOTES));
	$bannercode			= htmlspecialchars(trim($_POST['adrotate_bannercode'], "\t\n "), ENT_QUOTES);
	$thetime 			= date('U');
	$active 			= $_POST['adrotate_active'];
	$sortorder			= strip_tags(htmlspecialchars(trim($_POST['adrotate_sortorder'], "\t\n "), ENT_QUOTES));

	// Schedule and timeframe variables
	$sday 					= strip_tags(trim($_POST['adrotate_sday'], "\t\n "));
	$smonth 				= strip_tags(trim($_POST['adrotate_smonth'], "\t\n "));
	$syear 					= strip_tags(trim($_POST['adrotate_syear'], "\t\n "));
	$shour 					= strip_tags(trim($_POST['adrotate_shour'], "\t\n "));
	$sminute				= strip_tags(trim($_POST['adrotate_sminute'], "\t\n "));
	$eday 					= strip_tags(trim($_POST['adrotate_eday'], "\t\n "));
	$emonth 				= strip_tags(trim($_POST['adrotate_emonth'], "\t\n "));
	$eyear 					= strip_tags(trim($_POST['adrotate_eyear'], "\t\n "));
	$ehour 					= strip_tags(trim($_POST['adrotate_ehour'], "\t\n "));
	$eminute				= strip_tags(trim($_POST['adrotate_eminute'], "\t\n "));
	$maxclicks				= strip_tags(trim($_POST['adrotate_maxclicks'], "\t\n "));
	$maxshown				= strip_tags(trim($_POST['adrotate_maxshown'], "\t\n "));
	$timeframe				= strip_tags(trim($_POST['adrotate_timeframe'], "\t\n "));
	$timeframelength		= strip_tags(trim($_POST['adrotate_timeframelength'], "\t\n "));
	$timeframeclicks		= strip_tags(trim($_POST['adrotate_timeframeclicks'], "\t\n "));
	$timeframeimpressions	= strip_tags(trim($_POST['adrotate_timeframeimpressions'], "\t\n "));
	$schedules				= $_POST['scheduleselect'];

	// Advanced options
	$image_field		= strip_tags(htmlspecialchars(trim($_POST['adrotate_image'], "\t\n "), ENT_QUOTES));
	$image_dropdown		= $_POST['adrotate_image_dropdown'];
	$link				= strip_tags(htmlspecialchars(trim($_POST['adrotate_link'], "\t\n "), ENT_QUOTES));
	$tracker			= $_POST['adrotate_tracker'];
	
	// Misc variabled
	$groups				= $_POST['groupselect'];
	$type				= strip_tags(trim($_POST['adrotate_type'], "\t\n "));
	$advertiser			= $_POST['adrotate_advertiser'];
	$weight				= $_POST['adrotate_weight'];

	if(current_user_can('adrotate_ad_manage')) {
		if(strlen($title) < 1) {
			$title = 'Ad '.$id;
		}

		// Sort out start dates
		if(strlen($smonth) > 0 AND !is_numeric($smonth)) 	$smonth 	= date_i18n('m');
		if(strlen($sday) > 0 AND !is_numeric($sday)) 		$sday 		= date_i18n('d');
		if(strlen($syear) > 0 AND !is_numeric($syear)) 		$syear 		= date_i18n('Y');
		if(strlen($shour) > 0 AND !is_numeric($shour)) 		$shour 		= date_i18n('H');
		if(strlen($sminute) > 0 AND !is_numeric($sminute))	$sminute	= date_i18n('i');
		if(($smonth > 0 AND $sday > 0 AND $syear > 0) AND strlen($shour) == 0) $shour = '00';
		if(($smonth > 0 AND $sday > 0 AND $syear > 0) AND strlen($sminute) == 0) $sminute = '00';

		if($smonth > 0 AND $sday > 0 AND $syear > 0) {
			$startdate = gmmktime($shour, $sminute, 0, $smonth, $sday, $syear);
		} else {
			$startdate = 0;
		}
		
		// Sort out end dates
		if(strlen($emonth) > 0 AND !is_numeric($emonth)) 	$emonth 	= $smonth;
		if(strlen($eday) > 0 AND !is_numeric($eday)) 		$eday 		= $sday;
		if(strlen($eyear) > 0 AND !is_numeric($eyear)) 		$eyear 		= $syear+1;
		if(strlen($ehour) > 0 AND !is_numeric($ehour)) 		$ehour 		= $shour;
		if(strlen($eminute) > 0 AND !is_numeric($eminute)) 	$eminute	= $sminute;
		if(($emonth > 0 AND $eday > 0 AND $eyear > 0) AND strlen($ehour) == 0) $ehour = '00';
		if(($emonth > 0 AND $eday > 0 AND $eyear > 0) AND strlen($eminute) == 0) $eminute = '00';

		if($emonth > 0 AND $eday > 0 AND $eyear > 0) {
			$enddate = gmmktime($ehour, $eminute, 0, $emonth, $eday, $eyear);
		} else {
			$enddate = 0;
		}
		
		// Enddate is too early, reset to default
		if($enddate <= $startdate) $enddate = $startdate + 7257600; // 84 days (12 weeks)

		// Determine if timeframe is valid
		if(($timeframeclicks == 0 OR $timeframeclicks == '') AND ($timeframeimpressions == 0 OR $timeframeimpressions == '') AND ($timeframelength == 0 OR $timeframelength == '')) $timeframe = ''; // disabled if 0
		if($timeframe == '') $timeframeclicks = $timeframeimpressions = $timeframelength = 0; // disabled if empty
		if($timeframeclicks > $timeframeimpressions) $timeframeclicks = $timeframeimpressions; // Reset if too high

		// Validate sort order
		if(strlen($sortorder) < 1 OR !is_numeric($sortorder) AND ($sortorder < 1 OR $sortorder > 99999)) $sortorder = $id;

		// Sort out click and impressions restrictions
		if(strlen($maxclicks) < 1 OR !is_numeric($maxclicks))	$maxclicks	= 0;
		if(strlen($maxshown) < 1 OR !is_numeric($maxshown))		$maxshown	= 0;

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

		// Save initial schedule for new ads or when a new schedule is made
		if($type == 'empty' OR ($startdate > 1 AND $enddate > 1)) {
		    $wpdb->insert($wpdb->prefix."adrotate_schedule", array('ad' => $id, 'starttime' => $startdate, 'stoptime' => $enddate, 'maxclicks' => $maxclicks, 'maximpressions' => $maxshown));
		}

		// Remove schedules from this ad
		if(!is_array($schedules)) $schedules = array();
		foreach($schedules as &$value) {
			$wpdb->query("DELETE FROM `".$wpdb->prefix."adrotate_schedule` WHERE `id` = '$value';"); 
		}
		unset($value);

		// Save the ad to the DB
	    $wpdb->update(
	    	$wpdb->prefix."adrotate", 
	    	array(
	    		'title' => $title, 
	    		'bannercode' => $bannercode, 
	    		'updated' => $thetime, 
	    		'author' => $author, 
	    		'imagetype' => $imagetype, 
	    		'image' => $image, 
	    		'link' => $link, 
	    		'tracker' => $tracker, 
	    		'timeframe' => $timeframe, 
	    		'timeframelength' => $timeframelength, 
	    		'timeframeclicks' => $timeframeclicks, 
	    		'timeframeimpressions' => $timeframeimpressions, 
	    		'weight' => $weight, 
	    		'sortorder' => $sortorder
	    	), 
	    	array(
	    		'id' => $id
	    	)
	    );

		if($active == "active") {
			// Determine status of ad 
			$adstate = adrotate_evaluate_ad($id);
			if($adstate == 'error' OR $adstate == 'expired') {
				$action = 'field_error';
				$active = 'error';
			} else {
				if($type == "empty") {
					$action = 'new';
				} else {
					$action = 'update';
				}
			}
		} 
	    $wpdb->update($wpdb->prefix."adrotate", array('type' => $active), array('id' => $id));

		// Check all ads and update ad cache
		adrotate_prepare_evaluate_ads();		

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
		    $wpdb->insert($wpdb->prefix."adrotate_linkmeta", array('ad' => $id, 'group' => $value, 'block' => 0, 'user' => 0));
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
		if($linkmeta == 0 AND $advertiser > 0)		$wpdb->insert($wpdb->prefix."adrotate_linkmeta", array('ad' => $id, 'group' => 0, 'block' => 0, 'user' => $advertiser));
		if($linkmeta == 1 AND $advertiser > 0) 		$wpdb->update($wpdb->prefix."adrotate_linkmeta", array('user' => $advertiser), array('ad' => $id, 'group' => 0, 'block' => 0));
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

	$action			= $_POST['adrotate_action'];
	$id 			= $_POST['adrotate_id'];
	$name 			= strip_tags(trim($_POST['adrotate_groupname'], "\t\n "));
	$fallback 		= $_POST['adrotate_fallback'];
	$ads			= $_POST['adselect'];
	$sortorder		= strip_tags(htmlspecialchars(trim($_POST['adrotate_sortorder'], "\t\n "), ENT_QUOTES));
	$categories		= $_POST['adrotate_categories'];
	$category_loc	= $_POST['adrotate_cat_location'];
	$pages			= $_POST['adrotate_pages'];
	$page_loc		= $_POST['adrotate_page_location'];

	if(current_user_can('adrotate_group_manage')) {
		if(strlen($name) < 1) $name = 'Group '.$id;

		// Validate sort order
		if(strlen($sortorder) < 1 OR !is_numeric($sortorder) AND ($sortorder < 1 OR $sortorder > 99999)) $sortorder = $id;

		// Categories
		if(!is_array($categories)) $categories = array();
		$category = '';
		foreach($categories as $key => $value) {
			$category = $category.','.$value;
		}
		$category = trim($category, ',');
		if(strlen($category) < 1) $category = '';
		if($category_loc < 0 OR $category_loc > 3) $category_loc = 0;

		// Pages
		if(!is_array($pages)) $pages = array();
		$page = '';
		foreach($pages as $key => $value) {
			$page = $page.','.$value;
		}
		$page = trim($page, ',');
		if(strlen($page) < 1) $page = '';
		if($page_loc < 0 OR $page_loc > 3) $page_loc = 0;

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
			$wpdb->insert($wpdb->prefix."adrotate_linkmeta", array('ad' => $value, 'group' => $id, 'block' => 0, 'user' => 0));
		}
		unset($value);
		
		// Remove ads from this group
		$delete = array_diff($meta_array,$ads);
		foreach($delete as &$value) {
			$wpdb->query("DELETE FROM `".$wpdb->prefix."adrotate_linkmeta` WHERE `ad` = '$value' AND `group` = '$id' AND `block` = 0 AND `user` = 0;"); 
		}
		unset($value);

		// Update the group itself
	    $wpdb->update(
	    	$wpdb->prefix."adrotate_groups", 
	    	array(
	    		'name' => $name, 
	    		'fallback' => $fallback, 
	    		'sortorder' => $sortorder, 
	    		'cat' => $category, 
	    		'cat_loc' => $category_loc, 
	    		'page' => $page, 
	    		'page_loc' => $page_loc
	    	), 
	    	array(
	    		'id' => $id
	    	)
	    );

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

	$rows			= strip_tags(trim($_POST['adrotate_gridrows'], "\t\n "));
	$columns 		= strip_tags(trim($_POST['adrotate_gridcolumns'], "\t\n "));
	$gridfloat 		= strip_tags(trim($_POST['adrotate_gridfloat'], "\t\n "));
	$gridpadding	= strip_tags(trim($_POST['adrotate_gridpadding'], "\t\n "));
	$gridpx			= strip_tags(trim($_POST['adrotate_gridborderpx'], "\t\n "));
	$gridcolor		= strip_tags(trim($_POST['adrotate_gridbordercolor'], "\t\n "));
	$gridstyle 		= strip_tags(trim($_POST['adrotate_gridborderstyle'], "\t\n "));

	$adwidth		= strip_tags(trim($_POST['adrotate_adwidth'], "\t\n "));
	$adheight 		= strip_tags(trim($_POST['adrotate_adheight'], "\t\n "));
	$admargin		= strip_tags(trim($_POST['adrotate_admargin'], "\t\n "));
	$adpadding		= strip_tags(trim($_POST['adrotate_adpadding'], "\t\n "));
	$adpx			= strip_tags(trim($_POST['adrotate_adborderpx'], "\t\n "));
	$adcolor		= strip_tags(trim($_POST['adrotate_adbordercolor'], "\t\n "));
	$adstyle		= strip_tags(trim($_POST['adrotate_adborderstyle'], "\t\n "));

	$wrapper_before	= trim($_POST['adrotate_wrapper_before'], "\t\n ");
	$wrapper_after	= trim($_POST['adrotate_wrapper_after'], "\t\n ");
	$groups 		= $_POST['groupselect'];
	$sortorder		= strip_tags(htmlspecialchars(trim($_POST['adrotate_sortorder'], "\t\n "), ENT_QUOTES));

	if(current_user_can('adrotate_block_manage')) {
		if(strlen($name) < 1) $name = 'Block '.$id;
		if($rows < 1 OR $rows == '' OR !is_numeric($rows)) $rows = 2;
		if($columns < 1 OR $columns == '' OR !is_numeric($columns)) $columns = 2;

		// Sort out block shape
		if($gridpadding < 0 OR $gridpadding > 99 OR $gridpadding == '' OR !is_numeric($gridpadding)) $gridpadding = 0;
		if($gridpx >= 1 AND $gridpx <= 99 AND is_numeric($gridpx) AND $gridcolor != '' AND preg_match('/^#[a-f0-9]{6}$/i', $gridcolor) AND $gridstyle != 'none') {
			$gridborder = $gridpx."px ".$gridcolor." ".$gridstyle;
		} else {
			$gridborder = 'none';
		}
		
		// Sort out advert specs
		if($adwidth < 1 OR $adwidth > 1000 OR $adwidth == '' OR !is_numeric($adwidth)) $adwidth = '125';
		if((is_numeric($adheight) AND $adheight < 1 OR $adheight > 1000) OR $adheight == '' OR (!is_numeric($adheight) AND $adheight != 'auto')) $adheight = '125';
		if($admargin < 0 OR $admargin > 99 OR $admargin == '' OR !is_numeric($admargin)) $admargin = 0;
		if($adpadding < 0 OR $adpadding > 99 OR  $adpadding == '' OR !is_numeric($adpadding)) $adpadding = 0;
		if($adpx >= 1 AND $adpx <= 99 AND is_numeric($adpx) AND $adcolor != '' AND preg_match('/^#[a-f0-9]{6}$/i', $adcolor) AND $adstyle != '') {
			$adborder = $adpx."px ".$adcolor." ".$adstyle;
		} else {
			$adborder = 'none';
		}

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
			$wpdb->insert($wpdb->prefix."adrotate_linkmeta", array('ad' => 0, 'group' => $value, 'block' => $id, 'user' => 0));
		}
		unset($value);
		
		// Remove groups from this block
		$delete = array_diff($meta_array,$groups);
		foreach($delete as &$value) {
			$wpdb->query("DELETE FROM `".$wpdb->prefix."adrotate_linkmeta` WHERE `ad` = 0 AND `group` = '$value' AND `block` = '$id' AND `user` = 0;"); 
		}
		unset($value);

		// Update the block itself
	    $wpdb->update(
	    	$wpdb->prefix."adrotate_blocks", 
	    	array(
	    		'name' => $name, 
	    		'rows' => $rows, 
	    		'columns' => $columns, 
	    		'gridfloat' => $gridfloat, 
	    		'gridpadding' => $gridpadding, 
	    		'gridborder' => $gridborder, 
	    		'adwidth' => $adwidth, 
	    		'adheight' => $adheight, 
	    		'adpadding' => $adpadding, 
	    		'admargin' => $admargin, 
	    		'adpadding' => $adpadding, 
	    		'adborder' => $adborder, 
	    		'wrapper_before' => $wrapper_before, 
	    		'wrapper_after' => $wrapper_after, 
	    		'sortorder' => $sortorder
	    	), 
	    	array(
	    		'id' => $id
	    	)
	    );
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

	if($id > 0) {
		if($what == 'banner') {
			$wpdb->query("DELETE FROM `".$wpdb->prefix."adrotate` WHERE `id` = $id;");
			$wpdb->query("DELETE FROM `".$wpdb->prefix."adrotate_linkmeta` WHERE `ad` = $id;");
			$wpdb->query("DELETE FROM `".$wpdb->prefix."adrotate_schedule` WHERE `ad` = $id;");
			$wpdb->query("DELETE FROM `".$wpdb->prefix."adrotate_stats_tracker` WHERE `ad` = $id;");
		} else if ($what == 'group') {
			$wpdb->query("DELETE FROM `".$wpdb->prefix."adrotate_groups` WHERE `id` = $id;");
			$wpdb->query("DELETE FROM `".$wpdb->prefix."adrotate_linkmeta` WHERE `group` = $id;");
		} else if ($what == 'block') {
			$wpdb->query("DELETE FROM `".$wpdb->prefix."adrotate_blocks` WHERE `id` = $id;");
			$wpdb->query("DELETE FROM `".$wpdb->prefix."adrotate_linkmeta` WHERE `block` = $id;");
		} else if ($what == 'bannergroup') {
			$linkmeta = $wpdb->get_results("SELECT `ad` FROM `".$wpdb->prefix."adrotate_linkmeta` WHERE `group` = '$id' AND `block` = '0';");
			foreach($linkmeta as $meta) {
				$wpdb->query("DELETE FROM `".$wpdb->prefix."adrotate` WHERE `id` = ".$meta->ad.";");
				$wpdb->query("DELETE FROM `".$wpdb->prefix."adrotate_stats_tracker` WHERE `ad` = ".$meta->ad.";");
				$wpdb->query("DELETE FROM `".$wpdb->prefix."adrotate_schedule` WHERE `ad` = ".$meta->ad.";");
				$wpdb->query("DELETE FROM `".$wpdb->prefix."adrotate_linkmeta` WHERE `ad` = ".$meta->ad.";");
			}
			$wpdb->query("DELETE FROM `".$wpdb->prefix."adrotate_groups` WHERE `id` = $id;");
			$wpdb->query("DELETE FROM `".$wpdb->prefix."adrotate_linkmeta` WHERE `group` = $id;");
			$wpdb->query("DELETE FROM `".$wpdb->prefix."adrotate_stats_tracker` WHERE `group` = $id;"); // Perhaps unnessesary
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
			$wpdb->update($wpdb->prefix."adrotate", array('type' => 'disabled'), array('id' => $id));
		}
		if ($what == 'activate') {
			// Determine status of ad 
			$adstate = adrotate_evaluate_ad($id);
			if($adstate == true) $adtype = 'error';
				else $adtype = 'active';
			$wpdb->update($wpdb->prefix."adrotate", array('type' => $adtype), array('id' => $id));
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

 Purpose:   Renew the end date of a banner with a new schedule starting where the last ended
 Receive:   $id, $howlong
 Return:    -none-
 Since:		2.2
-------------------------------------------------------------*/
function adrotate_renew($id, $howlong = 2592000) {
	global $wpdb;

	if($id > 0) {
		$starttime = $wpdb->get_var("SELECT `stoptime` FROM `".$wpdb->prefix."adrotate_schedule` WHERE `ad` = '".$id."' ORDER BY `id` DESC LIMIT 1;");
		$stoptime = $starttime + $howlong;

		$wpdb->insert(
			$wpdb->prefix."adrotate_schedule", 
			array(
				'ad' => $id, 
				'starttime' => $starttime, 
				'stoptime' => $stoptime, 
				'maxclicks' => 0, 
				'maximpressions' => 0
			)
		);
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
		$wpdb->update($wpdb->prefix."adrotate", array('weight' => $weight), array('id' => $id));
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

	// Set and save user roles
	adrotate_set_capability($_POST['adrotate_advertiser'], "adrotate_advertiser");
	adrotate_set_capability($_POST['adrotate_global_report'], "adrotate_global_report");
	adrotate_set_capability($_POST['adrotate_ad_manage'], "adrotate_ad_manage");
	adrotate_set_capability($_POST['adrotate_ad_delete'], "adrotate_ad_delete");
	adrotate_set_capability($_POST['adrotate_group_manage'], "adrotate_group_manage");
	adrotate_set_capability($_POST['adrotate_group_delete'], "adrotate_group_delete");
	adrotate_set_capability($_POST['adrotate_block_manage'], "adrotate_block_manage");
	adrotate_set_capability($_POST['adrotate_block_delete'], "adrotate_block_delete");
	$config['advertiser'] 			= $_POST['adrotate_advertiser'];
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
	if(isset($_POST['adrotate_debug_track'])) 			$debug['track'] 		= true;
		else 											$debug['track']			= false;
	if(isset($_POST['adrotate_debug_upgrade'])) 		$debug['upgrade'] 		= true;
		else 											$debug['upgrade']		= false;
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
	// End remove
}
?>