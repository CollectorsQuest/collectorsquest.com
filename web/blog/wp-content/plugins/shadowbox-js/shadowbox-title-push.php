<?php
/**
 * ShadowboxTitlePush class for Pushing the image attribute onto anchor tags
 *
 * This class contains all functions and actions required for ShadowboxTitle to work on the frontend of WordPress
 *
 * @since 3.0.3
 * @package shadowbox-js
 * @subpackage TitlePush
 */

/*
Plugin Name:  Shadowbox JS - Use Title from Image
Plugin URI:   http://sivel.net/wordpress/shadowbox-js/
Description:  Push the title attribute from the img tag to the anchor tag
Version:      3.0.3.9
Author:       Matt Martz
Author URI:   http://sivel.net/
Text Domain:  shadowbox-js
Domain Path:  shadowbox-js/localization
License:      LGPL

	Shadowbox JS (c) 2008-2010 Matt Martz (http://sivel.net/)
	Shadowbox JS is released under the GNU General Public License (LGPL)
	http://www.gnu.org/licenses/lgpl-2.1.txt

	Shadowbox (c) 2007-2010 Michael J. I. Jackson (http://www.shadowbox-js.com/)
	Shadowbox is licensed under the Shadowbox.js License version 1.0
	http://www.shadowbox-js.com/LICENSE

	JW FLV Media Player (c) 2008 LongTail As Solutions (http://www.longtailvideo.com/)
	JW FLV Media Player is licensed under the Creative Commons Attribution-Noncommercial-Share Alike 3.0 Unported License
	http://creativecommons.org/licenses/by-nc-sa/3.0/
*/

class ShadowboxTitlePush {

	/**
	 * PHP 4 Style constructor which calls the below PHP5 Style Constructor
	 *
	 * @since 3.0.3
	 * @return none
	 */
	function ShadowboxTitlePush () {
		$this->__construct();
	}

	/**
	 * Setup plugin and hook into WordPress
	 *
	 * @return none
	 * @since 3.0.3
	 */
	function __construct () {
		add_filter ( 'the_content' , array ( &$this , 'push_title_to_anchor' ) , 11 );
		add_filter ( 'the_excerpt' , array ( &$this , 'push_title_to_anchor' ) , 11 );
		add_filter ( 'wp_get_attachment_link' , array ( &$this , 'push_title_to_anchor' ) , 11 );
	}

	/**
	 * Filter the_content and the_excerpt finding a title attribute on an <img> tag
	 * and pushing it to the parent <a> tag if the title attribute does not exist on
	 * the <a> tag.
	 *
	 * @since 3.0.3
	 * @param string $content The content of the post
	 * @return string
	 */
	function push_title_to_anchor ( $content ) {
		$master_pattern = '%<a[^>]+><img[^>]+></a>%';
		$anchor_pattern = '%(<a[^>]+)>%';

		if ( preg_match_all ( $master_pattern , $content , $links ) ) {

			foreach ( $links[0] as $link ) {
				$anchor_title_pattern = '%<a[^>]+title=([\'"]).*?\\1[^>]+?>%';
				$img_title_pattern = '%<img[^>]+(title=([\'"]).*?\\2)[^>]+?>%';
				if ( preg_match ( $img_title_pattern , $link , $title ) && ! preg_match ( $anchor_title_pattern , $link ) ) {
					$link_replace = preg_replace ( $anchor_pattern , '$1 ' . $title[1] . '>' , $link );
					$content = str_replace ( $link , $link_replace , $content );
				}

			}

		}

		return $content;
	}

}

/**
 * Instantiate the ShadowboxTitlePush Class
 */
if ( ! is_admin () ) {
	$ShadowboxTitlePush = new ShadowboxTitlePush ();
}
