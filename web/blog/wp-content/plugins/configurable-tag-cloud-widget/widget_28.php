<?php
// WP 2.8+ Sidebar widget

// Add our function to the widgets_init hook.
add_action('widgets_init', 'CTC_load');

function CTC_load() {
	register_widget('CTC_Widget');
}

class CTC_Widget extends WP_Widget {
	function CTC_Widget() {
		/* Widget settings. */
		$widget_ops = array('classname' => 'ctc', 'description' => __("Customizable cloud of your blog's tags."));

		/* Widget control settings. */
		$control_ops = array('width' => 420, 'height' => 510, 'id_base' => 'ctc');

		/* Create the widget. */
		$this->WP_Widget('CTC', __('CTC'), $widget_ops, $control_ops);
		$this->alt_option_name = 'widget_ctc';
	}

	function widget($args, $instance) {
		$cache = wp_cache_get('widget_ctc', 'widget');

		if (!is_array($cache))
			$cache = array();

		if (isset($cache[$args['widget_id']]))
			return $cache[$args['widget_id']];

		ob_start();
		extract($args);
		$title = apply_filters('widget_title', empty($instance['title']) ? __('Tags') : $instance['title']);

		$tagcloud = 'smallest='.$instance['smallest'];
		$tagcloud.= '&largest='.$instance['largest'];
		$tagcloud.= '&mincolor='.$instance['mincolor'];
		$tagcloud.= '&maxcolor='.$instance['maxcolor'];
		$tagcloud.= '&unit='.$instance['unit'];
		$tagcloud.= '&format='.$instance['format'];
		$tagcloud.= '&number='.$instance['number'];
		$tagcloud.= '&minnum='.$instance['minnum'];
		$tagcloud.= '&maxnum='.$instance['maxnum'];
		$tagcloud.= '&orderby='.$instance['orderby'];
		$tagcloud.= '&order='.$instance['order'];
		$tagcloud.= '&showcount='.$instance['showcount'];
		$tagcloud.= '&showcats='.$instance['showcats'];
		$tagcloud.= '&showtags='.$instance['showtags'];
		$tagcloud.= '&empty='.$instance['empty'];
		$tagcloud.= '&widget=yes';

		echo $before_widget;
		echo $before_title.$title.$after_title;
		echo '<div class="ctc">';
			wdgt_ctc($tagcloud);
		echo '</div>';
		echo $after_widget;

		$cache[$args['widget_id']] = ob_get_flush();
		wp_cache_add('widget_ctc', $cache, 'widget');
	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;

		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = (int) $new_instance['number'];
		$instance['minnum'] = (int) $new_instance['minnum'];
		$instance['maxnum'] = (int) $new_instance['maxnum'];
		$instance['unit'] = $new_instance['unit'];
		$instance['smallest'] = $new_instance['smallest'];
		$instance['largest'] = $new_instance['largest'];
		$instance['mincolor'] = strip_tags($new_instance['mincolor']);
		$instance['maxcolor'] = strip_tags($new_instance['maxcolor']);
		$instance['format'] = $new_instance['format'];
		$instance['orderby'] = $new_instance['orderby'];
		$instance['order'] = $new_instance['order'];
		$instance['showcount'] = $new_instance['showcount'];
		$instance['showcats'] = $new_instance['showcats'];
		$instance['showtags'] = $new_instance['showtags'];
		$instance['empty'] = $new_instance['empty'];
		$this->flush_widget_cache();

		$alloptions = wp_cache_get('alloptions', 'options');
		if (isset($alloptions['widget_ctc']))
			delete_option('widget_ctc');

		return $instance;
	}

	function flush_widget_cache() {
		wp_cache_delete('widget_ctc', 'widget');
	}

	function form($instance) {
		/* Set up some default widget settings. */
		$defaults = array(
			'title' => 'Tags', 'smallest' => 8, 'largest' => 22, 'unit' => 'pt', 'number' => '',
			'minnum' => 0, 'maxnum' => 100, 'format' => 'flat', 'orderby' => 'name', 'order' => 'ASC',
			'exclude' => '', 'include' => '', 'mincolor' => '', 'maxcolor' => '', 'showcount' => 'no',
			'showtags' => 'yes', 'showcats' => 'no', 'empty' => 'no', 'widget' => 'yes'
		);
		$instance = wp_parse_args((array) $instance, $defaults);
?>
	<div style="text-align:center">
		<h3>Configurable Tag Cloud Options</h3>
		<span style="line-height:15px"><br /><br /></span>
		<table>
			<tr>
				<td><strong><?php _e('Title') ?></strong></td>
				<td><input style="text-align:right" type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo esc_attr($instance['title']); ?>" /></td>
				<td style="font-size:0.75em"><?php _e('Title shown in sidebar.') ?></td>
			</tr>
			<tr>
				<td><strong><?php _e('Number of Tags to Display') ?></strong></td>
				<td><input style="text-align:right" type="text" id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" value="<?php echo esc_attr($instance['number']); ?>" /></td>
				<td style="font-size:0.75em"><?php _e('Controls the total number of tags in your cloud.') ?></td>
			</tr>
			<tr>
				<td><strong><?php _e('Min. Number of Posts') ?></strong></td>
				<td><input style="text-align:right" type="text" id="<?php echo $this->get_field_id('minnum'); ?>" name="<?php echo $this->get_field_name('minnum'); ?>" value="<?php echo esc_attr($instance['minnum']); ?>" /></td>
				<td style="font-size:0.75em"><?php _e('Tags with less than this number of posts will not be displayed.') ?></td>
			</tr>
			<tr>
				<td><strong><?php _e('Max. Number of Posts') ?></strong></td>
				<td><input style="text-align:right" type="text" id="<?php echo $this->get_field_id('maxnum'); ?>" name="<?php echo $this->get_field_name('maxnum'); ?>" value="<?php echo esc_attr($instance['maxnum']); ?>" /></td>
				<td style="font-size:0.75em"><?php _e('Tags with more than this number of posts will not be displayed.') ?></td>
			</tr>
			<tr>
				<td><strong><?php _e('Font Display Unit') ?></strong></td>
				<td style="text-align:right;">
					<select id="<?php echo $this->get_field_id('unit'); ?>" name="<?php echo $this->get_field_name('unit'); ?>">
						<option value="px" <?php if ('px' == $instance['unit']) echo 'selected="selected"'; ?>>Pixel</option>
						<option value="pt" <?php if ('pt' == $instance['unit']) echo 'selected="selected"'; ?>>Point</option>
						<option value="em" <?php if ('em' == $instance['unit']) echo 'selected="selected"'; ?>>Em</option>
						<option value="%" <?php if ('%' == $instance['unit']) echo 'selected="selected"'; ?>>Percent</option>
					</select>
				</td>
				<td style="font-size:0.75em"><?php _e('What unit to use for font sizes.') ?></td>
			</tr>
			<tr>
				<td><strong><?php _e('Smallest Font Size') ?></strong></td>
				<td><input style="text-align:right" type="text" id="<?php echo $this->get_field_id('smallest'); ?>" name="<?php echo $this->get_field_name('smallest'); ?>" value="<?php echo esc_attr($instance['smallest']); ?>" /></td>
				<td style="font-size:0.75em"><?php _e('Tags will be displayed no smaller than this value.') ?></td>
			</tr>
			<tr>
				<td><strong><?php _e('Largest Font Size') ?></strong></td>
				<td><input style="text-align:right" type="text" id="<?php echo $this->get_field_id('largest'); ?>" name="<?php echo $this->get_field_name('largest'); ?>" value="<?php echo esc_attr($instance['largest'], true); ?>" /></td>
				<td style="font-size:0.75em"><?php _e('Tags will be displayed no larger that this value.') ?></td>
			</tr>
			<tr>
				<td><strong><?php _e('Min. Tag Color') ?></strong></td>
				<td><input style="text-align:right" type="text" id="<?php echo $this->get_field_id('mincolor'); ?>" name="<?php echo $this->get_field_name('mincolor'); ?>" value="<?php echo esc_attr($instance['mincolor'], true); ?>" /></td>
				<td style="font-size:0.75em"><?php _e('Beginning color for tag gradient.  Please include the #.') ?></td>
			</tr>
			<tr>
				<td><strong><?php _e('Max. Tag Color') ?></strong></td>
				<td><input style="text-align:right" type="text" id="<?php echo $this->get_field_id('maxcolor'); ?>" name="<?php echo $this->get_field_name('maxcolor'); ?>" value="<?php echo esc_attr($instance['maxcolor'], true); ?>" /></td>
				<td style="font-size:0.75em"><?php _e('Ending color for tag gradient.  Please include the #.') ?></td>
			</tr>
			<tr>
				<td><strong><?php _e('Cloud Format') ?></strong></td>
				<td style="text-align:right;">
					<select id="<?php echo $this->get_field_id('format'); ?>" name="<?php echo $this->get_field_name('format'); ?>" size="1" value="">
						<option value="flat" <?php if ('flat' == $instance['format']) echo 'selected="selected"'; ?>>Flat</option>
						<option value="list" <?php if ('list' == $instance['format']) echo 'selected="selected"'; ?>>List</option>
						<option value="drop" <?php if ('drop' == $instance['format']) echo 'selected="selected"'; ?>>Dropdown</option>
		   			</select>
				</td>
				<td style="font-size:0.75em"><?php _e('How to display the cloud.') ?></td>
			</tr>
			<tr>
				<td><strong><?php _e('Show Tags') ?></strong></td>
				<td style="text-align:right;">
					<select id="<?php echo $this->get_field_id('showtags'); ?>" name="<?php echo $this->get_field_name('showtags'); ?>" size="1" value="">
						<option value="yes" <?php if ('yes' == $instance['showtags']) echo 'selected="selected"'; ?>>Yes</option>
						<option value="no" <?php if ('no' == $instance['showtags']) echo 'selected="selected"'; ?>>No</option>
		   			</select>
				</td>
				<td style="font-size:0.75em"><?php _e('Display tags in cloud.') ?></td>
			</tr>
			<tr>
				<td><strong><?php _e('Show Categories') ?></strong></td>
				<td style="text-align:right;">
					<select id="<?php echo $this->get_field_id('showcats'); ?>" name="<?php echo $this->get_field_name('showcats'); ?>" size="1" value="">
						<option value="no" <?php if ('no' == $instance['showcats']) echo 'selected="selected"'; ?>>No</option>
						<option value="yes" <?php if ('yes' == $instance['showcats']) echo 'selected="selected"'; ?>>Yes</option>
		   			</select>
				</td>
				<td style="font-size:0.75em"><?php _e('Display categories in cloud.') ?></td>
			</tr>
			<tr>
				<td><strong><?php _e('Show Empty') ?></strong></td>
				<td style="text-align:right;">
					<select id="<?php echo $this->get_field_id('empty'); ?>" name="<?php echo $this->get_field_name('empty'); ?>" size="1" value="">
						<option value="no" <?php if ('no' == $instance['empty']) echo 'selected="selected"'; ?>>No</option>
						<option value="yes" <?php if ('yes' == $instance['empty']) echo 'selected="selected"'; ?>>Yes</option>
		   			</select>
				</td>
				<td style="font-size:0.75em"><?php _e('Display empty categories in cloud.') ?></td>
			</tr>
			<tr>
				<td><strong><?php _e('Display Post Count?') ?></strong></td>
				<td style="text-align:right;">
					<select id="<?php echo $this->get_field_id('showcount'); ?>" name="<?php echo $this->get_field_name('showcount'); ?>" size="1" value="">
						<option value="no" <?php if ('no' == $instance['showcount']) echo 'selected="selected"'; ?>>No</option>
						<option value="yes" <?php if ('yes' == $instance['showcount']) echo 'selected="selected"'; ?>>Yes</option>
		   			</select>
				</td>
				<td style="font-size:0.75em"><?php _e('Show number of posts for each tag.') ?></td>
			</tr>
			<tr>
				<td><strong><?php _e('Sort By') ?></strong></td>
				<td style="text-align:right;">
					<select id="<?php echo $this->get_field_id('orderby'); ?>" name="<?php echo $this->get_field_name('orderby'); ?>" size="1" value="">
						<option value="name" <?php if ('name' == $instance['orderby']) echo 'selected="selected"'; ?>>Name</option>
						<option value="count" <?php if ('count' == $instance['orderby']) echo 'selected="selected"'; ?>>Count</option>
						<option value="rand" <?php if ('rand' == $instance['orderby']) echo 'selected="selected"'; ?>>Random</option>
		   			</select>
				</td>
				<td style="font-size:0.75em"><?php _e('What field to sort by.') ?></td>
			</tr>
			<tr>
				<td><strong><?php _e('Sort Order') ?></strong></td>
				<td style="text-align:right;">
					<select id="<?php echo $this->get_field_id('order'); ?>" name="<?php echo $this->get_field_name('order'); ?>" size="1" value="">
						<option value="ASC" <?php if ('ASC' == $instance['order']) echo 'selected="selected"'; ?>>Ascending</option>
						<option value="DESC" <?php if ('DESC' == $instance['order']) echo 'selected="selected"'; ?>>Descending</option>
		   			</select>
				</td>
				<td style="font-size:0.75em"><?php _e('Direction of sort.') ?></td>
			</tr>
		</table>
	</div>
<?php
	}
}
?>