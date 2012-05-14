<?php if (!empty($error)) : ?>
	<div>
		<p class="<?php echo $this -> pre; ?>error">
			<small><?php echo $error; ?></small>
		</p>
	</div>
<?php endif; ?>