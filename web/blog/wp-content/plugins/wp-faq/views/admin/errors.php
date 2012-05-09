<?php if (!empty($errors)) : ?>
	<p style="color:red;">
		<?php foreach ($errors as $err) : ?>
			&raquo;&nbsp;<?php _e($err); ?><br/>
		<?php endforeach; ?>
	</p>
<?php endif; ?>