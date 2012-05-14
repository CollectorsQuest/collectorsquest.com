<table class="form-table">
	<tbody>
		<tr>
			<th><label for=""><?php _e('Questions Bullet', $this -> plugin_name); ?></label></th>
			<td>
				<?php $bullets = array('none', 'black', 'blue', 'green', 'orange', 'pink', 'purple', 'red', 'star', 'white', 'yellow'); ?>
				<?php $accbullet = $this -> get_option('accbullet'); ?>
				<?php foreach ($bullets as $bullet) : ?>
					<label><input <?php echo ($accbullet == $bullet) ? 'checked="checked"' : ''; ?> type="radio" name="accbullet" value="<?php echo $bullet; ?>" /> <?php if ($bullet != "none") : ?><?php echo $wpfaqHtml -> image($bullet . '.png', $this -> url() . '/images/bullets/'); ?> <?php endif; ?><?php echo ucfirst($bullet); ?></label><br/>
				<?php endforeach; ?>
			</td>
		</tr>
		<tr>
			<th><label for="accY"><?php _e('Sliding Accordion', $this -> plugin_name); ?></label></th>
			<td>
				<label><input onclick="jQuery('#accdiv').show(); jQuery('#clickdiv').hide();" <?php echo ($this -> get_option('acc') == "Y") ? 'checked="checked"' : ''; ?> type="radio" name="acc" value="Y" id="accY" /> <?php _e('Yes', $this -> plugin_name); ?></label>
				<label><input onclick="jQuery('#accdiv').hide(); jQuery('#clickdiv').show();" <?php echo ($this -> get_option('acc') == "N") ? 'checked="checked"' : ''; ?> type="radio" name="acc" value="N" id="accN" /> <?php _e('No', $this -> plugin_name); ?></label>
			</td>
		</tr>
	</tbody>
</table>

<div id="accdiv" style="display:<?php echo ($this -> get_option('acc') == "Y") ? 'block' : 'none'; ?>;">
	<table class="form-table">
		<tbody>
			<tr>
				<th><label for="accactive"><?php _e('Active Question', $this -> plugin_name); ?></label></th>
				<td>
					<input class="widefat" style="width:35px;" type="text" name="accactive" value="<?php echo ($this -> get_option('accactive') == "") ? "0" : $this -> get_option('accactive'); ?>" id="accactive" />
					<span class="howto"><?php _e('the question/item to open automatically. type the number. eg. "1". type "0" to leave all closed', $this -> plugin_name); ?></span>
				</td>
			</tr>
			<tr>
				<th><label for="acceventclick"><?php _e('Sliding Event', $this -> plugin_name); ?></label></th>
				<td>
					<label><input <?php echo ($this -> get_option('accevent') == "over") ? 'checked="checked"' : ''; ?> type="radio" name="accevent" value="over" id="acceventover" /> <?php _e('Mouse Over', $this -> plugin_name); ?></label>
					<label><input <?php echo ($this -> get_option('accevent') == "click") ? 'checked="checked"' : ''; ?> type="radio" name="accevent" value="click" id="acceventclick" /> <?php _e('Mouse Click', $this -> plugin_name); ?></label>
					<span class="howto"><?php _e('event that triggers the opening/closing action', $this -> plugin_name); ?></span>
				</td>
			</tr>
            <tr>
            	<th><label for="acccollapsible_Y"><?php _e('Collapsible Questions', $this -> plugin_name); ?></label></th>
                <td>
                	<label><input <?php echo ($this -> get_option('acccollapsible') == "Y") ? 'checked="checked"' : ''; ?> type="radio" name="acccollapsible" value="Y" id="acccollapsible_Y" /> <?php _e('Enabled', $this -> plugin_name); ?></label>
                    <label><input <?php echo ($this -> get_option('acccollapsible') == "N") ? 'checked="checked"' : ''; ?> type="radio" name="acccollapsible" value="N" id="acccollapsible_N" /> <?php _e('Disabled', $this -> plugin_name); ?></label>
                	<span class="howto"><?php _e('Enable this to allow questions to be closed when clicked again.', $this -> plugin_name); ?></span>
                </td>
            </tr>
		</tbody>
	</table>
</div>

<div id="clickdiv" style="display:<?php echo ($this -> get_option('acc') == "N") ? 'block' : 'none'; ?>;">
	<table class="form-table">
		<tbody>
			<tr>
				<th><label for="clickocN"><?php _e('Open/Close Answers', $this -> plugin_name); ?></label></th>
				<td>
					<label><input <?php echo ($this -> get_option('clickoc') == "N") ? 'checked="checked"' : ''; ?> type="radio" name="clickoc" value="N" id="clickocN" /> <?php _e('All answers open', $this -> plugin_name); ?></label><br/>
					<label><input <?php echo ($this -> get_option('clickoc') == "Y") ? 'checked="checked"' : ''; ?> type="radio" name="clickoc" value="Y" id="clickocY" /> <?php _e('Click to open/close answers', $this -> plugin_name); ?></label>
				</td>
			</tr>
		</tbody>
	</table>
</div>