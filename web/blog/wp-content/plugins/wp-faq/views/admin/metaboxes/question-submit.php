<div class="submitbox" id="submitpost">
	<div id="minor-publishing">
		<div id="misc-publishing-actions">
			<div class="misc-pub-section">
				<b><?php _e('Approved', $this -> plugin_name); ?>:</b>
				<?php $approved = array("Y" => __('Yes', $this -> plugin_name), "N" => __('No', $this -> plugin_name)); ?>
				<?php echo $wpfaqForm -> radio('wpfaqQuestion.approved', $approved, array('separator' => false, 'default' => "N")); ?>
			</div>
            
            <?php $email = $wpfaqHtml -> field_value('wpfaqQuestion.email'); ?>
            <?php if (!empty($email)) : ?>
                <div class="misc-pub-section misc-pub-section-last">
                    <strong><?php _e('Notify User:', $this -> plugin_name); ?></strong>
                    <?php $notify = array("Y" => __('Yes', $this -> plugin_name), "N" => __('No', $this -> plugin_name)); ?>
                    <?php echo $wpfaqForm -> radio('wpfaqQuestion.notifyuser', $notify, array('separator' => false, 'default' => (($this -> get_option('notifywhenanswered') == "Y") ? 'Y' : 'N'))); ?>
                    
                    <p>
                    	<strong><?php _e('User Email:', $this -> plugin_name); ?></strong>
                    	<span class="howto"><?php echo $email; ?></span>
                    </p>
                </div>
                
                <?php echo $wpfaqForm -> hidden('wpfaqQuestion.email'); ?>
            <?php endif; ?>
		</div>
		<br class="clear" />
	</div>
	<div id="major-publishing-actions">
		<div id="publishing-action">
			<input class="button-primary" type="submit" name="save" value="<?php _e('Save Question', $this -> plugin_name); ?>" />
			<br class="clear" />
		</div>
		<br class="clear" />
	</div>
</div>