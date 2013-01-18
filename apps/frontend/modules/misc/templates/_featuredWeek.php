<?php /** @var $wp_post wpPost */ ?>

<div id="wp_post_<?= $wp_post->getId(); ?>_featured"
     data-id="<?= $wp_post->getId(); ?>" class="wp_post_featured">

  <?php echo image_tag_wp_post($wp_post, '140x140'); ?>

  <h3><?= !empty($title) ? $title : link_to($wp_post->getPostTitle(), array('sf_route' => 'wordpress_featured_week', 'sf_subject' => $wp_post)); ?></h3>

  <?= !empty($excerpt) ? $excerpt : $wp_post->getPostExcerpt(270); ?>

</div>

