<?php
/*
Plugin Name: AdRotate
Plugin URI: http://www.adrotateplugin.com
Description: The very best and most convenient way to publish your ads.
Author: Arnan de Gans of AJdG Solutions
Version: 3.7.4.1
Author URI: http://www.ajdg.net
License: GPL2
*/

/*  
Copyright 2010-2012 Arnan de Gans - AJdG Solutions (email : info@ajdg.net)
*/

/*--- AdRotate values ---------------------------------------*/
define("ADROTATE_BETA", '');
define("ADROTATE_VERSION", 362);
define("ADROTATE_DB_VERSION", 22);
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
$adrotate_advert_status			= get_option("adrotate_advert_status");
/*-----------------------------------------------------------*/

/*--- Core --------------------------------------------------*/
register_activation_hook(__FILE__, 'adrotate_activate');
register_deactivation_hook(__FILE__, 'adrotate_deactivate');
register_uninstall_hook(__FILE__, 'adrotate_uninstall');
add_action('admin_init', 'adrotate_check_upgrade');
add_action('adrotate_ad_notification', 'adrotate_mail_notifications');
add_action('adrotate_clean_trackerdata', 'adrotate_clean_trackerdata');
add_filter('cron_schedules', 'adrotate_reccurences');
/*-----------------------------------------------------------*/

/*--- Front end ---------------------------------------------*/
add_shortcode('adrotate', 'adrotate_shortcode');
add_filter('the_content', 'adrotate_inject_posts');
//add_action('wp_enqueue_scripts', 'adrotate_head');
add_action('widgets_init', create_function('', 'return register_widget("adrotate_widgets");'));
add_action('wp_meta', 'adrotate_meta');
/*-----------------------------------------------------------*/

/*--- Dashboard ---------------------------------------------*/
add_action('admin_init', 'adrotate_colorpicker');
add_action('admin_menu', 'adrotate_dashboard');
add_action('admin_notices','adrotate_notifications_dashboard');
add_action('wp_dashboard_setup', 'adrotate_dashboard_widget');
/*-----------------------------------------------------------*/

/*--- BETA NOTICE -------------------------------------------*/
if(strlen(ADROTATE_BETA) > 0) add_action('admin_notices','adrotate_beta_notifications_dashboard');
/*-----------------------------------------------------------*/

/*--- Internal redirects ------------------------------------*/
if(isset($_POST['adrotate_ad_submit'])) 				add_action('init', 'adrotate_insert_input');
if(isset($_POST['adrotate_group_submit'])) 				add_action('init', 'adrotate_insert_group');
if(isset($_POST['adrotate_block_submit'])) 				add_action('init', 'adrotate_insert_block');
if(isset($_POST['adrotate_action_submit'])) 			add_action('init', 'adrotate_request_action');
if(isset($_POST['adrotate_disabled_action_submit']))	add_action('init', 'adrotate_request_action');
if(isset($_POST['adrotate_error_action_submit']))		add_action('init', 'adrotate_request_action');
if(isset($_POST['adrotate_beta_submit'])) 				add_action('init', 'adrotate_mail_beta');
if(isset($_POST['adrotate_options_submit'])) 			add_action('init', 'adrotate_options_submit');
if(isset($_POST['adrotate_request_submit'])) 			add_action('init', 'adrotate_mail_message');
if(isset($_POST['adrotate_notification_test_submit'])) 	add_action('init', 'adrotate_mail_test');
if(isset($_POST['adrotate_advertiser_test_submit'])) 	add_action('init', 'adrotate_mail_test');
if(isset($_POST['adrotate_role_add_submit']))			add_action('init', 'adrotate_prepare_roles');
if(isset($_POST['adrotate_role_remove_submit'])) 		add_action('init', 'adrotate_prepare_roles');
if(isset($_POST['adrotate_db_optimize_submit'])) 		add_action('init', 'adrotate_optimize_database');
if(isset($_POST['adrotate_db_cleanup_submit'])) 		add_action('init', 'adrotate_cleanup_database');
if(isset($_POST['adrotate_db_upgrade_submit'])) 		add_action('init', 'adrotate_check_upgrade');
if(isset($_POST['adrotate_evaluate_submit'])) 			add_action('init', 'adrotate_prepare_evaluate_ads');
if(isset($_POST['adrotate_export_submit'])) 			add_action('init', 'adrotate_export_csv');
/*-----------------------------------------------------------*/

/*-------------------------------------------------------------
 Name:      adrotate_dashboard

 Purpose:   Add pages to admin menus
 Receive:   -none-
 Return:    -none-
-------------------------------------------------------------*/
function adrotate_dashboard() {
	global $adrotate_config;

	$admin_pages = array();

	add_object_page('AdRotate', 'AdRotate', 'adrotate_ad_manage', 'adrotate', 'adrotate_manage');
	$admin_pages[] = add_submenu_page('adrotate', 'AdRotate > '.__('Manage Ads', 'adrotate'), __('Manage Ads', 'adrotate'), 'adrotate_ad_manage', 'adrotate', 'adrotate_manage');
	$admin_pages[] = add_submenu_page('adrotate', 'AdRotate > '.__('Manage Groups', 'adrotate'), __('Manage Groups', 'adrotate'), 'adrotate_group_manage', 'adrotate-groups', 'adrotate_manage_group');
	$admin_pages[] = add_submenu_page('adrotate', 'AdRotate > '.__('Manage Blocks', 'adrotate'), __('Manage Blocks', 'adrotate'), 'adrotate_block_manage', 'adrotate-blocks', 'adrotate_manage_block');
//	$admin_pages[] = add_submenu_page('adrotate', 'AdRotate > '.__('Moderate', 'adrotate'), __('Moderate New Adverts', 'adrotate'), 'adrotate_moderate', 'adrotate-moderate-ads', 'adrotate_moderate');
	$admin_pages[] = add_submenu_page('adrotate', 'AdRotate > '.__('Advertiser', 'adrotate'), __('Advertiser', 'adrotate'), 'adrotate_advertiser', 'adrotate-advertiser', 'adrotate_advertiser');
	$admin_pages[] = add_submenu_page('adrotate', 'AdRotate > '.__('Global Reports', 'adrotate'), __('Global Reports', 'adrotate'), 'adrotate_global_report', 'adrotate-global-report', 'adrotate_global_report');
	$admin_pages[] = add_submenu_page('adrotate', 'AdRotate > '.__('Settings', 'adrotate'), __('Settings', 'adrotate'), 'manage_options', 'adrotate-settings', 'adrotate_options');
	if(strlen(ADROTATE_BETA) > 0) $admin_pages[] = add_submenu_page('adrotate', 'AdRotate > Beta Feedback', 'Beta Feedback', 'adrotate_ad_manage', 'adrotate-beta', 'adrotate_beta');
	
	foreach ( $admin_pages as $admin_page ) {
		add_action("admin_print_styles-{$admin_page}", 'adrotate_filemanager_admin_scripts');
		add_action("admin_print_scripts-{$admin_page}", 'adrotate_filemanager_admin_styles');
	}
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
	$in84days 		= $now + 7257600;
	
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

		<?php if(adrotate_check_database(adrotate_list_tables()) == true) { ?>
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
			<?php } else if ($message == 'nodata') { ?>
				<div id="message" class="updated fade"><p><?php _e('No data found in selected time period', 'adrotate'); ?></p></div>
			<?php } else if ($message == 'csvmail') { ?>
				<div id="message" class="updated fade"><p><?php _e('Email(s) with reports successfully sent', 'adrotate'); ?></p></div>
			<?php } ?>
	
			<?php
			$allbanners = $wpdb->get_results("SELECT `id`, `title`, `type`, `tracker`, `weight` FROM `".$wpdb->prefix."adrotate` ORDER BY $order;");
			
			foreach($allbanners as $singlebanner) {
				
				$starttime = $wpdb->get_var("SELECT `starttime` FROM `".$wpdb->prefix."adrotate_schedule` WHERE `ad` = '".$singlebanner->id."' ORDER BY `starttime` ASC LIMIT 1;");
				$stoptime = $wpdb->get_var("SELECT `stoptime` FROM `".$wpdb->prefix."adrotate_schedule` WHERE `ad` = '".$singlebanner->id."' ORDER BY `stoptime` DESC LIMIT 1;");
				
					
				if($singlebanner->type == 'active') {
					$activebanners[$singlebanner->id] = array(
						'id' => $singlebanner->id,
						'title' => $singlebanner->title,
						'type' => $singlebanner->type,
						'tracker' => $singlebanner->tracker,
						'weight' => $singlebanner->weight,
						'firstactive' => $starttime,
						'lastactive' => $stoptime
					);
				}
				
				if($singlebanner->type == 'error') {
					$errorbanners[$singlebanner->id] = array(
						'id' => $singlebanner->id,
						'title' => $singlebanner->title,
						'type' => $singlebanner->type,
						'tracker' => $singlebanner->tracker,
						'weight' => $singlebanner->weight,
						'firstactive' => $starttime,
						'lastactive' => $stoptime
					);
				}
				
				if($singlebanner->type == 'disabled') {
					$disabledbanners[$singlebanner->id] = array(
						'id' => $singlebanner->id,
						'title' => $singlebanner->title,
						'type' => $singlebanner->type,
						'tracker' => $singlebanner->tracker,
						'weight' => $singlebanner->weight,
						'firstactive' => $starttime,
						'lastactive' => $stoptime
					);
				}
			}
			?>
			
			<div class="tablenav">
				<div class="alignleft actions">
					<a class="row-title" href="<?php echo admin_url('/admin.php?page=adrotate&view=manage');?>"><?php _e('Manage', 'adrotate'); ?></a> | 
					<a class="row-title" href="<?php echo admin_url('/admin.php?page=adrotate&view=addnew');?>"><?php _e('Add New', 'adrotate'); ?></a> 
					<?php if($ad_edit_id) { ?>
					| <a class="row-title" href="<?php echo admin_url('/admin.php?page=adrotate&view=report&ad='.$ad_edit_id);?>"><?php _e('Report', 'adrotate'); ?></a>
					<?php } ?>
				</div>
			</div>

			<br class="clear" />

			<?php adrotate_credits(); ?>

			<br class="clear" />

	    	<?php if ($view == "" OR $view == "manage") { ?>
	
				<?php
				// Show list of errorous ads if any			
				if ($errorbanners) {
					include("dashboard/adrotate-manage-ads-error.php");
				}
		
				include("dashboard/adrotate-manage-ads-active.php");
	
				// Show disabled ads, if any
				if ($disabledbanners) {
					include("dashboard/adrotate-manage-ads-disabled.php");
				}
				?>

			<?php
		   	} else if($view == "addnew" OR $view == "edit") { 
		   	?>

				<?php
				include("dashboard/adrotate-manage-ads-edit.php");
				?>

		   	<?php } else if($view == "report") { ?>

				<?php
				include("dashboard/adrotate-manage-ads-report.php");
				?>

		   	<?php } ?>
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

		<?php if(adrotate_check_database(adrotate_list_tables()) == true) { ?>
			<?php if ($message == 'created') { ?>
				<div id="message" class="updated fade"><p><?php _e('Group created', 'adrotate'); ?></p></div>
			<?php } else if ($message == 'updated') { ?>
				<div id="message" class="updated fade"><p><?php _e('Group updated', 'adrotate'); ?></p></div>
			<?php } else if ($message == 'deleted') { ?>
				<div id="message" class="updated fade"><p><?php _e('Group deleted', 'adrotate'); ?></p></div>
			<?php } else if ($message == 'deleted_banners') { ?>
				<div id="message" class="updated fade"><p><?php _e('Group including it\'s Ads deleted', 'adrotate'); ?></p></div>
			<?php } else if ($message == 'nodata') { ?>
				<div id="message" class="updated fade"><p><?php _e('No data found in selected time period', 'adrotate'); ?></p></div>
			<?php } ?>

			<div class="tablenav">
				<div class="alignleft actions">
					<a class="row-title" href="<?php echo admin_url('/admin.php?page=adrotate-groups&view=manage');?>"><?php _e('Manage', 'adrotate'); ?></a> | 
					<a class="row-title" href="<?php echo admin_url('/admin.php?page=adrotate-groups&view=addnew');?>"><?php _e('Add New', 'adrotate'); ?></a>
					<?php if($group_edit_id) { ?>
					| <a class="row-title" href="<?php echo admin_url('/admin.php?page=adrotate-groups&view=report&group='.$group_edit_id);?>"><?php _e('Report', 'adrotate'); ?></a>
					<?php } ?>
				</div>
			</div>

			<?php adrotate_credits(); ?>

			<br class="clear" />

	    	<?php if ($view == "" OR $view == "manage") { ?>

				<?php
				include("dashboard/adrotate-manage-groups-main.php");
				?>

		   	<?php } else if($view == "addnew" OR $view == "edit") { ?>

				<?php
				include("dashboard/adrotate-manage-groups-edit.php");
				?>

		   	<?php } else if($view == "report") { ?>

				<?php
				include("dashboard/adrotate-manage-groups-report.php");
				?>

		   	<?php } ?>
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

		<?php if(adrotate_check_database(adrotate_list_tables()) == true) { ?>
			<?php if ($message == 'created') { ?>
				<div id="message" class="updated fade"><p><?php _e('Block created', 'adrotate'); ?></p></div>
			<?php } else if ($message == 'updated') { ?>
				<div id="message" class="updated fade"><p><?php _e('Block updated', 'adrotate'); ?></p></div>
			<?php } else if ($message == 'deleted') { ?>
				<div id="message" class="updated fade"><p><?php _e('Block deleted', 'adrotate'); ?></p></div>
			<?php } else if ($message == 'nodata') { ?>
				<div id="message" class="updated fade"><p><?php _e('No data found in selected time period', 'adrotate'); ?></p></div>
			<?php } ?>

			<div class="tablenav">
				<div class="alignleft actions">
					<a class="row-title" href="<?php echo admin_url('/admin.php?page=adrotate-blocks&view=manage');?>"><?php _e('Manage', 'adrotate'); ?></a> 
					| <a class="row-title" href="<?php echo admin_url('/admin.php?page=adrotate-blocks&view=addnew');?>"><?php _e('Add New', 'adrotate'); ?></a> 
					<?php if($block_edit_id) { ?>
					| <a class="row-title" href="<?php echo admin_url('/admin.php?page=adrotate-blocks&view=report&block='.$block_edit_id);?>"><?php _e('Report', 'adrotate'); ?></a> 
					<?php } ?>
				</div>
			</div>

			<?php adrotate_credits(); ?>

			<br class="clear" />

	    	<?php if ($view == "" OR $view == "manage") { ?>

				<?php
				include("dashboard/adrotate-manage-blocks-main.php");
				?>

		   	<?php } else if($view == "addnew" OR $view == "edit") { ?>
		   	
				<?php
				include("dashboard/adrotate-manage-blocks-edit.php");
				?>
	
		   	<?php } else if($view == "report") { ?>

				<?php
				include("dashboard/adrotate-manage-blocks-report.php");
				?>

		   	<?php } ?>
		<?php } else { ?>
			<?php echo adrotate_error('db_error'); ?>
		<?php }	?>
		<br class="clear" />
	</div>
<?php
}

/*-------------------------------------------------------------
 Name:      adrotate_moderate

 Purpose:   Moderation queue
 Receive:   -none-
 Return:    -none-
-------------------------------------------------------------*/
function adrotate_moderate() {
	global $wpdb, $adrotate_config, $adrotate_debug;
	
	$message 		= $_GET['message'];
	$view 			= $_GET['view'];
?>
	<div class="wrap">
	  	<h2><?php _e('Moderator Queue', 'adrotate'); ?></h2>

		<?php if ($message == 'mail_sent') { ?>
			<div id="message" class="updated fade"><p><?php _e('Your message has been sent', 'adrotate'); ?></p></div>
		<?php } else if ($message == 'nodata') { ?>
			<div id="message" class="updated fade"><p><?php _e('No data found in selected time period', 'adrotate'); ?></p></div>
		<?php } ?>

		<br class="clear" />
		<?php adrotate_user_notice(); ?>

		<br class="clear" />
	</div>
<?php 
}

/*-------------------------------------------------------------
 Name:      adrotate_advertiser

 Purpose:   Advertiser page
 Receive:   -none-
 Return:    -none-
-------------------------------------------------------------*/
function adrotate_advertiser() {
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
	  	<h2><?php _e('Advertiser', 'adrotate'); ?></h2>

		<?php if ($message == 'mail_sent') { ?>
			<div id="message" class="updated fade"><p><?php _e('Your message has been sent', 'adrotate'); ?></p></div>
		<?php } else if ($message == 'nodata') { ?>
			<div id="message" class="updated fade"><p><?php _e('No data found in selected time period', 'adrotate'); ?></p></div>
		<?php } ?>

		<?php 
		if($view == "" OR $view == "stats") {
			
			include("dashboard/adrotate-advertiser-main.php");

		} else if($view == "message") {
			
			include("dashboard/adrotate-advertiser-message.php");

		}
		?>

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
	
	$message 		= $_GET['message'];
	$adrotate_stats = adrotate_prepare_global_report();
	
	if($adrotate_stats['tracker'] > 0 OR $adrotate_stats['clicks'] > 0) {
		$clicks = round($adrotate_stats['clicks'] / $adrotate_stats['tracker'], 2); 
	} else { 
		$clicks = 0; 
	}
	
	// Get Click Through Rate
	$ctr = adrotate_ctr($adrotate_stats['clicks'], $adrotate_stats['impressions']);						
	
	$export_month = gmdate('m');
?>
	<div class="wrap">

	  	<h2><?php _e('Statistics', 'adrotate'); ?></h2>

		<?php adrotate_credits(); ?>

		<br class="clear" />

		<?php if ($message == 'nodata') { ?>
			<div id="message" class="updated fade"><p><?php _e('No data found in selected time period', 'adrotate'); ?></p></div>
		<?php } ?>

		<?php include("dashboard/adrotate-reports-global.php"); ?>
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
	$adrotate_version 			= get_option("adrotate_version");
	$adrotate_db_version 		= get_option("adrotate_db_version");
	
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
				<th valign="top"><?php _e('Advertiser page', 'adrotate'); ?></th>
				<td>
					<select name="adrotate_advertiser">
						<?php wp_dropdown_roles($adrotate_config['advertiser']); ?>
					</select> <span class="description"><?php _e('Role to allow users/advertisers to see their advertisement page.', 'adrotate'); ?></span>
				</td>
			</tr>
			<tr>
				<th valign="top"><?php _e('Global report page', 'adrotate'); ?></th>
				<td>
					<select name="adrotate_global_report">
						<?php wp_dropdown_roles($adrotate_config['global_report']); ?>
					</select> <span class="description"><?php _e('Role to review the global report.', 'adrotate'); ?></span>
				</td>
			</tr>
			<tr>
				<th valign="top"><?php _e('Manage/Add/Edit adverts', 'adrotate'); ?></th>
				<td>
					<select name="adrotate_ad_manage">
						<?php wp_dropdown_roles($adrotate_config['ad_manage']); ?>
					</select> <span class="description"><?php _e('Role to see and add/edit ads.', 'adrotate'); ?></span>
				</td>
			</tr>
			<tr>
				<th valign="top"><?php _e('Delete/Reset adverts', 'adrotate'); ?></th>
				<td>
					<select name="adrotate_ad_delete">
						<?php wp_dropdown_roles($adrotate_config['ad_delete']); ?>
					</select> <span class="description"><?php _e('Role to delete ads and reset stats.', 'adrotate'); ?></span>
				</td>
			</tr>
			<tr>
				<th valign="top"><?php _e('Manage/Add/Edit groups', 'adrotate'); ?></th>
				<td>
					<select name="adrotate_group_manage">
						<?php wp_dropdown_roles($adrotate_config['group_manage']); ?>
					</select> <span class="description"><?php _e('Role to see and add/edit groups.', 'adrotate'); ?></span>
				</td>
			</tr>
			<tr>
				<th valign="top"><?php _e('Delete groups', 'adrotate'); ?></th>
				<td>
					<select name="adrotate_group_delete">
						<?php wp_dropdown_roles($adrotate_config['group_delete']); ?>
					</select> <span class="description"><?php _e('Role to delete groups.', 'adrotate'); ?></span>
				</td>
			</tr>
			<tr>
				<th valign="top"><?php _e('Manage/Add/Edit blocks', 'adrotate'); ?></th>
				<td>
					<select name="adrotate_block_manage">
						<?php wp_dropdown_roles($adrotate_config['block_manage']); ?>
					</select> <span class="description"><?php _e('Role to see and add/edit blocks.', 'adrotate'); ?></span>
				</td>
			</tr>
			<tr>
				<th valign="top"><?php _e('Delete blocks', 'adrotate'); ?></th>
				<td>
					<select name="adrotate_block_delete">
						<?php wp_dropdown_roles($adrotate_config['block_delete']); ?>
					</select> <span class="description"><?php _e('Role to delete blocks.', 'adrotate'); ?></span>
				</td>
			</tr>
<!--
			<tr>
				<th valign="top"><?php _e('Moderate new adverts', 'adrotate'); ?></th>
				<td>
					<select name="adrotate_moderate" disabled>
						<?php wp_dropdown_roles($adrotate_config['moderate']); ?>
					</select> <span class="description"><?php _e('Role to approve ads submitted by advertisers.', 'adrotate'); ?> (This likely comes in 3.8)</span>
				</td>
			</tr>
			<tr>
				<th valign="top"><?php _e('Approve/Reject adverts in Moderation Queue', 'adrotate'); ?></th>
				<td>
					<select name="adrotate_moderate_approve" disabled>
						<?php wp_dropdown_roles($adrotate_config['moderate_approve']); ?>
					</select> <span class="description"><?php _e('Role to approve or reject ads submitted by advertisers.', 'adrotate'); ?> (This likely comes in 3.8)</span>
				</td>
			</tr>
-->
			<tr>
				<th valign="top"><?php _e('AdRotate Advertisers', 'adrotate'); ?></th>
				<td>
					<?php if($adrotate_roles == 0) { ?>
					<input type="submit" id="post-role-submit" name="adrotate_role_add_submit" value="<?php _e('Create Role', 'adrotate'); ?>" class="button-secondary" />
					<?php } else { ?>
					<input type="submit" id="post-role-submit" name="adrotate_role_remove_submit" value="<?php _e('Remove Role', 'adrotate'); ?>" class="button-secondary" onclick="return confirm('<?php _e('You are about to remove the AdRotate Clients role.', 'adrotate'); ?>\n\n<?php _e('This may lead to users not being able to access their ads statistics!', 'adrotate'); ?>\n\n<?php _e('OK to continue, CANCEL to stop.', 'adrotate'); ?>')" />
					<?php } ?><br />
					<span class="description"><?php _e('This role has no capabilities unless you assign them using the above options. Obviously you should use this with care.', 'adrotate'); ?><br />
					<?php _e('This type of user is NOT required to use AdRotate or any of it\'s features. It merely helps you to seperate advertisers from regular subscribers without giving them too much access to your dashboard.', 'adrotate'); ?></span>
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
			<tr>
				<td colspan="2"><span class="description"><?php _e('DISCLAIMER: If for any reason your data is lost, damaged or otherwise becomes unusable in any way or by any means in whichever way I will not take responsibility. You should always have a backup of your database. These functions do NOT destroy data. If data is lost, damaged or unusable, your database likely was beyond repair already. Claiming it worked before clicking these buttons is not a valid point in any case.', 'adrotate'); ?></span></td>
			</tr>
			<tr>
				<th valign="top"><?php _e('Optimize Database', 'adrotate'); ?></th>
				<td>
					<input type="submit" id="post-role-submit" name="adrotate_db_optimize_submit" value="<?php _e('Optimize Database', 'adrotate'); ?>" class="button-secondary" onclick="return confirm('<?php _e('You are about to optimize the AdRotate database.', 'adrotate'); ?>\n\n<?php _e('Did you make a backup of your database?', 'adrotate'); ?>\n\n<?php _e('This may take a moment and may cause your website to respond slow temporarily!', 'adrotate'); ?>\n\n<?php _e('OK to continue, CANCEL to stop.', 'adrotate'); ?>')" /><br />
					<span class="description"><?php _e('Cleans up overhead data in the AdRotate tables.', 'adrotate'); ?><br />
					<?php _e('Overhead data is accumulated garbage resulting from many changes you might have made. This can vary from nothing to hundreds of KiB of data.', 'adrotate'); ?></span>
				</td>
			</tr>
			<tr>
				<th valign="top"><?php _e('Clean-up Database', 'adrotate'); ?></th>
				<td>
					<input type="submit" id="post-role-submit" name="adrotate_db_cleanup_submit" value="<?php _e('Clean-up Database', 'adrotate'); ?>" class="button-secondary" onclick="return confirm('<?php _e('You are about to remove empty records from the AdRotate database.', 'adrotate'); ?>\n\n<?php _e('Did you make a backup of your database?', 'adrotate'); ?>\n\n<?php _e('OK to continue, CANCEL to stop.', 'adrotate'); ?>')" /><br />
					<span class="description"><?php _e('AdRotate creates empty records when you start making ads, groups or blocks. In rare occasions these records are faulty. If you made an ad, group or block that does not save when you make it use this button to delete those empty records. Faulty stats records are cleaned up as well.', 'adrotate'); ?></span>
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
				<td colspan="2"><h2><?php _e('Troubleshooting', 'adrotate'); ?></h2></td>
			</tr>
			<tr>
				<td colspan="2"><span class="description"><?php _e('DISCLAIMER: If for any reason your data is lost, damaged or otherwise becomes unusable in any way or by any means in whichever way I will not take responsibility. You should always have a backup of your database. Use with caution and make a backup first!', 'adrotate'); ?></span></td>
			</tr>
			<?php if($adrotate_debug['upgrade'] == true) { ?>
			<tr>
				<td colspan="2">
					<?php 
					echo "<p><strong>[DEBUG] Saved build and database versions.</strong><pre>";
					print_r($adrotate_version); 
					print_r($adrotate_db_version); 
					echo "</pre></p>"; 
					?>
				</td>
			</tr>
			<?php } ?>
			<tr>
				<td colspan="2">Current AdRotate build: <strong><?php echo $adrotate_version['current']; ?></strong><br />Required AdRotate build: <strong><?php echo ADROTATE_VERSION; ?></strong></td>
			</tr>
			<tr>
				<td colspan="2">Current AdRotate database version: <strong><?php echo $adrotate_db_version['current']; ?></strong><br />Required AdRotate database version: <strong><?php echo ADROTATE_DB_VERSION; ?></strong></td>
			</tr>
			<tr>
				<th valign="top"><?php _e('Manual Upgrade', 'adrotate'); ?></th>
				<td>
				<?php if(($adrotate_version['current'] < ADROTATE_VERSION AND $adrotate_version['previous'] < ADROTATE_VERSION) OR ($adrotate_db_version['current'] < ADROTATE_DB_VERSION AND $adrotate_db_version['previous'] < ADROTATE_DB_VERSION)) { ?>
					<input type="submit" id="post-role-submit" name="adrotate_db_upgrade_submit" value="<?php _e('Upgrade Database and Migrate Data', 'adrotate'); ?>" class="button-secondary" onclick="return confirm('<?php _e('You are about to upgrade your database and migrate data within the database.', 'adrotate'); ?>\n\n<?php _e('MAKE SURE YOU HAVE A BACKUP!!!', 'adrotate'); ?>\n\n<?php _e('OK to continue, CANCEL to stop.', 'adrotate'); ?>')" />
				<?php } else { ?>
					<strong><?php _e('It appears your database is up-to-date. If you believe this to be untrue, contact support and report your Plugin version, AdRotate build and database version.', 'adrotate'); ?></strong>
				<?php } ?>
				</td>
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
					<input type="checkbox" name="adrotate_debug_track" <?php if($adrotate_debug['track'] == true) { ?>checked="checked" <?php } ?> /> <span class="description"><?php _e('Disable encryption on the redirect url. This will NOT compromise any security!', 'adrotate'); ?></span><br />
					<input type="checkbox" name="adrotate_debug_upgrade" <?php if($adrotate_debug['upgrade'] == true) { ?>checked="checked" <?php } ?> /> <span class="description"><?php _e('Display version numbers and show a list of errors that happened (if any) during the database upgrade.', 'adrotate'); ?></span><br />
				</td>
			</tr>
			<?php if($adrotate_debug['dashboard'] == true) { ?>
			<tr>
				<td colspan="2">
					<?php 
					$tables = adrotate_list_tables();
					$found = adrotate_check_database($tables);
					if($found == true) { $found = 'YES'; } else { $found = 'NO'; }

					echo "<p><strong>[DEBUG] List of tables</strong><pre>";
					print_r($tables); 
					echo "</pre></p>"; 
					echo "<p><strong>[DEBUG] Are all tables found?</strong> ".$found."</p>"; 
					?>
				</td>
			</tr>
			<?php } ?>
			
			<?php if($adrotate_debug['upgrade'] == true) { ?>
			<tr>
				<td colspan="2">
					<?php 
					$upgradelog	= get_option('adrotate_upgrade_log');
					echo "<p><strong>[DEBUG] List of events during upgrade. Arrays only apply to database. 1 = GOOD - 0 (or empty) = ERROR/SKIPPED.</strong><br />Errors/Skipped queries is not a bad thing per-se. Some items will be skipped over because they are not required for you.<br />This array merely serves as a troubleshooting tool to suggest a starting point to find a fix to a problem.<br />An empty array means no update was executed or not required.<pre>";
					print_r($upgradelog); 
					echo "</pre></p>"; 
					?>
				</td>
			</tr>
			<?php } ?>
	    	</table>
	    	
		    <p class="submit">
		      	<input type="submit" name="adrotate_options_submit" class="button-primary" value="<?php _e('Update Options', 'adrotate'); ?>" />
		    </p>
		</form>
	</div>
<?php 
}

/*-------------------------------------------------------------
 Name:      adrotate_beta

 Purpose:   Admin dashboard for beta releases
 Receive:   -none-
 Return:    -none-
-------------------------------------------------------------*/
function adrotate_beta() {
	global $wpdb, $current_user;
	
	$message = $_GET['message'];
?>
	<div class="wrap">

	  	<h2>Feedback submission</h2>

		<?php if ($message == 'sent') { ?>
			<div id="message" class="updated fade"><p>Feedback sent! Thanks for improving AdRotate</p></div>
		<?php } else if ($message == 'empty') { ?>
			<div id="message" class="error fade"><p>Feedback form can not be empty!</p></div>
		<?php } ?>

		<?php include("dashboard/adrotate-beta-feedback-form.php"); ?>
		
		<br class="clear" />
		<?php adrotate_credits(); ?>

		<br class="clear" />
	</div>
<?php 
}

?>
