<?php
/*
Plugin Name: Customizable Post Listings
Version: 1.5
Plugin URI: http://www.coffee2code.com/wp-plugins/
Author: Scott Reilly
Author URI: http://www.coffee2code.com
Description: Display Recent Posts, Recently Commented Posts, Recently Modified Posts, Random Posts, and other post listings using the post information of your choosing in an easily customizable manner.  You can narrow post searches by specifying categories and/or authors, among other things.

=>> Visit the plugin's homepage for more information and latest updates  <<=

Installation:

1. Download the file http://www.coffee2code.com/wp-plugins/customizable-post-listings.zip and unzip it into your 
/wp-content/plugins/ directory.
-OR-
Copy and paste the the code ( http://www.coffee2code.com/wp-plugins/customizable-post-listings.phps ) into a file called 
customizable-post-listings.php, and put that file into your /wp-content/plugins/ directory.
2. Activate the plugin from your WordPress admin 'Plugins' page.
3. In your sidebar.php (or other template file), insert calls to post listings function(s) provided by the plugin.

See the plugin's homepage for usage instructions.


*/

/*
Copyright (c) 2004-2005 by Scott Reilly (aka coffee2code)

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation 
files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, 
modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the 
Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR
IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

//
// ************************ START TEMPLATE TAGS ******************************************************************
//

if ( !isset($tableposts) ) {
	$tablecomments = $wpdb->comments;
	$tablepost2cat = $wpdb->post2cat;
	$tableposts = $wpdb->posts;
}

function c2c_get_recent_posts ($num_posts = 5,
	$format = "<li>%post_date%: %post_URL%</li>",
	$categories = '',		// space separated list of category IDs -- leave empty to get all
	$orderby = 'date',
	$order = 'DESC',		// either 'ASC' (ascending) or 'DESC' (descending)
	$offset = 0,			// number of posts to skip
	$date_format = 'm/d/Y',		// Date format, php-style, if different from blog's date-format setting
	$authors = '',			// space separated list of author IDs -- leave empty to get all
	$post_status = 'publish',	// space separated list of post_statuses to consider (possible values: publish, draft, private, and/or static)
	$include_passworded_posts = false) 
{
	global $wpdb, $tablecomments, $tableposts, $tablepost2cat;
	if ($order != 'ASC') $order = 'DESC';
	if ('max_comment_date' == $orderby) { $add_recent_comment_to_sql = 1; }
	else {
		if ($orderby != 'rand()') $orderby = "$tableposts.post_$orderby";
		$add_recent_comment_to_sql = 0;
	}
	if (empty($post_status)) $post_status = 'publish';
	
	$now = current_time('mysql');
	if ($add_recent_comment_to_sql) $sql = "SELECT $tableposts.*, MAX(comment_date) AS max_comment_date FROM $tablecomments, $tableposts ";
	else $sql = "SELECT DISTINCT * FROM $tableposts ";
	if ($categories) {
		$categories = addslashes_gpc($categories);
		if (stristr($categories,'-')) {
			// Note: If any one category is defined as being excluded (i.e. "-10") then ALL other listed categories will also be
			//	excluded, regardless of whether a "-" preceded them or not
			// TODO: Support mixture of multiple category inclusion(s) and exclusion(s)
			$eq = '!=';
			$andor = 'AND';
		} else {
			$eq = '=';
			$andor = 'OR';
		}
		$sql .= "LEFT JOIN $tablepost2cat ON ($tableposts.ID = $tablepost2cat.post_id) ";
		$cat_array = preg_split('/[,\s]+/', $categories);
		$sql .= ' AND ( category_id '.$eq.' '.abs(intval($cat_array[0]));
		$sql .= get_category_children($cat_array[0], ' '.$andor.' category_id '.$eq.' ');
		for ($i = 1; $i < (count($cat_array)); $i = $i + 1) {
			$sql .= ' '.$andor.' category_id '.$eq.' '.abs(intval($cat_array[$i]));
			$sql .= get_category_children($cat_array[$i], ' '.$andor.' category_id '.$eq.' ');
		}
		$sql .= ') ';
	}
	$sql .= "WHERE $tableposts.post_date <= '$now' ";
	$sql .= "AND ( $tableposts.post_status = '" . str_replace(" ", "' OR $tableposts.post_status = '", $post_status) . "' ) ";
	if (!$include_passworded_posts) $sql .= "AND $tableposts.post_password = '' ";
	if ($add_recent_comment_to_sql) $sql .= "AND $tableposts.ID = $tablecomments.comment_post_ID AND $tablecomments.comment_approved = '1' ";
	if ($authors) {
		$authors = addslashes_gpc($authors);
		if (stristr($authors,'-')) {
			// Note: If any one author is defined as being excluded (i.e. "-10") then ALL other listed author will also be
			//	excluded, regardless of whether a "-" preceded them or not
			// TODO: Support mixture of multiple author inclusion(s) and exclusion(s)
			$eq = '!=';
			$andor = 'AND';
		} else {
			$eq = '=';
			$andor = 'OR';
		}
		$author_array = preg_split('/[,\s]+/', $authors);
		$sql .= " AND ( $tableposts.post_author $eq '" . abs(intval($author_array[0])) . "' ";
		for ($i = 1; $i < (count($author_array)); $i = $i + 1) {
			$sql .= "OR $tableposts.post_author $eq '" . abs(intval($author_array[$i])) . "' ";
		}
		$sql .= ') ';
	}
	if ('modified' == $orderby) $sql .= "AND $tableposts.post_modified_gmt <= '$now' ";
	$sql .= "GROUP BY $tableposts.ID ORDER BY $orderby $order";
	if ($num_posts) $sql .= " LIMIT $offset, $num_posts";
	$posts = array();
	$posts = $wpdb->get_results($sql);
	if (empty($posts)) return;
	return c2c_get_recent_handler($posts, $format, $date_format);
} //end function c2c_get_recent_posts()

function c2c_get_random_posts($num_posts = 5,
        $format = "<li>%post_date%: %post_URL%</li>",
        $categories = '',               // space separated list of category IDs -- leave empty to get all
        $order = 'DESC',                // either 'ASC' (ascending) or 'DESC' (descending)
        $offset = 0,                    // number of posts to skip
        $date_format = 'm/d/Y',         // Date format, php-style, if different from blog's date-format setting
        $authors = '',                  // space separated list of author IDs -- leave empty to get all
	$post_status = 'publish',	// space separated list of post_statuses to consider (possible values: publish, draft, private, and/or static)
        $include_passworded_posts = false)
{
        return c2c_get_recent_posts($num_posts, $format, $categories, 'rand()', $order, $offset, $date_format, $authors, $include_passworded_posts);
} //end function get_random_post()

function c2c_get_recently_commented ($num_posts = 5, 
	$format = "<li>%comments_URL%<br />%last_comment_date%<br />%comments_fancy%</li>",
	$categories = '',		// space separated list of category IDs -- leave empty to get all
	$order = 'DESC',		// either 'ASC' (ascending) or 'DESC' (descending)
	$offset = 0,			// number of posts to skip
	$date_format = 'm/d/Y h:i a',	// Date format, php-style, if different from blog's date-format setting
	$authors = '',			// space separated list of author IDs -- leave empty to get all
	$post_status = 'publish',	// space separated list of post_statuses to consider (possible values: publish, draft, private, and/or static)
	$include_passworded_posts = false)
{
	return c2c_get_recent_posts($num_posts, $format, $categories, 'max_comment_date', $order, $offset, $date_format, $authors, $include_passworded_posts);
} //end function get_recently_commented()

function c2c_get_recently_modified ($num_posts = 5,
	$format = "<li>%post_URL%<br />Updated: %post_modified%</li>",
	$categories = '',		// space separated list of category IDs -- leave empty to get all
	$order = 'DESC',		// either 'ASC' (ascending) or 'DESC' (descending)
	$offset = 0,			// number of posts to skip
	$date_format = 'm/d/Y',		// Date format, php-style, if different from blog's date-format setting
	$authors = '',			// space separated list of author IDs -- leave empty to get all
	$post_status = 'publish',	// space separated list of post_statuses to consider (possible values: publish, draft, private, and/or static)
	$include_passworded_posts = false)
{
	return c2c_get_recent_posts($num_posts, $format, $categories, 'modified', $order, $offset, $date_format, $authors, $include_passworded_posts);
} //end function c2c_get_recently_modified()

//
// ************************ END TEMPLATE TAGS ********************************************************************
//

if (! function_exists('c2c_comment_count') ) {
	// Leave $comment_types blank to count all comment types (comment, trackback, and pingback).  Otherwise, specify $comment_types
	//	as a space-separated list of any combination of those three comment types (only valid for WP 1.5+)
	function c2c_comment_count ($post_id, $comment_types='') {
		global $wpdb;
		if (!isset($wpdb->comments)) {
			global $tablecomments;
			$wpdb->comments = $tablecomments;
		}
		$sql = "SELECT COUNT(*) FROM $wpdb->comments WHERE comment_post_ID = '$post_id' AND comment_approved = '1'";
		if (!empty($comment_type)) {
			$sql .= " AND ( comment_type = '" . str_replace(" ", "' OR comment_type = '", $comment_types) . "' ";
			if (strpos($comment_types,'comment') !== false)
				$sql .= "OR comment_type = '' ";		//WP allows a comment_type of '' to be == 'comment'
			$sql .= ")";
		}
		return $wpdb->get_var($sql);
	} //end function c2c_comment_count()
}

if (! function_exists('c2c_get_get_custom') ) {
	function c2c_get_get_custom( $post_id, $field, $none = '' ) {
		global $wpdb;
		if (! empty($post_meta_cache[$id][$field]) ) {
			$result = $post_meta_cache[$id][$field];
		} else {
			$sql  = "SELECT DISTINCT meta_value FROM $wpdb->postmeta ";
			$sql .= "WHERE post_id = '$post_id' AND meta_key = '$field' ";
			$sql .= "LIMIT 1";
			$result = $wpdb->get_var($sql);
		}
		if ( empty($result) && !empty($none) ) $result = $none;
		return $result;
	} //end function c2c_get_get_custom()
}

function c2c_get_recent_tagmap ($posts, $format, $tags, $ctags, $date_format) {
	if (!$tags) return $format;
	global $authordata, $comment, $post;
	
	//-- Some things you might want to configure -----
	$excerpt_words = 6;		// Number of words to use for %post_excerpt_short%
	$excerpt_length = 50; 		// Number of characters to use for %post_excerpt_short%, only used if $excerpt_words is 0
	$comment_excerpt_words = 6;	// Number or words to use for %last_comment_excerpt% and %last_comment_excerpt_URL%
	$comment_excerpt_length = 15;	// Numbler of characters to use for %last_comment_excerpt% and %last_comment_excerpt_URL%, only used if $comment_excerpt_words is 0
	$between_categories = ', ';	// Text to appear between categories when categories are listed
	$time_format = '';
	$idmode = 'nickname';	// how to present post author name
	$comment_fancy = array('No comments', '1 Comment', '%comments_count% Comments');
	$pingback_fancy = array('No pingbacks', '1 Pingback', '%pingbacks_count% Pingbacks');
	$trackback_fancy = array('No trackbacks', '1 Trackback', '%trackbacks_count% Trackbacks');
	//-- END configuration section -----

	if (!$date_format) $date_format = get_settings('date_format');
	
	// Now process the posts
	$orig_post = $post; $orig_authordata = $authordata; $orig_comment = $comment;
	foreach ($posts as $post) {
		$text = $format;
		$comment_count = ''; $pingback_count = ''; $trackback_count = ''; $allcomment_count = '';
		$authordata = '';
		$title = '';

		// If want last_comment information, then need to make a special db request
		$using_last_comment = 0;
		foreach ($tags as $tag) {
			if (strpos($tag, 'last_comment') !== false) { $using_last_comment = 1; break; }
		}
		if ($using_last_comment) {
			global $wpdb;
			if (!isset($wpdb->comments)) {
				global $tablecomments;
				$wpdb->comments = $tablecomments;
			}
			$comment = $wpdb->get_row("SELECT * FROM $wpdb->comments WHERE comment_post_ID = '$post->ID' AND comment_approved = '1' AND ( comment_type = '' OR comment_type = 'comment' ) ORDER BY comment_date DESC LIMIT 1");
		}
		
		// Perform percent substitutions
		foreach ($ctags as $tag) {
			$new = '';
			if (strpos($tag, '%last_commenters(') !== false) {
				global $wpdb;
				preg_match("/^%last_commenters\((.+)\)%$/U", $tag, $matches);
				// This pseudo-function looks like this: %last_commenters(limit,type,more)%
				// Where:
				//	limit = number of latest commenters to list by name
				//	more = text to show after listed commenter *if* there are more commenters to the post; default is [...]
				//	between = text to show between listed commenters; default is ", "
				list($limit,$more,$between) = explode(',',$matches[1]);
				if (empty($more)) $more = '[...]';
				if (empty($between)) $between = ', ';
				
				$nlimit = $limit + 1;
				
				$comments = $wpdb->get_results("SELECT comment_author, comment_ID FROM $wpdb->comments WHERE comment_post_ID = '$post->ID' and comment_approved = '1' AND ( comment_type = '' OR comment_type = 'comment' ) ORDER BY comment_date DESC LIMIT $nlimit");
				$count = 1;
				if ($comments) :
				foreach ($comments as $cmnt) {
					if ($count > 1) $new .= $between;
					if ($count > $limit) {
						$new .= '<a href="'.get_permalink().'#comments" title="View all comments">' . $more . "</a>\n";
						break;
					}
					$new .= '<a href="'.get_permalink().'#comment-'.$cmnt->comment_ID.'" title="View comment by '.$cmnt->comment_author.'">'.$cmnt->comment_author.'</a>';
					$count++;
				}
				endif;
			} elseif (strpos($tag, '%post_custom(') !== false) {
				preg_match("/^%post_custom\((.+)\)%$/U", $tag, $matches);
				// This pseudo-function looks like this: %post_custom(field,format,none)%
				list($field,$cformat,$none) = explode(',',$matches[1]);
				$custom = c2c_get_get_custom($post->ID,$field, $none);
				if ( empty($custom) ) {
					//Do nothing
				}
				elseif ( empty($cformat) ) {
					$new = $custom;
				} else {
					$cformat = str_replace('%field%', $field, $cformat);
					$cformat = str_replace('%value%', $custom, $cformat);
					$new = $cformat;
				}
			} elseif (strpos($tag, '%post_other(') !== false) {
				preg_match("/^%post_other\((.+)\)%$/U", $tag, $matches);
				// This pseudo-function looks like this: %post_other(post_view_count)%
				$field = $matches[1];
				if (isset($post->$field)) $new = $post->$field;
			}
			$text = str_replace($tag, $new, $text);
		}
		// Perform percent substitutions
		foreach ($tags as $tag) {
			switch ($tag) {
				case '%allcomments_count%':
					if (!$allcomment_count) { $allcomment_count = c2c_comment_count($post->ID); }
					$new = $allcomment_count;
					break;
				case '%allcomments_fancy%':
					if (!$allcomment_count) { $allcomment_count = c2c_comment_count($post->ID); }
					if ($allcomment_count < 2) $new = $comment_fancy[$allcomment_count];
					else $new = str_replace('%comments_count%', $allcomment_count, $comment_fancy[2]);
					break;
				case '%comments_count%':
					if (!$comment_count) { $comment_count = c2c_comment_count($post->ID, 'comment'); }
					$new = $comment_count;
					break;
				case '%comments_count_URL%':
					if (!$title) { $title = the_title('', '', false); }
					if (!$comment_count) { $comment_count = c2c_comment_count($post->ID, 'comment'); }
					$new = '<a href="'.get_permalink().'#comments" title="View all comments for '.wp_specialchars(strip_tags($title), 1).'">'.$comment_count.'</a>';
					break;
				case '%comments_fancy%':
				case '%comments_fancy_URL%':
					if (!$comment_count) { $comment_count = c2c_comment_count($post->ID, 'comment'); }
					if ($comment_count < 2) $new = $comment_fancy[$comment_count];
					else $new = str_replace('%comments_count%', $comment_count, $comment_fancy[2]);
					if ( '%comments_fancy_URL%' == $tag )
						$new = '<a href="'.get_permalink().'#comments" title="View all comments for '.wp_specialchars(strip_tags($title), 1).'">'.$new.'</a>';
					break;
				case '%comments_url%':
					$new = get_permalink() . "#postcomment";
					break;
				case '%comments_URL%':
					if (!$title) { $title = the_title('', '', false); }
					$new = '<a href="'.get_permalink().'#comments" title="View all comments for '.wp_specialchars(strip_tags($title), 1).'">'.$title.'</a>';
					break;
				case '%last_comment_date%':
					$new = get_comment_date($date_format);
					break;
				case '%last_comment_excerpt%':
				case '%last_comment_excerpt_URL%':
					$new = ltrim(strip_tags(apply_filters('get_comment_excerpt', $comment->comment_content)));
					if ($comment_excerpt_words) {
						$words = explode(" ", $new);
						$new = join(" ", array_slice($words, 0, $comment_excerpt_words));
						if (count($words) > $comment_excerpt_words) $new .= "...";
					} elseif ($comment_excerpt_length) {
						if (strlen($new) > $comment_excerpt_length) $new = substr($new,0,$comment_excerpt_length) . "...";
					}
					if ( '%last_comment_excerpt_URL%' == $tag )
						$new = '<a href="'.get_permalink().'#comment-'.$comment->comment_ID.'">'.$new.'</a>';
					break;
				case '%last_comment_id%':
					$new = get_comment_ID();
					break;
				case '%last_comment_time%':
					$new = get_comment_time($time_format);
					break;
				case '%last_comment_url%':
					$new = get_permalink().'#comment-'.$comment->comment_ID;
					break;
				case '%last_commenter%':
					$new = apply_filters('comment_author', get_comment_author());
					break;
				case '%last_commenter_URL%':
					$new = get_comment_author_link();
					break;
				case '%pingbacks_count%':
					if (!$pingback_count) { $pingback_count = c2c_comment_count($post->ID, 'pingback'); }
					$new = $pingback_count;
					break;
				case '%pingbacks_fancy%':
					if (!$pingback_count) { $pingback_count = c2c_comment_count($post->ID, 'pingback'); }
					if ($pingback_count < 2) $new = $pingback_fancy[$pingback_count];
					else $new = str_replace('%pingbacks_count%', $pingback_count, $pingback_fancy[2]);
					break;
				case '%post_author%':
					if (!$authordata) { $authordata = get_userdata($post->post_author); }
					$new = the_author($idmode, false);
					break;
				case '%post_author_count%':
					$new = get_usernumposts($post->post_author);
					break;
				case '%post_author_posts%':
					if (!$authordata) { $authordata = get_userdata($post->post_author); }
					$new = '<a href="'.get_author_link(0, $authordata->ID, $authordata->user_nicename).'" title="';
					$new .= sprintf(__("Posts by %s"), wp_specialchars(the_author($idmode, false), 1)).'">'.stripslashes(the_author($idmode, false)).'</a>';
					break;
				case '%post_author_url%':
					if (!$authordata) { $authordata = get_userdata($post->post_author); }
					if ($authordata->user_url)
						$new = '<a href="'.$authordata->user_url.'" title="Visit '.the_author($idmode, false).'\'s site">'.the_author($idmode, false).'</a>';
					else
						$new = the_author($idmode, false);
					break;
				case '%post_categories%':
				case '%post_categories_URL%':
					$cats = get_the_category($post->ID);
					$new = '';
					if ($cats) {
						if ('%post_categories_URL%' == $tag)
							$new .= '<a href="' . get_category_link($cats[0]->category_id) . '" title="View archive for category">';
						$new .= $cats[0]->cat_name;
						if ('%post_categories_URL%' == $tag) $new .= '</a>';
						for ($i = 1; $i < (count($cats)); $i = $i + 1) {
							$new .= $between_categories;
							if ('%post_categories_URL%' == $tag)
								$new .= '<a href="' . get_category_link($cats[$i]->category_id) . '" title="View archive for category">';
							$new .= $cats[$i]->cat_name;
							if ('%post_categories_URL%' == $tag) $new .= '</a>';
						}
					}
					break;
				case '%post_content%':
				case '%post_content_full%':
					$new = apply_filters('the_content', $post->post_content);
					if ( '%post_content_full%' != $tag ) $new = str_replace(array('<p>','</p>','<br />'), '', $new);
					break;
				case '%post_date%':
					$new = apply_filters('the_date', mysql2date($date_format, $post->post_date));
					break;
				case '%post_excerpt%':
				case '%post_excerpt_full%':
					$new = apply_filters('the_excerpt', get_the_excerpt());
					if ( '%post_excerpt_full%' != $tag ) $new = str_replace(array('<p>','</p>','<br />'), '', $new);
					break;
				case '%post_excerpt_short%':
                                        $new = ltrim(strip_tags(apply_filters('the_excerpt', get_the_excerpt())));
					if ($excerpt_words) {
  						$words = explode(" ", $new);
  						$new = join(" ", array_slice($words, 0, $excerpt_words));
  						if (count($words) > $excerpt_words) $new .= "...";
					} elseif ($excerpt_length) {
   						if (strlen($new) > $excerpt_length) $new = substr($new,0,$excerpt_length) . "...";
					}
                                        break;
				case '%post_id%':
					$new = $post->ID;
					break;
				case '%post_modified%':
					$new = mysql2date($date_format, $post->post_modified);
					break;
				case '%post_status%':
					$new = apply_filters('post_status', $post->post_status);
					break;
				case '%post_time%':
					$new = apply_filters('get_the_time', get_post_time($time_format));
					break;
				case '%post_title%':
					if (!$title) { $title = the_title('', '', false); }
					$new = $title;
					break;
				case '%post_url%':
					$new = get_permalink();
					break;
				case '%post_URL%':		
					if (!$title) { $title = the_title('', '', false); }
					$new = '<a href="'.get_permalink().'" title="View post '.wp_specialchars(strip_tags($title), 1).'">'.$title.'</a>';
					break;
				case '%trackbacks_count%':
					if (!$trackback_count) { $trackback_count = c2c_comment_count($post->ID, 'trackback'); }
					$new = $trackback_count;
					break;
				case '%trackbacks_fancy%':
					if (!$trackback_count) { $trackback_count = c2c_comment_count($post->ID, 'trackback'); }
					if ($trackback_count < 2) $new = $trackback_fancy[$trackback_count];
					else $new = str_replace('%trackbacks_count%', $trackback_count, $trackback_fancy[2]);
					break;
			}
			$text = str_replace($tag, $new, $text);
		}
		echo $text . "\n";
	}
	$post = $orig_post; $authordata = $orig_authordata; $comment = $orig_comment;
	return count($posts);
} // end function c2c_get_recent_tagmap()

function c2c_get_recent_handler ($posts, $format = '', $date_format = '') {
	if (!$format) { return $posts; }
	
	// Determine the format of the listing
	$percent_tags = array(
		"%allcomments_count%",	// Number of comments + pingbacks + trackbacks for post
		"%allcomments_fancy%",	// Fancy reporting of allcomments
		"%comments_count%",	// Number of comments for post
		"%comments_count_URL%",	// Count of number of comments linked to the top of the comments section
		"%comments_fancy%",	// Fancy reporting of comments: (see get_recent_tagmap())
		"%comments_fancy_URL%",	// Fancy reporting of comments linked to comments section
		"%comments_url%", 	// URL to top of comments section for post
		"%comments_URL%",	// Post title linked to the top of the comments section on post's permalink page
		"%last_comment_date%",  // Date of last comment for post
		"%last_comment_excerpt%",	// Excerpt of contents for last comment to post
		"%last_comment_excerpt_URL%",	// Excerpt of contents for last comment to post linked to that comment
		"%last_comment_id%",	// ID for last comment for post
		"%last_comment_time%",	// Time of last comment for post
		"%last_comment_url%",	// URL to most recent comment for post
		"%last_commenter%",	// Author of last comment for post
		"%last_commenter_URL%", // Linked (if author URL provided) of author of last comment for post
		"%pingbacks_count%",	// Number of pingbacks for post
		"%pingbacks_fancy%",	// Fancy report of trackbacks
		"%post_author%",	// Author for post
		"%post_author_count%",  // Number of posts made by post author
		"%post_author_posts%",  // Link to page of all of post author's posts
		"%post_author_url%",    // Linked (if URL provided) name of post author
		"%post_categories%",	// Name of each of post's categories
		"%post_categories_URL%",// Name of each of post's categories linked to respective category archive
		"%post_content%",	// Full content of the post (<p> and <br> tags stripped)
		"%post_content_full%",	// Full content of the post (<p> and <br> tags intact)
		"%post_date%",		// Date for post
		"%post_excerpt%",	// Excerpt for post (<p> and <br> tags stripped)
		"%post_excerpt_full%",	// Excerpt for post (<p> and <br> tags intact)
		"%post_excerpt_short%",	// Customizably shorter excerpt, suitable for sidebar usage
		"%post_id%",		// ID for post
		"%post_modified%",	// Last modified date for post
		"%post_status%",	// Post status for post
		"%post_time%",		// Time for post
		"%post_title%",		// Title for post
		"%post_url%",		// URL for post
		"%post_URL%",		// Post title linked to post's permalink page
		"%trackbacks_count",	// Number of trackbacks for post
		"%trackbacks_fancy",	// Fancy reporting of trackbacks
	);
	$ptags = array();
	foreach ($percent_tags as $tag) { if (strpos($format, $tag) !== false) $ptags[] = $tag; }
	$cptags = array();
	$custom_percent_tags = array(
		"%last_commenters\((.+)\)%",	// List of last commenters by name, linked to their comment, used like this: %last_commenters(limit,more,between)%
		"%post_custom\((.+)\)%",// Custom field for post, used like this: %post_custom(field,format,none)%, where format can contain %field% and/or %value%
		"%post_other\((.+)\)%",	// Other unaddressed post table field, used like this: %post_other(post_view_count)%
	);
	foreach ($custom_percent_tags as $tag) { if (preg_match("|$tag|imsU", $format, $matches)) $cptags[] = $matches[0]; }
	return c2c_get_recent_tagmap($posts, $format, $ptags, $cptags, $date_format);
} //end function c2c_get_recent_handler()

?>