<?php if (!empty($errors) && is_array($errors)) : ?>
	<p class="<?php echo $this -> pre; ?>error">
		<?php foreach ($errors as $err) : ?>
			&raquo;&nbsp;<?php echo $err ?><br/>
		<?php endforeach; ?>
	<p>
<?php endif; ?>