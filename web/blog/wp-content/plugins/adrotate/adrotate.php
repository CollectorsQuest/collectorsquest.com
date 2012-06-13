<?php
/*
Plugin Name: AdRotate
Plugin URI: http://www.adrotateplugin.com
Description: The very best and most convenient way to publish your ads.
Author: Arnan de Gans
Version: 3.6.10.1
Author URI: http://meandmymac.net/
License: GPL2
*/

/*  
Copyright 2010-2011 Arnan de Gans  (email : adegans@meandmymac.net)
*/

/*--- AdRotate values ---------------------------------------*/
define("ADROTATE_VERSION", 354);
define("ADROTATE_DB_VERSION", 12);
//setlocale(LC_ALL, get_locale().'.'.DB_CHARSET);
/*-----------------------------------------------------------*/

/*--- Load Files --------------------------------------------*/
include_once(WP_CONTENT_DIR.'/plugins/adrotate/adrotate-setup.php');
include_once(WP_CONTENT_DIR.'/plugins/adrotate/adrotate-manage.php');
include_once(WP_CONTENT_DIR.'/plugins/adrotate/adrotate-functions.php');
include_once(WP_CONTENT_DIR.'/plugins/adrotate/adrotate-output.php');
include_once(WP_CONTENT_DIR.'/plugins/adrotate/adrotate-widget.php');
include_once(WP_CONTENT_DIR.'/plugins/adrotate/adrotate-network.php');
// wp-content/plugins/adrotate/adrotate-out.php
/*-----------------------------------------------------------*/

/*--- Check and Load config ---------------------------------*/
load_plugin_textdomain('adrotate', false, basename( dirname( __FILE__ ) ) . '/language' );
adrotate_check_config();
$adrotate_config 				= get_option('adrotate_config');
$adrotate_crawlers 				= get_option('adrotate_crawlers');
$adrotate_roles 				= get_option('adrotate_roles');
$adrotate_version				= get_option("adrotate_version");
$adrotate_db_version			= get_option("adrotate_db_version");
$adrotate_debug					= get_option("adrotate_debug");
/*-----------------------------------------------------------*/

/*--- Core --------------------------------------------------*/
register_activation_hook(__FILE__, 'adrotate_activate');
register_deactivation_hook(__FILE__, 'adrotate_deactivate');
register_uninstall_hook(__FILE__, 'adrotate_uninstall');
if($adrotate_version < ADROTATE_VERSION) adrotate_core_upgrade();
if($adrotate_db_version < ADROTATE_DB_VERSION) adrotate_database_upgrade();
add_action('adrotate_ad_notification', 'adrotate_mail_notifications');
add_action('admin_print_scripts', 'adrotate_filemanager_admin_scripts');
add_action('admin_print_styles', 'adrotate_filemanager_admin_styles');
add_filter('cron_schedules', 'adrotate_reccurences');
adrotate_clean_trackerdata();
/*-----------------------------------------------------------*/

/*--- Front end ---------------------------------------------*/
add_shortcode('adrotate', 'adrotate_shortcode');
//add_action('wp_enqueue_scripts', 'adrotate_head');
add_action('widgets_init', create_function('', 'return register_widget("adrotate_widgets");'));
add_action('wp_meta', 'adrotate_meta');
/*-----------------------------------------------------------*/

/*--- Dashboard ---------------------------------------------*/
add_action('admin_menu', 'adrotate_dashboard');
add_action('admin_notices','adrotate_notifications_dashboard');
add_action('wp_dashboard_setup', 'adrotate_dashboard_widget');
/*-----------------------------------------------------------*/

/*--- Internal redirects ------------------------------------*/
if(isset($_POST['adrotate_ad_submit'])) 				add_action('init', 'adrotate_insert_input');
if(isset($_POST['adrotate_group_submit'])) 				add_action('init', 'adrotate_insert_group');
if(isset($_POST['adrotate_block_submit'])) 				add_action('init', 'adrotate_insert_block');
if(isset($_POST['adrotate_action_submit'])) 			add_action('init', 'adrotate_request_action');
if(isset($_POST['adrotate_disabled_action_submit']))	add_action('init', 'adrotate_request_action');
if(isset($_POST['adrotate_error_action_submit']))		add_action('init', 'adrotate_request_action');
if(isset($_POST['adrotate_options_submit'])) 			add_action('init', 'adrotate_options_submit');
if(isset($_POST['adrotate_request_submit'])) 			add_action('init', 'adrotate_mail_message');
if(isset($_POST['adrotate_notification_test_submit'])) 	add_action('init', 'adrotate_mail_test');
if(isset($_POST['adrotate_advertiser_test_submit'])) 	add_action('init', 'adrotate_mail_test');
if(isset($_POST['adrotate_role_add_submit']))			add_action('init', 'adrotate_prepare_roles');
if(isset($_POST['adrotate_role_remove_submit'])) 		add_action('init', 'adrotate_prepare_roles');
if(isset($_POST['adrotate_db_optimize_submit'])) 		add_action('init', 'adrotate_optimize_database');
if(isset($_POST['adrotate_db_cleanup_submit'])) 		add_action('init', 'adrotate_cleanup_database');
if(isset($_POST['adrotate_evaluate_submit'])) 			add_action('init', 'adrotate_prepare_evaluate_ads');
/*-----------------------------------------------------------*/

/*-------------------------------------------------------------
 Name:      adrotate_dashboard

 Purpose:   Add pages to admin menus
 Receive:   -none-
 Return:    -none-
-------------------------------------------------------------*/
function adrotate_dashboard() {
	global $adrotate_config;

	add_object_page('AdRotate', 'AdRotate', 'adrotate_ad_manage', 'adrotate', 'adrotate_manage');
	add_submenu_page('adrotate', 'AdRotate > '.__('Manage Ads', 'adrotate'), __('Manage Ads', 'adrotate'), 'adrotate_ad_manage', 'adrotate', 'adrotate_manage');
	add_submenu_page('adrotate', 'AdRotate > '.__('Groups', 'adrotate'), __('Manage Groups', 'adrotate'), 'adrotate_group_manage', 'adrotate-groups', 'adrotate_manage_group');
	add_submenu_page('adrotate', 'AdRotate > '.__('Blocks', 'adrotate'), __('Manage Blocks', 'adrotate'), 'adrotate_block_manage', 'adrotate-blocks', 'adrotate_manage_block');
	add_submenu_page('adrotate', 'AdRotate > '.__('Advertiser Reports', 'adrotate'), __('Advertiser Reports', 'adrotate'), 'adrotate_advertiser_report', 'adrotate-advertiser-report', 'adrotate_advertiser_report');
	add_submenu_page('adrotate', 'AdRotate > '.__('Global Reports', 'adrotate'), __('Global Reports', 'adrotate'), 'adrotate_global_report', 'adrotate-global-report', 'adrotate_global_report');
	add_submenu_page('adrotate', 'AdRotate > '.__('Settings', 'adrotate'), __('Settings', 'adrotate'), 'manage_options', 'adrotate-settings', 'adrotate_options');
}

/*-------------------------------------------------------------
 Name:      adrotate_manage

 Purpose:   Admin management page
 Receive:   -none-
 Return:    -none-
-------------------------------------------------------------*/
function adrotate_manage() {
	global $wpdb, $current_user, $userdata, $adrotate_config, $adrotate_debug;

	$message 		= $_GET['message'];
	$view 			= $_GET['view'];
	$ad_edit_id 	= $_GET['ad'];
	$now 			= current_time('timestamp');
	$in2days 		= $now + 172800;
	$in7days 		= $now + 604800;
	
	if(isset($_POST['adrotate_order_submit'])) { 
		$order = $_POST['adrotate_order']; 
	} else { 
		$order = '`sortorder` ASC, `id` ASC'; 
	}
	?>

	<style type="text/css" media="screen">
	.row_urgent {
		background-color:#ffebe8;
		border-color:#c00;
	}
	.row_error {
		background-color:#ffffe0;
		border-color:#e6db55;
	}
	.row_inactive {
		background-color:#ebf3fa;
		border-color:#466f82;
	}
	.stats_large {
		display: block;
		margin-bottom: 10px;
		margin-top: 10px;
		text-align: center;
		font-weight: bold;
	}
	.number_large {
		margin: 20px;
		font-size: 28px;
	}
	</style>

	<div class="wrap">
		<h2><?php _e('Ad Management', 'adrotate'); ?></h2>

		<?php if ($message == 'created') { ?>
			<div id="message" class="updated fade"><p><?php _e('Ad created', 'adrotate'); ?></p></div>
		<?php } else if ($message == 'updated') { ?>
			<div id="message" class="updated fade"><p><?php _e('Ad updated', 'adrotate'); ?></p></div>
		<?php } else if ($message == 'deleted') { ?>
			<div id="message" class="updated fade"><p><?php _e('Ad(s) deleted', 'adrotate'); ?></p></div>
		<?php } else if ($message == 'reset') { ?>
			<div id="message" class="updated fade"><p><?php _e('Ad(s) statistics reset', 'adrotate'); ?></p></div>
		<?php } else if ($message == 'renew') { ?>
			<div id="message" class="updated fade"><p><?php _e('Ad(s) renewed', 'adrotate'); ?></p></div>
		<?php } else if ($message == 'deactivate') { ?>
			<div id="message" class="updated fade"><p><?php _e('Ad(s) deactivated', 'adrotate'); ?></p></div>
		<?php } else if ($message == 'activate') { ?>
			<div id="message" class="updated fade"><p><?php _e('Ad(s) activated', 'adrotate'); ?></p></div>
		<?php } else if ($message == 'field_error') { ?>
			<div id="message" class="updated fade"><p><?php _e('The ad was saved but has an issue which might prevent it from working properly. Review the yellow marked ad.', 'adrotate'); ?></p></div>
		<?php } else if ($message == 'no_access') { ?>
			<div id="message" class="updated fade"><p><?php _e('Action prohibited', 'adrotate'); ?></p></div>
		<?php } ?>

		<?php if($wpdb->get_var("SHOW TABLES LIKE '".$wpdb->prefix."adrotate';") AND $wpdb->get_var("SHOW TABLES LIKE '".$wpdb->prefix."adrotate_groups';") AND $wpdb->get_var("SHOW TABLES LIKE '".$wpdb->prefix."adrotate_linkmeta';")) { ?>
			<div class="tablenav">
				<div class="alignleft actions">
					<a class="row-title" href="<?php echo admin_url('/admin.php?page=adrotate&view=manage');?>"><?php _e('Manage', 'adrotate'); ?></a> | 
					<a class="row-title" href="<?php echo admin_url('/admin.php?page=adrotate&view=addnew');?>"><?php _e('Add New', 'adrotate'); ?></a> 
					<?php if($ad_edit_id) { ?>
					| <a class="row-title" href="<?php echo admin_url('/admin.php?page=adrotate&view=report&ad='.$ad_edit_id);?>"><?php _e('Report', 'adrotate'); ?></a>
					<?php } ?>
				</div>
			</div>

	    	<?php if ($view == "" OR $view == "manage") { ?>
	
			<?php
			$errorbanners = $wpdb->get_results("SELECT * FROM `".$wpdb->prefix."adrotate` WHERE (`type` = 'error' OR `endshow` <= $now OR `endshow` <= $in2days) AND `active` = 'yes' ORDER BY `sortorder` ASC;");
			if ($errorbanners) {
			?>
			<h3><?php _e('Ads that need attention', 'adrotate'); ?></h3>

			<form name="errorbanners" id="post" method="post" action="admin.php?page=adrotate">
			
			<div class="tablenav">
				<div class="alignleft actions">
					<select name="adrotate_error_action" id="cat" class="postform">
				        <option value=""><?php _e('Bulk Actions', 'adrotate'); ?></option>
				        <option value="deactivate"><?php _e('Deactivate', 'adrotate'); ?></option>
				        <option value="delete"><?php _e('Delete', 'adrotate'); ?></option>
				        <option value="reset"><?php _e('Reset stats', 'adrotate'); ?></option>
				        <option value="" disabled><?php _e('-- Renew --', 'adrotate'); ?></option>
				        <option value="renew-31536000"><?php _e('For 1 year', 'adrotate'); ?></option>
				        <option value="renew-5184000"><?php _e('For 180 days', 'adrotate'); ?></option>
				        <option value="renew-2592000"><?php _e('For 30 days', 'adrotate'); ?></option>
				        <option value="renew-604800"><?php _e('For 7 days', 'adrotate'); ?></option>
					</select>
					<input type="submit" id="post-action-submit" name="adrotate_error_action_submit" value="Go" class="button-secondary" />
				</div>

				<br class="clear" />
			</div>

		   	<table class="widefat" style="margin-top: .5em">
	 			<thead>
	  				<tr>
						<th scope="col" class="manage-column column-cb check-column" style=""><input type="checkbox" /></th>
						<th width="2%"><center><?php _e('ID', 'adrotate'); ?></center></th>
						<th width="12%"><?php _e('Show from', 'adrotate'); ?></th>
						<th width="12%"><?php _e('Show until', 'adrotate'); ?></th>
						<th><?php _e('Title', 'adrotate'); ?></th>
					</tr>
	  			</thead>
	  			<tbody>
				<?php foreach($errorbanners as $errbanner) {
					$today = gmmktime(0, 0, 0, gmdate("n"), gmdate("j"), gmdate("Y"));
					
					if($adrotate_debug['dashboard'] == true) {
						echo "<tr><td>&nbsp;</td><td><strong>[DEBUG]</strong></td><td colspan='9'><pre>";
						$memory = (memory_get_usage() / 1024 / 1024);
						echo "Memory usage: " . round($memory, 2) ." MB <br />"; 
						$peakmemory = (memory_get_peak_usage() / 1024 / 1024);
						echo "Peak memory usage: " . round($peakmemory, 2) ." MB</pre><br />"; 
						echo "Ad Specs: <pre>";
						print_r($errbanner); 
						echo "</pre></td></tr>"; 
					}
								
					$groups	= $wpdb->get_results("
						SELECT 
							`".$wpdb->prefix."adrotate_groups`.`name` 
						FROM 
							`".$wpdb->prefix."adrotate_groups`, 
							`".$wpdb->prefix."adrotate_linkmeta` 
						WHERE 
							`".$wpdb->prefix."adrotate_linkmeta`.`ad` = '".$errbanner->id."'
							AND `".$wpdb->prefix."adrotate_linkmeta`.`group` = `".$wpdb->prefix."adrotate_groups`.`id`
							AND `".$wpdb->prefix."adrotate_linkmeta`.`block` = 0
							AND `".$wpdb->prefix."adrotate_linkmeta`.`user` = 0
						;");
					$grouplist = '';
					foreach($groups as $group) {
						$grouplist .= $group->name.", ";
					}
					$grouplist = rtrim($grouplist, ", ");
					
					if($errbanner->type == 'error') {
						$errorclass = ' row_error';
					} else {
						$errorclass = '';
					}

					if($banner->endshow <= $now OR $banner->endshow <= $in2days) {
						$expiredclass = ' row_urgent';
					} else {
						$expiredclass = '';
					}

					if($class != 'alternate') {
						$class = 'alternate';
					} else {
						$class = '';
					}
					?>
				    <tr id='adrotateindex' class='<?php echo $class.$expiredclass.$errorclass; ?>'>
						<th class="check-column"><input type="checkbox" name="errorbannercheck[]" value="<?php echo $errbanner->id; ?>" /></th>
						<td><center><?php echo $errbanner->id;?></center></td>
						<td><?php echo date_i18n("F d, Y", $errbanner->startshow);?></td>
						<td><span style="color: <?php echo adrotate_prepare_color($errbanner->endshow);?>;"><?php echo date_i18n("F d, Y", $errbanner->endshow);?></span></td>
						<td><strong><a class="row-title" href="<?php echo admin_url('/admin.php?page=adrotate&view=edit&ad='.$errbanner->id);?>" title="<?php _e('Edit', 'adrotate'); ?>"><?php echo stripslashes(html_entity_decode($errbanner->title));?></a></strong> - <a href="<?php echo admin_url('/admin.php?page=adrotate&view=report&ad='.$errbanner->id);?>" title="<?php _e('Report', 'adrotate'); ?>"><?php _e('Report', 'adrotate'); ?></a><?php if($groups) echo '<br /><em style="color:#999">'.$grouplist.'</em>'; ?></td>
					</tr>
	 			<?php } ?>
				</tbody>
			</table>
			</form>
		<?php } ?>
	
			<h3><?php _e('Active Ads', 'adrotate'); ?></h3>

			<form name="banners" id="post" method="post" action="admin.php?page=adrotate">

			<div class="tablenav">
				<div class="alignleft actions">
					<select name="adrotate_action" id="cat" class="postform">
				        <option value=""><?php _e('Bulk Actions', 'adrotate'); ?></option>
				        <option value="deactivate"><?php _e('Deactivate', 'adrotate'); ?></option>
				        <option value="delete"><?php _e('Delete', 'adrotate'); ?></option>
				        <option value="reset"><?php _e('Reset stats', 'adrotate'); ?></option>
				        <option value="" disabled><?php _e('-- Renew --', 'adrotate'); ?></option>
				        <option value="renew-31536000"><?php _e('For 1 year', 'adrotate'); ?></option>
				        <option value="renew-5184000"><?php _e('For 180 days', 'adrotate'); ?></option>
				        <option value="renew-2592000"><?php _e('For 30 days', 'adrotate'); ?></option>
				        <option value="renew-604800"><?php _e('For 7 days', 'adrotate'); ?></option>
				        <option value="" disabled><?php _e('-- Weight --', 'adrotate'); ?></option>
				        <option value="weight-2">2 - <?php _e('Barely visible', 'adrotate'); ?></option>
				        <option value="weight-4">4 - <?php _e('Less than average', 'adrotate'); ?></option>
				        <option value="weight-6">6 - <?php _e('Normal coverage', 'adrotate'); ?></option>
				        <option value="weight-8">8 - <?php _e('More than average', 'adrotate'); ?></option>
				        <option value="weight-10">10 - <?php _e('Best visibility', 'adrotate'); ?></option>
					</select>
					<input type="submit" id="post-action-submit" name="adrotate_action_submit" value="Go" class="button-secondary" />
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<?php _e('Sort by', 'adrotate'); ?> <select name="adrotate_order" id="cat" class="postform">
				        <option value="sortorder ASC" <?php if($order == "sortorder ASC") { echo 'selected'; } ?>><?php _e('Sort Order (ascending, default)', 'adrotate'); ?></option>
				        <option value="sortorder DESC" <?php if($order == "sortorder DESC") { echo 'selected'; } ?>><?php _e('Sort Order (descending)', 'adrotate'); ?></option>
				        <option value="startshow ASC" <?php if($order == "startshow ASC") { echo 'selected'; } ?>><?php _e('Start Date (ascending)', 'adrotate'); ?></option>
				        <option value="startshow DESC" <?php if($order == "startshow DESC") { echo 'selected'; } ?>><?php _e('Start Date (descending)', 'adrotate'); ?></option>
				        <option value="endshow ASC" <?php if($order == "endshow ASC") { echo 'selected'; } ?>><?php _e('End Date (ascending)', 'adrotate'); ?></option>
				        <option value="endshow DESC" <?php if($order == "endshow DESC") { echo 'selected'; } ?>><?php _e('End Date (descending)', 'adrotate'); ?></option>
				        <option value="ID ASC" <?php if($order == "ID ASC") { echo 'selected'; } ?>><?php _e('ID', 'adrotate'); ?></option>
				        <option value="ID DESC" <?php if($order == "ID DESC") { echo 'selected'; } ?>><?php _e('ID reversed', 'adrotate'); ?></option>
				        <option value="title ASC" <?php if($order == "title ASC") { echo 'selected'; } ?>><?php _e('Title (A-Z)', 'adrotate'); ?></option>
				        <option value="title DESC" <?php if($order == "title DESC") { echo 'selected'; } ?>><?php _e('Title (Z-A)', 'adrotate'); ?></option>
					</select>
					<input type="submit" id="post-query-submit" name="adrotate_order_submit" value="Sort" class="button-secondary" />
				</div>

				<br class="clear" />
			</div>

		   	<table class="widefat" style="margin-top: .5em">
	 			<thead>
	  				<tr>
						<th scope="col" class="manage-column column-cb check-column" style=""><input type="checkbox" /></th>
						<th width="2%"><center><?php _e('ID', 'adrotate'); ?></center></th>
						<th width="12%"><?php _e('Show from', 'adrotate'); ?></th>
						<th width="12%"><?php _e('Show until', 'adrotate'); ?></th>
						<th><?php _e('Title', 'adrotate'); ?></th>
						<th width="5%"><center><?php _e('Weight', 'adrotate'); ?></center></th>
						<th width="5%"><center><?php _e('Impressions', 'adrotate'); ?></center></th>
						<th width="5%"><center><?php _e('Today', 'adrotate'); ?></center></th>
						<th width="5%"><center><?php _e('Clicks', 'adrotate'); ?></center></th>
						<th width="5%"><center><?php _e('Today', 'adrotate'); ?></center></th>
						<th width="5%"><center><?php _e('CTR', 'adrotate'); ?></center></th>
					</tr>
	  			</thead>
	  			<tbody>
				<?php
				$banners = $wpdb->get_results("SELECT * FROM `".$wpdb->prefix."adrotate` WHERE `type` = 'manual' AND `active` = 'yes' AND `endshow` >= $in2days ORDER BY ".$order);
				if ($banners) {
					foreach($banners as $banner) {
						$today = gmmktime(0, 0, 0, gmdate("n"), gmdate("j"), gmdate("Y"));
						$stats = $wpdb->get_row("SELECT SUM(`clicks`) as `clicks`, SUM(`impressions`) as `impressions` FROM `".$wpdb->prefix."adrotate_stats_tracker` WHERE `ad` = '$banner->id';");
						$stats_today = $wpdb->get_row("SELECT `clicks`, `impressions` FROM `".$wpdb->prefix."adrotate_stats_tracker` WHERE `ad` = '$banner->id' AND `thetime` = '$today';");

						// Sort out CTR
						if($stats->impressions == 0) $ctrimpressions = 0.001;
							else $ctrimpressions = $stats->impressions;
						if($stats->clicks == 0) $ctrclicks = 0.001;
							else $ctrclicks = $stats->clicks;
						$ctr = round((100/$ctrimpressions)*$ctrclicks,2);						

						// Prevent gaps in display
						if($stats->impressions == 0) 		$stats->impressions 		= 0;
						if($stats->clicks == 0)				$stats->clicks 				= 0;
						if($stats_today != null) {
							if($stats_today->impressions == 0) 	$stats_today->impressions 	= 0;
							if($stats_today->clicks == 0) 		$stats_today->clicks 		= 0;
						} else {
							$stats_today->impressions 		= 0;
							$stats_today->clicks 			= 0;
						}
						
						if($adrotate_debug['dashboard'] == true) {
							echo "<tr><td>&nbsp;</td><td><strong>[DEBUG]</strong></td><td colspan='9'><pre>";
							$memory = (memory_get_usage() / 1024 / 1024);
							echo "Memory usage: " . round($memory, 2) ." MB <br />"; 
							$peakmemory = (memory_get_peak_usage() / 1024 / 1024);
							echo "Peak memory usage: " . round($peakmemory, 2) ." MB</pre><br />"; 
							echo "Ad Specs: <pre>";
							print_r($banner); 
							echo "</pre>"; 
							echo "Stats: <pre>";
							print_r($stats); 
							echo "</pre>"; 
							echo "Stats today: <pre>";
							print_r($stats_today); 
							echo "</pre></td></tr>"; 
						}
									
						$groups	= $wpdb->get_results("
							SELECT 
								`".$wpdb->prefix."adrotate_groups`.`name` 
							FROM 
								`".$wpdb->prefix."adrotate_groups`, 
								`".$wpdb->prefix."adrotate_linkmeta` 
							WHERE 
								`".$wpdb->prefix."adrotate_linkmeta`.`ad` = '".$banner->id."'
								AND `".$wpdb->prefix."adrotate_linkmeta`.`group` = `".$wpdb->prefix."adrotate_groups`.`id`
								AND `".$wpdb->prefix."adrotate_linkmeta`.`block` = 0
								AND `".$wpdb->prefix."adrotate_linkmeta`.`user` = 0
							;");
						$grouplist = '';
						foreach($groups as $group) {
							$grouplist .= $group->name.", ";
						}
						$grouplist = rtrim($grouplist, ", ");
						
						if($class != 'alternate') {
							$class = 'alternate';
						} else {
							$class = '';
						}
						?>
					    <tr id='adrotateindex' class='<?php echo $class; ?>'>
							<th class="check-column"><input type="checkbox" name="bannercheck[]" value="<?php echo $banner->id; ?>" /></th>
							<td><center><?php echo $banner->id;?></center></td>
							<td><?php echo date_i18n("F d, Y", $banner->startshow);?></td>
							<td><span style="color: <?php echo adrotate_prepare_color($banner->endshow);?>;"><?php echo date_i18n("F d, Y", $banner->endshow);?></span></td>
							<td><strong><a class="row-title" href="<?php echo admin_url('/admin.php?page=adrotate&view=edit&ad='.$banner->id);?>" title="<?php _e('Edit', 'adrotate'); ?>"><?php echo stripslashes(html_entity_decode($banner->title));?></a></strong> - <a href="<?php echo admin_url('/admin.php?page=adrotate&view=report&ad='.$banner->id);?>" title="<?php _e('Report', 'adrotate'); ?>"><?php _e('Report', 'adrotate'); ?></a><?php if($groups) echo '<br /><em style="color:#999">'.$grouplist.'</em>'; ?></td>
							<td><center><?php echo $banner->weight; ?></center></td>
							<td><center><?php echo $stats->impressions; ?></center></td>
							<td><center><?php echo $stats_today->impressions; ?></center></td>
							<?php if($banner->tracker == "Y") { ?>
							<td><center><?php echo $stats->clicks; ?></center></td>
							<td><center><?php echo $stats_today->clicks; ?></center></td>
							<td><center><?php echo $ctr; ?> %</center></td>
							<?php } else { ?>
							<td><center>--</center></td>
							<td><center>--</center></td>
							<td><center>--</center></td>
							<?php } ?>
						</tr>
		 			<?php } ?>
		 		<?php } else { ?>
					<tr id='no-groups'>
						<th class="check-column">&nbsp;</th>
						<td colspan="10"><em><?php _e('No ads created yet!', 'adrotate'); ?></em></td>
					</tr>
				<?php } ?>
				</tbody>
			</table>
		
			</form>

			<form name="disabled_banners" id="post" method="post" action="admin.php?page=adrotate">

			<h3><?php _e('Disabled Ads', 'adrotate'); ?></h3>

			<div class="tablenav">
				<div class="alignleft actions">
					<select name="adrotate_disabled_action" id="cat" class="postform">
				        <option value=""><?php _e('Bulk Actions', 'adrotate'); ?></option>
				        <option value="activate"><?php _e('Activate', 'adrotate'); ?></option>
				        <option value="delete"><?php _e('Delete', 'adrotate'); ?></option>
				        <option value="reset"><?php _e('Reset stats', 'adrotate'); ?></option>
					</select>
					<input type="submit" id="post-action-submit" name="adrotate_disabled_action_submit" value="Go" class="button-secondary" />
				</div>

				<br class="clear" />
			</div>

		   	<table class="widefat" style="margin-top: .5em">
	 			<thead>
	  				<tr>
						<th scope="col" class="manage-column column-cb check-column" style=""><input type="checkbox" /></th>
						<th width="2%"><center><?php _e('ID', 'adrotate'); ?></center></th>
						<th width="12%"><?php _e('Show from', 'adrotate'); ?></th>
						<th width="12%"><?php _e('Show until', 'adrotate'); ?></th>
						<th><?php _e('Title', 'adrotate'); ?></th>
						<th width="5%"><center><?php _e('Weight', 'adrotate'); ?></center></th>
						<th width="5%"><center><?php _e('Impressions', 'adrotate'); ?></center></th>
						<th width="5%"><center><?php _e('Clicks', 'adrotate'); ?></center></th>
						<th width="5%"><center><?php _e('CTR', 'adrotate'); ?></center></th>
					</tr>
	  			</thead>
	  			<tbody>
				<?php
				$disabledbanners = $wpdb->get_results("SELECT * FROM `".$wpdb->prefix."adrotate` WHERE `active` = 'no' ORDER BY `sortorder` ASC;");
				if ($disabledbanners) {
					foreach($disabledbanners as $disbanner) {
						$today = gmmktime(0, 0, 0, gmdate("n"), gmdate("j"), gmdate("Y"));
						$stats = $wpdb->get_row("SELECT SUM(`clicks`) as `clicks`, SUM(`impressions`) as `impressions` FROM `".$wpdb->prefix."adrotate_stats_tracker` WHERE `ad` = '$disbanner->id';");

						// Sort out CTR
						if($stats->impressions == 0) $ctrimpressions = 0.001;
							else $ctrimpressions = $stats->impressions;
						if($stats->clicks == 0) $ctrclicks = 0.001;
							else $ctrclicks = $stats->clicks;
						$ctr = round((100/$ctrimpressions)*$ctrclicks,2);						

						// Prevent gaps in display
						if($stats->impressions == 0) 		$stats->impressions 		= 0;
						if($stats->clicks == 0)				$stats->clicks 				= 0;
						
						if($adrotate_debug['dashboard'] == true) {
							echo "<tr><td>&nbsp;</td><td><strong>[DEBUG]</strong></td><td colspan='9'><pre>";
							$memory = (memory_get_usage() / 1024 / 1024);
							echo "Memory usage: " . round($memory, 2) ." MB <br />"; 
							$peakmemory = (memory_get_peak_usage() / 1024 / 1024);
							echo "Peak memory usage: " . round($peakmemory, 2) ." MB</pre><br />"; 
							echo "Ad Specs: <pre>";
							print_r($disbanner); 
							echo "</pre>"; 
							echo "Stats: <pre>";
							print_r($stats); 
							echo "</pre></td></tr>"; 
						}
									
						$groups	= $wpdb->get_results("
							SELECT 
								`".$wpdb->prefix."adrotate_groups`.`name` 
							FROM 
								`".$wpdb->prefix."adrotate_groups`, 
								`".$wpdb->prefix."adrotate_linkmeta` 
							WHERE 
								`".$wpdb->prefix."adrotate_linkmeta`.`ad` = '".$disbanner->id."'
								AND `".$wpdb->prefix."adrotate_linkmeta`.`group` = `".$wpdb->prefix."adrotate_groups`.`id`
								AND `".$wpdb->prefix."adrotate_linkmeta`.`block` = 0
								AND `".$wpdb->prefix."adrotate_linkmeta`.`user` = 0
							;");
						$grouplist = '';
						foreach($groups as $group) {
							$grouplist .= $group->name.", ";
						}
						$grouplist = rtrim($grouplist, ", ");
						
						if($disbanner->active == 'no' AND $disbanner->type != 'error') {
							$inactiveclass = ' row_inactive';
						} else {
							$inactiveclass = '';
						}

						if($disbanner->type == 'error') {
							$errorclass = ' row_error';
						} else {
							$errorclass = '';
						}
	
						if($class != 'alternate') {
							$class = 'alternate';
						} else {
							$class = '';
						}
						?>
					    <tr id='adrotateindex' class='<?php echo $class.$inactiveclass.$errorclass; ?>'>
							<th class="check-column"><input type="checkbox" name="disabledbannercheck[]" value="<?php echo $disbanner->id; ?>" /></th>
							<td><center><?php echo $disbanner->id;?></center></td>
							<td><?php echo date_i18n("F d, Y", $disbanner->startshow);?></td>
							<td><span style="color: <?php echo adrotate_prepare_color($disbanner->endshow);?>;"><?php echo date_i18n("F d, Y", $disbanner->endshow);?></span></td>
							<td><strong><a class="row-title" href="<?php echo admin_url('/admin.php?page=adrotate&view=edit&ad='.$disbanner->id);?>" title="<?php _e('Edit', 'adrotate'); ?>"><?php echo stripslashes(html_entity_decode($disbanner->title));?></a></strong> - <a href="<?php echo admin_url('/admin.php?page=adrotate&view=report&ad='.$disbanner->id);?>" title="<?php _e('Report', 'adrotate'); ?>"><?php _e('Report', 'adrotate'); ?></a><?php if($groups) echo '<br /><em style="color:#999">'.$grouplist.'</em>'; ?></td>
							<td><center><?php echo $disbanner->weight; ?></center></td>
							<td><center><?php echo $stats->impressions; ?></center></td>
							<?php if($disbanner->tracker == "Y") { ?>
							<td><center><?php echo $stats->clicks; ?></center></td>
							<td><center><?php echo $ctr; ?> %</center></td>
							<?php } else { ?>
							<td><center>--</center></td>
							<td><center>--</center></td>
							<?php } ?>
						</tr>
		 			<?php } ?>
		 		<?php } else { ?>
					<tr id='no-groups'>
						<th class="check-column">&nbsp;</th>
						<td colspan="10"><em><?php _e('No disabled ads!', 'adrotate'); ?></em></td>
					</tr>
				<?php } ?>
				</tbody>
			</table>
			
			</form>

		   	<?php } else if($view == "addnew" OR $view == "edit") { ?>

			<?php if(!$ad_edit_id) { ?>
			<h3><?php _e('New Ad', 'adrotate'); ?></h3>
			<?php
				$action = "new";
				$startshow = $now;
				$endshow = $now + 31536000;
				$query = "SELECT `id` FROM `".$wpdb->prefix."adrotate` WHERE `type` = 'empty' ORDER BY `id` DESC LIMIT 1;";
				$edit_id = $wpdb->get_var($query);
				if($edit_id == 0) {
					$wpdb->query("INSERT INTO `".$wpdb->prefix."adrotate` (`title`, `bannercode`, `thetime`, `updated`, `author`, `active`, `startshow`, `endshow`, `imagetype`, `image`, `link`, `tracker`, `maxclicks`, `maxshown`, `targetclicks`, `targetimpressions`, `type`, `weight`, `sortorder`) VALUES ('', '', '$startshow', '$startshow', '$userdata->user_login', 'yes', '$startshow', '$endshow', '', '', '', 'N', 0, 0, 0, 0, 'empty', 6, 0);");
					$edit_id = $wpdb->get_var($query);
				}
				$ad_edit_id = $edit_id;
			} else { ?>
			<h3><?php _e('Edit Ad', 'adrotate'); ?></h3>
			<?php
				$action = "update";
				
			}
			
			$edit_banner 	= $wpdb->get_row("SELECT * FROM `".$wpdb->prefix."adrotate` WHERE `id` = '$ad_edit_id';");
			$groups			= $wpdb->get_results("SELECT * FROM `".$wpdb->prefix."adrotate_groups` WHERE `name` != '' ORDER BY `id` ASC;"); 
			$user_list		= $wpdb->get_results("SELECT `ID`, `display_name` FROM `$wpdb->users` ORDER BY `user_nicename` ASC;");
			$saved_user 	= $wpdb->get_var("SELECT `user` FROM `".$wpdb->prefix."adrotate_linkmeta` WHERE `ad` = '$edit_banner->id' AND `group` = 0 AND `block` = 0;");
			$linkmeta		= $wpdb->get_results("SELECT `group` FROM `".$wpdb->prefix."adrotate_linkmeta` WHERE `ad` = '$edit_banner->id' AND `block` = 0 AND `user` = 0;");
			foreach($linkmeta as $meta) {
				$meta_array[] = $meta->group;
			}
			if(!is_array($meta_array)) $meta_array = array();
			
			list($sday, $smonth, $syear) = split(" ", gmdate("d m Y", $edit_banner->startshow));
			list($eday, $emonth, $eyear) = split(" ", gmdate("d m Y", $edit_banner->endshow));
			
			if($ad_edit_id) {
				// Errors
				if(strlen($edit_banner->bannercode) < 1 AND $edit_banner->type != 'empty') 
					echo '<div class="error"><p>'. __('The AdCode cannot be empty!', 'adrotate').'</p></div>';

				if($edit_banner->tracker == 'N' AND strlen($edit_banner->link) < 1 AND $saved_user > 0) 
					echo '<div class="error"><p>'. __('You\'ve set an advertiser but didn\'t enable clicktracking!', 'adrotate').'</p></div>';

				if($edit_banner->tracker == 'Y' AND strlen($edit_banner->link) < 1) 
					echo '<div class="error"><p>'. __('You\'ve enabled clicktracking but didn\'t provide an url in the url field!', 'adrotate').'</p></div>';

				if($edit_banner->tracker == 'N' AND strlen($edit_banner->link) > 0) 
					echo '<div class="error"><p>'. __('You didn\'t enable clicktracking but you did use the url field!', 'adrotate').'</p></div>';

				if(!preg_match("/%link%/i", $edit_banner->bannercode) AND $edit_banner->tracker == 'Y') 
					echo '<div class="error"><p>'. __('You didn\'t use %link% in your AdCode but did enable clicktracking!', 'adrotate').'</p></div>';

				if(preg_match("/%link%/i", $edit_banner->bannercode) AND $edit_banner->tracker == 'N') 
					echo '<div class="error"><p>'. __('You\'ve %link% in your AdCode but didn\'t enable clicktracking!', 'adrotate').'</p></div>';

				if(!preg_match("/%image%/i", $edit_banner->bannercode) AND $edit_banner->image != '') 
					echo '<div class="error"><p>'. __('You didn\'t use %image% in your AdCode but did select an image!', 'adrotate').'</p></div>';

				if(preg_match("/%image%/i", $edit_banner->bannercode) AND $edit_banner->image == '') 
					echo '<div class="error"><p>'. __('You did use %image% in your AdCode but did not select an image!', 'adrotate').'</p></div>';
				
				if((($edit_banner->imagetype != '' AND $edit_banner->image == '') OR ($edit_banner->imagetype == '' AND $edit_banner->image != ''))) 
					echo '<div class="error"><p>'. __('There is a problem saving the image specification. Please reset your image and re-save the ad!', 'adrotate').'</p></div>';
				
				// If ad is set to error, but there is no error
				if($edit_banner->type == 'error'
				AND strlen($edit_banner->bannercode) > 1
				AND (($edit_banner->tracker == 'Y' AND strlen($edit_banner->link) > 0 AND $saved_user > 0)
					OR ($edit_banner->tracker == 'N' AND strlen($edit_banner->link) < 1)
					OR ($edit_banner->tracker == 'Y' AND strlen($edit_banner->link) > 0))
				AND ((!preg_match("/%link%/i", $edit_banner->bannercode) AND $edit_banner->tracker == 'N')
					OR (preg_match("/%link%/i", $edit_banner->bannercode) AND $edit_banner->tracker == 'Y'))
				AND ((!preg_match("/%image%/i", $edit_banner->bannercode) AND $edit_banner->image == '')
					OR (preg_match("/%image%/i", $edit_banner->bannercode) AND $edit_banner->image != ''))
				AND (($edit_banner->imagetype == '' AND $edit_banner->image == '')
					OR ($edit_banner->imagetype != '' AND $edit_banner->image != ''))
				) echo '<div class="error"><p>'. __('AdRotate cannot find an error but the ad is marked erroneous, try re-saving the ad!', 'adrotate').'</p></div>';
				
				// Notices
				if($edit_banner->active == 'no' AND $edit_banner->type != "empty") echo '<div class="updated"><p>'. __('This ad has been disabled and does not rotate on your site!', 'adrotate').'</p></div>';
				if($edit_banner->endshow < $now) echo '<div class="updated"><p>'. __('This ad is expired and currently not shown on your website!', 'adrotate').'</p></div>';
				else if($edit_banner->endshow < $in2days) echo '<div class="updated"><p>'. __('This ad will expire in less than 2 days!', 'adrotate').'</p></div>';
				else if($edit_banner->endshow < $in7days) echo '<div class="updated"><p>'. __('This ad will expire in less than 7 days!', 'adrotate').'</p></div>';

				// Determine image field
				if($edit_banner->imagetype == "field") {
					$image_field = $edit_banner->image;
					$image_dropdown = '';
				}
				
				if($edit_banner->imagetype == "dropdown") {
					$image_field = '';
					$image_dropdown = $edit_banner->image;
				}
			}
			?>
			
			<script language="JavaScript">
			jQuery(document).ready(function() {
				jQuery('#adrotate_image_button').click(function() {
					formfield = jQuery('#adrotate_image').attr('name');
					tb_show('', 'media-upload.php?type=image&TB_iframe=true');
					return false;
				});
				
				window.send_to_editor = function(html) {
					imgurl = jQuery('img',html).attr('src');
					jQuery('#adrotate_image').val(imgurl);
					tb_remove();
				}
			
			});
			</script>

		  	<form method="post" action="admin.php?page=adrotate">
		    	<input type="hidden" name="adrotate_username" value="<?php echo $userdata->user_login;?>" />
		    	<input type="hidden" name="adrotate_id" value="<?php echo $edit_banner->id;?>" />
		    	<input type="hidden" name="adrotate_type" value="<?php echo $edit_banner->type;?>" />
	
		    	<table class="widefat" style="margin-top: .5em">
	
					<thead>
					<tr>
						<th colspan="4"><?php _e('The basics (Required)', 'adrotate'); ?></th>
					</tr>
					</thead>
	
					<tbody>
			      	<tr>
				        <th><?php _e('Title:', 'adrotate'); ?></th>
				        <td colspan="3"><input tabindex="1" name="adrotate_title" type="text" size="80" class="search-input" value="<?php echo $edit_banner->title;?>" autocomplete="off" /></td>
			      	</tr>
			      	<tr>
				        <th valign="top"><?php _e('AdCode:', 'adrotate'); ?></th>
				        <td colspan="2">
				        	<textarea tabindex="2" name="adrotate_bannercode" cols="65" rows="15"><?php echo stripslashes($edit_banner->bannercode); ?></textarea>
				        </td>
				        <td>
					        <p><strong><?php _e('Options:', 'adrotate'); ?></strong></p>
					        <p><em>%id%, %image%, %link%</em><br />
					        <?php _e('HTML/JavaScript allowed, use with care!', 'adrotate'); ?></p>
					        
					        <p><strong><?php _e('Basic Examples:', 'adrotate'); ?></strong></p>
					        <p><?php _e('Clicktracking:', 'adrotate'); ?> <em>&lt;a href="%link%"&gt;This ad is great!&lt;/a&gt;</em></p>
					        <p><?php _e('Image:', 'adrotate'); ?> <em>&lt;a href="http://example.com"&gt;&lt;img src="%image%" /&gt;&lt;/a&gt;</em></p>
					        <p><?php _e('Combination:', 'adrotate'); ?> <em>&lt;a href="%link%"&gt;&lt;img src="%image%" /&gt;&lt;/a&gt;</em></p>
					        
					        <p><strong><?php _e('Advanced Example:', 'adrotate'); ?></strong></p>
					        <p><?php _e('Clicktracking:', 'adrotate'); ?> <em>&lt;span class="ad-%id%"&gt;&lt;a href="%link%"&gt;Text Link Ad!&lt;/a&gt;&lt;/span&gt;</em></p>
				        </td>
			      	</tr>
			      	<tr>
				        <th><?php _e('Display From:', 'adrotate'); ?></th>
				        <td>
				        	<input tabindex="3" name="adrotate_sday" class="search-input" type="text" size="4" maxlength="2" value="<?php echo $sday;?>" /> /
							<select tabindex="4" name="adrotate_smonth">
								<option value="01" <?php if($smonth == "01") { echo 'selected'; } ?>><?php _e('January', 'adrotate'); ?></option>
								<option value="02" <?php if($smonth == "02") { echo 'selected'; } ?>><?php _e('February', 'adrotate'); ?></option>
								<option value="03" <?php if($smonth == "03") { echo 'selected'; } ?>><?php _e('March', 'adrotate'); ?></option>
								<option value="04" <?php if($smonth == "04") { echo 'selected'; } ?>><?php _e('April', 'adrotate'); ?></option>
								<option value="05" <?php if($smonth == "05") { echo 'selected'; } ?>><?php _e('May', 'adrotate'); ?></option>
								<option value="06" <?php if($smonth == "06") { echo 'selected'; } ?>><?php _e('June', 'adrotate'); ?></option>
								<option value="07" <?php if($smonth == "07") { echo 'selected'; } ?>><?php _e('July', 'adrotate'); ?></option>
								<option value="08" <?php if($smonth == "08") { echo 'selected'; } ?>><?php _e('August', 'adrotate'); ?></option>
								<option value="09" <?php if($smonth == "09") { echo 'selected'; } ?>><?php _e('September', 'adrotate'); ?></option>
								<option value="10" <?php if($smonth == "10") { echo 'selected'; } ?>><?php _e('October', 'adrotate'); ?></option>
								<option value="11" <?php if($smonth == "11") { echo 'selected'; } ?>><?php _e('November', 'adrotate'); ?></option>
								<option value="12" <?php if($smonth == "12") { echo 'selected'; } ?>><?php _e('December', 'adrotate'); ?></option>
							</select> /
							<input tabindex="5" name="adrotate_syear" class="search-input" type="text" size="4" maxlength="4" value="<?php echo $syear;?>" />
				        </td>
				        <th><?php _e('Until:', 'adrotate'); ?></th>
				        <td>
				        	<input tabindex="6" name="adrotate_eday" class="search-input" type="text" size="4" maxlength="2" value="<?php echo $eday;?>"  /> /
							<select tabindex="7" name="adrotate_emonth">
								<option value="01" <?php if($emonth == "01") { echo 'selected'; } ?>><?php _e('January', 'adrotate'); ?></option>
								<option value="02" <?php if($emonth == "02") { echo 'selected'; } ?>><?php _e('February', 'adrotate'); ?></option>
								<option value="03" <?php if($emonth == "03") { echo 'selected'; } ?>><?php _e('March', 'adrotate'); ?></option>
								<option value="04" <?php if($emonth == "04") { echo 'selected'; } ?>><?php _e('April', 'adrotate'); ?></option>
								<option value="05" <?php if($emonth == "05") { echo 'selected'; } ?>><?php _e('May', 'adrotate'); ?></option>
								<option value="06" <?php if($emonth == "06") { echo 'selected'; } ?>><?php _e('June', 'adrotate'); ?></option>
								<option value="07" <?php if($emonth == "07") { echo 'selected'; } ?>><?php _e('July', 'adrotate'); ?></option>
								<option value="08" <?php if($emonth == "08") { echo 'selected'; } ?>><?php _e('August', 'adrotate'); ?></option>
								<option value="09" <?php if($emonth == "09") { echo 'selected'; } ?>><?php _e('September', 'adrotate'); ?></option>
								<option value="10" <?php if($emonth == "10") { echo 'selected'; } ?>><?php _e('October', 'adrotate'); ?></option>
								<option value="11" <?php if($emonth == "11") { echo 'selected'; } ?>><?php _e('November', 'adrotate'); ?></option>
								<option value="12" <?php if($emonth == "12") { echo 'selected'; } ?>><?php _e('December', 'adrotate'); ?></option>
							</select> /
							<input tabindex="8" name="adrotate_eyear" class="search-input" type="text" size="4" maxlength="4" value="<?php echo $eyear;?>" />
						</td>
			      	</tr>
			      	<tr>
				        <th><?php _e('Activate:', 'adrotate'); ?></th>
				        <td colspan="3">
					        <select tabindex="9" name="adrotate_active">
								<option value="yes" <?php if($edit_banner->active == "yes") { echo 'selected'; } ?>><?php _e('Yes, this ad will be used', 'adrotate'); ?></option>
								<option value="no" <?php if($edit_banner->active == "no") { echo 'selected'; } ?>><?php _e('No, no do not show this ad anywhere', 'adrotate'); ?></option>
							</select>
						</td>
			      	</tr>
			      	<tr>
				        <th><?php _e('Sortorder:', 'adrotate'); ?></th>
				        <td colspan="3">
					        <input tabindex="10" name="adrotate_sortorder" type="text" size="5" class="search-input" autocomplete="off" value="<?php echo $edit_banner->sortorder;?>" /> <em><?php _e('For administrative purposes set a sortorder.', 'adrotate'); ?> <?php _e('Leave empty or 0 to skip this. Will default to ad id.', 'adrotate'); ?></em>
						</td>
			      	</tr>
					</tbody>
	
				<?php if($edit_banner->type != 'empty') { ?>
					<thead>
					<tr>
						<th colspan="4"><?php _e('Preview', 'adrotate'); ?></th>
					</tr>
					</thead>
	
					<tbody>
			      	<tr>
				        <td colspan="4"><?php echo adrotate_preview($edit_banner->id); ?>
				        <br /><em><?php _e('Note: While this preview is an accurate one, it might look different then it does on the website.', 'adrotate'); ?>
						<br /><?php _e('This is because of CSS differences. Your themes CSS file is not active here!', 'adrotate'); ?></em></td>
			      	</tr>
			      	</tbody>
				<?php } ?>
	
					<thead>
					<tr>
						<th colspan="4"><?php _e('Usage', 'adrotate'); ?></th>
					</tr>
					</thead>
	
					<tbody>
			      	<tr>
				        <th><?php _e('In a post or page:', 'adrotate'); ?></th>
				        <td>[adrotate banner="<?php echo $edit_banner->id; ?>"]</td>
				        <th><?php _e('Directly in a theme:', 'adrotate'); ?></th>
				        <td>&lt;?php echo adrotate_ad(<?php echo $edit_banner->id; ?>); ?&gt;</td>
			      	</tr>
			      	</tbody>
	
					<thead>
					<tr>
						<th colspan="4" bgcolor="#DDD"><?php _e('Advanced (Everything below is optional)', 'adrotate'); ?></th>
					</tr>
					</thead>
	
					<tbody>
			      	<tr>
				        <th valign="top"><?php _e('Advertiser:', 'adrotate'); ?></th>
				        <td colspan="3">
				        	<select tabindex="11" name="adrotate_advertiser" style="min-width: 200px;">
								<option value="0" <?php if($saved_user == '0') { echo 'selected'; } ?>><?php _e('Not specified', 'adrotate'); ?></option>
							<?php 
							foreach($user_list as $user) {
/*
							foreach($user_list as $id) {
								$user = get_userdata($id->ID); 
								if(strlen($user->first_name) < 1) $firstname = $user->user_login;
									else $firstname = $user->first_name;
								if(strlen($user->last_name) < 1) $lastname = ''; 
									else $lastname = $user->last_name;
								if($user->ID == $userdata->ID) $you = ' (You)';
									else $you = '';
*/
								if($user->ID == $userdata->ID) $you = ' (You)';
									else $you = '';
							?>
								<option value="<?php echo $user->ID; ?>"<?php if($saved_user == $user->ID) { echo ' selected'; } ?>><?php echo $user->display_name; ?><?php echo $you; ?></option>
							<?php } ?>
							</select><br />
					        <em><?php _e('Must be a registered user on your site with appropriate access roles.', 'adrotate'); ?></em>
						</td>
			      	</tr>
			      	<tr>
				        <th valign="top"><?php _e('Clicktracking:', 'adrotate'); ?></th>
				        <td colspan="3">
				        	<?php _e('Enable?', 'adrotate'); ?> <input tabindex="12" type="checkbox" name="adrotate_tracker" <?php if($edit_banner->tracker == 'Y') { ?>checked="checked" <?php } ?> /> url: <input tabindex="12" name="adrotate_link" type="text" size="80" class="search-input" value="<?php echo $edit_banner->link;?>" /><br />
					        <em><?php _e('Use %link% in the adcode instead of the actual url.', 'adrotate'); ?><br />
					        <?php _e('For a random seed you can use %random%. A generated timestamp you can use.', 'adrotate'); ?></em>
				        </td>
			      	</tr>
					<tr>
				        <th valign="top"><?php _e('Banner image:', 'adrotate'); ?></th>
						<td colspan="3">
							<label for="upload_image">
								<?php _e('Media:', 'adrotate'); ?> <input tabindex="14" size="100" id="adrotate_image" type="text" name="adrotate_image" value="<?php echo $image_field; ?>" /> <input tabindex="15" id="adrotate_image_button" type="button" value="<?php _e('Select Image', 'adrotate'); ?>" /><br />
								<?php _e('- OR -', 'adrotate'); ?><br />
								<?php _e('Banner folder:', 'adrotate'); ?> <select tabindex="16" name="adrotate_image_dropdown" style="min-width: 200px;">
			   						<option value=""><?php _e('No image selected', 'adrotate'); ?></option>
									<?php echo adrotate_folder_contents($image_dropdown); ?>
								</select><br />
								<em><?php _e('Use %image% in the code. Accepted files are:', 'adrotate'); ?> jpg, jpeg, gif, png, swf and flv. <?php _e('Use either the text field or the dropdown. If the textfield has content that field has priority.', 'adrotate'); ?></em>
							</label>
						</td>
					</tr>
			      	<tr>
					    <th valign="top"><?php _e('Weight:', 'adrotate'); ?></th>
				        <td colspan="3">
				        	<input type="radio" tabindex="17" name="adrotate_weight" value="2" <?php if($edit_banner->weight == "2") { echo 'checked'; } ?> /> 2, <?php _e('Barely visible', 'adrotate'); ?><br />
				        	<input type="radio" tabindex="18" name="adrotate_weight" value="4" <?php if($edit_banner->weight == "4") { echo 'checked'; } ?> /> 4, <?php _e('Less than average', 'adrotate'); ?><br />
				        	<input type="radio" tabindex="19" name="adrotate_weight" value="6" <?php if($edit_banner->weight == "6") { echo 'checked'; } ?> /> 6, <?php _e('Normal coverage', 'adrotate'); ?><br />
				        	<input type="radio" tabindex="20" name="adrotate_weight" value="8" <?php if($edit_banner->weight == "8") { echo 'checked'; } ?> /> 8, <?php _e('More than average', 'adrotate'); ?><br />
				        	<input type="radio" tabindex="21" name="adrotate_weight" value="10" <?php if($edit_banner->weight == "10") { echo 'checked'; } ?> /> 10, <?php _e('Best visibility', 'adrotate'); ?>
						</td>
					</tr>
			      	<tr>
					    <th><?php _e('Maximum Clicks:', 'adrotate'); ?></th>
				        <td colspan="3"><?php _e('Disable after', 'adrotate'); ?> <input tabindex="22" name="adrotate_maxclicks" type="text" size="5" class="search-input" autocomplete="off" value="<?php echo $edit_banner->maxclicks;?>" /> <?php _e('clicks!', 'adrotate'); ?> <em><?php _e('Leave empty or 0 to skip this.', 'adrotate'); ?></em></td>
					</tr>
			      	<tr>
					    <th><?php _e('Maximum Impressions:', 'adrotate'); ?></th>
				        <td colspan="3"><?php _e('Disable after', 'adrotate'); ?> <input tabindex="23" name="adrotate_maxshown" type="text" size="5" class="search-input" autocomplete="off" value="<?php echo $edit_banner->maxshown;?>" /> <?php _e('impressions!', 'adrotate'); ?> <em><?php _e('Leave empty or 0 to skip this.', 'adrotate'); ?></em></td>
					</tr>
			      	<tr>
				        <th valign="top"><?php _e('Expected Clicks:', 'adrotate'); ?></th>
				        <td colspan="3">
				        	<input tabindex="24" name="adrotate_targetclicks" type="text" size="5" class="search-input" autocomplete="off" value="<?php echo $edit_banner->targetclicks;?>" /> <em><?php _e('Set a target or milestone for clicks. Shows in the graph.', 'adrotate'); ?> <?php _e('Leave empty or 0 to skip this.', 'adrotate'); ?></em>
				        </td>
			      	</tr>
			      	<tr>
				        <th valign="top"><?php _e('Expected impressions:', 'adrotate'); ?></th>
				        <td colspan="3">
				        	<input tabindex="25" name="adrotate_targetimpressions" type="text" size="5" class="search-input" autocomplete="off" value="<?php echo $edit_banner->targetimpressions;?>" /> <em><?php _e('Set a target or milestone for impressions. Shows in the graph.', 'adrotate'); ?> <?php _e('Leave empty or 0 to skip this.', 'adrotate'); ?></em>
				        </td>
			      	</tr>
					</tbody>

				<?php if($edit_banner->type != 'empty') { ?>
					<thead>
					<tr>
						<th colspan="4" bgcolor="#DDD"><?php _e('Maintenance', 'adrotate'); ?></th>
					</tr>
					</thead>
	
					<tbody>
			      	<tr>
				        <th><?php _e('Actions:', 'adrotate'); ?></th>
				        <td colspan="3">
					        <select name="adrotate_action" id="cat" class="postform">
						        <option value="0">--</option>
						        <option value="renew-31536000"><?php _e('Renew for 1 year', 'adrotate'); ?></option>
						        <option value="renew-5184000"><?php _e('Renew for 180 days', 'adrotate'); ?></option>
						        <option value="renew-2592000"><?php _e('Renew for 30 days', 'adrotate'); ?></option>
						        <option value="renew-604800"><?php _e('Renew for 7 days', 'adrotate'); ?></option>
						        <option value="delete"><?php _e('Delete', 'adrotate'); ?></option>
						        <option value="reset"><?php _e('Reset stats', 'adrotate'); ?></option>
							</select> <input type="submit" id="post-action-submit" name="adrotate_action_submit" value="Go" class="button-secondary" />
						</td>
					</tr>
					</tbody>
				<?php } ?>
				
				</table>
	
		    	<p class="submit">
					<input tabindex="26" type="submit" name="adrotate_ad_submit" class="button-primary" value="<?php _e('Save ad', 'adrotate'); ?>" />
					<a href="admin.php?page=adrotate&view=manage" class="button"><?php _e('Cancel', 'adrotate'); ?></a>
		    	</p>

				<?php if($groups) { ?>
				<h3><?php _e('Select Groups', 'adrotate'); ?></h3>

		    	<table class="widefat" style="margin-top: .5em">
		  			<thead>
	  				<tr>
						<th colspan="3"><?php _e('Select the group(s) this ad belongs to (Optional)', 'adrotate'); ?></th>
					</tr>
		  			</thead>

					<tbody>
					<?php foreach($groups as $group) {
						$ads_in_group = $wpdb->get_var("SELECT COUNT(*) FROM `".$wpdb->prefix."adrotate_linkmeta` WHERE `group` = ".$group->id." AND `block` = 0;");
						$class = ('alternate' != $class) ? 'alternate' : ''; ?>
					    <tr id='group-<?php echo $group->id; ?>' class=' <?php echo $class; ?>'>
							<th class="check-column"><input type="checkbox" name="groupselect[]" value="<?php echo $group->id; ?>" <?php if(in_array($group->id, $meta_array)) echo "checked"; ?> /></th>
							<td><?php echo $group->id; ?> - <strong><?php echo $group->name; ?></strong></td>
							<td width="15%"><?php echo $ads_in_group; ?> <?php _e('Ads', 'adrotate'); ?></td>
						</tr>
		 			<?php } ?>
					</tbody>					
				</table>
				<?php } ?>
	
		    	<p class="submit">
					<input tabindex="27" type="submit" name="adrotate_ad_submit" class="button-primary" value="<?php _e('Save ad', 'adrotate'); ?>" />
					<a href="admin.php?page=adrotate&view=manage" class="button"><?php _e('Cancel', 'adrotate'); ?></a>
		    	</p>
	
			</form>

		   	<?php } else if($view == "report") { ?>

				<h3><?php _e('This ads performance', 'adrotate'); ?></h3>
				
				<?php
					$today 			= gmmktime(0, 0, 0, gmdate("n"), gmdate("j"), gmdate("Y"));
					$banner 		= $wpdb->get_row("SELECT `title`, `tracker` FROM `".$wpdb->prefix."adrotate` WHERE `id` = '$ad_edit_id';");
					$stats 			= $wpdb->get_row("SELECT SUM(`clicks`) as `clicks`, SUM(`impressions`) as `impressions` FROM `".$wpdb->prefix."adrotate_stats_tracker` WHERE `ad` = '$ad_edit_id';");
					$stats_today 	= $wpdb->get_row("SELECT SUM(`clicks`) as `clicks`, SUM(`impressions`) as `impressions` FROM `".$wpdb->prefix."adrotate_stats_tracker` WHERE `ad` = '$ad_edit_id' AND `thetime` = '$today';");

					// Sort out CTR
					if($stats->impressions == 0) $ctrimpressions = 0.001;
						else $ctrimpressions = $stats->impressions;
					if($stats->clicks == 0) $ctrclicks = 0.001;
						else $ctrclicks = $stats->clicks;
					$ctr = round((100/$ctrimpressions)*$ctrclicks,2);						
	
					// Prevent gaps in display
					if($stats->impressions == 0) 		$stats->impressions 		= 0;
					if($stats->clicks == 0) 			$stats->clicks 				= 0;
					if($stats_today->impressions == 0) 	$stats_today->impressions 	= 0;
					if($stats_today->clicks == 0) 		$stats_today->clicks 		= 0;
			
					if($adrotate_debug['stats'] == true) {
						echo "<p><strong>[DEBUG] Ad Stats (all time)</strong><pre>";
						$memory = (memory_get_usage() / 1024 / 1024);
						echo "Memory usage: " . round($memory, 2) ." MB <br />"; 
						$peakmemory = (memory_get_peak_usage() / 1024 / 1024);
						echo "Peak memory usage: " . round($peakmemory, 2) ." MB <br />"; 
						print_r($stats); 
						echo "</pre></p>"; 
						echo "<p><strong>[DEBUG] Ad Stats (today)</strong><pre>";
						print_r($stats_today); 
						echo "</pre></p>"; 
					}	
		
				?>
				
		    	<table class="widefat" style="margin-top: .5em">
					<thead>
					<tr>
						<th colspan="5" bgcolor="#DDD"><?php _e('Statistics for', 'adrotate'); ?> '<?php echo $banner->title; ?>'</th>
					</tr>
					</thead>
	
					<tbody>
			      	<tr>
				        <td width="20%"><div class="stats_large"><?php _e('Impressions', 'adrotate'); ?><br /><div class="number_large"><?php echo $stats->impressions; ?></div></div></td>
				        <td width="20%"><div class="stats_large"><?php _e('Clicks', 'adrotate'); ?><br /><div class="number_large"><?php if($banner->tracker == "Y") { echo $stats->clicks; } else { echo '--'; } ?></div></div></td>
				        <td width="20%"><div class="stats_large"><?php _e('Impressions today', 'adrotate'); ?><br /><div class="number_large"><?php echo $stats_today->impressions; ?></div></div></td>
				        <td width="20%"><div class="stats_large"><?php _e('Clicks today', 'adrotate'); ?><br /><div class="number_large"><?php if($banner->tracker == "Y") { echo $stats_today->clicks; } else { echo '--'; } ?></div></div></td>
				        <td width="20%"><div class="stats_large"><?php _e('CTR', 'adrotate'); ?><br /><div class="number_large"><?php if($banner->tracker == "Y") { echo $ctr.' %'; } else { echo '--'; } ?></div></div></td>
			      	</tr>
			      	<tr>
				        <th colspan="5">
				        	<?php
				        	$adstats = $wpdb->get_results("SELECT `thetime`, SUM(`clicks`) as `clicks`, SUM(`impressions`) as `impressions` FROM `".$wpdb->prefix."adrotate_stats_tracker` WHERE `ad` = '$ad_edit_id' GROUP BY `thetime` DESC LIMIT 21;");
				        	$target = $wpdb->get_row("SELECT `targetclicks`, `targetimpressions` FROM `".$wpdb->prefix."adrotate` WHERE `id` = '$ad_edit_id';");
							if($adstats) {
							
								$adstats = array_reverse($adstats);

								if($adrotate_debug['stats'] == true) { 
									echo "<p><strong>[DEBUG] 21 days (Or as much as is available) Ad stats</strong><pre>"; 
									print_r($adstats); 
									echo "</pre></p>"; 
								}

								foreach($adstats as $result) {
									if($result->clicks == null) $result->clicks = '0';
									if($result->impressions == null) $result->impressions = '0';
									
									$clicks_array[date_i18n("d M", $result->thetime)] = $result->clicks;
									$impressions_array[date_i18n("d M", $result->thetime)] = $result->impressions;
								}
								
								if($adrotate_debug['stats'] == true) { 
									echo "<p><strong>[DEBUG] Found clicks as presented to PHPGraphLib</strong><pre>"; 
									print_r($clicks_array); 
									echo "</pre></p>"; 
									echo "<p><strong>[DEBUG] Found impressions as presented to PHPGraphLib</strong><pre>"; 
									print_r($impressions_array); 
									echo "</pre></p>"; 
								}

								$impressions_title = urlencode(serialize('Impressions over the past 21 days'));
								$impressions_target = urlencode(serialize($target->targetimpressions));
								$impressions_array = urlencode(serialize($impressions_array));
								echo "<img src=\"../wp-content/plugins/adrotate/library/graph_single_ad.php?title=$impressions_title&target=$impressions_target&data=$impressions_array\" />";

								if($banner->tracker == "Y") {
									$clicks_title = urlencode(serialize('Clicks over the past 21 days'));
									$clicks_target = urlencode(serialize($target->targetclicks));
									$clicks_array = urlencode(serialize($clicks_array));
									echo "<img src=\"../wp-content/plugins/adrotate/library/graph_single_ad.php?title=$clicks_title&target=$clicks_target&data=$clicks_array\" />";
								}
							} else {
								_e('No data to show!', 'adrotate');
							} 
							?>
				        </th>
			      	</tr>
			      	<tr>
						<td colspan="5">
							<b><?php _e('Note:', 'adrotate'); ?></b> <em><?php _e('All statistics are indicative. They do not nessesarily reflect results counted by other parties.', 'adrotate'); ?></em><br />
							<?php _e('Visual graphing kindly provided using', 'adrotate'); ?> <a href="http://www.ebrueggeman.com/" target="_blank">PHPGraphLib by Elliot Brueggeman</a>
						</td>
			      	</tr>
					</tbody>
				</table>

		   	<?php } ?>

			<br class="clear" />

			<?php adrotate_credits(); ?>

		<?php } else { ?>
			<?php echo adrotate_error('db_error'); ?>
		<?php }	?>
		<br class="clear" />
	</div>
<?php
}

/*-------------------------------------------------------------
 Name:      adrotate_manage_group

 Purpose:   Manage groups
 Receive:   -none-
 Return:    -none-
-------------------------------------------------------------*/
function adrotate_manage_group() {
	global $wpdb, $adrotate_debug;

	$message 		= $_GET['message'];
	$view 			= $_GET['view'];
	$group_edit_id 	= $_GET['group'];
	?>

	<style type="text/css" media="screen">
	.stats_large {
		display: block;
		margin-bottom: 10px;
		margin-top: 10px;
		text-align: center;
		font-weight: bold;
	}
	.number_large {
		margin: 20px;
		font-size: 28px;
	}
	</style>

	<div class="wrap">
		<h2><?php _e('Group Management', 'adrotate'); ?></h2>

		<?php if ($message == 'created') { ?>
			<div id="message" class="updated fade"><p><?php _e('Group created', 'adrotate'); ?></p></div>
		<?php } else if ($message == 'updated') { ?>
			<div id="message" class="updated fade"><p><?php _e('Group updated', 'adrotate'); ?></p></div>
		<?php } else if ($message == 'deleted') { ?>
			<div id="message" class="updated fade"><p><?php _e('Group deleted', 'adrotate'); ?></p></div>
		<?php } else if ($message == 'deleted_banners') { ?>
			<div id="message" class="updated fade"><p><?php _e('Group including it\'s Ads deleted', 'adrotate'); ?></p></div>
		<?php } ?>

		<?php if($wpdb->get_var("SHOW TABLES LIKE '".$wpdb->prefix."adrotate_groups';") AND $wpdb->get_var("SHOW TABLES LIKE '".$wpdb->prefix."adrotate_linkmeta';")) { ?>
			<div class="tablenav">
				<div class="alignleft actions">
					<a class="row-title" href="<?php echo admin_url('/admin.php?page=adrotate-groups&view=manage');?>"><?php _e('Manage', 'adrotate'); ?></a> | 
					<a class="row-title" href="<?php echo admin_url('/admin.php?page=adrotate-groups&view=addnew');?>"><?php _e('Add New', 'adrotate'); ?></a>
					<?php if($group_edit_id) { ?>
					| <a class="row-title" href="<?php echo admin_url('/admin.php?page=adrotate-groups&view=report&group='.$group_edit_id);?>"><?php _e('Report', 'adrotate'); ?></a>
					<?php } ?>
				</div>
			</div>

	    	<?php if ($view == "" OR $view == "manage") { ?>

			<h3><?php _e('Manage Groups', 'adrotate'); ?></h3>

			<form name="groups" id="post" method="post" action="admin.php?page=adrotate-groups">
	
				<div class="tablenav">
					<div class="alignleft">
						<select name="adrotate_action" id="cat" class="postform">
					        <option value=""><?php _e('Bulk Actions', 'adrotate'); ?></option>
					        <option value="group_delete"><?php _e('Delete Group', 'adrotate'); ?></option>
							<option value="group_delete_banners"><?php _e('Delete Group including ads', 'adrotate'); ?></option>
						</select>
						<input onclick="return confirm('<?php _e('You are about to delete a group', 'adrotate'); ?>\n<?php _e('This action can not be undone!', 'adrotate'); ?>\n<?php _e('OK to continue, CANCEL to stop.', 'adrotate'); ?>')" type="submit" id="post-action-submit" name="adrotate_action_submit" value="<?php _e('Go', 'adrotate'); ?>" class="button-secondary" />
					</div>
				</div>
				
			   	<table class="widefat" style="margin-top: .5em">
		  			<thead>
	  				<tr>
						<th class="check-column">&nbsp;</th>
						<th width="5%"><center><?php _e('ID', 'adrotate'); ?></center></th>
						<th><?php _e('Name', 'adrotate'); ?></th>
						<th width="5%"><center><?php _e('Ads', 'adrotate'); ?></center></th>
						<th width="5%"><center><?php _e('Impressions', 'adrotate'); ?></center></th>
						<th width="5%"><center><?php _e('Today', 'adrotate'); ?></center></th>
						<th width="5%"><center><?php _e('Clicks', 'adrotate'); ?></center></th>
						<th width="5%"><center><?php _e('Today', 'adrotate'); ?></center></th>
						<th width="15%"><center><?php _e('Code', 'adrotate'); ?></center></th>
						<th width="8%"><center><?php _e('Fallback', 'adrotate'); ?></center></th>
					</tr>
		  			</thead>
					<tbody>
		  			
					<?php $groups = $wpdb->get_results("SELECT * FROM `".$wpdb->prefix . "adrotate_groups` WHERE `name` != '' ORDER BY `sortorder` ASC, `id` ASC;");
					if ($groups) {
						foreach($groups as $group) {
							$today 			= gmmktime(0, 0, 0, gmdate("n"), gmdate("j"), gmdate("Y"));
							$stats 			= $wpdb->get_row("SELECT SUM(`clicks`) as `clicks`, SUM(`impressions`) as `impressions` FROM `".$wpdb->prefix."adrotate_stats_tracker` WHERE `group` = '$group->id';");
							$stats_today	= $wpdb->get_row("SELECT SUM(`clicks`) as `clicks`, SUM(`impressions`) as `impressions` FROM `".$wpdb->prefix."adrotate_stats_tracker` WHERE `group` = '$group->id' AND `thetime` = '$today';");
	
							// Prevent gaps in display
							if($stats->impressions == 0) 		$stats->impressions 		= 0;
							if($stats->clicks == 0) 			$stats->clicks 				= 0;
							if($stats_today->impressions == 0) 	$stats_today->impressions 	= 0;
							if($stats_today->clicks == 0) 		$stats_today->clicks 		= 0;

							$ads_in_group = $wpdb->get_var("SELECT COUNT(*) FROM `".$wpdb->prefix."adrotate_linkmeta` WHERE `group` = ".$group->id." AND `block` = 0;");
							$class = ('alternate' != $class) ? 'alternate' : ''; ?>
						    <trclass='<?php echo $class; ?>'>
								<th class="check-column"><input type="checkbox" name="groupcheck[]" value="<?php echo $group->id; ?>" /></th>
								<td><center><?php echo $group->id;?></center></td>
								<td><strong><a class="row-title" href="<?php echo admin_url('/admin.php?page=adrotate-groups&view=edit&group='.$group->id);?>" title="<?php _e('Edit', 'adrotate'); ?>"><?php echo $group->name;?></a></strong><br /><a href="<?php echo admin_url('/admin.php?page=adrotate-groups&view=report&group='.$group->id);?>" title="<?php _e('Report', 'adrotate'); ?>"><?php _e('Report', 'adrotate'); ?></a></td>
								<td><center><?php echo $ads_in_group;?></center></td>
								<td><center><?php echo $stats->impressions;?></center></td>
								<td><center><?php echo $stats_today->impressions;?></center></td>
								<td><center><?php echo $stats->clicks;?></center></td>
								<td><center><?php echo $stats_today->clicks;?></center></td>
								<td><center>[adrotate group="<?php echo $group->id; ?>"]</center></td>
								<td><center><?php if($group->fallback == 0) { echo "Not set"; } else { echo $group->fallback; } ?></center></td>
							</tr>
							<?php unset($stats);?>
			 			<?php } ?>
					<?php } else { ?>
					<tr>
						<th class="check-column">&nbsp;</th>
						<td colspan="9"><em><?php _e('No groups created!', 'adrotate'); ?></em></td>
					</tr>
					<?php } ?>
		 			</tbody>
				</table>
			</form>

		   	<?php } else if($view == "addnew" OR $view == "edit") { ?>
		   	
				<?php if(!$group_edit_id) { ?>
				<h3><?php _e('New group', 'adrotate'); ?></h3>
					<?php
					$action = "group_new";
					$query = "SELECT `id` FROM `".$wpdb->prefix."adrotate_groups` WHERE `name` = '' ORDER BY `id` DESC LIMIT 1;";
					$edit_id = $wpdb->get_var($query);
					if($edit_id == 0) {
						$wpdb->query("INSERT INTO `".$wpdb->prefix."adrotate_groups` (`name`, `fallback`) VALUES ('', 0);");
						$edit_id = $wpdb->get_var($query);
					}
					$group_edit_id = $edit_id;
					?>
				<?php } else { ?>
				<h3><?php _e('Edit Group', 'adrotate'); ?></h3>
				<?php 
					$action = "group_edit";
				}

				$edit_group = $wpdb->get_row("SELECT * FROM `".$wpdb->prefix."adrotate_groups` WHERE `id` = '$group_edit_id';");
				$groups		= $wpdb->get_results("SELECT * FROM `".$wpdb->prefix."adrotate_groups` WHERE `name` != '' ORDER BY `id` ASC;"); 
				$ads		= $wpdb->get_results("SELECT * FROM `".$wpdb->prefix."adrotate` WHERE `type` != 'empty' AND `active` = 'yes' ORDER BY `id` ASC;"); 
				$linkmeta	= $wpdb->get_results("SELECT `ad` FROM `".$wpdb->prefix."adrotate_linkmeta` WHERE `group` = '$group_edit_id' AND `block` = 0 AND `user` = 0;");
				foreach($linkmeta as $meta) {
					$meta_array[] = $meta->ad;
				}
				if(!is_array($meta_array)) $meta_array = array();
				?>
	
				<form name="editgroup" id="post" method="post" action="admin.php?page=adrotate-groups">
			    	<input type="hidden" name="adrotate_id" value="<?php echo $edit_group->id;?>" />
			    	<input type="hidden" name="adrotate_action" value="<?php echo $action;?>" />
	
				   	<table class="widefat" style="margin-top: .5em">
	
			  			<thead>
		  				<tr>
							<th colspan="4"><?php _e('Fill in a name as reference and specify a fallback group', 'adrotate'); ?></th>
						</tr>
			  			</thead>
	
						<tbody>
					    <tr>
							<th width="15%"><?php _e('ID:', 'adrotate'); ?></th>
							<td colspan="3"><?php echo $edit_group->id; ?></td>
						</tr>
					    <tr>
							<th width="15%"><?php _e('Name:', 'adrotate'); ?></th>
							<td colspan="3"><input tabindex="1" name="adrotate_groupname" type="text" class="search-input" size="80" value="<?php echo $edit_group->name; ?>" autocomplete="off" /></td>
						</tr>
					    <tr>
							<th><?php _e('Fallback group?', 'adrotate'); ?></th>
							<td colspan="3">
								<select tabindex="2" name="adrotate_fallback">
						        <option value="0"><?php _e('No', 'adrotate'); ?></option>
							<?php if ($groups) { ?>
								<?php foreach($groups as $group) { ?>
							        <option value="<?php echo $group->id;?>" <?php if($edit_group->fallback == $group->id) { echo 'selected'; } ?>><?php echo $group->id;?> - <?php echo $group->name;?></option>
					 			<?php } ?>
							<?php } ?>
								</select> <em><?php _e('You need atleast two groups to use this feature!', 'adrotate'); ?></em>
							</td>
						</tr>
						<?php if($edit_group->name != '') { ?>
				      	<tr>
					        <th><?php _e('This group is in the block(s):', 'adrotate'); ?></th>
					        <td colspan="3"><?php echo adrotate_group_is_in_blocks($edit_group->id); ?></td>
				      	</tr>
						<?php } ?>
				      	<tr>
					        <th><?php _e('Sortorder:', 'adrotate'); ?></th>
					        <td colspan="3">
						        <input tabindex="23" name="adrotate_sortorder" type="text" size="5" class="search-input" autocomplete="off" value="<?php echo $edit_group->sortorder;?>" /> <em><?php _e('For administrative purposes set a sortorder.', 'adrotate'); ?> <?php _e('Leave empty or 0 to skip this. Will default to group id.', 'adrotate'); ?></em>
							</td>
				      	</tr>
						</tbody>
	
						<thead>
						<tr>
							<th colspan="4"><?php _e('Usage', 'adrotate'); ?></th>
						</tr>
						</thead>
		
						<tbody>
				      	<tr>
					        <th width="15%"><?php _e('In a post or page:', 'adrotate'); ?></th>
					        <td width="35%">[adrotate group="<?php echo $edit_group->id; ?>"]</td>
					        <th width="15%"><?php _e('Directly in a theme:', 'adrotate'); ?></th>
					        <td width="35%">&lt;?php echo adrotate_group(<?php echo $edit_group->id; ?>); ?&gt;</td>
				      	</tr>
				      	</tbody>
					</table>
				
			    	<p class="submit">
						<input tabindex="3" type="submit" name="adrotate_group_submit" class="button-primary" value="<?php _e('Save', 'adrotate'); ?>" />
						<a href="admin.php?page=adrotate-groups&view=manage" class="button"><?php _e('Cancel', 'adrotate'); ?></a>
			    	</p>

					<h3><?php _e('Select Ads', 'adrotate'); ?></h3>

				   	<table class="widefat" style="margin-top: .5em">
			  			<thead>
		  				<tr>
							<th colspan="2"><?php _e('Choose the ads to use in this group', 'adrotate'); ?></th>
							<th width="5%"><center><?php _e('Impressions', 'adrotate'); ?></center></th>
							<th width="5%"><center><?php _e('Clicks', 'adrotate'); ?></center></th>
							<th width="5%"><center><?php _e('Weight', 'adrotate'); ?></center></th>
							<th width="15%"><?php _e('Visible until', 'adrotate'); ?></th>
						</tr>
			  			</thead>
	
						<tbody>
						<?php if($ads) {
							foreach($ads as $ad) {
								$stats = $wpdb->get_row("SELECT SUM(`clicks`) as `clicks`, SUM(`impressions`) as `impressions` FROM `".$wpdb->prefix."adrotate_stats_tracker` WHERE `ad` = '$ad->id';");

								// Prevent gaps in display
								if($stats->impressions == 0) 		$stats->impressions 		= 0;
								if($stats->clicks == 0) 			$stats->clicks 				= 0;

								$class = ('alternate' != $class) ? 'alternate' : ''; ?>
							    <tr class='<?php echo $class; ?>'>
									<th class="check-column"><input type="checkbox" name="adselect[]" value="<?php echo $ad->id; ?>" <?php if(in_array($ad->id, $meta_array)) echo "checked"; ?> /></th>
									<td><?php echo $ad->id; ?> - <strong><?php echo $ad->title; ?></strong></td>
									<td><center><?php echo $stats->impressions; ?></center></td>
									<td><center><?php if($ad->tracker == 'Y') { echo $stats->clicks; } else { ?>--<?php } ?></center></td>
									<td><center><?php echo $ad->weight; ?></center></td>
									<td><span style="color: <?php echo adrotate_prepare_color($ad->endshow);?>;"><?php echo date_i18n("F d, Y", $ad->endshow); ?></span></td>
								</tr>
							<?php unset($stats);?>
				 			<?php } ?>
						<?php } else { ?>
						<tr>
							<th class="check-column">&nbsp;</th>
							<td colspan="5"><em><?php _e('No ads created!', 'adrotate'); ?></em></td>
						</tr>
						<?php } ?>
						</tbody>					
			 		</table>

			    	<p class="submit">
						<input tabindex="3" type="submit" name="adrotate_group_submit" class="button-primary" value="<?php _e('Save', 'adrotate'); ?>" />
						<a href="admin.php?page=adrotate-groups&view=manage" class="button"><?php _e('Cancel', 'adrotate'); ?></a>
			    	</p>
	
				</form>

		   	<?php } else if($view == "report") { ?>

				<h3><?php _e('This groups performance', 'adrotate'); ?></h3>

				<?php
					$today 			= gmmktime(0, 0, 0, gmdate("n"), gmdate("j"), gmdate("Y"));
					$title		 	= $wpdb->get_var("SELECT `name` FROM `".$wpdb->prefix."adrotate_groups` WHERE `id` = '$group_edit_id';");
					$stats 			= $wpdb->get_row("SELECT SUM(`clicks`) as `clicks`, SUM(`impressions`) as `impressions` FROM `".$wpdb->prefix."adrotate_stats_tracker` WHERE `group` = '$group_edit_id';");
					$stats_today 	= $wpdb->get_row("SELECT `clicks`, `impressions` FROM `".$wpdb->prefix."adrotate_stats_tracker` WHERE `group` = '$group_edit_id' AND `thetime` = '$today';");

					// Sort out CTR
					if($stats->impressions == 0) $ctrimpressions = 0.001;
						else $ctrimpressions = $stats->impressions;
					if($stats->clicks == 0) $ctrclicks = 0.001;
						else $ctrclicks = $stats->clicks;
					$ctr = round((100/$ctrimpressions)*$ctrclicks, 2);						
	
					// Prevent gaps in display
					if($stats->impressions == 0) 		$stats->impressions 		= 0;
					if($stats->clicks == 0) 			$stats->clicks 				= 0;
					if($stats_today->impressions == 0) 	$stats_today->impressions 	= 0;
					if($stats_today->clicks == 0) 		$stats_today->clicks 		= 0;
			
					if($adrotate_debug['stats'] == true) {
						echo "<p><strong>[DEBUG] Group (all time)</strong><pre>";
						$memory = (memory_get_usage() / 1024 / 1024);
						echo "Memory usage: " . round($memory, 2) ." MB <br />"; 
						$peakmemory = (memory_get_peak_usage() / 1024 / 1024);
						echo "Peak memory usage: " . round($peakmemory, 2) ." MB <br />"; 
						print_r($stats); 
						echo "</pre></p>"; 
						echo "<p><strong>[DEBUG] Group (today)</strong><pre>";
						print_r($stats_today); 
						echo "</pre></p>"; 
					}	
		
				?>
				
		    	<table class="widefat" style="margin-top: .5em">
					<thead>
					<tr>
						<th colspan="5" bgcolor="#DDD"><?php _e('Statistics for', 'adrotate'); ?> '<?php echo $title; ?>'</th>
					</tr>
					</thead>
	
					<tbody>
			      	<tr>
				        <td width="20%"><div class="stats_large"><?php _e('Impressions', 'adrotate'); ?><br /><div class="number_large"><?php echo $stats->impressions; ?></div></div></td>
				        <td width="20%"><div class="stats_large"><?php _e('Clicks', 'adrotate'); ?><br /><div class="number_large"><?php echo $stats->clicks; ?></div></div></td>
				        <td width="20%"><div class="stats_large"><?php _e('Impressions today', 'adrotate'); ?><br /><div class="number_large"><?php echo $stats_today->impressions; ?></div></div></td>
				        <td width="20%"><div class="stats_large"><?php _e('Clicks today', 'adrotate'); ?><br /><div class="number_large"><?php echo $stats_today->clicks; ?></div></div></td>
				        <td width="20%"><div class="stats_large"><?php _e('CTR', 'adrotate'); ?><br /><div class="number_large"><?php echo $ctr.' %'; ?></div></div></td>
			      	</tr>
			      	<tr>
				        <th colspan="5">
				        	<?php
				        	$groupstats = $wpdb->get_results("SELECT `thetime`, SUM(`clicks`) as `clicks`, SUM(`impressions`) as `impressions` FROM `".$wpdb->prefix."adrotate_stats_tracker` WHERE `group` = '$group_edit_id' GROUP BY `thetime` DESC LIMIT 21;");
							if($groupstats) {
							
								$groupstats = array_reverse($groupstats);

								if($adrotate_debug['stats'] == true) { 
									echo "<p><strong>[DEBUG] 21 days (Or as much as is available) Group stats</strong><pre>"; 
									print_r($groupstats); 
									echo "</pre></p>"; 
								}

								foreach($groupstats as $result) {
									if($result->clicks == null) $result->clicks = '0';
									if($result->impressions == null) $result->impressions = '0';
									
									$clicks_array[date_i18n("d M", $result->thetime)] = $result->clicks;
									$impressions_array[date_i18n("d M", $result->thetime)] = $result->impressions;
								}
								
								if($adrotate_debug['stats'] == true) { 
									echo "<p><strong>[DEBUG] Found clicks as presented to PHPGraphLib</strong><pre>"; 
									print_r($clicks_array); 
									echo "</pre></p>"; 
									echo "<p><strong>[DEBUG] Found impressions as presented to PHPGraphLib</strong><pre>"; 
									print_r($impressions_array); 
									echo "</pre></p>"; 
								}

								$impressions_title = urlencode(serialize(__('Impressions over the past 21 days', 'adrotate')));
								$impressions_array = urlencode(serialize($impressions_array));
								echo "<img src=\"".plugins_url("/library/graph_group.php?title=$impressions_title&data=$impressions_array", __FILE__)."\" />";

								$clicks_title = urlencode(serialize(__('Clicks over the past 21 days', 'adrotate')));
								$clicks_array = urlencode(serialize($clicks_array));
								echo "<img src=\"".plugins_url("/library/graph_group.php?title=$clicks_title&data=$clicks_array", __FILE__)."\" />";
							} else {
								_e('No data to show!', 'adrotate');
							} 
							?>
				        </th>
			      	</tr>
			      	<tr>
				        <td colspan="5"><b><?php _e('Note:', 'adrotate'); ?></b> <em><?php _e('All statistics are indicative. They do not nessesarily reflect results counted by other parties.', 'adrotate'); ?></em></td>
			      	</tr>
					</tbody>
				</table>

		   	<?php } ?>
	
			<br class="clear" />
		
			<?php adrotate_credits(); ?>

		<?php } else { ?>
			<?php echo adrotate_error('db_error'); ?>
		<?php }	?>
		<br class="clear" />
	</div>
<?php
}

/*-------------------------------------------------------------
 Name:      adrotate_manage_block

 Purpose:   Manage blocks of ads
 Receive:   -none-
 Return:    -none-
-------------------------------------------------------------*/
function adrotate_manage_block() {
	global $wpdb, $adrotate_debug;

	$message 		= $_GET['message'];
	$view 			= $_GET['view'];
	$block_edit_id 	= $_GET['block'];
	?>

	<style type="text/css" media="screen">
	.stats_large {
		display: block;
		margin-bottom: 10px;
		margin-top: 10px;
		text-align: center;
		font-weight: bold;
	}
	.number_large {
		margin: 20px;
		font-size: 28px;
	}
	</style>

	<div class="wrap">
		<h2><?php _e('Block Management', 'adrotate'); ?></h2>

		<?php if ($message == 'created') { ?>
			<div id="message" class="updated fade"><p><?php _e('Block created', 'adrotate'); ?></p></div>
		<?php } else if ($message == 'updated') { ?>
			<div id="message" class="updated fade"><p><?php _e('Block updated', 'adrotate'); ?></p></div>
		<?php } else if ($message == 'deleted') { ?>
			<div id="message" class="updated fade"><p><?php _e('Block deleted', 'adrotate'); ?></p></div>
		<?php } ?>

		<?php if($wpdb->get_var("SHOW TABLES LIKE '".$wpdb->prefix."adrotate_blocks';") AND $wpdb->get_var("SHOW TABLES LIKE '".$wpdb->prefix."adrotate_linkmeta';")) { ?>
			<div class="tablenav">
				<div class="alignleft actions">
					<a class="row-title" href="<?php echo admin_url('/admin.php?page=adrotate-blocks&view=manage');?>"><?php _e('Manage', 'adrotate'); ?></a> 
					| <a class="row-title" href="<?php echo admin_url('/admin.php?page=adrotate-blocks&view=addnew');?>"><?php _e('Add New', 'adrotate'); ?></a> 
					<?php if($block_edit_id) { ?>
					| <a class="row-title" href="<?php echo admin_url('/admin.php?page=adrotate-blocks&view=report&block='.$block_edit_id);?>"><?php _e('Report', 'adrotate'); ?></a> 
					<?php } ?>
				</div>
			</div>

	    	<?php if ($view == "" OR $view == "manage") { ?>

			<h3><?php _e('Manage Blocks', 'adrotate'); ?></h3>

			<form name="blocks" id="post" method="post" action="admin.php?page=adrotate-blocks">
	
				<div class="tablenav">
					<div class="alignleft">
						<select name="adrotate_action" id="cat" class="postform">
					        <option value=""><?php _e('Bulk Actions', 'adrotate'); ?></option>
					        <option value="block_delete"><?php _e('Delete Block(s)', 'adrotate'); ?></option>
						</select>
						<input onclick="return confirm('<?php _e('You are about to delete a block', 'adrotate'); ?>\n<?php _e('This action can not be undone!', 'adrotate'); ?>\n<?php _e('OK to continue, CANCEL to stop.', 'adrotate'); ?>')" type="submit" id="post-action-submit" name="adrotate_action_submit" value="<?php _e('Go', 'adrotate'); ?>" class="button-secondary" />
					</div>
				</div>
	
			   	<table class="widefat" style="margin-top: .5em">

		  			<thead>
	  				<tr>
						<th class="check-column">&nbsp;</th>
						<th width="5%"><center><?php _e('ID', 'adrotate'); ?></center></th>
						<th><?php _e('Name', 'adrotate'); ?></th>
						<th width="5%"><center><?php _e('Groups', 'adrotate'); ?></center></th>
						<th width="5%"><center><?php _e('Impressions', 'adrotate'); ?></center></th>
						<th width="5%"><center><?php _e('Today', 'adrotate'); ?></center></th>
						<th width="5%"><center><?php _e('Clicks', 'adrotate'); ?></center></th>
						<th width="5%"><center><?php _e('Today', 'adrotate'); ?></center></th>
						<th width="15%"><center><?php _e('Code', 'adrotate'); ?></center></th>
					</tr>
		  			</thead>

					<tbody>
		  			
					<?php $blocks = $wpdb->get_results("SELECT * FROM `".$wpdb->prefix . "adrotate_blocks` WHERE `name` != '' ORDER BY `sortorder` ASC, `id` ASC;");
					if ($blocks) {
						foreach($blocks as $block) {
							$today 			= gmmktime(0, 0, 0, gmdate("n"), gmdate("j"), gmdate("Y"));
							$stats 			= $wpdb->get_row("SELECT SUM(`clicks`) as `clicks`, SUM(`impressions`) as `impressions` FROM `".$wpdb->prefix."adrotate_stats_tracker` WHERE `block` = '$block->id';");
							$stats_today	= $wpdb->get_row("SELECT SUM(`clicks`) as `clicks`, SUM(`impressions`) as `impressions` FROM `".$wpdb->prefix."adrotate_stats_tracker` WHERE `block` = '$block->id' AND `thetime` = '$today';");
	
							// Prevent gaps in display
							if($stats->impressions == 0) 		$stats->impressions 		= 0;
							if($stats->clicks == 0) 			$stats->clicks 				= 0;
							if($stats_today->impressions == 0) 	$stats_today->impressions 	= 0;
							if($stats_today->clicks == 0) 		$stats_today->clicks 		= 0;

							$groups_in_block = $wpdb->get_var("SELECT COUNT(*) FROM `".$wpdb->prefix."adrotate_linkmeta` WHERE `block` = ".$block->id.";");
							$class = ('alternate' != $class) ? 'alternate' : ''; ?>
						    <tr class='<?php echo $class; ?>'>
								<th class="check-column"><input type="checkbox" name="blockcheck[]" value="<?php echo $block->id; ?>" /></th>
								<td><center><?php echo $block->id;?></center></td>
								<td><strong><a class="row-title" href="<?php echo admin_url('/admin.php?page=adrotate-blocks&view=edit&block='.$block->id);?>" title="<?php _e('Edit', 'adrotate'); ?>"><?php echo $block->name;?></a></strong><br /><a href="<?php echo admin_url('/admin.php?page=adrotate-blocks&view=report&block='.$block->id);?>" title="<?php _e('Report', 'adrotate'); ?>"><?php _e('Report', 'adrotate'); ?></a></td>
								<td><center><?php echo $groups_in_block;?></center></td>
								<td><center><?php echo $stats->impressions;?></center></td>
								<td><center><?php echo $stats_today->impressions;?></center></td>
								<td><center><?php echo $stats->clicks;?></center></td>
								<td><center><?php echo $stats_today->clicks;?></center></td>
								<td><center>[adrotate block="<?php echo $block->id; ?>"]</center></td>
							</tr>
						<?php unset($stats);?>
			 			<?php } ?>
					<?php } else { ?>
					<tr>
						<th class="check-column">&nbsp;</th>
						<td colspan="8"><em><?php _e('No blocks created yet!', 'adrotate'); ?></em></td>
					</tr>
					<?php } ?>
		 			</tbody>

				</table>
			</form>

		   	<?php } else if($view == "addnew" OR $view == "edit") { ?>
		   	
				<?php if(!$block_edit_id) { ?>
				<h3><?php _e('New Block', 'adrotate'); ?></h3>
					<?php
					$action = "block_new";
					$query = "SELECT `id` FROM `".$wpdb->prefix."adrotate_blocks` WHERE `name` = '' ORDER BY `id` DESC LIMIT 1;";
					$edit_id = $wpdb->get_var($query);
					if($edit_id == 0) {
						$wpdb->query("INSERT INTO `".$wpdb->prefix."adrotate_blocks` (`name`, `adcount`, `columns`, `wrapper_before`, `wrapper_after`) VALUES ('', 0, 0, '', '');");
						$edit_id = $wpdb->get_var($query);
					}
					$block_edit_id = $edit_id;
					?>
				<?php } else { ?>
				<h3><?php _e('Edit Block', 'adrotate'); ?></h3>
				<?php 
					$action = "block_edit";
				} 
				
				$edit_block = $wpdb->get_row("SELECT * FROM `".$wpdb->prefix."adrotate_blocks` WHERE `id` = '$block_edit_id';");
				$groups		= $wpdb->get_results("SELECT * FROM `".$wpdb->prefix."adrotate_groups` WHERE `name` != '' ORDER BY `id` ASC;"); 
				$templates	= $wpdb->get_results("SELECT `id`, `name` FROM `".$wpdb->prefix."adrotate_templates` ORDER BY `id` ASC;"); 
				$linkmeta	= $wpdb->get_results("SELECT `group` FROM `".$wpdb->prefix."adrotate_linkmeta` WHERE `ad` = 0 AND `block` = '$block_edit_id' AND `user` = 0;");
				foreach($linkmeta as $meta) {
					$meta_array[] = $meta->group;
				}
				if(!is_array($meta_array)) $meta_array = array();
				?>

				<form name="editblock" id="post" method="post" action="admin.php?page=adrotate-blocks">
			    	<input type="hidden" name="adrotate_id" value="<?php echo $edit_block->id;?>" />
			    	<input type="hidden" name="adrotate_action" value="<?php echo $action;?>" />

				   	<table class="widefat" style="margin-top: .5em">
				   	
			  			<thead>
		  				<tr>
							<th colspan="4"><?php _e('The basics (Required)', 'adrotate'); ?></th>
						</tr>
			  			</thead>
			  			
						<tbody>
					    <tr>
							<th width="15%"><?php _e('ID:', 'adrotate'); ?></th>
							<td colspan="3"><?php echo $edit_block->id; ?></td>
						</tr>
					    <tr>
							<th width="15%"><?php _e('Name / Reference:', 'adrotate'); ?></th>
							<td colspan="3"><input tabindex="1" name="adrotate_blockname" type="text" class="search-input" size="80" value="<?php echo $edit_block->name; ?>" autocomplete="off" /></td>
						</tr>
					    <tr>
							<th width="15%"><?php _e('Format:', 'adrotate'); ?></th>
							<td colspan="3">
								<input tabindex="2" name="adrotate_adcount" type="text" class="search-input" size="5" value="<?php echo $edit_block->adcount; ?>" autocomplete="off" /> <?php _e('ads, and', 'adrotate'); ?> <input tabindex="3" name="adrotate_columns" type="text" class="search-input" size="5" value="<?php echo $edit_block->columns; ?>" autocomplete="off" /> <?php _e('columns. (Example: 4 ads and 2 columns, makes a square block of 2x2 ads.)', 'adrotate'); ?>
							</td>
						</tr>
						</tbody>
	
			  			<thead>
		  				<tr>
							<th colspan="4"><?php _e('Wrapper code (Optional) - Wraps around each ad to facilitate easy margins, paddings or borders around ads', 'adrotate'); ?></th>
						</tr>
			  			</thead>
			  			
						<tbody>
					    <tr>
							<th valign="top"><?php _e('Before ad', 'adrotate'); ?></strong></th>
							<td colspan="2"><textarea tabindex="4" name="adrotate_wrapper_before" cols="65" rows="3"><?php echo $edit_block->wrapper_before; ?></textarea></td>
							<td>
						        <p><strong><?php _e('Example:', 'adrotate'); ?></strong></p>
						        <p><em>&lt;span style="margin: 2px;"&gt;</em></p>
							</td>
						</tr>
					    <tr>
							<th valign="top"><?php _e('After ad', 'adrotate'); ?></strong></th>
							<td colspan="2"><textarea tabindex="5" name="adrotate_wrapper_after" cols="65" rows="3"><?php echo $edit_block->wrapper_after; ?></textarea></td>
							<td>
								<p><strong><?php _e('Example:', 'adrotate'); ?></strong></p>
								<p><em>&lt;/span&gt;</em></p>
							</td>
						</tr>
				      	<tr>
					        <th><?php _e('Sortorder:', 'adrotate'); ?></th>
					        <td colspan="3">
						        <input tabindex="23" name="adrotate_sortorder" type="text" size="5" class="search-input" autocomplete="off" value="<?php echo $edit_block->sortorder;?>" /> <em><?php _e('For administrative purposes set a sortorder.', 'adrotate'); ?> <?php _e('Leave empty or 0 to skip this. Will default to block id.', 'adrotate'); ?></em>
							</td>
				      	</tr>
						</tbody>
	
						<thead>
						<tr valign="top">
							<th colspan="4"><?php _e('Usage', 'adrotate'); ?></th>
						</tr>
						</thead>
		
						<tbody>
				      	<tr>
					        <th width="15%"><?php _e('In a post or page:', 'adrotate'); ?></th>
					        <td>[adrotate block="<?php echo $edit_block->id; ?>"]</td>
					        <th width="15%"><?php _e('Directly in a theme:', 'adrotate'); ?></th>
					        <td width="35%">&lt;?php echo adrotate_block(<?php echo $edit_block->id; ?>); ?&gt;</td>
				      	</tr>
				      	</tbody>
					</table>
					
			    	<p class="submit">
						<input tabindex="6" type="submit" name="adrotate_block_submit" class="button-primary" value="<?php _e('Save', 'adrotate'); ?>" />
						<a href="admin.php?page=adrotate-blocks&view=manage" class="button"><?php _e('Cancel', 'adrotate'); ?></a>
			    	</p>

					<h3><?php _e('Select Groups', 'adrotate'); ?></h3>

				   	<table class="widefat" style="margin-top: .5em">
			  			<thead>
		  				<tr>
							<th colspan="3"><?php _e('Choose the groups to use in this block', 'adrotate'); ?></th>
						</tr>
			  			</thead>
	
						<tbody>
						<?php if($groups) {
							foreach($groups as $group) {
								$ads_in_group = $wpdb->get_var("SELECT COUNT(*) FROM `".$wpdb->prefix."adrotate_linkmeta` WHERE `group` = ".$group->id." AND `block` = 0;");
								$class = ('alternate' != $class) ? 'alternate' : ''; ?>
							    <tr class='<?php echo $class; ?>'>
									<th class="check-column"><input type="checkbox" name="groupselect[]" value="<?php echo $group->id; ?>" <?php if(in_array($group->id, $meta_array)) echo "checked"; ?> /></th>
									<td><?php echo $group->id; ?> - <strong><?php echo $group->name; ?></strong></td>
									<td width="10%"><?php echo $ads_in_group; ?> <?php _e('Ads', 'adrotate'); ?></td>
								</tr>
				 			<?php } ?>
						<?php } else { ?>
						<tr>
							<th class="check-column">&nbsp;</th>
							<td colspan="2"><em><?php _e('No groups created!', 'adrotate'); ?></em></td>
						</tr>
						<?php } ?>
						</tbody>					
					</table>
				
			    	<p class="submit">
						<input tabindex="6" type="submit" name="adrotate_block_submit" class="button-primary" value="<?php _e('Save', 'adrotate'); ?>" />
						<a href="admin.php?page=adrotate-blocks&view=manage" class="button"><?php _e('Cancel', 'adrotate'); ?></a>
			    	</p>
	
				</form>
	
		   	<?php } else if($view == "report") { ?>

				<h3><?php _e('This blocks performance', 'adrotate'); ?></h3>

				<?php
					$today 			= gmmktime(0, 0, 0, gmdate("n"), gmdate("j"), gmdate("Y"));
					$title		 	= $wpdb->get_var("SELECT `name` FROM `".$wpdb->prefix."adrotate_blocks` WHERE `id` = '$block_edit_id';");
					$stats 			= $wpdb->get_row("SELECT SUM(`clicks`) as `clicks`, SUM(`impressions`) as `impressions` FROM `".$wpdb->prefix."adrotate_stats_tracker` WHERE `block` = '$block_edit_id';");
					$stats_today 	= $wpdb->get_row("SELECT `clicks`, `impressions` FROM `".$wpdb->prefix."adrotate_stats_tracker` WHERE `block` = '$block_edit_id' AND `thetime` = '$today';");

					// Sort out CTR
					if($stats->impressions == 0) $ctrimpressions = 0.001;
						else $ctrimpressions = $stats->impressions;
					if($stats->clicks == 0) $ctrclicks = 0.001;
						else $ctrclicks = $stats->clicks;
					$ctr = round((100/$ctrimpressions)*$ctrclicks,2);						
	
					// Prevent gaps in display
					if($stats->impressions == 0) 		$stats->impressions 		= 0;
					if($stats->clicks == 0) 			$stats->clicks 				= 0;
					if($stats_today->impressions == 0) 	$stats_today->impressions 	= 0;
					if($stats_today->clicks == 0) 		$stats_today->clicks 		= 0;
			
					if($adrotate_debug['stats'] == true) {
						echo "<p><strong>[DEBUG] Block (all time)</strong><pre>";
						$memory = (memory_get_usage() / 1024 / 1024);
						echo "Memory usage: " . round($memory, 2) ." MB <br />"; 
						$peakmemory = (memory_get_peak_usage() / 1024 / 1024);
						echo "Peak memory usage: " . round($peakmemory, 2) ." MB <br />"; 
						print_r($stats); 
						echo "</pre></p>"; 
						echo "<p><strong>[DEBUG] Block (today)</strong><pre>";
						print_r($stats_today); 
						echo "</pre></p>"; 
					}	
		
				?>

		    	<table class="widefat" style="margin-top: .5em">
					<thead>
					<tr>
						<th colspan="5"><?php _e('Statistics for', 'adrotate'); ?> '<?php echo $title; ?>'</th>
					</tr>
					</thead>
	
					<tbody>
			      	<tr>
				        <td width="20%"><div class="stats_large"><?php _e('Impressions', 'adrotate'); ?><br /><div class="number_large"><?php echo $stats->impressions; ?></div></div></td>
				        <td width="20%"><div class="stats_large"><?php _e('Clicks', 'adrotate'); ?><br /><div class="number_large"><?php echo $stats->clicks; ?></div></div></td>
				        <td width="20%"><div class="stats_large"><?php _e('Impressions today', 'adrotate'); ?><br /><div class="number_large"><?php echo $stats_today->impressions; ?></div></div></td>
				        <td width="20%"><div class="stats_large"><?php _e('Clicks today', 'adrotate'); ?><br /><div class="number_large"><?php echo $stats_today->clicks; ?></div></div></td>
				        <td width="20%"><div class="stats_large"><?php _e('CTR', 'adrotate'); ?><br /><div class="number_large"><?php echo $ctr.' %'; ?></div></div></td>
			      	</tr>
			      	<tr>
				        <th colspan="5">
				        	<?php
				        	$blockstats = $wpdb->get_results("SELECT `thetime`, SUM(`clicks`) as `clicks`, SUM(`impressions`) as `impressions` FROM `".$wpdb->prefix."adrotate_stats_tracker` WHERE `block` = '$block_edit_id' GROUP BY `thetime` DESC LIMIT 21;");
							if($blockstats) {
								
								$blockstats = array_reverse($blockstats);
								
								if($adrotate_debug['stats'] == true) { 
									echo "<p><strong>[DEBUG] 21 days (Or as much as is available) Block stats</strong><pre>"; 
									print_r($blockstats); 
									echo "</pre></p>"; 
								}

								foreach($blockstats as $result) {
									if($result->clicks == null) $result->clicks = '0';
									if($result->impressions == null) $result->impressions = '0';
									
									$clicks_array[date_i18n("d M", $result->thetime)] = $result->clicks;
									$impressions_array[date_i18n("d M", $result->thetime)] = $result->impressions;
								}
								
								if($adrotate_debug['stats'] == true) { 
									echo "<p><strong>[DEBUG] Found clicks as presented to PHPGraphLib</strong><pre>"; 
									print_r($clicks_array); 
									echo "</pre></p>"; 
									echo "<p><strong>[DEBUG] Found impressions as presented to PHPGraphLib</strong><pre>"; 
									print_r($impressions_array); 
									echo "</pre></p>"; 
								}

								$impressions_title = urlencode(serialize(__('Impressions over the past 21 days', 'adrotate')));
								$impressions_array = urlencode(serialize($impressions_array));
								echo "<img src=\"".plugins_url("/library/graph_block.php?title=$impressions_title&data=$impressions_array", __FILE__)."\" />";

								$clicks_title = urlencode(serialize(__('Clicks over the past 21 days', 'adrotate')));
								$clicks_array = urlencode(serialize($clicks_array));
								echo "<img src=\"".plugins_url("/library/graph_block.php?title=$clicks_title&data=$clicks_array", __FILE__)."\" />";
							} else {
								_e('No data to show!', 'adrotate');
							} 
							?>
				        </th>
			      	</tr>
			      	<tr>
				        <td colspan="5"><b><?php _e('Note:', 'adrotate'); ?></b> <em><?php _e('All statistics are indicative. They do not nessesarily reflect results counted by other parties.', 'adrotate'); ?></em></td>
			      	</tr>
					</tbody>
				</table>

		   	<?php } ?>
	
			<br class="clear" />
		
			<?php adrotate_credits(); ?>

		<?php } else { ?>
			<?php echo adrotate_error('db_error'); ?>
		<?php }	?>
		<br class="clear" />
	</div>
<?php
}

/*-------------------------------------------------------------
 Name:      adrotate_advertiser_report

 Purpose:   User statistics page
 Receive:   -none-
 Return:    -none-
-------------------------------------------------------------*/
function adrotate_advertiser_report() {
	global $wpdb, $current_user, $adrotate_config, $adrotate_debug;
	
	get_currentuserinfo();
	
	$message 		= $_GET['message'];
	$view 			= $_GET['view'];
	$request		= $_GET['request'];
	$request_id		= $_GET['id'];
	$now 			= current_time('timestamp');
	$in2days 		= $now + 172800;
?>
	<div class="wrap">
	  	<h2><?php _e('Advertiser Report', 'adrotate'); ?></h2>

		<?php if ($message == 'mail_sent') { ?>
			<div id="message" class="updated fade"><p><?php _e('Your message has been sent', 'adrotate'); ?></p></div>
		<?php } ?>

		<?php if($view == "" OR $view == "stats") {
			$user_has_ads = $wpdb->get_var("SELECT COUNT(*) FROM `".$wpdb->prefix."adrotate_linkmeta` WHERE `group` = 0 AND `block` = 0 AND `user` = ".$current_user->ID.";");

			if($user_has_ads > 0) {
				$result = adrotate_prepare_advertiser_report($current_user->ID); 
				
				if($result['total_impressions'] > 0 AND $result['total_clicks'] > 0) {
					$ctr = round((100/$result['total_impressions'])*$result['total_clicks'], 2);
				} else {
					$ctr = 0;
				}
		?>
	
				<h4><?php _e('Your ads', 'adrotate'); ?></h4>
				
				<table class="widefat" style="margin-top: .5em">
					<thead>
						<tr>
						<th width="2%"><center><?php _e('ID', 'adrotate'); ?></center></th>
						<th width="13%"><?php _e('Show from', 'adrotate'); ?></th>
						<th width="13%"><?php _e('Show until', 'adrotate'); ?></th>
						<th><?php _e('Title', 'adrotate'); ?></th>
						<th width="5%"><center><?php _e('Impressions', 'adrotate'); ?></center></th>
						<th width="5%"><center><?php _e('Today', 'adrotate'); ?></center></th>
						<th width="5%"><center><?php _e('Clicks', 'adrotate'); ?></center></th>
						<th width="5%"><center><?php _e('Today', 'adrotate'); ?></center></th>
						<th width="5%"><center><?php _e('CTR', 'adrotate'); ?></center></th>
						<th width="15%"><?php _e('Contact', 'adrotate'); ?></th>
					</tr>
					</thead>
					
					<tbody>
				<?php
				if($result['ads']) {
					foreach($result['ads'] as $ad) {
						$class 			= ('alternate' != $class) ? 'alternate' : '';
						$expiredclass 	= ($ad['endshow'] <= $now OR $ad['endshow'] <= $in2days) ? ' error' : '';
				?>
					    <tr id='banner-<?php echo $ad['id']; ?>' class='<?php echo $class.$expiredclass; ?>'>
							<td><center><?php echo $ad['id'];?></center></td>
							<td><?php echo date_i18n("F d, Y", $ad['startshow']);?></td>
							<td><span style="color: <?php echo adrotate_prepare_color($ad['endshow']);?>;"><?php echo date_i18n("F d, Y", $ad['endshow']);?></span></td>
							<th><strong><?php echo stripslashes(html_entity_decode($ad['title']));?></strong></th>
							<td><center><?php echo $ad['impressions'];?></center></td>
							<td><center><?php echo $ad['impressions_today'];?></center></td>
							<td><center><?php echo $ad['clicks'];?></center></td>
							<td><center><?php echo $ad['clicks_today'];?></center></td>
							<?php if($ad['impressions'] == 0) $ad['impressions'] = 1; ?>
							<td><center><?php echo round((100/$ad['impressions']) * $ad['clicks'],2); ?> %</center></td>
							<td><a href="admin.php?page=adrotate-advertiser-report&view=message&request=renew&id=<?php echo $ad['id']; ?>"><?php _e('Renew', 'adrotate'); ?></a> - <a href="admin.php?page=adrotate-advertiser-report&view=message&request=remove&id=<?php echo $ad['id']; ?>"><?php _e('Remove', 'adrotate'); ?></a> - <a href="admin.php?page=adrotate-advertiser-report&view=message&request=other&id=<?php echo $ad['id']; ?>"><?php _e('Other', 'adrotate'); ?></a></td>
						</tr>
						<?php } ?>
				<?php } else { ?>
					<tr id='no-ads'>
						<th class="check-column">&nbsp;</th>
						<td colspan="10"><em><?php _e('No ads to show!', 'adrotate'); ?> <a href="admin.php?page=adrotate-advertiser-report&view=message&request=issue"><?php _e('Contact your publisher', 'adrotate'); ?></a>.</em></td>
					</tr>
				<?php } ?>
					</tbody>
				</table>

				<h4><?php _e('Summary', 'adrotate'); ?></h4>
				
				<table class="widefat" style="margin-top: .5em">					

					<thead>
					<tr>
						<th colspan="2"><?php _e('Overall statistics', 'adrotate'); ?></th>
						<th><?php _e('The last 8 clicks in the past 24 hours', 'adrotate'); ?></th>
					</tr>
					</thead>
					
					<tbody>

					<?php if($adrotate_debug['userstats'] == true) { ?>
					<tr>
						<td colspan="3">
							<?php 
							echo "<p><strong>User Report</strong><pre>"; 
							print_r($result); 
							echo "</pre></p>"; 
							?>
						</td>
					</tr>
					<?php } ?>
		
				    <tr>
						<th width="10%"><?php _e('General', 'adrotate'); ?></th>
						<td width="40%"><?php echo $result['ad_amount']; ?> <?php _e('ads, sharing a total of', 'adrotate'); ?> <?php echo $result['total_impressions']; ?> <?php _e('impressions.', 'adrotate'); ?></td>
						<td rowspan="5" style="border-left:1px #EEE solid;">
						<?php 
						if($result['last_clicks']) {
							foreach($result['last_clicks'] as $last) {
								$bannertitle = $wpdb->get_var("SELECT `title` FROM `".$wpdb->prefix."adrotate` WHERE `id` = '$last[bannerid]'");
								echo '<strong>'.date_i18n('d-m-Y', $last['timer']) .'</strong> - '. $bannertitle .'<br />';
							}
						} else {
							echo '<em>'.__('No recent clicks', 'adrotate').'</em>';
						} ?>
						</td>
					</tr>
				    <tr>
						<th><?php _e('The best', 'adrotate'); ?></th>
						<td><?php if($result['thebest']) {?>'<?php echo $result['thebest']['title']; ?>' <?php _e('with', 'adrotate'); ?> <?php echo $result['thebest']['clicks']; ?> <?php _e('clicks.', 'adrotate'); ?><?php } else { ?><?php _e('No ad stands out at this time.', 'adrotate'); ?><?php } ?></td>
					</tr>
				    <tr>
						<th><?php _e('The worst', 'adrotate'); ?></th>
						<td><?php if($result['theworst']) {?>'<?php echo $result['theworst']['title']; ?>' <?php _e('with', 'adrotate'); ?> <?php echo $result['theworst']['clicks']; ?> <?php _e('clicks.', 'adrotate'); ?><?php } else { ?><?php _e('All ads seem equally bad.', 'adrotate'); ?><?php } ?></td>
					</tr>
				    <tr>
						<th><?php _e('Average on all ads', 'adrotate'); ?></th>
						<td><?php echo $result['total_clicks']; ?> <?php _e('clicks.', 'adrotate'); ?></td>
					</tr>
				    <tr>
						<th><?php _e('Click-Through-Rate', 'adrotate'); ?></th>
						<td><?php echo $ctr; ?>%, <?php _e('based on', 'adrotate'); ?> <?php echo $result['total_impressions']; ?> <?php _e('impressions and', 'adrotate'); ?> <?php echo $result['total_clicks']; ?> <?php _e('clicks.', 'adrotate'); ?></td>
					</tr>
			      	<tr>
				        <th colspan="3">
				        	<?php
				        	$adstats = $wpdb->get_results("SELECT `thetime`, SUM(`clicks`) as `clicks`, SUM(`impressions`) as `impressions` FROM `".$wpdb->prefix."adrotate_stats_tracker`, `".$wpdb->prefix."adrotate_linkmeta` WHERE `".$wpdb->prefix."adrotate_stats_tracker`.`ad` = `".$wpdb->prefix."adrotate_linkmeta`.`ad` AND `".$wpdb->prefix."adrotate_linkmeta`.`user` = $current_user->ID GROUP BY `thetime` DESC LIMIT 21;");
		        			
							if($adstats) {
								
								$adstats = array_reverse($adstats);
		
								if($adrotate_debug['userstats'] == true) { 
									echo "<p><strong>[DEBUG] 21 days (Or as much as is available) Ad stats</strong><pre>"; 
									print_r($adstats); 
									echo "</pre></p>"; 
								}
		
								foreach($adstats as $stat) {
									if($stat->clicks == null) $stat->clicks = '0';
									if($stat->impressions == null) $stat->impressions = '0';
								
									$clicks_array[date_i18n("M d", $stat->thetime)] = $stat->clicks;
									$impressions_array[date_i18n("M d", $stat->thetime)] = $stat->impressions;
								}
			
								if($adrotate_debug['userstats'] == true) { 
									echo "<p><strong>[DEBUG] Found clicks as presented to PHPGraphLib</strong><pre>"; 
									print_r($clicks_array); 
									echo "</pre></p>"; 
									echo "<p><strong>[DEBUG] Found impressions as presented to PHPGraphLib</strong><pre>"; 
									print_r($impressions_array); 
									echo "</pre></p>"; 
								}
			
								$impressions_title = urlencode(serialize(__('Impressions of all your ads over the past 21 days', 'adrotate')));
								$impressions_array = urlencode(serialize($impressions_array));
								echo "<img src=\"".plugins_url("/library/graph_all_ads.php?title=$impressions_title&data=$impressions_array", __FILE__)."\" />";

								$clicks_title = urlencode(serialize(__('Clicks of all your ads over the past 21 days', 'adrotate')));
								$clicks_array = urlencode(serialize($clicks_array));
								echo "<img src=\"".plugins_url("/library/graph_all_ads.php?title=$clicks_title&data=$clicks_array", __FILE__)."\" />";
							} else {
								_e('No data to show!');
							} 
							?>
				        </th>
			      	</tr>
					</tbody>
				</table>
				
			<?php } else { ?>
				<table class="widefat" style="margin-top: .5em">
					<thead>
						<tr>
							<th><?php _e('Notice', 'adrotate'); ?></th>
						</tr>
					</thead>
					<tbody>
					    <tr>
							<td><?php _e('No ads for user. If you feel this to be in error please', 'adrotate'); ?> <a href="admin.php?page=adrotate-advertiser-report&view=message&request=issue"><?php _e('contact the site administrator', 'adrotate'); ?></a>.</td>
						</tr>
					</tbody>
				</table>
			<?php } ?>
			
		<?php } else if($view == "message") { ?>
			
			<?php
			if($request == "renew") {
				$request_name = __('Renewal of');
				$example = __('- I\'d want my ad renewed for 1 year. Quote me!').'<br />'.__('- Renew my ad, but i want the weight set higher.');
			} else if($request == "remove") {
				$request_name = __('Removal of');
				$example = __('- This ad doesn\'t perform, please remove it.').'<br />'.__('- The budget is spent, please remove the ad when it expires.');
			} else if($request == "other") {
				$request_name = __('About');
				$example = __('- The ad is not in the right place. I\'d like....').'<br />'.__('- This ad works great for me!!');
			} else if($request == "issue") {
				$request_name = __('Complaint or problem');
				$example = __('- My ads do not show, what\'s going on?').'<br />'.__('- Why can\'t i see any clicks?');
			}
	
			$user = get_userdata($user->ID); 
			if(strlen($user->first_name) < 1) $firstname = $user->user_login;
				else $firstname = $user->first_name;
			if(strlen($user->last_name) < 1) $lastname = ''; 
				else $lastname = $user->last_name;
			if(strlen($user->user_email) < 1) $email = __('No address specified'); 
				else $email = $user->user_email;
			?>
			<form name="request" id="post" method="post" action="admin.php?page=adrotate-advertiser-report">
		    	<input type="hidden" name="adrotate_id" value="<?php echo $request_id;?>" />
		    	<input type="hidden" name="adrotate_request" value="<?php echo $request;?>" />
		    	<input type="hidden" name="adrotate_username" value="<?php echo $firstname." ".$lastname;?>" />
		    	<input type="hidden" name="adrotate_email" value="<?php echo $email;?>" />

				<h4><?php _e('Contact your Publisher', 'adrotate'); ?></h4>

				<table class="widefat" style="margin-top: .5em">
					<thead>
						<tr>
							<th colspan="3"><?php _e('Put in a request for renewal, removal or report an issue with this ad.', 'adrotate'); ?></th>
						</tr>
					</thead>
					<tbody>
					    <tr>
							<th width="15%"><?php _e('Subject', 'adrotate'); ?></th>
							<td colspan="2">
								<?php
								if($request == "issue") {
									echo $request_name;
								} else {
									echo $request_name." ".__('ad')." ".$request_id;
								}
								?>
							</td>
						</tr>
					    <tr>
							<td valign="top"><p><strong><?php _e('Short message/Reason', 'adrotate'); ?></strong></p></td>
							<td><textarea tabindex="1" name="adrotate_message" cols="50" rows="5"></textarea></td>
							<td>
								<p><strong><?php _e('Examples:', 'adrotate'); ?></strong></p>
								<p><em><?php echo $example; ?></em></p>
							</td>
						</tr>
					</tbody>
				</table>

		    	<p class="submit">
					<input tabindex="2" type="submit" name="adrotate_request_submit" class="button-primary" value="<?php _e('Send', 'adrotate'); ?>" />
					<a href="admin.php?page=adrotate-userstatistics&view=stats" class="button"><?php _e('Cancel', 'adrotate'); ?></a>
		    	</p>

			</form>
		<?php } ?>

		<br class="clear" />
		<?php adrotate_user_notice(); ?>

		<br class="clear" />
	</div>
<?php 
}

/*-------------------------------------------------------------
 Name:      adrotate_global_report

 Purpose:   Admin statistics page
 Receive:   -none-
 Return:    -none-
-------------------------------------------------------------*/
function adrotate_global_report() {
	global $wpdb, $adrotate_debug;
	
	$adrotate_stats = adrotate_prepare_global_report();
	
	if($adrotate_stats['tracker'] > 0 OR $adrotate_stats['clicks'] > 0) {
		$clicks = round($adrotate_stats['clicks'] / $adrotate_stats['tracker'], 2); 
	} else { 
		$clicks = 0; 
	}
	
	if($adrotate_stats['impressions'] > 0 AND $adrotate_stats['clicks'] > 0) {
		$ctr = round((100/$adrotate_stats['impressions'])*$adrotate_stats['clicks'], 2);
	} else {
		$ctr = 0;
	}
?>
	<div class="wrap">
	  	<h2><?php _e('Statistics', 'adrotate'); ?></h2>

		<table class="widefat" style="margin-top: .5em">

			<thead>
			<tr>
				<th colspan="4"><?php _e('Overall statistics', 'adrotate'); ?></th>
			</tr>
			</thead>
			
			<tbody>

			<?php if($adrotate_debug['stats'] == true) { ?>
			<tr>
				<td colspan="4">
					<?php 
					echo "<p><strong>Globalized Statistics from cache</strong><pre>"; 
					print_r($adrotate_stats); 
					echo "</pre></p>"; 
					?>
				</td>
			</tr>
			<?php } ?>

		    <tr>
				<th width="10%"><?php _e('General', 'adrotate'); ?></th>
				<td width="40%"><?php echo $adrotate_stats['banners']; ?> <?php _e('ads, sharing a total of', 'adrotate'); ?> <?php echo $adrotate_stats['impressions']; ?> <?php _e('impressions.', 'adrotate'); ?> <?php echo $adrotate_stats['tracker']; ?> <?php _e('ads have tracking enabled.', 'adrotate'); ?></td>
			</tr>
		    <tr>
				<th><?php _e('Average on all ads', 'adrotate'); ?></th>
				<td><?php echo $clicks; ?> <?php _e('clicks.', 'adrotate'); ?></td>
			</tr>
		    <tr>
				<th><?php _e('Click-Through-Rate', 'adrotate'); ?></th>
				<td><?php echo $ctr; ?>%, <?php _e('based on', 'adrotate'); ?> <?php echo $adrotate_stats['impressions']; ?> <?php _e('impressions and', 'adrotate'); ?> <?php echo $adrotate_stats['clicks']; ?> <?php _e('clicks.', 'adrotate'); ?></td>
			</tr>
	      	<tr>
		        <th colspan="4">
		        	<?php
		        	$adstats = $wpdb->get_results("SELECT `thetime`, SUM(`clicks`) as `clicks`, SUM(`impressions`) as `impressions` FROM `".$wpdb->prefix."adrotate_stats_tracker` GROUP BY `thetime` DESC LIMIT 21;");

					if($adstats) {
					
						$adstats = array_reverse($adstats);

						if($adrotate_debug['stats'] == true) { 
							echo "<p><strong>[DEBUG] 21 days (Or as much as is available) Ad stats</strong><pre>"; 
							print_r($adstats); 
							echo "</pre></p>"; 
						}

						foreach($adstats as $result) {
							if($result->clicks == null) $result->clicks = '0';
							if($result->impressions == null) $result->impressions = '0';
						
							$clicks_array[date_i18n("M d", $result->thetime)] = $result->clicks;
							$impressions_array[date_i18n("M d", $result->thetime)] = $result->impressions;
						}

						if($adrotate_debug['stats'] == true) { 
							echo "<p><strong>[DEBUG] Found clicks as presented to PHPGraphLib</strong><pre>"; 
							print_r($clicks_array);
							echo "</pre></p>"; 
							echo "<p><strong>[DEBUG] Found impressions as presented to PHPGraphLib</strong><pre>"; 
							print_r($impressions_array);
							echo "</pre></p>"; 
						}

						$impressions_title = urlencode(serialize(__('Impressions over the past 21 days')));
						$impressions_array = urlencode(serialize($impressions_array));
						echo "<img src=\"".plugins_url("/library/graph_all_ads.php?title=$impressions_title&data=$impressions_array", __FILE__)."\" />";

						$clicks_title = urlencode(serialize(__('Clicks over the past 21 days')));
						$clicks_array = urlencode(serialize($clicks_array));
						echo "<img src=\"".plugins_url("/library/graph_all_ads.php?title=$clicks_title&data=$clicks_array", __FILE__)."\" />";
					} else {
						_e('No data to show!');
					} 
					?>
		        </th>
	      	</tr>
			</tbody>

			<thead>
			<tr>
				<th colspan="4"><?php _e('The last 50 clicks in the past 24 hours', 'adrotate'); ?></th>
			</tr>
			</thead>
			
			<tbody>
			<tr>
				<td colspan="4">
				<?php 
				if($adrotate_stats['lastclicks']) {
					foreach($adrotate_stats['lastclicks'] as $last) {
						$bannertitle = $wpdb->get_var("SELECT `title` FROM `".$wpdb->prefix."adrotate` WHERE `id` = '$last[bannerid]'");
						echo '<strong>'.date_i18n('d-m-Y', $last['timer']) .'</strong> - '. $bannertitle .' - '.$last['useragent'].'<br />';
					}
				} else {
					echo '<em>'.__('No recent clicks', 'adrotate').'</em>';
				} ?>
				</td>
			</tr>
	      	<tr>
				<td colspan="4">
					<b><?php _e('Note:', 'adrotate'); ?></b> <em><?php _e('All statistics are indicative. They do not nessesarily reflect results counted by other parties.', 'adrotate'); ?></em><br />
					<?php _e('Visual graphing kindly provided using', 'adrotate'); ?> <a href="http://www.ebrueggeman.com/" target="_blank">PHPGraphLib by Elliot Brueggeman</a>
				</td>
	      	</tr>
			</tbody>
		</table>

		<br class="clear" />
		<?php adrotate_credits(); ?>

		<br class="clear" />
	</div>
<?php 
}

/*-------------------------------------------------------------
 Name:      adrotate_options

 Purpose:   Admin options page
 Receive:   -none-
 Return:    -none-
-------------------------------------------------------------*/
function adrotate_options() {
	global $wpdb;

	$adrotate_config 			= get_option('adrotate_config');
	$adrotate_crawlers 			= get_option('adrotate_crawlers');
	$adrotate_roles				= get_option('adrotate_roles');
	$adrotate_debug				= get_option('adrotate_debug');
	
	$crawlers 			= implode(', ', $adrotate_crawlers);
	$notification_mails	= implode(', ', $adrotate_config['notification_email']);
	$advertiser_mails	= implode(', ', $adrotate_config['advertiser_email']);
	$message 			= $_GET['message'];
	$corrected		 	= $_GET['corrected'];
	$converted		 	= base64_decode($_GET['converted']);
?>
	<div class="wrap">
	  	<h2><?php _e('AdRotate Settings', 'adrotate'); ?></h2>

		<?php if ($message == 'updated') { ?>
			<div id="message" class="updated fade"><p><?php _e('Settings saved', 'adrotate'); ?></p></div>
		<?php } else if ($message == 'role_add') { ?>
			<div id="message" class="updated fade"><p><?php _e('AdRotate client role added', 'adrotate'); ?></p></div>
		<?php } else if ($message == 'role_remove') { ?>
			<div id="message" class="updated fade"><p><?php _e('AdRotate client role removed', 'adrotate'); ?></p></div>
		<?php } else if ($message == 'db_optimized') { ?>
			<div id="message" class="updated fade"><p><?php _e('Database optimized', 'adrotate'); ?></p></div>
		<?php } else if ($message == 'db_repaired') { ?>
			<div id="message" class="updated fade"><p><?php _e('Database repaired', 'adrotate'); ?></p></div>
		<?php } else if ($message == 'db_cleaned') { ?>
			<div id="message" class="updated fade"><p><?php _e('Empty database records removed', 'adrotate'); ?></p></div>
		<?php } else if ($message == 'eval_complete') { ?>
			<div id="message" class="updated fade"><p><?php _e('Ads re-evaluated, '.$corrected.' ad(s) marked with errors', 'adrotate'); ?></p></div>
		<?php } else if ($message == 'db_timer') { ?>
			<div id="message" class="updated fade"><p><?php _e('Database can only be optimized or cleaned once every hour', 'adrotate'); ?></p></div>
		<?php } else if ($message == 'mail_notification_sent') { ?>
			<div id="message" class="updated fade"><p><?php _e('Test notification(s) sent', 'adrotate'); ?></p></div>
		<?php } else if ($message == 'mail_advertiser_sent') { ?>
			<div id="message" class="updated fade"><p><?php _e('Test mail(s) sent', 'adrotate'); ?></p></div>
		<?php } ?>

	  	<form name="settings" id="post" method="post" action="admin.php?page=adrotate-settings">

	    	<table class="form-table">
			<tr>
				<td colspan="2"><h2><?php _e('Access Rights', 'adrotate'); ?></h2></td>
			</tr>

			<tr>
				<th valign="top"><?php _e('Advertiser Reports Page', 'adrotate'); ?></th>
				<td>
					<select name="adrotate_advertiser_report">
						<?php wp_dropdown_roles($adrotate_config['advertiser_report']); ?>
					</select> <span class="description"><?php _e('Role to allow users/advertisers to see their reports page.', 'adrotate'); ?></span>
				</td>
			</tr>
			<tr>
				<th valign="top"><?php _e('Global Report Page', 'adrotate'); ?></th>
				<td>
					<select name="adrotate_global_report">
						<?php wp_dropdown_roles($adrotate_config['global_report']); ?>
					</select> <span class="description"><?php _e('Role to review the global report.', 'adrotate'); ?></span>
				</td>
			</tr>
			<tr>
				<th valign="top"><?php _e('Manage/Add/Edit Ads', 'adrotate'); ?></th>
				<td>
					<select name="adrotate_ad_manage">
						<?php wp_dropdown_roles($adrotate_config['ad_manage']); ?>
					</select> <span class="description"><?php _e('Role to see and add/edit ads.', 'adrotate'); ?></span>
				</td>
			</tr>
			<tr>
				<th valign="top"><?php _e('Delete/Reset Ads', 'adrotate'); ?></th>
				<td>
					<select name="adrotate_ad_delete">
						<?php wp_dropdown_roles($adrotate_config['ad_delete']); ?>
					</select> <span class="description"><?php _e('Role to delete ads and reset stats.', 'adrotate'); ?></span>
				</td>
			</tr>
			<tr>
				<th valign="top"><?php _e('Manage/Add/Edit Groups', 'adrotate'); ?></th>
				<td>
					<select name="adrotate_group_manage">
						<?php wp_dropdown_roles($adrotate_config['group_manage']); ?>
					</select> <span class="description"><?php _e('Role to see and add/edit groups.', 'adrotate'); ?></span>
				</td>
			</tr>
			<tr>
				<th valign="top"><?php _e('Delete Groups', 'adrotate'); ?></th>
				<td>
					<select name="adrotate_group_delete">
						<?php wp_dropdown_roles($adrotate_config['group_delete']); ?>
					</select> <span class="description"><?php _e('Role to delete groups.', 'adrotate'); ?></span>
				</td>
			</tr>
			<tr>
				<th valign="top"><?php _e('Manage/Add/Edit Blocks', 'adrotate'); ?></th>
				<td>
					<select name="adrotate_block_manage">
						<?php wp_dropdown_roles($adrotate_config['block_manage']); ?>
					</select> <span class="description"><?php _e('Role to see and add/edit blocks.', 'adrotate'); ?></span>
				</td>
			</tr>
			<tr>
				<th valign="top"><?php _e('Delete Blocks', 'adrotate'); ?></th>
				<td>
					<select name="adrotate_block_delete">
						<?php wp_dropdown_roles($adrotate_config['block_delete']); ?>
					</select> <span class="description"><?php _e('Role to delete blocks.', 'adrotate'); ?></span>
				</td>
			</tr>
			<tr>
				<th valign="top"><?php _e('AdRotate Advertisers', 'adrotate'); ?></th>
				<td>
					<?php if($adrotate_roles == 0) { ?>
					<input type="submit" id="post-role-submit" name="adrotate_role_add_submit" value="<?php _e('Create Role', 'adrotate'); ?>" class="button-secondary" />
					<?php } else { ?>
					<input type="submit" id="post-role-submit" name="adrotate_role_remove_submit" value="<?php _e('Remove Role', 'adrotate'); ?>" class="button-secondary" onclick="return confirm('<?php _e('You are about to remove the AdRotate Clients role.', 'adrotate'); ?>\n\n<?php _e('This may lead to users not being able to access their ads statistics!', 'adrotate'); ?>\n\n<?php _e('OK to continue, CANCEL to stop.', 'adrotate'); ?>')" />
					<?php } ?><br />
					<span class="description"><?php _e('This role has no capabilities unless you assign them using the above options. Obviously you should use this with care.', 'adrotate'); ?><br />
					<?php _e('This type of user is NOT required to use AdRotate or it\'s stats. It merely helps you to seperate advertisers from regular subscribers without giving them too much access to your dashboard.', 'adrotate'); ?></span>
				</td>
			</tr>

			<?php if($adrotate_debug['dashboard'] == true) { ?>
			<tr>
				<td colspan="2">
					<?php 
					echo "<p><strong>[DEBUG] Globalized Config</strong><pre>"; 
					$memory = (memory_get_usage() / 1024 / 1024);
					echo "Memory usage: " . round($memory, 2) ." MB <br />"; 
					$peakmemory = (memory_get_peak_usage() / 1024 / 1024);
					echo "Peak memory usage: " . round($peakmemory, 2) ." MB <br />"; 
					print_r($adrotate_config); 
					echo "</pre></p>"; 
					?>
				</td>
			</tr>
			<?php } ?>
			<?php if($adrotate_debug['userroles'] == true) { ?>
			<tr>
				<td colspan="2">
					<?php 
					echo "<p><strong>[DEBUG] AdRotate Advertiser role enabled? (0 = no, 1 = yes)</strong><pre>"; 
					print_r($adrotate_roles); 
					echo "</pre></p>"; 
					echo "<p><strong>[DEBUG] Current User Capabilities</strong><pre>"; 
					print_r(get_option('wp_user_roles')); 
					echo "</pre></p>"; 
					?>
				</td>
			</tr>
			<?php } ?>

			<tr>
				<td colspan="2"><h2><?php _e('Email Notifications', 'adrotate'); ?></h2></td>
			</tr>
			<tr>
				<th valign="top"><?php _e('Notifications', 'adrotate'); ?></th>
				<td>
					<textarea name="adrotate_notification_email" cols="90" rows="3"><?php echo $notification_mails; ?></textarea><br />
					<span class="description"><?php _e('A comma separated list of email addresses. Maximum of 5 addresses. Keep this list to a minimum!', 'adrotate'); ?><br />
					<?php _e('Messages are sent once every 24 hours when needed. If this field is empty the function will be disabled.', 'adrotate'); ?></span>
				</td>
			</tr>
			<tr>
				<th scope="row" valign="top"><?php _e('Test', 'adrotate'); ?></th>
				<td>
					<input type="submit" name="adrotate_notification_test_submit" class="button-secondary" value="<?php _e('Test', 'adrotate'); ?>" /> 
					<span class="description"><?php _e('This sends a test notification. Before you test, for example, with a new email address. Save the options first!', 'adrotate'); ?></span>
				</td>
			</tr>

			<tr>
				<td colspan="2"><h2><?php _e('Advertiser Messages', 'adrotate'); ?></h2></td>
			</tr>
			<tr>
				<th valign="top"><?php _e('Advertiser Messages', 'adrotate'); ?></th>
				<td>
					<textarea name="adrotate_advertiser_email" cols="90" rows="2"><?php echo $advertiser_mails; ?></textarea><br />
					<span class="description"><?php _e('Maximum of 2 addresses. Comma seperated. This field cannot be empty!', 'adrotate'); ?></span>
				</td>
			</tr>
			<tr>
				<th scope="row" valign="top"><?php _e('Test', 'adrotate'); ?></th>
				<td>
					<input type="submit" name="adrotate_advertiser_test_submit" class="button-secondary" value="<?php _e('Test', 'adrotate'); ?>" /> 
					<span class="description"><?php _e('This sends a test message. Before you test, for example, with a new email address. Save the options first!', 'adrotate'); ?></span>
				</td>
			</tr>
			
			<tr>
				<td colspan="2"><h2><?php _e('Clicktracker / Impressiontracker', 'adrotate'); ?></h2></td>
			</tr>

			<?php if($adrotate_debug['dashboard'] == true) { ?>
			<tr>
				<td colspan="2">
					<?php 
					echo "<p><strong>[DEBUG] List of crawler keywords</strong><pre>";
					print_r($adrotate_crawlers); 
					echo "</pre></p>"; 
					?>
				</td>
			</tr>
			<?php } ?>

			<tr>
				<th valign="top"><?php _e('User-Agent Filter', 'adrotate'); ?></th>
				<td>
					<textarea name="adrotate_crawlers" cols="90" rows="5"><?php echo $crawlers; ?></textarea><br />
					<span class="description"><?php _e('A comma separated list of keywords. Filter out bots/crawlers/user-agents. To prevent impressions and clicks counted on them.', 'adrotate'); ?><br />
					<?php _e('Keep in mind that this might give false positives. The word \'google\' also matches \'googlebot\', so be careful!', 'adrotate'); ?><br />
					<?php _e('Additionally to the list specified here, empty User-Agents are blocked as well.', 'adrotate'); ?> (<?php _e('Learn more about', 'adrotate'); ?> <a href="http://en.wikipedia.org/wiki/User_agent" title="User Agents" target="_blank"><?php _e('user-agents', 'adrotate'); ?></a>.)</span>
				</td>
			</tr>
			<tr>
				<th valign="top"><?php _e('Impressions timer', 'adrotate'); ?></th>
				<td>
					<input name="adrotate_impression_timer" type="text" class="search-input" size="5" value="<?php echo $adrotate_config['impression_timer']; ?>" autocomplete="off" /> <?php _e('Seconds.', 'adrotate'); ?><br />
					<span class="description"><?php _e('Default: 10. Set to 0 to disable this timer.', 'adrotate'); ?><br /><?php _e('This number may not be empty, negative or exceed 3600 (1 hour).', 'adrotate'); ?></span>
				</td>
			</tr>

			
			<tr>
				<td colspan="2"><h2><?php _e('Miscellaneous', 'adrotate'); ?></h2></td>
			</tr>
			<tr>
				<th valign="top"><?php _e('Widget alignment', 'adrotate'); ?></th>
				<td><input type="checkbox" name="adrotate_widgetalign" <?php if($adrotate_config['widgetalign'] == 'Y') { ?>checked="checked" <?php } ?> /> <span class="description"><?php _e('Check this box if your widgets do not align in your themes sidebar. (Does not always help!)', 'adrotate'); ?></span></td>
			</tr>
			<tr>
				<th valign="top"><?php _e('Credits', 'adrotate'); ?></th>
				<td><input type="checkbox" name="adrotate_credits" <?php if($adrotate_config['credits'] == 'Y') { ?>checked="checked" <?php } ?> /> <span class="description"><?php _e('Show a simple token that you\'re using AdRotate in the themes Meta part.', 'adrotate'); ?></span></td>
			</tr>

			<tr>
				<td colspan="2"><h2><?php _e('Maintenance', 'adrotate'); ?></h2></td>
			</tr>

			<?php if($adrotate_debug['dashboard'] == true) { ?>
			<tr>
				<td colspan="2">
					<?php 
					echo "<p><strong>[DEBUG] List of tables</strong><pre>";
					$tables = adrotate_list_tables();
					print_r($tables); 
					echo "</pre></p>"; 
					?>
				</td>
			</tr>
			<?php } ?>
			
			<tr>
				<td colspan="2"><span class="description"><?php _e('NOTE: The below functions are intented to be used to OPTIMIZE your database. They only apply to your ads/groups/blocks and stats. Not to other settings or other parts of Wordpress! Always always make a backup! These functions are to be used when you feel or notice your database is slow, unresponsive and sluggish.', 'adrotate'); ?></span></td>
			</tr>
			<tr>
				<th valign="top"><?php _e('Optimize Database', 'adrotate'); ?></th>
				<td>
					<input type="submit" id="post-role-submit" name="adrotate_db_optimize_submit" value="<?php _e('Optimize Database', 'adrotate'); ?>" class="button-secondary" onclick="return confirm('<?php _e('You are about to optimize the AdRotate database.', 'adrotate'); ?>\n\n<?php _e('Did you make a backup of your database?', 'adrotate'); ?>\n\n<?php _e('This may take a moment and may cause your website to respond slow temporarily!', 'adrotate'); ?>\n\n<?php _e('OK to continue, CANCEL to stop.', 'adrotate'); ?>')" /><br />
					<span class="description"><?php _e('Cleans up overhead data in the AdRotate tables.', 'adrotate'); ?><br />
					<?php _e('Overhead data is accumulated garbage resulting from many changes you\'ve made. This can vary from nothing to hundreds of KiB of data.', 'adrotate'); ?></span>
				</td>
			</tr>
			<tr>
				<th valign="top"><?php _e('Clean-up Database', 'adrotate'); ?></th>
				<td>
					<input type="submit" id="post-role-submit" name="adrotate_db_cleanup_submit" value="<?php _e('Clean-up Database', 'adrotate'); ?>" class="button-secondary" onclick="return confirm('<?php _e('You are about to remove empty records from the AdRotate database.', 'adrotate'); ?>\n\n<?php _e('Did you make a backup of your database?', 'adrotate'); ?>\n\n<?php _e('OK to continue, CANCEL to stop.', 'adrotate'); ?>')" /><br />
					<span class="description"><?php _e('AdRotate creates empty records when you start making ads, groups or blocks. In rare occasions these records are faulty. If you made an ad, group or block that does not save when you make it use this button to delete those empty records.', 'adrotate'); ?></span>
				</td>
			</tr>
			<tr>
				<th valign="top"><?php _e('Re-evaluate Ads', 'adrotate'); ?></th>
				<td>
					<input type="submit" id="post-role-submit" name="adrotate_evaluate_submit" value="<?php _e('Re-evaluate all ads', 'adrotate'); ?>" class="button-secondary" onclick="return confirm('<?php _e('You are about to check all ads for errors.', 'adrotate'); ?>\n\n<?php _e('This might take a while and make slow down your site during this action!', 'adrotate'); ?>\n\n<?php _e('OK to continue, CANCEL to stop.', 'adrotate'); ?>')" /><br />
					<span class="description"><?php _e('This will apply all evaluation rules to all ads to see if any error slipped in. Normally you shouldn\t need this feature.', 'adrotate'); ?></span>
				</td>
			</tr>
			<tr>
				<td colspan="2"><span class="description"><?php _e('DISCLAIMER: If for any reason your data is lost, damaged or otherwise becomes unusable in any way or by any means in whichever way i will not take responsibility. You should always have a backup of your database. These functions do NOT destroy data. If data is lost, damaged or unusable, your database likely was beyond repair already. Claiming it worked before clicking these buttons is not a valid point in any case.', 'adrotate'); ?></span></td>
			</tr>

			<tr>
				<td colspan="2"><h2><?php _e('Troubleshooting', 'adrotate'); ?></h2></td>
			</tr>
			<tr>
				<td colspan="2"><span class="description"><?php _e('NOTE: The below options are not meant for normal use and are only there for developers to review saved settings or how ads are selected. These can be used as a measure of troubleshooting upon request but for normal use they SHOULD BE LEFT UNCHECKED!!', 'adrotate'); ?></span></td>
			</tr>

			<tr>
				<th valign="top"><?php _e('Developer Debug', 'adrotate'); ?></th>
				<td>
					<input type="checkbox" name="adrotate_debug" <?php if($adrotate_debug['general'] == true) { ?>checked="checked" <?php } ?> /> <span class="description"><?php _e('Troubleshoot ads and how (if) they are selected, will mess up your theme!', 'adrotate'); ?></span><br />
					<input type="checkbox" name="adrotate_debug_dashboard" <?php if($adrotate_debug['dashboard'] == true) { ?>checked="checked" <?php } ?> /> <span class="description"><?php _e('Show all settings, dashboard routines and related values!', 'adrotate'); ?></span><br />
					<input type="checkbox" name="adrotate_debug_userroles" <?php if($adrotate_debug['userroles'] == true) { ?>checked="checked" <?php } ?> /> <span class="description"><?php _e('Show array of all userroles and capabilities!', 'adrotate'); ?></span><br />
					<input type="checkbox" name="adrotate_debug_userstats" <?php if($adrotate_debug['userstats'] == true) { ?>checked="checked" <?php } ?> /> <span class="description"><?php _e('Review saved user stats (users)! Visible to advertisers!', 'adrotate'); ?></span><br />
					<input type="checkbox" name="adrotate_debug_stats" <?php if($adrotate_debug['stats'] == true) { ?>checked="checked" <?php } ?> /> <span class="description"><?php _e('Review global stats, per ad/group/block stats (admins)!', 'adrotate'); ?></span><br />
					<input type="checkbox" name="adrotate_debug_timers" <?php if($adrotate_debug['timers'] == true) { ?>checked="checked" <?php } ?> /> <span class="description"><?php _e('Disable timers for clicks and impressions allowing you to test the impression and click counters or stats without having to wait for the timer!', 'adrotate'); ?></span><br />
				</td>
			</tr>
	    	</table>
	    	
		    <p class="submit">
		      	<input type="submit" name="adrotate_options_submit" class="button-primary" value="<?php _e('Update Options', 'adrotate'); ?>" />
		    </p>
		</form>
	</div>
<?php 
}
?>