<table class="form-table">
	<tbody>
		<tr>
			<th><label for="askregisteredY"><?php _e('Permission To Ask Questions', $this -> plugin_name); ?></label></th>
			<td>
				<label><input <?php echo $check = ($this -> get_option('askregistered') == "Y") ? 'checked="checked"' : ''; ?> type="radio" name="askregistered" value="Y" id="askregisteredY" />&nbsp;<?php _e('Registered Members', $this -> plugin_name); ?></label>
				<label><input <?php echo $check = ($this -> get_option('askregistered') == "N") ? 'checked="checked"' : ''; ?> type="radio" name="askregistered" value="N" id="askregisteredN" />&nbsp;<?php _e('All Users/Visitors', $this -> plugin_name); ?></label>
				<span class="howto"><?php _e('the type of users who are allowed to submit new questions on the front-end', $this -> plugin_name); ?></span>
			</td>
		</tr>
		<tr>
			<th><label for="adminnotifyY"><?php _e('Admin Notifications', $this -> plugin_name); ?></label></th>
			<td>
				<label><input onclick="jQuery('#adminnotifydiv').show();" <?php echo $check = ($this -> get_option('adminnotify') == "Y") ? 'checked="checked"' : ''; ?> type="radio" name="adminnotify" value="Y" id="adminnotifyY" /> <?php _e('Yes', $this -> plugin_name); ?></label>
				<label><input onclick="jQuery('#adminnotifydiv').hide();" <?php echo $check = ($this -> get_option('adminnotify') == "N") ? 'checked="checked"' : ''; ?> type="radio" name="adminnotify" value="N" id="adminnotifyN" /> <?php _e('No', $this -> plugin_name); ?></label>
				<span class="howto"><?php _e('receive an email notification when a new question has been asked', $this -> plugin_name); ?></span>
			</td>
		</tr>
	</tbody>
</table>

<div id="adminnotifydiv" style="display:<?php echo ($this -> get_option('adminnotify') == "Y") ? 'block' : 'none'; ?>;">
	<table class="form-table">
		<tbody>
			<tr>
				<th><label for="adminemail"><?php _e('Admin Email', $this -> plugin_name); ?></label></th>
				<td>
					<input class="widefat" type="text" name="adminemail" style="width:100%;" value="<?php echo $this -> get_option('adminemail'); ?>" id="adminemail" />
					<span class="howto"><?php _e('you may specify multiple email recipients, each separated by a comma', $this -> plugin_name); ?></span>
				</td>
			</tr>
		</tbody>
	</table>
</div>

<table class="form-table">
	<tbody>
		<tr>
			<th><label for="adminlinksY"><?php _e('Admin Actions on Front', $this -> plugin_name); ?></label></th>
			<td>
				<label><input <?php echo ($this -> get_option('adminlinks') == "Y") ? 'checked="checked"' : ''; ?> type="radio" name="adminlinks" value="Y" id="adminlinksY" /> <?php _e('Yes', $this -> plugin_name); ?></label>
				<label><input <?php echo ($this -> get_option('adminlinks') == "N") ? 'checked="checked"' : ''; ?> type="radio" name="adminlinks" value="N" id="adminlinksN" /> <?php _e('No', $this -> plugin_name); ?></label>
				<span class="howto"><?php _e('turn this on to show "Edit" and "Delete" links on the front-end for groups and questions', $this -> plugin_name); ?></span>
			</td>
		</tr>
		<tr>
			<th><label for="searchcontext_full"><?php _e('Search Context', $this -> plugin_name); ?></label></th>
			<td>
				<label><input <?php echo ($this -> get_option('searchcontext') == "questions") ? 'checked="checked"' : ''; ?> type="radio" name="searchcontext" value="questions" id="searchcontext_questions" /> <?php _e('Questions Only', $this -> plugin_name); ?></label>
				<label><input <?php echo ($this -> get_option('searchcontext') == "full") ? 'checked="checked"' : ''; ?> type="radio" name="searchcontext" value="full" id="searchcontext_full" /> <?php _e('Questions and Answers', $this -> plugin_name); ?></label>
			</td>
		</tr>
        <tr>
        	<th><label for="captchaN"><?php _e('Use Captcha for Questions', $this -> plugin_name); ?></label></th>
            <td>
            	<?php $rr_active = (is_plugin_active(plugin_basename('really-simple-captcha/really-simple-captcha.php'))) ? true : false; ?>
                <label><input <?php if (!$rr_active) { echo 'disabled="disabled"'; } else { echo ($this -> get_option('captcha') == "Y") ? 'checked="checked"' : ''; } ?> type="radio" name="captcha" value="Y" id="captchaY" /> <?php _e('Yes', $this -> plugin_name); ?></label>
                <label><input <?php if (!$rr_active) { echo 'disabled="disabled" checked="checked"'; } else { echo ($this -> get_option('captcha') == "N") ? 'checked="checked"' : ''; } ?> type="radio" name="captcha" value="N" id="captchaN" /> <?php _e('No', $this -> plugin_name); ?></label>
                <?php if (!$rr_active) : ?>
                	<br/><span style="color:red;"><?php _e('you need to install and activate the <a target="_blank" href="http://wordpress.org/extend/plugins/really-simple-captcha/">Really Simple Captcha plugin</a>.', $this -> plugin_name); ?></span>
                    <input type="hidden" name="captcha" value="N" />
                <?php endif; ?>
                <span class="howto"><?php _e('requires captcha image input upon submitting questions to the FAQ.', $this -> plugin_name); ?></span>
            </td>
        </tr>
	</tbody>
</table>