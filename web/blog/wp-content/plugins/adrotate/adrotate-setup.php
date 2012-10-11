<?php
/*  
Copyright 2010-2012 Arnan de Gans - AJdG Solutions (email : info@ajdg.net)
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
			wp_die('You do not have appropriate access to activate this plugin! Contact your administrator!<br /><a href="'. get_option('siteurl').'/wp-admin/plugins.php">Back to plugins</a>.'); 
			return; 
		} else {
			// Set default settings and values
			add_option('adrotate_db_timer', current_time('timestamp'));
			add_option('adrotate_debug', array('general' => false, 'dashboard' => false, 'userroles' => false, 'userstats' => false, 'stats' => false, 'track' => false, 'upgrade' => false));
			add_option("adrotate_version", array('current' => ADROTATE_VERSION, 'previous' => 0));
			add_option("adrotate_db_version", array('current' => ADROTATE_DB_VERSION, 'previous' => 0));

			// Install and Upgrade things where required
			adrotate_check_upgrade();
					
			// Set up some schedules
			if (!wp_next_scheduled('adrotate_ad_notification')) // Ad notifications
				wp_schedule_event(date('U'), '1day', 'adrotate_ad_notification');

			if (!wp_next_scheduled('adrotate_clean_trackerdata')) // Periodically clean trackerdata
				wp_schedule_event(date('U'), '3hour', 'adrotate_clean_trackerdata');

			if (!wp_next_scheduled('adrotate_evaluate_ads')) // Periodically evaluate ads
				wp_schedule_event(date('U'), 'hourly', 'adrotate_evaluate_ads');
		
			// Set the capabilities for the administrator
			$role = get_role('administrator');		
			$role->add_cap("adrotate_advertiser");
			$role->add_cap("adrotate_global_report");
			$role->add_cap("adrotate_ad_manage");
			$role->add_cap("adrotate_ad_delete");
			$role->add_cap("adrotate_group_manage");
			$role->add_cap("adrotate_group_delete");
			$role->add_cap("adrotate_block_manage");
			$role->add_cap("adrotate_block_delete");
			//$role->add_cap("adrotate_moderate");
			//$role->add_cap("adrotate_moderate_approve");
		
			// Switch additional roles on or off
			if($adrotate_roles = 1) {
				// Remove old named roles
				adrotate_remove_roles();
				// Set or reset the roles
				adrotate_add_roles();
			} else {
				update_option('adrotate_roles', '0');
			}

			adrotate_check_config();
	
			// Attempt to make the wp-content/banners/ folder
			if(!is_dir(ABSPATH.'/wp-content/banners')) {
				mkdir(ABSPATH.'/wp-content/banners', 0755);
			}
			if(!is_dir(ABSPATH.'/wp-content/reports')) {
				mkdir(ABSPATH.'/wp-content/reports', 0755);
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

	$num = 0;
	$add_tables[$num]['table_sql'] = "CREATE TABLE IF NOT EXISTS ".$tables['adrotate']." (
									  	`id` mediumint(8) unsigned NOT NULL auto_increment,
									  	`title` longtext NOT NULL,
									  	`bannercode` longtext NOT NULL,
									  	`thetime` int(15) NOT NULL default '0',
										`updated` int(15) NOT NULL,
									  	`author` varchar(60) NOT NULL default '',
									  	`imagetype` varchar(9) NOT NULL,
									  	`image` varchar(255) NOT NULL,
									  	`link` longtext NOT NULL,
									  	`tracker` varchar(5) NOT NULL default 'N',
									  	`timeframe` varchar(6) NOT NULL default '',
									  	`timeframelength` int(15) NOT NULL default '0',
									  	`timeframeclicks` int(15) NOT NULL default '0',
									  	`timeframeimpressions` int(15) NOT NULL default '0',
									  	`type` varchar(10) NOT NULL default '0',
									  	`weight` int(3) NOT NULL default '6',
										`sortorder` int(5) NOT NULL default '0',
										PRIMARY KEY  (`id`)
									) ".$charset_collate.";";  

	$num++;
	$add_tables[$num]['table_sql'] = "CREATE TABLE IF NOT EXISTS `".$tables['adrotate_groups']."` (
										`id` mediumint(8) unsigned NOT NULL auto_increment,
										`name` varchar(255) NOT NULL default 'group',
										`fallback` varchar(5) NOT NULL default '0',
										`sortorder` int(5) NOT NULL default '0',
										`cat` longtext NOT NULL,
										`cat_loc` tinyint(1) NOT NULL default '0',
										`page` longtext NOT NULL,
										`page_loc` tinyint(1) NOT NULL default '0',
										PRIMARY KEY  (`id`)
									) ".$charset_collate.";";
	
	$num++;
	$add_tables[$num]['table_sql'] = "CREATE TABLE IF NOT EXISTS `".$tables['adrotate_tracker']."` (
										`id` mediumint(8) unsigned NOT NULL auto_increment,
										`ipaddress` varchar(255) NOT NULL default '0',
										`timer` int(15) NOT NULL default '0',
										`bannerid` int(15) NOT NULL default '0',
										`stat` char(1) NOT NULL default 'c',
										`useragent` mediumtext NOT NULL,
										PRIMARY KEY  (`id`),
										INDEX `ipaddress` (`ipaddress`)
									) ".$charset_collate.";";
	
	$num++;
	$add_tables[$num]['table_sql'] = "CREATE TABLE IF NOT EXISTS `".$tables['adrotate_blocks']."` (
									  	`id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
									  	`name` varchar(255) NOT NULL DEFAULT 'Block',
										`rows` int(3) NOT NULL DEFAULT '2',
										`columns` int(3) NOT NULL DEFAULT '2',
										`gridfloat` varchar(6) NOT NULL DEFAULT 'none',
										`gridpadding` int(2) NOT NULL DEFAULT '0',
										`gridborder` varchar(20) NOT NULL DEFAULT '0',
										`adwidth` varchar(6) NOT NULL DEFAULT '125',
										`adheight` varchar(6) NOT NULL DEFAULT '125',
										`admargin` int(2) NOT NULL DEFAULT '1',
										`adpadding` int(2) NOT NULL DEFAULT '0',
										`adborder` varchar(20) NOT NULL DEFAULT '0',
										`wrapper_before` longtext NOT NULL,
										`wrapper_after` longtext NOT NULL,
										`sortorder` int(5) NOT NULL DEFAULT '0',
										PRIMARY KEY (`id`)
									) ".$charset_collate.";";
	
	$num++;
	$add_tables[$num]['table_sql'] = "CREATE TABLE IF NOT EXISTS `".$tables['adrotate_linkmeta']."` (
										`id` mediumint(8) unsigned NOT NULL auto_increment,
										`ad` int(5) NOT NULL default '0',
										`group` int(5) NOT NULL default '0',
										`block` int(5) NOT NULL default '0',
										`user` int(5) NOT NULL default '0',
										PRIMARY KEY  (`id`)
									) ".$charset_collate.";";
	
	$num++;
	$add_tables[$num]['table_sql'] = "CREATE TABLE IF NOT EXISTS `".$tables['adrotate_stats_tracker']."` (
										`id` mediumint(8) unsigned NOT NULL auto_increment,
										`ad` int(5) NOT NULL default '0',
										`group` int(5) NOT NULL default '0',
										`block` int(5) NOT NULL default '0',
										`thetime` int(15) NOT NULL default '0',
										`clicks` int(15) NOT NULL default '0',
										`impressions` int(15) NOT NULL default '0',
										PRIMARY KEY  (`id`),
										INDEX `ad` (`ad`)
									) ".$charset_collate.";";
	
	$num++;
	$add_tables[$num]['table_sql'] = "CREATE TABLE IF NOT EXISTS `".$tables['adrotate_schedule']."` (
										`id` mediumint(8) unsigned NOT NULL auto_increment,
										`ad` mediumint(8) NOT NULL default '0',
										`starttime` int(15) NOT NULL default '0',
										`stoptime` int(15) NOT NULL default '0',
										`maxclicks` int(15) NOT NULL default '0',
										`maximpressions` int(15) NOT NULL default '0',
										PRIMARY KEY  (`id`),
										INDEX `ad` (`ad`)
									) ".$charset_collate.";";
	
	foreach($add_tables as $add_table) {
		dbDelta($add_table['table_sql']);
	}
	unset($add_tables, $add_table);
}

/*-------------------------------------------------------------
 Name:      adrotate_database_upgrade

 Purpose:   Upgrades AdRotate where required
 Receive:   -none-
 Return:	-none-
 Since:		3.0.3
-------------------------------------------------------------*/
function adrotate_database_upgrade() {
	global $wpdb;

	$adrotate_db_version = get_option("adrotate_db_version");
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

	$tables = adrotate_list_tables();
	$upgrade = $migrate = array();
	
	// Database 1
	if($adrotate_db_version['current'] < 1) {
		$upgrade[1] = adrotate_add_column($tables['adrotate'], 'link', 'LONGTEXT NOT NULL AFTER `image`');
		$upgrade[2] = adrotate_add_column($tables['adrotate'], 'tracker', 'VARCHAR(5) NOT NULL DEFAULT \'N\' AFTER `link`');
		$upgrade[3] = adrotate_add_column($tables['adrotate'], 'type', 'VARCHAR(10) NOT NULL DEFAULT \'empty\' AFTER `tracker`');
		$upgrade[4] = adrotate_add_column($tables['adrotate_groups'], 'fallback', 'VARCHAR(5) NOT NULL DEFAULT \'0\' AFTER `name`');
		$upgrade[5] = adrotate_add_column($tables['adrotate_tracker'], 'bannerid', 'INT(15) NOT NULL DEFAULT \'0\' AFTER `timer`');

		// Migrate group data to accomodate version 3.0 and up from earlier setups
		$banners = $wpdb->get_results("SELECT `id`, `group` FROM ".$tables['adrotate']." ORDER BY `id` ASC;");
		if($banners) {
			foreach($banners as $banner) {
				$migrate[1][] = $wpdb->insert($tables['adrotate_linkmeta'], array('ad' => $banner->id, 'group' => $banner->group, 'block' => 0, 'user' => 0));
			}
			unset($banners);
		}
	
		$migrate[1][] = $wpdb->query("UPDATE `".$tables['adrotate']."` SET `type` = 'manual' WHERE `magic` = '0' AND `title` != '';");
		$migrate[1][] = $wpdb->query("UPDATE `".$tables['adrotate']."` SET `type` = 'manual' WHERE `magic` = '1' AND `title` != '';");
		$migrate[1][] = $wpdb->query("UPDATE `".$tables['adrotate']."` SET `type` = 'empty' WHERE `magic` = '2';");

		$upgrade[6] = adrotate_change_column($tables['adrotate_tracker'], 'ipaddress', 'ipaddress', 'varchar(255) NOT NULL DEFAULT \'0\'');
		$upgrade[7] = adrotate_remove_column($tables['adrotate'], 'magic');
		$upgrade[8] = adrotate_remove_column($tables['adrotate'], 'group');
	}

	// Database 3
	if($adrotate_db_version['current'] < 3) {
		$upgrade[9] = adrotate_add_column($tables['adrotate'], 'weight', 'INT(3) NOT NULL DEFAULT \'6\' AFTER `type`');
	}

	// Database 5
	if($adrotate_db_version['current'] < 5) {
		$today = mktime(0, 0, 0, gmdate("m"), gmdate("d"), gmdate("Y"));
		// Migrate current statistics to accomodate version 3.5s new stats system
		$ads = $wpdb->get_results("SELECT `id`, `clicks`, `shown` FROM ".$tables['adrotate']." ORDER BY `id` ASC;");
		if($ads) {
			foreach($ads as $ad) {
				$migrate[5][] = $wpdb->insert($tables['adrotate_stats_tracker'], array('ad' => $ad->id, 'thetime' => $today, 'clicks' => $ad->clicks, 'impressions' => $ad->shown));
			}
			unset($ads);
		}

		$upgrade[10] = adrotate_remove_column($tables['adrotate'], 'clicks');
		$upgrade[11] = adrotate_remove_column($tables['adrotate'], 'shown');
	}

	// Database 6
	if($adrotate_db_version['current'] < 6) {
		$migrate[6] = $wpdb->query("DROP TABLE `".$tables['adrotate_stats_cache']."`;");
	}
				
	// Database 8
	if($adrotate_db_version['current'] < 8) {
		// Convert image data to accomodate version 3.6 and up from earlier setups
		$images = $wpdb->get_results("SELECT `id`, `image` FROM ".$tables['adrotate']." ORDER BY `id` ASC;");
		if($images) {
			foreach($images as $image) {
				if(strlen($image->image) > 0) {
					if(preg_match("/wp-content\/banners\//i", $image->image)) {
						$migrate[8][] = $wpdb->query("UPDATE `".$tables['adrotate']."` SET `image` = 'dropdown|$image->image' WHERE `id` = '$image->id';");
					} else {
						$migrate[8][] = $wpdb->query("UPDATE `".$tables['adrotate']."` SET `image` = 'field|$image->image' WHERE `id` = '$image->id';");
					}
				}
			}
		}
	}

	// Database 10
	if($adrotate_db_version['current'] < 10) {
		$upgrade[12] = adrotate_add_column($tables['adrotate_tracker'], 'stat', 'CHAR(1) NOT NULL DEFAULT \'c\' AFTER `bannerid`');
		$migrate[10] = $wpdb->query("UPDATE `".$tables['adrotate_tracker']."` SET `stat` = 'c' WHERE `stat` = '';");
	}
				
	// Database 11
	if($adrotate_db_version['current'] < 11) {
		$upgrade[13] = adrotate_add_column($tables['adrotate'], 'sortorder', 'int(5) NOT NULL DEFAULT \'0\' AFTER `weight`');
		$upgrade[14] = adrotate_add_column($tables['adrotate_groups'], 'sortorder', 'int(5) NOT NULL DEFAULT \'0\' AFTER `fallback`');
		$upgrade[15] = adrotate_add_column($tables['adrotate_blocks'], 'sortorder', 'int(5) NOT NULL DEFAULT \'0\' AFTER `wrapper_after`');
		$upgrade[16] = adrotate_add_column($tables['adrotate'], 'imagetype', 'varchar(10) NOT NULL AFTER `author`');

		$images = $wpdb->get_results("SELECT `id`, `image` FROM ".$tables['adrotate']." ORDER BY `id` ASC;");
		if($images) {
			foreach($images as $image) {
				if(strlen($image->image) > 0) {
					if(preg_match("/dropdown|/i", $image->image) OR preg_match("/field|/i", $image->image)) {
						$buffer = explode("|", $image->image, 3);
						$migrate[11][] = $wpdb->query("UPDATE `".$tables['adrotate']."` SET `imagetype` = '".$buffer[0]."', `image` = '".$buffer[1]."' WHERE `id` = '$image->id';");
					}
				}
			}
		}
	}

	// Database 12
	if($adrotate_db_version['current'] < 12) {
		$upgrade[17] = adrotate_add_column($tables['adrotate_tracker'], 'useragent', 'mediumtext NOT NULL AFTER `stat`');
	}
		
	// Database 13
	if($adrotate_db_version['current'] < 13) {
		// Upgrade tables with Indexes for faster processing
		$migrate[13][] = $wpdb->query("CREATE INDEX `ad` ON `".$tables['adrotate_stats_tracker']."` (`ad`);");
		$migrate[13][] = $wpdb->query("CREATE INDEX `ipaddress` ON `".$tables['adrotate_tracker']."` (`ipaddress`);");

		// Migrate existing start / end times to new table
		$times = $wpdb->get_results("SELECT `id`, `startshow`, `endshow` FROM ".$tables['adrotate']." ORDER BY `id` ASC;");
		if($times) {
			foreach($times as $time) {
				$migrate[13][] = $wpdb->insert($tables['adrotate_schedule'], array('ad' => $time->id, 'starttime' => $time->startshow, 'stoptime' => $time->endshow));
			}
		}

		// Migrate existing statuses to new field
		$states = $wpdb->get_results("SELECT `id`, `active`, `type` FROM ".$tables['adrotate']." ORDER BY `id` ASC;");
		if($states) {
			foreach($states as $state) {
				if($state->active == 'yes' AND $state->type == 'manual') {
					$migrate[13][] = $wpdb->query("UPDATE `".$tables['adrotate']."` SET `type` = 'active' WHERE `active` = 'yes' AND `id` = '".$state->id."';");
				}
				if($state->active == 'no' AND $state->type == 'manual') {
					$migrate[13][] = $wpdb->query("UPDATE `".$tables['adrotate']."` SET `type` = 'disabled' WHERE `active` = 'no' AND `id` = '".$state->id."';");
				}
			}
		}

		$upgrade[18] = adrotate_remove_column($tables['adrotate'], 'startshow');
		$upgrade[19] = adrotate_remove_column($tables['adrotate'], 'endshow');
		$upgrade[20] = adrotate_remove_column($tables['adrotate'], 'active');
	}
				
	// Database 14
	if($adrotate_db_version['current'] < 14) {
		$upgrade[21] = adrotate_add_column($tables['adrotate_schedule'], 'maxclicks', 'int(15) NOT NULL DEFAULT \'0\' AFTER `stoptime`');
		$upgrade[22] = adrotate_add_column($tables['adrotate_schedule'], 'maximpressions', 'int(15) NOT NULL DEFAULT \'0\' AFTER `maxclicks`');
		$upgrade[23] = adrotate_remove_column($tables['adrotate'], 'maxclicks');
		$upgrade[24] = adrotate_remove_column($tables['adrotate'], 'maxshown');
	}

	// Database 15
	if($adrotate_db_version['current'] < 15) {
		$upgrade[25] = adrotate_add_column($tables['adrotate'], 'timeframe', 'varchar(6) NOT NULL DEFAULT \'\' AFTER `tracker`');
		$upgrade[26] = adrotate_add_column($tables['adrotate'], 'timeframelength', 'int(15) NOT NULL DEFAULT \'0\' AFTER `timeframe`');
		$upgrade[27] = adrotate_add_column($tables['adrotate'], 'timeframeclicks', 'int(15) NOT NULL DEFAULT \'0\' AFTER `timeframelength`');
		$upgrade[28] = adrotate_add_column($tables['adrotate'], 'timeframeimpressions', 'int(15) NOT NULL DEFAULT \'0\' AFTER `timeframeclicks`');
	}

	// Database 16
	if($adrotate_db_version['current'] < 16) {
		$engine = $wpdb->get_var("SELECT ENGINE FROM `information_schema`.`TABLES` WHERE `TABLE_SCHEMA` = '".DB_NAME."' AND `TABLE_NAME` = '".$wpdb->prefix."posts';");
		$engine2 = $wpdb->get_var("SELECT ENGINE FROM `information_schema`.`TABLES` WHERE `TABLE_SCHEMA` = '".DB_NAME."' AND `TABLE_NAME` = '".$wpdb->prefix."adrotate';");
		if(strtolower($engine) == 'innodb' AND strtolower($engine2) != 'innodb') {
			$migrate[16][] = $wpdb->query("ALTER TABLE `".$tables['adrotate']."` ENGINE=INNODB;");
			$migrate[16][] = $wpdb->query("ALTER TABLE `".$tables['adrotate_groups']."` ENGINE=INNODB;");
			$migrate[16][] = $wpdb->query("ALTER TABLE `".$tables['adrotate_tracker']."` ENGINE=INNODB;");
			$migrate[16][] = $wpdb->query("ALTER TABLE `".$tables['adrotate_blocks']."` ENGINE=INNODB;");
			$migrate[16][] = $wpdb->query("ALTER TABLE `".$tables['adrotate_linkmeta']."` ENGINE=INNODB;");
			$migrate[16][] = $wpdb->query("ALTER TABLE `".$tables['adrotate_stats_tracker']."` ENGINE=INNODB;");
			$migrate[16][] = $wpdb->query("ALTER TABLE `".$tables['adrotate_schedule']."` ENGINE=INNODB;");
		}
	}

	// Database 17
	if($adrotate_db_version['current'] < 17) {
		$upgrade[29] = adrotate_add_column($tables['adrotate_groups'], 'cat', 'longtext NOT NULL AFTER `sortorder`');
		$upgrade[30] = adrotate_add_column($tables['adrotate_groups'], 'cat_loc', 'tinyint(1) NOT NULL DEFAULT \'0\' AFTER `cat`');
		$upgrade[31] = adrotate_add_column($tables['adrotate_groups'], 'page', 'longtext NOT NULL AFTER `cat_loc`');
		$upgrade[32] = adrotate_add_column($tables['adrotate_groups'], 'page_loc', 'tinyint(1) NOT NULL DEFAULT \'0\' AFTER `page`');
	}

	// Database 18
	if($adrotate_db_version['current'] < 18) {
		$upgrade[33] = adrotate_change_column($tables['adrotate_blocks'], 'adcount', 'rows', 'INT(3)  NOT NULL  DEFAULT \'2\'');
		$upgrade[34] = adrotate_change_column($tables['adrotate_blocks'], 'columns', 'columns', 'INT(3)  NOT NULL  DEFAULT \'2\'');
		$upgrade[35] = adrotate_add_column($tables['adrotate_blocks'], 'gridpadding', 'int(2) NOT NULL DEFAULT \'0\' AFTER `columns`');
		$upgrade[36] = adrotate_add_column($tables['adrotate_blocks'], 'gridborder', 'varchar(20) NOT NULL DEFAULT \'0\' AFTER `gridpadding`');
		$upgrade[37] = adrotate_add_column($tables['adrotate_blocks'], 'adwidth', 'int(4) NOT NULL DEFAULT \'125\' AFTER `gridborder`');
		$upgrade[38] = adrotate_add_column($tables['adrotate_blocks'], 'adheight', 'int(4) NOT NULL DEFAULT \'125\' AFTER `adwidth`');
		$upgrade[39] = adrotate_add_column($tables['adrotate_blocks'], 'admargin', 'int(4) NOT NULL DEFAULT \'1\' AFTER `adheight`');
		$upgrade[40] = adrotate_add_column($tables['adrotate_blocks'], 'adpadding', 'int(4) NOT NULL DEFAULT \'0\' AFTER `admargin`');
		$upgrade[41] = adrotate_add_column($tables['adrotate_blocks'], 'adborder', 'varchar(20) NOT NULL DEFAULT \'0\' AFTER `adpadding`');
	}

	// Database 19
	if($adrotate_db_version['current'] < 19) {
		$upgrade[42] = adrotate_change_column($tables['adrotate_blocks'], 'adwidth', 'adwidth', 'varchar(6) NOT NULL DEFAULT \'125\'');
		$upgrade[43] = adrotate_change_column($tables['adrotate_blocks'], 'adheight', 'adheight', 'varchar(6) NOT NULL DEFAULT \'125\'');
		$upgrade[44] = adrotate_add_column($tables['adrotate_blocks'], 'gridfloat', 'varchar(7) NOT NULL DEFAULT \'none\' AFTER `columns`');
	}

	// Database 21
	if($adrotate_db_version['current'] < 21) {
		$upgrade[45] = adrotate_remove_column($tables['adrotate'], 'targetclicks');
		$upgrade[46] = adrotate_remove_column($tables['adrotate'], 'targetimpressions');
	}

	// Save upgrade state
	update_option("adrotate_upgrade_log", array('database' => $upgrade, 'data' => $migrate));
	update_option("adrotate_db_version", array('current' => ADROTATE_DB_VERSION, 'previous' => $adrotate_db_version['current']));
}

/*-------------------------------------------------------------
 Name:      adrotate_core_upgrade

 Purpose:   Upgrades AdRotate where required
 Receive:   -none-
 Return:	-none-
 Since:		3.5
-------------------------------------------------------------*/
function adrotate_core_upgrade() {
	global $wp_roles;

	$adrotate_version = get_option("adrotate_version");

	if (version_compare(PHP_VERSION, '5.2.0', '<') == -1) { 
		deactivate_plugins(plugin_basename('adrotate.php'));
		wp_die('AdRotate 3.6 and up requires PHP 5.2 or higher.<br />You likely have PHP 4, which has been discontinued since december 31, 2007. Consider upgrading your server!<br /><a href="'. get_option('siteurl').'/wp-admin/plugins.php">Back to plugins</a>.'); 
		return; 
	} else {
		if($adrotate_version['current'] < 323) {
			delete_option('adrotate_notification_timer');
		}
		
		if($adrotate_version['current'] < 340) {
			add_option('adrotate_db_timer', date('U'));
		}

		if($adrotate_version['current'] < 350) {
			update_option('adrotate_debug', array('general' => false, 'dashboard' => false, 'userroles' => false, 'userstats' => false, 'stats' => false));
		}

		if($adrotate_version['current'] < 351) {
			wp_clear_scheduled_hook('adrotate_prepare_cache_statistics');
			delete_option('adrotate_stats');
		}

		if($adrotate_version['current'] < 352) {
			adrotate_remove_capability("adrotate_userstatistics"); // OBSOLETE IN 3.5
			adrotate_remove_capability("adrotate_globalstatistics"); // OBSOLETE IN 3.5
			$role = get_role('administrator');		
			$role->add_cap("adrotate_advertiser_report"); // NEW IN 3.5
			$role->add_cap("adrotate_global_report"); // NEW IN 3.5
		}

		if($adrotate_version['current'] < 353) {
			if(!is_dir(ABSPATH.'/wp-content/plugins/adrotate/language')) {
				mkdir(ABSPATH.'/wp-content/plugins/adrotate/language', 0755);
			}
		}

		if($adrotate_version['current'] < 354) {
			$crawlers = array("Teoma", "alexa", "froogle", "Gigabot", "inktomi","looksmart", "URL_Spider_SQL", "Firefly", "NationalDirectory","Ask Jeeves", "TECNOSEEK", "InfoSeek", "WebFindBot", "girafabot","www.galaxy.com", "Googlebot", "Scooter", "Slurp","msnbot", "appie", "FAST", "WebBug", "Spade", "ZyBorg", "rabaz","Baiduspider", "Feedfetcher-Google", "TechnoratiSnoop", "Rankivabot","Mediapartners-Google", "Sogou web spider", "WebAlta Crawler","bot", "crawler", "yahoo", "msn", "ask", "ia_archiver");
			update_option('adrotate_crawlers', $crawlers);
		}

		if($adrotate_version['current'] < 355) {
			if(!is_dir(ABSPATH.'/wp-content/reports')) {
				mkdir(ABSPATH.'/wp-content/reports', 0755);
			}
		}

		if($adrotate_version['current'] < 356) {
			adrotate_remove_capability("adrotate_advertiser_report");
			$role = get_role('administrator');		
			$role->add_cap("adrotate_advertiser");
		}
		
		if($adrotate_version['current'] < 357) {
			$role = get_role('administrator');		
			$role->add_cap("adrotate_moderate");
			$role->add_cap("adrotate_moderate_approve");
		}
		
		update_option("adrotate_version", array('current' => ADROTATE_VERSION, 'previous' => $adrotate_version['current']));
	}
}

/*-------------------------------------------------------------
 Name:      adrotate_check_upgrade

 Purpose:   Checks if the plugin needs to upgrade stuff upon activation
 Receive:   -none-
 Return:	-none-
 Since:		3.7.3
-------------------------------------------------------------*/
function adrotate_check_upgrade() {
	$adrotate_version = get_option("adrotate_version");
	$adrotate_db_version = get_option("adrotate_db_version");

	adrotate_database_install();
	if((is_array($adrotate_db_version) AND $adrotate_db_version['current'] < ADROTATE_DB_VERSION) 
		OR (!is_array($adrotate_db_version) AND $adrotate_db_version < ADROTATE_DB_VERSION) 
		OR $adrotate_db_version == '') adrotate_database_upgrade();

	if((is_array($adrotate_version) AND $adrotate_version['current'] < ADROTATE_VERSION ) 
		OR (!is_array($adrotate_version) AND $adrotate_version < ADROTATE_VERSION) 
		OR $adrotate_version == '') adrotate_core_upgrade();
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
	adrotate_remove_capability("adrotate_moderate");
	adrotate_remove_capability("adrotate_moderate_approve");

	// Clear out wp_cron
	wp_clear_scheduled_hook('adrotate_ad_notification');
	wp_clear_scheduled_hook('adrotate_cache_statistics'); // OBSOLETE IN 3.6 - REMOVE IN 4.0
	wp_clear_scheduled_hook('adrotate_clean_trackerdata');
	wp_clear_scheduled_hook('adrotate_evaluate_ads');
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

	if(defined('WP_UNINSTALL_PLUGIN')) {
	// Drop MySQL Tables
		$wpdb->query("DROP TABLE `".$wpdb->prefix."adrotate`");
		$wpdb->query("DROP TABLE `".$wpdb->prefix."adrotate_groups`");
		$wpdb->query("DROP TABLE `".$wpdb->prefix."adrotate_tracker`");
		$wpdb->query("DROP TABLE `".$wpdb->prefix."adrotate_blocks`");
		$wpdb->query("DROP TABLE `".$wpdb->prefix."adrotate_linkmeta`");
		$wpdb->query("DROP TABLE `".$wpdb->prefix."adrotate_stats_tracker`");
		$wpdb->query("DROP TABLE `".$wpdb->prefix."adrotate_schedule`");
	
		// Delete Options	
		delete_option('adrotate_config');				// Since 0.1
		delete_option('adrotate_notification_timer'); 	// Since 3.0 - Obsolete in 3.2.3
		delete_option('adrotate_crawlers'); 			// Since 3.0
		delete_option('adrotate_stats');				// Since 3.0 - Obsolete in 3.5
		delete_option('adrotate_roles');				// Since 3.0
		delete_option('adrotate_version');				// Since 3.2.3
		delete_option('adrotate_db_version');			// Since 3.0.3
		delete_option('adrotate_debug');				// Since 3.2
		delete_option('adrotate_advert_status');		// Since 3.7
	
		// Clear out userroles
		remove_role('adrotate_advertiser');
	
		// Clear up capabilities from ALL users
		adrotate_remove_capability("adrotate_advertiser");
		adrotate_remove_capability("adrotate_global_report");
		adrotate_remove_capability("adrotate_ad_manage");
		adrotate_remove_capability("adrotate_ad_delete");
		adrotate_remove_capability("adrotate_group_manage");
		adrotate_remove_capability("adrotate_group_delete");
		adrotate_remove_capability("adrotate_block_manage");
		adrotate_remove_capability("adrotate_block_delete");
		adrotate_remove_capability("adrotate_moderate");
		adrotate_remove_capability("adrotate_moderate_approve");
		adrotate_remove_capability("adrotate_moderate_reply");
			
		// Delete cron schedules
		wp_clear_scheduled_hook('adrotate_ad_notification');
		wp_clear_scheduled_hook('adrotate_prepare_cache_statistics'); // OBSOLETE IN 3.6 - REMOVE IN 4.0
		wp_clear_scheduled_hook('adrotate_clean_trackerdata');
		wp_clear_scheduled_hook('adrotate_evaluate_ads');
	}
}

/*-------------------------------------------------------------
 Name:      adrotate_check_database

 Purpose:   Check availability of tables in the Database
 Receive:   $tables
 Return:    boolean
 Since:		3.7.3b3
-------------------------------------------------------------*/
function adrotate_check_database($tables) {
	global $wpdb;
	
	if(is_array($tables)) {

		$expected = count($tables);
		$tocheck = '';
		
		foreach($tables as &$value) {
		    $tocheck = $tocheck." OR `table_name` = '".$value."'";
		}
		unset($tables, $value);

		$tocheck = substr($tocheck, 4);
		$tablecount = $wpdb->get_var("SELECT COUNT(*) FROM `information_schema`.`tables` WHERE `table_schema` = '".DB_NAME."' AND ($tocheck);");
		if($tablecount == $expected) {
			return true;
		} else {
			return false;
		}	
	} else {
		return false;
	}
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

	$ads = $wpdb->get_results("SELECT `id` FROM `".$wpdb->prefix."adrotate` ORDER BY `id`;");
	$metas = $wpdb->get_results("SELECT `id`, `ad` FROM `".$wpdb->prefix."adrotate_linkmeta` WHERE `ad` != '0' ORDER BY `id`;");
	$schedules = $wpdb->get_results("SELECT `id`, `ad` FROM `".$wpdb->prefix."adrotate_schedule` ORDER BY `id`;");
	
	$adverts = $linkmeta = $timeframes = array();
	foreach($ads as $ad) {
		$adverts[$ad->id] = $ad->id;
	}
	foreach($metas as $meta) {
		$linkmeta[$meta->id] = $meta->ad;
	}
	foreach($schedules as $schedule) {
		$timeframes[$schedule->id] = $schedule->ad;
	}

	$result = array_diff($linkmeta, $adverts);
	$result2 = array_diff($timeframes, $adverts);
	foreach($result as $key => $value) {
		$wpdb->query("DELETE FROM `".$wpdb->prefix."adrotate_linkmeta` WHERE `id` = $key;");
	}
	unset($value);
	foreach($result2 as $key => $value) {
		$wpdb->query("DELETE FROM `".$wpdb->prefix."adrotate_schedule` WHERE `id` = $key;");
	}
	unset($value);

	unset($ads, $metas, $schedules, $adverts, $linkmeta, $timeframes);
	
	adrotate_clean_trackerdata();
	
	adrotate_return('db_cleaned');
}

/*-------------------------------------------------------------
 Name:      adrotate_add_column

 Purpose:   Check if the column exists in the table and add it
 Receive:   $table_name, $column_name, $attributes
 Return:	Boolean
 Since:		3.0.3
-------------------------------------------------------------*/
function adrotate_add_column($table_name, $column_name, $attributes) {
	global $wpdb;
	
	foreach ($wpdb->get_col("SHOW COLUMNS FROM `$table_name`;") as $column ) {
		if ($column == $column_name) return true;
	}
	
	$state = $wpdb->query("ALTER TABLE `$table_name` ADD `$column_name` " . $attributes.";");
	
	foreach ($wpdb->get_col("SHOW COLUMNS FROM `$table_name`;") as $column ) {
		if ($column == $column_name) return $state;
	}
	
	return false;
}

/*-------------------------------------------------------------
 Name:      adrotate_change_column

 Purpose:   Check if the column exists in the table and change it
 Receive:   $table_name, $column_name, $new_column_name, $attributes
 Return:	Boolean
 Since:		3.0.3
-------------------------------------------------------------*/
function adrotate_change_column($table_name, $column_name, $new_column_name, $attributes) {
	global $wpdb;
	
	foreach ($wpdb->get_col("SHOW COLUMNS FROM `$table_name`;") as $column ) {
		if ($column == $column_name) { 
			$state = $wpdb->query("ALTER TABLE `$table_name` CHANGE `$column_name` `$new_column_name` $attributes;");
			return $state;
		}
	}
	
	return false;
}

/*-------------------------------------------------------------
 Name:      adrotate_remove_column

 Purpose:   Check if the column exists in the table and remove it
 Receive:   $table_name, $column_name
 Return:	Boolean
 Since:		3.8
-------------------------------------------------------------*/
function adrotate_remove_column($table_name, $column_name) {
	global $wpdb;
	
	foreach ($wpdb->get_col("SHOW COLUMNS FROM `$table_name`;") as $column ) {
		if ($column == $column_name) {
			$state = $wpdb->query("ALTER TABLE `$table_name` DROP `$column_name`;");
			return $state;
		}
	}

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
		'adrotate_schedule'		 	=> $wpdb->prefix . "adrotate_schedule",			// Since 3.7
	);

	return $tables;
}
?>