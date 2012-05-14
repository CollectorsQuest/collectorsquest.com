<div class="wrap">
	<h2><?php _e('Group', $this -> plugin_name); ?>: <?php echo $group -> name; ?></h2>
	
	<div class="subsubsub" style="float:left;"><?php echo $wpfaqHtml -> link(__('&larr; All Groups', $this -> plugin_name), '?page=' . $this -> sections -> groups); ?></div>
	
	<div class="tablenav">
		<div class="actions alignleft">
			<?php if (!empty($questions)) : ?>
				<?php echo $wpfaqHtml -> link(__('Order Questions', $this -> plugin_name), '?page=' . $this -> sections -> questions . '&amp;method=order&amp;group_id=' . $group -> id, array('class' => "button")); ?>
			<?php endif; ?>
			<?php echo $wpfaqHtml -> link(__('Change Group', $this -> plugin_name), '?page=' . $this -> sections -> groups_save . '&amp;id=' . $group -> id, array('class' => "button")); ?>
			<?php echo $wpfaqHtml -> link(__('Delete Group', $this -> plugin_name), '?page=' . $this -> sections -> groups . '&amp;method=delete&amp;id=' . $group -> id, array('class' => "button button-highlighted", 'onclick' => "if (!confirm('" . __('Are you sure you wish to permanently remove this group?', $this -> plugin_name) . "')) { return false; }")); ?>
		</div>
	</div>
	
	<table class="widefat">
		<thead>
			<tr class="<?php echo $class = (empty($class)) ? 'alternate' : ''; ?>">
				<th><?php _e('Field', $this -> plugin_name); ?></th>
				<th><?php _e('Value', $this -> plugin_name); ?></th>
			</tr>
		</thead>
		<tfoot>
			<tr class="<?php echo $class = (empty($class)) ? 'alternate' : ''; ?>">
				<th><?php _e('Field', $this -> plugin_name); ?></th>
				<th><?php _e('Value', $this -> plugin_name); ?></th>
			</tr>
		</tfoot>
		<tbody>
			<tr class="<?php echo $class = (empty($class)) ? 'alternate' : ''; ?>">
				<th><?php _e('Name', $this -> plugin_name); ?></th>
				<td><?php echo $group -> name; ?></td>
			</tr>
			<tr class="<?php echo $class = (empty($class)) ? 'alternate' : ''; ?>">
				<th><?php _e('Status', $this -> plugin_name); ?></th>
				<td>
					<span class="<?php echo $this -> pre; ?><?php echo (!empty($group -> active) && $group -> active == "Y") ? 'grn">' . __('Active', $this -> plugin_name) : 'red">' . __('Inactive', $this -> plugin_name); ?></span>
				</td>
			</tr>
			<tr class="<?php echo $class = (empty($class)) ? 'alternate' : ''; ?>">
				<th><?php _e('Search Box', $this -> plugin_name); ?></th>
				<td><?php echo (!empty($group -> searchbox) && $group -> searchbox == "Y") ? __('Yes', $this -> plugin_name) : __('No', $this -> plugin_name); ?></td>
			</tr>
			<tr class="<?php echo $class = (empty($class)) ? 'alternate' : ''; ?>">
				<th><?php _e('Ask Box', $this -> plugin_name); ?></th>
				<td><?php echo (!empty($group -> askbox) && $group -> askbox == "Y") ? __('Yes', $this -> plugin_name) : __('No', $this -> plugin_name); ?></td>
			</tr>
			<tr class="<?php echo $class = (empty($class)) ? 'alternate' : ''; ?>">
				<th><?php _e('Created', $this -> plugin_name); ?></th>
				<td><?php echo $group -> created; ?></td>
			</tr>
			<tr class="<?php echo $class = (empty($class)) ? 'alternate' : ''; ?>">
				<th><?php _e('Modified', $this -> plugin_name); ?></th>
				<td><?php echo $group -> modified; ?></td>
			</tr>
			<tr class="<?php echo $class = (empty($class)) ? 'alternate' : ''; ?>">
				<th><?php _e('Total Questions', $this -> plugin_name); ?></th>
				<td>
					<?php $wpfaqDb -> model = $wpfaqQuestion -> model; ?>
					<?php echo $wpfaqDb -> count(array('group_id' => $group -> id)); ?> <?php _e('questions', $this -> plugin_name); ?>
				</td>
			</tr>
			<tr class="<?php echo $class = (empty($class)) ? 'alternate' : ''; ?>">
				<th><?php _e('Post/Page', $this -> plugin_name); ?></th>
				<td>
					<?php if (empty($group -> pp) || $group -> pp == "none") : ?> 	
						<?php _e('none', $this -> plugin_name); ?>
					<?php else : ?>
						<?php echo ucfirst($group -> pp); ?> - <?php echo $wpfaqHtml -> link($group -> pp_title, get_permalink($group -> pp_id), array('target' => "_blank", 'title' => $group -> pp_title)); ?>
					<?php endif; ?>
				</td>					
			</tr>
		</tbody>
	</table>
	
	<h3 id="<?php echo $this -> pre; ?>groupquestions<?php echo $group -> id; ?>"><?php _e('Questions', $this -> plugin_name); ?> <?php echo $wpfaqHtml -> link(__('Add New', $this -> plugin_name), '?page=' . $this -> sections -> questions_save . '&amp;group_id=' . $group -> id, array('class' => "button add-new-h2")); ?></h3>
	<?php $this -> render('questions' . DS . 'loop', array('questions' => $questions, 'paginate' => $paginate), 'admin', true); ?>
</div>