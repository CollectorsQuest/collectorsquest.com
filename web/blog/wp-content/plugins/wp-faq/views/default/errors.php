<?php if (!empty($errors) && is_array($errors)) : ?>
	<p>
		<?php foreach ($errors as $err) : ?>
			<small class="<?php echo $this -> pre; ?>error"><?php echo $err; ?></small><br/>
		<?php endforeach; ?>
	</p>
<?php endif; ?>