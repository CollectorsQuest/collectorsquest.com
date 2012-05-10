<?php $wpfaqDb -> model = $wpfaqGroup -> model; ?>
<?php if ($groups = $wpfaqDb -> find_all(array('active' => "Y", 'pp' => "!= 'none'"), false, array('order', "ASC"))) : ?>
	<?php echo $atts['before_title']; ?><?php echo $options['title']; ?><?php echo $atts['after_title']; ?>

	<ul>	
		<?php foreach ($groups as $group) : ?>
			<?php if (!empty($group -> pp_id) && $post = get_post($group -> pp_id)) : ?>
				<li><?php echo $wpfaqHtml -> link($group -> name, get_permalink($post -> ID), array('title' => $post -> post_title)); ?></li>	
			<?php endif; ?>
		<?php endforeach; ?>
	</ul>
<?php endif; ?>