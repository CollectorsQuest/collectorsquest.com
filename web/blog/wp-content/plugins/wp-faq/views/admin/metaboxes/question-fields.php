<?php $fields = $wpfaqQuestion -> data -> fields; ?>
<?php if (!empty($fields) && is_array($fields)) : ?>
	<table class="widefat">
		<tbody>
			<?php $class = ''; ?>
			<?php foreach ($fields as $field) : ?>
				<tr class="<?php echo $class = (empty($class)) ? 'alternate' : ''; ?>">
					<th><?php echo $field['title']; ?></th>
					<td><?php echo nl2br($field['value']); ?></td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
<?php else : ?>
	<p class="<?php echo $this -> pre; ?>error"><?php _e('No fields are available for this question.', $this -> plugin_name); ?></p>
<?php endif; ?>