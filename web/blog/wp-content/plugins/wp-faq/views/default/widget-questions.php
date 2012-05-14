<?php if (!empty($questions)) : ?>
	<?php echo $atts['before_title']; ?><?php echo $options['title']; ?><?php echo $atts['after_title']; ?>
    
    <ul class="<?php echo $this -> pre; ?>questionswidget">
    	<?php foreach ($questions as $question) : ?>
        	<li><a href="<?php echo get_permalink($question -> pp_id); ?>" title="<?php echo esc_attr($question -> question); ?>"><?php echo stripslashes($question -> question); ?></a></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>