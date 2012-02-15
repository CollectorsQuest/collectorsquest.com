<?php
/*
Plugin Name: Multiple Galleries
Plugin URI: http://konstruktors.com/blog/projects-services/wordpress-plugins/multiple-galleries/
Description: Add multiple galleries per post using simple checkboxes for selecting images you want to include.
Version: 0.4.1
Author: Kaspars Dambis
Author URI: http://konstruktors.com/blog/
*/

add_action('admin_enqueue_scripts', 'add_multiple_galleries_js');
function add_multiple_galleries_js($where) {
	if ($where == 'media-upload-popup')
		wp_enqueue_script('multiple-galleries', plugins_url('multiple-galleries/multiple-galleries.js'), array('jquery', 'media-upload', 'utils', 'admin-gallery'));
}

?>
