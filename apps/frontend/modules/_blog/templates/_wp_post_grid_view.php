<?php /** @var $wp_post wpPost */ ?>

<div id="wp_post_<?= $wp_post->getId(); ?>_grid_view"
     data-id="<?= $wp_post->getId(); ?>" class="wp_post_grid_view">


  <?php if ($wp_post->getPostType() == 'search_result' || $wp_post->getPostType() == 'featured_items'): ?>
    <?php
      if ($wp_post->getPostType() == 'search_result')
      {
        $values = $wp_post->getPostMetaValue('_search_result');
        $routing = $values['cq_route'];
        $url = url_for($routing);
      }
      else
      {
        $url = array('sf_route' => 'wordpress_featured_items', 'sf_subject' => $wp_post);
      }
    ?>
    <h3><?= link_to($wp_post->getPostTitle(), $url); ?></h3>

    <blockquote style="height: 66px;"><?= !empty($excerpt) ? $excerpt : $wp_post->getPostExcerpt(270); ?></blockquote>
  <?php else: ?>
    <h3><?= !empty($title) ? $title : link_to_blog_post($wp_post); ?></h3>

    <blockquote><?= !empty($excerpt) ? $excerpt : $wp_post->getPostExcerpt(128); ?></blockquote>
    <?php
    echo link_to_blog_author($wp_post->getwpUser(), 'image', array(
      'link_to' => array('style' => 'float: left; margin-right: 10px;'),
      'image_tag' => array('width' => 35, 'height' => 35, 'style' => 'float: left; margin-right: 10px;')
    ));
    ?>
    posted by <br/>
    <?= link_to_blog_author($wp_post->getwpUser(), 'text'); ?>
  <?php endif; ?>
</div>
