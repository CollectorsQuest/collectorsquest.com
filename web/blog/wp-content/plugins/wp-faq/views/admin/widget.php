<p>
	<label for="<?php echo $this -> pre; ?>_widget_<?php echo $number; ?>_title">
		<?php _e('Title', $this -> plugin_name); ?> :
		<input class="widefat" id="<?php echo $this -> pre; ?>_widget_<?php echo $number; ?>_title" type="text" name="<?php echo $this -> pre; ?>-widget[<?php echo $number; ?>][title]" value="<?php echo $options[$number]['title']; ?>" />
	</label>
</p>

<p>
	<label for="<?php echo $this -> pre; ?>_widget_<?php echo $number; ?>_display">
		<?php _e('Display', $this -> plugin_name); ?> :
		<?php $displays = array("groups" => __('FAQ Groups', $this -> plugin_name), 'questions' => __('FAQ Questions', $this -> plugin_name)); ?>
		<select onchange="change_display('<?php echo $number; ?>', this.value);" id="<?php echo $this -> pre; ?>_widget_<?php echo $number; ?>_display" name="<?php echo $this -> pre; ?>-widget[<?php echo $number; ?>][display]" class="widefat">
			<?php foreach ($displays as $dkey => $dval) : ?>
			<option <?php echo ($options[$number]['display'] == $dkey) ? 'selected="selected"' : ''; ?> value="<?php echo $dkey; ?>"><?php echo $dval; ?></option>
			<?php endforeach; ?>
		</select>
	</label>
</p>

<div id="questions<?php echo $number; ?>div" style="display:<?php echo (!empty($options[$number]['display']) && $options[$number]['display'] == "questions") ? 'block' : 'none'; ?>;">
	<p>
    	<label for="<?php echo $this -> pre; ?>_widget_<?php echo $number; ?>_questions_number"><?php _e('Number', $this -> plugin_name); ?></label>
        <br/><input type="text" name="<?php echo $this -> pre; ?>-widget[<?php echo $number; ?>][questions_number]" value="<?php echo esc_attr(stripslashes($options[$number]['questions_number'])); ?>" id="<?php echo $this -> pre; ?>_widget_<?php echo $number; ?>_questions_number" />
    </p>
    
    <p>
    	<label for="<?php echo $this -> pre; ?>_widget_<?php echo $number; ?>_questions_order"><?php _e('Order', $this -> plugin_name); ?></label>
        <select name="<?php echo $this -> pre; ?>-widget[<?php echo $number; ?>][questions_order]" id="<?php echo $this -> pre; ?>_widget_<?php echo $number; ?>_questions_order">
        	<option value="ASC"><?php _e('Ascending', $this -> plugin_name); ?></option>
            <option value="DESC"><?php _e('Descending', $this -> plugin_name); ?></option>
        </select>
        <label for="<?php echo $this -> pre; ?>_widget_<?php echo $number; ?>_questions_orderby"><?php _e('by', $this -> plugin_name); ?></label>
        <select name="<?php echo $this -> pre; ?>-widget[<?php echo $number; ?>][questions_orderby]" id="<?php echo $this -> pre; ?>_widget_<?php echo $number; ?>_questions_orderby">
        	<option value="id"><?php _e('ID', $this -> plugin_name); ?></option>
            <option value="question"><?php _e('Question', $this -> plugin_name); ?></option>
            <option value="order"><?php _e('Order', $this -> plugin_name); ?></option>
            <option value="created"><?php _e('Created Date', $this -> plugin_name); ?></option>
            <option value="modified"><?php _e('Modified Date', $this -> plugin_name); ?></option>
        </select>
    </p>
</div>

<script type="text/javascript">
	function change_display(number, display) {	
		jQuery('#questions' + number + 'div').hide();
		
		if (display == "questions") {
			jQuery('#questions' + number + 'div').show();	
		}
	}
</script>