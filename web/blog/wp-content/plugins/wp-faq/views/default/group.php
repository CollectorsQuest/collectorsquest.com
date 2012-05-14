<?php if (!empty($group)) : ?>
	<?php $this -> render('questions-paging', array('group' => $group, 'questions' => $questions)); ?>
<?php endif; ?>