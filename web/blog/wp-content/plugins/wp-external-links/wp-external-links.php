<?php defined( 'ABSPATH' ) OR die( 'No direct access.' );
/*
Plugin Name: WP External Links
Plugin URI: http://www.freelancephp.net/wp-external-links-plugin
Description: Open external links in a new window/tab, add "external" / "nofollow" to rel-attribute, set icon, XHTML strict, SEO friendly...
Author: Victor Villaverde Laan
Version: 1.30
Author URI: http://www.freelancephp.net
License: Dual licensed under the MIT and GPL licenses
*/

// plugin version
define( 'WP_EXTERNAL_LINKS_VERSION', '1.30' );

// plugin key (used as translation domain, option_group, page_slug etc)
define( 'WP_EXTERNAL_LINKS_KEY', 'wp_external_links' );

// plugin file
define( 'WP_EXTERNAL_LINKS_FILE', __FILE__ );


// check plugin compatibility
if ( isset( $wp_version ) AND version_compare( preg_replace( '/-.*$/', '', $wp_version ), '3.0', '>=' )
			AND version_compare( phpversion(), '5.2', '>=' ) ) {

	// include classes
	require_once( 'includes/wp-plugin-dev-classes/class-wp-meta-box-page.php' );
	require_once( 'includes/wp-plugin-dev-classes/class-wp-option-forms.php' );
	require_once( 'includes/class-admin-external-links.php' );
	require_once( 'includes/class-wp-external-links.php' );

	// create WP_External_Links instance
	$WP_External_Links = new WP_External_Links();

} else {

	// set error message
	function wp_external_links_error_notice() {
		echo '<div class="error">'
			. __( '<p>Warning - <strong>WP External Links Plugin</strong> requires PHP 5.2+ and WP 3.0+.'
					. '<br/>Please upgrade your configuration or use an older version of this plugin. '
					. 'Disable the plugin to remove this message.</p>', WP_EXTERNAL_LINKS_KEY )
			. '</div>';
	}
	add_action( 'admin_notices', 'wp_external_links_error_notice' );

}
