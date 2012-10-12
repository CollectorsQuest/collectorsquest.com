<?php
/*
Plugin Name: WP Nofollow Post
Plugin URI: http://www.niceplugins.com/
Description: Add nofollow attribute to all external links on posts / pages. Exception can be added on several domains. This plugin will not remove old rel of a link (if any), but this plugin smartly adds necessary rel attributes. For addition this plugin can remove links on comments too.
Author: Xrvel
Version: 1.0.2
Author URI: http://www.xrvel.com/
*/

/*  Copyright 2010 niceplugins.com

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    For a copy of the GNU General Public License, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

function xrvel_nfp_get_options() {
	$opt = get_option('xrvel_nfp_options');
	if ($opt == false || $opt == '') {
		$opt = array(
			'enabled' => 1,
			'enable_on' => 1,
			'exclude' => 'google.com,yahoo.com',
			'remove_comment_links' => 1,
			'nofollow_rel' => 'external nofollow',
			'dofollow_rel' => 'follow dofollow'
		);
	} else {
		if (!is_array($opt)) {
			$opt = unserialize($opt);
		}
	}
	return $opt;
}

function xrvel_nfp_options() {
	if (!current_user_can('manage_options')) {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}
	if (isset($_POST['go'])) {
		$x_enabled = '';
		$x_enable_on = 1;
		$x_exclude = '';
		$x_nofollow_rel = 'external nofollow';
		$x_dofollow_rel = 'follow dofollow';
		$x_remove_comment_links = 1;
		if (isset($_POST['x_enabled'])) {
			$x_enabled = intval($_POST['x_enabled']);
		}
		if (isset($_POST['x_enable_on'])) {
			$x_enable_on = intval($_POST['x_enable_on']);
		}
		if (isset($_POST['x_exclude'])) {
			$x_exclude = trim($_POST['x_exclude']);
			$x_exclude = preg_replace('/([^a-z0-9\-\.\,]+)/i', '', $x_exclude);
		}
		if (isset($_POST['remove_comment_links'])) {
			$x_remove_comment_links = intval($_POST['remove_comment_links']);
		}
		if (isset($_POST['x_nofollow_rel'])) {
			$x_nofollow_rel = strtolower(trim($_POST['x_nofollow_rel']));
		}
		if (isset($_POST['x_dofollow_rel'])) {
			$x_dofollow_rel = strtolower(trim($_POST['x_dofollow_rel']));
		}
		$opt = array(
			'enabled' => $x_enabled,
			'enable_on' => $x_enable_on,
			'exclude' => $x_exclude,
			'remove_comment_links' => $x_remove_comment_links,
			'nofollow_rel' => $x_nofollow_rel,
			'dofollow_rel' => $x_dofollow_rel
		);
		update_option('xrvel_nfp_options', serialize($opt));
		_e('<div id="message" class="updated fade"><p>Options updated.</p></div>');
	}
	$opt = xrvel_nfp_get_options();
	echo '<div class="wrap">';
	?>
	<h2>WP Nofollow Post Options</h2>
	<form name="form1" method="post" action="">
	<input type="hidden" name="go" value="1" />
	<p>
	WP Nofollow Post status :
	<select name="x_enabled">
	<option value="1">Enabled</option>
	<option value="0"<?php if ($opt['enabled'] == 0) : ?> selected="selected"<?php endif; ?>>Disabled</option>
	</select>
	</p>
	<p>
	Activate on :
	<select name="x_enable_on">
	<option value="1">Posts &amp; Pages</option>
	<option value="2"<?php if ($opt['enable_on'] == 2) : ?> selected="selected"<?php endif; ?>>Posts</option>
	<option value="3"<?php if ($opt['enable_on'] == 3) : ?> selected="selected"<?php endif; ?>>Pages</option>
	</select>
	</p>
	<p>
		Exclude nofollow on these domains : (separate each domain with commas)<br />
		<textarea name="x_exclude" cols="100" rows="5"><?php echo htmlentities($opt['exclude']); ?></textarea><br />
		&quot;<code>google.com</code>&quot; will match &quot;<code>www.google.com</code>&quot; / &quot;<code>mail.google.com</code>&quot; / &quot;<code>code.google.com</code>&quot;
	</p>
	<p>
		You also can exclude a post / page from being modified by adding <code>xrvel_nfp_skip</code> with value <code>1</code> on the custom field.
	</p>
	<p>
		Nofollow rels that will be added : <input type="text" name="x_nofollow_rel" value="<?php echo htmlentities($opt['nofollow_rel']); ?>" style="text-transform:lowercase" />
	</p>
	<p>
		Dofollow rels that will be removed if found : <input type="text" name="x_dofollow_rel" value="<?php echo htmlentities($opt['dofollow_rel']); ?>" style="text-transform:lowercase" />
	</p>
	<p>
	Remove links on comments ? :
	<select name="remove_comment_links">
	<option value="1"<?php if ($opt['remove_comment_links'] == 1) : ?> selected="selected"<?php endif; ?>>Yes</option>
	<option value="0"<?php if ($opt['remove_comment_links'] == 0) : ?> selected="selected"<?php endif; ?>>No</option>
	</select>
	</p>
	<p class="submit">
		<input type="submit" name="Submit" class="button-primary" value="Save Changes" />
	</p>
	</form>
	<p>
		Plugin by <a href="http://www.niceplugins.com" target="_blank">NicePlugins.com</a>, by <a href="http://www.xrvel.com" target="_blank">Xrvel</a>
	</p>
	<?php
	echo '</div>';
}

function xrvel_nfp_add_pages() {
	add_options_page('WP Nofollow Post', 'WP Nofollow Post', 'manage_options', 'np-wp-nofollow-post', 'xrvel_nfp_options');
}

function xrvel_nfp_text_mod($text) {
	global $post;

	$opt = xrvel_nfp_get_options();

	if ($opt['enabled'] == 0) {
		return $text;
	}

	if ($opt['enable_on'] == 2 && $post->post_type != 'post') {
		return $text;
	}

	if ($opt['enable_on'] == 3 && $post->post_type != 'page') {
		return $text;
	}

	if ((string)get_post_meta($post->ID, 'xrvel_nfp_skip', true) == '1') {
		return $text;
	}

	define('X_ADD_REL', $opt['nofollow_rel']);
	define('X_REMOVE_REL', $opt['dofollow_rel']);

	$ignore = explode(',', $opt['exclude']);
	$new = array();
	foreach ($ignore as $ig) {
		$ig = trim($ig);
		if ($ig != '') {
			$new[] = trim($ig);
		}
	}

	$text = xrvel_nfp_modify_nofollow($text, $new);

	return $text;
}

function xrvel_nfp_uninstall() {
	delete_option('xrvel_nfp_options');
}

if (!function_exists('xrvel_nfp_get_domain_name')) {
	function xrvel_nfp_get_domain_name() {
		$s = strtolower($_SERVER['SERVER_NAME']);
		return $s;
	}
}

if (!function_exists('xrvel_nfp_fix_rel')) {
	function xrvel_nfp_fix_rel($rel) {
		$rel = trim($rel);
		if ($rel == '') {
			return '';
		}
		$rel = preg_replace('/([ ]+)/', ' ', $rel);
		$rel2 = explode(' ', strtolower($rel));
		$rel_remove = explode(' ', strtolower(X_REMOVE_REL));
		$new = array();
		foreach ($rel2 as $needle) {
			if (!in_array($needle, $new) && !in_array($needle, $rel_remove)) {
				$new[] = strtolower($needle);
			}
		}
		return implode(' ', $new);
	}
}

if (!function_exists('xrvel_nfp_fix_html')) {
	function xrvel_nfp_fix_html($text) {
		$text = preg_replace('/href([ ]+)?=([ ]+)?\"(.*)\"/iU', 'href="\\3"', $text);
		$text = preg_replace('/rel([ ]+)?=([ ]+)?\"(.*)\"/iU', 'rel="\\3"', $text);
	
		$text = preg_replace('/href([ ]+)?=([ ]+)?\'(.*)\'/iU', 'href="\\3"', $text);
		$text = preg_replace('/rel([ ]+)?=([ ]+)?\'(.*)\'/iU', 'rel="\\3"', $text);
		return $text;
	}
}

function xrvel_nfp_modify_nofollow($text, $exception_domains = array()) {
	$dn = xrvel_nfp_get_domain_name();
	$text = xrvel_nfp_fix_html($text);
	preg_match_all("/<a(.*)href=\"(.*)\"(.*)?>(.*)?<\/a>/iU", $text, $matches);
	$max = count($matches[0]);
	for ($i=0;$i<$max;$i++) {
		$href = $matches[2][$i];

		if (preg_match('/^http(s)?\:\/\//i', $href)) {/* seems external link */
			$pu = parse_url($href);
			$proceed = false;
			if (preg_match('/'.xrvel_nfp_safe_regexp($dn).'+$/i', $pu['host'])) {/* internal link */
				$proceed = false;
			} else {/* external, lets check if it is on exception list */
				$found = false;
				foreach ($exception_domains as $ed) {
					if (preg_match('/'.xrvel_nfp_safe_regexp($ed).'+$/i', $pu['host'])) {/* exception match */
						$found = true;
					}
				}
				$proceed = !$found;
			}
			if ($proceed) {
				$part1 = $matches[1][$i];
				$part2 = $matches[3][$i];
				$inner = $matches[4][$i];

				$from = '<a'.$part1.'href="'.$href.'"'.$part2.'>'.$inner.'</a>';
				$replace = true;
				if (preg_match('/rel="(.*)?"/iU', $part1, $match)) {
					if (!isset($match[1])) {
						$match[1] = '';
					}
					$rel_old = $match[1];
					$rel_new = trim($rel_old.' '.X_ADD_REL);
					$rel_new = xrvel_nfp_fix_rel($rel_new);
					$part_new = str_replace('rel="'.$rel_old.'"', 'rel="'.$rel_new.'"', $part1);
					$to = '<a'.$part_new.'href="'.$href.'"'.$part2.'>'.$inner.'</a>';
				} else if (preg_match('/rel="(.*)?"/iU', $part2, $match)) {
					if (!isset($match[1])) {
						$match[1] = '';
					}
					$rel_old = $match[1];
					$rel_new = trim($rel_old.' '.X_ADD_REL);
					$rel_new = xrvel_nfp_fix_rel($rel_new);
					$part_new = str_replace('rel="'.$rel_old.'"', 'rel="'.$rel_new.'"', $part2);
					$to = '<a'.$part1.'href="'.$href.'"'.$part_new.'>'.$inner.'</a>';
				} else {
					$to = '<a'.$part1.'href="'.$href.'"'.$part2.' rel="'.X_ADD_REL.'">'.$inner.'</a>';
				}
				if ($replace) {
					$text = str_replace($from, $to, $text);
				}
			}
		}
	}
	return $text;
}

if (!function_exists('xrvel_nfp_remove_comment_links')) {
	function xrvel_nfp_remove_comment_links($str) {
		$str = preg_replace('/\<a(.*)\>(.*)\<\/a\>/iU', '$2', $str);
		return $str;
	}
}

if (!function_exists('xrvel_nfp_safe_regexp')) {
	function xrvel_nfp_safe_regexp($str) {
		$str = str_replace('.', '\.', $str);
		$str = str_replace('-', '\-', $str);
		return $str;
	}
}

if (!is_admin()) {
	$opt = xrvel_nfp_get_options();
	add_filter('the_content', 'xrvel_nfp_text_mod');
	if ($opt['remove_comment_links'] != 0) {
		add_filter('comment_text', 'xrvel_nfp_remove_comment_links');
	}
}

add_action('admin_menu', 'xrvel_nfp_add_pages');
register_uninstall_hook(ABSPATH.PLUGINDIR.'/wp-nofollow-post/wp-nofollow-post.php', 'xrvel_nfp_uninstall');
?>