<table class="form-table">
	<tr>
    	<th><label for="requireemail_Y"><?php _e('Capture Email Address', $this -> plugin_name); ?></label></th>
        <td>
        	<label><input <?php echo ($this -> get_option('requireemail') == "Y") ? 'checked="checked"' : ''; ?> onclick="jQuery('#requireemail_div').show();" type="radio" name="requireemail" value="Y" id="requireemail_Y" /> <?php _e('Yes', $this -> plugin_name); ?></label>
            <label><input <?php echo ($this -> get_option('requireemail') == "N") ? 'checked="checked"' : ''; ?> onclick="jQuery('#requireemail_div').hide();" type="radio" name="requireemail" value="N" id="requireemail_N" /> <?php _e('No', $this -> plugin_name); ?></label>
            <span class="howto"><?php _e('require an email address from a user when submitting a question', $this -> plugin_name); ?></span>
        </td>
    </tr>
</table>

<div id="requireemail_div" style="display:<?php echo ($this -> get_option('requireemail') == "Y") ? 'block' : 'none'; ?>;">
	<table class="form-table">
    	<tbody>
        	<tr>
            	<th><label for="notifywhenanswered_Y"><?php _e('Notify When Answered', $this -> plugin_name); ?></label></th>
                <td>
                	<label><input <?php echo ($this -> get_option('notifywhenanswered') == "Y") ? 'checked="checked"' : ''; ?> type="radio" name="notifywhenanswered" value="Y" id="notifywhenanswered_Y" /> <?php _e('Yes', $this -> plugin_name); ?></label>
                    <label><input <?php echo ($this -> get_option('notifywhenanswered') == "N") ? 'checked="checked"' : ''; ?> type="radio" name="notifywhenanswered" value="N" id="notifywhenanswered_N" /> <?php _e('No', $this -> plugin_name); ?></label>
                	<span class="howto"><?php _e('send an email notification to the user when the question has been answered', $this -> plugin_name); ?></span>
                </td>
            </tr>
        </tbody>
    </table>
</div>