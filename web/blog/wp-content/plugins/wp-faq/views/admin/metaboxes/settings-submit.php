<div class="submitbox" id="submitpost">
	<div id="minor-publishing">
		<div id="misc-publishing-actions">
			<div class="misc-pub-section">
				<?php echo $wpfaqHtml -> link(__('Order/Sort Groups', $this -> plugin_name), '?page=' . $this -> sections -> groups . '&amp;method=order', array('title' => __('Order/sort your FAQ groups', $this -> plugin_name))); ?>
			</div>
			<div class="misc-pub-section misc-pub-section-last">
				<?php echo $wpfaqHtml -> link(__('Resave Posts/Pages', $this -> plugin_name), '?page=' . $this -> sections -> settings . '&amp;method=reset', array('title' => __('Resave Group and Question Posts/Pages', $this -> plugin_name), 'onclick' => "if (!confirm('" . __('Are you sure you wish to check and reset all pages/posts created by FAQ groups?', $this -> plugin_name) . "')) { return false; }")); ?>
			</div>
		</div>
	</div>
	<div id="major-publishing-actions">
		<div id="publishing-action">
			<input class="button-primary" type="submit" name="save" value="<?php _e('Save Configuration', $this -> plugin_name); ?>" />
			<br class="clear" />
		</div>
		<br class="clear" />
	</div>
</div>