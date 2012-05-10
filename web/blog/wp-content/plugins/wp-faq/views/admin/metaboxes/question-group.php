<?php $wpfaqDb -> model = $wpfaqGroup -> model; ?>
<?php if ($groups = $wpfaqDb -> find_all()) : ?>
	<?php $select = array(); ?>
	<?php foreach ($groups as $group) : ?>
		<?php $wpfaqDb -> model = $wpfaqQuestion -> model; ?>
		<?php $questionscount = $wpfaqDb -> count(array('group_id' => $group -> id)); ?>
		<?php $add = (!empty($questionscount)) ? ' (' . $questionscount . ' ' . __('questions', $this -> plugin_name) . ')' : ''; ?>
		<?php $status = ($group -> active == "Y") ? __('Active', $this -> plugin_name) : __('Inactive', $this -> plugin_name); ?>
		<?php $select[$group -> id] = $group -> name . ' (' . $status . ')' . $add; ?>
	<?php endforeach; ?>
	<?php $wpfaqDb -> model = $wpfaqGroup -> model; ?>
	<?php echo $wpfaqForm -> select('wpfaqQuestion.group_id', $select); ?>
<?php endif; ?>