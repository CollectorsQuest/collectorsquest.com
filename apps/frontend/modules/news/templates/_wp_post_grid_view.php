<?php /** @var $wp_post wpPost */ ?>

<div id="wp_post_<?= $wp_post->getId(); ?>_grid_view"
     data-id="<?= $wp_post->getId(); ?>" class="wp_post_grid_view">

  <h3><?php echo link_to_blog_post($wp_post); ?></h3>
  <blockquote><?= !empty($excerpt) ? $excerpt : $wp_post->getPostExcerpt(128); ?></blockquote>
  <?= link_to_blog_author($wp_post->getwpUser(), 'image', array('width' => 35, 'height' => 35, 'style' => 'float: left; margin-right: 10px;')); ?>
  posted by <br/>
  <?= link_to_blog_author($wp_post->getwpUser(), 'text'); ?>
</div>
