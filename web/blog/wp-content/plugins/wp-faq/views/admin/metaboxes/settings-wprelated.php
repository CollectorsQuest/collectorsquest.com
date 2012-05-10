<table class="form-table">
	<tbody>
		<tr>
			<th><label for="<?php echo $this -> pre; ?>accesslevel"><?php _e('User Access Level', $this -> plugin_name); ?></label></th>
			<td>			
				<select class="widefat" style="width:auto;" id="<?php echo $this -> pre; ?>accesslevel" name="accesslevel">
					<?php for ($i = 1; $i <= 10; $i++) : ?>
					<?php
					
					switch ($i) {
						case 1		:
							$display = $i . ' (' . __('Contributor', $this -> plugin_name) . ')';
							break;
						case 2		:
							$display = $i . ' (' . __('Authors', $this -> plugin_name) . ')';
							break;
						case 7		:
							$display = $i . ' (' . __('Editors', $this -> plugin_name) . ')';
							break;
						case 10		:
							$display = $i . ' (' . __('Administrators', $this -> plugin_name) . ')';
							break;
						default		:
							$display = $i;
							break;
					}
					
					?>
					<option <?php echo ($this -> get_option('accesslevel') == $i) ? 'selected="selected"' : ''; ?> value="<?php echo $i; ?>"><?php echo $display; ?></option>
					<?php endfor; ?>
				</select>
				
				<span class="howto"><?php _e('subscriber role cannot use the FAQ plugin', $this -> plugin_name); ?></span>
			</td>
		</tr>
        <tr>
        	<th><label for="edimagespost"><?php _e('Images Post ID', $this -> plugin_name); ?></label></th>
            <td>
            	<input style="width:65px;" type="text" name="edimagespost" value="<?php echo esc_attr(stripslashes($this -> get_option('edimagespost'))); ?>" id="edimagespost" />
            	<span class="howto"><?php _e('Post to use for saving the FAQ plugin images through the media uploader.', $this -> plugin_name); ?></span>
            </td>
        </tr>
	</tbody>
</table>