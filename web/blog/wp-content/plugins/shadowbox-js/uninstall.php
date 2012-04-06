<?php
/**
 * Uninstalls the shadowbox-js options when an uninstall has been requested
 * from the WordPress admin
 *
 * @package shadowbox-js
 * @subpackage uninstall
 * @since 2.0.3.0
 */

// If uninstall/delete not called from WordPress then exit
if( ! defined ( 'ABSPATH' ) && ! defined ( 'WP_UNINSTALL_PLUGIN' ) )
	exit ();

// Delete shadowbox option from options table
delete_option ( 'shadowbox' );
