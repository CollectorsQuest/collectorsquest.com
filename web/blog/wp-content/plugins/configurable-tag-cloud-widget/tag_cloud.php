<?php
/*
Plugin Name: Configurable Tag Cloud
Plugin URI: http://reciprocity.be/ctc/
Description: A tag cloud plugin for WordPress to give you more flexibility with the styling of your tag cloud.
Author: Keith Solomon
Version: 5.2
Author URI: http://reciprocity.be/

	Copyright (c) 2009 Keith Solomon (http://reciprocity.be)
	Configurable Tag Cloud is released under the GNU General Public License (GPL)
	http://www.gnu.org/licenses/gpl.txt
*/

/* Load Helper Functions */
require(WP_PLUGIN_DIR.'/configurable-tag-cloud-widget/base.php');

/* Load Template Tag Config Page */
include(WP_PLUGIN_DIR.'/configurable-tag-cloud-widget/admin_page.php');

/* Load WP Sidebar Widget */
if (class_exists('WP_Widget')) {
	include(WP_PLUGIN_DIR.'/configurable-tag-cloud-widget/widget_28.php');
} else {
	include(WP_PLUGIN_DIR.'/configurable-tag-cloud-widget/widget.php');
}

register_activation_hook(__FILE__,'install_defs');
register_deactivation_hook(__FILE__,'uninstall_defs');
?>