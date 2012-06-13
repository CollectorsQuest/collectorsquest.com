<?php
/*  
Copyright 2010-2011 Arnan de Gans  (email : adegans@meandmymac.net)
*/

/*-------------------------------------------------------------
 Name:      adrotate_activate

 Purpose:   Creates database table if it doesnt exist
 Receive:   -none-
 Return:	-none-
 Since:		0.1
-------------------------------------------------------------*/
function adrotate_activate() {
	global $wpdb, $wp_roles, $adrotate_roles;

	if (version_compare(PHP_VERSION, '5.2.0', '<')) { 
		deactivate_plugins(plugin_basename('adrotate.php'));
		wp_die('AdRotate 3.6 and up requires PHP 5.2 or higher.<br />You likely have PHP 4, which has been discontinued since december 31, 2007. Consider upgrading your server!<br /><a href="'. get_option('siteurl').'/wp-admin/plugins.php">Back to plugins</a>.'); 
		return; 
	} else {
		if(!current_user_can('activate_plugins')) {
			deactivate_plugins(plugin_basename('adrotate.php'));
			wp_die('You must be an administrator to activate this plugin!<br /><a href="'. get_option('siteurl').'/wp-admin/plugins.php">Back to plugins</a>.'); 
			return; 
		} else {
			// Install tables for AdRotate
			adrotate_database_install();
		
			// Run a schedule for email notifications
			if (!wp_next_scheduled('adrotate_ad_notification')) 
				wp_schedule_event(date('U'), '1day', 'adrotate_ad_notification');
		
			// Set the capabilities for the administrator
			$role = get_role('administrator');		
			$role->add_cap("adrotate_advertiser_report");
			$role->add_cap("adrotate_global_report");
			$role->add_cap("adrotate_ad_manage");
			$role->add_cap("adrotate_ad_delete");
			$role->add_cap("adrotate_group_manage");
			$role->add_cap("adrotate_group_delete");
			$role->add_cap("adrotate_block_manage");
			$role->add_cap("adrotate_block_delete");
		
			// Switch additional roles on or off
			if($adrotate_roles = 1) {
				// Remove old named roles
				adrotate_remove_roles();
				// Set or reset the roles
				adrotate_add_roles();
			} else {
				update_option('adrotate_roles', '0');
			}

			// Set default settings and values
			add_option('adrotate_db_timer', date('U'));
			add_option('adrotate_debug', array('general' => false, 'dashboard' => false, 'userroles' => false, 'userstats' => false, 'stats' => false));

			adrotate_check_config();
	
			// Attempt to make the wp-content/banners/ folder
			if(!is_dir(ABSPATH.'/wp-content/banners')) {
				mkdir(ABSPATH.'/wp-content/banners', 0755);
			}
		}
	}
}

/*-------------------------------------------------------------
 Name:      adrotate_database_install

 Purpose:   Creates database table if it doesnt exist
 Receive:   -none-
 Return:	-none-
 Since:		3.0.3
-------------------------------------------------------------*/
function adrotate_database_install() {
	global $wpdb;

	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

	$tables = adrotate_list_tables();

	if ( $wpdb->has_cap( 'collation' ) ) {
		if ( ! empty($wpdb->charset) )
			$charset_collate = " DEFAULT CHARACTER SET $wpdb->charset";
		if ( ! empty($wpdb->collate) )
			$charset_collate .= " COLLATE $wpdb->collate";
	}

	if(!$wpdb->get_var("SHOW TABLES LIKE '".$tables['adrotate']."'")) { // wp_adrotate
		$sql = "CREATE TABLE `".$tables['adrotate']."` (
			  	`id` mediumint(8) unsigned NOT NULL auto_increment,
			  	`title` longtext NOT NULL,
			  	`bannercode` longtext NOT NULL,
			  	`thetime` int(15) NOT NULL default '0',
				`updated` int(15) NOT NULL,
			  	`author` varchar(60) NOT NULL default '',
			  	`active` varchar(4) NOT NULL default 'yes',
			  	`startshow` int(15) NOT NULL default '0',
			  	`endshow` int(15) NOT NULL default '0',
			  	`imagetype` varchar(9) NOT NULL,
			  	`image` varchar(255) NOT NULL,
			  	`link` longtext NOT NULL,
			  	`tracker` varchar(5) NOT NULL default 'N',
			  	`maxclicks` int(15) NOT NULL default '0',
			  	`maxshown` int(15) NOT NULL default '0',			  
			  	`targetclicks` int(15) NOT NULL default '0',			  
			  	`targetimpressions` int(15) NOT NULL default '0',			  
			  	`type` varchar(10) NOT NULL default '0',
			  	`weight` int(3) NOT NULL default '6',
				`sortorder` int(5) NOT NULL default '0',
	  		PRIMARY KEY  (`id`)
			) ".$charset_collate." ENGINE = 'MyISAM';";
		dbDelta($sql);
	}

	if(!$wpdb->get_var("SHOW TABLES LIKE '".$tables['adrotate_groups']."'")) { // wp_adrotate_groups
		$sql = "CREATE TABLE `".$tables['adrotate_groups']."` (
				`id` mediumint(8) unsigned NOT NULL auto_increment,
				`name` varchar(255) NOT NULL default 'group',
				`fallback` varchar(5) NOT NULL default '0',
				`sortorder` int(5) NOT NULL default '0',
				PRIMARY KEY  (`id`)
			) ".$charset_collate." ENGINE = 'MyISAM';";
		dbDelta($sql);
	}

	if(!$wpdb->get_var("SHOW TABLES LIKE '".$tables['adrotate_tracker']."'")) { // wp_adrotate_tracker
		$sql = "CREATE TABLE `".$tables['adrotate_tracker']."` (
				`id` mediumint(8) unsigned NOT NULL auto_increment,
				`ipaddress` varchar(255) NOT NULL default '0',
				`timer` int(15) NOT NULL default '0',
				`bannerid` int(15) NOT NULL default '0',
				`stat` char(1) NOT NULL default 'c',
				`useragent` mediumtext NOT NULL,
				PRIMARY KEY  (`id`)
			) ".$charset_collate." ENGINE = 'MyISAM';";
		dbDelta($sql);
	}

	if(!$wpdb->get_var("SHOW TABLES LIKE '".$tables['adrotate_blocks']."'")) { // wp_adrotate_blocks
		$sql = "CREATE TABLE `".$tables['adrotate_blocks']."` (
				`id` mediumint(8) unsigned NOT NULL auto_increment,
				`name` varchar(255) NOT NULL default 'Block',
				`adcount` int(3) NOT NULL default '1',
				`columns` int(3) NOT NULL default '1',
				`wrapper_before` longtext NOT NULL,
				`wrapper_after` longtext NOT NULL,
				`sortorder` int(5) NOT NULL default '0',
				PRIMARY KEY  (`id`)
			) ".$charset_collate." ENGINE = 'MyISAM';";
		dbDelta($sql);
	}
	
	if(!$wpdb->get_var("SHOW TABLES LIKE '".$tables['adrotate_linkmeta']."'")) { // wp_adrotate_linkmeta
		$sql = "CREATE TABLE `".$tables['adrotate_linkmeta']."` (
				`id` mediumint(8) unsigned NOT NULL auto_increment,
				`ad` int(5) NOT NULL default '0',
				`group` int(5) NOT NULL default '0',
				`block` int(5) NOT NULL default '0',
				`user` int(5) NOT NULL default '0',
				PRIMARY KEY  (`id`)
			) ".$charset_collate." ENGINE = 'MyISAM';";
		dbDelta($sql);
	}

	if(!$wpdb->get_var("SHOW TABLES LIKE '".$tables['adrotate_stats_tracker']."'")) { // wp_adrotate_stats_tracker
		$sql = "CREATE TABLE `".$tables['adrotate_stats_tracker']."` (
				`id` mediumint(8) unsigned NOT NULL auto_increment,
				`ad` int(5) NOT NULL default '0',
				`group` int(5) NOT NULL default '0',
				`block` int(5) NOT NULL default '0',
				`thetime` int(15) NOT NULL default '0',
				`clicks` int(15) NOT NULL default '0',
				`impressions` int(15) NOT NULL default '0',
				PRIMARY KEY  (`id`)
			) ".$charset_collate." ENGINE = 'MyISAM';";
		dbDelta($sql);
	}

	add_option("adrotate_version", ADROTATE_VERSION);
	add_option("adrotate_db_version", ADROTATE_DB_VERSION);
}

/*-------------------------------------------------------------
 Name:      adrotate_database_upgrade

 Purpose:   Upgrades AdRotate where required
 Receive:   -none-
 Return:	-none-
 Since:		3.0.3
-------------------------------------------------------------*/
function adrotate_database_upgrade() {
	global $wpdb, $adrotate_db_version;

	if (version_compare(PHP_VERSION, '5.2.0', '<') == -1) { 
		deactivate_plugins(plugin_basename('adrotate.php'));
		wp_die('AdRotate 3.6 and up requires PHP 5.2 or higher.<br />You likely have PHP 4, which has been discontinued since december 31, 2007. Consider upgrading your server!<br /><a href="'. get_option('siteurl').'/wp-admin/plugins.php">Back to plugins</a>.'); 
		return; 
	} else {
		// Install tables for AdRotate where required
		adrotate_database_install();

		$tables = adrotate_list_tables();

		// Database: 	1
		if($adrotate_db_version < 1) {
			// Migrate group data to accomodate version 3.0 and up from earlier setups
			$banners = $wpdb->get_results("SELECT `id`, `group` FROM ".$tables['adrotate']." ORDER BY `id` ASC;");
			foreach($banners as $banner) {
				$wpdb->query("INSERT INTO `".$tables['adrotate_linkmeta']."` (`ad`, `group`, `block`, `user`) VALUES (".$banner->id.", ".$banner->group.", 0, 0);");
			}
			unset($banners);
	
			adrotate_add_column($tables['adrotate'], 'startshow', 'INT( 15 ) NOT NULL DEFAULT \'0\' AFTER `active`');
			adrotate_add_column($tables['adrotate'], 'endshow', 'INT( 15 ) NOT NULL DEFAULT \'0\' AFTER `startshow`');
			adrotate_add_column($tables['adrotate'], 'link', 'LONGTEXT NOT NULL AFTER `image`');
			adrotate_add_column($tables['adrotate'], 'tracker', 'VARCHAR( 5 ) NOT NULL DEFAULT \'N\' AFTER `link`');
			adrotate_add_column($tables['adrotate'], 'clicks', 'INT( 15 ) NOT NULL DEFAULT \'0\' AFTER `tracker`');
			adrotate_add_column($tables['adrotate'], 'maxclicks', 'INT( 15 ) NOT NULL DEFAULT \'0\' AFTER `clicks`');
			adrotate_add_column($tables['adrotate'], 'shown', 'INT( 15 ) NOT NULL DEFAULT \'0\' `maxclicks`');
			adrotate_add_column($tables['adrotate'], 'maxshown', 'INT( 15 ) NOT NULL DEFAULT \'0\' AFTER `shown`');
			adrotate_add_column($tables['adrotate'], 'type', 'VARCHAR( 10 ) NOT NULL DEFAULT \'manual\' AFTER `maxshown`');
			
			adrotate_add_column($tables['adrotate_groups'], 'fallback', 'VARCHAR( 5 ) NOT NULL DEFAULT \'0\' AFTER `name`');
			
			adrotate_add_column($tables['adrotate_tracker'], 'bannerid', 'INT( 15 ) NOT NULL DEFAULT \'0\' AFTER `timer`');
			
			$wpdb->query("ALTER TABLE `".$tables['adrotate_tracker']."` CHANGE `ipaddress` `ipaddress` varchar(255) NOT NULL DEFAULT '0';");
	
			$wpdb->query("UPDATE `".$tables['adrotate']."` SET `type` = 'manual' WHERE `magic` = '0' AND `title` != '';");
			$wpdb->query("UPDATE `".$tables['adrotate']."` SET `type` = 'manual' WHERE `magic` = '1' AND `title` != '';");
			$wpdb->query("UPDATE `".$tables['adrotate']."` SET `type` = 'empty' WHERE `magic` = '2';");
	
			$wpdb->query("ALTER TABLE `".$tables['adrotate']."` DROP `magic`;");
			$wpdb->query("ALTER TABLE `".$tables['adrotate']."` DROP `group`;");
		}
	
		// Database: 	3
		if($adrotate_db_version < 3) {
			adrotate_add_column($tables['adrotate'], 'weight', 'INT( 3 ) NOT NULL DEFAULT \'6\' AFTER `type`');
		}
		
		// Database: 	5
		if($adrotate_db_version < 5) {
			$today = mktime(0, 0, 0, gmdate("m"), gmdate("d"), gmdate("Y"));
			// Migrate current statistics to accomodate version 3.5s new stats system
			$ads = $wpdb->get_results("SELECT `id`, `clicks`, `shown` FROM ".$tables['adrotate']." ORDER BY `id` ASC;");
			foreach($ads as $ad) {
				$wpdb->query("INSERT INTO `".$tables['adrotate_stats_tracker']."` (`ad`, `thetime`, `clicks`, `impressions`) VALUES (".$ad->id.", ".$today.", ".$ad->clicks.", ".$ad->shown.");");
			}
			unset($ads);

			$wpdb->query("ALTER TABLE `".$tables['adrotate']."` DROP `clicks`;");
			$wpdb->query("ALTER TABLE `".$tables['adrotate']."` DROP `shown`;");
		}
		
		// Database: 	6
		if($adrotate_db_version < 6) {
			$wpdb->query("DROP TABLE `".$tables['adrotate_stats_cache']."`;");
		}
		
		// Database: 	7
		if($adrotate_db_version < 7) {
			adrotate_add_column($tables['adrotate'], 'targetclicks', 'INT( 15 ) NOT NULL DEFAULT \'0\' AFTER `maxshown`');
			adrotate_add_column($tables['adrotate'], 'targetimpressions', 'INT( 15 ) NOT NULL DEFAULT \'0\' AFTER `targetclicks`');
		}
		
		// Database: 	8
		if($adrotate_db_version < 8) {
			// Convert image data to accomodate version 3.6 and up from earlier setups
			$images = $wpdb->get_results("SELECT `id`, `image` FROM ".$tables['adrotate']." ORDER BY `id` ASC;");
			foreach($images as $image) {
				if(strlen($image->image) > 0) {
					if(preg_match("/wp-content\/banners\//i", $image->image)) {
						$wpdb->query("UPDATE `".$tables['adrotate']."` SET `image` = 'dropdown|$image->image' WHERE `id` = '$image->id';");
					} else {
						$wpdb->query("UPDATE `".$tables['adrotate']."` SET `image` = 'field|$image->image' WHERE `id` = '$image->id';");
					}
				}
			}
		}

		// Database: 	10
		// AdRotate: 	3.6.2
		if($adrotate_db_version < 10) {
			adrotate_add_column($tables['adrotate_tracker'], 'stat', 'CHAR(1) NOT NULL DEFAULT \'c\' AFTER `bannerid`');
			$wpdb->query("UPDATE `".$tables['adrotate_tracker']."` SET `stat` = 'c' WHERE `stat` = '';");
		}
		
		// Database: 	11
		// AdRotate: 	3.6.4
		if($adrotate_db_version < 11) {
			adrotate_add_column($tables['adrotate'], 'sortorder', 'int(5) NOT NULL DEFAULT \'0\' AFTER `weight`');
			adrotate_add_column($tables['adrotate_groups'], 'sortorder', 'int(5) NOT NULL DEFAULT \'0\' AFTER `fallback`');
			adrotate_add_column($tables['adrotate_blocks'], 'sortorder', 'int(5) NOT NULL DEFAULT \'0\' AFTER `wrapper_after`');

			// Convert image data to accomodate version 3.6.4 and up from earlier setups
			adrotate_add_column($tables['adrotate'], 'imagetype', 'varchar(10) NOT NULL AFTER `endshow`');

			$images = $wpdb->get_results("SELECT `id`, `image` FROM ".$tables['adrotate']." ORDER BY `id` ASC;");
			foreach($images as $image) {
				if(strlen($image->image) > 0) {
					if(preg_match("/dropdown|/i", $image->image) OR preg_match("/field|/i", $image->image)) {
						$buffer = explode("|", $image->image, 3);
						$wpdb->query("UPDATE `".$tables['adrotate']."` SET `imagetype` = '".$buffer[0]."', `image` = '".$buffer[1]."' WHERE `id` = '$image->id';");
					}
				}
			}
		}

		// Database: 	12
		// AdRotate: 	3.6.5
		if($adrotate_db_version < 12) {
			adrotate_add_column($tables['adrotate_tracker'], 'useragent', 'mediumtext NOT NULL AFTER `stat`');
		}

		update_option("adrotate_db_version", ADROTATE_DB_VERSION);
	}
}

/*-------------------------------------------------------------
 Name:      adrotate_core_upgrade

 Purpose:   Upgrades AdRotate where required
 Receive:   -none-
 Return:	-none-
 Since:		3.5
-------------------------------------------------------------*/
function adrotate_core_upgrade() {
	global $wp_roles, $adrotate_version;

	if (version_compare(PHP_VERSION, '5.2.0', '<') == -1) { 
		deactivate_plugins(plugin_basename('adrotate.php'));
		wp_die('AdRotate 3.6 and up requires PHP 5.2 or higher.<br />You likely have PHP 4, which has been discontinued since december 31, 2007. Consider upgrading your server!<br /><a href="'. get_option('siteurl').'/wp-admin/plugins.php">Back to plugins</a>.'); 
		return; 
	} else {
		if($adrotate_version < 323) {
			delete_option('adrotate_notification_timer');
		}
		
		if($adrotate_version < 340) {
			add_option('adrotate_db_timer', date('U'));
		}

		if($adrotate_version < 350) {
			update_option('adrotate_debug', array('general' => false, 'dashboard' => false, 'userroles' => false, 'userstats' => false, 'stats' => false));
		}

		if($adrotate_version < 351) {
			wp_clear_scheduled_hook('adrotate_prepare_cache_statistics');
			delete_option('adrotate_stats');
		}

		if($adrotate_version < 352) {
			adrotate_remove_capability("adrotate_userstatistics"); // OBSOLETE IN 3.5
			adrotate_remove_capability("adrotate_globalstatistics"); // OBSOLETE IN 3.5
			$role = get_role('administrator');		
			$role->add_cap("adrotate_advertiser_report"); // NEW IN 3.5
			$role->add_cap("adrotate_global_report"); // NEW IN 3.5
		}

		if($adrotate_version < 353) {
			if(!is_dir(ABSPATH.'/wp-content/plugins/adrotate/language')) {
				mkdir(ABSPATH.'/wp-content/plugins/adrotate/language', 0755);
			}
		}

		if($adrotate_version < 354) {
			$crawlers = array("Teoma", "alexa", "froogle", "Gigabot", "inktomi","looksmart", "URL_Spider_SQL", "Firefly", "NationalDirectory","Ask Jeeves", "TECNOSEEK", "InfoSeek", "WebFindBot", "girafabot","www.galaxy.com", "Googlebot", "Scooter", "Slurp","msnbot", "appie", "FAST", "WebBug", "Spade", "ZyBorg", "rabaz","Baiduspider", "Feedfetcher-Google", "TechnoratiSnoop", "Rankivabot","Mediapartners-Google", "Sogou web spider", "WebAlta Crawler","bot", "crawler", "yahoo", "msn", "ask", "ia_archiver");
			update_option('adrotate_crawlers', $crawlers);
		}

		update_option("adrotate_version", ADROTATE_VERSION);
	}
}

/*-------------------------------------------------------------
 Name:      adrotate_deactivate

 Purpose:   Deactivate script
 Receive:   -none-
 Return:	-none-
 Since:		2.0
-------------------------------------------------------------*/
function adrotate_deactivate() {
	global $adrotate_roles;
	
	// Clear out roles
	if($adrotate_roles == 1) {
		adrotate_remove_roles();
	}

	// Clear up capabilities from ALL users
	adrotate_remove_capability("adrotate_advertiser_report");
	adrotate_remove_capability("adrotate_global_report");
	adrotate_remove_capability("adrotate_ad_manage");
	adrotate_remove_capability("adrotate_ad_delete");
	adrotate_remove_capability("adrotate_group_manage");
	adrotate_remove_capability("adrotate_group_delete");
	adrotate_remove_capability("adrotate_block_manage");
	adrotate_remove_capability("adrotate_block_delete");

	// Clear out wp_cron
	wp_clear_scheduled_hook('adrotate_ad_notification');
	wp_clear_scheduled_hook('adrotate_cache_statistics'); // OBSOLETE IN 3.6 - REMOVE IN 4.0
}

/*-------------------------------------------------------------
 Name:      adrotate_uninstall

 Purpose:   Delete the entire database tables and remove the options on uninstall.
 Receive:   -none-
 Return:	-none-
 Since:		2.4.2
-------------------------------------------------------------*/
function adrotate_uninstall() {
	global $wpdb, $wp_roles;

	/* Changelog:
	// Nov 15 2010 - Moved function to work with WP's uninstall system, stripped out unnessesary code
	// Dec 13 2010 - Updated uninstaller to properly remove options for the new installer
	// Jan 21 2011 - Added capability cleanup
	// Jan 24 2011 - Added adrotate_version removal
	// Jan 25 2011 - Moved to adrotate-setup.php
	*/

	// Drop MySQL Tables
	$wpdb->query("DROP TABLE `".$wpdb->prefix."adrotate`");
	$wpdb->query("DROP TABLE `".$wpdb->prefix."adrotate_groups`");
	$wpdb->query("DROP TABLE `".$wpdb->prefix."adrotate_tracker`");
	$wpdb->query("DROP TABLE `".$wpdb->prefix."adrotate_blocks`");
	$wpdb->query("DROP TABLE `".$wpdb->prefix."adrotate_linkmeta`");
	$wpdb->query("DROP TABLE `".$wpdb->prefix."adrotate_stats_tracker`");

	// Delete Options	
	delete_option('adrotate_config');				// Since 0.1
	delete_option('adrotate_notification_timer'); 	// Since 3.0 - Obsolete in 3.2.3
	delete_option('adrotate_crawlers'); 			// Since 3.0
	delete_option('adrotate_stats');				// Since 3.0 - Obsolete in 3.5
	delete_option('adrotate_roles');				// Since 3.0
	delete_option('adrotate_version');				// Since 3.2.3
	delete_option('adrotate_db_version');			// Since 3.0.3
	delete_option('adrotate_debug');				// Since 3.2

	// Clear out userroles
	remove_role('adrotate_advertiser');

	// Clear up capabilities from ALL users
	adrotate_remove_capability("adrotate_advertiser_report");
	adrotate_remove_capability("adrotate_global_report");
	adrotate_remove_capability("adrotate_ad_manage");
	adrotate_remove_capability("adrotate_ad_delete");
	adrotate_remove_capability("adrotate_group_manage");
	adrotate_remove_capability("adrotate_group_delete");
	adrotate_remove_capability("adrotate_block_manage");
	adrotate_remove_capability("adrotate_block_delete");
		
	// Delete cron schedules
	wp_clear_scheduled_hook('adrotate_ad_notification');
	wp_clear_scheduled_hook('adrotate_prepare_cache_statistics'); // OBSOLETE IN 3.6 - REMOVE IN 4.0
}

/*-------------------------------------------------------------
 Name:      adrotate_optimize_database

 Purpose:   Optimizes all AdRotate tables
 Receive:   -none-
 Return:    -none-
 Since:		3.4
-------------------------------------------------------------*/
function adrotate_optimize_database() {
	global $wpdb;
	
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

	$adrotate_db_timer 	= get_option('adrotate_db_timer');

	$now = current_time('timestamp');
	$yesterday = $now - 86400;

	$tables = adrotate_list_tables();

	if($adrotate_db_timer < $yesterday) {
		foreach($tables as $table) {
			if($wpdb->get_var("SHOW TABLES LIKE '".$table."';")) {
				dbDelta("OPTIMIZE TABLE `$table`;");
			}
		}
		update_option('adrotate_db_timer', $now);
		adrotate_return('db_optimized');
	} else {
		adrotate_return('db_timer');
	}
}

/*-------------------------------------------------------------
 Name:      adrotate_cleanup_database

 Purpose:   Clean AdRotate tables
 Receive:   -none-
 Return:    -none-
 Since:		3.5
-------------------------------------------------------------*/
function adrotate_cleanup_database() {
	global $wpdb;

	$wpdb->query("DELETE FROM `".$wpdb->prefix."adrotate` WHERE `type` = 'empty';");
	$wpdb->query("DELETE FROM `".$wpdb->prefix."adrotate_groups` WHERE `name` = '';");
	$wpdb->query("DELETE FROM `".$wpdb->prefix."adrotate_blocks` WHERE `name` = '';");
	adrotate_return('db_cleaned');
}

/*-------------------------------------------------------------
 Name:      adrotate_add_column

 Purpose:   Check if the column exists in the table
 Receive:   $table_name, $column_name, $attributes
 Return:	Boolean
 Since:		3.0.3
-------------------------------------------------------------*/
function adrotate_add_column($table_name, $column_name, $attributes) {
	global $wpdb;
	
	foreach ($wpdb->get_col("SHOW COLUMNS FROM $table_name;") as $column ) {
		if ($column == $column_name) return true;
	}
	
	$wpdb->query("ALTER TABLE $table_name ADD $column_name " . $attributes.";");
	
	foreach ($wpdb->get_col("SHOW COLUMNS FROM $table_name;") as $column ) {
		if ($column == $column_name) return true;
	}
	
	echo("Could not add column $column_name in table $table_name<br />\n");
	return false;
}

/*-------------------------------------------------------------
 Name:      adrotate_list_tables

 Purpose:   List tables for AdRotate in an array
 Receive:   -None-
 Return:	-None-
 Since:		3.4
-------------------------------------------------------------*/
function adrotate_list_tables() {
	global $wpdb;

	$tables = array(
		'adrotate' 					=> $wpdb->prefix . "adrotate",					// Since 0.1
		'adrotate_groups' 			=> $wpdb->prefix . "adrotate_groups",			// Since 0.2
		'adrotate_tracker' 			=> $wpdb->prefix . "adrotate_tracker",			// Since 2.0
		'adrotate_blocks' 			=> $wpdb->prefix . "adrotate_blocks",			// Since 3.0
		'adrotate_linkmeta' 		=> $wpdb->prefix . "adrotate_linkmeta",			// Since 3.0
		'adrotate_stats_tracker' 	=> $wpdb->prefix . "adrotate_stats_tracker",	// Since 3.5
	);

	return $tables;
}
?>