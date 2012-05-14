<table class="form-table">
	<tbody>
    	<tr>
        	<th><label for="theme_folder"><?php _e('Theme Folder', $this -> plugin_name); ?></label></th>
            <td>
            	<?php if ($themefolders = $this -> get_themefolders()) : ?>
                	<select name="theme_folder" id="theme_folder">
                    	<?php foreach ($themefolders as $themefolder) : ?>
                        	<option <?php echo ($this -> get_option('theme_folder') == $themefolder) ? 'selected="selected"' : ''; ?> name="<?php echo $themefolder; ?>"><?php echo $themefolder; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <span class="howto"><?php _e('Select the folder inside "wp-faq/views/" to take shop view files from. eg. "default"', $this -> plugin_name); ?></span>
                <?php endif; ?>
            </td>
        </tr>
        <tr>
        	<th><label for="theme_stylesheet_Y"><?php _e('Use Theme Style File?', $this -> plugin_name); ?></label></th>
            <td>
            	<label><input <?php echo ($this -> get_option('theme_stylesheet') == "Y") ? 'checked="checked"' : ''; ?> type="radio" name="theme_stylesheet" value="Y" id="theme_stylesheet_Y" /> <?php _e('Yes', $this -> plugin_name); ?></label>
                <label><input <?php echo ($this -> get_option('theme_stylesheet') == "N") ? 'checked="checked"' : ''; ?> type="radio" name="theme_stylesheet" value="N" id="theme_stylesheet_N" /> <?php _e('No', $this -> plugin_name); ?></label>
            	<span class="howto"><?php _e('This will load a style.css file inside the theme folder above.', $this -> plugin_name); ?></span>
            </td>
        </tr>
    	<tr>
        	<th><label for="customcss_N"><?php _e('Use Custom CSS', $this -> plugin_name); ?></label></th>
            <td>
            	<label><input onclick="jQuery('#customcsscode_div').show();" <?php echo ($this -> get_option('customcss') == "Y") ? 'checked="checked"' : ''; ?> type="radio" name="customcss" value="Y" id="customcss_Y" /> <?php _e('Yes', $this -> plugin_name); ?></label>
                <label><input onclick="jQuery('#customcsscode_div').hide();" <?php echo ($this -> get_option('customcss') == "N") ? 'checked="checked"' : ''; ?> type="radio" name="customcss" value="N" id="customcss_N" /> <?php _e('No', $this -> plugin_name); ?></label>
            </td>
        </tr>
    </tbody>
</table>

<div id="customcsscode_div" style="display:<?php echo ($this -> get_option('customcss') == "Y") ? 'block' : 'none'; ?>;">
	<textarea name="customcsscode" class="widefat" cols="100%" rows="10"><?php echo stripslashes($this -> get_option('customcsscode')); ?></textarea>
</div>