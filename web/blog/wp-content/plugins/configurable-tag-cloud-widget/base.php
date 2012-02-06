<?php
// Base functions for CTC Plugin

// Generates colors for tag links based on weight (number of posts for tag/category)
// If nothing gets passed in to the function for $mincolor and $maxcolor, it skips the function and uses the defined link color
// from the CSS.  If only one color is passed, it's used for both.
function ColorWeight($weight, $mincolor, $maxcolor) {
	if ($mincolor == "" && $maxcolor == "")
		return;

	if ($mincolor == "") {
		$color = $maxcolor;
		return $color;
	}

	if ($maxcolor == "") {
		$color = $mincolor;
		return $color;
	}

	if ($weight) {
		$weight = $weight/100;

		// Ugly hack to handle CSS shorthand color definitions (i.e., #fff instead of #ffffff)
		if (strlen($mincolor) == 4) {
			$r = substr($mincolor, 1, 1);
			$g = substr($mincolor, 2, 1);
			$b = substr($mincolor, 3, 1);

			$mincolor = "#$r$r$g$g$b$b";
		}

		if (strlen($maxcolor) == 4) {
			$r = substr($maxcolor, 1, 1);
			$g = substr($maxcolor, 2, 1);
			$b = substr($maxcolor, 3, 1);

			$maxcolor = "#$r$r$g$g$b$b";
		}

		$minr = hexdec(substr($mincolor, 1, 2));
		$ming = hexdec(substr($mincolor, 3, 2));
		$minb = hexdec(substr($mincolor, 5, 2));

		$maxr = hexdec(substr($maxcolor, 1, 2));
		$maxg = hexdec(substr($maxcolor, 3, 2));
		$maxb = hexdec(substr($maxcolor, 5, 2));

		$r = dechex(intval((($maxr - $minr) * $weight) + $minr));
		$g = dechex(intval((($maxg - $ming) * $weight) + $ming));
		$b = dechex(intval((($maxb - $minb) * $weight) + $minb));

		if (strlen($r) == 1) $r = "0".$r;
		if (strlen($g) == 1) $g = "0".$g;
		if (strlen($b) == 1) $b = "0".$b;

		$color = "#$r$g$b";
		$color = substr($color,0,7);
		
		return $color;
	}
}

// Custom get_tags function to support ignoring tags with less than $minnum posts.
function ctc_get_tags($args = '') {
	extract($args);
	$alltags = get_terms('post_tag', $args);

	$tags = array();

	foreach ($alltags as $tag) {
		if ($tag->count < $minnum || $tag->count > $maxnum)
			continue;
			
		array_push($tags, $tag);
	}

	if (empty($tags)) {
		$return = array();
		return $return;
	}

	$tags = apply_filters('get_tags', $tags, $args);
	return $tags;
}

// Tag cloud function for widget use
function wdgt_ctc($args = '') {
	$defaults = array(
		'title' => 'Tags', 'smallest' => 8, 'largest' => 22, 'unit' => 'pt', 'number' => '',
		'minnum' => 0, 'maxnum' => 100, 'format' => 'flat', 'orderby' => 'name', 'order' => 'ASC',
		'exclude' => '', 'include' => '', 'mincolor' => '', 'maxcolor' => '', 'showcount' => 'no',
		'showtags' => 'yes', 'showcats' => 'no', 'empty' => 'no', 'widget' => 'yes'
	);
	
	$options = get_option('widget_ctc');

	$args = wp_parse_args($args, $options, $defaults);

	extract($args);

	$tags = array();

	// Does the user want to show tags in the cloud (default is yes)?
	if ('yes' == $showtags) {
		$tags = ctc_get_tags(array_merge($args, array('minnum' => $minnum, 'maxnum' => $maxnum, 'orderby' => 'count', 'order' => 'DESC'))); // Always query top tags
	}

	// Does the user want to show categories in the cloud (default is no)?
	if ('yes' == $showcats) {
		// Do they want to see empty categories?
		if ('yes' == $empty) {
			$empty=0;
		} else {
			$empty=1;
		}

		$hide_empty = '&hide_empty='.$empty;
		
		$cats = get_categories("show_count=1&use_desc_for_title=0&hierarchical=0$hide_empty");

		$tagscats = array_merge($tags, $cats);
	} else {
		$tagscats = array_merge($tags);
	}
	
	if (empty($tagscats))
		return;

	$return = generate_tag_cloud($tagscats, $args); // Here's where those top tags get sorted according to $args
	if (is_wp_error($return))
		return false;
	else if (is_array($return)) {
		return $return;
	} else {
		echo apply_filters('wdgt_ctc', $return, $args);
	}
}

// Tag cloud function for template tag use
function ctc($args = '') {
	$defaults = array(
		'title' => 'Tags', 'smallest' => 8, 'largest' => 22, 'unit' => 'pt', 'number' => '',
		'minnum' => 0, 'maxnum' => 100, 'format' => 'flat', 'orderby' => 'name', 'order' => 'ASC',
		'exclude' => '', 'include' => '', 'mincolor' => '', 'maxcolor' => '', 'showcount' => 'no',
		'showtags' => 'yes', 'showcats' => 'no', 'empty' => 'no', 'widget' => 'no'
	);
	
	$options = get_option('template_ctc');

	$args = wp_parse_args($args, $options, $defaults);

	extract($args);

	$tags = array();

	// Does the user want tags in the cloud (default is yes)?
	if ('yes' == $showtags) {
		$tags = ctc_get_tags(array_merge($args, array('minnum' => $minnum, 'maxnum' => $maxnum, 'orderby' => 'count', 'order' => 'DESC'))); // Always query top tags
	}

	// If the user chooses to show categories...
	if ('yes' == $showcats) {
		// Do they want to see empty categories?
		if ('yes' == $empty) {
			$empty=0;
		} else {
			$empty=1;
		}

		$hide_empty = '&hide_empty='.$empty;
		
		$cats = get_categories("show_count=1&use_desc_for_title=0&hierarchical=0$hide_empty");

		$tagscats = array_merge($tags, $cats);
	} else {
		$tagscats = array_merge($tags);
	}
	
	if (empty($tagscats))
		return;

	$return = generate_tag_cloud($tagscats, $args); // Here's where those top tags get sorted according to $args
	if (is_wp_error($return))
		return false;
	else if (is_array($return)) {
		return $return;
	} else {
		echo apply_filters('ctc', $return, $args);
	}
}

// generate_tag_cloud() - function to create the links for the cloud based on the args from the ctc() function
// $tagscats = prefetched tag array (get_tags() & get_categories())
// $args['format'] = 'flat' => whitespace separated, 'list' => UL, 'array' => array()
// $args['orderby'] = 'name', 'count', 'rand'
function generate_tag_cloud($tagscats, $args = '') {
	global $wp_rewrite;

	$defaults = array(
		'title' => 'Tags', 'smallest' => 8, 'largest' => 22, 'unit' => 'pt', 'number' => '',
		'minnum' => 0, 'maxnum' => 100, 'format' => 'flat', 'orderby' => 'name', 'order' => 'ASC',
		'exclude' => '', 'include' => '', 'mincolor' => '', 'maxcolor' => '', 'showcount' => 'no',
		'showtags' => 'yes', 'showcats' => 'no', 'empty' => 'no', 'widget' => 'yes'
	);

	$widget = $defaults['widget'];

	if ('yes' == $widget) {
		$options = get_option('widget_ctc');
	} else {
		$options = get_option('template_ctc');
	}

	$args = wp_parse_args($args, $options, $defaults);

	extract($args);

	if (!$tagscats)
		return;
	$counts = $tag_links = array();
	if ('rand' == $orderby)
		shuffle($tagscats);
	foreach ((array) $tagscats as $tag) {
		$counts[$tag->name] = $tag->count;
		$cat = $tag->taxonomy;
		if ('category' == $cat) {
			$tag_links[$tag->name] = get_category_link($tag->term_id);
		} else {
			$tag_links[$tag->name] = get_tag_link($tag->term_id);
		}
		if (is_wp_error($tag_links[$tag->name]))
			return $tag_links[$tag->name];
		$tag_ids[$tag->name] = $tag->term_id;
	}

	$min_count = min($counts);
	$spread = max($counts) - $min_count;
	if ($spread <= 0)
		$spread = 1;
	$font_spread = $largest - $smallest;
	if ($font_spread <= 0)
		$font_spread = 1;
	$font_step = $font_spread / $spread;

	// SQL cannot save you; this is a second (potentially different) sort on a subset of data.
	if ('name' == $orderby)
		uksort($counts, 'strnatcasecmp');
	elseif ('count' == $orderby)
		asort($counts);

	if ('DESC' == $order)
		$counts = array_reverse($counts, true);

	$a = array();

	$rel = (is_object($wp_rewrite) && $wp_rewrite->using_permalinks()) ? ' rel="tag"' : '';

	foreach ($counts as $tag => $count) {
		if ($largest == $smallest)
			$tag_weight = $largest;
		else
			$tag_weight = ($smallest+(($count-$min_count)*$font_step));
		$diff = $largest-$smallest;
		if ($diff <= 0)
			$diff = 1;
		if ('yes' == $showcount)
			$postcount = '('.$count.')';
		$color_weight = round(99*($tag_weight-$smallest)/($diff)+1);
		$tag_color = ColorWeight($color_weight, $mincolor, $maxcolor);
		$tag_id = $tag_ids[$tag];
		$tag_link = clean_url($tag_links[$tag]);
		$tag = wp_specialchars($tag);
		if ($format=='list') {
			$a[] = "<li class=\"ctc-tag-li\"><a href=\"$tag_link\" class=\"ctc-tag tag-link-$tag_id\" title=\"".attribute_escape(sprintf(__('%d topics'), $count))."\"$rel style=\"font-size: ".$tag_weight
				."$unit;".(isset($tag_color) ? " color: $tag_color;" : "")
				."\">$tag</a>".('yes' == $showcount ? " $postcount" : "")."</li>";
		} elseif ($format=='drop') {
			$a[] = "<option value='$tag_link'>$tag".('yes' == $showcount ? " $postcount" : "")."</option>";
		} else {
			$a[] = "<a href=\"$tag_link\" class=\"ctc-tag tag-link-$tag_id\" title=\"".attribute_escape(sprintf(__('%d topics'), $count))."\"$rel style=\"font-size: ".$tag_weight
				."$unit;".(isset($tag_color) ? " color: $tag_color;" : "")
				."\">$tag"."</a>".('yes' == $showcount ? " $postcount" : "");
		}
	}

	switch ($format) :
	case 'array' :
		$return =& $a;
		break;
	case 'list' :
		$return = "<ul class='ctc-tag-cloud'>\n\t";
		$return .= join("\n\t", $a);
		$return .= "\n</ul>\n";
		break;
	case 'drop' :
		$return = "\n<select name=\"ctc-dropdown\" onchange='document.location.href=this.options[this.selectedIndex].value;'>\n\t<option value=\"\">Select Tag</option>\n\t";
		$return .= join("\n\t", $a);
		$return .= "</option>\n</select>\n";
		break;
	default :
		$return = join("\n", $a);
		break;
	endswitch;

	return apply_filters('generate_tag_cloud', $return, $tagscats, $args);
}

function install_defs() {
	$defaults = array(
		'title' => 'Tags', 'smallest' => 8, 'largest' => 22, 'unit' => 'pt', 'number' => '',
		'minnum' => 0, 'maxnum' => 100, 'format' => 'flat', 'orderby' => 'name', 'order' => 'ASC',
		'exclude' => '', 'include' => '', 'mincolor' => '', 'maxcolor' => '', 'showcount' => 'no',
		'showtags' => 'yes', 'showcats' => 'no', 'empty' => 'no'
	);

	add_option('template_ctc');
	add_option('widget_ctc');

	update_option('widget_ctc',$defaults);
	update_option('template_ctc',$defaults);
}

function uninstall_defs() {
	delete_option('template_ctc');
	delete_option('widget_ctc');
}
?>