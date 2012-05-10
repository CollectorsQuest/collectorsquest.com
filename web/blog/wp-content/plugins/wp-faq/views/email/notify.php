<p>
	<?php _e('The question you asked previously has been answered by the administrator.', $this -> plugin_name); ?>
</p>

<p>
	<strong><?php _e('Question:', $this -> plugin_name); ?></strong><br/>
    <?php echo stripslashes($question -> question); ?>
</p>

<p>
	<strong><?php _e('Answer:', $this -> plugin_name); ?></strong><br/>
    <?php echo wpautop(stripslashes($question -> answer)); ?>
</p>