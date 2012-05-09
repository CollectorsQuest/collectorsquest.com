<p>
	<?php _e('Thank you for submitting your question:', $this -> plugin_name); ?><br/>
    <?php echo wpautop(stripslashes($question -> question)); ?>
</p>

<p>
	<?php _e('The administrator will answer and approve the question shortly.', $this -> plugin_name); ?><br/>
    <?php _e('You may be notified by email when the question has been answered/changed.', $this -> plugin_name); ?>
</p>