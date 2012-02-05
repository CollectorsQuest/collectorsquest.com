<?php
// WP 2.3-2.7 Sidebar widget

// This gets called at the plugins_loaded action
function widget_ctc_init() {
	// Check for the required API functions
	if (!function_exists('register_sidebar_widget') || !function_exists('register_widget_control'))
		return;

	// This saves options and prints the widget's config form.
	function widget_ctc_control() {
		$options = $newoptions = get_option('widget_ctc');
		if ($_POST['submit']) {
			$newoptions['title'] = strip_tags($_POST['title']);
			$newoptions['number'] = (int) $_POST['number'];
			$newoptions['minnum'] = (int) $_POST['minnum'];
			$newoptions['maxnum'] = (int) $_POST['maxnum'];
			$newoptions['unit'] = $_POST['unit'];
			$newoptions['smallest'] = strip_tags($_POST['smallest']);
			$newoptions['largest'] = strip_tags($_POST['largest']);
			$newoptions['mincolor'] = strip_tags($_POST['mincolor']);
			$newoptions['maxcolor'] = strip_tags($_POST['maxcolor']);
			$newoptions['format'] = $_POST['format'];
			$newoptions['orderby'] = $_POST['orderby'];
			$newoptions['order'] = $_POST['order'];
			$newoptions['showcount'] = $_POST['showcount'];
			$newoptions['showcats'] = $_POST['showcats'];
			$newoptions['showtags'] = $_POST['showtags'];
			$newoptions['empty'] = $_POST['empty'];
		}

		if ($options != $newoptions) {
			$options = $newoptions;
			update_option('widget_ctc', $options);
		}

		$title = htmlspecialchars($options['title'], ENT_QUOTES);
		$number = (int) $options['number'];
		$minnum = (int) $options['minnum'];
		$maxnum = (int) $options['maxnum'];
		$unit = $options['unit'];
		$smallest = htmlspecialchars($options['smallest'], ENT_QUOTES);
		$largest = htmlspecialchars($options['largest'], ENT_QUOTES);
		$mincolor = htmlspecialchars($options['mincolor'], ENT_QUOTES);
		$maxcolor = htmlspecialchars($options['maxcolor'], ENT_QUOTES);
		$format = $options['format'];
		$orderby = $options['orderby'];
		$order = $options['order'];
		$showcount = $options['showcount'];
		$showcats = $options['showcats'];
		$showtags = $options['showtags'];
		$empty = $options['empty'];
?>
	
	<div style="text-align:center">
		<h3>Configurable Tag Cloud Options</h3>
		<span style="line-height:15px"><br /><br /></span>

		<table>
			<tr><td><strong>Title</strong></td>
				<td><input style="text-align:right" type="text" id="title" name="title" value="<?php echo wp_specialchars($options['title'], true); ?>" /></td>
				<td style="font-size:0.75em">Title shown in sidebar.</td>
			</tr>
			<tr><td><strong>Number of Tags to Display</strong></td>
				<td><input style="text-align:right" type="text" id="number" name="number" value="<?php echo wp_specialchars($options['number'], true); ?>" /></td>
				<td style="font-size:0.75em">Controls the total number of tags in your cloud.</td>
			</tr>
			<tr><td><strong>Min. Number of Posts</strong></td>
				<td><input style="text-align:right" type="text" id="minnum" name="minnum" value="<?php echo wp_specialchars($options['minnum'], true); ?>" /></td>
				<td style="font-size:0.75em">Tags with less than this number of posts will not be displayed.</td>
			</tr>
			<tr><td><strong>Max. Number of Posts</strong></td>
				<td><input style="text-align:right" type="text" id="maxnum" name="maxnum" value="<?php echo wp_specialchars($options['maxnum'], true); ?>" /></td>
				<td style="font-size:0.75em">Tags with more than this number of posts will not be displayed.</td>
			</tr>
			<tr><td><strong>Font Display Unit</strong></td>
				<td style="text-align:right;">
					<select id="unit" name="unit" size="1" value="" />
			   			<option value="px" <?php echo ($unit=="px")?'selected':''?>>Pixel</option>
			   			<option value="pt" <?php echo ($unit=="pt")?'selected':''?>>Point</option>
			   			<option value="em" <?php echo ($unit=="em")?'selected':''?>>Em</option>
				   		<option value="%" <?php echo ($unit=="%")?'selected':''?>>Percent</option>
		   			</select>
				</td>
				<td style="font-size:0.75em">What unit to use for font sizes.</td>
			</tr>
			<tr><td><strong>Smallest Font Size</strong></td>
				<td><input style="text-align:right" type="text" id="smallest" name="smallest" value="<?php echo wp_specialchars($options['smallest'], true); ?>" /></td>
				<td style="font-size:0.75em">Tags will be displayed no smaller than this value.</td>
			</tr>
			<tr><td><strong>Largest Font Size</strong></td>
				<td><input style="text-align:right" type="text" id="largest" name="largest" value="<?php echo wp_specialchars($options['largest'], true); ?>" /></td>
				<td style="font-size:0.75em">Tags will be displayed no larger that this value.</td>
			</tr>
			<tr><td><strong>Min. Tag Color</strong></td>
				<td><input style="text-align:right" type="text" id="mincolor" name="mincolor" value="<?php echo wp_specialchars($options['mincolor'], true); ?>" /></td>
				<td style="font-size:0.75em">Beginning color for tag gradient.  Please include the #.</td>
			</tr>
			<tr><td><strong>Max. Tag Color</strong></td>
				<td><input style="text-align:right" type="text" id="maxcolor" name="maxcolor" value="<?php echo wp_specialchars($options['maxcolor'], true); ?>" /></td>
				<td style="font-size:0.75em">Ending color for tag gradient.  Please include the #.</td>
			</tr>
			<tr><td><strong>Cloud Format</strong></td>
				<td style="text-align:right;">
					<select id="format" name="format" size="1" value="" />
				   		<option value="flat" <?php echo ($format=="flat")?'selected':''?>>Flat</option>
				   		<option value="list" <?php echo ($format=="list")?'selected':''?>>List</option>
						<option value="drop" <?php echo ($format=="drop")?'selected':''?>>Dropdown</option>
		   			</select>
				</td>
				<td style="font-size:0.75em">How to display the cloud.</td>
			</tr>
			<tr><td><strong>Show Tags</strong></td>
				<td><input type="radio" id="showtags" name="showtags" <?php echo ($showtags=="yes")?'checked="checked"':''?> value="yes" /> Yes <input type="radio" id="showtags" name="showtags" <?php echo ($showtags=="no")?'checked="checked"':''?> value="no" /> No</td>
				<td style="font-size:0.75em">Display tags in cloud.</td>
			</tr>
			<tr><td><strong>Show Categories</strong></td>
				<td><input type="radio" id="showcats" name="showcats" <?php echo ($showcats=="yes")?'checked="checked"':''?> value="yes" /> Yes <input type="radio" id="showcats" name="showcats" <?php echo ($showcats=="no")?'checked="checked"':''?> value="no" /> No</td>
				<td style="font-size:0.75em">Display categories in cloud.</td>
			</tr>
			<tr><td><strong>Show Empty?</strong></td>
				<td><input type="radio" id="empty" name="empty" <?php echo ($empty=="yes")?'checked="checked"':''?> value="yes" /> Yes <input type="radio" id="empty" name="empty" <?php echo ($empty=="no")?'checked="checked"':''?> value="no" /> No</td>
				<td style="font-size:0.75em">Display empty categories in cloud.</td>
			</tr>
			<tr><td><strong>Display Post Count?</strong></td>
				<td><input type="radio" id="showcount" name="showcount" <?php echo ($showcount=="yes")?'checked="checked"':''?> value="yes" /> Yes <input type="radio" id="showcount" name="showcount" <?php echo ($showcount=="no")?'checked="checked"':''?> value="no" /> No</td>
				<td style="font-size:0.75em">Show number of posts for each tag.</td>
			</tr>
			<tr><td><strong>Sort By</strong></td>
				<td style="text-align:right;">
					<select id="orderby" name="orderby" size="1" value="" />
				   		<option value="name" <?php echo ($orderby=="name")?'selected':''?>>Name</option>
				   		<option value="count" <?php echo ($orderby=="count")?'selected':''?>>Count</option>
				   		<option value="rand" <?php echo ($orderby=="rand")?'selected':''?>>Random</option>
		   			</select>
				</td>
				<td style="font-size:0.75em">What field to sort by.</td>
			</tr>
			<tr><td><strong>Sort Order</strong></td>
				<td style="font-size:0.85em"><input type="radio" id="order" name="order" <?php echo ($order=="ASC")?'checked="checked"':''?> value="ASC" /> Ascending <input type="radio" id="order" name="order" <?php echo ($order=="DESC")?'checked="checked"':''?> value="DESC" /> Descending</td>
				<td style="font-size:0.75em">Direction of sort.</td>
			</tr>
		</table>
		<input type="hidden" name="submit" id="submit" value="1" />
	</div>
<?php
	}

	// The widget itself
	function widget_ctc($args) {
		extract($args);
		$defaults = array(
			'title' => 'Tags', 'smallest' => 8, 'largest' => 22, 'unit' => 'pt', 'number' => '',
			'minnum' => 0, 'maxnum' => 100, 'format' => 'flat', 'orderby' => 'name', 'order' => 'ASC',
			'exclude' => '', 'include' => '', 'mincolor' => '', 'maxcolor' => '', 'showcount' => 'no',
			'showtags' => 'yes', 'showcats' => 'no', 'empty' => 'no', 'widget' => 'yes'
		);
		$options = (array) get_option('widget_ctc');

		foreach ($defaults as $key => $value)
			if ($options[$key] == "")
				$options[$key] = $defaults[$key];

		$tagcloud = 'smallest='.$options['smallest'];
		$tagcloud.= '&largest='.$options['largest'];
		$tagcloud.= '&mincolor='.$options['mincolor'];
		$tagcloud.= '&maxcolor='.$options['maxcolor'];
		$tagcloud.= '&unit='.$options['unit'];
		$tagcloud.= '&format='.$options['format'];
		$tagcloud.= '&number='.$options['number'];
		$tagcloud.= '&minnum='.$options['minnum'];
		$tagcloud.= '&maxnum='.$options['maxnum'];
		$tagcloud.= '&orderby='.$options['orderby'];
		$tagcloud.= '&order='.$options['order'];
		$tagcloud.= '&showcount='.$options['showcount'];
		$tagcloud.= '&showcats='.$options['showcats'];
		$tagcloud.= '&showtags='.$options['showtags'];
		$tagcloud.= '&empty='.$options['empty'];

		echo $before_widget;
		echo $before_title.$options['title'].$after_title;
		echo '<div class="ctc">';
			wdgt_ctc($tagcloud);
		echo '</div>';
		echo $after_widget;
	}

	// Tell Dynamic Sidebar about our new widget and its control
	register_sidebar_widget(array('CTC', 'widgets'), 'widget_ctc');
	register_widget_control(array('CTC', 'widgets'), 'widget_ctc_control', 520, 510);
	
}

// Delay plugin execution to ensure Dynamic Sidebar has a chance to load first
add_action('widgets_init', 'widget_ctc_init');
?>