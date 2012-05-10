<div class="wrap <?php echo $this -> pre; ?>">
	<h2><?php _e('Save a Custom Field', $this -> plugin_name); ?></h2>
	
	<form action="?page=<?php echo $this -> sections -> fields; ?>&amp;method=save" method="post">
		<?php echo $wpfaqForm -> hidden('wpfaqField.id'); ?>
	
		<table class="form-table">
			<tbody>
				<tr>
					<th><label for="wpfaqField.title"><?php _e('Title', $this -> plugin_name); ?></label></th>
					<td>
						<?php echo $wpfaqForm -> text('wpfaqField.title', array('onkeyup' => "wpfaq_titletoslug(this.value);")); ?>
						<span class="howto"><?php _e('Title of this custom field for identification purposes.', $this -> plugin_name); ?></span>
					</td>
				</tr>
				<tr>
					<th><label for="wpfaqField.slug"><?php _e('Slug', $this -> plugin_name); ?></label></th>
					<td>
						<?php $slug = $wpfaqHtml -> field_value('wpfaqField.slug'); ?>
						<input type="text" name="slugdisplay" disabled="disabled" value="<?php echo esc_attr(stripslashes($slug)); ?>" id="slugdisplay" class="widefat" />
						<?php echo $wpfaqHtml -> field_error('wpfaqField.slug'); ?>
						<?php echo $wpfaqForm -> hidden('wpfaqField.slug'); ?>
						<span class="howto"><?php _e('Slug for database and internal purposes.', $this -> plugin_name); ?></span>
					</td>
				</tr>
				<tr>
					<th><label for="wpfaqField.caption"><?php _e('Caption/Description', $this -> plugin_name); ?></label></th>
					<td>
						<?php echo $wpfaqForm -> text('wpfaqField.caption'); ?>
						<span class="howto"><small><?php _e('(optional)', $this -> plugin_name); ?></small> <?php _e('Caption/description to show below the custom field.', $this -> plugin_name); ?></span>
					</td>
				</tr>
				<tr>
					<th><label for="wpfaqField.fieldtype"><?php _e('Field Type', $this -> plugin_name); ?></label></th>
					<td>
						<?php
						
						$fieldtypes = array(
							'text'					=>	__('Text Input', $this -> plugin_name),
							'checkbox'				=>	__('Checkboxes List', $this -> plugin_name),
							'radio'					=>	__('Radio Buttons List', $this -> plugin_name),
							'select'				=>	__('Select Drop Down', $this -> plugin_name),
							'textarea'				=>	__('Textarea Box', $this -> plugin_name),
						);
						
						?>
						<?php echo $wpfaqForm -> select('wpfaqField.fieldtype', $fieldtypes, array('onchange' => "fieldtype_change(this.value);")); ?>
						<span class="howto"><?php _e('Choose the type of field to output for this custom field.', $this -> plugin_name); ?></span>
					</td>
				</tr>
			</tbody>
		</table>
		
		<?php $fieldtype = $wpfaqHtml -> field_value('wpfaqField.fieldtype'); ?>
		<div id="fieldtypediv" style="display:<?php echo ($fieldtype == "checkbox" || $fieldtype == "radio" || $fieldtype == "select") ? 'block' : 'none'; ?>;">
			<table class="form-table">
				<tbody>
					<tr>
						<th><label for="wpfaqField.fieldoptions"><?php _e('Field Options', $this -> plugin_name); ?></label></th>
						<td>
							<?php $fieldoptions = $wpfaqHtml -> field_value('wpfaqField.fieldoptions'); ?>
							<?php if (!empty($fieldoptions)) : ?>
								<?php $wpfaqField -> data -> fieldoptions = @implode("\n", unserialize($fieldoptions)); ?>
							<?php endif; ?>
							<?php echo $wpfaqForm -> textarea('wpfaqField.fieldoptions'); ?>
							<span class="howto"><?php _e('Options for this field, each option on a newline.', $this -> plugin_name); ?></span>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		
		<table class="form-table">
			<tbody>
				<tr>
					<th><label for="wpfaqField.required"><?php _e('Required', $this -> plugin_name); ?></label></th>
					<td>
						<?php $required = array("Y" => __('Yes', $this -> plugin_name), "N" => __('No', $this -> plugin_name)); ?>
						<?php echo $wpfaqForm -> radio('wpfaqField.required', $required, array('onclick' => "required_change(this.value);", 'separator' => false, 'default' => "N")); ?>
						<span class="howto"><?php _e('Should this custom field be mandatory to be filled in?', $this -> plugin_name); ?></span>
					</td>
				</tr>
			</tbody>
		</table>
		
		<div id="requireddiv" style="display:<?php echo ($wpfaqHtml -> field_value('wpfaqField.required') == "Y") ? 'block' : 'none'; ?>;">
			<table class="form-table">
				<tbody>
					<tr>
						<th><label for="wpfaqField.errormessage"><?php _e('Error Message', $this -> plugin_name); ?></label></th>
						<td>
							<?php echo $wpfaqForm -> text('wpfaqField.errormessage'); ?>
							<span class="howto"><?php _e('Error message to display to the user when the field is not filled in.', $this -> plugin_name); ?></span>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	
		<p class="submit">
			<?php echo $wpfaqForm -> submit(__('Save Custom Field', $this -> plugin_name)); ?>
		</p>
	</form>
</div>

<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery('[name="wpfaqField[title]"]').Watermark('<?php _e('Enter field title here', $this -> plugin_name); ?>');
	jQuery('[name="wpfaqField[caption]"]').Watermark('<?php _e('Enter caption/description here', $this -> plugin_name); ?>');
	jQuery('[name="wpfaqField[errormessage]"]').Watermark('<?php _e('Enter error message here', $this -> plugin_name); ?>');
});

function required_change(required) {
	if (required == "Y") { jQuery('#requireddiv').show(); }
	else if (required == "N") { jQuery('#requireddiv').hide(); }
}

function fieldtype_change(fieldtype) {
	if (fieldtype == "checkbox" || fieldtype == "radio" || fieldtype == "select") {
		jQuery('#fieldtypediv').show();
	} else {
		jQuery('#fieldtypediv').hide();
	}
}

function wpfaq_titletoslug(title) {
	var title = title.toLowerCase();
	var slug = title.replace(/[^0-9a-z]+/g, "");
	jQuery('[name="wpfaqField[slug]"]').val(slug);	
	jQuery('#slugdisplay').val(slug);
}
</script>